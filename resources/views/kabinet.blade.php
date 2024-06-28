<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard • Cabinet</title>
  @include('include/link')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
  <style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        background-color:#007bff
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
        <div class="container-fluid py-3">
            <div class="card" id="table-divisi" style="display: none">
                <div class="table-responsive-sm p-3 p-3">
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th class="bg-primary">DEPARTEMENT</th>
                                <th class="text-center bg-primary"></th>
                            </tr>
                        </thead>
                        <tbody id="table-content-divisi">
                            {{-- content tabel divisi --}}
                        </tbody>
                    </table>

                    <button style="display: none" id="new_data_divisi" class="btn btn-success"  data-toggle="modal" data-target="#addDivisi"><i class="fas fa-solid fa-plus"></i>  Add new</button>
                </div>
            </div>
            <div class="card" id="table-program" style="display: none">
                <div class="table-responsive-sm p-3">
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th class="bg-primary">CURSE WORK</th>
                                <th class="text-center bg-primary"></th>
                            </tr>
                        </thead>
                        <tbody id="table-content-program">
                            {{-- content table program --}}
                        </tbody>
                    </table>

                    <button style="display: none" class="btn btn-success"  id="new_data_program" data-toggle="modal" data-target="#addProgram"><i class="fas fa-solid fa-plus"></i>  Add new</button>
                </div>
            </div>
            <div id="modal-save">
                {{-- modal divisi --}}
                <div class="modal fade" id="addDivisi" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 id="grafik-title" class="modal-title">Tambah Divisi Baru</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <form id="divisiForm">
                                    <div class="form-group">
                                        <label for="divisi">Nama Divisi</label>
                                        <input type="text" class="form-control" id="divisiName" name="divisi_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="leader">Leader</label>
                                        <select class="form-control" id="divisi_leader" name="leader" required>
                                            <option value="" disabled selected>select</option>
                                            <!-- Tambahkan lebih banyak anggota sesuai kebutuhan -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="memberList">Pilih Anggota</label><br>
                                        <select multiple class="form-control" id="divisi_memberList" name="members[]">
                                            <!-- Tambahkan lebih banyak anggota sesuai kebutuhan -->
                                        </select>
                                    </div>
                                    <button class="btn btn-success float-right mb-2">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end modal divisi  --}}
                
                {{-- modal program --}}
                <div class="modal fade" id="addProgram" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 id="grafik-title" class="modal-title">Tambah Program Baru</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <form id="programForm">
                                    <div class="form-group">
                                        <label for="program">Nama Program</label>
                                        <input type="text" class="form-control" id="programName" name="program_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="leader">Leader</label>
                                        <select class="form-control" id="leader" name="leader" required>
                                            <option value="" disabled selected>select</option>
                                            <!-- Tambahkan lebih banyak anggota sesuai kebutuhan -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="memberList">Pilih Anggota</label><br>
                                        <select multiple class="form-control" id="memberList" name="members[]">
                                            <!-- Tambahkan lebih banyak anggota sesuai kebutuhan -->
                                        </select>
                                    </div>
                                    <button class="btn btn-success float-right mb-2">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end modal program --}}
            </div>
        </div>
    </section>
  </div>
  @include('include/footer')
</div>
@include('include/script')
<script src="{{asset('storage/js/logincheck.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    $(document).ready(function(){
        if (sessionStorage.getItem('session')==null || sessionStorage.getItem('session')==2 ) {
            return window.location = window.location.origin+'/login';
        }
        loginCheck(sessionStorage.getItem('id'));
        sessionCheck(sessionStorage.getItem('id'));
        $('#memberList, #divisi_memberList').select2({
            placeholder: 'Pilih Anggota',
            allowClear: true
        });
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
        });

        var program_channel = pusher.subscribe('program');
        var divisi_channel = pusher.subscribe('divisi');
        var connection_channel = pusher.subscribe('connection');

        program_channel.bind('new-program', function(response) {
            var data = response.data;
            // submitAjax("/api/connection", {'program_id': data.id});
            $('#memberList').val().forEach(function(element) {
                submitAjax("/api/data/" + element, {'program_id': data.id});
            });
            $('#addProgram').modal('hide');
        });
        program_channel.bind('delete-program', function(response) {
            $('#tr-' + response.data.id).remove();
        });

        divisi_channel.bind('delete-divisi', function(response) {
            $('#tr-' + response.data.id).remove();
        });
        divisi_channel.bind('new-divisi', function(response) {
            var data = response.data;
            // submitAjax("/api/connection", {'divisi_id': data.id});
            $('#divisi_memberList').val().forEach(function(element) {
                submitAjax("/api/data/" + element, {'divisi_id': data.id});
            });
            $('#addDivisi').modal('hide');
        });

        connection_channel.bind('new-connection', function(response) {
            if (response.data.data_divisi != null) {
                $('#table-content-divisi').append(
                    '<tr id="tr-' + response.data.data_divisi.id + '">' +
                        '<td class="text"><a href="{{route('cabinet_discuss')}}?d=' + response.data.id + '">' + response.data.data_divisi.divisi + '</a></td>' +
                        '<td class="text-center">' +
                            '<button id="edit-divisi-' + response.data.data_divisi.id + '" class="btn btn-warning m-1"data-toggle="modal" data-target="#modalEdit-' + response.data.data_divisi.id + '"><i class="nav-icon fas fa-pen"></i></button>' +
                            '<button id="del-divisi-' + response.data.data_divisi.id + '" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                        '</td>' +
                    '</tr>'
                );
                modalEdit('divisi',response.data.data_divisi.id,response.data.data_divisi.leader_id,response.data.data_divisi.divisi);
                deleteProgram('del-divisi-' + response.data.data_divisi.id);
            }if(response.data.data_program != null){
                $('#table-content-program').append(
                    '<tr id="tr-' + response.data.data_program.id + '">' +
                        '<td class="text"><a href="{{route('cabinet_discuss')}}?d=' + response.data.id + '">' + response.data.data_program.program + '</a></td>' +
                        '<td class="text-center">' +
                            '<button id="edit-program-' + response.data.data_program.id + '" class="btn btn-warning m-1"data-toggle="modal" data-target="#modalEdit-' + response.data.data_program.id + '"><i class="nav-icon fas fa-pen"></i></button>' +
                            '<button id="del-program-' + response.data.data_program.id + '" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                        '</td>' +
                    '</tr>'
                );
                getLeaderID().then(({ leader_id, id })=>{
                    if (sessionStorage.getItem('session') == 1 || sessionStorage.getItem('id') == leader_id) {
                       document.getElementById('edit-program-' + response.data.data_program.id).style.display = "block";
                       document.getElementById('del-program-' + response.data.data_program.id).style.display = "block";
                    } else {
                        document.getElementById('del-program-' + response.data.data_program.id).style.display = "none";
                        document.getElementById('edit-program-' + response.data.data_program.id).style.display = "none";
                    }
                });
                modalEdit('program',response.data.data_program.id,response.data.data_program.leader_id,response.data.data_program.program);
                deleteProgram('del-program-' + response.data.data_program.id);
            }
        });
        
        var previousLeader = '';
        var divisi_previousLeader = '';

        $('#divisi_leader, #leader').change(function() {
            var prevLeader = $(this).attr('id') === 'divisi_leader' ? divisi_previousLeader : previousLeader;
            var prevLeader_text = $('#' + $(this).attr('id').replace('leader', 'memberList') + ' option[value="' + prevLeader + '"]').text();

            $('#' + $(this).attr('id').replace('leader', 'memberList') + ' option[value="' + prevLeader + '"]').remove();
            $('#' + $(this).attr('id').replace('leader', 'memberList') + ' option[value="' + prevLeader + '"]').prop('selected',false);
            $('#memberList').append(
                '<option value="'+prevLeader+'">'+prevLeader_text+'</option>'
            )

            $('#' + $(this).attr('id').replace('leader', 'memberList') + ' option[value="'+$(this).val()+'"]').prop('selected', true);
            if ($(this).attr('id') === 'divisi_leader') {
                divisi_previousLeader = $(this).val();
            } else {
                previousLeader = $(this).val();
            }
        });
        $.ajax({
            url: "/api/data",
            method:'GET',
            success: function (response) {
                var data = response.data;
                data.forEach(element => {
                    if (element.data_program.id == 1){
                        $('#memberList').append(
                            '<option value="'+element.id+'">'+element.data_anggota.nama+'</option>'
                        )
                        $('#leader').append(
                            '<option value="'+element.id+'">'+element.data_anggota.nama+'</option>'
                        )
                    }
                    if (element.data_divisi.id == 1) {
                        $('#divisi_memberList').append(
                            '<option value="'+element.id+'">'+element.data_anggota.nama+'</option>'
                        )
                        $('#divisi_leader').append(
                            '<option value="'+element.id+'">'+element.data_anggota.nama+'</option>'
                        )
                    }
                });
            }
        });
        function submitAjax(url, formData) {
            $.ajax({
                url: url,
                method: "POST",
                data: formData,
            });
        }

        function deleteProgram(id) {
            $('#' + id).on('click', function() {
                var program = id.split('-')[1];
                var program_id = id.split('-')[2];
                $.ajax({
                    url: "/api/"+program+"/"+program_id,
                    method: "DELETE"
                });
            });
            $.ajax({
                url: "/api/data/",
                method: "GET",
                success:function(response){
                    if (response.data.program_id == program_id) {
                        $.ajax({
                            url: "/api/data/"+response.data.id,
                            method: "POST",
                            data:{
                                "program_id":1
                            }
                        });
                    }
                }
            });
        }

        $('#programForm').submit(function(event) {
            // event.preventDefault();
            var formData = {
                'program': $('#programName').val(),
                'leader_id': $('#leader').val()
            };
            submitAjax("/api/program/", formData);
            submitAjax("/api/connection", {'program_id': data.id});

        });

        $('#divisiForm').submit(function(event) {
            // event.preventDefault();
            var formData = {
                'divisi': $('#divisiName').val(),
                'leader_id': $('#divisi_leader').val()
            };
            submitAjax("/api/divisi/", formData);
            submitAjax("/api/connection", {'divisi_id': data.id});
        });

        // create modal 
        function modalEdit(name,id,leader_id,placeholder=null){
            $('#modal-save').append(
                '<div class="modal fade" id="modalEdit-'+id+'" tabindex="-1" role="dialog" aria-hidden="true">' +
                    '<div class="modal-dialog modal-lg" role="document">' +
                        '<div class="modal-content">' +
                            '<div class="modal-header bg-warning">' +
                                '<h5 id="grafik-title" class="modal-title">Manage Program</h5>' +
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                            '</div>' +
                            '<div class="card-body">' +
                                '<form id="modalEdit-form-'+id+'">' +
                                    '<div class="form-group">' +
                                        '<label for="edit_program">Rename</label>' +
                                        '<input type="text" class="form-control" id="modalEdit-name-'+id+'" name="edit_program_name" value="'+placeholder+'">' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                        '<label for="leader">Change Leader</label>' +
                                        '<select class="form-control" id="modalEdit-leader-'+id+'" name="leader">' +
                                        '</select>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                        '<label for="memberList">Edit Member</label><br>' +
                                        '<select multiple class="form-control" id="modalEdit-memberList-'+id+'" name="members[]">' +
                                        '</select>' +
                                    '</div>' +
                                    '<button class="btn btn-warning float-right mb-2">save</button>' +
                                '</form>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
            $('#modalEdit-memberList-'+id+'').select2({
                placeholder: 'Select member', // Teks placeholder
                allowClear: true // Memungkinkan pengguna menghapus pilihan
            });
            $.ajax({
                url: origin+"/api/data",
                method:'GET',
                success: function (response) {
                    var data = response.data;
                    var program = '';
                    // fetch member 
                    data.forEach(member => {
                        if (name == 'divisi') {
                            program = member.data_divisi.id;
                        }
                        if (name == 'program') {
                            program = member.data_program.id;
                        }

                        if (program == id) {
                            if (member.id == leader_id) {
                                $('#modalEdit-leader-'+id+'').append(
                                    '<option value="'+member.id+'"selected>'+member.data_anggota.nama+'</option>'
                                )    
                            }else{
                                $('#modalEdit-leader-'+id+'').append(
                                    '<option value="'+member.id+'">'+member.data_anggota.nama+'</option>'
                                )
                            }
                            $('#modalEdit-memberList-'+id+'').append(
                                '<option value="'+member.id+'"selected>'+member.data_anggota.nama+'</option>'
                            )
                        }
                        if (program != id && program == 1){
                            $('#modalEdit-memberList-'+id+'').append(
                                '<option value="'+member.id+'">'+member.data_anggota.nama+'</option>'
                            )
                        }
                    });
                    // end fetch member 
                    var link = '';
                    if (name == 'divisi') {
                        link = '/api/divisi/';
                    }
                    if (name == 'program') {
                        link = '/api/program/';
                    }
                    var old_leader = $('#modalEdit-leader-'+id).val();
                    var old_member = JSON.stringify($('#modalEdit-memberList-'+id).val());
                    $('#modalEdit-form-'+id).submit(function(event){
                        // event.preventDefault();
                        var new_leader = $('#modalEdit-leader-'+id).val();
                        var new_member = JSON.stringify($('#modalEdit-memberList-'+id).val());
                        if (new_leader != old_leader) {
                            $.ajax({
                                url: link+id,
                                method: "put", // First change type to method here    
                                data: {
                                    'leader_id': new_leader
                                },
                                success: function(){
                                    old_leader = new_leader;
                                }
                            });
                        }
                        if (new_member != old_member) {
                            var delete_member = JSON.parse(old_member).filter(item=> !JSON.parse(new_member).includes(item));
                            new_member = JSON.parse(new_member).filter(item=> !JSON.parse(old_member).includes(item));
                            // update data
                            var program = '';
                            if (name == 'divisi') {
                                new_member.forEach(user => {
                                    $.ajax({
                                        url: "/api/data/"+user,
                                        method: "POST", // First change type to method here    
                                        data: {
                                            divisi_id: id,
                                            role_id:4
                                        },
                                        success: function(){
                                            old_member = new_member;
                                        }
                                    })
                                });
                                delete_member.forEach(user => {
                                    $.ajax({
                                        url: "/api/data/"+user,
                                        method: "POST", // First change type to method here    
                                        data: {
                                            divisi_id : 1,
                                            role_id:2

                                        },
                                        success: function(){
                                            old_member = new_member;
                                        }
                                    })
                                });
                            }
                            if (name == 'program') {
                                new_member.forEach(user => {
                                    $.ajax({
                                        url: "/api/data/"+user,
                                        method: "POST", // First change type to method here    
                                        data: {
                                            program_id: id,
                                            role_id:4
                                        },
                                        success: function(){
                                            old_member = new_member;
                                        }
                                    })
                                });
                                delete_member.forEach(user => {
                                    $.ajax({
                                        url: "/api/data/"+user,
                                        method: "POST", // First change type to method here    
                                        data: {
                                            program_id : 1,
                                            role_id:2
                                        },
                                        success: function(){
                                            old_member = new_member;
                                        }
                                    })
                                });
                            }
                        }
                    });
                }
            });
        }
        // fetch connection/program 
        $.ajax({
            url: origin+"/api/connection",
            method:'GET',
            success: function (response) {
                var data = response.data;
                data.forEach(connection => {
                    getUser().then(({ divisi_id,program_id,role_id })=>{
                        if (!(connection.divisi_id == null && connection.program_id == null)) {
                            if (role_id == 1) {
                                if (connection.data_divisi != null) {
                                    $('#table-content-divisi').append(
                                        '<tr id="tr-'+connection.data_divisi.id+'">'+
                                            '<td class="text"><a href="{{route('cabinet_discuss')}}?d='+connection.id+'">'+connection.data_divisi.divisi+'</a></td>' +
                                            '<td class="text-center">' +
                                                '<button id="edit-divisi-'+connection.data_divisi.id+'" class="btn btn-warning m-1" data-toggle="modal" data-target="#modalEdit-'+connection.data_divisi.id+'"><i class="nav-icon fas fa-pen"></i></button>' +
                                                '<button id="del-divisi-'+connection.data_divisi.id+'" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                                            '</td>' +
                                        '</tr>'
                                    );
                                    modalEdit('divisi',connection.data_divisi.id,connection.data_divisi.leader_id,connection.data_divisi.divisi);
                                    deleteProgram('del-divisi-'+connection.data_divisi.id);
                                };
                                if (connection.data_program != null) {
                                    $('#table-content-program').append(
                                        '<tr id="tr-'+connection.data_program.id+'">'+
                                            '<td class="text"><a href="{{route('cabinet_discuss')}}?d='+connection.id+'">'+connection.data_program.program+'</a></td>' +
                                            '<td class="text-center">' +
                                                '<button id="edit-program-'+connection.data_program.id+'" class="btn btn-warning m-1"data-toggle="modal" data-target="#modalEdit-'+connection.data_program.id+'"><i class="nav-icon fas fa-pen"></i></button>' +
                                                '<button id="del-program-'+connection.data_program.id+'" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                                            '</td>' +
                                        '</tr>'
                                    );
                                    modalEdit('program',connection.data_program.id,connection.data_program.leader_id,connection.data_program.program);
                                    deleteProgram('del-program-'+connection.data_program.id);
                                };
                                document.getElementById('new_data_divisi').style.display = "block";
                                document.getElementById('new_data_program').style.display = "block";
                            } else{
                                if (connection.data_divisi != null) {
                                    if (connection.data_divisi.id == divisi_id) {
                                        $('#table-content-divisi').append(
                                            '<tr id="tr-'+connection.data_divisi.id+'">'+
                                                '<td class="text"><a href="{{route('cabinet_discuss')}}?d='+connection.id+'">'+connection.data_divisi.divisi+'</a></td>' +
                                                '<td class="text-center" id="edit-divisi-'+connection.data_divisi.id+'">' +
                                                    '<button class="btn btn-warning m-1" data-toggle="modal" data-target="#modalEdit-'+connection.data_divisi.id+'"><i class="nav-icon fas fa-pen"></i></button>' +
                                                    '<button id="del-divisi-'+connection.data_divisi.id+'" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                                                '</td>' +
                                            '</tr>'
                                        );
                                        if (sessionStorage.getItem('session') == 1 || sessionStorage.getItem('id') == connection.data_divisi.leader_id) {
                                            document.getElementById('edit-divisi-'+connection.data_divisi.id).style.display = "block";
                                        } else {
                                            document.getElementById('edit-divisi-' + connection.data_divisi.id).style.display = "none";
                                        }
                                        document.getElementById('table-divisi').style.display = "block";
                                        modalEdit('divisi',connection.data_divisi.id,connection.data_divisi.leader_id,connection.data_divisi.divisi);
                                        deleteProgram('del-divisi-'+connection.data_divisi.id);
                                    }
                                };
                                if (connection.data_program != null) {
                                    if (connection.data_program.id == program_id) {
                                        $('#table-content-program').append(
                                            '<tr id="tr-'+connection.data_program.id+'">'+
                                                '<td class="text"><a href="{{route('cabinet_discuss')}}?d='+connection.id+'">'+connection.data_program.program+'</a></td>' +
                                                '<td class="text-center" id="edit-program-'+connection.data_program.id+'">' +
                                                    '<button style="display:none" class="btn btn-warning m-1"data-toggle="modal" data-target="#modalEdit-'+connection.data_program.id+'"><i class="nav-icon fas fa-pen"></i></button>' +
                                                    '<button id="del-program-'+connection.data_program.id+'" style="display:none" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                                                '</td>' +
                                            '</tr>'
                                        );
                                        if (sessionStorage.getItem('session') == 1 || sessionStorage.getItem('id') == connection.data_program.leader_id) {
                                            document.getElementById('edit-program-' + connection.data_program.id).style.display = "block";
                                        } else {
                                            document.getElementById('del-program-' + connection.data_program.id).style.display = "none";
                                        }
                                        document.getElementById('table-program').style.display = "block";
                                        modalEdit('program',connection.data_program.id,connection.data_program.leader_id,connection.data_program.program);
                                        deleteProgram('del-program-'+connection.data_program.id);
                                    }
                                };
                            }
                        };
                    });
                });
                if (sessionStorage.getItem('session') == 1) {
                    document.getElementById('table-divisi').style.display = "block";
                    document.getElementById('table-program').style.display = "block";
                }
            }
        });
        function getUser(){
            return new Promise((resolve)=>{
                $.ajax({
                url: "/api/data/" + sessionStorage.getItem('id'),
                method: "GET",
                success: function(response) {
                    var data = response.data;
                    resolve({ divisi_id: data.divisi_id, program_id:data.program_id, role_id: data.role_id });
                }
                });
            })
        }
    });
</script>
</body>
</html>
