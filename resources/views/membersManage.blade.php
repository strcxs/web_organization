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
                                                <th id="head">Gender</th>
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
                                    <button class="btn btn-success" id="csv-import"><i class="fas fa-solid fa-file-import"></i>  import CSV</button>
                                    <input type="file" id="csv-file" name="csv-file" class="d-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="modal">

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
                                            <label for="gender">Gender</label>
                                            <select class="form-control" id="gender" name="gender">
                                                <option disabled selected>select</option>
                                                <option value="M">Male</option>
                                                <option value="F">Female</option>
                                            </select>
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
                </div>
            </section>
        </div>
        @include('include/footer')
    </div>
</body>
@include('include/script')

<script src="{{asset('storage/js/logincheck.js')}}"></script>
<script>
    $(document).ready(function(){
        var origin = window.location.origin;
        if (sessionStorage.getItem('session')==null || sessionStorage.getItem('session')!=1 ) {
            return window.location = window.location.origin+'/login';
        }
        sessionCheck(sessionStorage.getItem('id'));
        loginCheck(sessionStorage.getItem('id'));
        $('#csv-import').click(function(){
            $('#csv-file').click();
        })
        $('#csv-file').change(function(){
            var csv = $('#csv-file').prop('files')[0];
            var csv_file = new FormData();

            csv_file.append('csv',csv);

            $.ajax({
                url: origin+"/api/csv",
                method: 'POST',
                data: csv_file,
                processData: false,
                contentType: false,
                success: function (response) {
                    
                }
            });
        })
        $('#memberForm').submit(function(event) {
            // event.preventDefault();
            var origin = window.location.origin;

            $.ajax({
                url: origin+'/api/member',
                method: "POST",
                data: {
                    'nama': $('#memberName').val(),
                    'gender': $('#gender').val(),
                    'id': $('#studentId').val(),
                    'tahun_akt': $('#batchYear').val()
                },
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
                    var gender;
                    if (element.gender == 'M') {
                        gender = 'Male';
                    }else{
                        gender = 'female'
                    }
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
                                            '<button class="dropdown-item" data-toggle="modal" data-target="#editMember-'+element.id+'">Edit</button>' +
                                            '<button class="dropdown-item" id ="member-deactived-'+ element.id +'">Deactived</button>' +
                                        '</div>' +
                                        '<a class="text-dark">'+
                                            element.nama + 
                                        '</a>'+
                                        "<i class='fas fa-exclamation-triangle px-1 text-warning' title='unregistered'></i>"+
                                        // "<i class='fas fa-power-off px-1 text-danger' title='active'></i>"+
                                        // "<i class='fas fa-user-check px-1 text-success' title='registered'></i>"+
                                        // "<i class='fas fa-user-slash px-1 text-danger' title='deactived'></i>"+
                                        // "<i class='fas fa-user-shield px-1 text-primary' title='limited'></i>"+
                                    '</div>' +
                                "</td>"+
                                "<td class='text-center'>"+gender+"</td>"+
                                "<td class='text-center'>"+element.id+"</td>"+
                                "<td class='text-center'>"+'member'+"</td>"+
                                "<td class='text-center'>"+element.tahun_akt+"</td>"+
                            "</tr>"
                        );
                    }
                    if (element.data_users != null) {
                        if (element.data_users.id != 64) {
                            $("tbody").append(
                                "<tr id = 'tr-"+ element.id +"'>"+
                                    "<td>"+
                                        '<div class="dropdown">' +
                                            '<button class="btn btn-secondary dropdown-toggle mr-1 btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                                // 'Actions' +
                                            '</button>' +
                                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdown-'+element.id+'">' +
                                                '<button class="dropdown-item" id ="member-delete-'+ element.id +'">Delete</button>' +
                                                '<button class="dropdown-item" data-toggle="modal" data-target="#editMember-'+element.id+'">Edit</button>' +
                                                '<button class="dropdown-item" id ="member-deactived-'+ element.id +'">Deactived</button>' +
                                                (element.data_users.role_id == 1 ?
                                                    '<button class="dropdown-item" id="member-demote-'+ element.id +'" style ="display:block">Demote</button>' +
                                                    '<button class="dropdown-item" id="member-promote-'+ element.id +'" style ="display:none">Promote</button>' :
                                                    '<button class="dropdown-item" id="member-demote-'+ element.id +'" style ="display:none">Demote</button>' +
                                                    '<button class="dropdown-item" id="member-promote-'+ element.id +'" style ="display:block">Promote</button>'
                                                ) +
                                            '</div>' +
                                            '<a class="text-dark" href="profile/detail?id=' + element.id + '">'+
                                                element.nama + 
                                            '</a>'+
                                            "<i class='fas fa-user-check px-1 text-success' title='registered'></i>"+
                                            // "<i class='fas fa-power-off px-1 text-success' title='active'></i>"+
                                        '</div>' +
                                    "</td>"+
                                    "<td class='text-center'>"+gender+"</td>"+
                                    "<td class='text-center'>"+element.id+"</td>"+
                                    "<td class='text-center'>"+element.data_users.data_divisi.divisi+"</td>"+
                                    "<td class='text-center'>"+element.tahun_akt+"</td>"+
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
                    $('#member-promote-'+element.id).on('click',function(){
                        if (confirm("Jadikan "+element.nama+" Admin ?")) {
                            $.ajax({
                                url: origin+"/api/data/"+element.data_users.id,
                                method: 'POST',
                                data:{
                                    'role_id':1
                                },
                                success: function (response) {
                                    // $('#dropdown-'+element.id+'').append(
                                    //     '<button class="dropdown-item" id ="member-demote-'+ element.id +'">Demote</button>'
                                    // );
                                    $('#member-demote-'+element.id+'').show();
                                    $('#member-promote-'+element.id+'').hide();
                                }
                            });
                        }
                    })
                    $('#member-demote-'+element.id).on('click',function(){
                        if (confirm("Menghapus "+element.nama+" dari Admin ?")) {
                            var role = 2;
                            if (element.data_users.divisi_id!=1) {
                                if (element.data_users.data_divisi.leader_id == element.data_users.id) {
                                    role = 3
                                }else{
                                    role = 4
                                }
                            }else if (element.data_users.program_id!=1) {
                                if (element.data_users.data_program.leader_id == element.data_users.id) {
                                    role = 3
                                }else{
                                    role = 4
                                }
                            }else{
                                role = 2
                            }
                            $.ajax({
                                url: origin+"/api/data/"+element.data_users.id,
                                method: 'POST',
                                data:{
                                    'role_id':role
                                },
                                success: function (response) {
                                    // $('#dropdown-'+element.id+'').append(
                                    //     '<button class="dropdown-item" id ="member-promote-'+ element.id +'">Promote</button>'
                                    // );
                                    $('#member-promote-'+element.id+'').show();
                                    $('#member-demote-'+element.id+'').hide();
                                }
                            });
                        }
                    })
                    // modal edit
                    $('#modal').append(
                        '<div>' +
                            '<div class="modal fade" id="editMember-'+element.id+'" tabindex="-1" role="dialog" aria-hidden="true">' +
                                '<div class="modal-dialog modal-lg" role="document">' +
                                    '<div class="modal-content">' +
                                        '<div class="modal-header bg-warning">' +
                                            '<h5 id="grafik-title" class="modal-title">Edit Member</h5>' +
                                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                                '<span aria-hidden="true">&times;</span>' +
                                            '</button>' +
                                        '</div>' +
                                        '<div class="card-body">' +
                                            '<form id="edit_memberForm-'+element.id+'">' +
                                                '<div class="form-group">' +
                                                    '<label for="edit_studentId">Student ID</label>' +
                                                    '<input type="text" class="form-control" id="edit_studentId-'+element.id+'" name="edit_student_id" value="'+element.id+'">' +
                                                '</div>' +
                                                '<div class="form-group">' +
                                                    '<label for="edit_studentId">Gender</label>' +
                                                    '<select class="form-control" id="edit_gender-'+element.id+'" name="gender">' +
                                                        '<option disabled selected>select</option>'+
                                                    '</select>'+
                                                '</div>' +
                                                '<div class="form-group">' +
                                                    '<label for="edit_member">Member Name</label>' +
                                                    '<input type="text" class="form-control" id="edit_memberName-'+element.id+'" name="edit_member_name" value="'+element.nama+'">' +
                                                '</div>' +
                                                '<div class="form-group">' +
                                                    '<label for="edit_batchYear">Batch Year</label>' +
                                                    '<input type="text" class="form-control" id="edit_batchYear-'+element.id+'" name="edit_batch_year" value="'+element.tahun_akt+'">' +
                                                '</div>' +
                                                '<button class="btn btn-success float-right mb-2">Submit</button>' +
                                            '</form>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>'
                    );
                    if (element.gender == 'M') {
                        $('#edit_gender-'+element.id).append(
                            '<option value="M" selected>Male</option>'+
                            '<option value="F">Female</option>'  
                        );
                    }else{
                        $('#edit_gender-'+element.id).append(
                            '<option value="M">Male</option>'+
                            '<option value="F" selected>Female</option>'  
                        );
                    }
                    // end modal edit 
                    $('#edit_memberForm-'+element.id).submit(function(event) {
                        // event.preventDefault();
                        $.ajax({
                            url: origin+"/api/member/"+element.id,
                            method: 'PUT',
                            data:{
                                'id': $('#edit_studentId-'+element.id+'').val()||null,
                                'gender': $('#edit_gender-'+element.id+'').val()||null,
                                'nama': $('#edit_memberName-'+element.id+'').val().toUpperCase()||null,
                                'tahun_akt': $('#edit_batchYear-'+element.id+'').val()||null,
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
                    "ordering": true,
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