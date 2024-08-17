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
            <div class="row justify-content-end text-right container pt-2 pb-3">
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#resultVoting">
                    Voting Result
                </button>
                <div class="dropdown pl-1">
                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Choose Voting
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <!--dropdown item-->
                    </div>
                </div>
            </div>
            <div class="row justify-content-center text-center container">
                <h4 id="time-label" class="mr-1">
                    Voting Ends In
                </h4>
                <h4 id="time-count">
                    00:00:00
                </h4>
            </div>
            <div class="row" id="voting-content">
                {{-- voting-content --}}
            </div>
            <div class="modal fade" id="resultVoting" tabindex="-1" role="dialog" aria-labelledby="modalCandidate1Label" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 id="grafik-title" class="modal-title" id="modalCandidate1Label">Voting Result (RealTime)</h5>
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
                    data: [0,0,0,0,0,0,0,0],
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
            if (labelIndex !== -1) {
                // Jika label ditemukan, update data
                myChart.data.datasets.forEach((dataset) => {
                    dataset.data[labelIndex] = newData;
                });

                myChart.update(); // Perbarui grafik
            }
        }
        
        if (sessionStorage.getItem('session')==null) {
            return window.location = window.location.origin+'/login';
        }
        sessionCheck(sessionStorage.getItem('id'));
        loginCheck(sessionStorage.getItem('id'));
        
        var queryString = window.location.search;
        var urlParams = new URLSearchParams(queryString);
        if(urlParams.get('vote')!=null){
            var data_vote = urlParams.get('vote');
        }else{
            var data_vote = 1;
        }

        $.ajax({
            url: "/api/vote/",
            method: "GET", // First change type to method here
            success: function(response) {
                var data = response.data;
                data.forEach(vote =>{
                    $('.dropdown-menu').append(
                        '<a id=item-'+vote.id+' class="dropdown-item"data-value='+vote.id+'>'+vote.description+'</a>'
                    );
                    $('#item-'+vote.id).on('click', function() {
                        data_vote = vote.id;
                        var url = window.location.href.split('?')[0];
                        return window.location = url+'?vote='+vote.id;
                    });
                })
            }
        });

        $.ajax({
            url: "/api/team/"+data_vote,
            method: "GET", // First change type to method here
            success: function(response) {
                var data = response.data;
                data.forEach(element => {
                    var card_style = "font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; color: rgb(98, 104, 126)";
                    var number_style = "font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif";
                    if (element.id_vote == data_vote) {
                        var col = 12/data.length;
                        var candidate = "";
                        element.data_candidate.forEach(loopCandidate => {
                            candidate += loopCandidate.data_users.data_anggota.nama+"<br> "
                        });
                        $('#voting-content').append(
                            '<div class="col-12 col-md-'+col+'">'+
                                '<div>'+
                                    '<div class="text-center">'+
                                        '<img id="banner-'+element.id+'" class="img-fluid rounded" src='+element.banner_image+' alt="" style="height: '+col*67+'px;">'+
                                    '</div>'+
                                '</div>'+
                                '<div>'+
                                    '<div class="card" style='+card_style+'>'+
                                        '<h1 class="text-center text-lg pt-3">'+element.name+'</h1>'+
    
                                        '<h3 class="text-center text-md ">'+candidate+'</h3>'+
    
                                        '<hr class="mx-3" style="border-top:1px solid">'+
                                        '<p class="text-center mx-3">'+element.data_vote.description+'</p>'+
                                        // '<p class="display-3 text-center" style='+number_style+'>'+nomor+'</p>'+
                                        '<button class="btn mx-3 mb-1" data-toggle="modal" data-target="#visimisi-modal-'+element.id+'" style="background-color: rgb(98, 104, 126); color: white">'+
                                            'View Visi & Misi'+
                                        '</button><button name="vote" id="vote-'+element.id+'" class="btn btn-primary mx-3 mb-3">'+
                                            'VOTE'+
                                        '</button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div>'+
                                '<div class="modal fade" id="visimisi-modal-'+element.id+'" tabindex="-1" role="dialog" aria-labelledby="visimisi-label" aria-hidden="true">' +
                                    '<div class="modal-dialog modal-lg" role="document">' +
                                        '<div class="modal-content">' +
                                            '<div class="modal-header" style="background-color: rgb(98, 104, 126); color: white">' +
                                                '<h5 class="modal-title">Visi & Misi</h5>' +
                                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                                    '<span aria-hidden="true">&times;</span>' +
                                                '</button>' +
                                            '</div>' +
                                            '<div class="modal-body">' +
                                                '<h6>Visi:</h6>' +
                                                '<p>'+element.visi+'</p>' +
                                                '<h6>Misi:</h6>' +
                                                '<p>'+element.misi+'</p>' +
                                            '</div>' +
                                            '<div class="modal-footer">' +
                                                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>'
                        );
                        if (element.banner_image!=null) {
                            $('#banner-'+element.id+'').attr('src', `{{asset('storage/images/banner/${element.banner_image}')}}`);
                        }
    
                        myChart.data.labels.push(element.name);
    
                        $('#vote-'+element.id+'').on('click', function() {
                            var userConfirmed = window.confirm("Apakah Anda yakin ingin memilih "+element.name+" ?");
                            if (userConfirmed) {
                                $.ajax({
                                    url: "/api/ballot",
                                    method: "POST",
                                    data: {
                                        'user_id': sessionStorage.getItem('id'),
                                        'id_team':element.id,
                                        'id_vote':data_vote
                                    },
                                    success: function(response) {
                                        $('button[name="vote"]').attr('disabled',true) 
                                        $('button[name="vote"]').text("you've already voted") 
                                    }
                                });
                            }
                        });
                        $.ajax({
                            url: "/api/ballot",
                            success: function(response) {
                                var data = response.data;
                                data.forEach(check => {
                                    if (check.data_team.id_vote == data_vote) {
                                        if (check.user_id == sessionStorage.getItem('id')) {
                                            $('button[name="vote"]').attr('disabled',true) 
                                            $('button[name="vote"]').text("you've already voted") 
                                        }
                                    } 
                                });
                            }
                        });
                    }
                });
            }
        });
        
        $.ajax({
            url: "/api/vote/"+data_vote,
            success: function(response) {
                var data = response.data;
                var countDownEnds = data.voteEnds;
                var countDownStart = data.voteStart;

                
                if (new Date().getTime()<data.voteStart) {
                    countStart(countDownStart);
                    $('button[name="vote"]').attr('disabled',true) 
                    $('button[name="vote"]').text("voting hasn't started") 
                }else{
                    countEnd(countDownEnds);
                }
                if (new Date().getTime()>data.voteEnds) {
                    $('button[name="vote"]').attr('disabled',true) 
                    $('button[name="vote"]').text("voting has ended") 
                }
            }
        });
        function countEnd(countDownDate){;
            var x = setInterval(function() {
                
                // Get today's date and time
                var now = new Date().getTime();
                    
                // Find the distance between now and the count down date
                var distance = countDownDate - now;
                    
                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Add leading zeros to hours, minutes, and seconds
                hours = String(hours).padStart(2, '0');
                minutes = String(minutes).padStart(2, '0');
                seconds = String(seconds).padStart(2, '0');
                
                // Format output
                var formattedOutput = `${days} days ${hours}:${minutes}:${seconds}`;
                if (days == 0) {
                    var formattedOutput = `${hours}:${minutes}:${seconds}`;
                }
                
                // Output the result in an element with id="time-count"
                document.getElementById("time-count").innerHTML = formattedOutput;
                    
                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("time-count").innerHTML = "";
                    document.getElementById("time-label").innerHTML = "Voting has Ended";

                    $('button[name="vote"]').attr('disabled',true) 
                    $('button[name="vote"]').text("voting has ended") 

                    $.ajax({
                        url: "/api/ballot/"+data_vote,
                        method: "GET", // First change type to method here
                        success: function(response) {
                            var data = response.data;
                            var ballotCount = {};
                            
                            data.forEach(vote => {
                                if (isNaN(ballotCount[vote.data_team.name])) {
                                    ballotCount[vote.data_team.name] = 0;
                                }
                                ballotCount[vote.data_team.name] += 1;
                                updateDataForLabel(vote.data_team.name,ballotCount[vote.data_team.name]);
                            });
                        }
                    });
                }
            }, 1000);
        }
        
        function countStart(countDownDate){;
            var x = setInterval(function() {
                document.getElementById("time-label").innerHTML = "Voting Start In";
                
                // Get today's date and time
                var now = new Date().getTime();
                    
                // Find the distance between now and the count down date
                var distance = countDownDate - now;
                    
                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Add leading zeros to hours, minutes, and seconds
                hours = String(hours).padStart(2, '0');
                minutes = String(minutes).padStart(2, '0');
                seconds = String(seconds).padStart(2, '0');
                
                // Format output
                var formattedOutput = `${days} days ${hours}:${minutes}:${seconds}`;
                if (days == 0) {
                    var formattedOutput = `${hours}:${minutes}:${seconds}`;
                }
                
                // Output the result in an element with id="time-count"
                document.getElementById("time-count").innerHTML = formattedOutput;
                    
                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    window.location.reload();

                    $('button[name="vote"]').attr('disabled',false) 
                    $('button[name="vote"]').text("VOTE") 
                }
            }, 1000);
        }
    });
</script>
</body>
</html>
