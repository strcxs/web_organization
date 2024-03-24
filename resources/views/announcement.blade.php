<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | User Profile</title>
  @include('include/link')
</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  @include('include/navbar')
  @include('include/sidebar')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content py-3">
      <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <div class="col">
          {{-- <div class="col-md-8"> --}}
            <!-- annnouncement PANEL -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title text-primary"><i class="nav-icon fas fa-bullhorn"></i> Announcement</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="post p-3" style="display: none" id="admin-div">
                <p>add new announcement</p>
                <input id="new-announcement" class="form-control form-control-sm" type="text" placeholder="write new announcement" onkeydown=AnnouncementkeyPress(event)>
              </div>
            </div>
              <!-- /.card-header -->
            <div>
              <div id="announcement-content">
                {{-- <div>

                </div>
                <div id="forum-comment" class="card-body p-3">
    
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
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    if (sessionStorage.getItem('login') == 64) {
        document.getElementById("admin-div").style.display = "block";
    } else {
        document.getElementById("admin-div").style.display = "none";
    }
    $(document).ready(function(){
      if (sessionStorage.getItem('login')==null) {
        return window.location = '../login';
      }

      var pusher = new Pusher('71d8b7362ac9e3875667', {
        cluster: 'ap1'
      });
      var channel = pusher.subscribe('announcement');
      channel.bind('sent-announcement', function(data) {
        $('#new-announcement').val("");
        if (data.data['avatar']!=null) {
            var img = "{{asset('storage/images/users-images/')}}"+'/'+data.data['avatar']
        }
        else{
            var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
        }
        $('#new-announcement').val("");
        $('#announcement-content').prepend(
            '<div class="card p-3" id="card-announcement-'+data.data['id']+'">'+
                '<div class="post p-2">'+
                    '<div class="user-block">'+

                        '<div id="delete" class="card-tools float-right">'+
                          '<button id="btnDelete-'+data.data['id']+'" type="button" class="btn btn-tool">'+
                            '<i class="fa-solid fa-x">X</i>'+
                          '</button>'+
                        '</div>'+

                        '<img class="img-circle img-bordered-sm" src='+img+' alt="user image">'+
                        '<span class="username">'+
                            '<a href="#">'+data.data['nama']+'</a>'+
                        '</span>'+
                        '<span class="description">'+data.data['formatted_created_at']+'</span>'+
                    '</div>'+
                    '<p>'+
                        data.data['title']+
                    '</p>'+
                '</div>'+
            '</div>'           
        );
        if (sessionStorage.getItem('login') == 64) {
            document.getElementById('btnDelete-'+ data.data['id']).style.display = "block";
        } else {
            document.getElementById('btnDelete-'+ data.data['id']).style.display = "none";
        }
        $('#btnDelete-' + data.data['id']).on('click', function() {
          deleteAnnouncement(data.data['id'])
        });
      });

      channel.bind('delete-announcement', function(data){
        console.log(data.data);
        $('#card-announcement-'+data.data['id']+'').remove();
      })

    });
    $.ajax({
      url: "/api/announcement",
      method: "GET", // First change type to method here
      success: function(response) {
        var data_announcement = response.data
        data_announcement.forEach(function(announcement){
          if (announcement['avatar']!=null) {
            var img = "{{asset('storage/images/users-images/')}}"+'/'+announcement['avatar']
          }
          else{
            var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
          }
          $('#announcement-content').append(

            '<div class="card p-3" id = "card-announcement-'+announcement['id']+'">'+
              '<div class="post p-2">'+
                '<div class="user-block">'+
                  
                  '<div id="delete" class="card-tools float-right">'+
                    '<button id="btnDelete-'+announcement['id']+'" type="button" class="btn btn-tool">'+
                      '<i class="fa-solid fa-x">X</i>'+
                    '</button>'+
                  '</div>'+

                  '<img class="img-circle img-bordered-sm" src='+img+' alt="user image">'+
                  '<span class="username">'+
                    '<a href="#">'+announcement['nama']+'</a>'+
                  '</span>'+
                  '<span class="description">'+announcement['formatted_created_at']+'</span>'+
                '</div>'+
                '<p>'+
                    announcement['title']+
                '</p>'+
              '</div>'+
            '</div>'
          );
          if (sessionStorage.getItem('login') == 64) {
              document.getElementById('btnDelete-'+ announcement['id']).style.display = "block";
          } else {
              document.getElementById('btnDelete-'+ announcement['id']).style.display = "none";
          }
          $('#btnDelete-' + announcement['id']).on('click', function() {
            deleteAnnouncement(announcement['id'])
          });
        });
      }
    });
    $.ajax({
        url: "/api/data/"+sessionStorage.getItem('login'),
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

      function AnnouncementkeyPress(event){
        if (event.keyCode === 13) {
            var input = $('#new-announcement').val();
            submitAnnouncement(input)
        }
      }
      function deleteAnnouncement(id) {
        $.ajax({
          url: "/api/announcement/"+id,
          method: "delete"
        });
      }

      var progres_forum = false;
      function submitAnnouncement(content) {
        if (progres_forum) {
          return;
        };

        progres_forum = true;
        $.ajax({
          url: "/api/announcement",
          method: "POST", // First change type to method here
          data: {
              "user_id": sessionStorage.getItem('login'),
              "content": content
          },
          complete: function(){
            progres_forum = false;
          }
        });
      }
  </script>
</body>
</html>
