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
					<a href="index.php"><img id="logo" src="./images/logo2.png"></a>
					<?php include "./showUser.php"; ?>

				</div>
			</div>
		</div> 
	</div>
	
	<nav style="background-color:#FEDF46;">
		<div class="container-fluid" style=" "> 
				<ul class="nav navbar-nav" style="">
					<li class="nav-items"> <a class="nav-text" id="item-1" href="atari.php">ATARI</a></li>
					<li class="nav-items"> <a class="nav-text" id="item-2" href="#">PLAYSTATION</a></li>
					<li class="nav-items"> <a class="nav-text" id="item-3" href="#">NINTENDO</a></li>
					<li class="nav-items"> <a class="nav-text" id="item-4" href="#">SEARCH</a></li>
					<li class="nav-items"> <a class="nav-text" id="item-4" href="basket.php">BASKET</a></li>
				</ul>
		</div>
	</nav>

	<div class="container">
		<div class="row" style="" >
			<div class="col-md-1">
				
			</div>

			<div class="col-md-10">
				<div class="slideshow-container" >
					<div class="slide-images effect" style="display: block">
						<img src="./images/products/StreetFighterSlideShow.jpg" style="height:250px; width:100%; object-fit: cover;">
					</div>
					<div class="slide-images effect">
						<img src="./images/products/oldschool.jpg" style=" height:250px; width:100%; object-fit: cover;">
					</div>
					<div class="slide-images effect">
						<img src="./images/products/controllers2.jpg" style=" height:250px; width:100%; object-fit: cover;">
					</div>
					<div class="slide-images effect">
						<img src="./images/products/controllers.jpg" style=" height:250px; width:100%; object-fit: cover;">
					</div>
				</div>
			</div>

			<div class="col-md-1">
				
			</div>

		</div>
	</div>

	<div class="container-fluid" style="position: absolute; width: 100%; bottom: 0;">
		
		<div class="row">
			<div class="col-md" style="background-color: #1A1A2E">
						<!-- <div class="footer"> -->
    	 					<!-- <p><b>Developer: </b>Mohammed Shaikh</p><br/> -->
  							<p style="text-align: center; position: relative; top:50%; color: white">© 2018 Copyright: <a href="index.php"> OneCoin Inc. </a></p>
						<!-- </div>  -->
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