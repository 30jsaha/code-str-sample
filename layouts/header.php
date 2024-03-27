<!DOCTYPE html>
<html lang="en">

    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
   
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
     <!-- Site Metas -->
    <title>Saha CyberTech EDUgroup</title>  
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="<?=CDN_URL; ?>img/sctLogo.png" type="image/x-icon" />
    <link rel="apple-touch-icon" href="<?=CDN_URL; ?>img/sctLogo.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=CDN_URL; ?>css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="<?=CDN_URL; ?>css/style.css">
    <!-- ALL VERSION CSS -->
    <link rel="stylesheet" href="<?=CDN_URL; ?>css/versions.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="<?=CDN_URL; ?>css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?=CDN_URL; ?>css/custom.css">

    <!-- Modernizer for Portfolio -->
    <script src="<?=CDN_URL; ?>js/modernizer.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="host_version"> 

	<!-- Modal -->
	<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header tit-up">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Customer Login</h4>
			</div>
			<div class="modal-body customer-box">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs">
					<li><a class="active" href="#Login" data-toggle="tab">Login</a></li>
					<li><a href="#Registration" data-toggle="tab">Registration</a></li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane active" id="Login">
						<form role="form" class="form-horizontal">
							<div class="form-group">
								<div class="col-sm-12">
									<input class="form-control" id="email1" placeholder="Name" type="text">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<input class="form-control" id="exampleInputPassword1" placeholder="Email" type="email">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-10">
									<button type="submit" class="btn btn-light btn-radius btn-brd grd1">
										Submit
									</button>
									<a class="for-pwd" href="javascript:void(0);">Forgot your password?</a>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane" id="Registration">
						<form role="form" class="form-horizontal">
							<div class="form-group">
								<div class="col-sm-12">
									<input class="form-control" placeholder="Name" type="text">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<input class="form-control" id="email" placeholder="Email" type="email">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<input class="form-control" id="mobile" placeholder="Mobile" type="email">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<input class="form-control" id="password" placeholder="Password" type="password">
								</div>
							</div>
							<div class="row">							
								<div class="col-sm-10">
									<button type="button" class="btn btn-light btn-radius btn-brd grd1">
										Save &amp; Continue
									</button>
									<button type="button" class="btn btn-light btn-radius btn-brd grd1">
										Cancel</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	  </div>
	</div>

    <!-- LOADER -->
	<div id="preloader">
		<div class="loader-container">
			<div class="progress-br float shadow">
				<div class="progress__item"></div>
			</div>
		</div>
	</div>
	<!-- END LOADER -->	

    <!-- Start header -->
	<header class="top-navbar">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container-fluid">
				<a class="navbar-brand" href="<?=HOST_URL; ?>" <?php if(is_mobile()): ?> style="width:50% !important;" <?php endif;?>>
					<img src="<?=CDN_URL; ?>img/sctLogoFull.png" alt="" <?php if(is_mobile()): ?> style="width:100% !important;" <?php else:?> style="width:40%;" <?php endif;?> />
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-host" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
					<span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbars-host">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item <?php if(($action == "home") || ($action == "") || $action == "index"): ?> active <?php endif; ?>"><a class="nav-link" href="<?=HOST_URL;?>">Home</a></li>
						<li class="nav-item <?php if($action == "about"): ?> active <?php endif; ?>"><a class="nav-link" href="<?=HOST_URL; ?>about/">About Us</a></li>
						<li class="nav-item dropdown <?php if(($action == "course-grid-2") || ($action == "course-grid-3") || $action == "course-grid-4"): ?> active <?php endif; ?>">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown-a" data-toggle="dropdown">Course </a>
							<div class="dropdown-menu" aria-labelledby="dropdown-a">
								<a class="dropdown-item" href="<?=HOST_URL; ?>course-grid-2/">Course Grid 2 </a>
								<a class="dropdown-item" href="<?=HOST_URL; ?>course-grid-3/">Course Grid 3 </a>
								<a class="dropdown-item" href="<?=HOST_URL; ?>course-grid-4/">Course Grid 4 </a>
							</div>
						</li>
						<!-- <li class="nav-item dropdown <?php if(($action == "blog") || ($action == "blog-Single")): ?> active <?php endif; ?>">
							<a class="nav-link dropdown-toggle" href="javascript:void(0);" id="dropdown-a" data-toggle="dropdown">Blog </a>
							<div class="dropdown-menu" aria-labelledby="dropdown-a">
								<a class="dropdown-item" href="<?=HOST_URL; ?>blog/">Blog </a>
								<a class="dropdown-item" href="<?=HOST_URL; ?>blog-Single/">Blog single </a>
							</div>
						</li> -->
						
						<li class="nav-item <?php if(($action == "blog") || $action == "blog-Single"): ?> active <?php endif; ?>"><a class="nav-link" href="<?=HOST_URL; ?>blog/">Blog</a></li></li>
						<li class="nav-item <?php if($action == "teachers"): ?> active <?php endif; ?>"><a class="nav-link" href="<?=HOST_URL; ?>teachers/">Teachers</a></li>
						<li class="nav-item <?php if($action == "pricing"): ?> active <?php endif; ?>"><a class="nav-link" href="<?=HOST_URL; ?>pricing/">Pricing</a></li>
						<li class="nav-item <?php if($action == "contact"): ?> active <?php endif; ?>"><a class="nav-link" href="<?=HOST_URL; ?>contact/">Contact</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
                        <li><a class="hover-btn-new log orange" href="<?=HOST_URL; ?>admin-area/"><span>Members Login</span></a></li>
                    </ul>
				</div>
			</div>
		</nav>
	</header>
	<!-- End header -->