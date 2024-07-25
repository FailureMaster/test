<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $general->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <link rel="manifest"   href="{{ route('pwa.configuration') }}">
    <link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/52653.18b84e6734bed9409e26.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/4015.1d0e3a62a59d173c81f3.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/76592.e28e75165f1449a40136.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/89842.a124f95c68aef6c6fa6f.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/39855.8ef3d02e39af37e8d19e.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/92483.726dbd3f2cf50c973c26.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/37124.57ed1f0e14de0ce7dcbb.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/50293.d4ce023e54009adf69b3.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/67103.76dcfc00f4fbfd0cf774.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/8545.3ea79d7dc2adb798b744.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/63391.a7e46868a2e859477d4a.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/92436.985d54f729f98977bb6a.css" rel="stylesheet"
    type="text/css" />
<link crossorigin="anonymous"
    href="https://static.tradingview.com/static/bundles/embed/49981.10d6f2ad585fd540c0db.css" rel="stylesheet"
    type="text/css" />
    @stack('style-lib')
    @stack('style')
    
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/auth_color.php') }}?color={{ $general->auth_color }}">
    
    <style>
        .progress-cricle{
            width: 80px;
            height: 80px;
            border-radius: 50%;
            left: 5%;
            z-index: 99;
            bottom: 0;
            position: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="progress-cricle">
        <h4 class="progress-cricle__number"></h4>
    </div>
    @stack('fbComment')
    <div class="preloader-wrapper">
        <div class="preloader">
            <div class="wrapper">
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
                <div class="loader">
                    <div class="dot"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>
    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

    @yield('main-content')

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <div class="cookies-card text-center hide">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="mt-4 cookies-card__content">{{ $cookie->data_values->short_desc }} <a
                    href="{{ route('cookie.policy') }}" class="text--base" target="_blank">@lang('learn more')</a></p>
            <div class="cookies-card__btn mt-4">
                <a href="javascript:void(0)" class="btn btn--base w-100 policy">@lang('Allow')</a>
            </div>
        </div>
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    
    <script>
        window.my_pusher = {
            'app_key'    : "{{ base64_encode(config('app.PUSHER_APP_KEY')) }}",
            'app_cluster': "{{ base64_encode(config('app.PUSHER_APP_CLUSTER')) }}",
            'base_url'   : "{{ route('home') }}"
        }
        window.allow_decimal="{{ $general->allow_decimal_after_number }}";
    </script>

    @stack('script-lib')
    @stack('script')
    @include('partials.plugins')
    @include('partials.notify')
    <script>
        (function($) {
            "use strict";
            
          $('.change-account-type').on('click', function() {
                var selectedAccountType = $(this).data('account');
                $('#accountTypeInput').val(selectedAccountType);
                $('#accountTypeForm').submit();
            });

            $('.change-lang').on('click', function(e) {
                let langCode = $(this).data('code');
                window.location.href = "{{ route('home') }}/change/" + langCode;
            });

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                let windowsIsDarkTheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (windowsIsDarkTheme) {
                    setTheme('light');
                } else {
                    setTheme('dark');
                }
            });

            const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
            const currentTheme = localStorage.getItem('theme');

            if (currentTheme) {
                setTheme(currentTheme);
            } else {
                let windowsIsDarkTheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (windowsIsDarkTheme) {
                    setTheme('light');
                } else {
                    setTheme('dark');
                }
            }

            function setTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                theme == 'dark' ? toggleSwitch.checked = true : toggleSwitch.checked = false;
            }

            toggleSwitch.addEventListener('change', function(e) {
                setTheme(e.target.checked ? 'dark' : 'light');
            });

            var inputElements = $('input,select');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

        })(jQuery);


        async function registerSW() {
            if ('serviceWorker' in navigator) {
                try {
                    await navigator.serviceWorker.register("{{ asset($activeTemplateTrue . 'js/pwa/serviceworker.js') }}");
                } catch (e) {
                    console.warn('SW registration failed');
                }
            }
        }
        window.addEventListener('load', () => {
            registerSW();
        });
    </script>
</body>
</html>
