<!doctype HTML>
<html>
	<head>
		<link rel="stylesheet" href="<?=  public_css('bootstrap.css') ?>" />
		<style>
		.loader {
  font-size: 10px;
  margin: 50px auto;
  text-indent: -9em;
  width: 11em;
  height: 11em;
  border-radius: 50%;
  background: #f00;
  background: -moz-linear-gradient(left, #0dc5c1 10%, rgba(0,0 , 0, 0) 42%);
  background: -webkit-linear-gradient(left, #0dc5c1 10%, rgba(255, 255, 255, 0) 42%);
  background: -o-linear-gradient(left, #0dc5c1 10%, rgba(255, 255, 255, 0) 42%);
  background: -ms-linear-gradient(left, #0dc5c1 10%, rgba(255, 255, 255, 0) 42%);
  background: linear-gradient(to right, #0dc5c1 10%, rgba(255, 255, 255, 0) 42%);
  position: relative;
  
  -webkit-animation: load3 1.4s infinite linear;
  animation: load3 1.4s infinite linear;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}
.loader:before {
  width: 50%;
  height: 50%;
  background: #0dc5c1;
  border-radius: 100% 0 0 0;
  position: absolute;
  top: 0;
  left: 0;
  content: '';
}
.loader:after {
  background: #fff;
  width: 75%;
  height: 75%;
  border-radius: 50%;
  content: '';
  margin: auto;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
@-webkit-keyframes load3 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes load3 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

		</style>
		<title><?=  isset($title) ? $title : 'Redirecting... :: OneShop Secure Redirect' ?></title>
	</head>
	<body onload="<?=  isset($onload) ? $onload : '' ?>"  >
		<div class="container" >
			<h1 class="text-center">Select account to login to</h1>
			<br/>
			<div class="loader" style="display: none;" ></div>
			<div class="text-center" >	
				<?=  isset($code) ? $code : '' ?>
			</div>
			<br/>
			<div class="row" >
				<div class="col-md-4 col-md-offset-4" >
				<?php foreach( $login_tokens as $token ): ?>
				<div class="row" >
					
					<div class="col-md-3" >
						<img src="<?= shop_image( $token['shop']['logo'] )  ?>" style="max-width: 96px; margin-left: auto;" class="img-responsive img-cirle" />
					</div>
					<div class="col-md-9" >
						<legend><?= $token['shop']['name'] ?></legend>
						<p>Login as <?= $token['user']['fullname'] ?></p>
						
					</div>
					<div class="col-md-12" >
						<a href="<?=  $token['url'] ?>" class="btn btn-default btn-lg btn-block" >Login to <?= $token['shop']['name'] ?></a>
					</div>
				</div>
				
				<br/><br/>
				<?php endforeach; ?>
				</div>
			</div>
			<p class="text-center" >
				<small><?=  isset($msg) ? $msg : 'Select an account you would like to login to...' ?></small>
			</p>
		</div>
	</body>
</html>
