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
		<title><?=  isset($title) ? $title : 'Verify your email :: ' . OS_SITE_NAME ?></title>
	</head>
	<body onload="<?=  isset($onload) ? $onload : '' ?>"   >
		<div class="container"  style="width: 600px;" >
			<div class="row" >
				<div class="col-sm-12" style="background-color: #0000CF;" >
					<a href="/" ><img src="<?= public_resource('www/sedna/img/logo.png'); ?>" style="margin-left: auto; margin-right: auto; margin-bottom: 30px; " class="img-responsive" /></a>
				</div>
			</div>
			<h1 class="text-center">Verify your email address</h1>
			<br/>
			
			<div class="text-center" >	
				<img src="<?= public_img('emailVerifyAnimation.gif'); ?>" class="img-responsive" style="margin-left: auto; margin-right: auto;" />
				<?=  isset($code) ? $code : '' ?>
			</div>
			<br/>
			<p class="text-center" >
				We sent an email to <b><?= $signup['email'] ?></b><br/>
				
				<small><?=  isset($msg) ? $msg : 'We sent an email to your account, follow the link the email to verify your account.' ?></small>
				<a href="/" class="btn btn-block btn-default" >Back to homepage</a>
				<br/>
				<form action="/action/signup_change_email" method="POST" >
				<label>Change your email</label>
				<input type="email" name="new_email" class="form-control" value="<?= $signup['email'] ?>" />
				<input type="hidden" name="xsrf_token" value="<?= $xsrf_token ?>" />
				<div class="text-center" >
					<button type="submit" class="btn btn-danger " >Update email address</button>
				</div>
				</form>
			</p>
		</div>
	</body>
</html>
