<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title> Login and Registration</title>
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('/css/login.css')}}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
   <body >
      <img class="img-blur" src="{{asset('storage/images/adhisti.jpg')}}" style="position: absolute;" alt="">
      <div class="container">
        <input type="checkbox" id="flip">
        <div class="cover">
          <div class="front">
            <img class="front_img" src="{{asset('https://thumbs.dreamstime.com/b/blue-bokeh-computer-code-background-job-offer-blue-bokeh-computer-code-background-job-offer-coding-code-learning-180554511.jpg')}}" alt="">
            <div class="text">
              <span class="text-1">Himpunan Mahasiswa</span>
              <span class="text-2">Teknik Informatika</span>
              <span class="text-3">Universitas Nurtanio Bandung</span>
            </div>
          </div>
          <div class="back">
            <img class="back_img" src="{{asset('storage/images/icon_himaif.png')}}" alt="">
            <div class="text">
            </div>
          </div>
        </div>
        <div>
          <button type="button" class="btn btn-tool" data-card-widget="remove"style="font-size: 20px ;background-color: transparent; border: none; padding: 0; margin: 0;" onclick="window.location.href='/'" >
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="forms">
            <div class="form-content">
              <div class="login-form">
                <div class="front-alert" id="front-alert">
                  <p id="front_alertt"></p>
                </div>
                <div class="title">Login</div>
              <form action="" method="POST">
                <div class="input-boxes">
                  <div class="input-box">
                    <i class="fas fa-user"></i>
                    <input name="username" id="username" type="text" placeholder="Enter your username" required>
                  </div>
                  <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input name="password" id="password" type="password" placeholder="Enter your password" required>
                  </div>
                  <div class="button input-box">
                    <input type="button" id="btnSubmit" value="Submit" name="login">
                  </div>
                  <div class="text sign-up-text">Don't have an account? <label for="flip">SignUp now</label></div>
                </div>
            </form>
          </div>
            <div class="signup-form">
              <div class="back-alert" id="back-alert">
                <p id="back_alertt"></p>
              </div>
              <div class="sign-alert" id="sign-alert">
                <p id="sign_alertt"></p>
              </div>
              <div class="title">Signup</div>
            <form action="" method="POST">
                <div class="input-boxes">
                  <div class="input-box">
                    <i class="fas fa-user"></i>
                    <input name="nim" id="nim" type="text" placeholder="Enter your NIM" required>
                  </div>
                  <div class="input-box">
                    <i class="fas fa-rocket"></i>
                    <input name="username" id="signUp-username" type="text" placeholder="Enter your Username" required>
                  </div>
                  <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input name="password" id="signUp-password" type="password" placeholder="Enter your password" required>
                  </div>
                  <div class="input-box">
                    <i class="fas fa-check"></i>
                    <input name="re_password" id="re_password" type="password" placeholder="Enter your password again" required>
                  </div>
                  <div class="button input-box">
                    <input type="button" id="btnSubmitt" value="Submit" name="signUp">
                  </div>
                  <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
                </div>
          </form>
        </div>
        </div>
        </div>
      </div>
  </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $('#front-alert').hide();
    $('#back-alert').hide();
    $('#sign-alert').hide();
    
    $(document).ready(function () { 
      if (sessionStorage.getItem('login')!=null) {
        return window.location = 'dashboard';
      }
      $("#btnSubmit").click(function(){
        var user = $("#username").val();
        var pass = $("#password").val();
        $.ajax({
          url: "/api/login",
          method: "POST", // First change type to method here    
          data: {
              "username": user,
              "password": pass,
          },
          success: function(response) {
          if (response.success === true){
            sessionStorage.setItem('login',response.session);
            window.location.href = 'dashboard';
          }
          else if(response.message === 'password anda salah'){
            $("#front_alertt").text(response.message);
            $('#front-alert').slideDown().delay(5000);
            $('#front-alert').slideUp();
            $('#password').val("");
          }
          else {
            $("#front_alertt").text(response.message);
            $('#front-alert').slideDown().delay(5000);
            $('#front-alert').slideUp();
            $('#username').val("");
            $('#password').val("");
          }
          }
        });    
      });
      $("#btnSubmitt").click(function(){
        var nim = $("#nim").val();
        var user = $("#signUp-username").val();
        var pass = $("#signUp-password").val();
        var re_pass = $("#re_password").val();
        $.ajax({
          url: "/api/regis",
          method: "POST", // First change type to method here    
          data: {
              "nim": nim,
              "username": user,
              "password": pass,
              "re_password": re_pass
          },
          success: function(response) {
            $("#back_alertt").text(response.password);
            if (response.success === true){
              $("#sign_alertt").text('success, silahkan login ulang');
              $('#sign-alert').slideDown().delay(3000);
              setTimeout(() => {
                window.location = 'login';
              }, 2000);
            }
            else{
              $("#back_alertt").text(response.message);
              $('#back-alert').slideDown().delay(4000);
              $('#back-alert').slideUp();
            }
          }
        });    
      });
    });
  </script>
</html>