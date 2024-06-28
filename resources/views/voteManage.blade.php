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
                    '<input type="text" class="form-control" id="teamName-'+team+'" name="team_name" placeholder="Team Name" style="border:none">' +
                    '<select multiple class="form-control" id="vote-candidate-'+team+'"></select>' 
                    // '<button class="btn btn-success mt-2" id="banner-upload-'+team+'"><i class="fas fa-solid fa-file-import"></i>  upload image banner</button>'+
                    // '<input type="file" id="banner-file-'+team+'" class="form-control d-none" id="teamBanner-'+team+'" name="team_banner">' 
                )
                // $('#banner-upload-'+team+'').click(function(event){
                //     event.preventDefault();
                //     var id = team-1
                //     $('#banner-file-'+id+'').click();
                // })
                // $('#banner-file-'+team+'').change(function(){
                //     var image = $('#banner-file-'+team+'').prop('files')[0];
                //     var csv_file = new FormData();

                //     csv_file.append('image',image);

                //     console.log('tets');
                    // $.ajax({
                    //     url: origin+"/api/csv",
                    //     method: 'POST',
                    //     data: csv_file,
                    //     processData: false,
                    //     contentType: false,
                    // });
                // })

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
            var loopCount = 0;
            var vote_name = $('#voteName').val(); 

            $.ajax({
                url: "/api/vote",
                method: 'POST',
                data: {
                    "description": vote_name,
                },
                success:function(response){
                    $('#teamVote input').each(function() {
                        var elementId = $(this).attr('id'); 
                        var team = $(this).val();
                        if (!team) {
                            return;
                        }
                        if (elementId != undefined) {
                            $.ajax({
                                url: "/api/team",
                                method: 'POST',
                                data: {
                                    "id_vote": response.data.id,
                                    "name": team,
                                },
                                success:function(response){
                                    var candidate = $('#teamVote select')[loopCount];
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
                                                    "id_team": response.data.id,
                                                    "user_id": item,
                                                }
                                            });
                                        })
                                    }
                                    loopCount++;
                                }
                            });
                        }
                    });
                }
                // alert('success save data vote');
            });
        });
        $.ajax({
            url: "/api/vote",
            method: "GET", // First change type to method here
            success: function(response) {
                var data = response.data
                data.forEach(vote => {
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
                    var team = vote.data_team.length;                  
                    $('#edit-vote-'+vote.id).on('click',function(event){
                        event.preventDefault();
                        var loopCount = 0;
                        var vote_name = $('#voteName-'+vote.id).val();
                        var array_candidate = [];
                        // console.log(vote_name);
                        $('#teamCandidate-'+vote.id+' input').each(function(item) {
                            var value = $(this).val();
                            var elementId = $(this).attr('id');
                            if (elementId != undefined) {
                                var candidate = $('#teamCandidate-'+vote.id+' select')[loopCount];
                                var user_id = $(candidate).val();
                                
                                var object = {};
                                object.team = value;
                                object.user_id = user_id;
                                array_candidate.push(object)
                                loopCount++;
                            }
                        });
                        $.ajax({
                            url: "/api/vote/"+vote.id,
                            method: 'PUT',
                            data: {
                                "description": vote_name,
                            },
                            success:function(response){
                                array_candidate.forEach((data,index)=>{
                                    $.ajax({
                                        url: "/api/team/"+vote.data_team[index].id,
                                        method: 'PUT',
                                        data: {
                                            "id_vote": response.data.id,
                                            "name":array_candidate[index].team,
                                        },
                                        success:function(response){
                                            // vote.data_team[index].data_candidate.forEach((old,old_index)=>{
                                                // $.ajax({
                                                //     url: "/api/candidate/"+old.id,
                                                //     method: 'PUT',
                                                //     data: {
                                                //         "id_team": response.data.id,
                                                //         "user_id":array_candidate[index].user_id[old_index]
                                                //     },
                                                // });
                                            // })
                                            var candidate_old = [];
                                            for (var i = 0; i < vote.data_team[index].data_candidate.length; i++) {
                                                candidate_old.push(vote.data_team[index].data_candidate[i].user_id);
                                            }
                                            var result = array_candidate[index].user_id.filter(function(item) {
                                                return candidate_old.includes(item);
                                            });

                                            console.log(result);
                                            // console.log(array_candidate[index].user_id);
                                            // console.log(candidate_old);
                                        }
                                    });
                                })
                            }
                        });
                    });

                    $('#form-add-team-'+vote.id).on('click',function(){
                        if (team == 4) {
                            alert('cant add new team again, max team is 4')
                            return;
                        }
                        else{
                            $('#teamCandidate-'+vote.id).append(
                                '<input type="text" class="form-control" id="teamName-'+team+'" name="team_name" placeholder="Team Name" style="border:none">' +
                                '<select multiple class="form-control" id="new-candidate-'+team+'"></select>' 
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
                                alert('vote delete successfully')
                            }
                        });
                    })
                    vote.data_team.forEach(team => {
                        $('#teamCandidate-'+vote.id+'').append(
                            '<input type="text" class="form-control" id="teamName" name="team_name" value="'+team.name+'"style="border:none">'+
                            '<select multiple class="form-control" id="modalEdit-candidate-'+team.id+'" name="members[]">' +
                            '</select>' 
                        );
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
