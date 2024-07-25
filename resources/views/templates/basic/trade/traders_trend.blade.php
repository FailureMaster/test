<div class="py-2 px-3">
    <div class="d-flex justify-content-between">
        <img src="{{ asset('assets/images/extra_images/bear.png') }}"/>
        <img src="{{ asset('assets/images/extra_images/bull.png') }}" class="mb-1" />
    </div>
    <div class="traders-trend">
        <div class="bear" style="width: 33%;"></div>
        <div class="bull" style="width: 67%;"></div>
    </div>
    <div class="d-flex justify-content-between">
        <span class="bear-pct">33%</span>
        <small class="traders-trend-title">@lang('Traders Trend')</small>
        <span class="bull-pct">67%</span>
    </div>
</div>
@push('script')
    <script>
        function updateTrend() {
            const bear = document.querySelector('.bear');
            const bull = document.querySelector('.bull');
            const bearPct = document.querySelector('.bear-pct');
            const bullPct = document.querySelector('.bull-pct');

            const bearPercentage = Math.floor(Math.random() * 101);
            const bullPercentage = 100 - bearPercentage;

            bear.style.width = bearPercentage + '%';
            bull.style.width = bullPercentage + '%';
            bearPct.textContent = bearPercentage + '%';
            bullPct.textContent = bullPercentage + '%';
        }

        updateTrend();
    </script>
@endpush
@push('style')
    <style>
        .traders-trend {
            display: flex;
            width: 100%;
            height: 5px;
            border: 1px solid #333;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
            background-color: #333;
        }

        .bear, .bull {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #fff;
            font-weight: bold;
        }

        .bear {
            background-color: #ff433d;
        }

        .bear-pct {
            color: #ff433d;
            font-weight: 600;
            font-size: 16px;
        }

        .bull {
            background-color: #1ecd93;
            position: absolute;
            right: 0;
            top: 0;
        }

        .bull-pct {
            color: #1ecd93;
            font-weight: 600;
            font-size: 16px;
        }

        .traders-trend::before {
            content: "Traders Trend";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            font-size: 0.8em;
            line-height: 30px;
        }
        
        .traders-trend-title {
            color: hsl(var(--white));
        }
    </style>
@endpush