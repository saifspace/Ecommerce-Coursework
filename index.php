<!DOCTYPE html>
<html>

<head>
	<title>OneCoin</title>
	<!-- <link rel="icon" href="./images/coin.gif" type="image/gif" sizes="50x50">  -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/stylesheet.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body style="background-color:#171728">
	
	<div class="container-fluid">

		<div class="row">
			<div class="col-md">
				<div>
					<img id="header-bg" src="./images/header_background.gif">
					<img id="logo" src="./images/logo2.png">
					<?php include "./showUser.php"; ?>

				</div>
			</div>
		</div> 
	</div>
	
	<nav style="background-color:#FEDF46;">
		<div class="container-fluid" style=" "> 
				<ul class="nav navbar-nav" style="">
					<li class="nav-items"> <a class="nav-text" id="item-1" href="#">ATARI</a></li>
					<li class="nav-items"> <a class="nav-text" id="item-2" href="#">PLAYSTATION</a></li>
					<li class="nav-items"> <a class="nav-text" id="item-3" href="#">NINTENDO</a></li>
					<li class="nav-items"> <a class="nav-text" id="item-4" href="#">SEARCH</a></li>
				</ul>
		</div>
	</nav>

	<div class="container">
		<div class="row" style="" >
			<div class="col-md">
				<div class="slideshow-container" >
					<div class="slide-images effect" style="display: block">
						<img src="./images/products/StreetFighterSlideShow.jpg" style="height:350px; width:100%; object-fit: cover;">
					</div>
					<div class="slide-images effect">
						<img src="./images/products/oldschool.jpg" style=" height:350px; width:100%; object-fit: cover;">
					</div>
					<div class="slide-images effect">
						<img src="./images/products/controllers2.jpg" style=" height:350px; width:100%; object-fit: cover;">
					</div>
					<div class="slide-images effect">
						<img src="./images/products/controllers.jpg" style=" height:350px; width:100%; object-fit: cover;">
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		setInterval(function() {
  			$('.slideshow-container > div:first')
    		.fadeOut(0)
    		.next()
    		.fadeIn(2000)
    		.end()
    		.appendTo('.slideshow-container');
			}, 5000);

	</script>

</body>

</html>