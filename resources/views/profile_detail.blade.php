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
                    <b>Angkatan </b><b class="text-center" id="profile-angkatan"></b>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">profile</a></li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="profile">
                    <form class="form-horizontal">
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
                        <label for="inputExperience" class="col-sm-2 col-form-label">born of birth</label>
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
    if (sessionStorage.getItem('id')==null) {
      return window.location = window.location.origin+'/login';
    }
    var urlParams = new URLSearchParams(window.location.search);
    var user_detail = urlParams.get('id');
    sessionCheck(sessionStorage.getItem('login'));
    $.ajax({
        url: "/api/member/"+user_detail,
        method: "GET", // First change type to method here
        success: function(response) {
          var data = response.data;
          if (data.tanggal_lahir == null) {
              data.tanggal_lahir = "-";
          }if (data.tempat_lahir == null) {
              data.tempat_lahir = "-";
          }if (data.no_telp == null) {
              data.no_telp = "-";
          }if (data.gender == 'M') {
              data.gender = "Male";
          }else{
            data.gender = "Female  ";
          }
          $("#profile-name").text(data.nama); 
          $("#profile-nim").text(data.id);
          $("#profile-divisi-2").text(data.data_users.data_divisi.divisi);
          $("#profile-angkatan").text(data.tahun_akt);

          $("#name-profile").text(data.nama);
          $("#nim-profile").text(data.id);
          $("#gender-profile").text(data.gender);
          
          $("#tanggal_lahir-profile").text(data.tanggal_lahir);
          $("#tempat_lahir-profile").text(data.tempat_lahir);
          $("#telp-profile").text(data.no_telp);

          if (data.data_users.avatar!=null) {
            $('#profile-avatar').attr('src', `{{asset('storage/images/users-images/${data.data_users.avatar}')}}`);
          }
        }
      });
  });
</script>
</body>
</html>
