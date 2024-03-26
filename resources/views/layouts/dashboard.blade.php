<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Bus Pricing</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!--  Favicon -->
    <!--<link rel="shortcut icon" type="image/png" href="{{ asset('public') }}/dist/images/logos/favicon.ico" />-->
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="{{ asset('public') }}/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/dist/libs/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/dist/libs/daterangepicker/daterangepicker.css">
    @yield('css')
    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="{{ asset('public') }}/dist/css/style.min.css" />
</head>
<style>
    /* Hide the spinner arrows in number input */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
        /* Firefox */
    }


    header {
        background-color: #990B0C !important;
    }

    a:hover {
        color: inherit !important;
    }

    .nav-icon-hover:hover::before {
        background-color: transparent !important;
    }

    .menu-icon {
        color: #fff !important;
    }

    .app-header .navbar {
        min-height: 60px !important;
        height: 60px !important;
        padding: 0;
    }

    #main-wrapper[data-layout=vertical] .app-header.fixed-header .navbar {
        background: inherit !important;
        padding: 0 !important;
        border-radius: inherit !important;
        box-shadow: none !important;
        margin-top: inherit !important;
    }

    #main-wrapper[data-layout=vertical] .app-header.fixed-header .notification {
        top: 22px !important;
    }

    .color-primary {
        color: #990B0C;
    }

    .btn-submit {
        margin: 2px;
        border-radius: 0px;
        background-image: linear-gradient(161deg, #990B0C 82%, #990B0C 100%);
        color: #fff;
        padding-right: 20px;
        padding-left: 20px;
    }

    .btn-success {
        margin: 2px;
        border-radius: 0px;
        background-image: linear-gradient(161deg, #0B996D 82%, #0B996D 100%);
        color: #fff;
        padding-right: 20px;
        padding-left: 20px;
    }

    .sidebar-nav ul .sidebar-item.selected>.sidebar-link,
    .sidebar-nav ul .sidebar-item.selected>.sidebar-link.active,
    .sidebar-nav ul .sidebar-item>.sidebar-link.active {
        background-color: #F6F9FC !important;
        color: inherit !important;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link:hover {
        background-color: #F6F9FC !important;
        color: inherit !important;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link:hover.has-arrow::after {
        border-color: inherit !important;
    }

    .btn:hover {
        color: #fff !important;
        border-color: none !important;
    }

    .card {
        border-radius: 0px !important;
    }
    .drop-down-icon{
            color: #ffffff !important;
        }
    @media (max-width: 992px){
        .drop-down-icon{
            color: #000000 !important;
        }
    }

    @media (max-width: 991.98px){
        .navbar-collapse {
    margin-top: -75px !important;
}
    }
</style>

<body class="bg-light">
    <!-- Preloader -->
    <div class="preloader">
        {{-- <img src="{{ asset('public') }}/dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" /> --}}
    </div>
    <!-- Preloader -->
    <div class="preloader">
        {{-- <img src="{{ asset('public') }}/dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" /> --}}
    </div>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-center">
                    <a href="{{ url('/home') }}" class="text-nowrap logo-img">
                        <img src="{{ asset('public') }}/dist/images/backgrounds/logo.png" class="dark-logo"
                            width="180" alt="" />
                        <img src="{{ asset('public') }}/dist/images/backgrounds/logo.png" class="light-logo"
                            width="180" alt="" />
                    </a>
                    {{-- <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8 text-muted"></i>
                    </div> --}}
                    {{-- <h3 class="dark-logo text-dark fw-bolder">Hi Technician</h3>
                    <h3 class="light-logo text-white fw-bolder">Hi Technician</h3> --}}
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            {{-- <span class="hide-menu">Applications</span> --}}
                        </li>
                        <!-- ============================= -->
                        <!-- Home -->
                        <!-- ============================= -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ url('/home') }}" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-layout-dashboard" style="color: #990B0C;"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item has-arrow">
                            <a class="sidebar-link" href="{{ url('/Buspreiskalkulation') }}" aria-expanded="false">
                                <span class="d-flex">
                                    {{-- <i class="ti ti-users"></i> --}}
                                </span>
                                <span class="hide-menu">Preiskalkulation</span>
                            </a>
                        </li>
                        <li class="sidebar-item has-arrow">
                            <a class="sidebar-link" href="{{ url('/Saison') }}" aria-expanded="false">
                                <span class="d-flex">
                                    {{-- <i class="ti ti-users"></i> --}}
                                </span>
                                <span class="hide-menu">Saison</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-arrow">
                            <a class="sidebar-link" href="{{ url('/Bundesland') }}" aria-expanded="false">
                                <span class="d-flex">
                                    {{-- <i class="ti ti-users"></i> --}}
                                </span>
                                <span class="hide-menu">Bundesländer</span>
                            </a>
                        </li>
                        <li class="sidebar-item has-arrow">
                            <a class="sidebar-link" href="{{ url('/Bus-Type') }}" aria-expanded="false">
                                <span class="d-flex">
                                    {{-- <i class="ti ti-bus"></i> --}}
                                </span>
                                <span class="hide-menu">Bustypen (Bus Types)</span>
                            </a>
                        </li>
                        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Dispatcher')
                            <li class="sidebar-item has-arrow">
                                <a class="sidebar-link" href="{{ url('/Employees') }}" aria-expanded="false">
                                    <span class="d-flex">
                                        {{-- <i class="ti ti-users"></i> --}}
                                    </span>
                                    <span class="hide-menu">Mitarbeiter (Employee)</span>
                                </a>
                            </li>
                        @endif
                        @php
                            $support = DB::table('support_setting')->where('id', 1)->first();
                        @endphp
                    </ul>
                    <ul id="sidebarnav" style="bottom: 0px; position: fixed; margin-bottom: 0px; width: 215px;">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ $support->link }}" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="fa-solid fa-circle-question text-muted fs-6"></i>
                                </span>
                                <span class="hide-menu">Support</span>
                                <i class="hide-menu fa-solid fa-arrow-up-right-from-square  text-muted"
                                    style="margin-left: 70px;"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse"
                                href="javascript:void(0)">
                                <i class="ti ti-menu-2 menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link" style="color: #fff; font-size: 16px;" href="javascript:void(0)">
                                {{ @$title }}
                            </div>
                        </li>
                    </ul>
                    <div class="d-block d-lg-none" style="margin-left: -20px;margin-top: -75px;">
                        <img src="{{ asset('public') }}/dist/images/backgrounds/logo.png" class="dark-logo"
                            width="150" alt="" />
                        <img src="{{ asset('public') }}/dist/dist/images/backgrounds/logo.png" class="light-logo"
                            width="150" alt="" />
                        {{-- <h3 class="dark-logo text-dark fw-bolder">Kleen-air Filters</h3>
                        <h3 class="light-logo text-white fw-bolder">Kleen-air Filters</h3> --}}
                    </div>
                    <button class="navbar-toggler p-0 border-0" style="margin-top: -75px; color: #ffffff;" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="p-2">
                            <i class="ti ti-dots fs-7"></i>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end mobile-menu-nav" id="navbarNav">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="javascript:void(0)"
                                class="nav-link d-flex d-lg- none d-none align-items-center justify-content-center"
                                type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                                aria-controls="offcanvasWithBothOptions">
                                <i class="ti ti-align-justified fs-7"></i>
                            </a>
                            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                                <li class="nav-item me-4">
                                    <a class="nav-link nav-icon-hover" href="javascript:void(0)"
                                        aria-expanded="false">
                                        <i class="ti ti-bell-ringing" style="color: #fff !important;"></i>
                                        <div class="notification bg-light rounded-circle"></div>
                                    </a>
                                </li>
                                <li class="nav-item me-2">
                                    <div style="color: #fff">
                                        {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                                    </div>
                                </li>
                                <li class="nav-item dropdown me-2">
                                    <a class="nav-link pe-0" href="javascript:void(0)" id="drop1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <div class="user-profile-img">
                                                <i class="ti ti-chevron-down drop-down-icon"></i>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                        aria-labelledby="drop1">
                                        <div class="profile-dropdown position-relative" data-simplebar>
                                            <div class="py-3 px-7 pb-0">
                                                <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                            </div>
                                            <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                                {{-- <img src="{{ asset('public') }}/dist/images/profile/user-1.jpg"
                                                    class="rounded-circle" width="80" height="80"
                                                    alt="" /> --}}
                                                <div class="ms-3">
                                                    <h5 class="mb-1 fs-3">{{ ucwords(Auth::user()->name) }}</h5>
                                                    @if (Auth::user()->role != null)
                                                        <span
                                                            class="mb-1 d-block text-dark">{{ ucwords(Auth::user()->role) }}</span>
                                                    @endif
                                                    <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                                        <i class="ti ti-mail fs-4"></i> {{ Auth::user()->email }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="message-body">
                                                {{-- <a href="{{ url('/profile-settings') }}"
                                                    class="py-8 px-7 mt-8 d-flex align-items-center">
                                                    <span
                                                        class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                                        <img src="{{ asset('public') }}/dist/images/svgs/icon-account.svg"
                                                            alt="" width="24" height="24">
                                                    </span>
                                                    <div class="w-75 d-inline-block v-middle ps-3">
                                                        <h6 class="mb-1 bg-hover-primary fw-semibold"> My Profile </h6>
                                                        <span class="d-block text-dark">Account Settings</span>
                                                    </div>
                                                </a> --}}

                                            </div>
                                            @if (Auth::user()->role == 'Admin')
                                            <div class="d-grid py-2 px-7 pt-8">
                                                <a href="{{ url('/Link') }}"
                                                    class="btn btn-submit">Settings</a>
                                            </div>
                                            @endif
                                            <div class="d-grid py-2 px-7 pt-8">
                                                <a href="javascript:void(0);"
                                                    onclick="document.getElementById('logoutForm').submit();"
                                                    class="btn btn-submit">Log Out</a>
                                                <form id="logoutForm" method="POST" action="{{ url('/logout') }}">
                                                    @csrf</form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <!--  Header End -->
            @yield('content')
        </div>
        <div class="dark-transparent sidebartoggler"></div>
        <div class="dark-transparent sidebartoggler"></div>
    </div>

    <!--  Mobilenavbar -->
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="mobilenavbar"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <nav class="sidebar-nav scroll-sidebar">
            <div class="offcanvas-header justify-content-between">
                <img src="{{ asset('public') }}/dist/images/logos/favicon.ico" alt="" class="img-fluid">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body profile-dropdown mobile-navbar" data-simplebar="" data-simplebar>
                <ul id="sidebarnav">
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <span>
                                <i class="ti ti-apps"></i>
                            </span>
                            <span class="hide-menu">Apps</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level my-3">
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public') }}/dist/images/svgs/icon-dd-chat.svg"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Chat Application</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">New messages arrived</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public') }}/dist/images/svgs/icon-dd-invoice.svg"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Invoice App</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Get latest invoice</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public') }}/dist/images/svgs/icon-dd-mobile.svg"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Contact Application</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">2 Unsaved Contacts</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public') }}/dist/images/svgs/icon-dd-message-box.svg"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Email App</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Get new emails</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public') }}/dist/images/svgs/icon-dd-cart.svg"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">User Profile</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">learn more information</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public') }}/dist/images/svgs/icon-dd-date.svg"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Calendar App</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Get dates</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public') }}/dist/images/svgs/icon-dd-lifebuoy.svg"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Contact List Table</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Add new contact</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public') }}/dist/images/svgs/icon-dd-application.svg"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Notes Application</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">To-do and Daily tasks</span>
                                    </div>
                                </a>
                            </li>
                            <ul class="px-8 mt-7 mb-4">
                                <li class="sidebar-item mb-3">
                                    <h5 class="fs-5 fw-semibold">Quick Links</h5>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Pricing Page</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Authentication Design</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Register Now</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">404 Error Page</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Notes App</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">User Application</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Account Settings</a>
                                </li>
                            </ul>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="app-chat.html" aria-expanded="false">
                            <span>
                                <i class="ti ti-message-dots"></i>
                            </span>
                            <span class="hide-menu">Chat</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="app-calendar.html" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar"></i>
                            </span>
                            <span class="hide-menu">Calendar</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="app-email.html" aria-expanded="false">
                            <span>
                                <i class="ti ti-mail"></i>
                            </span>
                            <span class="hide-menu">Email</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!--  Search Bar -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content rounded-1">
                <div class="modal-header border-bottom">
                    <input type="search" class="form-control fs-3" placeholder="Search here" id="search" />
                    <span data-bs-dismiss="modal" class="lh-1 cursor-pointer">
                        <i class="ti ti-x fs-5 ms-3"></i>
                    </span>
                </div>
                <div class="modal-body message-body" data-simplebar="">
                    <h5 class="mb-0 fs-5 p-1">Quick Page Links</h5>
                    <ul class="list mb-0 py-2">
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Modern</span>
                                <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Dashboard</span>
                                <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Contacts</span>
                                <span class="fs-3 text-muted d-block">/apps/contacts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Posts</span>
                                <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Detail</span>
                                <span
                                    class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Shop</span>
                                <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Modern</span>
                                <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Dashboard</span>
                                <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Contacts</span>
                                <span class="fs-3 text-muted d-block">/apps/contacts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Posts</span>
                                <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Detail</span>
                                <span
                                    class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Shop</span>
                                <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--  Import Js Files -->
    <script src="{{ asset('public') }}/dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('public') }}/dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="{{ asset('public') }}/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--  core files -->
    <script src="{{ asset('public') }}/dist/js/app.min.js"></script>
    <script src="{{ asset('public') }}/dist/js/app.init.js"></script>
    <script src="{{ asset('public') }}/dist/js/app-style-switcher.js"></script>
    <script src="{{ asset('public') }}/dist/js/sidebarmenu.js"></script>
    <script src="{{ asset('public') }}/dist/js/custom.js"></script>
    <script src="{{ asset('public') }}/dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="{{ asset('public') }}/dist/js/forms/sweet-alert.init.js"></script>
    <!--  current page js files -->
    <script src="{{ asset('public') }}/dist/libs/owl.carousel/dist/owl.carousel.min.js"></script>
    <script src="{{ asset('public') }}/dist/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="{{ asset('public') }}/dist/js/dashboard.js"></script>
    <script src="{{ asset('public') }}/dist/libs/bootstrap-material-datetimepicker/node_modules/moment/moment.js"></script>
    <script src="{{ asset('public') }}/dist/libs/daterangepicker/daterangepicker.js"></script>
    @if (Session::has('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ Session::get('success') }}',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-submit text-light'
                },
                buttonsStyling: false
            });
        </script>
    @elseif (Session::has('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ Session::get('error') }}',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-submit text-light'
                },
                buttonsStyling: false
            });
        </script>
    @endif
    @yield('javascript')
    <script></script>
</body>

</html>
