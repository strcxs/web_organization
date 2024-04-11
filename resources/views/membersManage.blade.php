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
                                <div class="card-header bg-warning">
                                    <h3 class="card-title">Edit Member of HMIF</h3>
                                </div>
                                <div class="card-body">
                                    <table id="editMemberTable" class="table table-bordered table-striped">
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
                                    <hr>
                                    <button class="btn btn-success"  data-toggle="modal" data-target="#addMember"><i class="fas fa-solid fa-plus"></i>  Add new member</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- modal add member  --}}
                <div class="modal fade" id="addMember" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 id="grafik-title" class="modal-title">Add New Member</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <form id="memberForm">
                                    <div class="form-group">
                                        <label for="studentId">Student ID</label>
                                        <input type="text" class="form-control" id="studentId" name="student_id" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="member">Member Name</label>
                                        <input type="text" class="form-control" id="memberName" name="member_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="batchYear">Batch Year</label>
                                        <input type="text" class="form-control" id="batchYear" name="batch_year" required>
                                    </div>
                                    <button class="btn btn-success float-right mb-2">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end modal add member --}}
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
        if (sessionStorage.getItem('login')==null) {
        return window.location = '../login';
        }
        loginCheck(sessionStorage.getItem('login'));
        $('#memberForm').submit(function(event) {
            event.preventDefault();
            var origin = window.location.origin;

            $.ajax({
                url: origin+'/api/member',
                method: "POST",
                data: {
                    'nama': $('#memberName').val(),
                    'nim': $('#studentId').val(),
                    'tahun_akt': $('#batchYear').val()
                },
                success: function(response){
                    console.log(response);
                }
            });
        });
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
                                "<tr id = 'tr-"+ element.id +"'>"+
                                    "<td>"+
                                        '<div class="dropdown">' +
                                            '<button class="btn btn-secondary dropdown-toggle mr-1 btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                                // 'Actions' +
                                            '</button>' +
                                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                                                '<button class="dropdown-item" id ="member-delete-'+ element.id +'">Delete</button>' +
                                                '<button class="dropdown-item" id ="member-edit-'+ element.id +'">Edit</button>' +
                                                '<button class="dropdown-item" id ="member-deactived-'+ element.id +'">Deactived</button>' +
                                            '</div>' +
                                            '<a class="text-dark" href="profile/detail?id=' + element.user_id + '">'+
                                                element.nama + 
                                            '</a>'+
                                            "<span class='border rounded bg-danger ml-1 px-1'>unregistered</span>"+
                                        '</div>' +
                                    "</td>"+
                                    "<td>"+element.nim+"</td>"+
                                    "<td>"+'member'+"</td>"+
                                    "<td>"+element.tahun_akt+"</td>"+
                                "</tr>"
                            );
                        }if (element.data_users != null) {
                            $("tbody").append(
                                "<tr id = 'tr-"+ element.id +"'>"+
                                    "<td>"+
                                        '<div class="dropdown">' +
                                            '<button class="btn btn-secondary dropdown-toggle mr-1 btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                                // 'Actions' +
                                            '</button>' +
                                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                                                '<button class="dropdown-item" id ="member-delete-'+ element.id +'">Delete</button>' +
                                                '<button class="dropdown-item" id ="member-edit-'+ element.id +'">Edit</button>' +
                                                '<button class="dropdown-item" id ="member-deactived-'+ element.id +'">Deactived</button>' +
                                            '</div>' +
                                            '<a class="text-dark" href="profile/detail?id=' + element.user_id + '">'+
                                                element.nama + 
                                            '</a>'+
                                            "<span class='border rounded bg-success ml-1 px-1'>active</span>"+
                                        '</div>' +
                                    "</td>"+
                                    "<td>"+element.nim+"</td>"+
                                    "<td>"+element.data_users.data_divisi.divisi+"</td>"+
                                    "<td>"+element.tahun_akt+"</td>"+
                                "</tr>"
                            );
                        }
                    }
                    $('#member-delete-'+element.id).on('click',function(){
                        $.ajax({
                            url: origin+"/api/member/"+element.id,
                            method: 'DELETE',
                            success: function (response) {
                                $('#tr-'+element.id+'').remove();
                            }
                        });
                    })
                });
                $('#editMemberTable').DataTable({
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
                    "lengthMenu": [5,20,50],
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