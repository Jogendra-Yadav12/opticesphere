<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from smartshop.websitelayout.net/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 09 Jan 2024 11:18:49 GMT -->
<head>
    <!-- metas -->
    <meta charset="utf-8" />
    <meta name="author" content="Chitrakoot Web" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="description" content="Smartshop - Responsive eCommerce Admin Dashboard" />

    <!-- title  -->
    <title>Admin Panel</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon.ico') }}" />

    <!-- base styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />

    <!-- sidebar icon font -->
    <link rel="stylesheet" href="{{ asset('_admin/plugins/icomoon/style.css') }}" />

    <!-- theme core css -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />

</head>

<body>

    <!-- PAGE LOADING
    ================================================== -->
    <div id="preloader"></div>

    @yield('content')

                <div class="page-footer">
                    <p>Copyright &copy; <span class="current-year"></span> Smartshop All rights reserved.</p>
                </div>
            </div>

            <!-- PAGE MAIN RIGHT SIDEBAR
            ================================================== -->
            <div class="page-right-sidebar" id="main-right-sidebar">
                <div class="page-right-sidebar-inner">
                    <div class="right-sidebar-top">
                        <div class="right-sidebar-tabs">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active" id="chat-tab"><a href="#chat" aria-controls="chat" role="tab" data-bs-toggle="tab">chat</a></li>
                                <li role="presentation" id="settings-tab"><a href="#settings" aria-controls="settings" role="tab" data-bs-toggle="tab">settings</a></li>
                            </ul>
                        </div>
                        <a href="#" class="right-sidebar-toggle right-sidebar-close" data-sidebar-id="main-right-sidebar"><i class="icon-close"></i></a>
                    </div>
                    <div class="right-sidebar-content">
                        <!-- start tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="chat">
                                <div class="chat-list">
                                    <span class="chat-title">Recent</span>
                                    <a href="#" class="right-sidebar-toggle chat-item unread" data-sidebar-id="chat-right-sidebar">
                                        <div class="user-avatar">
                                            <img src="{{ asset('img/avatars/chat01.jpg') }}" alt="...">
                                        </div>
                                        <div class="chat-info">
                                            <span class="chat-author">David</span>
                                            <span class="chat-text">where u at?</span>
                                            <span class="chat-time">08:50</span>
                                        </div>
                                    </a>
                                    <a href="#" class="right-sidebar-toggle chat-item unread active-user" data-sidebar-id="chat-right-sidebar">
                                        <div class="user-avatar">
                                            <img src="{{ asset('img/avatars/chat02.jpg') }}" alt="...">
                                        </div>
                                        <div class="chat-info">
                                            <span class="chat-author">Daisy</span>
                                            <span class="chat-text">Daisy sent a photo.</span>
                                            <span class="chat-time">11:34</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="chat-list">
                                    <span class="chat-title">Older</span>
                                    <a href="#" class="right-sidebar-toggle chat-item" data-sidebar-id="chat-right-sidebar">
                                        <div class="user-avatar">
                                            <img src="{{ asset('img/avatars/chat03.jpg') }}" alt="...">
                                        </div>
                                        <div class="chat-info">
                                            <span class="chat-author">Tom</span>
                                            <span class="chat-text">You: ok</span>
                                            <span class="chat-time">2d</span>
                                        </div>
                                    </a>
                                    <a href="#" class="right-sidebar-toggle chat-item active-user" data-sidebar-id="chat-right-sidebar">
                                        <div class="user-avatar">
                                            <img src="{{ asset('img/avatars/chat04.jpg') }}" alt="...">
                                        </div>
                                        <div class="chat-info">
                                            <span class="chat-author">Anna</span>
                                            <span class="chat-text">asdasdasd</span>
                                            <span class="chat-time">4d</span>
                                        </div>
                                    </a>
                                    <a href="#" class="right-sidebar-toggle chat-item active-user" data-sidebar-id="chat-right-sidebar">
                                        <div class="user-avatar">
                                            <img src="{{ asset('img/avatars/chat05.jpg') }}" alt="...">
                                        </div>
                                        <div class="chat-info">
                                            <span class="chat-author">Liza</span>
                                            <span class="chat-text">asdasdasd</span>
                                            <span class="chat-time">&nbsp;</span>
                                        </div>
                                    </a>
                                    <a href="#" class="load-more-messages" data-bs-toggle="tooltip" data-placement="bottom" title="Load More">&bull;&bull;&bull;</a>
                                </div>
                            </div>
                            <div class="tab-pane" id="settings">
                                <div class="right-sidebar-settings">
                                    <span class="settings-title">General Settings</span>
                                    <ul class="sidebar-setting-list list-unstyled">
                                        <li>
                                            <span class="settings-option">Notifications</span>
                                            <input type="checkbox" class="js-switch" checked />
                                        </li>
                                        <li>
                                            <span class="settings-option">Activity log</span>
                                            <input type="checkbox" class="js-switch" checked />
                                        </li>
                                        <li>
                                            <span class="settings-option">Automatic updates</span>
                                            <input type="checkbox" class="js-switch" />
                                        </li>
                                        <li>
                                            <span class="settings-option">Allow backups</span>
                                            <input type="checkbox" class="js-switch" />
                                        </li>
                                    </ul>
                                    <span class="settings-title">Account Settings</span>
                                    <ul class="sidebar-setting-list list-unstyled">
                                        <li>
                                            <span class="settings-option">Chat</span>
                                            <input type="checkbox" class="js-switch" checked />
                                        </li>
                                        <li>
                                            <span class="settings-option">Incognito mode</span>
                                            <input type="checkbox" class="js-switch" />
                                        </li>
                                        <li>
                                            <span class="settings-option">Public profile</span>
                                            <input type="checkbox" class="js-switch" />
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE CHAT RIGHT SIDEBAR
            ================================================== -->
            <div class="page-right-sidebar" id="chat-right-sidebar">
                <div class="page-right-sidebar-inner">
                    <div class="right-sidebar-top">
                        <div class="chat-top-info">
                            <span class="chat-name">Noah</span>
                            <span class="chat-state">2h ago</span>
                        </div>
                        <a href="#" class="right-sidebar-toggle chat-sidebar-close float-end" data-sidebar-id="chat-right-sidebar"><i class="icon-keyboard_arrow_right"></i></a>
                    </div>
                    <div class="right-sidebar-content">
                        <div class="right-sidebar-chat slimscroll">
                            <div class="chat-bubbles">
                                <div class="chat-start-date">02/03/2019 5:58PM</div>
                                <div class="chat-bubble them">
                                    <div class="chat-bubble-img-container">
                                        <img src="{{ asset('img/avatars/chat06.jpg') }}" alt="...">
                                    </div>
                                    <div class="chat-bubble-text-container">
                                        <span class="chat-bubble-text">Hello</span>
                                    </div>
                                </div>
                                <div class="chat-bubble me">
                                    <div class="chat-bubble-text-container">
                                        <span class="chat-bubble-text">Hello!</span>
                                    </div>
                                </div>
                                <div class="chat-start-date">03/02/2019 5:18AM</div>
                                <div class="chat-bubble me">
                                    <div class="chat-bubble-text-container">
                                        <span class="chat-bubble-text">lorem</span>
                                    </div>
                                </div>
                                <div class="chat-bubble them">
                                    <div class="chat-bubble-img-container">
                                        <img src="{{ asset('img/avatars/chat07.jpg') }}" alt="...">
                                    </div>
                                    <div class="chat-bubble-text-container">
                                        <span class="chat-bubble-text">ipsum dolor sit amet</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-write">
                            <form class="form-horizontal" action="#">
                                <input type="text" class="form-control" placeholder="Say something">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- BUY TEMPLATE
    ================================================== -->
    <div class="buy-theme alt-font d-none d-lg-inline-block"><a href="https://wrapbootstrap.com/theme/smartshop-multipurpose-ecommerce-template-WB0N7CT4D" target="_blank"><i class="fas fa-cart-plus"></i><span>Buy Template</span></a></div>

    <div class="all-demo alt-font d-none d-lg-inline-block"><a href="https://www.chitrakootweb.com/contact.html" target="_blank"><i class="far fa-envelope"></i><span>Quick Question?</span></a></div>
    
    <!-- SCROLL TO TOP
    ================================================== -->
    <a href="#" class="scroll-to-top"><i class="fas fa-angle-up" aria-hidden="true"></i></a>

    <!-- ALL JS INCLUDE
    ================================================== -->

    <!-- bootstrap bundle + minimal admin scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>

    <!-- all js include end -->
    @yield('scripts')
</body>


<!-- Mirrored from smartshop.websitelayout.net/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 09 Jan 2024 11:19:01 GMT -->
</html>