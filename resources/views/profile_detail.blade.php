<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile</title>

  @include('include/link')

</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  @include('include/navbar')
  @include('include/sidebar')
  <div class="content-wrapper">
    <section class="content py-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
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
                  <li class="list-group-item">
                    <b>Angkatan</b> <b class="float-right" id="profile-angkatan"></b>
                  </li>
                  <li class="list-group-item">
                    <b>Divisi</b> <b class="float-right" id="profile-divisi-2"></b>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  {{-- <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li> --}}
                  <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">profile</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="profile">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="name-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Nim</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="nim-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Tanggal lahir</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="tanggal_lahir-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Tempat lahir</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="tempat_lahir-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Angkatan</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="angkatan-profile"></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Telepon</label>
                        <div class="col-sm-10">
                          <p class="col-form-label" id="telp-profile"></p>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
  </div>
  @include('include/footer')
</div>
<!-- jQuery -->
@include('include/script')
<script>
  $(document).ready(function(){
    if (sessionStorage.getItem('login')==null) {
      return window.location = '../../login';
    }
    var urlParams = new URLSearchParams(window.location.search);
    var user_detail = urlParams.get('id');

    $.ajax({
        url: "/api/data/"+user_detail,
        method: "GET", // First change type to method here
        success: function(response) {
          var data = response.data;
          console.log(data);
          if (data.tanggal_lahir == null) {
              data.tanggal_lahir = "-";
          }if (data.tempat_lahir == null) {
              data.tempat_lahir = "-";
          }if (data.no_telp == null) {
              data.no_telp = "-";
          }
          $("#profile-name").text(data.nama); 
          $("#profile-nim").text(data.nim);
          $("#profile-divisi-2").text(data.nama_divisi);
          $("#profile-angkatan").text(data.tahun_akt);

          $("#name-profile").text(data.nama);
          $("#nim-profile").text(data.nim);
          $("#angkatan-profile").text(data.tahun_akt);
          
          $("#tanggal_lahir-profile").text(data.tanggal_lahir);
          $("#tempat_lahir-profile").text(data.tempat_lahir);
          $("#telp-profile").text(data.no_telp);

          if (data.avatar!=null) {
            $('#profile-avatar').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
          }
        },
        error: function(error){

        }
      });
      $.ajax({
        url: "/api/data/"+sessionStorage.getItem('login'),
        method: "GET", // First change type to method here
        success: function(response) {
          var data = response.data;
          console.log(data);
          if (data.tanggal_lahir == null) {
              data.tanggal_lahir = "-";
          }if (data.tempat_lahir == null) {
              data.tempat_lahir = "-";
          }if (data.no_telp == null) {
              data.no_telp = "-";
          }
          $(".d-block").text(data.nama);
          $(".c-block").text('divisi '+data.nama_divisi);

          if (data.avatar!=null) {
            $('#user_image').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
          }
        },
        error: function(error){

        }
      });
      $("#btnLogOut").click(function(){
        sessionStorage.clear();
        window.location = '../login';
      });
  });
</script>
</body>
</html>
