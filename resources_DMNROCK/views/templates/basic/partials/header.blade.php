<header class="header" id="header">
    <div class="container-fluid mt-2">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo ps-5 ms-5" href="{{ route('home') }}">
                <img src="{{ siteLogo() }}">
            </a>
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
                                        <span class="text">{{ __(@$langDetails->name) }}</span>
                                    </div>
                                    <ul class="dropdown-list">
                                        @foreach ($languages as $language)
                                            <li class="dropdown-list__item change-lang "
                                                data-code="{{ @$language->code }}">
                                                <div class="thumb">
                                                    <img
                                                        src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                                </div>
                                                <span class="text">{{ __(@$language->name) }}</span>
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
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.home') }}"
                                                class="btn btn--base btn--sm">@lang('Dashboard')</a>
                                        </li>
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.logout') }}" class="sign-in">@lang('Logout')</a>
                                        </li>
                                    @endguest
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
                                <span class="text">{{ __(@$langDetails->name) }}</span>
                            </div>
                            <ul class="dropdown-list">
                                @foreach ($languages as $language)
                                    <li class="dropdown-list__item change-lang "
                                        data-code="{{ @$language->code }}">
                                        <div class="thumb">
                                            <img
                                                src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                        </div>
                                        <span class="text">{{ __(@$language->name) }}</span>
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
                                    <p class="text-white">@lang('Welcome to'), {{ auth()->user()->fullname }}</p>
                                </li>
                                <li class="login-registration-list__item">
                                    <button class="btn btn--base btn--sm" id="for-deposit-btn">@lang('Deposit ')</button>
                                </li>
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.home') }}"
                                        class="btn btn--base btn--sm">@lang('Dashboard ')</a>
                                </li>
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.logout') }}" class="sign-in">@lang('Logout')</a>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </li>
            </ul>
            <div class="theme-switch-wrapper">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" class="d-none" id="checkbox">
                    <span class="slider">
                        <i class="las la-sun"></i>
                    </span>
                </label>
            </div>
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
    document.getElementById("for-deposit-btn") && document.getElementById("for-deposit-btn").addEventListener('click', () => {
        document.getElementById("deposit_btn").click();
    })
</script>
@endpush
