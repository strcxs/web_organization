<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard • Announcement</title>
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
          <div class="col">
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
            <div>
              <div id="announcement-content">
                {{-- announcement-content --}}
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
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="{{asset('storage/js/logincheck.js')}}"></script>
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

      var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
      });
      var channel = pusher.subscribe('announcement');
      channel.bind('sent-announcement', function(data) {
        $('#new-announcement').val("");
        if (data.data.data_users['avatar']!=null) {
            var img = "{{asset('storage/images/users-images/')}}"+'/'+data.data.data_users['avatar']
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
                            '<a href="#">'+data.data.data_users.data_anggota['nama']+'</a>'+
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
        $('#card-announcement-'+data.data['id']+'').remove();
      })

    });

    let page = 1; // Nomor halaman saat ini
    const itemsPerPage = 5; 
    let isLoading = false;
    let maxpage = false;

    function loadData() {
      if (!isLoading) {
        isLoading = true;
        $.ajax({
          url: "/api/announcement",
          method: "GET", // First change type to method here
          data: {
            pagination : true,
            page: page,
            perPage: itemsPerPage 
          },
          success: function(response) {
            var data_announcement = response.data.data
            if (data_announcement.length == 0) {
              maxpage = true;
            }
            data_announcement.forEach(function(announcement){
              if (announcement.data_users['avatar']!=null) {
                var img = "{{asset('storage/images/users-images/')}}"+'/'+announcement.data_users['avatar']
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
                        '<a href="#">'+announcement.data_users.data_anggota['nama']+'</a>'+
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
            page++;
            isLoading = false;
          }
        });
      }
    }

    function checkEndOfPage() {
      const scrollPosition = $(window).scrollTop() + $(window).height();
      const totalHeight = $(document).height();
      if (scrollPosition >= totalHeight-1 && !maxpage) {
        loadData();
      }
    }

    $(window).scroll(checkEndOfPage);
    loadData();

    loginCheck(sessionStorage.getItem('login'));

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
