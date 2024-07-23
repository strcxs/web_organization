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
                                <table id="memberTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr id="head">
                                            <th id="head">Name</th>
                                            <th id="head">Student ID</th>
                                            <th id="head">Departement</th>
                                            <th id="head">Batch Year</th>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">

<script src="{{asset('storage/js/logincheck.js')}}"></script>
<script>
    $(document).ready(function(){
        if (sessionStorage.getItem('id')==null) {
            return window.location = window.location.origin+'/login';
        }
        sessionCheck(sessionStorage.getItem('id'));
        loginCheck(sessionStorage.getItem('id'));
        $.ajax({
            url: "/api/data",
            method: "GET", // First change type to method here
            data:{
                'member':true
            },
            success: function(response) {
                var data = response.data;
                data.forEach(element => {
                    if (element.user_id != '64') {
                        if (element.data_users == null) {
                            $("tbody").append(
                                "<tr>"+
                                    "<td>"+element.nama+"</td>"+
                                    "<td>"+element.id+"</td>"+
                                    "<td>"+'member'+"</td>"+
                                    "<td>"+element.tahun_akt+"</td>"+
                                "</tr>"
                            );
                        }if (element.data_users != null) {
                            $("tbody").append(
                                "<tr>"+
                                    "<td>"+'<a class="text-dark" href="profile/detail?id=' + element.data_users.member_id + '">' + element.nama + '</a>'+"</td>"+
                                    "<td>"+element.id+"</td>"+
                                    "<td>"+element.data_users.data_divisi.divisi+"</td>"+
                                    "<td>"+element.tahun_akt+"</td>"+
                                "</tr>"
                            );
                        }
                    }
                });
                $('#memberTable').DataTable({
                    "dom": "<'float-right'Bf><'float-left'l><t><p>",
                    "buttons": ['pdf'],
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": false,
                    "info": false,
                    "autoWidth": true,
                    "responsive": true,
                    "pagingType": "simple",
                    "lengthMenu": [20,50],
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
    });
</script>
</html>