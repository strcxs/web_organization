<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard •</title>
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
            <div class="col-12 col-md-8">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="info-box mb-3">
                    <a href="{{ route('members') }}" class="info-box-icon bg-danger elevation-1">
                      <i class="fas fa-users" style="pointer-events: none;"></i>
                    </a>
                    <a href="dashboard/members" style="text-decoration: none;color: inherit;">
                      <div class="info-box-content">
                        <span class="info-box-text">Members</span>
                        <span id="members-count" class="info-box-number"></span>
                      </div>
                    </a>
                  </div>
                </div>
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="info-box mb-3">
                    <a href="{{ route('discuss') }}" class="info-box-icon bg-success elevation-1">
                      <i class="fas fa-reply" style="pointer-events: none;"></i>
                    </a>
                    <a href="{{route('discuss')}}" style="text-decoration: none;color: inherit;">
                      <div class="info-box-content">
                        <span class="info-box-text">Discuss</span>
                        <span id="post-count" class="info-box-number"></span>
                      </div>
                    </a>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header bg-primary">
                      <h3 class="card-title">New Post</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body p-0">
                      <ul id="forum-content" class="products-list product-list-in-card pl-2 pr-2">
                      </ul>
                    </div>
                    <div class="card-footer text-center">
                      <a href="{{route('discuss')}}" class="uppercase">View All Post</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-title"><i class="nav-icon fas fa-bullhorn"></i> Announcement</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="d-md-flex">
                    <div id="announcement-content" class="p-1 flex-fill" style="overflow: hidden">
                      {{-- announcement-content --}}
                    </div>
                  </div>
                </div>
              </div>
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
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="{{asset('storage/js/logincheck.js')}}"></script>
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
      }
    });

    loginCheck(sessionStorage.getItem('login'));
    fetchDiscuss(6);
    fetchAnnouncement(6);

    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
    });

    var channel = pusher.subscribe('announcement');
    channel.bind('sent-announcement', function(data) {
      fetchAnnouncement(3);
    });
  });
  function limitWords(inputString, maxWords) {
      var words = inputString.split(' ');
      var limitedWords = words.slice(0, maxWords);
      var resultString = limitedWords.join(' ');
      return resultString+" <a href='dashboard/discuss'>...read more</a>";
  }
  
  function fetchDiscuss(page){
    $.ajax({
      url: "/api/forum",
      method: "GET", // First change type to method here
      success: function(response) {
        var data = response.data
        $('#post-count').text(response.data.length)
        for (let index = 0; index < page; index++) {
          if (data[index].data_users['avatar']!=null) {
            var img = "{{asset('storage/images/users-images/')}}"+'/'+data[index].data_users['avatar']
          }
          else{
            var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
          }
          $('#forum-content').append(
            '<div class="post p-3">'
              +'<div class="user-block">'+
                '<img class="img-circle img-bordered-sm" src='+img+' alt="user image" style="width: 43px; height: 43px; object-fit: cover; border-radius: 50%;">'+
                '<span class="username">'+
                  '<a href="dashboard/profile/detail?id='+data[index].data_users.data_anggota['id']+'">'+data[index].data_users.data_anggota['nama']+'</a>'+
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
          if (data_user[index].data_users['avatar']!=null) {
            var img = "{{asset('storage/images/users-images/')}}"+'/'+data_user[index].data_users['avatar']
          }
          else{
            var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
          }
          $('#announcement-content').append(
            '<div class="post p-3">'
              +'<div class="user-block">'+
                '<img class="img-circle img-bordered-sm" src='+img+' alt="user image style="width: 43px; height: 43px; object-fit: cover; border-radius: 50%;"">'+
                '<span class="username">'+
                  '<a href="dashboard/profile/detail?id='+data_user[index]['user_id']+'">'+data_user[index].data_users.data_anggota['nama']+'</a>'+
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
