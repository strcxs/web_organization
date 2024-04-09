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

                    <button class="btn btn-success"  data-toggle="modal" data-target="#addTopic"><i class="fas fa-solid fa-plus"></i>  Add new</button>
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
                            <form id="topicForm">
                                <div class="form-group">
                                    <label for="topicName">Nama Program</label>
                                    <input type="text" class="form-control" name="topic_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="memberList">Pilih Anggota</label>
                                    <select multiple class="form-control" id="memberList" name="members[]">
                                        <option value="member1">Anggota 1</option>
                                        <option value="member2">Anggota 2</option>
                                        <option value="member3">Anggota 3</option>
                                        <!-- Tambahkan lebih banyak anggota sesuai kebutuhan -->
                                    </select>
                                </div>
                            </form>
                            <button class="btn btn-primary float-right mb-2">Submit</button>
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
<script src="{{asset('storage/js/logincheck.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        if (sessionStorage.getItem('login')==null) {
            return window.location = '../login';
        }
        loginCheck(sessionStorage.getItem('login'));
        $('#memberList').select2({
            placeholder: 'Pilih Anggota', // Teks placeholder
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });
        $.ajax({
            url: "/api/connection",
            method: "GET",
            success: function (response) {
                var data = response.data;
                data.forEach(element => {
                    if (!(element.divisi_id == null && element.program_id == null)) {
                        var contentName = 'error';
                        if (element.data_program != null) {
                            contentName =  element.data_program.program;
                            $('#table-content-program').append(
                                '<tr>' +
                                    '<td class="text"><a href="{{route('cabinet_discuss')}}?d='+element.id+'">'+contentName+'</a></td>' +
                                    '<td class="text-center">' +
                                        '<button class="btn btn-warning m-1"><i class="nav-icon fas fa-pen"></i></button>' +
                                        '<button id="del-topic-'+element.id+'" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                                    '</td>' +
                                '</tr>'
                            );
                        }
                        else{
                            contentName = element.data_divisi.divisi;
                            $('#table-content-divisi').append(
                                '<tr>' +
                                    '<td class="text"><a href="{{route('cabinet_discuss')}}?d='+element.id+'">'+contentName+'</a></td>' +
                                    '<td class="text-center">' +
                                        '<button class="btn btn-warning m-1"><i class="nav-icon fas fa-pen"></i></button>' +
                                        '<button id="del-topic-'+element.id+'" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>' +
                                    '</td>' +
                                '</tr>'
                            );
                        }
                    }
                });
            }
        });
    });
</script>
</body>
</html>
