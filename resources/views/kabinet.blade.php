<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard • Cabinet</title>
  @include('include/link')
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
                        <tbody id="table-content">
                            <tr>
                                <td class="text"><a href="{{route('cabinet_discuss')}}?d=kaderisasi">Divisi Kaderisasi</a></td>
                                <td class="text-center">
                                    <button class="btn btn-warning m-1"><i class="nav-icon fas fa-pen"></i></button>
                                    <button id="del-topic" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text"><a href="{{route('cabinet_discuss')}}?d=sosial">Divisi Sosial</a></td>
                                <td class="text-center">
                                    <button class="btn btn-warning m-1"><i class="nav-icon fas fa-pen"></i></button>
                                    <button id="del-topic" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>
                                </td>
                            </tr>
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
                        <tbody id="table-content">
                            <tr>
                                <td class="text"><a href="#">Seminar Nasional</a></td>
                                <td class="text-center">
                                    <button class="btn btn-warning m-1"><i class="nav-icon fas fa-pen"></i></button>
                                    <button id="del-topic" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button class="btn btn-success"  data-toggle="modal" data-target="#addTopic"><i class="fas fa-solid fa-plus"></i>  Add new</button>
                </div>
            </div>
        </div>
    </section>
  </div>
  @include('include/footer')
</div>
@include('include/script')
<script>
    $(document).ready(function(){
        if (sessionStorage.getItem('login')==null) {
            return window.location = '../login';
        }
        $.ajax({
            url: "/api/data/"+sessionStorage.getItem('login'),
            method: "GET", // First change type to method here
            success: function(response) {
                var data = response.data;
                $(".d-block").text(data.nama);
                $(".c-block").text(data.nama_divisi);
                if (data.avatar != null) {
                    $('#user_image').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
                }
            },
        });   
        $("#btnLogOut").click(function(){
          sessionStorage.clear();
          window.location = '../login';
        });
    });
</script>
</body>
</html>
