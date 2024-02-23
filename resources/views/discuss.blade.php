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
  </style>
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
            <div class="card ">
              <div class="card-header">
                <h3 class="card-title text-primary"><i class="nav-icon fas fa-bolt"></i> Mari Bersuara!</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="post p-3">
                <p>suarakan pikiranmu disini</p>
                <div class="row">
                  <input id="send-post" class="form-control col-10" type="text" placeholder="luapkan semua.." onkeydown=PostkeyPress(event)>
                  <button id="sent-post" class="btn btn-primary form-control col-2">
                    <i class="nav-icon fas fa-paper-plane"></i>
                  </button>
                </div>
              </div>
              <hr class="border-top">
              {{-- <div id="forum-content"> --}}

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
<script>
    $(document).ready(function(){
      if (sessionStorage.getItem('login')==null) {
        return window.location = '../login';
      }
      // Fetch forum data
      $.ajax({
        url: "/api/forum",
        method: "GET",
        success: function(response) {
          var forums = response.data;
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
        },
        error: function(error) {
          console.error('Error fetching forum data:', error);
        }
      });

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
                renderComment(comment, commentContainer, forumId);
              });
            } else {
              commentCountElement.text('');
            }
          },
          error: function(error) {
            console.error('Error fetching comments:', error);
          }
        });
      }

      // Function to submit a comment for a forum
      function submitComment(forumId, content) {
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

            renderComment(comment, commentContainer, forumId);
          },
          error: function(error) {
            console.error('Error submitting comment:', error);
          }
        });
      }

      // Function to render a comment in the UI
      function renderComment(comment, container, forumId) {
        var img = comment.avatar ? "{{asset('storage/images/users-images/')}}" + '/' + comment.avatar : "{{asset('storage/images/default/default-user-icon.jpg')}}";

        container.prepend(
          '<div class="card-comment" id="card-comment-' + comment.id + '">' +
            '<div id="delete" class="card-tools float-right">' +
              '<button id="btnDeletecomment-' + comment.id + '" type="button" class="btn btn-tool">' +
                '<i class="fa-solid fa-x">X</i>' +
              '</button>' +
            '</div>' +
            '<img class="img-circle img-sm" src=' + img + ' alt="User Image">' +
            '<div class="comment-text">' +
              '<span class="username">' +
                '<a href="profile/detail?id=' + comment.no_user + '">' + comment.nama + '</a>' +
                '<span class="text-muted float-right">' + comment.formatted_created_at + '</span>' +
              '</span>' +
              comment.content +
            '</div>' +
          '</div>'
        );

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
            console.log(response);
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
            console.log(response);
            $('#card-comment-' + commentId).remove();

            // If all comments are deleted, hide the comment section
            if ($('#forum-comment-' + forumId + ' .card-comment').length === 0) {
              $('#forum-comment-' + forumId).removeClass().addClass("card-footer card-comments d-none");
            }
          },
          error: function(error) {
            console.error('Error deleting comment:', error);
          }
        });
      }


      
      $('#sent-post').on('click', function() {
        var input = $('#send-post').val();
          $.ajax({
              url: "/api/forum",
              method:'POST', // First change type to method here
              data: {
                  "user_id": sessionStorage.getItem('login'),
                  "content": input
              },
              success: function(response) {
                $('#send-post').val("");
                if (response.data['avatar']!=null) {
                  var img = "{{asset('storage/images/users-images/')}}"+'/'+response.data['avatar']
                }
                else{
                  var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
                }
                $('#forum-content').prepend(
                  '<div class="card p-3" id=card-content-'+response.data['id']+'>'+
                    '<div class="post p-2">'+
                      '<div class="user-block">'+

                        '<div id="delete" class="card-tools float-right">'+
                          '<button id="btnDelete-'+response.data['id']+'" type="button" class="btn btn-tool">'+
                            '<i class="fa-solid fa-x">X</i>'+
                          '</button>'+
                        '</div>'+

                        '<img class="img-circle img-bordered-sm" src='+img+' alt="user image">'+
                        '<span class="username">'+
                          '<a href="profile/detail?id='+response.data['no_user']+'">'+response.data['nama']+'</a>'+
                        '</span>'+
                        '<span class="description">'+response.data['formatted_created_at']+'</span>'+
                      '</div>'+
                      '<p>'+
                        response.data['content']+
                      '</p>'+
                      '<p>'+
                        '<span>'+
                          '<a id="count-comment-'+response.data['id']+'" class="link-black text-sm">'+
                          '</a>'+
                        '</span>'+
                      '</p>'+
                      '<div class="row">'+
                        '<input id="send-comment-'+response.data['id']+'"  class="form-control col-10" type="text" placeholder="Type a comment" onkeydown=keyPress(event,'+response.data['id']+')>'+
                        '<button id="sent-comment-'+response.data['id']+'" class="btn btn-primary form-control col-2">'+
                          '<i class="nav-icon fas fa-paper-plane"></i>'+
                        '</button>'+
                      '</div>'+
                    '</div>'+
                      '<div id="forum-comment-'+response.data['id']+'" class="card-footer card-comments d-none">'+
                    '</div>'+
                  '</div>'

                );
                if (sessionStorage.getItem('login') == 64) {
                          document.getElementById('btnDelete-'+ response.data['id']).style.display = "block";
                } else {
                    document.getElementById('btnDelete-'+ response.data['id']).style.display = "none";
                }
                $('#btnDelete-' + response.data['id']).on('click', function() {
                  // alert('Tombol delete di-klik untuk elemen dengan ID ' + response.data['id']);
                  var forr = response.data['id'];
                  $.ajax({
                    url: "/api/forum/"+forr,
                    method: "delete", // First change type to method here    
                    
                    success: function(response) {
                      var data = response.data;
                      console.log(response);
                      $('#card-content-'+forr+'').remove();
                    },
                    error: function(error){

                    }
                  });
                });

                $('#sent-comment-'+response.data['id']+'').on('click', function() {
                  var input = $('#send-comment-'+response.data['id']+'').val();
                  if (input != null || input != " ") {
                    $.ajax({
                      url: "/api/comment",
                      method: "POST", // First change type to method here
                      data: {
                          "id_forum": response.data['id'],
                          "user_id": sessionStorage.getItem('login'),
                          "content": input
                      },
                      success: function(response) {
                        // console.log(response);
                        if (response.data['avatar']!=null) {
                          var img = "{{asset('storage/images/users-images/')}}"+'/'+response.data['avatar']
                        }
                        else{
                          var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
                        }
                        $('#send-comment-'+response.data['forum_id']).val("");
                        $('#forum-comment-'+response.data['forum_id']).removeClass().addClass("card-footer card-comments ");
                        $('#forum-comment-'+response.data['forum_id']).prepend(
                          '<div class="card-comment" id="card-comment-'+response.data['forum_id']+'">'+  
                            '<div id="delete" class="card-tools float-right">'+
                              '<button id="btnDeletecomment-'+response.data['forum_id']+'" type="button" class="btn btn-tool">'+
                                '<i class="fa-solid fa-x">X</i>'+
                              '</button>'+
                            '</div>'+
                            '<img class="img-circle img-sm" src='+img+' alt="User Image">'+
                            '<div class="comment-text">'+
                              '<span class="username">'+
                                '<a href="profile/detail?id='+response.data['no_user']+'">'+response.data['nama']+'</a>'+
                                '<span class="text-muted float-right">'+response.data['formatted_created_at']+'</span>'+
                              '</span>'+
                              response.data['content']+
                            '</div>'+
                          '</div>'
                        );
                        if (sessionStorage.getItem('login') == 64) {
                            document.getElementById('btnDeletecomment-'+ response.data['forum_id']).style.display = "block";
                        } else {
                            document.getElementById('btnDeletecomment-'+ response.data['forum_id']).style.display = "none";
                        }
                        $('#btnDeletecomment-' + response.data['id']).on('click', function() {
                          // alert('Tombol delete di-klik untuk elemen dengan ID ' + response.data['id']);
                          var forr = response.data['id'];
                          $.ajax({
                            url: "/api/comment/"+forr,
                            method: "delete", // First change type to method here    
                            
                            success: function(response) {
                              var data = response.data;
                              console.log(response);
                              $('#card-content-'+forr+'').remove();
                            },
                            error: function(error){

                            }
                          });
                        });
                      },
                      error:function(error) {
            
                      }
                    });
                  }
                });
              },
              error:function(error) {

              }
          })
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
    function keyPress(event,id){
      if (event.keyCode === 13) {
        var input = $('#send-comment-'+id).val();
        if (input != null || input != " ") {
          $.ajax({
            url: "/api/comment",
            method: "POST", // First change type to method here
            data: {
                "id_forum": id,
                "user_id": sessionStorage.getItem('login'),
                "content": input
            },
            success: function(response) {
              if (response.data['avatar']!=null) {
                var img = "{{asset('storage/images/users-images/')}}"+'/'+response.data['avatar']
              }
              else{
                var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
              }
              $('#send-comment-'+id).val("");
              $('#forum-comment-'+id).removeClass().addClass("card-footer card-comments ");
              $('#forum-comment-'+id).prepend(
                '<div class="card-comment" id="card-comment-'+id+'">'+  
                  '<div id="delete" class="card-tools float-right">'+
                    '<button id="btnDeletecomment-'+id+'" type="button" class="btn btn-tool">'+
                      '<i class="fa-solid fa-x">X</i>'+
                    '</button>'+
                  '</div>'+
                  '<img class="img-circle img-sm" src='+img+' alt="User Image">'+
                  '<div class="comment-text">'+
                    '<span class="username">'+
                      '<a href="profile/detail?id='+response.data['no_user']+'">'+response.data['nama']+'</a>'+
                      '<span class="text-muted float-right">'+response.data['formatted_created_at']+'</span>'+
                    '</span>'+
                    response.data['content']+
                  '</div>'+
                '</div>'
              );
              if (sessionStorage.getItem('login') == 64) {
                  document.getElementById('btnDeletecomment-'+ id).style.display = "block";
              } else {
                  document.getElementById('btnDeletecomment-'+ id).style.display = "none";
              }
              $('#btnDeletecomment-' + id).on('click', function() {
                // alert('Tombol delete di-klik untuk elemen dengan ID ' + id);
                var forr = id;
                $.ajax({
                  url: "/api/comment/"+forr,
                  method: "delete", // First change type to method here    
                  
                  success: function(response) {
                    var data = response.data;
                    console.log(response);
                    $('#card-content-'+forr+'').remove();
                  },
                  error: function(error){

                  }
                });
              });
            },
            error:function(error) {
  
            }
          });
        }
      }
    }
    function PostkeyPress(event,id){
      if (event.keyCode === 13) {
        var input = $('#send-post').val();
        $.ajax({
            url: "/api/forum",
            method: "POST", // First change type to method here
            data: {
                "user_id": sessionStorage.getItem('login'),
                "content": input
            },
            success: function(response) {
              $('#send-post').val("");
              if (response.data['avatar']!=null) {
                var img = "{{asset('storage/images/users-images/')}}"+'/'+response.data['avatar']
              }
              else{
                var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
              }
              $('#forum-content').prepend(
                '<div class="card p-3" id=card-content-'+response.data['id']+'>'+
                  '<div class="post p-2">'+
                    '<div class="user-block">'+

                      '<div id="delete" class="card-tools float-right">'+
                        '<button id="btnDelete-'+response.data['id']+'" type="button" class="btn btn-tool">'+
                          '<i class="fa-solid fa-x">X</i>'+
                        '</button>'+
                      '</div>'+

                      '<img class="img-circle img-bordered-sm" src='+img+' alt="user image">'+
                      '<span class="username">'+
                        '<a href="profile/detail?id='+response.data['no_user']+'">'+response.data['nama']+'</a>'+
                      '</span>'+
                      '<span class="description">'+response.data['formatted_created_at']+'</span>'+
                    '</div>'+
                    '<p>'+
                      response.data['content']+
                    '</p>'+
                    '<p>'+
                      '<span>'+
                        '<a id="count-comment-'+response.data['id']+'" class="link-black text-sm">'+
                        '</a>'+
                      '</span>'+
                    '</p>'+
                    '<div class="row">'+
                      '<input id="send-comment-'+response.data['id']+'"  class="form-control col-10" type="text" placeholder="Type a comment" onkeydown=keyPress(event,'+response.data['id']+')>'+
                      '<button id="sent-comment-'+response.data['id']+'" class="btn btn-primary form-control col-2">'+
                        '<i class="nav-icon fas fa-paper-plane"></i>'+
                      '</button>'+
                    '</div>'+
                  '</div>'+
                    '<div id="forum-comment-'+response.data['id']+'" class="card-footer card-comments d-none">'+
                  '</div>'+
                '</div>'
              );
              if (sessionStorage.getItem('login') == 64) {
                  document.getElementById('btnDelete-'+ response.data['id']).style.display = "block";
              } else {
                  document.getElementById('btnDelete-'+ response.data['id']).style.display = "none";
              }
              $('#btnDelete-' + response.data['id']).on('click', function() {
                // alert('Tombol delete di-klik untuk elemen dengan ID ' + response.data['id']);
                var forr = response.data['id'];
                $.ajax({
                  url: "/api/forum/"+forr,
                  method: "delete", // First change type to method here    
                  
                  success: function(response) {
                    var data = response.data;
                    console.log(response);
                    $('#card-content-'+forr+'').remove();
                  },
                  error: function(error){

                  }
                });
              });
            },
            error:function(error) {

            }
        })
      }
    }
  </script>
</body>
</html>
