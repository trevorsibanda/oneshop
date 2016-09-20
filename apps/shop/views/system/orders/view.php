<?php
    $shopper = $shipping;
    $is_paid = in_array($order['status'] , array('paid','archived') );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View your order - <?= $shop['name'] ?></title>
<!--
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<!-- main CSS -->
<link rel="stylesheet" href="<?= public_css('bootstrap.css'); ?>">
<link rel="stylesheet" href="<?= public_css('font-awesome.min.css'); ?>">

<!-- main CSS -->
<style type="text/css">
    
body {
    background: #CCC;
    font-family: 'Open Sans', sans-serif !important;
    }
    
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: 'Open Sans', sans-serif !important;
    }
    
    
li {
    list-style: none;
    }
    
.container {
    max-width: 1200px;
    }
    
p {
    color: #333;
}
a {
    text-decoration: none !important;               
}

.container {
    max-width: 960px;
    height: auto;
    background: #fff;
    padding: 20px;
    }
    
.header {
    width: 100%;
    height: auto;
    padding: 20px 0px;
    padding-bottom: 60px;
    text-align: center;
    padding-bottom: 20px;
    }
    
h1 {
    font-size: 40px;
    font-weight: bold;
    color: #333;
    }
    
.mbl {
    width: 100%;
    height: auto;
    }
    
.main-body {
    width: 100%;
    height: auto;
    float: left;
    padding: 0px 5px;
    }
    
.os_content .row {
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 30px 0px;
    }
    
.mbl-img {
    width: 100%;
    height: auto;
    float: left;
    }
    
.os_message-content {
    width: 100%;
    height: auto;
    float: left;
    }
    
.os_message {
    width: 100%;
    height: auto;
    float: left;
    margin-bottom: 10px;
    }
    
.os_message h4 {
    font-size: 16px;
    color: #333;
    font-weight: bold;
    }

.os_message h4 i {
    margin-right: 10px;
    }
    
    
a.caption-btn {
    padding: 15px 20px;
    margin-right: 10px;
    text-align: left;
    float: left;
    font-size: 25px;
    text-decoration: none;
    line-height: 24px;
    padding-top: 6px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

a.caption-btn:hover {
    color: #CCC;
    }

.btn-white {
    background: #333;
    border: 1px solid #fefefe;
    color: #fff;
    font-weight: 100;
        
    }
    
.btn-white span {
    font-size: 16px;
    display: block;
    font-weight: 100;
    }
    
    
.inventore {
    width: 100%;
    height: auto;
    padding: 30px 0px;
    border-bottom: 1px solid #eee;
    }
    
.inventore h4 {
    font-size: 20px;
    color: #333;
    font-weight: bold;
    }
    

.testi-box {
    border-left: 4px solid #E5E5E5;
    width: 100%;
    height: auto;
    margin-bottom: 20px;
    padding: 0px 15px;
    }

.testi-box p {
    font-size: 16px;
    color: #666;
    }
    
.testi-box small:before {
    content: "— ";
}

.testi-box small {
    color: #999999;
    }
    

.get_outa_here {
    width: 100%;
    height: auto;
    padding: 30px 0px;
    border-bottom: 1px solid #eee;
    }
    
.get_outa_here h4 {
    font-size: 20px;
    color: #333;
    font-weight: bold;
    text-align: center;
    margin-top: 0px;
    }
    
form.form-inline {
    width: 40%;
    margin: 0px auto;
    }
    
.social {
    width: 100%;
    height: auto;
    padding: 30px 0px;
    border-bottom: 1px solid #eee;
    text-align: center;
    }
    
.social a {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #F75F51;
    color: #fff;
    display: inline-block;
    margin-right: 10px;
    transition:all 0.2s ease-in-out 0s;
    }
    
.social a:hover {
    background: #e5e5e5;
    }
    
.social a .fa {
    line-height: 2.2;
    }
    
.footer {
    width: 100%;
    height: auto;
    margin-top: 8px;
    }
    
.footer p {
    font-size: 12px;
    color: #999999;
    }
    
.footer p a {
    color: #999999;
    }

.btn-bar{ background-color: #337AB7; color: #fff; font-weight: bold; margin-bottom: 10px;} 

.btn-bar:hover{ color: #fff; }   


</style>
</head>

<body>

    <div class="container">
    
        <div class="header">
            <h1><?= $shop['name'] ?></h1>
            <h5>Powered by OneShop</h5>
        </div>
        
        <div class="main-body">
            
            <div class="os_content">
            
                <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                    
                </div>
                <div class="col-sm-12 col-xs-12 col-md-4 col-lg-7">
                    
                    <div class="os_message-content">
                        <div class="os_message">
                            
                            <h2 class="text-center">
                                <i class="fa fa-<?= $is_paid ? 'check' : 'shopping-cart' ?> fa-3x"></i><br/>
                                Your order - <?= money( $order['total'] ) ?>
                            </h2>
                            <br/>
                            <p>Hey <?= $shopper['fullname'] ?>, Thank you for choosing us. <br/>
                            <?= $msg ?>
                            </p>
                            <div class="row " style=" ">
                                <div class="browse-bar">
                                    
                                    
                                    <div class="col-md-4 col-sm-6">
                                        <a href="<?= $print_url ?>" class="btn btn-block btn-bar" ><i class="fa fa-print"></i> Receipt</a>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-6">
                                        <a href="<?= $support_url ?>" class="btn btn-block btn-bar" ><i class="fa fa-support"></i> Customer support</a>
                                    </div>
                                    <?php if( ! $is_paid): ?>
                                    <div class="col-md-4 col-sm-6">
                                        <a href="<?= $checkout_url ?>" class="btn btn-block btn-danger" ><i class="fa fa-shopping-cart"></i> Checkout</a>
                                    </div>
                                    <?php elseif( isset($is_virtual_order) && $is_virtual_order ): ?>
                                    <div class="col-md-4 col-sm-6">
                                        <a href="<?= $download_url ?>" class="btn btn-block btn-info" ><i class="fa fa-download"></i> Download </a>
                                    </div>  
                                    <?php else: ?>
                                    <div class="col-md-4 col-sm-6">
                                        <a href="#" class="btn btn-block btn-disabled btn-danger" disabled ><i class="fa fa-shopping-cart"></i> Checkout</a>
                                    </div>  
                                    <?php endif; ?>    
                                    
                                </div>
                            </div>
                            <?php if( ! $is_paid ): ?>
                            <a href="<?= $checkout_url ?>" class="btn btn-danger btn-block btn-lg" ><i class="fa fa-check"></i> Pay <?= money($order['total'] ) ?> now</a>
                            <?php endif; ?>            
                            <?php foreach( $items as $item ):  $product = $item['product']; ?>
                            <div class="row" >
                                <div class="col-md-3 col-xs-12 col-sm-3" >
                                    <img src="<?= product_image( $product['images'][0] ) ?>" class="img-responsive" />
                                </div>
                                <div class="col-md-9 col-xs-12 col-sm-9" >
                                    <legend><?= $product['name'] ?><span class="pull-right"><?= money( $product['price'] ) . ' x ' . $item['qty'] ?></span></legend>
                                    <br/>
                                    <table class="table table-striped" >
                                        <?php foreach( $item['options'] as $option ):  ?>
                                        <tr>
                                            <td><?= $option['option'] ?></td>
                                            <td><?= $option['value'] ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>    
                            </div>
                            <?php endforeach; ?>
                            <div class="row" >
                                <div class="col-md-3 col-xs-6" >
                                    <h4 class="text-center">Subtotal:</h4>
                                    <h4 class="text-center">Shipping:</h4>
                                    <h4 class="text-center"><strong>Total:</strong></h4>
                                </div>
                                <div class="col-md-9 col-xs-6" >
                                    <h4 class="text-right"><?= money( $cart_total ) ?></h4>
                                    <h4 class="text-right"><?= money( $order['shipping_cost'] ) ?></h4>
                                    <h4 class="text-right"><?= money( $order['total'] ) ?></h4>
                                </div>
                            </div>
                            
                            <div class="row" >
                                <div class="col-sm-2" >
                                </div>
                                <div class="col-sm-8" >
                                    
                                    <?php if(  $is_paid ): ?>
                                    <h4 class=" text-center"><strong class="text-success">Paid <?= money( $order['total']) . ' using ' . $transaction['payment_gateway'] ?></strong></h4>    
                                    <a href="/shop" class="btn btn-danger btn-block btn-lg" ><i class="fa fa-check"></i> Keep shopping</a>
                                    <?php else: ?>
                                        <a href="<?= $checkout_url ?>" class="btn btn-danger btn-block btn-lg" ><i class="fa fa-check"></i> Pay <?= money($order['total'] ) ?> now</a>
                                        <a href="<?= $cancel_url ?>" class="btn btn-info btn-block btn-lg" ><i class="fa fa-remove"></i> Cancel order</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

            <div class="get_outa_here">
                
                <h4>Feeling lost?</h4>
                
                <a href="/" class="btn btn-block btn-info btn-lg"> << Back to shop</a>

                
            </div><!--/ .get_outa_here -->
            
            <div class="social">
                
                <a href="<?= $shop['contact']['facebook_page'] ?>"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="https://www.twitter.com/<?= $shop['contact']['twitter_handle'] ?>"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="<?= $shop['contact']['skype_id'] ?>"><i class="fa fa-skype fa-2x"></i></a>
                <a href="<?= $shop['contact']['linkedin_url'] ?>"><i class="fa fa-linkedin fa-2x"></i></a>
                
            </div><!--/ .social -->
            
            <div class="footer">
                
                <p><span class="text-center">Powered by <a href="<?= OS_BASE_SITE ?>" target="_blank"><?= OS_SITE_NAME ?></a></span></p>
                
            </div><!--/ .footer -->
            
        </div><!--/ .main-body -->
    
    </div>


</body>
</html>
    
