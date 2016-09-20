<!DOCTYPE html>
<html>
<head>
<title><?=  $page['title'] ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<meta name="keywords" content="<?= $page['keywords'] ?>" />
<meta name="description" content="<?= $page['description'] ?>" />

<!--webfonts-->
<link href="<?=  public_css('sys/page_login.css') ?>" rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Nixie+One' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<!--//webfonts-->
</head>
<body>
	<div class="main">
		<div class="login">
			<h1><a href="<?= $shop['url'] ?>" style="color: white;"><?= $shop['name'] ?></a></h1>
			<div class="inset">
				<!--start-main-->
				<form  method="POST"  >
			         
			         <div>
			         	
			         	<h2>Login Form</h2>
						<span><label>Email</label></span>
						<span><input type="text" placeholder="Your email address" value="<?= $email ?>" name="email" required class="textbox" id="active"></span>
					 </div>
					 <div>
						<span><label>Password</label></span>
					    <span><input type="password" placeholder="Your password"  name="password" required class="password"></span>
					 </div>
					 <?php if( isset($enable_captcha) && $enable_captcha ): ?>
					 <div>
					 	<span><label>Are you human?</label></span>
					 	<?= $captcha['image'] ?>
					 	<span><input type="text" placeholder="Type captcha code here"  name="captcha" required class="text"></span>
					 </div>
					<?php endif; ?>
					 <?=  validation_errors(); ?>
                     <?php foreach($errors as $err ) echo '<p>'.$err.'</p>'; ?>
					<div class="sign">
						<button type="submit" class="submit">
							Login
						</button>
					</div>
					<br/>
					<a href="/admin/recover_password" >Forgot your password ?</a>
					</form>
				</div>
			</div>
		<!--//end-main-->
		</div>
<!--start-copyright-->
<div class="copy-right">
	<p>&copy; <?= date('Y') . ' ' . $shop['name'] ?> . All Rights Reserved | <a href="<?= OS_BASE_SITE ?>" target="_blank" >Create your own online store</a></p>

</div>
<!--//end-copyright-->
</body>
</html>
