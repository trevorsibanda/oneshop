      <aside>
          <div id="sidebar" ng-controller='SideBarCtrl as vm '  class="nav-collapse " ng-cloak >
              <!-- sidebar menu start-->
              <ul class="sidebar-menu">                
                  <li class="active">
                      <a class="" ng-href="#/dashboard">
                          <i class="fa fa-home"></i>
                          <span class="li-text">{{ vm.summary.shop.name }}</span>
                      </a>
                  </li>
                  <?php if( account_can('pos') and has_permission('pos') ): ?>
                  <li >
                    <a ng-href="#/point-of-sale">
                        <span class="fa fa-calculator"></span>
                        <span class="li-text">Point of Sale</span>
                    </a>
                   </li> 
                 <?php endif; ?>
				          <li class="sub-menu">
                      <a href="javascript:;" class="">
                          <i class="fa fa-shopping-cart"></i>
                          <span class="li-text">Orders</span>
                          <span class="li-text badge pull-right" ng-if="vm.summary.pending_orders" style="margin-right: 5px;" >{{ vm.summary.pending_orders }}</span>
                      </a>
                      <ul class="sub">
                          <li><a class="" ng-href="#/orders/browse"><span class="fa fa-archive" ></span> Browse</a></li>
                          <li><a class="" ng-href="#/orders/shipping" ><span class="fa fa-truck" ></span> Shipping <span class="li-text badge pull-right" style="margin-right: 5px;" ng-if="vm.summary.stock_count" >{{ vm.summary.shipping_orders }}</span></a></li>
                          <li><a class="" ng-href="#/orders/support"><span class="fa fa-support"></span> Customer Support </a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" class="">
                          <i class="fa fa-cubes"></i>
                          <span class="li-text">Products </span>
                          
                      </a>
                      <ul class="sub">
                          <li><a ng-href="#/products/browse"><i class="fa fa-list" ></i> Browse</a></li>
                          <li><a class="" ng-href="#/products/categories"><span class="fa fa-sitemap"></span> Categories</a></li>                          
                          <?php if( has_permission('manage_products') ): ?>
                          <li><a ng-href="#/products/stock-manager"><span class="fa fa-cubes"></span> Stock Manager<span class="li-text badge pull-right" style="margin-right: 5px;" ng-if="vm.summary.stock_count" >{{ vm.summary.stock_count }}</span></a></li>
                          <li><a ng-href="#/products/add" ><span class="fa fa-plus"></span> Add New Product</a></li>
                          
                          <?php endif; ?>
                      </ul>
                  </li> 
                  <?php if( account_can('analytics') and has_permission('manage_orders')): ?>
                  <li  >
                    <a ng-href="#/reports">
                        <span class="fa fa-bar-chart" title="Reports"></span>
                        <span class="li-text"  >Reports</span>
                    </a>
                   </li>     
                  <?php endif; ?>  
                  <?php if( has_permission('designer') ): ?>  
                  <li  >
                    <a ng-href="#/designer/theme">
                        <i class="fa fa-paint-brush"></i>
                        <span class="li-text">Shop Designer</span>
                    </a>
                  </li>
                  <?php endif; ?> 
                  <?php if( has_permission('blog') ): ?>    
                  <li class="sub-menu">
                      <a href="javascript:;" class="">
                          <i class="fa fa-pencil-square-o"></i>
                          <span class="li-text">Blog</span>
                          <span class="menu-arrow arrow_carrot-right"></span>
                      </a>
                      <ul class="sub">
                          <li><a class="" ng-href="#/blog/browse"><span class="fa fa-list"></span> Browse articles</a></li>
                          <li><a class="" ng-href="#/blog/write"><span class="fa fa-pencil-square"></span> Write new article</a></li>
                          
                      </ul>
                  </li>
                  <?php endif; ?>
                  <li class="sub-menu" >
                      <a href="javascript:;" class="" >
                            <i class="fa fa-wrench" ></i>
                            <span class="li-text">Settings</span>
                            
                      </a>
                      <ul class="sub" >
                            <?php if( is_admin() ): ?>
                            <li><a class="" ng-href="#/settings/general" ><span class="fa fa-asterisk"></span> General</a></li>
                            <li><a class="" ng-href="#/settings/account" ><span class="fa fa-laptop"></span> My Account</a></li>
                            <?php endif; ?>
                            <li><a ng-href="#/settings/users" ><span class="fa fa-users"></span> Users</a></li>
                            <?php if( is_admin() ): ?>
                            <li><a ng-href="#/settings/payments"><span class="fa fa-credit-card"></span> Payment</a></li>
                            <?php if( account_can('analytics') ): ?>
                            <li><a ng-href="#/settings/analytics" ><span class="fa fa-line-chart"></span> Stats and Analytics</a></li>
                            <?php endif; ?>
                            <li><a ng-href="#/settings/orders"><span class="fa fa-truck"></span> Orders and shipping</a></li>
                            <!-- @todo activate activity log functionality
                            <li><a ng-href="#/settings/activity_log"><span class="fa fa-clock-o"></span> Activity log</a></li>
                            -->
                            <?php endif; ?>
                      </ul>

                  </li>

                  
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>