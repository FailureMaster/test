<div class="sidebar-menu">
    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-xl-none d-block"><i class="fas fa-times"></i></span>
        <div class="sidebar-logo">
            <a href="{{ route('user.home') }}" class="sidebar-logo__link">
                <img src="{{siteLogo()}}">
            </a>
        </div>
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.home') }}" class="sidebar-menu-list__link {{ menuActive('user.home') }}">
                    <span class="icon"><span class="icon-dashboard"></span></span>
                    <span class="text">@lang('Daashboard')</span>
                </a>
            </li>
       
        
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.deposit.history') }}" class="sidebar-menu-list__link {{ menuActive('user.deposit.*') }}">
                    <span class="icon"><span class="icon-deposit"></span></span>
                    <span class="text">@lang('Deposit History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.withdraw.history') }}" class="sidebar-menu-list__link {{ menuActive('user.withdraw.history') }}">
                    <span class="icon"><span class="icon-withdraw"></span></span>
                    <span class="text">@lang('Withdraw History')</span>
                </a>
            </li>
                    <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.transactions') }}" class="sidebar-menu-list__link {{ menuActive('user.transactions') }}">
                    <span class="icon"><span class="icon-transaction"></span></span>
                    <span class="text">@lang('Transaction Histoy')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('ticket.index') }}" class="sidebar-menu-list__link {{ menuActive('ticket.*') }}">
                    <span class="icon"><span class="icon-support"></span></span>
                    <span class="text">@lang('Get Support')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.twofactor') }}" class="sidebar-menu-list__link {{ menuActive('user.twofactor') }}">
                    <span class="icon"><span class="icon-security"></span></span>
                    <span class="text">@lang('Security')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item ">
                <a href="{{ route('user.twofactor') }}" class="sidebar-menu-list__link">
                    <span class="icon"><span class="icon-logout"></span></span>
                    <span class="text">@lang('Logout')</span>
                </a>
            </li>
        </ul>
    </div>
</div>
