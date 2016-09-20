<?php
        $this->load->theme($theme , 'partials/head' , array('page' => $page ) );
        $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) );
?>
<div class="content">
  <div class="content_box">
	<div class="men">
	 <h1 class="contact_head">Our Address</h1>
	 <div class="contact_box">
	  <div class="col-sm-4">
	  	 <address class="addr">
              <p>
                <?=  nl2br( $shop['address'] ) ?>
              <br/>
			  <?= $shop['city'] ?>
			  </p>
			  <dl>
                <dt>Phone Number:</dt>
                <dd><?= $settings['contact_phone'] ?></dd>
              </dl>
              <dl>
                <dt>Telephone:</dt>
                <dd><?= $shop['telephone'] ?></dd>
              </dl>
              <dl>
                <dt>WhatsApp:</dt>
                <dd><?= $settings['contact_whatsapp'] ?></dd>
              </dl>
              <p>E-mail:
                <a href="#"><?= 'Click to See' ?></a>
              </p>
            </address>
	     </div>
	     <div class="col-sm-4">
	  	 <address class="addr">
              
              <dl>
                <dt>About Us:</dt>
                <dd><p><?= nl2br($shop['description']) ?></p></dd>
              </dl>
              
            </address>
	     </div>
	     <div class="col-sm-4">
	  	 <address class="addr">
              <dl>
                <dt>Shop Now:</dt>
                <dd><?=  is_shop_open( $shop['operate_days'] , $shop['open_time'] , $shop['close_time'] ) ? "<b style='color: green;'>Open</b>" : "<b style='color: red;'>Closed</b>" ?></dd>
              </dl>
             <dl>
                <dt>Open Time:</dt>
                <dd><?= $shop['open_time'] ?></dd>
              </dl>
              <dl>
                <dt>Close Time:</dt>
                <dd><?=  $shop['close_time'] ?></dd>
              </dl>
			  <dl>
                <dt>Operating days:</dt>
                <dd><?= shop_operating_days( $shop['operate_days'] ) ?></dd>
              </dl>
              
			  
              
            </address>
	     </div>
	     <div class="clearfix"> </div>
	   </div>
	   <div class="contact_form">
	   	  <h2>Contact Form</h2>
	           <form>
					<div class="row_5">
                     	<input type="text" class="text" value="Name" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Name';}">
					 	<input type="text" class="text" value="Email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}" style="margin-left:20px">
					 	<input type="text" class="text" value="Subject" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Subject';}" style="margin-left:20px">
					 	<div class="clearfix"></div>
					</div>
					<div class="row_6">
	                   <textarea value="Message:" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Message';}">Message:</textarea>
	                </div>
	                <input name="submit" type="submit" id="submit" value="Send Message">
			        <div class="clearfix"></div>
               </form>
       </div>
    </div>
<?php
  $data = array();
  $data['featured_products'] = $featured_products;
  $data['shop'] = $shop; 
  $this->load->theme( $theme , 'partials/footer' , $data );
?>  	