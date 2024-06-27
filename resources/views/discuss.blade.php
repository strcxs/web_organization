<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard • Discuss</title>
  @include('include/link')
  <style>
    .card {
        box-shadow: none !important;
        margin-bottom: 0px;
    }
  </style>
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
            <div class="card mb-3">
              <div class="card-header bg-primary">
                <h3 class="card-title"><i class="nav-icon fas fa-bolt"></i> Speaker!</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="post p-3">
                <div class="row">
                  <input id="send-post" class="form-control col-10" type="text" onkeydown=PostkeyPress(event) placeholder="Text your ideas !">
                  <button id="sent-post" class="btn btn-primary form-control col-2">
                    <i class="nav-icon fas fa-paper-plane"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="card"> 
              <div id="forum-content">
            </div>
          </div>
        </div>
      </div>
      <div id="confirmationDeleteForum" class="modal" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header bg-danger">
                      <h5 class="modal-title">DELETE ALERT !</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true" class="text-light">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">

                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                      <button type="button" class="btn btn-danger" onclick="deleteForum(id)" data-dismiss="modal">Hapus</button>
                  </div>
              </div>
          </div>
      </div>
      <div id="confirmationDeleteComment" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">DELETE ALERT !</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button id="id" forum_id='forum' type="button" class="btn btn-danger" onclick="deleteComment(id)" data-dismiss="modal">Hapus</button>
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
    var datasave = [];
    var id_comment = [];
    $(document).ready(function(){
      if (sessionStorage.getItem('session')==null) {
        return window.location = window.location.origin+'/login';
      }
      sessionCheck(sessionStorage.getItem('id'));
        
      var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
      });

      var channel = pusher.subscribe('discuss');
      channel.bind('sent-discuss', function(data) {
        if (data.data.data_users['avatar']!=null) {
          var img = "{{asset('storage/images/users-images/')}}"+'/'+data.data.data_users['avatar']
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
                  '<a href="profile/detail?id='+data.data.data_users['id']+'">'+data.data.data_users.data_anggota['nama']+'</a>'+
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
              '<div id="forum-comment-'+data.data['id']+'" class="card-footer card-comments collapse">'+
            '</div>'+
          '</div>'
        );
        if (sessionStorage.getItem('session') == 1) {
                  document.getElementById('btnDelete-'+ data.data['id']).style.display = "block";
        } else {
            document.getElementById('btnDelete-'+ data.data['id']).style.display = "none";
        }
        $('#btnDelete-' + data.data['id']).on('click', function() {
          deleteForumConfirm(data.data['id']);
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
          url: "/api/comment/" + data.data['id_forum'],
          method: "GET",
          success: function(response) {
            var comments = response.data;
            var commentCountElement = $('#count-comment-' + data.data['id_forum']);

            if (comments && comments.length > 0) {
              commentCountElement.html(
                '<div class="card-tools">' +
                  '<button type="button" class="btn btn-tool" data-card-widget="collapse" aria-expanded="false">' +
                    '<i class="far fa-comments mr-1"></i> Comments (' + comments.length + ')' +
                  '</button>' +
                '</div>'
              );
              var commentContainer = $('#forum-comment-' + data.data['id_forum']);
              if (sessionStorage.getItem('id') == data.data['user_id']) {
                $('#forum-comment-'+data.data['id_forum']).removeClass().addClass("card-footer card-comments ");
              }
              comments.forEach(function(comment) {
                if (!id_comment.includes(comment.id)){
                  renderComment(comment, commentContainer, data.data['id_forum'], prepend=false);
                  id_comment.push(comment.id)
                }
              });
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

      loginCheck(sessionStorage.getItem('id'));
    });
    function deleteForumConfirm(forum_id,content='Are you sure you want to delete this post?') {
      $('#confirmationDeleteForum').modal('show');
      $('#confirmationDeleteForum').find('.modal-body').html(content);
      $('#confirmationDeleteForum').find('.btn-danger').attr('id', forum_id);
    }
    function deleteCommentConfirm(comment_id,forum_id,content='Are you sure you want to delete this comment?') {
      $('#confirmationDeleteComment').modal('show');
      $('#confirmationDeleteComment').find('.modal-body').html(content);
      $('#confirmationDeleteComment').find('.btn-danger').attr('id', comment_id+','+forum_id);
    }

    //fetch data forum
    let page = 1;
    const itemsPerPage = 5; 
    let isLoading = false;
    let maxpage = false;

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
            if (forums.length == 0) {
              maxpage = true;
            }

            forums.forEach(function(forum) {
              var img = forum.data_users.avatar ? "{{asset('storage/images/users-images/')}}" + '/' + forum.data_users.avatar : "{{asset('storage/images/default/default-user-icon.jpg')}}";

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
                        '<a href="profile/detail?id=' + forum.data_users.member_id + '">' + forum.data_users.data_anggota.nama + '</a>' +
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
                  '<div id="forum-comment-' + forum.id + '" class="card-footer card-comments collapse">' +
                  '</div>' +
                '</div>'
              );
              
              //admin only
              if (sessionStorage.getItem('session') == 1) {
                  document.getElementById('btnDelete-'+ forum.id).style.display = "block";
              } else {
                  document.getElementById('btnDelete-'+ forum.id).style.display = "none";
              }

              // Delete forum
              $('#btnDelete-' + forum.id).on('click', function() {
                deleteForumConfirm(forum.id);
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
      if (scrollPosition >= totalHeight-1 && !maxpage) {
        loadData();
      }
    }

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
          "user_id": sessionStorage.getItem('id'),
          "content": content
        },
        success: function(response) {
          var comment = response.data;
          var commentContainer = $('#forum-comment-' + forumId);

          $('#send-comment-' + forumId).val("");
          commentContainer.removeClass().addClass("card-footer card-comments");
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
          "user_id": sessionStorage.getItem('id'),
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
      var img = comment.data_users.avatar ? "{{asset('storage/images/users-images/')}}" + '/' + comment.data_users.avatar : "{{asset('storage/images/default/default-user-icon.jpg')}}";

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
                '<a href="profile/detail?id=' + comment.data_users.id + '">' + comment.data_users.data_anggota.nama + '</a>' +
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
                '<a href="profile/detail?id=' + comment.data_users.id + '">' + comment.data_users.data_anggota.nama + '</a>' +
                '<span class="text-muted float-right">' + comment.formatted_created_at + '</span>' +
              '</span>' +
              comment.content +
            '</div>' +
          '</div>'
        );
      }

      //admin only
      if (sessionStorage.getItem('session') == 1) {
          document.getElementById('btnDeletecomment-'+ comment.id).style.display = "block";
      } else {
          document.getElementById('btnDeletecomment-'+ comment.id).style.display = "none";
      }

      // Delete comment
      $('#btnDeletecomment-' + comment.id).on('click', function() {
        deleteCommentConfirm(comment.id, forumId);
      });
    }

    // Function to delete a forum
    function deleteForum(forumId) {
      $.ajax({
        url: "/api/forum/" + forumId,
        method: "DELETE",
        data:{
          "user_id":sessionStorage.getItem('id')
        },
        success: function(response) {
          var data = response.data;
          $('#card-content-' + forumId).remove();
          $(window).scroll(checkEndOfPage);
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
          $(window).scroll(checkEndOfPage);

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
