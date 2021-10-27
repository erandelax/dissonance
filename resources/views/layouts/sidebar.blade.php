<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="background:#111">
<head>
    @include('partials.layout_head')
    @routes
    @stack('styles')
    @include('partials.dynamic_styles')
    @stack('scripts')
</head>
<body class="with-custom-webkit-scrollbars with-custom-css-scrollbars" data-dm-shortcut-enabled="true" data-sidebar-shortcut-enabled="true" data-set-preferred-mode-onload="true">
<!-- Modals go here -->
@stack('modals')
@include('partials.iframe_modal')
<!-- Reference: https://www.gethalfmoon.com/docs/modal -->

<!-- Page wrapper start -->
<div class="page-wrapper with-navbar with-sidebar {{--with-navbar-fixed-bottom--}}">
    @include('partials.layout_sticky_alerts')
    @include('partials.layout_navbar')
    @include('partials.layout_sidebar', ['search' => false])
    @include('partials.layout_content_wrapper')
    {{--@include('partials.layout_fixed_bottom')--}}
</div>
<!-- Page wrapper end -->

<!-- POST-BODY-SCRIPTS -->
<script src="{{asset('vendor/halfmoon/js/halfmoon.min.js')}}"></script>
<script>window.alerts = JSON.parse('{!! \App\Utils\Alert::serialize() !!}')</script>
</body>
</html>
