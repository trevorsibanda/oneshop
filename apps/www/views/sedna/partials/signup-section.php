<div class="morph-button morph-signup morph-button-overlay morph-button-fixed">
					<button type="button" id="modal-btn" class="build-store-btn" >Build your shop</button>
					<div class="morph-content">
						<div>
						
							<div class="content-style-overlay">
								<div class="row" style="left: 10px; top: 0px; z-index: 99999; position: fixed; background-color: #000; " >
									<div class="col-md-12" >
										<img src="<?= public_resource('www/sedna/img/logo.png'); ?>" class="img-responsive" style="max-width: 128px; max-height: 128px; " /> 	
									</div>
								</div>
								<div class="row" style="right: 7%; top: 0px; z-index: 99999; position: fixed; position: fixed;">
									<span class="icon-close" style="width: 32px; "	 ><h3 ><a href="javascript:;" >&times;</a></h3></span>
								</div>
								
								<div class="row" >
									
									
									<div class="col-md-7 visible-md visible-lg" style="position: fixed; right: 0px;" >
										<img src="<?=  public_resource('www/sedna/img/macbook-pro.png');  ?>" class="img-responsive" style="width: 100%;" />
										<p style="color: #FFF; font-weight: bold;"><small>You can change your theme, shop url and select your plan once you've verfied your email address</small></p>
									</div>
									<div class="col-md-5" >
										<form method="POST" action="/action/signup" >
										<input type="hidden" name="csrf_token" value="<?= $csrf_signup_token ?>" />
										<input type="hidden" name="selected_plan" id="selected_plan" value="" />
										<span class="input input--isao">
											<input class="input__field input__field--isao" id="input-38" type="text" placeholder="Your shop's name" name="shop_name" required />
											<label class="input__label input__label--isao" for="input-38" data-content="Your shop Name">
												<span class="input__label-content input__label-content--isao">Shop name</span>
											</label>
											
										</span>
										<span class="input input--isao">
											<input class="input__field input__field--isao" id="input-38" type="text" placeholder="Your fullname" name="admin_fullname" required/>
											<label class="input__label input__label--isao" for="input-38" data-content="Shop Admin's Fullname">
												<span class="input__label-content input__label-content--isao">Your fullname</span>
											</label>
											
										</span>
										<span class="input input--isao">
											<input class="input__field input__field--isao" id="input-38" type="email" placeholder="Your email address" name="admin_email" required />
											<label class="input__label input__label--isao" for="input-38" data-content="Admin Email">
												<span class="input__label-content input__label-content--isao">Your Email</span>
											</label>
											
										</span>
										<span class="input input--isao">
											<input class="input__field input__field--isao" id="input-38" type="text" placeholder="Your phone number +26377 " value="+2637" name="admin_phone" required pattern="\+2637[0-9]+" />
											<label class="input__label input__label--isao" for="input-38" data-content="Admin Phone number">
												<span class="input__label-content input__label-content--isao">Your Phone number</span>
											
											</label>
										</span>	
										<span class="input input--isao">
											<input class="input__field input__field--isao" id="input-38" type="password" placeholder="Your password" name="admin_password" required  />
											<label class="input__label input__label--isao" for="input-38" data-content="Admin Password">
												<span class="input__label-content input__label-content--isao">Your Password</span>
											
											</label>
											<br/>
											
											<button class="btn btn-lg btn-custom btn-block" style="color: #555; font-weight: bold;" type="submit" >Signup</button>
											<small style="color: #fff;" >By clicking on the button below, you agree to the <a href="/home/terms_and_conditions" target="_blank" >Terms and Conditions</a></small>
											
										</span>
										</form>
										<br/>
										
										
				
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><!-- morph-button -->