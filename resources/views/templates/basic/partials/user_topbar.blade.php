@php
    $user = auth()->user();
@endphp
<div class="dashboard-header">
    <div class="dashboard-header__inner">
        <div class="dashboard-header__left">
           
        </div>
        <div class="dashboard-header__right">
            {{-- <button type="button" class="btn btn--success outline flex-fill btn--sm depositBtn"> --}}
            {{-- <button type="button" class="btn btn--success outline btn--sm trade-btn deposit-btn"
                data-target="#depositModal">
                <span class="icon-deposit"></span> @lang('Deposit')
            </button> --}}
            <a href="{{ route('trade') }}" target="_blank" class="btn btn--base outline btn--sm trade-btn" style="padding: 15px 100px">
                <span class="icon-trade"></span> @lang('TRADE')
            </a>
            @if ($general->multi_language)
                @php
                    $langDetails = $languages->where('code', config('app.locale'))->first();
                @endphp
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
            @endif
            <div class="user-info">
                <div class="user-info__right">
                    <div class="user-info__button">
                        <div class="user-info__profile">
                            <p class="user-info__name">{{ __($user->fullname) }}</p>
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
@push('script')
    <script>
        "use strict";
        (function($) {
            $('.custom--dropdown > .custom--dropdown__selected').on('click', function () {
                  $(this).parent().toggleClass('open');
            });

            $('.change-lang').on('click', function(e) {
                let langCode = $(this).data('code');
                window.location.href = "{{ route('home') }}/change/" + langCode;
            });
            
        })(jQuery);
    </script>
@endpush
{{-- <div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="mb-0 fs-18" id="depositLabel">@lang('Deposit Money')</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="right-sidebar mt-3">
                    <div class="right-sidebar__header mb-3 skeleton">
                        <p class="mt-0 fs-12">@lang('Make crypto & fiat deposits in a few steps')</p>
                    </div>
                    <div class="right-sidebar__deposit">
                        <form class="skeleton deposit-form">
                            <div class="form-group position-relative" id="currency_list_wrapper">
                                <div class="input-group">
                                    <input type="number" step="any" name="amount"
                                        class="form--control form-control" placeholder="@lang('Amount')">
                                    <div class="input-group-text skeleton">
                                        <x-currency-list :action="route('user.currency.all')" valueType="2" logCurrency="true" />
                                    </div>
                                </div>
                            </div>
                            <button class="deposit__button btn btn--base w-100" type="submit">
                                <span class="icon-deposit"></span> @lang('Deposit')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
{{-- @push('script')
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <script>
        // // Add event listeners to all edit buttons
        // (function ($) {
        //     "use strict";
        try {
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.deposit-btn').forEach(function(button) {
                    button.addEventListener('click', function() {
                        // const comment = button.getAttribute('data-comment');
                        // const userId = button.getAttribute('data-userid');
                        // document.getElementById('userComment').value = comment;
                        // document.getElementById('commentUserId').value = userId;
                        // const form = document.getElementById('editCommentForm');
                        // form.action = '/admin/users/update-comment/' + userId;
                        const modal = new bootstrap.Modal(document.getElementById(
                            'depositModal'), {});
                        modal.show();
                    });
                });
            });
        } catch (e) {
            console.error(e);
        }
    </script>
@endpush --}}

{{-- @push('script')
    <script>
        "use strict";
        (function($) {
            
            $('.change-lang').on('click', function(e) {
                let langCode = $(this).data('code');
                window.location.href = "{{ route('home') }}/change/" + langCode;
            });


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

            // $(".other-user-transfer input[name=transfer_amount]").on('input change', function() {

            //     const amount = parseFloat($(this).val());

            //     if (!amount || amount <= 0) {
            //         $(".other-user-transfer").find('.transfer-details').addClass('d-none');
            //         return;
            //     }

            //     const chargePercent = parseFloat("{{ '$transferCharge' }}");
            //     const chargeAmount  = (amount / 100) * chargePercent;
            //     const totalAmount   = amount + chargeAmount;

            //     $(".other-user-transfer").find('.transfer-amount').text(getAmount(amount));
            //     $(".other-user-transfer").find('.transfer-charge').text(getAmount(chargeAmount));
            //     $(".other-user-transfer").find('.transfer-total-amount').text(getAmount(totalAmount));
            //     $(".other-user-transfer").find('.transfer-details').removeClass('d-none');
            // });

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
            
        })(jQuery);
    </script>
@endpush --}}

@push('style')
    <style>
        .wallet-currency img {
            width: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .wallet-ballance {
            background-color: #09171a;
        }

        .offcanvas {
            padding: 30px;
        }

        .custom--tab {
            justify-content: flex-start;
            border-radius: 0;
            border-bottom: 2px solid hsl(var(--white)/0.1);
            border-radius: 4px;
            padding: 10px;
            padding-bottom: 15px;
            margin-bottom: 0px !important;
            margin-bottom: 25px !important;
            background-color: #0d2227;
        }

        .custom--tab .nav-item {
            padding: 0;
            width: 50%;
            display: flex;
            justify-content: center;
            cursor: pointer;
        }

        .custom--tab .nav-item .nav-link {
            background-color: transparent !important;
            border-radius: 0;
            border: 0 !important;
            padding: 0 50px !important;
            position: relative;
            font-size: 1rem;
            font-weight: 600;
        }

        .custom--tab .nav-item .nav-link.active::before {
            position: absolute;
            content: "";
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 2px;
            background-color: hsl(var(--base));
            display: none;
            font-weight: normal;
        }

        .custom--tab .nav-item .nav-link::after {
            position: absolute;
            content: "";
            bottom: -17px;
            left: 0;
            width: 0;
            height: 1px;
            background-color: hsl(var(--base)) !important;
        }

        .custom--tab .nav-item .nav-link.active {
            color: hsl(var(--base)) !important;
            background-color: transparent !important;
        }

        .custom--tab .nav-item .nav-link.active.nav-link::after {
            width: 100%;
        }

        .custom--tab .nav-item .nav-link.active:hover {
            color: hsl(var(--base)) !important;
        }

        @media screen and (max-width:991px) {
            .offcanvas {
                padding: 20px;
            }

            .custom--tab .nav-item .nav-link {
                padding: 0 20px !important;
            }
        }

        @media screen and (max-width:991px) {
            .offcanvas {
                padding: 15px;
            }

            .custom--tab .nav-item .nav-link {
                padding: 0 10px !important;
                font-size: 15px;
            }
        }
    </style>
@endpush
