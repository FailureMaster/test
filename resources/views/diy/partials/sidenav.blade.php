<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('admin.dashboard')}}" class="sidebar__main-logo"><img src="{{siteLogo()}}"></a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive(['admin.order*','admin.trade.history'],3)}}">
                        <i class="menu-icon las la-coins"></i>
                        <span class="menu-title">@lang('Manage Order')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive(['admin.order*','admin.trade.history'],2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive(['admin.order.open'])}}">
                                <a href="{{route('diy.order.open')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Open Orders')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive(['admin.order.history'])}}">
                                <a href="{{route('diy.order.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Total Orders')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive(['admin.order.close'])}}">
                                <a href="{{route('diy.order.close')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Closed Orders')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <div class="footer-section text-center mb-3 text-uppercase position-absolute">
                <span class="text--primary">Sputnik22</span>
                <span class="text--success">@lang('V'){{systemDetails()['version']}} </span>
            </div>
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('script')
<script>
    if ($('li').hasClass('active')) {
        $('#sidebar__menuWrapper').animate({
            scrollTop: eval($(".active").offset().top - 320)
        }, 500);
    }
</script>
@endpush

@push('style')
<style>
    .footer-section {
        left: 25%;
        bottom: 20px;
    }
</style>
@endpush
