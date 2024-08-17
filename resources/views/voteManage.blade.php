<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard • Vote Manage</title>
  @include('include/link')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
  <style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        background-color:#007bff;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  @include('include/navbar')
  @include('include/sidebar')
  <div class="content-wrapper">
    <section class="content">
        <div class="container mt-5">
            <div class="card" id="card-modal">
                <div class="p-3">
                    <table id="myTable" class="table table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Vote Topic Name</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-content">
                            {{-- table-content --}}
                        </tbody>
                    </table>
                    <button class="btn btn-success"  data-toggle="modal" data-target="#addVote"><i class="fas fa-solid fa-plus"></i>  Add new</button>
                </div>
                <div class="modal fade" id="addVote" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 id="grafik-title" class="modal-title" id="modalCandidate1Label">Add New Vote</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <form id="add-vote">
                                    <div class="form-group">
                                        <label for="voteName">Vote Name</label>
                                        <input type="text" class="form-control" id="voteName" name="vote_name">
                                        <label for="voteName">Vote Start</label>
                                        <input type="datetime-local" class="form-control" id="voteStart">
                                        <label for="voteName">Vote Ends</label>
                                        <input type="datetime-local" class="form-control" id="voteEnds">
                                    </div>
                
                                    <div class="form-group" id="teamVote">

                                    </div>
                                    
                                    <label for="topicInfo">add team</label>
                                    <div>
                                        <div id="form-add-team" class="btn btn-success rounded-circle btn-sm float-left mb-2"><i class="fas fa-solid fa-plus"></i></div>
                                    </div>
                                    <br><br>
                                    <button id="saveVote" class="btn btn-primary float-right">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </section>
  </div>
  @include('include/footer')
</div>
@include('include/script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{asset('storage/js/logincheck.js')}}"></script>
<script>
    $(document).ready(function(){
        if (sessionStorage.getItem('session')==null || sessionStorage.getItem('session')!=1 ) {
            return window.location = window.location.origin+'/login';
        }
        sessionCheck(sessionStorage.getItem('id'));
        loginCheck(sessionStorage.getItem('id'));
        var team = 1;
        $('#form-add-team').on('click',function(){
            if (team == 5) {
                alert('cant add new team again, max team is 4')
                return;
            }
            else{
                $('#teamVote').append(
                    '<div class="form-group" id="dataTeam-'+team+'">'+
                        '<input type="text" class="form-control" id="teamName-'+team+'" name="team_name" placeholder="Team Name" style="border:none">' +
                        '<select multiple class="form-control" id="vote-candidate-'+team+'"></select>' +
    
                        '<input type="text" class="form-control mt-1" id="visi-'+team+'" name="team_name" placeholder="Visi Team">' +
                        '<input type="text" class="form-control mt-1" id="misi-'+team+'" name="team_name" placeholder="Misi Team">' +
    
                        '<button class="btn btn-success mt-2" id="banner-upload-'+team+'"><i class="fas fa-solid fa-file-import"></i>  upload image banner</button>'+
                        '<input type="file" id="banner-file-'+team+'" class="form-control d-none" id="teamBanner-'+team+'" name="team_banner">' +
                    '</div>'
                )
                $('#banner-upload-'+team+'').click(function(event){
                    event.preventDefault();
                    var id = team-1;
                    $('#banner-file-'+id+'').click();
                })

                $('#vote-candidate-'+team+'').select2({
                    placeholder: 'Select candidate', // Teks placeholder
                    allowClear: true, // Memungkinkan pengguna menghapus pilihan
                    maximumSelectionLength: 2 
                });
                $.ajax({
                    url: origin+"/api/data",
                    method:'GET',
                    success: function (response) {
                        var data = response.data;
                        data.forEach(member => {
                        $('#vote-candidate-'+team+'').append(
                            '<option value="'+member.id+'">'+member.data_anggota.nama+'</option>'
                        )
                    });
                    team++;
                    }
                });
            }
        });
        $('#saveVote').on('click',function(event){
            event.preventDefault();
            var vote_name = $('#voteName').val(); 
            var voteStart = new Date($('#voteStart').val()).getTime();
            var voteEnds = new Date($('#voteEnds').val()).getTime();
            
            $.ajax({
                url: "/api/vote",
                method: 'POST',
                data: {
                    "description": vote_name,
                    "voteStart":voteStart,
                    "voteEnds":voteEnds
                },
                success:function(response){
                    var loop = 1;
                    $('#teamVote div').each(function(ea) {
                        var dataArray = new FormData();
                        var dataTeam = $(this).attr('id');
                        dataArray.append('id_vote',response.data.id)
                        $('#'+dataTeam+' input').each(function(index) {
                            var alter = ['name','','visi','misi','banner']
                            var data = $(this).val();
                            if (index!=1) {
                                dataArray.append(alter[index],data)
                            }
                            if (index==4) {
                                dataArray.append(alter[index],$(this).prop('files')[0]) ;
                            }
                        });
                        var bool = false; 
                        $.ajax({
                            url: "/api/team",
                            method: 'POST',
                            data: dataArray,
                            processData: false,
                            contentType: false,
                            success:function(response){
                                var datax = response.data;
                                var candidate = $('#teamVote select')[ea];
                                var elementId = $(candidate).attr('id');
                                var user_id = $(candidate).val(); 
                                if (user_id.length == 0) {
                                    return;
                                }
                                if (elementId != undefined) {
                                    user_id.forEach(function(item){
                                        $.ajax({
                                            url: "/api/candidate",
                                            method: 'POST',
                                            data: {
                                                "id_team": datax.id,
                                                "user_id": item,
                                            },
                                            success:function(response){
                                                if($('#teamVote select').length==loop){
                                                    window.location.reload();
                                                }
                                                loop++;
                                            }
                                        });
                                    })
                                }
                            }
                        });
                    });
                }
            });
        });
        $.ajax({
            url: "/api/vote",
            method: "GET", // First change type to method here
            success: function(response) {
                var data = response.data

                data.forEach(vote => {
                    var dateStart = new Date(vote.voteStart);
                    var dateEnd = new Date(vote.voteEnds);
                    function convert(time) {  
                        var year = time.getFullYear();
                        var month = ('0' + (time.getMonth() + 1)).slice(-2); // Bulan dimulai dari 0
                        var day = ('0' + time.getDate()).slice(-2);
                        var hours = ('0' + time.getHours()).slice(-2);
                        var minutes = ('0' + time.getMinutes()).slice(-2);
    
                        return `${year}-${month}-${day}T${hours}:${minutes}`;
                    }
                    vote.voteEnds = convert(dateEnd);
                    vote.voteStart = convert(dateStart);
                    
                    $('#table-content').append(
                        '<tr id="tr-del-'+vote.id+'">'+
                            '<td class="text-primary">'+vote.description+'</td>'+
                            '<td class="text-center">'+
                                '<button class="btn btn-warning m-1" data-toggle="modal" data-target="#editTopic-'+vote.id+'"><i class="nav-icon fas fa-pen"></i></button>'+
                                '<button id="del-vote-'+vote.id+'" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>'+
                            '</td>'+
                        '</tr>'
                    );
                    $('#card-modal').append(
                        '<div class="modal fade" id="editTopic-'+vote.id+'" tabindex="-1" role="dialog" aria-hidden="true">'+
                            '<div class="modal-dialog modal-lg" role="document">'+
                                '<div class="modal-content">'+
                                    '<div class="modal-header bg-warning">'+
                                        '<h5 id="grafik-title" class="modal-title" id="modalCandidate1Label">Edit vote Topic</h5>'+
                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                                            '<span aria-hidden="true">&times;</span>'+
                                        '</button>'+
                                    '</div>'+
                                    '<div class="card-body">'+
                                        '<form id="edit-topicForm-'+vote.id+'">'+
                                            '<div class="form-group">'+
                                                '<label for="topicName">Topic Name</label>'+
                                                '<input type="text" class="form-control" id="voteName-'+vote.id+'" name="topic_name" value="'+vote.description+'"">'+
                                                '<label for="voteName">Vote Start</label>'+
                                                '<input type="datetime-local" class="form-control" id="voteStart-'+vote.id+'" value ="'+vote.voteStart+'">'+
                                                '<label for="voteName">Vote Ends</label>'+
                                                '<input type="datetime-local" class="form-control" id="voteEnds-'+vote.id+'" value="'+vote.voteEnds+'"">'+
                                            '</div>'+

                                            '<div class="form-group" id="teamCandidate-'+vote.id+'">' +
                                            '</div>' +

                                            '<label for="topicInfo">Option:</label>'+
                                            '<div>'+
                                                '<div id="form-add-team-'+vote.id+'" class="btn btn-success rounded-circle btn-sm float-left mb-2"><i class="fas fa-solid fa-plus"></i></div>'+
                                            '</div>'+
                                            '<br><br>'+
                                            '<button id="edit-vote-'+vote.id+'" class="btn btn-warning float-right">Edit</button>'+
                                            '</form>'+
                                        '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                    
                    $('#edit-vote-'+vote.id).on('click',function(event){
                        // var team = vote.data_team.length;                  
                        var team = vote.data_team;     
                        var timeStart = $('#voteStart-'+vote.id+'').val();
                        var timeEnds = $('#voteEnds-'+vote.id+'').val();

                        var start = new Date(timeStart);
                        var ends = new Date(timeEnds);

                        event.preventDefault();
                        $.ajax({
                            url: "/api/vote/"+vote.id,
                            method: 'PUT',
                            data: {
                                "description": $('#voteName-'+vote.id+'').val(),
                                "voteStart": start.getTime(),
                                "voteEnds": ends.getTime()
                            },
                        });
                        var loop = 1;
                        $('#teamCandidate-'+vote.id+' div').each(function(item) {
                            var editArray = new FormData();
                            var editTeam = $(this).attr('id');
                            $('#'+editTeam+' input').each(function(index){
                                var keys = ['name', '', 'visi', 'misi', 'banner_image'];
                                var data = $(this).val();
                                // Append data to FormData based on index
                                if (index !== 1) {
                                    editArray.append(keys[index], data);
                                }
                                // Handle file input
                                if (index === 4) {
                                    var file = $(this).prop('files')[0];
                                    if (file) {
                                        editArray.append(keys[index], file);
                                    }
                                }
                            });
                            $.ajax({
                                url: "/api/team/" + vote.data_team[item].id,
                                data: editArray,
                                method: 'POST',
                                processData: false,
                                contentType: false,
                                success:function(response){
                                    var id = response.data.id;
                                    $('#'+editTeam+' select').each(function(index){
                                        var tim_value = $(this).val();
                                        $.ajax({
                                            url: "/api/candidate/"+id,
                                            data: {
                                                'user_id':tim_value
                                            },
                                            method: 'PUT',
                                            success:function(response){
                                                if ($('#teamCandidate-'+vote.id+' div').length == loop) {
                                                    window.location.reload();
                                                }
                                                loop++;
                                            }
                                        });
                                    });
                                }
                            });
                        });
                    });

                    $('#form-add-team-'+vote.id).on('click',function(){
                        if (team == 4) {
                            alert('cant add new team again, max team is 4')
                            return;
                        }
                        else{
                            $('#teamCandidate-'+vote.id).append(
                                '<div class="form-group" id="dataTeam-'+team+'">'+
                                    '<input type="text" class="form-control" id="teamName-'+team+'" name="team_name" placeholder="Team Name" style="border:none">' +
                                    '<select multiple class="form-control" id="vote-candidate-'+team+'"></select>' +
                
                                    '<input type="text" class="form-control mt-1" id="visi-'+team+'" name="team_name" placeholder="Visi Team">' +
                                    '<input type="text" class="form-control mt-1" id="misi-'+team+'" name="team_name" placeholder="Misi Team">' +
                
                                    '<button class="btn btn-success mt-2" id="banner-upload-'+team+'"><i class="fas fa-solid fa-file-import"></i>  upload image banner</button>'+
                                    '<input type="file" id="banner-file-'+team+'" class="form-control d-none" id="teamBanner-'+team+'" name="team_banner">' +
                                '</div>'

                                // '<input type="text" class="form-control" id="teamName-'+team+'" name="team_name" placeholder="Team Name" style="border:none">' +
                                // '<select multiple class="form-control" id="new-candidate-'+team+'"></select>' 
                            )

                            $('#new-candidate-'+team+'').select2({
                                placeholder: 'Select candidate', // Teks placeholder
                                allowClear: true, // Memungkinkan pengguna menghapus pilihan
                                maximumSelectionLength: 2 
                            });
                            $.ajax({
                                url: origin+"/api/data",
                                method:'GET',
                                success: function (response) {
                                    var data = response.data;
                                    data.forEach(member => {
                                    $('#new-candidate-'+team+'').append(
                                        '<option value="'+member.id+'">'+member.data_anggota.nama+'</option>'
                                    )
                                });
                                team++;
                                }
                            });
                        }
                    });


                    $('#del-vote-'+vote.id).on('click',function(event){
                        $.ajax({
                            url: "/api/vote/"+vote.id,
                            method: "DELETE",
                            success:function(response){
                                window.location.reload();
                            }
                        });
                    })
                    vote.data_team.forEach(team => {
                        $('#teamCandidate-'+vote.id+'').append(
                            '<div class="form-group" id="dataTeam-'+team.id+'">'+
                                '<input type="text" class="form-control" id="teamNameEdit-'+team.id+'" name="team_name" placeholder="Team Name" value="'+team.name+'" style="border:none">' +
                                '<select multiple class="form-control" id="modalEdit-candidate-'+team.id+'" name="members[]"></select>'+

                                '<input type="text" class="form-control mt-1" id="visiEdit-'+team.id+'" name="team_name" value="'+team.visi+'">' +
                                '<input type="text" class="form-control mt-1" id="misiEdit-'+team.id+'" name="team_name" value="'+team.misi+'">' +
            
                                '<button class="btn btn-success mt-2" id="banner-edit-'+team.id+'"><i class="fas fa-solid fa-file-import"></i>  upload image banner</button>'+
                                '<input type="file" id="edit-banner-file-'+team.id+'" class="form-control d-none" id="teamBanner-edit-'+team.id+'" name="team_banner">' +
                            '</div>'

                            // '<input type="text" class="form-control" id="teamName" name="team_name" value="'+team.name+'" style="border:none">'+
                            // '<select multiple class="form-control" id="modalEdit-candidate-'+team.id+'" name="members[]">' +
                            // '</select>' 
                        );
                        $('#banner-edit-'+team.id+'').click(function(event){
                                event.preventDefault();
                                var id = team.id;
                                $('#edit-banner-file-'+id+'').click();
                            })
                        $('#modalEdit-candidate-'+team.id+'').select2({
                            placeholder: 'Select candidate', // Teks placeholder
                            allowClear: true, // Memungkinkan pengguna menghapus pilihan
                            maximumSelectionLength: 2 
                        });
                        $.ajax({
                            url: origin+"/api/data",
                            method:'GET',
                            success: function (response) {
                                var data = response.data;
                                data.forEach(member => {
                                    $('#modalEdit-candidate-'+team.id+'').append(
                                        '<option value="'+member.id+'">'+member.data_anggota.nama+'</option>'
                                    )
                                    team.data_candidate.forEach(candidate => {
                                        if (candidate.user_id == member.id) {
                                            $('#modalEdit-candidate-'+team.id+' option[value="'+member.id+'"]').remove();
                                            $('#modalEdit-candidate-'+team.id+'').append(
                                                '<option value="'+member.id+'"selected>'+member.data_anggota.nama+'</option>'
                                            )
                                        }
                                    });
                                });
                            }
                        });
                    });
                });
            }
        });      
        $('#form-topic').on('click', function() {
            $.ajax({
                url: "/api/topic/",
                method: "post", // First change type to method here   
                data:$("#topicForm").serialize(), 
                success: function(response) {
                    if (response.success != false) {
                        window.location.reload();
                    }
                }
            });
        });
    });
</script>
</body>
</html>
