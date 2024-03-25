<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | User Profile</title>
  @include('include/link')
  <style>
    .card {
        box-shadow: none !important;
        margin-bottom: 0px;
    }
    /* .btn-container{
      refresh
      z-index: 1;
      position: fixed;
      top: 17%;
      left: 50%;
      transform: translate(-50%, -50%);
    } */
  </style>
</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  @include('include/navbar')
  @include('include/sidebar')
  <div class="content-wrapper">
    
    {{-- refresh
    <div class="btn-container">
      <button id="refreshButton" class="btn btn-warning rounded-pill shadow" onclick="#">
        new post <i class="fas fa-sync-alt"></i>
      </button>
    </div> --}}
    <!-- Content Header (Page header) -->
    <section class="content py-3">
      <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <div class="col">
          {{-- <div class="col-md-8"> --}}
            <div class="card mb-3">
              <div class="card-header bg-primary">
                <h3 class="card-title"><i class="nav-icon fas fa-bolt"></i> Mari Bersuara!</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="post p-3">
                {{-- <p>suarakan pikiranmu disini</p> --}}
                <div class="row">
                  <input id="send-post" class="form-control col-10" type="text" placeholder="suarakan pikiranmu disini.." onkeydown=PostkeyPress(event)>
                  <button id="sent-post" class="btn btn-primary form-control col-2">
                    <i class="nav-icon fas fa-paper-plane"></i>
                  </button>
                </div>
              </div>
              
            </div>
              <!-- /.card-header -->
            <div class="card">
              <div id="forum-content">
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
    var datasave = [];
    var id_comment = [];
    $(document).ready(function(){
      if (sessionStorage.getItem('login')==null) {
        return window.location = '../login';
      }

      var pusher = new Pusher('71d8b7362ac9e3875667', {
        cluster: 'ap1'
      });

      var channel = pusher.subscribe('discuss');
      channel.bind('sent-discuss', function(data) {
        if (data.data['avatar']!=null) {
          var img = "{{asset('storage/images/users-images/')}}"+'/'+data.data['avatar']
        }
        else{
          var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
        }
        $('#forum-content').prepend(
          '<div class="card p-3" id=card-content-'+data.data['id']+'>'+
            '<div class="post p-2">'+
              '<div class="user-block">'+

                '<div id="delete" class="card-tools float-right">'+
                  '<button id="btnDelete-'+data.data['id']+'" type="button" class="btn btn-tool">'+
                    '<i class="fa-solid fa-x">X</i>'+
                  '</button>'+
                '</div>'+

                '<img class="img-circle img-bordered-sm" src='+img+' alt="user image" style="width: 43px; height: 43px; object-fit: cover; border-radius: 50%;" >'+
                '<span class="username">'+
                  '<a href="profile/detail?id='+data.data['no_user']+'">'+data.data['nama']+'</a>'+
                '</span>'+
                '<span class="description">'+data.data['formatted_created_at']+'</span>'+
              '</div>'+
              '<p>'+
                data.data['content']+
              '</p>'+
              '<p>'+
                '<span>'+
                  '<a id="count-comment-'+data.data['id']+'" class="link-black text-sm">'+
                  '</a>'+
                '</span>'+
              '</p>'+
              '<div class="row">'+
                '<input id="send-comment-'+data.data['id']+'"  class="form-control col-10" type="text" placeholder="Type a comment" onkeydown=keyPress(event,'+data.data['id']+')>'+
                '<button id="sent-comment-'+data.data['id']+'" class="btn btn-primary form-control col-2">'+
                  '<i class="nav-icon fas fa-paper-plane"></i>'+
                '</button>'+
              '</div>'+
            '</div>'+
              '<div id="forum-comment-'+data.data['id']+'" class="card-footer card-comments d-none">'+
            '</div>'+
          '</div>'

        );
        if (sessionStorage.getItem('login') == 64) {
                  document.getElementById('btnDelete-'+ data.data['id']).style.display = "block";
        } else {
            document.getElementById('btnDelete-'+ data.data['id']).style.display = "none";
        }
        $('#btnDelete-' + data.data['id']).on('click', function() {
          deleteForum(data.data['id'])
        });

        $('#sent-comment-'+data.data['id']+'').on('click', function() {
          var input = $('#send-comment-'+data.data['id']+'').val();
          if (input != null || input != " ") {
            submitComment(data.data['id'],input)
          }
        });
      });

      channel.bind('delete-discuss', function(data) {
        $('#card-content-'+data.data['id']+'').remove();
      });

      channel.bind('sent-comment', function(data) {

        $.ajax({
          url: "/api/comment/" + data.data['forum_id'],
          method: "GET",
          success: function(response) {
            var comments = response.data;
            var commentCountElement = $('#count-comment-' + data.data['forum_id']);

            if (comments && comments.length > 0) {
              commentCountElement.html(
                '<div class="card-tools">' +
                  '<button type="button" class="btn btn-tool" data-card-widget="collapse" aria-expanded="false">' +
                    '<i class="far fa-comments mr-1"></i> Comments (' + comments.length + ')' +
                  '</button>' +
                '</div>'
              );
              var commentContainer = $('#forum-comment-' + data.data['forum_id']);
              commentContainer.removeClass().addClass("card-footer card-comments collapse");

              $('#send-comment-'+data.data['forum_id']).val("");
              if (sessionStorage.getItem('login') == data.data['no_user']) {
                $('#forum-comment-'+data.data['forum_id']).removeClass().addClass("card-footer card-comments ");
              }
              comments.forEach(function(comment) {
                if (!id_comment.includes(comment.id)){
                  renderComment(comment, commentContainer, data.data['forum_id'], prepend=false);
                  id_comment.push(comment.id)
                }
              });

              // renderComment(comment, commentContainer, data.data['forum_id'], prepend=false);
            }
          }
        });
      });


      channel.bind('delete-comment', function(data) {
        $('#card-comment-' + data.data['id']).remove();
        if ($('#forum-comment-' + data.data['id_forum'] + ' .card-comment').length == 0) {
          $('#forum-comment-' + data.data['id_forum']).removeClass().addClass("card-footer card-comments d-none");
          $('#count-comment-' + data.data['id_forum']).html('');
        }else{
          $('#count-comment-' + data.data['id_forum']).html(
            '<div class="card-tools">' +
              '<button type="button" class="btn btn-tool" data-card-widget="collapse" aria-expanded="false">' +
                '<i class="far fa-comments mr-1"></i> Comments (' + $('#forum-comment-' + data.data['id_forum'] + ' .card-comment').length + ')' +
              '</button>' +
            '</div>'
          );
        }
      });

      //fetch data forum
      let page = 1;
      const itemsPerPage = 10; 
      let isLoading = false;

      // Fungsi untuk memuat data dari server
      function loadData() {
        if (!isLoading) {
          isLoading = true;
          $.ajax({
            url: "/api/forum",
            method: "GET",
            data: {
              pagination : true,
              page: page,
              perPage: itemsPerPage 
            },
            success: function(response) {
              var forums = response.data.data;
              $('#post-count').text(forums.length);

              forums.forEach(function(forum) {
                var img = forum.avatar ? "{{asset('storage/images/users-images/')}}" + '/' + forum.avatar : "{{asset('storage/images/default/default-user-icon.jpg')}}";

                $('#forum-content').append(
                  '<div class="card p-3" id="card-content-' + forum.id + '">' +
                    '<div class="post p-2">' +
                      '<div class="user-block">' +
                        '<div id="delete" class="card-tools float-right">' +
                          '<button id="btnDelete-' + forum.id + '" type="button" class="btn btn-tool">' +
                            '<i class="fa-solid fa-x">X</i>' +
                          '</button>' +
                        '</div>' +
                        '<img class="img-circle img-bordered-sm" src=' + img + ' alt="user image" style="width: 43px; height: 43px; object-fit: cover; border-radius: 50%;" >' +
                        '<span class="username">' +
                          '<a href="profile/detail?id=' + forum.no_user + '">' + forum.nama + '</a>' +
                        '</span>' +
                        '<span class="description">' + forum.formatted_created_at + '</span>' +
                      '</div>' +
                      '<p>' + forum.content + '</p>' +
                      '<p>' +
                        '<span>' +
                          '<a id="count-comment-' + forum.id + '" class="link-black text-sm"></a>' +
                        '</span>' +
                      '</p>' +
                      '<div class="row">' +
                        '<input id="send-comment-' + forum.id + '" class="form-control col-10" type="text" placeholder="Type a comment" onkeydown="keyPress(event,' + forum.id + ')">' +
                        '<button id="sent-comment-' + forum.id + '" class="btn btn-primary form-control col-2">' +
                          '<i class="nav-icon fas fa-paper-plane"></i>' +
                        '</button>' +
                      '</div>' +
                    '</div>' +
                    '<div id="forum-comment-' + forum.id + '" class="card-footer card-comments d-none">' +
                    '</div>' +
                  '</div>'
                );
                
                //admin only
                if (sessionStorage.getItem('login') == 64) {
                    document.getElementById('btnDelete-'+ forum.id).style.display = "block";
                } else {
                    document.getElementById('btnDelete-'+ forum.id).style.display = "none";
                }

                // Delete forum
                $('#btnDelete-' + forum.id).on('click', function() {
                  deleteForum(forum.id);
                });

                // Fetch comments for the forum
                fetchComments(forum.id);

                // Handle comment submission
                $('#sent-comment-' + forum.id).on('click', function() {
                  var input = $('#send-comment-' + forum.id).val();
                  if (input.trim() !== "") {
                    submitComment(forum.id, input);
                  }
                });
              });
              page++;
              isLoading = false;
            },
            error: function(error) {
              console.error('Error fetching forum data:', error);
              isLoading = false;
            }
          });
        }
      }

      // Fungsi untuk memeriksa apakah telah mencapai akhir halaman dan memuat data jika ya
      function checkEndOfPage() {
        const scrollPosition = $(window).scrollTop() + $(window).height();
        const totalHeight = $(document).height();
        if (scrollPosition >= totalHeight) {
          loadData();
        }
      }

      $(window).scroll(checkEndOfPage);

      loadData();

      var progress = false;
      $('#sent-post').on('click', function() {
        if (progress) {
          return;
        };

        progress = true;
        var input = $('#send-post').val();
        if (input != null || input != " ") {
          submitForum(input);
        }
      });

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
          error: function(error){
  
          }
        });
        $("#btnLogOut").click(function(){
          sessionStorage.clear();
          window.location = '../login';
        });
    });

    var progres_comment = false;
    var progres_forum = false;

    // Function to fetch comments for a forum
    function fetchComments(forumId) {
      $.ajax({
        url: "/api/comment/" + forumId,
        method: "GET",
        success: function(response) {
          var comments = response.data;
          var commentCountElement = $('#count-comment-' + forumId);

          if (comments && comments.length > 0) {
            commentCountElement.html(
              '<div class="card-tools">' +
                '<button type="button" class="btn btn-tool" data-card-widget="collapse" aria-expanded="false">' +
                  '<i class="far fa-comments mr-1"></i> Comments (' + comments.length + ')' +
                '</button>' +
              '</div>'
            );
            var commentContainer = $('#forum-comment-' + forumId);
            commentContainer.removeClass().addClass("card-footer card-comments collapse");

            comments.forEach(function(comment) {
              id_comment.push(comment['id']);
              renderComment(comment, commentContainer, forumId);
            });
          } else {
            commentCountElement.html('');
          }
        },
        error: function(error) {
          console.error('Error fetching comments:', error);
        }
      });
    }

    // Function to submit a comment for a forum
    function submitComment(forumId, content) {
      if (progres_comment) {
        return;
      };

      progres_comment = true;
      $.ajax({
        url: "/api/comment",
        method: 'POST',
        data: {
          "id_forum": forumId,
          "user_id": sessionStorage.getItem('login'),
          "content": content
        },
        success: function(response) {
          var comment = response.data;
          var commentContainer = $('#forum-comment-' + forumId);

          $('#send-comment-' + forumId).val("");
          commentContainer.removeClass().addClass("card-footer card-comments");

          // renderComment(comment, commentContainer, forumId);
        },
        error: function(error) {
          console.error('Error submitting comment:', error);
        },
        complete: function(){
          progres_comment = false;
        }
      });
    }

    function submitForum(content) {
      if (progres_forum) {
        return;
      };

      progres_forum = true;
      $.ajax({
        url: "/api/forum",
        method: 'POST',
        data: {
          "user_id": sessionStorage.getItem('login'),
          "content": content
        },
        success: function(response) {
          $('#send-post').val("");
        },
        error: function(error) {
          console.error('Error submitting forum:', error);
        },
        complete: function(){
          progres_forum = false;
        }
      });
    }

    // Function to render a comment in the UI
    function renderComment(comment, container, forumId,prepend=true) {
      var img = comment.avatar ? "{{asset('storage/images/users-images/')}}" + '/' + comment.avatar : "{{asset('storage/images/default/default-user-icon.jpg')}}";

      if (prepend) {
        container.prepend(
          '<div class="card-comment" id="card-comment-' + comment.id + '">' +
            '<div id="delete" class="card-tools float-right">' +
              '<button id="btnDeletecomment-' + comment.id + '" type="button" class="btn btn-tool">' +
                '<i class="fa-solid fa-x">X</i>' +
              '</button>' +
            '</div>' +
            '<img class="img-circle img-sm" src=' + img + ' alt="User Image" style="width: 27px; height: 27px; object-fit: cover; border-radius: 50%;" >' +
            '<div class="comment-text">' +
              '<span class="username">' +
                '<a href="profile/detail?id=' + comment.no_user + '">' + comment.nama + '</a>' +
                '<span class="text-muted float-right">' + comment.formatted_created_at + '</span>' +
              '</span>' +
              comment.content +
            '</div>' +
          '</div>'
        );
      }else{
        container.append(
          '<div class="card-comment" id="card-comment-' + comment.id + '">' +
            '<div id="delete" class="card-tools float-right">' +
              '<button id="btnDeletecomment-' + comment.id + '" type="button" class="btn btn-tool">' +
                '<i class="fa-solid fa-x">X</i>' +
              '</button>' +
            '</div>' +
            '<img class="img-circle img-sm" src=' + img + ' alt="User Image" style="width: 27px; height: 27px; object-fit: cover; border-radius: 50%;" >' +
            '<div class="comment-text">' +
              '<span class="username">' +
                '<a href="profile/detail?id=' + comment.no_user + '">' + comment.nama + '</a>' +
                '<span class="text-muted float-right">' + comment.formatted_created_at + '</span>' +
              '</span>' +
              comment.content +
            '</div>' +
          '</div>'
        );
      }

      //admin only
      if (sessionStorage.getItem('login') == 64) {
          document.getElementById('btnDeletecomment-'+ comment.id).style.display = "block";
      } else {
          document.getElementById('btnDeletecomment-'+ comment.id).style.display = "none";
      }

      // Delete comment
      $('#btnDeletecomment-' + comment.id).on('click', function() {
        deleteComment(comment.id, forumId);
      });
    }

    // Function to delete a forum
    function deleteForum(forumId) {
      $.ajax({
        url: "/api/forum/" + forumId,
        method: "DELETE",
        success: function(response) {
          var data = response.data;
          $('#card-content-' + forumId).remove();
        },
        error: function(error) {
          console.error('Error deleting forum:', error);
        }
      });
    }

    // Function to delete a comment
    function deleteComment(commentId, forumId) {
      $.ajax({
        url: "/api/comment/" + commentId,
        method: "DELETE",
        success: function(response) {
          var data = response.data;
          $('#card-comment-' + commentId).remove();

          // If all comments are deleted, hide the comment section
          if ($('#forum-comment-' + forumId + ' .card-comment').length === 0) {
            $('#forum-comment-' + forumId).removeClass().addClass("card-footer card-comments d-none");
            
            $('#count-comment-' + forumId).html('');
          }
        },
        error: function(error) {
          console.error('Error deleting comment:', error);
        }
      });
    }

    function keyPress(event,id){
      if (event.keyCode === 13) {
        var input = $('#send-comment-'+id).val();
        if (input != null || input != " ") {
          submitComment(id,input)
        }
      }
    }
    function PostkeyPress(event,id){
      if (event.keyCode === 13) {
        var input = $('#send-post').val();
        if (input != null || input != " ") {
          submitForum(input);
        }
      }
    }
  </script>
</body>
</html>
