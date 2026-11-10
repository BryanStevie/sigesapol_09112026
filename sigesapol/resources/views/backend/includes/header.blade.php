          <!-- Main Header -->
          <header class="main-header">

            <!-- Logo -->
            <a href="{!!route('home')!!}" class="logo"><b>Gestión</b> Farmacia</a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
              <!-- Sidebar toggle button-->
              <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">{{ trans('labels.toggle_navigation') }}</span>
              </a>
              <!-- Navbar Right Menu -->
              <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                  

                  <!-- Messages: style can be found in dropdown.less-->
                  

                  <!-- Notifications Menu -->
                  
                  <!-- Tasks Menu -->
                  
                  <!-- User Account Menu -->
                  <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <!-- The user image in the navbar-->
                      <img src="{!! access()->user()->picture !!}" class="user-image" alt="User Image"/>
                      <!-- hidden-xs hides the username on small devices so only the image appears. -->
                      <span class="hidden-xs">{{ access()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                      <!-- The user image in the menu -->
                      <li class="user-header">
                        <img src="{!! access()->user()->picture !!}" class="img-circle" alt="User Image" />
                        <p>
                          {{ access()->user()->name }} - {{ trans('roles.web_developer') }}
                          
                        </p>
                      </li>
                      <!-- Menu Body -->
                    
                      <!-- Menu Footer-->
                      <li class="user-footer">

                        <div class="pull-right">
                          <a href="{!!url('auth/logout')!!}" class="btn btn-default btn-flat">{{ trans('navs.logout') }}</a>
                        </div>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
            </nav>
          </header>
