<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard • User Profile</title>

  @include('include/link')

</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  @include('include/navbar')
  @include('include/sidebar')
  <div class="content-wrapper">
    <section class="content py-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       alt="User profile picture"
                       id="profile-avatar"
                       src="{{asset('storage/images/default/default-user-icon.jpg')}}"
                       style="width: 90px; height: 90px; object-fit: cover; border-radius: 50%;" 
                       class="img-circle elevation-2" alt="User Image" id="user_image"> 
                </div>
                <h3 class="profile-username text-center" id="profile-name"></h3>
                <p class="text-muted text-center" id="profile-nim"></p>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item text-center">
                    <b class="text-center" id="profile-divisi-2"></b>
                  </li>
                  <li class="list-group-item text-center">
                    <b class="text-center" id="profile-angkatan"></b>
                  </li>
                  <li class="list-group-item text-center">
                  </li>
                  <div class="btn btn-success" id="btnUpload"><i class="fas fa-upload"></i> Upload new image</div>
                  <input type="file" id="fileToUpload" style="display: none">
                  <div class="btn btn-danger my-2" id="btnDelete">delete image</div>
                  <input type="file" id="fileToUpload" style="display: none">
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">profile</a></li>
                  <li class="nav-item"><a class="nav-link" href="#profile-edit" data-toggle="tab">profile edit</a></li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="profile">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="username-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="name-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Student ID</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="nim-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Date of birth</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="tanggal_lahir-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Birthplace</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="tempat_lahir-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="telp-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Gender</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="gender-profile"></p>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane" id="profile-edit">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="username" placeholder="username">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="input_tanggal_lahir" class="col-sm-2 col-form-label">Date of birth</label>
                        <div class="col-sm-10">
                          <input type="date" class="form-control" id="tanggal_lahir" placeholder="Date of birth">
                        </div>
                      </div><div class="form-group row">
                        <label for="input_tempat_lahir" class="col-sm-2 col-form-label">Birthplace</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="tempat_lahir" placeholder="Birthplace">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="input" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="telepon" placeholder="Phone">
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <input type="submit" id="btnUpdate" class="btn btn-warning">
                        </div>
                      </div>
                    </form>
                  </div>
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
<script>
  $(document).ready(function(){
    if (sessionStorage.getItem('login')==null) {
      return window.location = window.location.origin+'/login';
    }
    sessionCheck(sessionStorage.getItem('id'));
    $.ajax({
        url: "/api/data/"+sessionStorage.getItem('id'),
        method: "GET", // First change type to method here
        success: function(response) {
          var data = response.data;
          if (data.data_anggota.tanggal_lahir == null) {
              data.data_anggota.tanggal_lahir = "-";
          }if (data.data_anggota.tempat_lahir == null) {
              data.data_anggota.tempat_lahir = "-";
          }if (data.data_anggota.no_telp == null) {
              data.data_anggota.no_telp = "-";
          }if (data.data_anggota.gender == 'M') {
              data.data_anggota.gender = "Male";
          }else{
            data.data_anggota.gender = "Female  ";
          }
          $(".d-block").text(data.data_anggota.nama);
          $("#profile-name").text(data.data_anggota.nama);
          $("#profile-nim").text(data.data_anggota.id);
          $("#profile-divisi-2").text(data.data_divisi.divisi);
          $("#profile-angkatan").text(data.data_anggota.tahun_akt);
          $(".c-block").text(data.data_divisi.divisi);
          
          $("#username-profile").text(data.username);
          $("#name-profile").text(data.data_anggota.nama);
          $("#nim-profile").text(data.data_anggota.id);
          $("#gender-profile").text(data.data_anggota.gender);

          $("#tanggal_lahir-profile").text(data.data_anggota.tanggal_lahir);
          $("#tempat_lahir-profile").text(data.data_anggota.tempat_lahir);
          $("#telp-profile").text(data.data_anggota.no_telp);

          if (data.avatar!=null) {
            $('#user_image').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
            $('#profile-avatar').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
          }
        }
      });
      $("#btnLogOut").click(function(){
        sessionStorage.clear();
        window.location = '../login';
      });
      $('#btnUpload').click(function(event){
        $('#fileToUpload').click();
      });
      $('#btnDelete').click(function(){
        $.ajax({
          url: "/api/data/"+sessionStorage.getItem('id'),
          method: "POST", // First change type to method here    
          data: {
            "avatar": "delete"
          },
          success: function(response) {
            var data = response.data;
            $('#user_image').attr('src', `{{asset('storage/images/default/default-user-icon.jpg')}}`);
            $('#profile-avatar').attr('src', `{{asset('storage/images/default/default-user-icon.jpg')}}`);
          }
        });
      });

      $('#fileToUpload').change(function(){
          var file_avatar = $('#fileToUpload').prop('files')[0];
          var avatar = new FormData();
          avatar.append('avatar', file_avatar);
          $.ajax({
          url: "/api/data/"+sessionStorage.getItem('id'),
          method: "POST", // First change type to method here    
          data: avatar,
          processData: false,
          contentType: false,
          success: function(response) {
            var data = response.data;
            alert(response.message);
            if (data.avatar != null) {
              $('#user_image').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
              $('#profile-avatar').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
            }
          }
        });  
      });
      $("#btnUpdate").click(function(event){ 
        var user = $("#username").val();
        var tanggal = $("#tanggal_lahir").val();
        var tempat = $("#tempat_lahir").val();
        var telp = $("#telepon").val();
        $.ajax({
          url: "/api/data/"+sessionStorage.getItem('id'),
          method: "POST", // First change type to method here    
          data: {
              "username": user,
              "tanggal_lahir": tanggal,
              "tempat_lahir": tempat,
              "no_telp": telp,
          },
          success: function(response) {
            alert(response.message);
          }
        });    
      });
  });
</script>
</body>
</html>
