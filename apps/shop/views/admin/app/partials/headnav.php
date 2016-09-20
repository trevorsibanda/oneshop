
      <header class="header dark-bg non-printable" ng-controller="NavBarCtrl as vm" ng-cloak >
            <div class="toggle-nav" style="cursor: pointer;" >
                <div class="fa fa-navicon tooltips" data-original-title="Toggle Navigation" data-placement="bottom"></div>
            </div>

            <!--logo start-->
            <a href="#/dashboard" class="logo">263 <span class="lite">Shop</span></a>
            <!--logo end-->

            <div class="nav search-row" id="top_menu">
                <!--  search form start -->
                
                <!--  search form end -->                
            </div>

            <div class="top-nav notification-row"  >                
                <!-- notificatoin dropdown start-->
                <ul class="nav pull-right top-menu">
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void();">
                            <span class="profile-ava">
                                <img alt="" src="<?= admin_img('user.png'); ?>">
                            </span>
                            <span class="username" ng-bind="vm.user.fullname" ></span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li class="eborder-top">
                                <a ng-href="#/settings/users"><i class="fa fa-user"></i> My Profile</a>
                            </li>
                            
                            <?php if( has_permission('manage_products') or has_permission('blog') ): ?>
                            <li>
                                <a ng-click="vm.filemanager()"><i class="fa fa-folder"></i> File Manager</a>
                            </li>
                            <?php endif; ?>
                            <li>
                                <a href="/shop" target="_blank" ><i class="fa fa-external-link"></i> View Shop Website</a>
                            </li>
                            <li>
                                <a ng-click="vm.logout()" ><i class="fa fa-lock"></i> Log Out</a>
                            </li>
                            <li class="">
                                <a ng-click="vm.enableSupport()" ng-hide="vm.isSupportOn"><i class="fa fa-support"></i> Live Support</a>
                            </li>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                </ul>
                <!-- notificatoin dropdown end-->
            </div>
      </header>      
      <!--header end-->
