<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard • Member list</title>
    @include('include/link')
</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        @include('include/sidebar')
        @include('include/navbar')
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row p-3">
                        <div class="col-12">
                            <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Member of HMIF</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr id="head">
                                            <th id="head">Nama</th>
                                            <th id="head">NIM</th>
                                            <th id="head">Divisi</th>
                                            <th id="head">tempat lahir</th>
                                            <th id="head">Tanggal lahir</th>
                                            <th id="head">Angkatan</th>
                                            <th id="head">No Telp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- table-content --}}
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('include/footer')
    </div>
</body>
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
                $(".c-block").text('divisi '+data.nama_divisi);
                if (data.avatar != null) {
                    $('#user_image').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
                }
            },
            error: function(error){

            }
        });
        $.ajax({
            url: "/api/data",
            method: "GET", // First change type to method here
            success: function(response) {
                var keys = Object.keys(response.data[0]);
                var karyawan = response.data;

                karyawan.forEach(function(item){
                    if (item.tanggal_lahir == null) {
                        item.tanggal_lahir = "-";
                    }if (item.tempat_lahir == null) {
                        item.tempat_lahir = "-";
                    }if (item.no_telp == null) {
                        item.no_telp = "-";
                    }
                    $("tbody").append(
                    "<tr>"+
                        "<td>"+item.nama+"</td>"+
                        "<td>"+item.nim+"</td>"+
                        "<td>"+item.nama_divisi+"</td>"+
                        "<td>"+item.tanggal_lahir+"</td>"+
                        "<td>"+item.tempat_lahir+"</td>"+
                        "<td>"+item.tahun_akt+"</td>"+
                        "<td>"+item.no_telp+"</td>"+
                    "</tr>"
                    );
                });
                $('#example1').DataTable({
                    // "dom": "Bfrtip",
                    "dom": "<'float-right'Bf><'float-left'l><t><p>",
                    "buttons": ['pdf'],
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    "responsive": true,
                    "pagingType": "simple",
                    "lengthMenu": [10,25,50],
                    "language": {
                        "search": "search ",
                        "paginate": {
                            "next": "<button type='button' class='btn btn-tool' style='font-size: 17px ;background-color: transparent; border: none; padding: 20; margin: 0;color: blue'> next</button>",
                            "previous": "<button type='button' class='btn btn-tool' style='font-size: 17px ;background-color: transparent; border: none; padding: 20; margin: 0;color: blue' >previous </button>"    
                        },
                        // "lengthMenu": "Display _MENU_ records per page",
                        "zeroRecords": "Not found",
                        "info": "Showing page _PAGE_ of _PAGES_",
                        "infoEmpty": "No records available",
                    }
                });
            }
        });
        $("#btnLogOut").click(function(){
            sessionStorage.clear();
            window.location = '../login';
        });
    });
</script>
</html>