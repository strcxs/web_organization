<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | User Profile</title>
  @include('include/link')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
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
                                {{-- <th class="text-center">Main Vote</th> --}}
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-content">
                            {{-- <tr>
                                <td>Calon Ketua Himpunan</td>
                                <td class="text-center">
                                    <button class="btn btn-primary">Public</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-warning">Edit</button>
                                    <button class="btn btn-danger">Hapus</button>
                                </td>
                            </tr> --}}
                            {{-- <tr>
                                <td>Studi banding Himpunan</td>
                                <td class="text-center">
                                    <button class="btn btn-secondary">Private</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-warning">Edit</button>
                                    <button class="btn btn-danger">Hapus</button>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>
                    <button class="btn btn-success"  data-toggle="modal" data-target="#addTopic"><i class="fas fa-solid fa-plus"></i>  Add new</button>
                </div>
                {{-- modal add --}}
                <div class="modal fade" id="addTopic" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 id="grafik-title" class="modal-title" id="modalCandidate1Label">Add new voting</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="card-body">
                                <form id="topicForm">
                                    <div class="form-group">
                                        <label for="topicName">Topic Name</label>
                                        <input type="text" class="form-control" id="topicName" name="topic_name" required placeholder="input new topic name">
                                    </div>
                            
                                    <div class="form-group">
                                        <label for="topicInfo">Topic Information:</label>
                                        <input class="form-control" id="topicInfo" name="topic_information" required placeholder="information of topic">
                                    </div>
                                    
                                    <div id="option-content">
                                        {{-- <div class="card">
                                            <div class="card-header">
                                              <h3 class="card-title">opsi 1</h3>
                                              <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fa-solid fas fa-caret-down"></i>
                                                </button>
                                              </div>
                                            </div>
                                            <div class="card-body collapse">
                                                <form id="OptionForm">
                                                    <div class="form-group">
                                                        <label for="number">List Number</label>
                                                        <input type="text" class="form-control" id="number" name="number" required placeholder="input new topic name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="value">Value</label>
                                                        <input type="text" class="form-control" id="value" name="value" required placeholder="input new topic name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="visi">visi</label>
                                                        <textarea type="text" class="form-control" id="visi" name="visi" required placeholder="input new topic name"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="misi">Misi</label>
                                                        <textarea type="text" class="form-control" id="misi" name="misi" required placeholder="input new topic name"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="misi">Upload Image (.PNG)</label><br>
                                                        <input type="file" id="value" name="value" required accept=".png">
                                                    </div>
                                                </form>
                                            </div>
                                        </div> --}}
                                    </div>
                                </form>
                                {{-- <div>
                                    <button id="form-add-option" class="btn btn-success rounded-circle btn-sm float-left mb-2"><i class="fas fa-solid fa-plus"></i></button>
                                </div> --}}
                                <button id="form-topic" class="btn btn-primary float-right mb-2">Submit</button>
                            </div>
                            {{-- <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div> --}}
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
  </div>
  @include('include/footer')
</div>
<!-- ./wrapper -->
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
        });   
        $.ajax({
            url: "/api/topic",
            method: "GET", // First change type to method here
            success: function(response) {
                var topic = response.data
                for (let index = 0; index < topic.length; index++) {
                    $('#table-content').append(
                        '<tr id="tr-del-'+topic[index]['id']+'">'+
                            '<td class="text-primary">'+topic[index]['topic_name']+'</td>'+
                            '<td class="text-center">'+
                                '<button class="btn btn-warning m-1" data-toggle="modal" data-target="#editTopic-'+topic[index]['id']+'"><i class="nav-icon fas fa-pen"></i></button>'+
                                '<button id="del-topic-'+topic[index]['id']+'" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>'+
                            '</td>'+
                        '</tr>'
                    );
                    $('#card-modal').append(
                        '<div class="modal fade" id="editTopic-'+topic[index]['id']+'" tabindex="-1" role="dialog" aria-hidden="true">'+
                            '<div class="modal-dialog modal-lg" role="document">'+
                                '<div class="modal-content">'+
                                    '<div class="modal-header bg-warning">'+
                                        '<h5 id="grafik-title" class="modal-title" id="modalCandidate1Label">Edit vote Topic</h5>'+
                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                                            '<span aria-hidden="true">&times;</span>'+
                                        '</button>'+
                                    '</div>'+
                                    '<div class="card-body">'+
                                        '<form id="edit-topicForm-'+topic[index]['id']+'">'+
                                            '<div class="form-group">'+
                                                '<label for="topicName">Topic Name</label>'+
                                                '<input type="text" class="form-control" id="topicName" name="topic_name" placeholder="'+topic[index]['topic_name']+'">'+
                                            '</div>'+
                                            '<div class="form-group">'+
                                                '<label for="topicInfo">Topic Information:</label>'+
                                                '<textarea class="form-control" id="topicInfo" name="topic_information" placeholder="'+topic[index]['topic_information']+'"></textarea>'+
                                            '</div>'+
                                            '<div id="edit-topic-'+topic[index]['id']+'" class="btn btn-warning float-right">Edit</div><br>'+
                                            '</form>'+
                                            '<label for="topicInfo">Option:</label>'+
                                            '<div id="option-content-'+topic[index]['id']+'">'+
                                        '</div>'+
                                        '<div>'+
                                            '<div id="form-add-option-'+topic[index]['id']+'" class="btn btn-success rounded-circle btn-sm float-left mb-2"><i class="fas fa-solid fa-plus"></i></div>'+
                                        '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                    // var value = topic[index]['id'];
                    // console.log(value);
                    $.ajax({
                        url: "/api/voting/"+topic[index]['id'],
                        method: "GET", // First change type to method here   
                        success: function(response) {
                            console.log(response.data);
                            for (let indexx = 0; indexx < response.data.length; indexx++) {
                                console.log(topic[index]['id']);
                                $('#option-content-'+topic[index]['id']+'').append(
                                    '<div class="card">'+
                                        '<div class="card-header">'+
                                            '<h3 class="card-title">option 1</h3>'+
                                            '<div class="card-tools">'+
                                                '<button id="b-sbmt-'+indexx+'" type="button" class="btn btn-tool" data-card-widget="collapse">'+
                                                    '<i id="sbmt-'+indexx+'" class="fa-solid fas fa-caret-down"></i>'+
                                                '</button>'+
                                                '<button id="b-del-'+indexx+'" type="button" class="btn btn-tool d-none" data-card-widget="collapse">'+
                                                    '<i id="del-'+indexx+'" class="nav-icon fas fa-trash text-danger"></i>'+
                                                '</button>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="card-body collapse">'+
                                            '<form id="OptionForm-'+indexx+'">'+
                                                '<div class="form-group">'+
                                                    '<label for="number">List Number</label>'+
                                                    '<input type="text" class="form-control" id="number" name="number" required placeholder="input new topic name">'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label for="value">Value</label>'+
                                                    '<input type="text" class="form-control" id="value-'+indexx+'" name="value" required placeholder="input new topic name">'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label for="visi">visi</label>'+
                                                    '<textarea type="text" class="form-control" id="visi" name="visi" required placeholder="input new topic name"></textarea>'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label for="misi">Misi</label>'+
                                                    '<textarea type="text" class="form-control" id="misi" name="misi" required placeholder="input new topic name"></textarea>'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label for="misi">Upload Image (.PNG)</label><br>'+
                                                    '<input type="file" id="value" name="value" accept=".png">'+
                                                '</div>'+
                                                '<div id="form-option-'+indexx+'" data-card-widget="collapse" class="btn btn-primary mb-2">OK</div>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>'
                                );
                                maxOption++
                            }
                        }
                    });
                    var maxOption = 0;
                    $('#form-add-option-'+response.data[index]['id']).on('click', function() {
                        var number = maxOption+1;
                        if (maxOption<4){
                            $('#option-content-'+response.data[index]['id']+'').append(
                                '<div class="card">'+
                                    '<div class="card-header">'+
                                        '<h3 class="card-title">option '+number+'</h3>'+
                                        '<div class="card-tools">'+
                                            '<button id="b-sbmt-'+response.data[index]['id']+'" type="button" class="btn btn-tool" data-card-widget="collapse">'+
                                                '<i id="sbmt-'+response.data[index]['id']+'" class="fa-solid fas fa-caret-down"></i>'+
                                            '</button>'+
                                            '<button id="b-del-'+response.data[index]['id']+'" type="button" class="btn btn-tool d-none" data-card-widget="collapse">'+
                                                '<i id="del-'+response.data[index]['id']+'" class="nav-icon fas fa-trash text-danger"></i>'+
                                            '</button>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="card-body collapse">'+
                                        '<form id="OptionForm-'+response.data[index]['id']+'">'+
                                            '<div class="form-group">'+
                                                '<label for="number">List Number</label>'+
                                                '<input type="text" class="form-control" id="number" name="number" required placeholder="input new topic name">'+
                                            '</div>'+
                                            '<div class="form-group">'+
                                                '<label for="value">Value</label>'+
                                                '<input type="text" class="form-control" id="value-'+response.data[index]['id']+'" name="value" required placeholder="input new topic name">'+
                                            '</div>'+
                                            '<div class="form-group">'+
                                                '<label for="visi">visi</label>'+
                                                '<textarea type="text" class="form-control" id="visi" name="visi" required placeholder="input new topic name"></textarea>'+
                                            '</div>'+
                                            '<div class="form-group">'+
                                                '<label for="misi">Misi</label>'+
                                                '<textarea type="text" class="form-control" id="misi" name="misi" required placeholder="input new topic name"></textarea>'+
                                            '</div>'+
                                            '<div class="form-group">'+
                                                '<label for="misi">Upload Image (.PNG)</label><br>'+
                                                '<input type="file" id="value" name="value" accept=".png">'+
                                            '</div>'+
                                            '<div id="form-option-'+response.data[index]['id']+'" data-card-widget="collapse" class="btn btn-primary mb-2">OK</div>'+
                                        '</form>'+
                                    '</div>'+
                                '</div>'
                            );
                            var number = maxOption
                            var form = true
                            $('#form-option-'+maxOption+'').on('click', function() {
                                for (let index = 0; index < number+1; index++) {
                                    if(!$('#OptionForm-'+number)[0].checkValidity()){
                                        form = false
                                    }
                                }
                                console.log(form);
                                if ($('#OptionForm-'+number)[0].checkValidity()) {
                                    $('#sbmt-'+number).removeClass().addClass("fa-solid fas fa-check text-success");
                                    $('#b-del-'+number).removeClass().addClass("btn btn-tool d-inline");
                                    $('#b-sbmt-'+number).prop('disabled', true);
                                }else{
                                    $('#sbmt-'+number).removeClass().addClass("fa-solid fas fa-exclamation text-danger");
                                    alert('please fill out the form!')
                                }
                            });
                            maxOption++;
                        }
                        else{
                            alert('i\'m sorry, max 4 option')
                        }
                    });
                    $('#del-topic-' + response.data[index]['id']).on('click', function() {
                        var del = response.data[index]['id'];
                        $.ajax({
                            url: "/api/topic/"+del,
                            method: "delete", // First change type to method here    
                            success: function(response) {
                                console.log(response);
                                $('#tr-del-'+del+'').remove();
                            },
                            error:function(error){
                                console.log(error);
                            }
                        });
                    });
                    $('#edit-topic-'+response.data[index]['id']).on('click', function() {
                        var edit = response.data[index]['id'];
                        // console.log($("#edit-topicForm-"+edit).serialize());
                        $.ajax({
                            url: "/api/topic/"+edit,
                            method: "post", // First change type to method here   
                            data:$("#edit-topicForm-"+edit).serialize(), 
                            success: function(response) {
                                // console.log(response);
                                window.location.reload();
                            }
                        });
                    });
                }
            }
        });      
        $('#form-topic').on('click', function() {
            // console.log($("#topicForm").serialize());
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
        $("#btnLogOut").click(function(){
          sessionStorage.clear();
          window.location = '../login';
        });
    });
</script>
</body>
</html>
