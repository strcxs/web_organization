<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | User Profile</title>
  @include('include/link')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  @include('include/navbar')
  @include('include/sidebar')
  <div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="text-right container pt-2 pb-3">
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#resultVoting">
                    Detail Hasil E-voting
                </button>
            </div>
            <div class="row" id="voting-content">
                {{-- content --}}

                {{-- <div class="col-12 col-md-3">
                    <div>
                        <div class="text-center">
                            <img class="img-fluid rounded" src="{{asset('storage/images/hai.png')}}" alt="">
                        </div>
                    </div>
                    <div>
                        <div class="card" style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; color: rgb(98, 104, 126)">
                            <h1 class="text-center text-lg pt-3">Zahrani</h1>
                            <hr class="mx-3" style="border-top:1px solid">
                            <p class="text-center mx-3">Calon Ketua Himpunan Mahasiswa Teknik Informatika 2023/2024</p>
                            <p style="font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif" class="display-3 text-center">01</p>
                            <button class="btn mx-3 mb-1" style="background-color: rgb(98, 104, 126); color: white">
                                View Visi & Misi
                            </button><button class="btn btn-primary mx-3 mb-3">
                                VOTE
                            </button>
                        </div>
                    </div>
                </div> --}}
            </div>
            <!-- Modal Kandidat 1 -->
            <div class="modal fade" id="resultVoting" tabindex="-1" role="dialog" aria-labelledby="modalCandidate1Label" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 id="grafik-title" class="modal-title" id="modalCandidate1Label">Hasil E-Voting (RealTime)</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart" width="400" height="200"></canvas>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="container mt-5">
                <div class="card">
                    <div class="card-header">
                        <h5 id="grafik-title" class="card-title">Grafik Hasil E-Voting</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>
  </div>
  @include('include/footer')
</div>
<!-- ./wrapper -->
@include('include/script')
<script>
    $(document).ready(function(){
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Jumlah Suara',
                    data: [],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        function realtime() {
            $.ajax({
                url: "/api/result/1",
                method: "GET", // First change type to method here
                success: function(response) {
                    // console.log(response);
                    const numbers = response.data.map(item => item.number);
                    const results = response.data.map(item => {
                        return{
                            label: item.number,
                            value: item.result
                        }
                    });
                    // console.log(value.length);
                    if (response.data.length!=0) {
                        $.ajax({
                            url: "/api/vote/"+response.data[0]['id_vote_topic'],
                            method: "GET", // First change type to method here
                            success: function(response) {
                                // console.log(response);
                                const number = response.data.map(item => item.number);
                                if (numbers.length!=response.data.length) {
                                    const filter = number.filter(value => !numbers.includes(value));
                                    const number_combine = numbers.concat(filter).sort();
                                    for (let index = 0; index < number_combine.length; index++) {
                                        if (number_combine[index]==filter) {
                                            // console.log(index);
                                            myChart.data.labels = number_combine;
                                            myChart.data.datasets[0].data[index] = 0;
                                            myChart.update();
                                        }
                                        for (let indexx = 0; indexx < results.length; indexx++) {
                                            if(number_combine[index]==results[indexx]['label']){
                                                // console.log(index);
                                                myChart.data.labels = number_combine;
                                                myChart.data.datasets[0].data[index] = results[indexx]['value'];
                                                myChart.update();
                                            }
                                        }
                                    }
                                }else{
                                    for (let index = 0; index < numbers.length; index++) {
                                        myChart.data.labels = numbers;
                                        myChart.data.datasets[0].data[index] = results[index]['value'];
                                        myChart.update();
                                    }
                                }
                            }
                        });
                    }
                }
            });
        }
        realtime();
        // setInterval(realtime, 10000)
        
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
        $.ajax({
            url: "/api/voting",
            method: "GET", // First change type to method here
            success: function(response) {
                // console.log(response);
                for (let index = 0; index < response.data.length; index++) {
                    // var src = "{{asset('storage/images/hai.png')}}"; //respon image
                    var src = "https://democaleg28.nyaleg.id/dirmember/00000001/democaleg28/profile-90.png"; //respon image
                    var card_style = "font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; color: rgb(98, 104, 126)";
                    var number_style = "font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif";
                    var col = 12/response.data.length
                    // $('#grafik-title').text(response.data[index]['topic_information'])
                    $('#voting-content').append(
                        '<div class="col-12 col-md-'+col+'">'+
                            '<div>'+
                                '<div class="text-center">'+
                                    '<img class="img-fluid rounded" src='+src+' alt="" width="70%">'+
                                '</div>'+
                            '</div>'+
                            '<div>'+
                                '<div class="card" style='+card_style+'>'+
                                    '<h1 class="text-center text-lg pt-3">'+response.data[index]['value']+'</h1>'+
                                    '<hr class="mx-3" style="border-top:1px solid">'+
                                    '<p class="text-center mx-3">'+response.data[index]['topic_information']+'</p>'+
                                    '<p class="display-3 text-center" style='+number_style+'>'+response.data[index]['number']+'</p>'+
                                    '<button id="view-'+response.data[index]['id']+'" class="btn mx-3 mb-1" style="background-color: rgb(98, 104, 126); color: white">'+
                                        'View Visi & Misi'+
                                    '</button><button name="vote" id="vote-'+response.data[index]['id']+'" class="btn btn-primary mx-3 mb-3">'+
                                        'VOTE'+
                                    '</button>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                    $('#view-'+response.data[index]['id']+'').on('click', function() {
                        window.location.href = 'vote/view?v='+response.data[index]['id']+''
                    });
                    $('#vote-'+response.data[index]['id']+'').on('click', function() {
                        $.ajax({
                            url: "/api/check",
                            method: "POST",
                            data: {
                                'id_user': sessionStorage.getItem('login'),
                                'id_vote_topic':response.data[index]['id_vote_topic'],
                                'id_voting':response.data[index]['id']
                            },
                            success: function(response) {
                                console.log(response);
                                $('button[name="vote"]').attr('disabled',true) 
                                $('button[name="vote"]').text("you've already voted") 
                            }
                        });
                    });
                    $.ajax({
                        url: "/api/check/"+response.data[index]['id_vote_topic'],
                        success: function(response) {
                            // console.log(sessionStorage.getItem('login'));
                            for (let index = 0; index < response.data.length; index++) {
                                if (response.data[index]['id_user']==sessionStorage.getItem('login')) {
                                    $('button[name="vote"]').attr('disabled',true) 
                                    $('button[name="vote"]').text("you've already voted") 
                                    
                                }
                            }
                        },
                        error: function(error){

                        }
                    });
                }
            }
        });
    });
</script>
</body>
</html>
