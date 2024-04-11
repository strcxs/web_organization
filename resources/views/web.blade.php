<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Teknik Informatika</title>

		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;subset=devanagari,latin-ext" rel="stylesheet">
		<link rel="shortcut icon" type="image/icon" href="{{asset('storage/images/icon_himaif.png')}}"/>
        <link rel="stylesheet" href="{{asset('/css/dash/animate.css')}}">
        <link rel="stylesheet" href="{{asset('/css/dash/owl.carousel.min.css')}}">
		<link rel="stylesheet" href="{{asset('/css/dash/owl.theme.default.min.css')}}">
        <link rel="stylesheet" href="{{asset('/css/dash/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('/css/dash/bootsnav.css')}}">
        <link rel="stylesheet" href="{{asset('/css/dash/style.css')}}">
        <link rel="stylesheet" href="{{asset('/css/dash/responsive.css')}}">
		<link rel="stylesheet" href="{{asset('template/plugins/fontawesome-free/css/all.min.css')}}">

		<style>
			@media (min-width: 769px) {
				.hide-on-desktop {
					display: none !important;
				}
			}
			@media (max-width: 426px) {
				.header-text h2 {
					color: #fff;
					font-size: 30px;
					font-weight: 700;
					text-transform: uppercase;
					line-height: 1.5;
				}
			}
		</style>
    </head>
	
	<body>
		<header class="top-area">
			<div class="header-area">
				<nav class="navbar navbar-default bootsnav navbar-fixed dark no-background">
					<div class="container-fluid">
						<div class="row">
							<div class="col-12 col-xs-12 col-sm-12 col-md-6">
								<div class="container-fluid" id="company">
									<div class="navbar">
										<div class="row">
											<div class="col-xs-4 col-sm-2">
												<a class="navbar-brand" href="/">
													<img src="{{asset('storage/images/icon_himaif.png')}}" alt="profile_image" style="width: 55px;">
												</a>
											</div>
											<div class="col-xs-6 col-sm-9">
												<a class="navbar-brand" href="/">
													<h1 style="margin-top:21px;text-align: center">HMIF Nurtanio Bandung</h1>
												</a>
											</div>
											<div class="col-xs-2 col-sm-1">
												<br>
												<button class="navbar-brand hide-on-desktop" type="button" data-toggle="collapse" data-target="#navbar-menu" style="border: 2px solid #43485c;border-radius: 10px; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);">
													<i class="fas fa-bars"></i>
												</button>
											</div>											
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-xs-12 col-sm-12 col-md-6">
								<div class="row">
									<div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
										<ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
											<li class="nav-item">
												<a class="nav-link" href="#about">Tentang kami</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#portfolio">proker</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#contact">kontak</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="/login">login</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>						
					</div>
				</nav>
			</div>
			<div class="clearfix"></div>
		</header>		
		<section id="welcome-hero" class="welcome-hero">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<div class="header-text">
							<h2>Welcome to Our Website! <br><span>Kabinet Adhisti Nawasena</span></h2>
							<p>Himpunan Mahasiswa Teknik Informatika</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="about" class="about">
			<div class="section-heading text-center">
				<h2>Tentang kami</h2>
			</div>
			<div class="container">
				<div class="about-content">
					<div class="row">
						<div class="col-sm-6">
							<div class="single-about-txt">
								<h3>
									I am a Professional UI/UX Designer and Web developer. Consectetur an adipisi elita, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud.
								</h3>
								<p>
									Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspi unde omnis iste natus error sit voluptatem accusantium doloremque lauda ntium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam vo luptatem quia voluptas sit aspernatur aut odit aut fugit,
								</p>
								<div class="row">
									<div class="col-sm-4">
										<div class="single-about-add-info">
											<h3>phone</h3>
											<p>+62 -857-1235-747</p>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="single-about-add-info">
											<h3>email</h3>
											<p>himaif@gmail.com</p>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="single-about-add-info">
											<h3>website</h3>
											<p>hmifunnur.com</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-offset-1 col-sm-5">
							<div class="single-about-img">
								<img src="{{asset('storage/images/hai.png')}}" alt="profile_image"> 
								<div class="about-list-icon">
									<ul>
										<li>
											<a href="#">
												<i  class="fa fa-facebook" aria-hidden="true"></i>
											</a>
										</li>
										<li>
											<a href="#">
												<i  class="fa fa-dribbble" aria-hidden="true"></i>
											</a>
											
										</li>
										<li>
											<a href="#">
												<i  class="fa fa-twitter" aria-hidden="true"></i>
											</a>
											
										</li>
										<li>
											<a href="#">
												<i  class="fa fa-linkedin" aria-hidden="true"></i>
											</a>
										</li>
										<li>
											<a href="#">
												<i  class="fa fa-instagram" aria-hidden="true"></i>
											</a>
										</li>
										
										
									</ul>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</section>
	
		<section id="portfolio" class="portfolio">
			<div class="portfolio-details">
				<div class="section-heading text-center">
					<h2>Program kerja dan kegiatan</h2>
				</div>
				<div class="container">
					<div class="portfolio-content">
						<div class="isotope">
							<div class="row">

								<div class="col-sm-4">
									<div class="item">
										<img src="{{asset('storage/images/adhisti.jpg')}}" alt="profile_image"/>
										<div class="isotope-overlay">
											<a href="#">
												ui/ux design
											</a>
										</div>
									</div>

									<div class="item">
										<img src="{{asset('storage/images/adhisti.jpg')}}" alt="profile_image"/>
										<div class="isotope-overlay">
											<a href="#">
												ui/ux design
											</a>
										</div>
									</div>
								</div>

								<div class="col-sm-4">
									<div class="item">
										<img src="{{asset('storage/images/adhisti.jpg')}}" alt="profile_image"/> 
										<div class="isotope-overlay">
											<a href="#">
												web design
											</a>
										</div>
									</div>
									<div class="item">
										<img src="{{asset('storage/images/adhisti.jpg')}}" alt="profile_image"/>
										<div class="isotope-overlay">
											<a href="#">
												ui/ux design
											</a>
										</div>
									</div>
								</div>
								
								<div class="col-sm-4">
									<div class="item">
										<img src="{{asset('storage/images/adhisti.jpg')}}" alt="profile_image"/> 
										<div class="isotope-overlay">
											<a href="#">
												web development
											</a>
										</div>
									</div>
									<div class="item">
										<img src="{{asset('storage/images/adhisti.jpg')}}" alt="profile_image"/>
										{{-- <img src="assets/images/portfolio/p5.jpg" alt="portfolio image"/> --}}
										<div class="isotope-overlay">
											<a href="#">
												web development
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="contact" class="contact">
			<div class="section-heading text-center">
				<h2>kontak</h2>
			</div>
			<div class="container">
				<div class="contact-content">
					<div class="row">
						<div class="col-md-offset-1 col-md-5 col-sm-6">
							<div class="single-contact-box">
								<div class="contact-form">
									<form>
										<div class="row">
											<div class="col-sm-6 col-xs-12">
												<div class="form-group">
												  <input type="text" class="form-control" id="name" placeholder="Name*" name="name">
												</div>
											</div>
											<div class="col-sm-6 col-xs-12">
												<div class="form-group">
													<input type="email" class="form-control" id="email" placeholder="Email*" name="email">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<input type="text" class="form-control" id="subject" placeholder="Subject" name="subject">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<textarea class="form-control" rows="8" id="comment" placeholder="Message" ></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="single-contact-btn">
													<a class="contact-btn" href="#" role="button">submit</a>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="col-md-offset-1 col-md-5 col-sm-6">
							<div class="single-contact-box">
								<div class="contact-adress">
									<div class="contact-add-head">
										<h3>HMIF Nurtanio Bandung</h3>
									</div>
									<div class="contact-add-info">
										<div class="single-contact-add-info">
											<h3>phone</h3>
											<p>+62-987-123-6547</p>
										</div>
										<div class="single-contact-add-info">
											<h3>email</h3>
											<p>hmifunnur@gmail.com</p>
										</div>
										<div class="single-contact-add-info">
											<h3>website</h3>
											<p>hmifunnur.com</p>
										</div>
									</div>
								</div>
								<div class="hm-foot-icon">
									<ul>
										<li><a href="#"><i class="fa fa-facebook"></i></a></li>
										<li><a href="#"><i class="fa fa-dribbble"></i></a></li>
										<li><a href="#"><i class="fa fa-twitter"></i></a></li>
										<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
										<li><a href="#"><i class="fa fa-instagram"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<footer id="footer-copyright" class="footer-copyright">
			<div class="container">
				<div class="hm-footer-copyright text-center">
					<p>
						HMIF Nurtanio Bandung</a>
						{{-- &copy; copyright HMIF Nurtanio Bandung</a> --}}
					</p>
				</div>
			</div>
			<div id="scroll-Top">
				<div class="return-to-top">
					<i class="fa fa-angle-up " id="scroll-top" ></i>
				</div>
			</div>
        </footer>
		
		<script src="{{asset('/js//jquery.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
        <script src="{{asset('/js//bootstrap.min.js')}}"></script>
		<script src="{{asset('/js//bootsnav.js')}}"></script>
		<script src="{{asset('/js//jquery.sticky.js')}}"></script>
		
		<!-- for progress bar start-->
		<script src="{{asset('/js//progressbar.js')}}"></script>
		<script src="{{asset('/js//jquery.appear.js')}}"></script>
		<!-- for progress bar end -->

		<!--owl.carousel.js-->
        <script src="{{asset('/js//owl.carousel.min.js')}}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        
        <!--Custom JS-->
        <script src="{{asset('/js//custom.js')}}"></script>
    </body>
</html>