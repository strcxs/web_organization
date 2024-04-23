<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard • Voting</title>
  @include('include/link')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
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
                {{-- voting-content --}}
            </div>
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
        </div>
    </section>
  </div>
  @include('include/footer')
</div>
@include('include/script')
<script src="{{asset('storage/js/logincheck.js')}}"></script>
<script>
    $(document).ready(function(){
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: [],
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

        function updateDataForLabel(label, newData) {
            var labelIndex = myChart.data.labels.indexOf(label); // find label
            // update data label
            myChart.data.datasets.forEach((dataset) => {
                dataset.data[labelIndex] = newData;
            });
            myChart.update();
        }
        
        if (sessionStorage.getItem('login')==null) {
            return window.location = '../login';
        }
        loginCheck(sessionStorage.getItem('login'));
        $.ajax({
            url: "/api/candidate",
            method: "GET", // First change type to method here
            success: function(response) {
                var data = response.data;
                var nomor = '1';
                data.forEach(element => {
                    var src = "https://democaleg28.nyaleg.id/dirmember/00000001/democaleg28/profile-90.png"; //respon image
                    var card_style = "font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; color: rgb(98, 104, 126)";
                    var number_style = "font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif";
                    var col = 12/response.data.length;
                    
                    $('#voting-content').append(
                        '<div class="col-12 col-md-'+col+'">'+
                            '<div>'+
                                '<div class="text-center">'+
                                    '<img class="img-fluid rounded" src='+src+' alt="" width="70%">'+
                                '</div>'+
                            '</div>'+
                            '<div>'+
                                '<div class="card" style='+card_style+'>'+
                                    '<h1 class="text-center text-lg pt-3">'+element.data_users.data_anggota.nama+'</h1>'+
                                    '<hr class="mx-3" style="border-top:1px solid">'+
                                    '<p class="text-center mx-3">'+element.data_vote.description+'</p>'+
                                    // '<p class="display-3 text-center" style='+number_style+'>'+nomor+'</p>'+
                                    '<button id="view-'+element.id+'" class="btn mx-3 mb-1" style="background-color: rgb(98, 104, 126); color: white">'+
                                        'View Visi & Misi'+
                                    '</button><button name="vote" id="vote-'+element.id+'" class="btn btn-primary mx-3 mb-3">'+
                                        'VOTE'+
                                    '</button>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                    myChart.data.labels.push(element.data_users.data_anggota.nama);
                    $('#view-'+element.id+'').on('click', function() {
                        window.location.href = 'vote/view?v='+element.id+''
                    });
                    $('#vote-'+element.id+'').on('click', function() {
                        $.ajax({
                            url: "/api/ballot",
                            method: "POST",
                            data: {
                                'user_id': sessionStorage.getItem('login'),
                                'id_candidate':element.id,
                            },
                            success: function(response) {
                                $('button[name="vote"]').attr('disabled',true) 
                                $('button[name="vote"]').text("you've already voted") 
                            }
                        });
                    });
                    $.ajax({
                        url: "/api/ballot",
                        success: function(response) {
                            var data = response.data;
                            data.forEach(element => {
                                if (element.user_id == sessionStorage.getItem('login')) {
                                    $('button[name="vote"]').attr('disabled',true) 
                                    $('button[name="vote"]').text("you've already voted") 
                                }
                            });
                        }
                    });
                });
            }
        });

        $.ajax({
            url: "/api/ballot/",
            method: "GET", // First change type to method here
            success: function(response) {
                var data = response.data;
                var countId = {};
                data.forEach(vote => {
                    if (!countId[vote.id_candidate]) {
                        countId[vote.id_candidate] = 1;
                    }else{
                        countId[vote.id_candidate]++;
                    }
                    if (vote.data_candidate.id == vote.id_candidate) {
                        updateDataForLabel(vote.data_candidate.data_users.data_anggota.nama,countId[vote.id_candidate]);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
