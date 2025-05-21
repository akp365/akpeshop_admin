<!DOCTYPE html>
<html class="st-layout ls-top-navbar ls-bottom-footer show-sidebar sidebar-l2" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- TITLE -->
    <title>AKP-ADMIN</title>

    <!-- THEME CSS FILES -->
    <link href="{{ URL::asset('admin_assets/css/vendor/all.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('admin_assets/css/app/app.css') }}" rel="stylesheet">

    <!-- POP UP LIBRARY CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/jquery_confirm/jquery-confirm.min.css') }}">

    <!-- SUMMERNOTE CSS -->
    <link href="{{ asset('summernote/summernote-lite.css') }}" rel="stylesheet">

    <!-- CSRF TOKEN META -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>

<body>

    <!-- Wrapper required for sidebar transitions -->
    <div class="st-container">

        <!-- Fixed navbar -->
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="#sidebar-menu" data-toggle="sidebar-menu" data-effect="st-effect-3" class="toggle pull-left visible-xs"><i class="fa fa-bars"></i></a>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="navbar-brand hidden-xs navbar-brand-primary">AKP Admin</a>
                </div>
                <div class="navbar-form navbar-left hidden-xs text-center">@yield('page_title')</div>
                <div class="navbar-collapse collapse" id="collapse">
                    <ul class="nav navbar-nav navbar-right">

                        <!-- notifications -->
                        <li class="dropdown notifications updates hidden-xs hidden-sm">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-file-video-o"></i>
                                <span class="badge badge-primary">0</span>
                            </a>
                            <ul class="dropdown-menu" role="notification">
                                <li class="dropdown-header">Notifications</li>
                                <li class="media">
                                    <div class="pull-right">
                                        <span class="label label-success">New</span>
                                    </div>
                                    <div class="media-left">
                                        <img src="" alt="people" class="img-circle" width="30">
                                    </div>
                                    <div class="media-body">
                                        <a href="#">Name</a> added <a href="">Text</a>.
                                        <br />
                                        <!-- <span class="text-caption text-muted">5 mins ago</span> -->
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- // END notifications -->


                        <!-- user -->
                        <li class="dropdown user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ URL::asset('admin_assets/images/people/110/guy-6.jpg') }}" alt="" class="img-circle" /> {{ Auth::user()->name }}<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route( 'logout' ) }}"><i class="fa fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                        <!-- // END user -->

                    </ul>
                </div>
            </div>
        </div>

        <!-- content push wrapper -->
        <div class="st-pusher">
            <!-- Sidebar component with st-effect-3 (set on the toggle button within the navbar) -->
            <div class="sidebar left sidebar-size-2 sidebar-offset-0 sidebar-skin-blue sidebar-visible-desktop" id=sidebar-menu data-type=collapse>
                <div class="split-vertical">
                    <div class="split-vertical-body">
                        <div class="split-vertical-cell">
                            <div data-scrollable>

                                <!-- DASHBOARD -->
                                {{--
                                    <ul class="sidebar-menu sm-icons-right sm-icons-block">
                                        <li class="active"><a href="#"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
                                    </ul>
                                --}}

                                <!-- SETTINGS -->
                                <ul class="sidebar-menu sm-icons-right sm-icons-block">
                                    <li class="{{ Route::currentRouteName() == 'settings' ? 'active' : ''}}"><a href="{{ route('settings') }}"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
                                </ul>

                                <!-- LOOKS -->
                                <ul class="sidebar-menu sm-icons-right sm-icons-block">
                                    <li class="{{ Route::currentRouteName() == 'looks' ? 'active' : ''}}"><a href="{{ route('looks') }}"><i class="fa fa-cog"></i> <span>Looks</span></a></li>
                                </ul>

                                <!-- FOOTER -->
                                <!-- <ul class="sidebar-menu sm-icons-right sm-icons-block">
                                    <li class="{{ Route::currentRouteName() == 'footer' ? 'active' : ''}}"><a href="{{ route('footer') }}"><i class="fa fa-cog"></i> <span>Footer</span></a></li>
                                </ul> -->

                                <!-- FOOTER -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu">
                                        <a href="#submenu-pages-footer"></i> <span>Footer</span></a>
                                        <ul id="submenu-pages-footer">
                                            <li><a href="{{ route('footer') }}"><i class="fa fa-angle-double-right"></i> <span>Links</span></a></li>
                                            <li><a href="{{ route('payment-methods') }}"><i class="fa fa-angle-double-right"></i> <span>Payment Methods</span></a></li>
                                            <li><a href="{{ route('social-networks') }}"><i class="fa fa-angle-double-right"></i> <span>Social Networks</span></a></li>
                                            <li><a href="{{ route('footer-texts') }}"><i class="fa fa-angle-double-right"></i> <span>Footer Texts</span></a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <!-- BANNER & ADD -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu">
                                        <a href="#submenu-pages-0"></i> <span>Banner & Adds</span></a>
                                        <ul id="submenu-pages-0">
                                            <li><a href="{{ route('banner-and-adds') }}"><i class="fa fa-angle-double-right"></i> <span>Top Banner</span></a></li>
                                            <li><a href="{{ route('home-slider') }}"><i class="fa fa-angle-double-right"></i> <span>Home Slider</span></a></li>
                                            <li><a href="{{ route('home-adds') }}"><i class="fa fa-angle-double-right"></i> <span>Home Adds</span></a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <!-- BANNER & ADD -->
                                {{--<ul class="sidebar-menu sm-icons-right sm-icons-block">
                                    <li class="{{ Route::currentRouteName() == 'banner-and-adds' ? 'active' : ''}}"><a href="{{ route('banner-and-adds') }}"><i class="fa fa-image"></i> <span>Banner & Add</span></a></li>
                                </ul>--}}


                                <!-- PAGE MANAGER -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu">
                                        <a href="#submenu-pages"></i> <span>Pages</span></a>
                                        <ul id="submenu-pages">
                                            <li><a href="{{ route('new-page') }}"><i class="fa fa-plus"></i> <span>Add New Page</span></a></li>
                                            <li><a href="{{ route('pages') }}"><i class="fa fa-list"></i> <span>Page List</span></a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <!-- MENU #1 -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu">
                                        <a href="#submenu-menus-1"></i> <span>Menu One</span></a>
                                        <ul id="submenu-menus-1">
                                            <li><a href="{{ route('new-menu-1') }}"><i class="fa fa-plus"></i> <span>Add New Menu</span></a></li>
                                            <li><a href="{{ route('menus-1') }}"><i class="fa fa-list"></i> <span>Menu List</span></a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <!-- MENU #2 -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu">
                                        <a href="#submenu-menus-2"></i> <span>Menu Two</span></a>
                                        <ul id="submenu-menus-2">
                                            <li><a href="{{ route('new-menu-2') }}"><i class="fa fa-plus"></i> <span>Add New Menu</span></a></li>
                                            <li><a href="{{ route('menus-2') }}"><i class="fa fa-list"></i> <span>Menu List</span></a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <!-- COUPONS -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu">
                                        <a href="#submenu-coupons"></i> <span>Coupons</span></a>
                                        <ul id="submenu-coupons">
                                            <li><a href="{{ route('add-new-coupon') }}"><i class="fa fa-plus"></i> <span>Add New Coupon</span></a></li>
                                            <li><a href="{{ route('coupons') }}"><i class="fa fa-list"></i> <span>Coupon List</span></a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <!-- CATEGORIES -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu">
                                        <a href="#submenu-categories"></i> <span>Categories</span></a>
                                        <ul id="submenu-categories">
                                            <li><a href="{{ route('add-new-category') }}"><i class="fa fa-plus"></i> <span>Add New Category</span></a></li>
                                            <li><a href="{{ route('categories') }}"><i class="fa fa-list"></i> <span>Category List</span></a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <!-- CURRENCIES -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu">
                                        <a href="#submenu-currencies"></i> <span>Currencies</span></a>
                                        <ul id="submenu-currencies">
                                            <li><a href="{{ route('add-new-currency') }}"><i class="fa fa-plus"></i> <span>Add New Currency</span></a></li>
                                            <li><a href="{{ route('currencies') }}"><i class="fa fa-list"></i> <span>Currency List</span></a></li>
                                        </ul>
                                    </li>
                                </ul>


                                <!-- VENDORS -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu {{ in_array(Route::currentRouteName(), array( 'pre-approval', 'final-approval', 'seller-list')) ? 'open' : ''}}">
                                        <a href="#submenu-vendors" aria-expanded="{{ in_array(Route::currentRouteName(), array( 'pre-approval', 'final-approval', 'seller-list')) ? 'true' : 'false' }}"></i> <span>Vendors</span></a>
                                        <ul id="submenu-vendors" aria-expanded="{{ in_array(Route::currentRouteName(), array( 'pre-approval', 'final-approval', 'seller-list')) ? 'true' : 'false' }}" class="{{ in_array(Route::currentRouteName(), array( 'pre-approval', 'final-approval', 'seller-list')) ? 'in' : '' }}">
                                            <li class="{{ Route::currentRouteName() == 'pre-approval' ? 'active' : ''}}"><a href="{{ route('pre-approval') }}"><i class="fa fa-list"></i> <span>Pre Approval</span></a></li>
                                            <li class="{{ Route::currentRouteName() == 'final-approval' ? 'active' : ''}}"><a href="{{ route('final-approval') }}"><i class="fa fa-list"></i> <span>Final Approval</span></a></li>
                                            <li class="{{ Route::currentRouteName() == 'seller-list' ? 'active' : ''}}"><a href="{{ route('seller-list') }}"><i class="fa fa-list"></i> <span>Seller Information</span></a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <!-- CHANGE REQUESTS -->
                                <ul class="sidebar-menu sm-icons-right sm-icons-block">
                                    <li class="{{ Route::currentRouteName() == 'change-requests' ? 'active' : ''}}"><a href="{{ route('change-requests') }}"><i class="fa fa-cog"></i> <span>Change Requests</span></a></li>
                                </ul>


                                <!-- PRODUCTS -->
                                <ul class="sidebar-menu sm-bordered sm-active-item-bg">
                                    <li class="hasSubmenu {{ in_array(Route::currentRouteName(), array( 'pending-products', 'product-list', 'product-details' )) ? 'open' : ''}}">
                                        <a href="#submenu-products" aria-expanded="{{ in_array(Route::currentRouteName(), array( 'pending-products', 'product-list', 'product-details')) ? 'true' : 'false' }}"></i> <span>Products</span></a>
                                        <ul id="submenu-products" aria-expanded="{{ in_array(Route::currentRouteName(), array( 'pre-approval', 'final-approval', 'seller-list')) ? 'true' : 'false' }}" class="{{ in_array(Route::currentRouteName(), array( 'pending-products', 'product-list', 'product-details')) ? 'in' : '' }}">
                                            <li class="{{ Route::currentRouteName() == 'pending-products' ? 'active' : ''}}"><a href="{{ route('pending-products') }}"><i class="fa fa-list"></i> <span>Approval</span></a></li>
                                            <li class="{{ in_array( Route::currentRouteName(), array( 'product-details' , 'product-list' ) ) ? 'active' : ''}}"><a href="{{ route('product-list') }}"><i class="fa fa-list"></i> <span>Product List</span></a></li>
                                            <li class="{{ Route::currentRouteName() == 'reported-products' ? 'active' : ''}}"><a href="{{ route('reported-products') }}"><i class="fa fa-warning"></i> <span>Reported Products</span></a></li>
                                        </ul>
                                    </li>
                                </ul>

                                <!-- ORDERS -->
                                <ul class="sidebar-menu sm-icons-right sm-icons-block">
                                    <li class="{{ Route::currentRouteName() == 'payment-options' ? 'active' : ''}}"><a href="{{ route('order.view') }}"><i class="fa fa-cog"></i> <span>Orders</span></a></li>
                                </ul>

                                <!-- Q-&-A ABUSE -->
                                <ul class="sidebar-menu sm-icons-right sm-icons-block">
                                    <li class="{{ Route::currentRouteName() == 'abusive-questions' ? 'active' : ''}}"><a href="{{ route('abusive-questions') }}"><i class="fa fa-warning"></i> <span>Abusive Questions</span></a></li>
                                </ul>


                                <!-- GEO LOCATIONS -->
                                <ul class="sidebar-menu sm-icons-right sm-icons-block">
                                    <li class="{{ Route::currentRouteName() == 'countries' ? 'active' : ''}}"><a href="{{ route('countries') }}"><i class="fa fa-cog"></i> <span>Geo Locations</span></a></li>
                                </ul>

                                <!-- PAYMENTS -->
                                <ul class="sidebar-menu sm-icons-right sm-icons-block">
                                    <li class="{{ Route::currentRouteName() == 'payment-options' ? 'active' : ''}}"><a href="{{ route('payment-options') }}"><i class="fa fa-cog"></i> <span>Payment Options</span></a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- // END .split-vertical-cell -->
                    </div>
                    <!-- // END .split-vertical-body -->
                </div>
            </div>

            <div class="st-content" id="content">
                <div class="st-content-inner">
                    <div class="container-fluid">
                        <!-- ALERT SECTION -->
                        <div class="row" style="text-align:center;">
                            <!-- SHOW VALIDATION ERRORS IF ANY -->
                            @if(count($errors))
                            <div class="form-group">
                                <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                                </div>
                            </div>
                            @endif

                            @if (Session::has('message'))
                            <div class="alert alert-success">{{ Session::get('message') }}</div>
                            @endif
                        </div>

                        @yield('content')
                    </div>
                    <!-- /container-fluid -->
                </div>
                <!-- /st-content-inner -->
            </div>
            <!-- /st-content -->
            <!-- Footer -->
            <footer class="footer">
                <strong>AKP Admin</strong> v1.0.0 &copy; Copyright {{ date('Y') }}
            </footer>
            <!-- // Footer -->

        </div>
        <!-- /st-container -->


        <!-- Inline Script for colors and config objects; used by various external scripts; -->
        <script>
            var colors = {
                "danger-color": "#e74c3c",
                "success-color": "#81b53e",
                "warning-color": "#f0ad4e",
                "inverse-color": "#2c3e50",
                "info-color": "#2d7cb5",
                "default-color": "#6e7882",
                "default-light-color": "#cfd9db",
                "purple-color": "#9D8AC7",
                "mustard-color": "#d4d171",
                "lightred-color": "#e15258",
                "body-bg": "#f6f6f6"
            };
            var config = {
                theme: "admin",
                skins: {
                    "default": {
                        "primary-color": "#3498db"
                    }
                }
            };
        </script>
        <script src="{{ URL::asset('admin_assets/js/vendor/all.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/app/app.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.extensions.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.services.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.microdata.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.microformat.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.overlays.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.rdfa.js') }}"></script>

        <!-- POP UP LIBRARY JS -->
        <script src="{{ asset('admin_assets/jquery_confirm/jquery-confirm.min.js') }}"></script>

        <!-- JQUERY FORM VALIDATION -->
        <script src="{{ asset('admin_assets/jquery_validate/jquery.validate.min.js') }}"></script>

        <!-- SUMMERNOTE JS -->
        <script src="{{ asset('summernote/summernote-lite.js') }}"></script>

        <!-- PRINT PLUGIN -->
        <script src="{{ asset('admin_assets/js/printThis.js') }}"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        @yield('scripts')
</body>

</html>
