<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  @include('include/link')
</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__wobble" src="{{asset('storage/images/icon_himaif.png')}}" alt="Hmif logo" height="120" width="120" style="opacity: 0.7">
      <h2 class="animation__wobble" style="font-weight: 400; color: rgb(0, 183, 255)">HMIF NURTANIO</h2>
    </div>

    @include('include/navbar')
    @include('include/sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Info boxes -->
          <div class="row py-3">
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-briefcase"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Course Work</span>
                  <span class="info-box-number">
                    5 
                  </span>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>

                <a href="dashboard/members" style="text-decoration: none;color: inherit;">
                  <div class="info-box-content">
                    <span class="info-box-text">Members</span>
                    <span id="members-count" class="info-box-number"></span>
                  </div>
                </a>
              </div>
            </div>
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-reply"></i></span>
                <a href="{{route('discuss')}}" style="text-decoration: none;color: inherit;">
                  <div class="info-box-content">
                    <span class="info-box-text">Discuss</span>
                    <span id="post-count" class="info-box-number"></span>
                  </div>
                </a>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-network-wired"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Divisi</span>
                  <span class="info-box-number">10</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
              <!-- MAP & BOX PANE -->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><i class="nav-icon fas fa-bullhorn"></i> Announcement</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <div class="d-md-flex">
                    <div id="announcement-content" class="p-1 flex-fill" style="overflow: hidden">
                      {{-- Announcement --}}
                      
                      {{-- end announcement --}}
                      
                    </div>
                  </div><!-- /.d-md-flex -->
                </div>
                <!-- /.card-body -->
              </div>

              <!-- TABLE: LATEST ORDERS -->
              <div class="card">
                <div class="card-header border-transparent">
                  <h3 class="card-title" style="color: red">Latest Absent (NOT AVAILABLE)</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table m-0">
                      <thead>
                      <tr>
                        <th>NIM</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Time</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td>55201120020</td>
                        <td>Rully D Faishal</td>
                        <td>2020</td>
                        <td>09.02</td>
                      </tr>
                      <tr>
                        <td>55201120001</td>
                        <td>Raka ramadhan</td>
                        <td>2020</td>
                        <td>09.10</td>
                      </tr>
                      <tr>
                          <td>55201120020</td>
                          <td>Tigar dolis</td>
                          <td>2022</td>
                          <td>09.02</td>
                      </tr>
                      <tr>
                          <td>55201120020</td>
                          <td>Satriawan nugraha</td>
                          <td>2021</td>
                          <td>09.02</td>
                      </tr>
                      <tr>
                          <td>55201120020</td>
                          <td>sophie nuraini</td>
                          <td>2022</td>
                          <td>09.02</td>
                      </tr>
                      <tr>
                          <td>55201120020</td>
                          <td>fashal kurniawan</td>
                          <td>2020</td>
                          <td>09.02</td>
                      </tr>
                      <tr>
                          <td>55201120020</td>
                          <td>Randy herawan</td>
                          <td>2020</td>
                          <td>09.02</td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                {{-- <div class="card-footer clearfix">
                  <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
                  <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
                </div> --}}
              </div>
            </div>

            <div class="col-md-4">
              <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-title">New Post</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <ul id="forum-content" class="products-list product-list-in-card pl-2 pr-2">
                  </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                  <a href="{{route('discuss')}}" class="uppercase">View All Post</a>
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- Info Boxes Style 2 -->
              <div class="info-box mb-3 bg-warning">
                <span class="info-box-icon"><i class="fas fa-coins"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Cash</span>
                  <span class="info-box-number">Rp. 2.105.200</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
              <div class="info-box mb-3 bg-success">
                <span class="info-box-icon"><i class="far fa-credit-card"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Advokasi</span>
                  <span class="info-box-number">Rp. 3.010.500</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
              <div class="info-box mb-3 bg-info">
                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">income</span>
                  <span class="info-box-number">Rp. 122.000</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              {{-- <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-title">New Post</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <ul id="forum-content" class="products-list product-list-in-card pl-2 pr-2">
                  </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                  <a href="{{route('discuss')}}" class="uppercase">View All Post</a>
                </div>
                <!-- /.card-footer -->
              </div> --}}
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <!-- Main Footer -->
    @include('include/footer')
  </div>
</body>
<!-- REQUIRED SCRIPTS -->
@include('include/script')

<script>
  $(document).ready(function(){
    if (sessionStorage.getItem('login')==null) {
      return window.location = '../login';
    }
    $.ajax({
      url: "/api/data/",
      method: "GET", // First change type to method here
      success: function(response) {
        $('#members-count').text(response.data.length)
      },
      error: function(error) {

      }
    });

    loginCheck(sessionStorage.getItem('login'));
    fetchDiscuss(3);
    fetchAnnouncement(3);
  });

  function limitWords(inputString, maxWords) {
      var words = inputString.split(' ');
      var limitedWords = words.slice(0, maxWords);
      var resultString = limitedWords.join(' ');
      return resultString+" <a href='dashboard/discuss'>...read more</a>";
  }
  function loginCheck(user){
    $.ajax({
      url: "/api/data/"+user,
      method: "GET", // First change type to method here
      success: function(response) {
        var data = response.data;
        if (data.avatar != null) {
            $('#user_image').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
            $('#profile-avatar').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
          }
        $(".d-block").text(data.nama);
        $(".c-block").text('divisi '+data.nama_divisi);
        // $('#user_image').attr('src', `{{asset('storage/images/users-images/${data.avatar}')}}`);
      },
      error: function(error){

      }
    });
    $("#btnLogOut").click(function(){
      sessionStorage.clear();
      window.location = '../login';
    });
  }
  function fetchDiscuss(page){
    $.ajax({
      url: "/api/forum",
      method: "GET", // First change type to method here
      success: function(response) {
        var data = response.data
        $('#post-count').text(response.data.length)
        for (let index = 0; index < page; index++) {
          if (data[index]['avatar']!=null) {
            var img = "{{asset('storage/images/users-images/')}}"+'/'+data[index]['avatar']
          }
          else{
            var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
          }
          $('#forum-content').append(
            '<div class="post p-3">'
              +'<div class="user-block">'+
                '<img class="img-circle img-bordered-sm" src='+img+' alt="user image" style="width: 43px; height: 43px; object-fit: cover; border-radius: 50%;">'+
                '<span class="username">'+
                  '<a href="dashboard/profile/detail?id='+data[index]['no_user']+'">'+data[index]['nama']+'</a>'+
                '</span>'+
                '<span class="description">'+data[index]['formatted_created_at']+'</span>'+
              '</div>'+
              '<p>'+
                limitWords(data[index]['content'],7)+
              '</p>'+
            '</div>'
          );
        }
      },
  });
  }
  function fetchAnnouncement(page){
    $.ajax({
      url: "/api/announcement",
      method: "GET", // First change type to method here
      success: function(response) {
        var data_user = response.data
        for (let index = 0; index < page; index++) {
          if (data_user[index]['avatar']!=null) {
            var img = "{{asset('storage/images/users-images/')}}"+'/'+data_user[index]['avatar']
          }
          else{
            var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
          }
          $('#announcement-content').append(
            '<div class="post p-3">'
              +'<div class="user-block">'+
                '<img class="img-circle img-bordered-sm" src='+img+' alt="user image style="width: 43px; height: 43px; object-fit: cover; border-radius: 50%;"">'+
                '<span class="username">'+
                  '<a href="dashboard/profile/detail?id='+data_user[index]['user_id']+'">'+data_user[index]['nama']+'</a>'+
                '</span>'+
                '<span class="description">'+data_user[index]['formatted_created_at']+'</span>'+
              '</div>'+
              '<p>'+
                data_user[index]['title']+
              '</p>'+
            '</div>'
          );
        }
      },
    });
  }
</script>
</html>
