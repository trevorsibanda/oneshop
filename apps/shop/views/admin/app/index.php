<?php
    $_page = array('page' => array('title' => ' Dashboard ' , 'description' => OS_SITE_NAME .' Admin Dashboard')  );
    $this->load->view('admin/app/partials/head' , $_page );
?>
  
  <body  >
  <!-- container section start -->
  <section id="container" class="">
     
      <!-- NavBar -->
      <?php $this->load->view('admin/app/partials/headnav'); ?>
      <!-- End of NavBar -->

      <!--sidebar start-->
      <?php $this->load->view('admin/app/partials/sidebar'); ?>
      <!--sidebar end-->
      
      <!--main content start-->
      <section id="main-content"  ng-controller="MainContentCtrl as vm" >
            <section class="wrapper">
                
                <!-- Start of notifications -->
                <div class="row" ng-show="vm.alerts.length" >
                    <div class="col-md-12" >
                        <div class="text-right"  ><a ng-click="vm.clearAlerts()" ><i class="fa fa-remove"></i> Clear notifications</a><br/></div>
                        <alert ng-repeat="alert in vm.alerts" type="{{alert.type}}" close="vm.closeAlert($index)"  >
                            {{ alert.msg }}
                        </alert>
                    </div>
                </div>
                <!-- End of Notifications -->
                <!-- Start of View -->
                <div ng-view ng-cloak ng-if="vm.isLoggedIn"></div>
                <!-- End of View -->
                <form ng-if="! vm.isLoggedIn "  class="login-form" style="margin-top: 0px;" onsubmit="return false;" method="post" ng-cloak >        
                  <div class="login-wrap">
                      <p class="login-img"><i class="icon_lock_alt"></i></p>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="icon_profile"></i></span>
                        <input type="email" class="form-control" ng-model="vm.login.email" ng-readonly="vm.login.processing" placeholder="Email address" autofocus>
                      </div>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                          <input type="password" ng-model="vm.login.password" class="form-control" placeholder="Password">
                      </div>
                      <span ng-if="vm.login.processing" class="text-center" >Authenticating....</span>
                      <button class="btn btn-primary btn-lg btn-block" ng-click="vm.submitLogin()" ng-disabled="vm.login.processing">Login</button>
                      <a href="/" class="btn btn-default btn-lg btn-block">Back to shop</a>
                  </div>
                </form>
            </section>
      </section>
      <!--main content end-->
  </section>
  <!-- container section end -->
   <!-- Start Modals -->
  <script type="text/ng-template" id="prompt.html">
  <?php $this->load->view('admin/app/partials/dialog'); ?>
  </script>
  <!-- End Modals -->
  <!-- Start Filemanager -->
  <?php 
    if( has_permission('manage_products') or has_permission('blog') )
        $this->load->view('admin/app/partials/filemanager'); 
  ?>
  <!-- End filemanager -->
 
  <!-- Start JS Section -->
  <?php $this->load->view('admin/app/partials/javascript'); ?>
  <!-- End JS Section -->
  </body>
</html>
