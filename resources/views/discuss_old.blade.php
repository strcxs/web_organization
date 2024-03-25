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
      $.ajax({
        url: "/api/forum",
        method: "GET", // First change type to method here
        success: function(response) {
          var data = response.data
          $('#post-count').text(response.data.length)

          for (let index = 0; index < response.data.length; index++) {
            if (data[index]['avatar']!=null) {
              var img = "{{asset('storage/images/users-images/')}}"+'/'+data[index]['avatar']
            }
            else{
              var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
            }
            // console.log(data[index]['id']);
            $('#forum-content').append(
                '<div class="card p-3" id=card-content-'+data[index]['id']+'>'+
                  '<div class="post p-2">'+
                    '<div class="user-block">'+

                      '<div id="delete" class="card-tools float-right">'+
                        '<button id="btnDelete-'+data[index]['id']+'" type="button" class="btn btn-tool">'+
                          '<i class="fa-solid fa-x">X</i>'+
                        '</button>'+
                      '</div>'+

                      '<img class="img-circle img-bordered-sm" src='+img+' alt="user image" style="width: 43px; height: 43px; object-fit: cover; border-radius: 50%;" >'+
                      '<span class="username">'+
                        '<a href="profile/detail?id='+data[index]['no_user']+'">'+data[index]['nama']+'</a>'+
                      '</span>'+
                      '<span class="description">'+data[index]['formatted_created_at']+'</span>'+
                    '</div>'+
                    '<p>'+
                      data[index]['content']+
                    '</p>'+
                    '<p>'+
                      '<span>'+
                        '<a id="count-comment-'+data[index]['id']+'" class="link-black text-sm">'+
                        '</a>'+
                      '</span>'+
                    '</p>'+
                    '<div class="row">'+
                      '<input id="send-comment-'+data[index]['id']+'"  class="form-control col-10" type="text" placeholder="Type a comment" onkeydown=keyPress(event,'+data[index]['id']+')>'+
                      '<button id="sent-comment-'+data[index]['id']+'" class="btn btn-primary form-control col-2">'+
                        '<i class="nav-icon fas fa-paper-plane"></i>'+
                      '</button>'+
                    '</div>'+
                  '</div>'+
                  '<div id="forum-comment-'+data[index]['id']+'" class="card-footer card-comments d-none">'+
                  '</div>'+
                '</div>'
              );
              $('#sent-comment-'+data[index]['id']+'').on('click', function() {
                var input = $('#send-comment-'+data[index]['id']+'').val();
                if (input != null || input != " ") {
                  $.ajax({
                    url: "/api/comment",
                    method:'POST', // First change type to method here
                    data: {
                        "id_forum": data[index]['id'],
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
                      $('#btnDeletecomment-' + response.data['forum_id']).on('click', function() {
                        // alert('Tombol delete di-klik untuk elemen dengan ID ' + response.data['forum_id']);
                        var forr = response.data['forum_id'];
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
              
              if (sessionStorage.getItem('login') == 64) {
                  document.getElementById('btnDelete-'+ data[index]['id']).style.display = "block";
              } else {
                  document.getElementById('btnDelete-'+ data[index]['id']).style.display = "none";
              }
              $('#btnDelete-' + data[index]['id']).on('click', function() {
                // alert('Tombol delete di-klik untuk elemen dengan ID ' + data[index]['id']);
                var forr = data[index]['id'];
                $.ajax({
                  url: "/api/forum/"+forr,
                  method: "delete", // First change type to method here    
                  
                  success: function(response) {
                    var data = response.data;
                    // console.log(response);
                    $('#card-content-'+forr+'').remove();
                  },
                  error: function(error){

                  }
                });
              });

              $.ajax({
                url: "/api/comment/"+data[index]['id'],
                method: "GET", // First change type to method here
                success: function(response) {
                  data_comment = response.data
                  // console.log(data_comment);
                  if (data_comment != null) {
                    $('#count-comment-'+data[index]['id']).html(
                      '<div class="card-tools">'+
                        '<button type="button" class="btn btn-tool" data-card-widget="collapse" aria-expanded="false">'+
                          '<i class="far fa-comments mr-1"></i> Comments ('+data_comment.length+')'+
                        '</button>'+
                      '</div>'
                      )
                    for (let com = 0; com < data_comment.length; com++) {
                      if (data_comment[com]['avatar']!=null) {
                        var img = "{{asset('storage/images/users-images/')}}"+'/'+data_comment[com]['avatar']
                      }
                      else{
                        var img = "{{asset('storage/images/default/default-user-icon.jpg')}}"
                      }
                      // console.log(data_comment);
                      $('#forum-comment-'+data[index]['id']).removeClass().addClass("card-footer card-comments collapse");
                      $('#forum-comment-'+data[index]['id']).append(
                        '<div class="card-comment" id="card-comment-'+data_comment[com]['id']+'">'+
                          
                          '<div id="delete" class="card-tools float-right">'+
                            '<button id="btnDeletecomment-'+data_comment[com]['id']+'" type="button" class="btn btn-tool">'+
                              '<i class="fa-solid fa-x">X</i>'+
                            '</button>'+
                            // '<p id="vc-'+data_comment[com]['id']+'">xx</p>'+
                          '</div>'+

                          '<img class="img-circle img-sm" src='+img+' alt="User Image">'+
                          '<div class="comment-text">'+
                            '<span class="username">'+
                              '<a href="profile/detail?id='+data_comment[com]['no_user']+'">'+data_comment[com]['nama']+'</a>'+
                              '<span class="text-muted float-right">'+data_comment[com]['formatted_created_at']+'</span>'+
                            '</span>'+
                            data_comment[com]['content']+
                          '</div>'+
                        '</div>'
                      );
                      // $('#vc-'+data_comment[com]['id']).text(data_comment[com]['id']);
                      if (sessionStorage.getItem('login') == 64) {
                          document.getElementById('btnDeletecomment-' + data_comment[com]['id']).style.display = "block";
                      } else {
                          document.getElementById('btnDeletecomment-' + data_comment[com]['id']).style.display = "none";
                      }
                      $('#btnDeletecomment-' + data_comment[com]['id']).on('click', function() {
                        var comm = response.data[com]['id']
                        $.ajax({
                          url: "/api/comment/"+comm,
                          method: "delete", // First change type to method here    
                          
                          success: function(response) {
                            var data = response.data;
                            console.log(response);
                            $('#card-comment-'+comm+'').remove();
                          },
                          error: function(error){

                          }
                        });
                      });
                    }
                  }
                },
                error: function(error) {

                }
              });
          }
        },
        error: function(error) {

        }
      });

      
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
