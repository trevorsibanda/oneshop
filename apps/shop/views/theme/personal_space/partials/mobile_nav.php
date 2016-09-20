<div id="logo" class="visible-sm visible-xs">
	<img src="<?=  shop_image( $shop['logo'] ) ?>" class="img-responsive" style="margin-left: auto; margin-right: auto; max-width: 64px;" alt="<?= $shop['name'] ?>" />	
</div>
<nav class="navbar  visible-sm visible-xs" role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" style="background-color: #fff" data-toggle="collapse" data-target="#navbar-collapsible">
      	<span class="fa fa-bars" style="color: #00d;"></span>
        <span class="sr-only">Toggle navigation</span>
        
      </button>
      <a class="navbar-brand" href="<?= shop_url('') ?>">Trevor's Shop</a>
    </div>
    <div class="navbar-collapse collapse" id="navbar-collapsible">
      <ul class="nav navbar-nav navbar-left">
        <li><a href="<?= shop_url('browse') ?>">Browse Store</a></li>
        <li><a href="<?= shop_url('blog') ?>">My Blog</a></li>
        <li><a href="<?= shop_url('about-us') ?>">About me</a></li>
        <li><a href="/cart/checkout">Checkout <?= money($cart_total ) ?></a></li>
        <li>&nbsp;</li>
      </ul>
      <form class="navbar-form">
        <div class="form-group" style="display:inline;">
          <div class="input-group"> 
            <div class="input-group-btn">
              
            </div>
            <input type="text" class="form-control" placeholder="What are searching for?">
            <span class="input-group-addon"><span class="fa fa-search"></span> </span>
          </div>
        </div>
      </form>
    </div>
 
</nav>

				
