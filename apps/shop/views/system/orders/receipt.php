<?php $valid = array('pending','archived'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= 'Order #'. $order['order_id'] . ' | ' . $shop['name'] ?> </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?= public_css('bootstrap.css') ?>" rel="stylesheet" id="bootstrap-css">
    <style type="text/css">
    body {
    margin-top: 20px;
}
    </style>
    
    
    <style>
    .container{ width: 800px;}
    @media print{ button{ display: none;} .well{ } .btn{ display: none;} }
    </style>
</head>
<body>
    <div class="container">
    <div class="row">
        <?php if( ! isset($no_buttons)): ?>
        <a href="<?= $order_url ?>" class="btn btn-block btn-info" ><i class="glyphicon glyphicon-arrow-left"></i> Back to Order</a>
        <?php endif; ?>
        <div class="well col-xs-12">

                
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 text-left">
                    
                    <address>
                        <strong><?= $shop['name'] ?></strong>
                        <br/>
                        <?= $shop['address'] ?>
                        <br>
                        <?= $shop['city'] ?>
                        <br>
                        <?= $shop['country'] ?>
                        <br>
                        <abbr title="Phone"></abbr> <?= $shop['telephone'] ?>
                        <br>
                        <?= $shop['url'] ?>
                        
                    </address>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                    <p>
                        <em><?= $order['shipping']['fullname'] ?></em>
                    </p>
                    <p>
                        <em><?= $order['shipping']['address'] ?></em>
                    </p>
                    <p>
                        <em><?= $order['shipping']['city'] ?></em>
                    </p>
                    <p>
                        <em><?= $order['shipping']['email'] ?></em>
                    </p>
                    <p>
                        <em><?= $order['shipping']['phone_number'] ?></em>
                    </p>

                </div>
                
            </div>
            <div class="row">
                <div class="text-center">
                    <h2>Receipt for order #<?= $order['order_id'] ?></h2>
                </div>
                </span>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>#</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $items as $item ): ?>
                        <tr>
                            <td class="col-md-9"><em><?= $item['product']['name'] ?></em></h4></td>
                            <td class="col-md-1" style="text-align: center"> <?= $item['qty'] ?> </td>
                            <td class="col-md-1 text-center"><?= money( $item['cost'])  ?></td>
                            <td class="col-md-1 text-center"><?= money( $item['subtotal'] ) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td> &nbsp; </td>
                            <td> &nbsp; </td>
                            <td class="text-right">
                            <p>
                                <strong>Subtotal:&nbsp;</strong>
                            </p>
                            <p>
                                <strong>Shipping:&nbsp;</strong>
                            </p></td>
                            <td class="text-center">
                            <p>
                                <strong><?= money( $cart_total ) ?></strong>
                            </p>
                            <p>
                                <strong><?= money( $order['shipping_cost'] ) ?></strong>
                            </p></td>
                        </tr>
                        <tr>
                            <td> &nbsp; </td>
                            <td> &nbsp; </td>
                            <td class="text-right"><h4><strong><?=  $order['status'] == 'paid' ? 'Amount Paid': 'Due ' ?>:&nbsp;</strong></h4></td>
                            <td class="text-center <?=  in_array( $order['status'] , $valid ) ? 'text-success': 'text-danger' ?>"><h4><strong><?= money( $order['total'] ) ?></strong></h4></td>
                            
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        <?php if( $order['status'] == 'pending' ): ?> 
                            <td class="text-center">Expires: </td>
                            <td class="text-center"><strong class="text-danger"><?= $order['expire_date'] ?></strong></td>
                            <?php endif; ?>
                        </tr>
                    </tbody>
                </table>
                <br/>
                <legend> Payment details</legend>
                <table class="table table-striped" >
                    <thead>
                        <tr>
                        <td>Gateway</td>
                        <td>Amount</td>
                        <td>Time Paid</td>
                        <td>Transaction ID</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $order['transaction']['payment_gateway'] ?></td>
                            <td><?= money($order['transaction']['amount']) ?></td>
                            <td><?= $order['transaction']['time_paid'] ?></td>
                            <td><?= $order['transaction']['transaction_id'] ?></td>
                        </tr>
                    </tbody>        
                </table>
                <legend> Shipping details</legend>
                <table class="table table-striped" >
                    <thead>
                        <tr>
                            <td>Type</td>
                            <td>Country</td>
                            <td>City</td>
                            <td>Suburb</td>
                            <td>Address</td>
                            <td>Collection code</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= ucwords( $order['shipping']['type'] ) ?></td>
                            <td><?= $order['shipping']['country'] ?></td>
                            <td><?= $order['shipping']['city'] ?></td>
                            <td><?= $order['shipping']['suburb'] ?></td>
                            <td><?= $order['shipping']['address'] ?></td>
                            <td><?= $order['shipping']['collection_code'] ?></td>
                        </tr>
                    </tbody>        
                </table>
                <?php if( ! isset($no_buttons)): ?>
                <button type="button" onclick="window.print()" class="btn btn-success btn-lg btn-block">
                    Print&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-print"></span>
                </button>
                <?php endif; ?>
                <br/>
                
                <p>To validate the authenticity of this receipt, go to <b><?= $order_url ?></b> or enter order challenge code <b><?= $order['v_code'] ?></b> in admin panel. </p> 
            </div>
        </div>
    </div>
    <script src="<?= public_js('jquery.min.js'); ?>"></script>
    <script src="<?= public_js('bootstrap.min.js'); ?>"></script>

</body>
</html>
