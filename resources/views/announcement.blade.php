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
    });
    $.ajax({
      url: "/api/announcement",
      method: "GET", // First change type to method here
      success: function(response) {
        var data_announcement = response.data
        for (let index = 0; index < data_announcement.length; index++) {
          $.ajax({
            url: "/api/data/"+data_announcement[index]['user_id'],
            method: "GET", // First change type to method here
            success: function(response) {
              var data_user = response.data
              if (data_user['avatar']!=null) {
                var img = "{{asset('storage/images/users-images/')}}"+'/'+data_user['avatar']
              }
              else{
                var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
              }
              $('#announcement-content').append(

                '<div class="card p-3" id = "card-announcement-'+data_announcement[index]['id']+'">'+
                  '<div class="post p-2">'+
                    '<div class="user-block">'+
                      
                      '<div id="delete" class="card-tools float-right">'+
                        '<button id="btnDelete-'+data_announcement[index]['id']+'" type="button" class="btn btn-tool">'+
                          '<i class="fa-solid fa-x">X</i>'+
                        '</button>'+
                      '</div>'+

                      '<img class="img-circle img-bordered-sm" src='+img+' alt="user image">'+
                      '<span class="username">'+
                        '<a href="#">'+data_user['nama']+'</a>'+
                      '</span>'+
                      '<span class="description">'+data_announcement[index]['formatted_created_at']+'</span>'+
                    '</div>'+
                    '<p>'+
                        data_announcement[index]['title']+
                    '</p>'+
                  '</div>'+
                '</div>'
              );
              if (sessionStorage.getItem('login') == 64) {
                  document.getElementById('btnDelete-'+ data_announcement[index]['id']).style.display = "block";
              } else {
                  document.getElementById('btnDelete-'+ data_announcement[index]['id']).style.display = "none";
              }
              $('#btnDelete-' + data_announcement[index]['id']).on('click', function() {
                var ann = data_announcement[index]['id'];
                $.ajax({
                  url: "/api/announcement/"+ann,
                  method: "delete", // First change type to method here    
                  
                  success: function(response) {
                    var data = response.data;
                    $('#card-announcement-'+ann+'').remove();
                  },
                  error: function(error){

                  }
                });
              });
            },
            error: function(error) {

            }
          });
        }
      },
      error:function(error) {

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
            $.ajax({
                url: "/api/announcement",
                method: "POST", // First change type to method here
                data: {
                    "user_id": sessionStorage.getItem('login'),
                    "content": input
                },
                success: function(response) {
                    $('#new-announcement').val("");
                    if (response.data['avatar']!=null) {
                        var img = "{{asset('storage/images/users-images/')}}"+'/'+response.data['avatar']
                    }
                    else{
                        var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
                    }
                    $('#new-announcement').val("");
                    $('#announcement-content').prepend(
                        '<div class="card p-3">'+
                            '<div class="post p-2">'+
                                '<div class="user-block">'+

                                    '<div id="delete" class="card-tools float-right">'+
                                      '<button id="btnDelete-" type="button" class="btn btn-tool">'+
                                        '<i class="fa-solid fa-x">X</i>'+
                                      '</button>'+
                                    '</div>'+

                                    '<img class="img-circle img-bordered-sm" src='+img+' alt="user image">'+
                                    '<span class="username">'+
                                        '<a href="#">'+response.data['nama']+'</a>'+
                                    '</span>'+
                                    '<span class="description">'+response.data['formatted_created_at']+'</span>'+
                                '</div>'+
                                '<p>'+
                                    response.data['title']+
                                '</p>'+
                            '</div>'+
                        '</div>'           
                    );
                },
                error:function(error) {

                }
            });
        }
      }
  </script>
</body>
</html>
