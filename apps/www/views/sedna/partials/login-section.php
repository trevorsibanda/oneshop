<section class="sign-up section-padding text-center" id="login">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h3>Already have an account ?</h3>
                    <p>Enter your details to login to your shop</p>
                    <form class="signup-form" action="/action/login" method="POST" role="form">
                        <div class="form-input-group">
                            <i class="fa fa-envelope"></i><input type="email" class="" placeholder="Enter your email" name="user_email" required>
                        </div>
                        <div class="form-input-group">
                            <i class="fa fa-lock"></i><input type="password" class="" placeholder="Enter your password" name="user_password" required>
                        </div>
                        <input type="hidden" name="csrf_token" value="<?= $csrf_login_token ?>" />
                        <button type="submit" class="btn-fill sign-up-btn">Login</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </section>