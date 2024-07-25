@extends('diy.layouts.master')

@section('content')
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('diy.partials.sidenav')
        @include('diy.partials.topnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                @include('diy.partials.breadcrumb')

                @yield('panel')


            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>



@endsection
