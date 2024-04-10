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
            <div class="card">
                <div class="table-responsive-sm p-3 p-3">
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th class="bg-primary">DIVISI</th>
                                <th class="text-center bg-primary"></th>
                            </tr>
                        </thead>
                        <tbody id="table-content-divisi">
                            {{-- content tabel divisi --}}
                        </tbody>
                    </table>

                    <button class="btn btn-success"  data-toggle="modal" data-target="#addDivisi"><i class="fas fa-solid fa-plus"></i>  Add new</button>
                </div>
            </div>
            <div class="card">
                <div class="table-responsive-sm p-3">
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th class="bg-primary">PROGRAM KERJA</th>
                                <th class="text-center bg-primary"></th>
                            </tr>
                        </thead>
                        <tbody id="table-content-program">
                            {{-- content table program --}}
                        </tbody>
                    </table>

                    <button class="btn btn-success"  data-toggle="modal" data-target="#addProgram"><i class="fas fa-solid fa-plus"></i>  Add new</button>
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
<script>
    $(document).ready(function(){
        if (sessionStorage.getItem('login')==null) {
            return window.location = '../login';
        }
        loginCheck(sessionStorage.getItem('login'));
        $('#memberList, #divisi_memberList').select2({
            placeholder: 'Pilih Anggota',
            allowClear: true
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
        function submitAjax(url, formData, successCallback) {
            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                success: successCallback
            });
        }

        function deleteProgram(id) {
            $('#' + id).on('click', function() {
                $.ajax({
                    url: "/api/" + id.split('-')[0] + "/" + id.split('-')[1],
                    method: "DELETE",
                    success: function(response) {
                        $('#tr-' + id.split('-')[1]).remove();
                    }
                });
            });
        }

        $('#programForm').submit(function(event) {
            event.preventDefault();
            var formData = {
                'program': $('#programName').val(),
                'leader_id': $('#leader').val()
            };
            submitAjax("/api/program/", formData, function(response) {
                var data = response.data;
                $('#memberList').val().forEach(function(element) {
                    submitAjax("/api/data/" + element, {'program_id': data.id});
                });
                submitAjax("/api/connection", {'program_id': data.id}, function(response) {
                    $('#table-content-program').append(
                        '<tr id="tr-' + data.id + '">' +
                            '<td class="text"><a href="{{route('cabinet_discuss')}}?d=' + response.data.id + '">' + data.program + '</a></td>' +
                            '<td class="text-center">' +
                                '<button class="btn btn-warning m-1"data-toggle="modal" data-target="#editDivisi-' + data.id + '"><i class="nav-icon fas fa-pen"></i></button>' +
                                '<button id="del-program-' + data.id + '" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                            '</td>' +
                        '</tr>'
                    );
                    deleteProgram('del-program-' + data.id);
                });
                $('#addProgram').modal('hide');
            });
        });

        $('#divisiForm').submit(function(event) {
            event.preventDefault();
            var formData = {
                'divisi': $('#divisiName').val(),
                'leader_id': $('#divisi_leader').val()
            };
            submitAjax("/api/divisi/", formData, function(response) {
                var data = response.data;
                $('#divisi_memberList').val().forEach(function(element) {
                    submitAjax("/api/data/" + element, {'divisi_id': data.id});
                });
                submitAjax("/api/connection", {'divisi_id': data.id}, function(response) {
                    $('#table-content-divisi').append(
                        '<tr id="tr-' + data.id + '">' +
                            '<td class="text"><a href="{{route('cabinet_discuss')}}?d=' + response.data.id + '">' + data.divisi + '</a></td>' +
                            '<td class="text-center">' +
                                '<button class="btn btn-warning m-1"data-toggle="modal" data-target="#editDivisi-' + data.id + '"><i class="nav-icon fas fa-pen"></i></button>' +
                                '<button id="del-divisi-' + data.id + '" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                            '</td>' +
                        '</tr>'
                    );
                    deleteProgram('del-divisi-' + data.id);
                });
                $('#addDivisi').modal('hide');
            });
        });


        // fetch connection/program 
        $.ajax({
            url: "/api/connection",
            method: "GET",
            success: function (response) {
                var data = response.data;
                $.ajax({
                    url: "/api/data",
                    method:'GET',
                    success: function (response) {
                        var dataUsers = response.data;
                        data.forEach(element => {
                            if (!(element.divisi_id == null && element.program_id == null)) {
                                var contentName = 'error';
                                // PROGRAM 
                                if (element.data_program != null) {
                                    contentName = element.data_program.program;
                                    $('#table-content-program').append(
                                        '<tr id="tr-'+element.data_program.id+'">'+
                                            '<td class="text"><a href="{{route('cabinet_discuss')}}?d='+element.id+'">'+contentName+'</a></td>' +
                                            '<td class="text-center">' +
                                                '<button class="btn btn-warning m-1"data-toggle="modal" data-target="#editProgram-'+element.data_program.id+'"><i class="nav-icon fas fa-pen"></i></button>' +
                                                '<button id="del-topic-'+element.data_program.id+'" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                                            '</td>' +
                                        '</tr>'
                                    );
                                    $('#del-topic-'+element.data_program.id+'').on('click', function() {
                                        console.log('cok');
                                        $.ajax({
                                            url: "/api/program/"+element.data_program.id,
                                            method: "delete", // First change type to method here    
                                            success: function(response) {
                                                $('#tr-'+element.data_program.id+'').remove();
                                            }
                                        });
                                    });
                                    $('#modal-save').append(
                                        '<div class="modal fade" id="editProgram-'+element.data_program.id+'" tabindex="-1" role="dialog" aria-hidden="true">' +
                                            '<div class="modal-dialog modal-lg" role="document">' +
                                                '<div class="modal-content">' +
                                                    '<div class="modal-header bg-warning">' +
                                                        '<h5 id="grafik-title" class="modal-title">Manage Program</h5>' +
                                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                        '</button>' +
                                                    '</div>' +
                                                    '<div class="card-body">' +
                                                        '<form id="edit_programForm-'+element.data_program.id+'">' +
                                                            '<div class="form-group">' +
                                                                '<label for="edit_program">Rename</label>' +
                                                                '<input type="text" class="form-control" id="edit_programName-'+element.data_program.id+'" name="edit_program_name" placeholder="'+element.data_program.program+'">' +
                                                            '</div>' +
                                                            '<div class="form-group">' +
                                                                '<label for="leader">Change Leader</label>' +
                                                                '<select class="form-control" id="edit_program_leader-'+element.data_program.id+'" name="leader">' +
                                                                '</select>' +
                                                            '</div>' +
                                                            '<div class="form-group">' +
                                                                '<label for="memberList">Edit Anggota</label><br>' +
                                                                '<select multiple class="form-control" id="edit_program_memberList-'+element.data_program.id+'" name="members[]">' +
                                                                    '<!-- Tambahkan lebih banyak anggota sesuai kebutuhan -->' +
                                                                '</select>' +
                                                            '</div>' +
                                                            '<button class="btn btn-warning float-right mb-2">save</button>' +
                                                        '</form>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>'
                                    );
                                    let users_before_program = [];
                                    let leader_before_program = '';
                                    $('#edit_programForm-'+element.data_program.id+'').submit(function(event) {
                                        event.preventDefault();
                                        var users_program = $('#edit_program_memberList-'+element.data_program.id+'').val();
                                        var leader_program = $('#edit_program_leader-'+element.data_program.id+'').val()
                                        
                                        var users_delete_program = users_before_program.filter(item=> !users_program.includes(item));
                                        var users_new_program = users_program.filter(item=> !users_before_program.includes(item));
                                        
                                        if (leader_before_program != leader_program) {
                                            $.ajax({
                                                url: "/api/program/"+element.data_program.id,
                                                method: "put", // First change type to method here    
                                                data: {
                                                    'leader_id': leader_program
                                                }
                                            })
                                        }
                                        users_new_program.forEach(user => {
                                            $.ajax({
                                                url: "/api/data/"+user,
                                                method: "POST", // First change type to method here    
                                                data: {
                                                    'program_id': element.data_program.id
                                                }
                                            })
                                        });
                                        users_delete_program.forEach(user => {
                                            $.ajax({
                                                url: "/api/data/"+user,
                                                method: "POST", // First change type to method here    
                                                data: {
                                                    'program_id': 1
                                                }
                                            })
                                        });
                                        $('#editProgram-'+element.data_program.id+'').modal('hide');
                                    });
                                    $('#edit_program_memberList-'+element.data_program.id+'').select2({
                                        placeholder: 'Pilih Anggota', // Teks placeholder
                                        allowClear: true // Memungkinkan pengguna menghapus pilihan
                                    });
                                    
                                    dataUsers.forEach(loop => {
                                        if (loop.data_program !=null) {
                                            if (loop.data_program.id == element.data_program.id) {
                                                if (loop.data_program.leader_id != loop.id) {
                                                    $('#edit_program_leader-'+element.data_program.id+'').append(
                                                        '<option value="'+loop.id+'">'+loop.data_anggota.nama+'</option>'
                                                    )
                                                }
                                                if (loop.data_program.leader_id == loop.id) {
                                                    $('#edit_program_leader-'+element.data_program.id+'').append(
                                                        '<option value="'+loop.id+'" selected>'+loop.data_anggota.nama+'</option>'
                                                    )
                                                    leader_before = $('#edit_program_leader-'+element.data_program.id+'').val();
                                                }
                                                $('#edit_program_memberList-'+element.data_program.id+'').append(
                                                    '<option value="'+loop.id+'"selected>'+loop.data_anggota.nama+'</option>'
                                                )
                                            }
                                            if (loop.data_program.id == 1) {
                                                $('#edit_program_memberList-'+element.data_program.id+'').append(
                                                    '<option value="'+loop.id+'">'+loop.data_anggota.nama+'</option>'
                                                )
                                            }
                                        }
                                    });
                                    users_before = $('#edit_program_memberList-'+element.data_program.id+'').val();
                                    leader_before = $('#edit_program_leader-'+element.data_program.id+'').val();
                                    
                                // DIVISI 
                                }else{
                                    contentName = element.data_divisi.divisi;
                                    $('#table-content-divisi').append(
                                        '<tr id="tr-'+element.data_divisi.id+'">'+
                                            '<td class="text"><a href="{{route('cabinet_discuss')}}?d='+element.id+'">'+contentName+'</a></td>' +
                                            '<td class="text-center">' +
                                                '<button class="btn btn-warning m-1"data-toggle="modal" data-target="#editDivisi-'+element.data_divisi.id+'"><i class="nav-icon fas fa-pen"></i></button>' +
                                                '<button id="del-topic-'+element.data_divisi.id+'" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                                            '</td>' +
                                        '</tr>'
                                    );
                                    $('#del-topic-' + element.data_divisi.id).on('click', function() {
                                        $.ajax({
                                            url: "/api/divisi/"+element.data_divisi.id,
                                            method: "delete", // First change type to method here    
                                            success: function(response) {
                                                $('#tr-'+element.data_divisi.id+'').remove();
                                            }
                                        });
                                    });
                                    $('#modal-save').append(
                                        '<div class="modal fade" id="editDivisi-'+element.data_divisi.id+'" tabindex="-1" role="dialog" aria-hidden="true">' +
                                            '<div class="modal-dialog modal-lg" role="document">' +
                                                '<div class="modal-content">' +
                                                    '<div class="modal-header bg-warning">' +
                                                        '<h5 id="grafik-title" class="modal-title">Manage Divisi</h5>' +
                                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                        '</button>' +
                                                    '</div>' +
                                                    '<div class="card-body">' +
                                                        '<form id="edit_divisiForm-'+element.data_divisi.id+'">' +
                                                            '<div class="form-group">' +
                                                                '<label for="edit_divisi">Rename</label>' +
                                                                '<input type="text" class="form-control" id="edit_divisiName-'+element.data_divisi.id+'" name="edit_divisi_name" placeholder="'+element.data_divisi.divisi+'">' +
                                                            '</div>' +
                                                            '<div class="form-group">' +
                                                                '<label for="leader">Change Leader</label>' +
                                                                '<select class="form-control" id="edit_divisi_leader-'+element.data_divisi.id+'" name="leader">' +
                                                                '</select>' +
                                                            '</div>' +
                                                            '<div class="form-group">' +
                                                                '<label for="memberList">Edit Anggota</label><br>' +
                                                                '<select multiple class="form-control" id="edit_divisi_memberList-'+element.data_divisi.id+'" name="members[]">' +
                                                                    '<!-- Tambahkan lebih banyak anggota sesuai kebutuhan -->' +
                                                                '</select>' +
                                                            '</div>' +
                                                            '<button class="btn btn-warning float-right mb-2">save</button>' +
                                                        '</form>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>'
                                    );
                                    let users_before = [];
                                    let leader_before = '';
                                    $('#edit_divisiForm-'+element.data_divisi.id+'').submit(function(event) {
                                        event.preventDefault();
                                        var users = $('#edit_divisi_memberList-'+element.data_divisi.id+'').val();
                                        var leader = $('#edit_divisi_leader-'+element.data_divisi.id+'').val()
        
                                        var users_delete = users_before.filter(item=> !users.includes(item));
                                        var users_new = users.filter(item=> !users_before.includes(item));
                                        if (leader_before != leader) {
                                            $.ajax({
                                                url: "/api/divisi/"+element.data_divisi.id,
                                                method: "put", // First change type to method here    
                                                data: {
                                                    'leader_id': leader
                                                }
                                            })
                                        }
                                        users_new.forEach(user => {
                                            $.ajax({
                                                url: "/api/data/"+user,
                                                method: "POST", // First change type to method here    
                                                data: {
                                                    'divisi_id': element.data_divisi.id
                                                }
                                            })
                                        });
                                        users_delete.forEach(user => {
                                            $.ajax({
                                                url: "/api/data/"+user,
                                                method: "POST", // First change type to method here    
                                                data: {
                                                    'divisi_id': 1
                                                }
                                            })
                                        });
                                        $('#editDivisi-'+element.data_divisi.id+'').modal('hide');
                                    });
                                    $('#edit_divisi_memberList-'+element.data_divisi.id+'').select2({
                                        placeholder: 'Pilih Anggota', // Teks placeholder
                                        allowClear: true // Memungkinkan pengguna menghapus pilihan
                                    });
                                    dataUsers.forEach(loop => {
                                        if (loop.data_divisi !=null) {
                                            if (loop.data_divisi.id == element.data_divisi.id) {
                                                if (loop.data_divisi.leader_id != loop.id) {
                                                    $('#edit_divisi_leader-'+element.data_divisi.id+'').append(
                                                        '<option value="'+loop.id+'">'+loop.data_anggota.nama+'</option>'
                                                    )
                                                }
                                                if (loop.data_divisi.leader_id == loop.id) {
                                                    $('#edit_divisi_leader-'+element.data_divisi.id+'').append(
                                                        '<option value="'+loop.id+'" selected>'+loop.data_anggota.nama+'</option>'
                                                    )
                                                    leader_before = $('#edit_divisi_leader-'+element.data_divisi.id+'').val();
                                                }
                                                $('#edit_divisi_memberList-'+element.data_divisi.id+'').append(
                                                    '<option value="'+loop.id+'"selected>'+loop.data_anggota.nama+'</option>'
                                                )
                                            }
                                            if (loop.data_divisi.id == 1) {
                                                $('#edit_divisi_memberList-'+element.data_divisi.id+'').append(
                                                    '<option value="'+loop.id+'">'+loop.data_anggota.nama+'</option>'
                                                )
                                            }
                                        }
                                    });
                                    users_before = $('#edit_divisi_memberList-'+element.data_divisi.id+'').val();
                                    leader_before = $('#edit_divisi_leader-'+element.data_divisi.id+'').val();
                                }
                            }
                        });
                    }
                });
            }
        });
    });
</script>
</body>
</html>
