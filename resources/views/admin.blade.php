<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard • Admin</title>
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
              <div class="card-header bg-primary">
                <h5>Landing Page</h5>
              </div>
              <div class="card-header"> 
                <p>about</p>
                <textarea id="aboutText" rows="5" cols="100"></textarea>
                <button class="button bg-warning" id="aboutSubmit">SAVE</button>
              </div>
            </div>

            <div class="card">
              <div class="card-header bg-primary">
                <h5>Contribution Chart</h5>
              </div>
              <div class="card-header"> 
                <canvas id="lineChart" style="height:250px"></canvas>
              </div>
            </div>
            <div class="card">
              <div class="card-header bg-primary">
                  <h3 class="card-title">Members Contribution</h3>
              </div>
              <div class="card-body">
                  <table id="countForumTable" class="table table-bordered table-striped">
                      <thead>
                          <tr id="head">
                              <th id="head">Name</th>
                              <th id="head">Batch Year</th>
                              <th id="head">Number Of Posts</th>
                              <th id="head">Number Of Comments</th>
                          </tr>
                      </thead>
                      <tbody>
                          {{-- table-content --}}
                      </tbody>
                  </table>
                  <hr>
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
<script>
    $(document).ready(function(){
        if (sessionStorage.getItem('session')==null || sessionStorage.getItem('session')!=1 ) {
            return window.location = window.location.origin+'/login';
        }
        sessionCheck(sessionStorage.getItem('id'));
        loginCheck(sessionStorage.getItem('id'));
        bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        bulanCEK = ['January 2024', 'February 2024', 'March 2024', 'April 2024', 'May 2024', 'June 2024', 'July 2024', 'August 2024', 'September 2024', 'October 2024', 'November 2024', 'December 2024'],
        forumData = [0,0,0,0,0,0,0,0,0,0,0,0];
        commentsData =  [0,0,0,0,0,0,0,0,0,0,0,0];
        $.ajax({
            url: "/api/dateFilter?filter=dateFilter",
            method: "GET", // First change type to method here
            success: function(response) {
                response.data.forums.forEach(data => {
                  for (let index = 0; index < bulan.length; index++) {
                      if (bulanCEK[index]==data.bulan) {
                          forumData[index]+=1;
                      }
                  }
                });
                response.data.comments.forEach(data => {
                  for (let index = 0; index < bulan.length; index++) {
                      if (bulanCEK[index]==data.bulan) {
                          commentsData[index]+=1;
                      }
                  }
                });

                var dataGrafik = {
                    labels  : bulan,
                    datasets: [
                        {
                            label               : 'Discuss',
                            backgroundColor     : 'rgba(0, 123, 255, 0)',
                            borderColor         : 'rgba(0, 123, 255, 1)', //garis
                            pointBackgroundColor : 'rgba(0, 123, 255, 1)', //titik
                            data                : forumData
                        },
                        {
                            label               : 'Comment',
                            backgroundColor     : 'rgba(0, 123, 255, 0)',
                            borderColor         : 'rgba(255, 0, 0, 1)', //garis
                            pointBackgroundColor : 'rgba(255, 0, 0, 1)', //titik
                            data                : commentsData
                        },
                    ]
                };

                var lineChartCanvas = $('#lineChart').get(0).getContext('2d');
                var lineChart = new Chart(lineChartCanvas, {
                    type: 'line',
                    data: dataGrafik,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });

                // Mengetahui grafik tertinggi
                var maxDiscuss = Math.max(...forumData);
                var maxComment = Math.max(...commentsData);
                var maxDiscussMonth = bulan[forumData.indexOf(maxDiscuss)];
                var maxCommentMonth = bulan[commentsData.indexOf(maxComment)];
                console.log('Grafik tertinggi Discuss: ' + maxDiscuss + ' pada bulan ' + maxDiscussMonth);
                console.log('Grafik tertinggi Comment: ' + maxComment + ' pada bulan ' + maxCommentMonth);
            }
        });
        $.ajax({
          url: "/api/dateFilter?filter=memberFilter",
          method: "GET", // First change type to method here
          success: function(response) {
            var data = response.data;
            data.forEach(element => {
              if (element.countForum == undefined) {
                element.countForum = 0;
              }
              if (element.countComment == undefined) {
                element.countComment = 0;
              }
              $("tbody").append(
                  "<tr>"+
                      "<td class='text'>"+element.data_anggota.nama+"</td>"+
                      "<td class='text-center'>"+element.data_anggota.tahun_akt+"</td>"+
                      "<td class='text-center'>"+element.countForum+"</td>"+
                      "<td class='text-center'>"+element.countComment+"</td>"+
                  "</tr>"
              );
            });
            
            $('#countForumTable').DataTable({
              "dom": "<'float-right'Bf><'float-left'l><t><p>",
              "buttons": ['pdf'],
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": true,
              "info": false,
              "autoWidth": true,
              "responsive": true,
              "pagingType": "simple",
              "lengthMenu": [20,50],
              "language": {
                "search": "search ",
                "paginate": {
                  "next": "<button type='button' class='btn btn-tool' style='font-size: 17px ;background-color: transparent; border: none; padding: 20; margin: 0;color: blue'> next</button>",
                  "previous": "<button type='button' class='btn btn-tool' style='font-size: 17px ;background-color: transparent; border: none; padding: 20; margin: 0;color: blue' >previous </button>"    
                },
                // "lengthMenu": "Display _MENU_ records per page",
                "zeroRecords": "Not found",
                "info": "Showing page _PAGE_ of _PAGES_",
                "infoEmpty": "No records available",
              }
            });
          }
        });
        $('#aboutSubmit').on('click',function(){
          $.ajax({
                url: origin+'/api/page',
                method: "POST",
                data:{
                  about_text:$('#aboutText').val()
                },
                success: function(response) {
                  window.location.reload();
                }
            });
        });
    });
</script>
</body>
</html>

