<?php
/**
 * Orders cron job.
 *
 * Expires overdue orders, checks for any broken orders, deletes expired orders
 * 
 *
 * @author      Trevor Sibanda
 * @date        10 October 2015
 * @package     Controllers/cron/Orders
 */

class Orders extends OS_Controller
{
    public function __construct()
    {
        ignore_user_abort(1);
        parent::__construct();
        
        $this->data = $this->load_shop();
        $this->load->model('payment');

    }

        
    
    /**
     * Expire all orders that have outlived their validity
     *
     * Ideally run once a day.
     */
    public function expire_overdue_orders( )
    {
        echo "[+] Started expire orders cron job at " . date('r') . "\n";
        $shop = $this->data['shop'];
        $o_settings = $this->shop->settings->get_order_settings($shop['shop_id'] );
        if( empty($o_settings))
            die('[!] Fatal error. Failed to get order settings.');

        $pending_orders = array();
        $pending_orders = $this->system->cart->get_shop_orders( $shop['shop_id'] , 'pending' ,  999 , 0 , 'ASC' );
        
        echo "[=] Found " . count( $pending_orders ) . " pending orders \n";

        //date in uix time
        $max_lifetime = $o_settings['order_expire_days'] * ( 24 * 60 * 60 );

        echo "[=] Order expire time is {$o_settings['order_expire_days']} days or " . $max_lifetime . " seconds\n";

        $cancel_queue = array();


        foreach( $pending_orders as $order )
        {
            $do_force_delete = False;
            $now = time();

            echo "[=]Processing order " . $order['order_id'] . " :: Amount ( " . money( $order['total'] ) . " ) Created {$order['date_created']} Expires {$order['expire_date']} by shopper {$order['shopper_id']} ";
            $b = $this->system->cart->load_order_into_cart( $order['order_id'] );
            if( ! $b )
            {
                echo "\t[-] Error loading order " . $order['order_id'] . " into cart. Orderalready deleted ? \n";
                continue;
            }

            $transaction = $this->payment->transaction->get( $order['transaction_id'] );
            if( empty($transaction) )
            {
                echo "\t Empty transaction, suggests order deleted or checkout not completed. Must delete order.\n";
                $do_force_delete = True;
            }

            //delete if old.
            $date_created = strtotime($order['date_created']);
            $expire_date = strtotime($order['expire_date']);

            if( $now > $expire_date )
            {
                //order expired
                //queue for deletion.
                array_push($cancel_queue,  $order );
                echo "\t[*] Order has expired and has been queued for deletion.\n";
                continue;
            }
            echo "\t[-] Order has not yet expired. \n";
        }
        
        //cancel queued orders, sending out emails
        foreach($cancel_queue as $order )
        {
            echo "\t[-] Cancelling order {$order['order_id']} \n";
            $this->system->cart->load_order_into_cart( $order['order_id'] );

            $shipping = $this->system->cart->get_order_shipping( $order['order_id'] );
            $transaction = $this->payment->transaction->get( $order['transaction_id'] );
            $items = $this->system->cart->get_order_items( $order['order_id'] );

            //restore stock for each item which was ordered
            foreach(  $items as $item )
            {
                if( ! empty( $item['product_json']))
                    continue; //deleted product, this should never happen
                //add stock back
                $product = $this->product->get( $item['product_id'] );
                if( ! empty($product) )
                {
                    //increment stock
                    $product['stock_left'] += $item['qty'];
                    //decrement ordered by qty
                    $product['stock_ordered'] -= $item['qty'];
                    $this->product->update( $product['product_id'] , $product );
                    echo "\t[-] Updated product  {$product['product_id']} stock details \n";
                }
            }

            //alert customer that the order has been deleted
            //send the user a notification
            $email = array();
            $email['type'] = 'order';
            $email['header'] = 'Your order has been cancelled';

            $email['subheader'] = '';
            $email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/> Your order has been automatically cancelled because it has expired.<br/>If you wish to continue this order, follow the link below.<br/><br/>We hope to see you soon.<br/>';
            $email['action_link'] = $this->data['shop']['url'];
            $email['action_message'] = 'Start shopping!';

            //url to continue order
            $url = $this->data['shop']['url'];

            $sms =  $this->data['shop']['name'] . "\nIMPORTANT: Your order has been cancelled.\nFollow " . $url . ' to continue your order. An email with more info was also sent to you.' . "\n This message is intended for {$shipping['fullname']} if this is not you, ignore it";
            $cart = array();
            
            $html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , $this->data['products'] , $cart );
            //push email
            $this->system->pushnotification->push_email( $order['shop_id'] , $shipping['fullname'] , $shipping['email'], 'IMPORTANT: Your order has been cancelled', $html );
            //push sms
            //critical but not as much :)
            if( account() != 'free' )
                $this->system->pushnotification->push_sms( $order['shop_id'] ,  $shipping['phone_number'] , $sms ,  8 );

            //set as cancelled
            $order['status'] = 'cancelled';
            $order['expire_date'] = date('Y-m-d');


            $order['log'] = $order['log'] . "\n Updated order set it to cancelled at " . date('r');
            $this->system->cart->update_order($order['order_id'], $order );

            echo "\t[-] Cancelled order {$order['order_id']} \n";
        }

        echo "[+] Finished expire orders cron job at " . date('r') . "\n";

    }
    
    /**
     * Delete all orders that have expired.
     *
     * Ideally run once a day
     */
    public function delete_expired_orders()
    {
        echo "[+] Started delete expired orders cron job at " . date('r') . "\n";
        $shop = $this->data['shop'];
        $o_settings = $this->shop->settings->get_order_settings($shop['shop_id'] );
        if( empty($o_settings))
            die('[!] Fatal error. Failed to get order settings.');

        $pending_orders = array();
        $pending_orders = $this->system->cart->get_shop_orders( $shop['shop_id'] , 'cancelled' ,  999 , 0 , 'ASC' );
        
        echo "[=] Found " . count( $pending_orders ) . " cancelled orders \n";

        //date in uix time
        $max_lifetime = $o_settings['order_expire_days'] * ( 24 * 60 * 60 );

        echo "[=] Order expire time is {$o_settings['order_expire_days']} days or " . $max_lifetime . " seconds\n";

        $delete_queue = array();


        foreach( $pending_orders as $order )
        {
            $do_force_delete = False;
            $now = time();

            echo "[=]Processing order " . $order['order_id'] . " :: Amount ( " . money( $order['total'] ) . " ) Created {$order['date_created']} Expires {$order['expire_date']} by shopper {$order['shopper_id']} ";
            $b = $this->system->cart->load_order_into_cart( $order['order_id'] );
            if( ! $b )
            {
                echo "\t[-] Error loading order " . $order['order_id'] . " into cart. Orderalready deleted ? \n";
                continue;
            }

            $transaction = $this->payment->transaction->get( $order['transaction_id'] );
            if( empty($transaction) )
            {
                echo "\t Empty transaction, suggests order deleted or checkout not completed. Must delete order.\n";
                $do_force_delete = True;
            }
            //@todo
            //because deleting verify one last time from the payment gateway before deleteing

            //delete if old.
            $expire_date = strtotime($order['expire_date']);

            //delete after n-days expired, specified by user.
            if( $now > ( $expire_date + $max_lifetime ) )
            {
                //order expired
                //queue for deletion.
                array_push($delete_queue,  $order );
                echo "\t[*] Order has expired and cancelled and has been queued for deletion.\n";
                continue;
            }
            echo "\t[-] Order has not yet expired. \n";
        }
        
        echo "[=] Found " . count( $delete_queue ) . " orders to be deleted \n ";

        //cancel queued orders, sending out emails
        foreach($delete_queue as $order )
        {
            echo "\t[-] Deleting order {$order['order_id']} \n";
            $this->system->cart->load_order_into_cart( $order['order_id'] );

            $shipping = $this->system->cart->get_order_shipping( $order['order_id'] );
            $transaction = $this->payment->transaction->get( $order['transaction_id'] );
            $items = $this->system->cart->get_order_items( $order['order_id'] );

            if( !empty($transaction) && $transaction['amount'] != 0.00 )
            {
                echo '[!] Cannot delete an order which has been paid for. Transaction ' . $transaction['transaction_id'] . ' paid - ' . money($transaction['amount']);
                continue;
            }

            //save logs for future
            echo "\t[-] Saving order deletion data.\n";
            echo "\t[=] Backup text data: \n\n\n\n\n";
            echo base64_encode(  json_encode( array('order' => $order, 'shipping' => $shipping , 'transaction' => $transaction , 'items' => $items ) ));

            

            $this->system->cart->remove_order(  $order['order_id'] );

            //send email to customer ???
            //@todo send out email and/or sms to customer
            $email = array();
            $email['type'] = 'order';
            $email['header'] = 'Your order has been deleted';
            $email['subheader'] = '';
            $email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/>We were doing some house keeping and had to delete your order to keep things running quickly and smoothly. We had already cancelled your order due to inactivity. We apologize for any inconvience caused. ';
            $email['action_link'] = $this->data['shop']['url'];
            $email['action_message'] = 'Start shopping ';

            $html= $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , array() , array() );
        
            $this->system->pushnotification->push_email( $order['shop_id'] , $shipping['fullname'] , $shipping['email'] , $email['header'] , $html );
            
            echo "\t[-] 'Deleted order # {$order['order_id']}  by  {$shipping['fullname']}  -  {$shipping['email']} \n";
        }

        echo "[+] Finished delete expired orders cron job at " . date('r') . "\n";

    }
    
    /**
     * Check if any pending transaction has actually been paid for but not yet updated.
     * Queries remote server
     *
     * Run this query once a day.
     * @todo Must be run before checking expired or cancelled orders
     */
    public function check_all_pending_transactions( )
    {
        
    }
    
    
    /**
     * Send an email reminding customer to pay or complete
     * order.
     *
     * Run this once a day after cancelling orders
     *
     * A reminder is sent after 1,3,7,14 and 28 days
     */
    public function order_reminders( )
    {
        echo "[+] Started orders reminder cron job at " . date('r') . "\n";
        $shop = $this->data['shop'];
        $o_settings = $this->shop->settings->get_order_settings($shop['shop_id'] );
        if( empty($o_settings))
            die('[!] Fatal error. Failed to get order settings.');

        $pending_orders = array();
        $pending_orders = $this->system->cart->get_shop_orders( $shop['shop_id'] , 'pending' ,  999 , 0 , 'ASC' );
        
        echo "[=] Found " . count( $pending_orders ) . " pending orders \n";

        //date in uix time
        $max_lifetime = $o_settings['order_expire_days'] * ( 24 * 60 * 60 );
        $count = 0;
        foreach( array(1,3,7,14,28) as $days )
        {
            $count++;
            echo "[=] Checking orders in $days days \n";
            foreach( $pending_orders as $order )
            {
                //date created + n days
                $time_added = date('Y-m-d', strtotime("+{$days} days" , strtotime($order['date_created'])));
                
                $today = date('Y-m-d');
                if( $today ==  $time_added )
                {
                    echo "\t[-] Sending out reminder email to order " . $order['order_id'] . " on day {$days} after order was placed. \n";

                    //url shown when payment is successfully made
                    $order_url = $this->data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . md5( $order['date_created'] . OS_BASE_DOMAIN . $order['order_id'] ) . '?action=view' ;
                    //url to cancel order
                    $order_cancel_url = $this->data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . md5( $order['date_created'] . OS_BASE_DOMAIN . $order['order_id'] ) . '?action=cancel';

                    //send out email 

                    $email = array();
                    $email['type'] = 'order';
                    $email['header'] = 'Reminder ( ' . $count . ' ) : Your order is waiting';

                    $shipping = $this->system->cart->get_order_shipping( $order['order_id'] );
            

                    $email['subheader'] = '';
                    $email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/>This is an automatically generated email reminding you that you have an order pending on our online shop. You placed the order ' . $days . ' days ago.<br/> To checkout an complete the payment, click on the button below.';
                    $email['action_link'] = $order_url;
                    $email['action_message'] = 'Complete your order.';
                    $email['footer_msg'] = 'After making the payment you will receive an SMS with a collection code you will be required to produce at our stores, you will then be able to collect your order. If you did not place this order, please click on cancel order to avoid receiving any future emails.';
                    $email['footer_action'] = 'Cancel this order';
                    $email['footer_action_url'] = $order_cancel_url;        
                    $this->data['shop']['contact'] = $this->shop->settings->get_contact_settings( $this->data['shop']['shop_id'] );
                    
                    $this->system->cart->load_order_into_cart( $order['order_id'] );
                    $cart = $this->system->cart->items();
                    
                    $html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , $this->data['products'] , $cart );
                    
                    $this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $shipping['fullname'] , $shipping['email'], $email['header'], $html );
                    
                }
            }    
        }
        echo "[+] Finished delete expired orders cron job at " . date('r') . "\n";
                
    }

    /** Automatically move paid orders into archive after 2 weeks. Ideally run once a day, after everything else **/
    public function archive_paid_orders( )
    {
        echo "[+] Started delete expired orders cron job at " . date('r') . "\n";
        $shop = $this->data['shop'];
        $o_settings = $this->shop->settings->get_order_settings($shop['shop_id'] );
        if( empty($o_settings))
            die('[!] Fatal error. Failed to get order settings.');

        $pending_orders = array();
        $pending_orders = $this->system->cart->get_shop_orders( $shop['shop_id'] , 'paid' ,  999 , 0 , 'ASC' );
        
        echo "[=] Found " . count( $pending_orders ) . " paid orders \n";

        //date in uix time
        $max_lifetime = 14 * ( 24 * 60 * 60 );

        echo "[=] Order expire time is 14 days or " . $max_lifetime . " seconds\n";


        foreach( $pending_orders as $order )
        {
            $now = time();

            echo "[=] Processing order " . $order['order_id'] . " :: Amount ( " . money( $order['total'] ) . " ) Created {$order['date_created']} Expires {$order['expire_date']} by shopper {$order['shopper_id']} ";
            
            $transaction = $this->payment->transaction->get( $order['transaction_id'] );
            if( empty($transaction) )
            {
                echo "\t Empty transaction, suggests order deleted or checkout not completed. Must delete order.\n";
            }

            //delete if old.
            $date_paid = strtotime($transaction['time_paid']);

            //delete after n-days expired, specified by user.
            if( $now > ( $date_paid + $max_lifetime ) )
            {
                echo "\t[*] Order has been paid over 14 days ago. moving to archive. \n";
                $order['status'] = 'archived';
                $this->system->cart->update_order( $order['order_id'] , $order );

                continue;
            }
            echo "\t[-] Order not yet ready to move to archive. \n";
        }

        echo "[+] Finished move to archive orders cron job at " . date('r') . "\n";

    }
    
    
}
