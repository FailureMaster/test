<div class="trading-right" style="height: 99%">
    <div class="trading-right__top">    
        <div class="w-100">
            <form id="search-market" onsubmit="searchMarket(event)">
                <div class="input--group">
                    <button class="search-btn" type="submit"><i class="las la-search"></i></button>
                    <input type="text" class="form--control style-two pjsInput market-search" placeholder="{{ __('Search') }}" name="search" id="searchInput">
                </div>
                <div class="coin-search-list-body">

                </div>
            </form>
        </div>
    </div>
    <nav id="market-nav">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button
                class="dropdown-btn"
                id="nav-favorites-tab"
                onclick="toggleDropdown('favorites')"
                data-bs-toggle="tab"
                data-bs-target="#nav-favorites"
                role="tab"
                aria-controls="nav-favorites"
                aria-selected="false">
                <div class="d-flex justify-content-between" style="align-items: center">
                    <div style="flex: 1">
                        <span class="arrow-indicator">&#9654;</span>
                        <span class="title-text text-uppercase">@lang('Favorites')</span>
                    </div>
                    <span class="price-text toggle-col">Price</span>
                    <span class="daily-change-text toggle-col">Daily change</span>
                </div>
            </button>
                <div class="dropdown-container" id="dropdown-container-favorites" style="display: none;">
                    <div class="tab-pane fade" id="nav-favorites" role="tabpanel" aria-labelledby="nav-favorites-tab">
                        <div class="market-favorites-body"></div>
                    </div> 
                </div>

            <button
                class="dropdown-btn"
                id="nav-arabic-tab"
                onclick="toggleDropdown('arabic')"
                data-bs-toggle="tab"
                data-bs-target="#nav-arabic"
                role="tab"
                aria-controls="nav-arabic"
                aria-selected="false">
                <div class="d-flex justify-content-between" style="align-items: center">
                    <div style="flex: 1">
                        <span class="arrow-indicator">&#9654;</span>
                        <span class="title-text text-uppercase">@lang('Arabic Stocks')</span>
                    </div>
                    <span class="price-text toggle-col">Price</span>
                    <span class="daily-change-text toggle-col">Daily change</span>
                </div>
            </button>
                <div class="dropdown-container" id="dropdown-container-arabic" style="display: none;">
                    <div class="tab-pane fade" id="nav-arabic" role="tabpanel" aria-labelledby="nav-arabic-tab">
                        <div class="market-arabic-body"></div>
                    </div> 
                </div>

            <button
                class="dropdown-btn"
                id="nav-stocks-tab"
                onclick="toggleDropdown('stocks')"
                data-bs-toggle="tab"
                data-bs-target="#nav-stocks"
                role="tab"
                aria-controls="nav-stocks"
                aria-selected="false">
                <div class="d-flex justify-content-between" style="align-items: center">
                    <div style="flex: 1">
                        <span class="arrow-indicator">&#9654;</span>
                        <span class="title-text text-uppercase">@lang('Stocks')</span>
                    </div>
                    <span class="price-text toggle-col">Price</span>
                    <span class="daily-change-text toggle-col">Daily change</span>
                </div>
            </button>
                <div class="dropdown-container" id="dropdown-container-stocks" style="display: none;">
                    <div class="tab-pane fade" id="nav-stocks" role="tabpanel" aria-labelledby="nav-stocks-tab">
                        <div class="market-stocks-body"></div>
                    </div> 
                </div>
            <button
                class="dropdown-btn"
                id="nav-forex-tab"
                onclick="toggleDropdown('forex')"
                data-bs-toggle="tab"
                data-bs-target="#nav-forex"
                role="tab"
                aria-controls="nav-forex"
                aria-selected="false">
                <div class="d-flex justify-content-between" style="align-items: center">
                    <div style="flex: 1">
                        <span class="arrow-indicator">&#9654;</span>
                        <span class="title-text text-uppercase">@lang('Forex')</span>
                    </div>
                    <span class="price-text toggle-col">Price</span>
                    <span class="daily-change-text toggle-col">Daily change</span>
                </div>

            </button>
                <div class="dropdown-container" id="dropdown-container-forex" style="display: none;">
                    <div class="tab-pane fade show active" id="nav-forex" role="tabpanel" aria-labelledby="nav-forex-tab">
                        <div class="market-forex-body"></div>
                    </div>
                </div>
            <button
                class="dropdown-btn"
                id="nav-index-tab"
                data-bs-toggle="tab"
                data-bs-target="#nav-index"
                onclick="toggleDropdown('index')"
                role="tab"
                aria-controls="nav-index"
                aria-selected="false"
                >
                <div class="d-flex justify-content-between" style="align-items: center">
                    <div style="flex: 1">
                        <span class="arrow-indicator">&#9654;</span>
                        <span class="title-text text-uppercase">@lang('Index')</span>
                    </div>
                    <span class="price-text toggle-col">Price</span>
                    <span class="daily-change-text toggle-col">Daily change</span>
                </div>

            </button>
                <div class="dropdown-container" id="dropdown-container-index" style="display: none;">
                    <div class="tab-pane fade" id="nav-index" role="tabpanel" aria-labelledby="nav-index-tab">
                        <div class="market-index-body"></div>
                    </div>
                 </div>
            <button
                class="dropdown-btn"
                id="nav-crypto-tab"
                data-bs-toggle="tab"
                data-bs-target="#nav-crypto"
                onclick="toggleDropdown('crypto')"
                role="tab"
                aria-controls="nav-crypto"
                aria-selected="true"
                >
                <div class="d-flex justify-content-between" style="align-items: center">
                    <div style="flex: 1">
                        <span class="arrow-indicator">&#9654;</span>
                        <span class="title-text text-uppercase">@lang('Crypto')</span>
                    </div>
                    <span class="price-text toggle-col">Price</span>
                    <span class="daily-change-text toggle-col">Daily change</span>
                </div>

            </button>
                <div class="dropdown-container" id="dropdown-container-crypto" style="display: none;">
                    <div class="tab-pane fade" id="nav-crypto" role="tabpanel" aria-labelledby="nav-crypto-tab">
                        <div class="market-crypto-body"></div>
                    </div>
                </div>
            <button
            class="dropdown-btn"
                id="nav-commodity-tab"
                data-bs-toggle="tab"
                data-bs-target="#nav-commodity"
                onclick="toggleDropdown('commodity')"
                role="tab"
                aria-controls="nav-commodity"
                aria-selected="false"
                >
                <div class="d-flex justify-content-between" style="align-items: center">
                    <div style="flex: 1">
                        <span class="arrow-indicator">&#9654;</span>
                        <span class="title-text text-uppercase">@lang('Commodity')</span>
                    </div>
                    <span class="price-text toggle-col">Price</span>
                    <span class="daily-change-text toggle-col">Daily change</span>
                </div>

            </button>
                <div class="dropdown-container" id="dropdown-container-commodity" style="display: none;">
                    <div class="tab-pane fade" id="nav-commodity" role="tabpanel" aria-labelledby="nav-commodity-tab">
                        <div class="market-commodity-body"></div>
                    </div>
                </div>
        </div>
    </nav>
    <div class="market-tab-content tab-content" id="nav-tabContent">
    
    </div>
</div>
@push('script')
    <script>
        "use strict";
        
        window.addEventListener('DOMContentLoaded', (event) => {
            $('.market-search').on('input', searchMarket);
    
            fetchHistory();

            setTimeout(() => {
                const urlParams = new URLSearchParams(window.location.search);
                const categoryParam = urlParams.get('category');
                
                if (categoryParam) {
                    toggleDropdown(categoryParam);
                    showDropdownContents(categoryParam);
                } else {
                    toggleDropdown('forex');
                    showDropdownContents('forex');
                }
            }, 500);
        });

        function showDropdownContents(type) {
            const tabPane = document.getElementById(`nav-${type}`);
            tabPane.classList.add('show');
        }

        function fetchHistory() {
            $.ajax({
                url: "{{ route('trade.fetch.favorite') }}",
                type: "GET",
                dataType: 'json',
                cache: false,
                success: function(favorites) {
                    let favorited = favorites.map(favorite => favorite.symbol);

                    $.ajax({
                        url: "{{ route('trade.fetch.coin') }}",
                        type: "GET",
                        dataType: 'json',
                        cache: false,
                        success: function(resp) {
                            let stocksData = resp.Stocks;
                            generateCoinssHTML(stocksData, '.market-stocks-body', 'stocks', 'Stocks', favorited)

                            let forexData = resp.FOREX;
                            generateCoinssHTML(forexData, '.market-forex-body', 'forex', 'FOREX', favorited)

                            let indexData = resp.INDEX;
                            generateCoinssHTML(indexData, '.market-index-body', 'index', 'INDEX', favorited)

                            let cryptoData = resp.Crypto;
                            generateCoinssHTML(cryptoData, '.market-crypto-body', 'crypto', 'Crypto', favorited)

                            let commodityData = resp.COMMODITY;
                            generateCoinssHTML(commodityData, '.market-commodity-body', 'commodity', 'COMMODITY', favorited)

                            fetchFavorites(resp);

                            updateElementValue();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching history: ", error);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching favorites: ", error);
                }
            });

            
        }

        fetchHistory();

        setInterval(fetchHistory, 1000);

        function updateElementValue() {
            let cli = document.getElementById('name-CL1!');
            if (cli) {
                cli.innerText = 'OIL';
            }

            let dowusd = document.getElementById('name-DOWUSD');
            if (dowusd) {
                dowusd.innerText = 'Dow Jones';
            }

            let ndx = document.getElementById('name-NDX');
            if (ndx) {
                ndx.innerText = 'NASDAQ 100';
            }

            let spx500usd = document.getElementById('name-SPX500USD');
            if (spx500usd) {
                spx500usd.innerText = 'S&P 500';
            }

            let jp225usd = document.getElementById('name-JP225USD');
            if (jp225usd) {
                jp225usd.innerText = 'Nikkei 225';
            }
        }

        function generateCoinssHTML(coinsData, className, category, belongsTo, favoriteCoins = []) {
            let html = '';
            for (let coin in coinsData) {
                let percentValue = parseFloat(coinsData[coin].percent.replace('%', ''));

                let colorClass = percentValue >= 0 ? 'text-success' : 'text-danger';

                let symbol = encodeURIComponent(coinsData[coin].dataSymbol);
                let encodedCoin = encodeURIComponent(coin);
                let param = `?category=${category ? category : coinsData[coin].category}&symbolHIFHSRbBIKR1pDOisb7nMDFp6JsuVZv=${symbol}%3A${encodedCoin}%3A%3A`;

                // Encode the parameter using Base64
                let encodedParam = btoa(param);

                let href = `?${encodedParam}`;

                let isFavorite = favoriteCoins.includes(coin);

                html += `
                    <div class="d-flex market-coin-item my-2 py-2 px-2">
                        <div class="d-flex" style="flex: 1">
                            <div class="coin-icon">
                                <img src="${coinsData[coin].logo_url}" alt="${coin}" style="height: 100%; width: auto; border-radius: 50%;">
                            </div>
                            <div class="position-relative text-right">
                                <a href="${param}" onclick="navigateToPage('${param}')" id="name-${coin}">${coin}</a>
                            </div>
                        </div>
                        <div class="position-relative price-text">
                            <a href="${param}" class="coin-link" data-param="${param}">${coinsData[coin].price}</a>
                        </div>
                        <div class="d-flex position-relative text-end daily-change-text">
                            <div class="d-flex justify-content-between w-100">
                                <span class="d-block text-secondary ${colorClass}">
                                    ${coinsData[coin].percent}
                                </span>
                                <div class="icon-favorite" style="margin-left: 5px; cursor: pointer" data-coin="${coin}" data-category="${category ? category : coinsData[coin].category}" onclick="addToFavorites('${coin}', '${belongsTo ? belongsTo : coinsData[coin].category}', '${coinsData[coin].dataSymbol}')">
                                    <i class="${isFavorite ? 'fas' : 'far'} fa-star" aria-hidden="true" style="color: ${isFavorite ? 'yellow' : ''}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            $(className).html(html);

            $(className).on('click', '.coin-link', function(event) {
                event.preventDefault(); // Prevent default link behavior
                let param = $(this).data('param');
                navigateToPage(param);
            });
        }

        function fetchFavorites(resp) {
            let marketData = resp
            let url = "{{ route('trade.fetch.favorite') }}";

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                cache: false,
                success: function(favorites) {
                    const favoriteData = {};
                    const favoriteCoins = favorites.map(favorite => favorite.symbol);
        
                    favorites.forEach(favorite => {
                        const category = favorite.category;
                        const symbol = favorite.symbol;
                        if (category in marketData && symbol in marketData[category]) {
                            let symbolData = marketData[category][symbol];
                            symbolData['symbol'] = symbol;
                            symbolData['category'] = category;
                            favoriteData[symbol] = symbolData;
                        }
                    });

                    generateCoinssHTML(favoriteData, '.market-favorites-body', '', '', favoriteCoins)
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching favorites: ", error);
                }
            });
        }

        function navigateToPage(param) {
            let decodedParam = atob(param);
            window.location.href = decodedParam;
        }

        function toggleDropdown(type) {
            const container = document.getElementById(`dropdown-container-${type}`);
            const button = document.getElementById(`nav-${type}-tab`);
            const isVisible = container.style.display === "block";
            hideAllDropdowns();
            container.style.display = isVisible ? "none" : "block";

            const arrowIndicator = document.querySelector(`#nav-${type}-tab .arrow-indicator`);
            arrowIndicator.classList.toggle('down', !isVisible);

            button.classList.toggle('active', !isVisible);
        }

        function hideAllDropdowns() {
            const containers = document.querySelectorAll('.dropdown-container');
            containers.forEach(container => {
                container.style.display = "none";
            });

            const arrowIndicators = document.querySelectorAll('.arrow-indicator');
            arrowIndicators.forEach(indicator => {
                indicator.classList.remove('down');
            });

            const buttons = document.querySelectorAll('.dropdown-btn');
            buttons.forEach(button => {
                button.classList.remove('active');
            });
        }

        function searchMarket(event) {
            event.preventDefault();
            const searchInput = $('.market-search').val().toUpperCase();

            if (!searchInput) {
                $('.coin-search-list-body').html('');
                $('.coin-search-list-body').css('display', 'none');
                return;
            } else {
                $('.coin-search-list-body').css('display', 'block');
            }

            let url = "{{ route('trade.fetch.coin') }}";

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                cache: false,
                success: function(resp) {
                    const filteredData = {};
                    for (const category in resp) {
                        for (const symbol in resp[category]) {
                            if (symbol.toUpperCase().includes(searchInput)) {
                                let symbolData = resp[category][symbol];
                                symbolData['symbol'] = symbol;
                                symbolData['category'] = category;

                                filteredData[symbol] = symbolData;
                            }
                        }
                    }

                    generateCoinssHTML(filteredData, '.coin-search-list-body', '', '');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching history: ", error);
                }
            });
        }

        function addToFavorites(coin, category, dataSymbol) {
            let url = "{{ route('trade.favorite.add') }}";

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    coin: coin,
                    category: category,
                    dataSymbol: dataSymbol,
                    _token: "{{ csrf_token() }}"
                },
                success: function(resp) {
                    $(`div[data-coin='${coin}'][data-category='${category}'] i`).removeClass('far').addClass('fas');
                },
                error: function(xhr, status, error) {
                    console.error("Error adding to favorites: ", error);
                }
            });
        }
    </script>
@endpush
@push('style')
    <style>
        #market-nav {
            padding-top: 1rem;
        }

        #market-nav .nav-tabs {
            display: flex;
            justify-content: space-between;
            border-bottom: 0;
        }

        #market-nav .nav-link {
            padding: .2rem .5rem;
            border-radius: .2rem;
            cursor: pointer;
            color: white;
            border: none;
        }

        #market-nav .nav-link.active {
            background-color: #2a2e39;
            font-weight: 600;
        }

        .market-coin-item {
            border-bottom: 1px solid hsl(var(--white));
            border-left: 4px solid #0d1e23;
        }

        .market-coin-item:hover {
            border-left: 4px solid #f4ae23;
        }

        .market-coin-item a {
            color: hsl(var(--white));
            font-size: 16px;
        }

        .market-coin-item .coin-icon {
            height: 22px;
            width: 22px;
            background-color: red;
            margin-left: 5px;
            margin-right: 10px;
            border-radius: 50%;
        }

        .dropdown-container > .tab-pane > div {
            max-height: 455px;
            overflow-y: auto;
        }

        .dropdown-btn {
            padding: 8px;
            text-decoration: none;
            font-size: 20px;
            color: #818181;
            display: block;
            border-bottom: 1px solid hsl(var(--white));
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            outline: none;
        }

        /* On mouse-over */
        .dropdown-btn:hover {
            color: #f1f1f1;
        }

        /* Main content */
        .main {
            margin-left: 300px; /* Same as the width of the sidenav */
            font-size: 20px; /* Increased text to enable scrolling */
            padding: 0px 10px;
        }

        /* Add an active class to the active dropdown button */
        .active {
            color: white;
        }

        /* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
        .dropdown-container {
            display: none;
            /* padding-left: 8px; */
            width: 100%;
            text-align: left;  /* Adjust the maximum width as needed */
        }
        /* Optional: Style the caret down icon */
        .fa-caret-down {
            float: right;
            padding-right: 8px;
        }

        .arrow-indicator {
            margin-right: 5px; /* Adjust margin as needed */
            transition: transform 0.3s ease; /* Smooth transition for arrow rotation */
            color: hsl(var(--white)); /* Default arrow color */
        }

        .arrow-indicator.down {
            transform: rotate(90deg); /* Rotate the arrow when button is clicked */
        }

        .dropdown-btn.active .arrow-indicator {
            color: hsl(var(--white)); /* Active arrow color */
        }

        .title-text {
            font-size: 16px; 
            color: hsl(var(--white));
        }

        .price-text {
            width: 100px;
            text-align: left;
            font-size: 16px;
            color: hsl(var(--white));
        }

        .daily-change-text {
            width: 100px;
            text-align: left;
            font-size: 16px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: hsl(var(--white));
        }

        .toggle-col {
            display: none;
        }

        .dropdown-btn.active .toggle-col {
            display: inline;
        }

        .market-search {
            padding-top: 12px !important;
            padding-bottom: 12px !important;
            color: hsl(var(--white)) !important;
        }

        ::placeholder {
            color: hsl(var(--white)) !important;
            opacity: 1;
        }

        ::-ms-input-placeholder {
            color: hsl(var(--white)) !important;
        }

        .search-btn {
            color: hsl(var(--white)) !important;
        }
    </style>
@endpush