<header class="header" id="header">
    <div class="container-fluid mt-2">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="d-flex align-items-center justify-content-between w-100">
                <a class="navbar-brand logo @if(!is_mobile()) ps-5 ms-5 @endif" href="{{ route('home') }}" style="margin-right: 0">
                    <img src="{{ siteLogo() }}">
                </a>
                @auth
                    @if (! enabledDashboard() && is_mobile())
                        <a
                            href="https://dev.quikipay.com/donate/ElomZUyWptRBW5HJFUolk69aFHUgXnqbE3wYcstX/68/27b1b43cbdde7099bb0ea35fc71c1a818abbc7dee6c97b529658065cf1dcf6c8"
                            class="btn btn--base btn--sm"
                            id="for-deposit-btn"
                            >
                            @lang('Deposit ')
                        </a>
                    @endif
                @endauth
            </div>
            @auth
                <div>
                    <div class="d-flex">
                        <p class="text-white">@lang('Welcome'), </p>
                        <div class="position-relative mx-1">
                            <div class="user-info">
                                <div class="user-info__right">
                                    <div class="user-info__button">
                                        <div class="user-info__profile text-white d-flex align-items-center">
                                            <p class="user-info__name">{{ __(auth()->user()->fullname) }}</p>
                                            <i class="las la-angle-down mx-1"></i>
                                        </div>
                                    </div>
                                </div>
                                <ul class="user-info-dropdown">
                                    <li class="user-info-dropdown__item">
                                        <a class="user-info-dropdown__link" href="{{ route('user.profile.setting') }}">
                                            <span class="icon"><i class="far fa-user-circle"></i></span>
                                            <span class="text">@lang('My Profile')</span>
                                        </a>
                                    </li>
                                    <li class="user-info-dropdown__item">
                                        <a class="user-info-dropdown__link" href="{{ route('user.change.password') }}">
                                            <span class="icon"><i class="fa fa-key"></i></span>
                                            <span class="text">@lang('Change Password')</span>
                                        </a>
                                    </li>
                                    <li class="user-info-dropdown__item">
                                        <a class="user-info-dropdown__link" href="{{ route('user.logout') }}">
                                            <span class="icon"><i class="far fa-user-circle"></i></span>
                                            <span class="text">@lang('Logout')</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @if (allowsDemoAccount())
                    @if (auth()->user()->account_type == 'demo')
                        <form action="{{ route('user.toggle.type', auth()->user()->id) }}" method="POST" id="accountTypeForm">
                            @csrf
                            <div class="position-relative" style="width: 150px; margin-left: 15px">
                                <div class="custom--dropdown w-100">
                                    <div class="custom--dropdown__selected dropdown-list__item">
                                        <p class="text-white text-capitalize">
                                            <i class="las la-bullseye"></i>
                                            {{ auth()->user()->account_type }}
                                            <i class="las la-caret-down"></i>
                                        </p>
                                    </div>
                                    <ul class="dropdown-list">
                                        <li class="dropdown-list__item change-account-type" data-account="demo" {{ auth()->user()->account_type == 'real' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>
                                            <span class="text">Demo</span>
                                        </li>
                                        <li class="dropdown-list__item change-account-type" data-account="real">
                                            <span class="text">Real</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="account_type" id="accountTypeInput" value="{{ auth()->user()->account_type }}">
                        </form>
                    @endif
                @endif
            @endauth
            
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu me-auto align-items-lg-center flex-wrap">
                    <li class="nav-item d-block d-lg-none">
                        @if ($general->multi_language)
                            @php
                                $langDetails = $languages->where('code', config('app.locale'))->first();
                            @endphp
                            <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                                <div class="custom--dropdown">
                                    <div class="custom--dropdown__selected dropdown-list__item">
                                        <div class="thumb">
                                            <img
                                                src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                                        </div>
                                        <span class="text text-uppercase">{{ __(@$langDetails->code) }}</span>
                                    </div>
                                    <ul class="dropdown-list">
                                        @foreach ($languages as $language)
                                            <li class="dropdown-list__item change-lang "
                                                data-code="{{ @$language->code }}">
                                                <div class="thumb">
                                                    <img
                                                        src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                                </div>
                                                <span class="text text-uppercase">{{ __(@$language->code) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <ul class="login-registration-list d-flex flex-wrap align-items-center">
                                    @guest
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.login') }}" class="sign-in ">@lang('Login')</a>
                                        </li>
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.register') }}"
                                                class="btn btn--base btn--sm ">@lang('Sign up') </a>
                                        </li>
                                    @else
                                        @if (enabledDashboard())
                                            <li class="login-registration-list__item">
                                                <a href="{{ route('user.home') }}"
                                                    class="btn btn--base btn--sm">@lang('Dashboard')</a>
                                            </li>
                                        @endif
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.logout') }}" class="sign-in">@lang('Logout')</a>
                                        </li>
                                    @endguest
                                    <li>
                                        <div class="theme-switch-wrapper">
                                            <label class="theme-switch" for="checkbox">
                                                <input type="checkbox" class="d-none" id="checkbox">
                                                <span class="slider">
                                                    <i class="las la-sun"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </li>
                </ul>
            </div>
            <ul class="header-right d-lg-block d-none">
                <li class="nav-item">
                    <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                        <div class="custom--dropdown">
                            <div class="custom--dropdown__selected dropdown-list__item">
                                <div class="thumb">
                                    <img
                                        src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                                </div>
                                <span class="text text-uppercase">{{ __(@$langDetails->code) }}</span>
                            </div>
                            <ul class="dropdown-list">
                                @foreach ($languages as $language)
                                    <li class="dropdown-list__item change-lang "
                                        data-code="{{ @$language->code }}">
                                        <div class="thumb">
                                            <img
                                                src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                        </div>
                                        <span class="text text-uppercase">{{ __(@$language->code) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <ul class="login-registration-list d-flex flex-wrap align-items-center">
                            @guest
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.login') }}" class="sign-in">@lang('Login')</a>
                                </li>
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.register') }}"
                                        class="btn btn--base btn--sm">@lang('Sign up') </a>

                                </li>
                            @else
                                <li class="login-registration-list__item">
                                    @if (auth()->user()->account_type !== 'demo')
                                        <a
                                            href="https://dev.quikipay.com/donate/ElomZUyWptRBW5HJFUolk69aFHUgXnqbE3wYcstX/68/27b1b43cbdde7099bb0ea35fc71c1a818abbc7dee6c97b529658065cf1dcf6c8"
                                            class="btn btn--base btn--sm"
                                            id="for-deposit-btn"
                                            >
                                            @lang('Deposit ')
                                        </a>
                                    @else
                                        <button class="btn btn--base btn--sm" data-bs-toggle="modal" data-bs-target="#frozeAccountModal">@lang('Deposit ')</button>
                                    @endif
                                </li>
                                @if (enabledDashboard())
                                    <li class="login-registration-list__item">
                                        <a href="{{ route('user.home') }}" class="btn btn--base btn--sm">@lang('Dashboard ')</a>
                                    </li>
                                @endif
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.logout') }}" class="sign-in">@lang('Logout')</a>
                                </li>
                            @endguest
                            <li>
                                <div class="theme-switch-wrapper">
                                    <label class="theme-switch" for="checkbox">
                                        <input type="checkbox" class="d-none" id="checkbox">
                                        <span class="slider">
                                            <i class="las la-sun"></i>
                                        </span>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
    <div class="container-fluid px-0 mt-2">
        <!-- TradingView Widget BEGIN -->
        <div class="tradingview-widget-container">
          <div class="tradingview-widget-container__widget"></div>
         
          <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
          {
          "symbols": [
            {
              "proName": "FOREXCOM:SPXUSD",
              "title": "S&P 500 Index"
            },
            {
              "proName": "FOREXCOM:NSXUSD",
              "title": "US 100 Cash CFD"
            },
            {
              "proName": "FX_IDC:EURUSD",
              "title": "EUR to USD"
            },
            {
              "proName": "BITSTAMP:BTCUSD",
              "title": "Bitcoin"
            },
            {
              "proName": "BITSTAMP:ETHUSD",
              "title": "Ethereum"
            },
            {
              "description": "TSLA",
              "proName": "NASDAQ:TSLA"
            },
            {
              "description": "GOLD",
              "proName": "OANDA:XAUUSD"
            },
            {
              "description": "CL",
              "proName": "NYMEX:CL1!"
            },
            
            {
              "description": "AAPL",
              "proName": "NASDAQ:AAPL"
            }
          ],
          "showSymbolLogo": true,
          "isTransparent": false,
          "displayMode": "adaptive",
          "colorTheme": "dark",
          "locale": "en"
        }
          </script>
        </div>
        <!-- TradingView Widget END -->
    </div>
</header>
@push('script')
<script>
    // document.getElementById("for-deposit-btn") && document.getElementById("for-deposit-btn").addEventListener('click', () => {
    //     document.getElementById("deposit__button").click();
    // })
    "use strict";
        (function($) {

            $('.depositBtnTopbar').on('click', function(e) {
                canvasShowTopbar("deposit-canvas");
            });

            $('.withdrawBtn').on('click', function(e) {
                canvasShowTopbar("withdraw-offcanvas");
            });

            $('.transferBtn').on('click', function(e) {
                canvasShowTopbar("transfer-offcanvas");
            });

            function canvasShowTopbar(id) {
                let myOffcanvas = document.getElementById(id);
                new bootstrap.Offcanvas(myOffcanvas).show();
            }

            $('.transfer-type').on('click', function(e) {
                let tranfserType = $(this).data('transfer-type');
                $('.transfer-type').find(`button`).removeClass('active');
                $(this).find(`button`).addClass('active');
                $(`.transfer-wrapper`).addClass('d-none');
                $(`.other-${tranfserType}-transfer`).removeClass('d-none');
            });

            $('.max').on('click',function(e){
                const max=$(this).data('max');
                $(this).closest('div').find(`input`).val(max);
                if($(this).hasClass('other-user-transfer-max')){
                    $(".other-user-transfer input[name=transfer_amount]").trigger('change');
                }
            });
            
            $('.user-info__button').on('click', function () {
              $('.user-info-dropdown').toggleClass('show');
            });
            $('.user-info__button').attr('tabindex', -1).focus();
        
            $('.user-info__button').on('focusout', function () {
              $('.user-info-dropdown').removeClass('show');
            });
            
        })(jQuery);
</script>
@endpush
@push('style')
    <style>
        .tradingview-widget-container,
        .tradingview-widget-container iframe {
            height: 37px !important;
        }
        
        .user-info__button {
            cursor: pointer;
        }
        
        .user-info .user-info-dropdown {
            border-radius: 4px;
            overflow: hidden;
            transition: 0.25s linear;
            border: 1px solid hsl(var(--white) / 0.14);
            background-color: #111e21;
            box-shadow: 0px 5px 25px rgba(0, 0, 0, 0.1);
            width: 220px;
            position: absolute;
            right: 0;
            z-index: 9;
            top: 100%;
            margin-top: 4px;
            padding: 15px;
            transform: scale(0.95);
            visibility: hidden;
            opacity: 0;
        }
        
        .user-info .user-info-dropdown.show {
            visibility: visible;
            opacity: 1;
            transform: scale(1);
        }
    </style>
@endpush
