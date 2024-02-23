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
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="view-content">
                {{-- <div class="col-12 d-none d-sm-block col-md-4">
                    <div class="text-center">
                        <img class="img-fluid rounded" src="{{asset('storage/images/hai.png')}}" alt="" width=40%>
                    </div>
                </div>
                <div  class="col-12 col-md-4 d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <p class="display-4">Kang Rahul</p>
                        <p class="text-center">Calon Ketua Himpunan Mahasiswa Teknik Informatika 2023/2024</p>
                    </div>
                </div>
                <div  class="col-12 col-md-4 d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <p class="display-1 d-none d-sm-block">01</p>
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <div class="card">
                        <p class="h3 px-2">visi :</p>
                        <p class="px-2">
                            Terwujudnya mahasiswa yang beriman, cerdas, kreatif, terampil, mandiri, dan berwawasan global dan menciptakan berbagai inovasi
                            baru sehingga mahasiswa lebih maju.
                        </p>
                        <p class="h3 px-2">misi :</p>
                        <p class="px-2">
                            1. Menanamkan keimanan dan ketakwaan melalui pengalaman ajaran agama <br>
                            2. Aktif dalam bidang akademik maupun non akademik <br>
                            3. aktif dalam berorganisasi agar dapat mengembangkan dan minat dalam potensi diri <br>
                            4. Ikut berpastisipasi aktif dalam setiap event dan juga seminar yang diadakan oleh kampus maupun di luar kampus <br>
                            5. Ikut Berpastisipasi ataupun ikut serta dalam setiap kompetisi yang dapat membantu mengasah kreativitas semua mahasiswa <br>
                            6. Menanamkan keimanan dan ketakwaan melalui pengalaman ajaran agama <br>
                            7. Aktif dalam bidang akademik maupun non akademik <br>
                            8. aktif dalam berorganisasi agar dapat mengembangkan dan minat dalam potensi diri <br>
                            9 Ikut berpastisipasi aktif dalam setiap event dan juga seminar yang diadakan oleh kampus maupun di luar kampus <br>
                        </p>
                    </div>
                </div> --}}
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
        var urlParams = new URLSearchParams(window.location.search);
        var view = urlParams.get('v');
        $.ajax({
            url: "/api/voting/"+view+"",
            method: "GET", // First change type to method here
            success: function(response) {
                // console.log(response);
                var src = "{{asset('storage/images/hai.png')}}"
                var misi = response.data[0]['misi']
                var misii = misi.replace(/\n/g, '<br>');

                $('#view-content').append(
                    '<div class="col-12 d-none d-sm-block col-md-4">'+
                        '<div class="text-center">'+
                            '<img class="img-fluid rounded" src='+src+' alt="" width=40%>'+
                        '</div>'+
                    '</div>'+
                    '<div  class="col-12 col-md-4 d-flex align-items-center justify-content-center">'+
                        '<div class="text-center">'+
                            '<p class="h1">'+response.data[0]['value']+'</p>'+
                            '<p class="text-center">'+response.data[0]['topic_information']+'</p>'+
                        '</div>'+
                    '</div>'+
                    '<div  class="col-12 col-md-4 d-flex align-items-center justify-content-center">'+
                        '<div class="text-center">'+
                            '<p class="display-1 d-none d-sm-block">'+response.data[0]['number']+'</p>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-12 col-md-12">'+
                        '<div class="card">'+
                            '<p class="h3 px-2">visi :</p>'+
                            '<p class="px-2">'+
                                response.data[0]['visi']+
                            '</p>'+
                            '<p class="h3 px-2">misi :</p>'+
                            '<p class="px-2">'+
                                misii+
                            '</p>'+
                        '</div>'+
                    '</div>'
                );
            },
            error: function(error){

            }
        });
    });
</script>
</body>
</html>
