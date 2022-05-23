<!DOCTYPE html>

<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="{{asset('images/logo.svg')}}" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Midone Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>Dashboard - Midone - Tailwind HTML Admin Template</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{asset('css/_app.css')}}" />
        <!-- END: CSS Assets-->
        <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
    </head>
    <!-- END: Head -->
    <body class="py-5">
        <!-- BEGIN: Mobile Menu -->
        
         @include('layout.partial.mob-menu')
        <!-- END: Mobile Menu -->
        <div class="flex" style="direction:  rtl;">
            <!-- BEGIN: Side Menu -->
            @include('layout.partial.nav')
            <!-- END: Side Menu -->
            <!-- BEGIN: Content -->
            @yield('content')
            
            <!-- END: Content -->
        </div>
        <!-- BEGIN: Dark Mode Switcher-->
        <div data-url="side-menu-dark-dashboard-overview-1.html" class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 left-0 box border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
            <div class="mr-4 text-slate-600 dark:text-slate-200">Dark Mode</div>
            <div class="dark-mode-switcher__toggle border"></div>
        </div>
        <!-- END: Dark Mode Switcher-->
        
        <!-- BEGIN: JS Assets-->
        <script src="{{asset('js/markerclusterer.js')}}"></script>
        
        <script src="{{asset('js/app.js')}}"></script>
        <script
          src="{{asset('js/jquery-3.6.0.min.js')}}"
          
          ></script>
        <!-- END: JS Assets-->
    </body>
</html>