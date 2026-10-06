@extends('layouts.landing.app')
@section('title', translate('messages.deliveryman_registration'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset('public/assets/landing') }}/css/style.css"/>
    <link rel="stylesheet" href="{{ dynamicAsset('public/assets/landing') }}/css/shift-multi-select.css"/>
@endpush

@section('content')
    <!-- Page Header Gap -->
    <div class="h-148px"></div>
    <!-- Page Header Gap -->

    <section class="m-0">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h1 class="page-header-title text-center"><i class="tio-add-circle-outlined"></i>
                            {{ translate('messages.deliveryman_application') }}</h1>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
                <div class="card shadow-sm col-12">
                    <form class="card-body" id="deliveryman-form" method="post" enctype="multipart/form-data">
                        @csrf
                        <small class="nav-subtitle">{{ translate('messages.deliveryman_info') }}</small>
                        <br>
                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.first_name') }}<small
                                            class="text-danger">*</small></label>
                                    <input type="text" name="f_name" class="form-control"
                                        placeholder="{{ translate('messages.first_name') }}" required
                                        value="{{ old('f_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.last_name') }}<small
                                            class="text-danger">*</small></label>
                                    <input type="text" name="l_name" class="form-control"
                                        placeholder="{{ translate('messages.last_name') }}" value="{{ old('l_name') }}"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.email') }}<small
                                            class="text-danger">*</small></label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="{{ translate('messages.Ex :') }} ex@example.com"
                                        value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.deliveryman_type') }}</label>
                                    <select name="earning" class="form-control">
                                        <option value="1">{{ translate('messages.freelancer') }}</option>
                                        <option value="0">{{ translate('messages.salary_based') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.zone') }}<small
                                            class="text-danger">*</small></label>
                                    <select name="zone_id" class="form-control js-select2-custom" required
                                        data-placeholder="{{ translate('messages.select_zone') }}">
                                        <option value="" readonly="true" hidden="true">
                                            {{ translate('messages.select_zone') }}</option>
                                        @foreach (\App\Models\Zone::active()->get(['id', 'name']) as $zone)
                                            @if (isset(auth('admin')->user()->zone_id))
                                                @if (auth('admin')->user()->zone_id == $zone->id)
                                                    <option value="{{ $zone->id }}" selected>{{ $zone->name }}
                                                    </option>
                                                @endif
                                            @else
                                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.Vehicle') }}<small
                                            class="text-danger">*</small></label>
                                    <select name="vehicle_id" class="form-control js-select2-custom h--45px" required
                                        data-placeholder="{{ translate('messages.select_vehicle') }}">
                                        <option value="" readonly="true" hidden="true">
                                            {{ translate('messages.select_vehicle') }}</option>
                                        @foreach (\App\Models\Vehicle::where('status', 1)->get(['id', 'type']) as $v)
                                            <option value="{{ $v->id }}">{{ $v->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-12" id="shift-view">
                                <div class="form-group pb-1">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{ translate('messages.Working Shift') }}<small
                                            class="text-danger">*</small></label>
                                    @php
                                        $shifts_translations = [
                                            'full_day' => translate('messages.full_day'),
                                            'select_shifts' => translate('messages.select_shifts'),
                                            'salary_text' => translate(
                                                'Salary based delivery men work according to their contract/assigned hours.',
                                            ),
                                            'full_day_text' => translate(
                                                'You will receive delivery orders 24/7.',
                                            ),
                                            'specific_shift_text_1' => translate(
                                                'You will only receive delivery orders during the',
                                            ),
                                            'specific_shift_text_2' => translate(
                                                'shifts. Orders outside these time slots will not be received.',
                                            ),
                                            'no_shift_text' => translate(
                                                'Please select a shift to see availability.',
                                            ),
                                        ];
                                    @endphp
                                    <div class="multi-select-container"
                                        data-translations='{{ json_encode($shifts_translations) }}'>
                                        <div class="select-box">
                                            <div class="tags-container"></div>
                                            <span class="arrow"><i class="tio-down-ui fs-10"></i></span>
                                        </div>
                                        <div class="dropdown-list">
                                            @foreach (\App\Models\Shift::orderBy('is_full_day', 'desc')->get() as $shift)
                                                <label
                                                    class="option-item {{ $shift->is_full_day ? 'full-day-wrapper' : '' }}">
                                                    <span>
                                                        {{ $shift->name }}
                                                        @unless ($shift->is_full_day)
                                                            ({{ \App\CentralLogics\Helpers::time_format($shift->start_time) }}
                                                            -
                                                            {{ \App\CentralLogics\Helpers::time_format($shift->end_time) }})
                                                        @endunless
                                                    </span>
                                                    <input type="checkbox" name="shifts[]"
                                                        class="{{ $shift->is_full_day ? 'full-day-checkbox' : 'slot-checkbox' }}"
                                                        value="{{ $shift->id }}"
                                                        data-name="{{ $shift->name }}"
                                                        data-time-range="{{ \App\CentralLogics\Helpers::time_format($shift->start_time) }} - {{ \App\CentralLogics\Helpers::time_format($shift->end_time) }}"
                                                        {{ $shift->is_full_day ? 'checked' : '' }}>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity_type') }}</label>
                                    <select name="identity_type" class="form-control">
                                        <option value="passport">{{ translate('messages.passport') }}</option>
                                        <option value="driving_license">{{ translate('messages.driving_license') }}
                                        </option>
                                        <option value="nid">{{ translate('messages.nid') }}</option>
                                        <option value="restaurant_id">{{ translate('messages.restaurant_id') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="alert py-2 bg-warning-soft rounded" role="alert">
                            <i class="tio-info fs-16 text-warning"></i>
                            <span class="mb-0 fs-13" id="shift-info-text">
                                {{ translate('Please select a shift to see availability.') }}
                            </span>
                        </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity_number') }}
                                        <small class="text-danger">*</small></label>
                                    <input type="text" name="identity_number" class="form-control"
                                        value="{{ old('identity_number') }}"
                                        placeholder="{{ translate('messages.Ex :') }} DH-23434-LS" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity_image') }}</label>
                                    <div>
                                        <div class="row" id="coba"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @include('partials._custom-inputs')


                        <small class="nav-subtitle text-capitalize">{{ translate('messages.login_info') }}</small>
                        <br>
                        <div class="row mt-3">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label" for="phone">{{ translate('messages.phone') }}<small
                                            class="text-danger">*</small></label>
                                    <div class="input-group">
                                        <input type="tel" id="phone"
                                            placeholder="{{ translate('messages.Ex :') }} 017********"
                                            class="form-control" value="{{ old('phone') }}" required>
                                        <input type="hidden" name="phone" id="phone_hidden">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.password') }}<small
                                            class="text-danger">*</small>
                                        <span class="input-label-secondary ps-1"
                                            title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img
                                                src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                    </label>

                                    <input type="text" id="password" name="password" class="form-control"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                        title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                        placeholder="{{ translate('messages.Ex :') }} Abc@1234"
                                        value="{{ old('password') }}" required>

                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img class="initial-95" style="max-width: 130px" id="viewer"
                                            src="{{ dynamicAsset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.deliveryman_image') }}<small
                                            class="text-danger">* ( {{ translate('messages.ratio') }} 1:1
                                            )</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="form-control"
                                            accept="{{ IMAGE_EXTENSION }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 col-12">
                                @php($recaptcha = \App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                                @if (isset($recaptcha) && $recaptcha['status'] == 1)
                                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                                    <input type="hidden" name="set_default_captcha" id="set_default_captcha_value"
                                        value="0">
                                    <div class="row p-2 d-none" id="reload-captcha">
                                        <div class="col-6 pr-0">
                                            <input type="text" class="form-control form-control-lg border-0"
                                                name="custome_recaptcha" id="custome_recaptcha"
                                                placeholder="{{ translate('Enter recaptcha value') }}" autocomplete="off"
                                                value="{{ getEnvMode() == 'dev' ? session('six_captcha') : '' }}">
                                        </div>
                                        <div class="col-6 bg-white rounded d-flex">
                                            <img src="<?php echo $custome_recaptcha->inline(); ?>" class="rounded w-100" />
                                            <div class="p-3 pr-0 capcha-spin reloadCaptcha">
                                                <i class="tio-cached"></i>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <div class="row p-2" id="reload-captcha">

                                            <div class="col-6 pr-0">
                                                <input type="text" class="form-control form-control-lg form-recapcha"
                                                    name="custome_recaptcha" id="custome_recaptcha"
                                                    placeholder="{{ translate('Enter recaptcha value') }}"
                                                    autocomplete="off"
                                                    value="{{ getEnvMode() == 'dev' ? session('six_captcha') : '' }}">
                                            </div>
                                            <div class="col-6 bg-white rounded d-flex">
                                                <img src="<?php echo $custome_recaptcha->inline(); ?>" class="rounded w-100" />
                                                <div class="p-3 pr-0 capcha-spin reloadCaptcha">
                                                    <i class="tio-cached"></i>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endif

                            </div>
                        </div>


                        <div class="text-end pt-4 d-flex flex-wrap justify-content-end gap-3">
                            <button type="reset" id='reset-btn'
                                    class="btn btn--reset ">{{ translate('Reset') }}
                            </button>
                            <button type="submit"
                                class="btn btn-primary px-4 float-right submitBtn">{{ translate('messages.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- Page Header Gap -->
    <div class="h-148px"></div>
    <!-- Page Header Gap -->
@endsection

@push('script_2')

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        </script>
    @endif

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
        });

            $('#deliveryman-form').on('submit', function (e) {
                e.preventDefault();
                // Capture phone value from intlTelInput
                const phoneInput = document.getElementById('phone');
                const phoneHiddenInput = document.getElementById('phone_hidden');
                const intlTelInputInstance = window.intlTelInputGlobals.getInstance(phoneInput);
                if (intlTelInputInstance) {
                    phoneHiddenInput.value = intlTelInputInstance.getNumber();
                } else {
                    phoneHiddenInput.value = phoneInput.value;
                }
                let formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{route('deliveryman.store')}}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        if (data.errors) {
                            $('#loading').hide();
                            for (let i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            $('#loading').hide();
                            toastr.success('{{ translate('application_placed_successfully') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        }
                    }
            });
        });
    </script>

    <script src="{{ dynamicAsset('public/assets/landing') }}/js/shift-multi-select.js"></script>
    <script src="{{ dynamicAsset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: {{ MAX_FILE_SIZE * 1024 * 1024 }},
                placeholderImage: {
                    image: '{{ dynamicAsset('public/assets/admin/img/400x400/img2.jpg') }}',
                    width: '70%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {
                    const item = $('#coba')
                        .find('.spartan_item_wrapper')
                        .eq(index);
                    item.css({
                        width: '13%',
                    });

                    item.find('img').css({
                        width: '100%',
                        objectFit: 'cover'
                    });
                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error(
                    '{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ translate('messages.file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>

    @if (isset($recaptcha) && $recaptcha['status'] == 1)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptcha['site_key'] }}"></script>
    @endif
    @if (isset($recaptcha) && $recaptcha['status'] == 1)
        <script>
            $(document).ready(function() {
                $('#signInBtn').click(function(e) {
                    if ($('#set_default_captcha_value').val() == 1) {
                        $('#contact-form-id').submit();
                        return true;
                    }
                    e.preventDefault();
                    if (typeof grecaptcha === 'undefined') {
                        toastr.error(
                            'Invalid recaptcha key provided. Please check the recaptcha configuration.');
                        $('#reload-captcha').removeClass('d-none');
                        $('#set_default_captcha_value').val('1');

                        return;
                    }
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ $recaptcha['site_key'] }}', {
                            action: 'submit'
                        }).then(function(token) {
                            $('#g-recaptcha-response').value = token;
                            $('#contact-form-id').submit();
                        });
                    });
                    window.onerror = function(message) {
                        var errorMessage =
                            'An unexpected error occurred. Please check the recaptcha configuration';
                        if (message.includes('Invalid site key')) {
                            errorMessage =
                                'Invalid site key provided. Please check the recaptcha configuration.';
                        } else if (message.includes('not loaded in api.js')) {
                            errorMessage =
                                'reCAPTCHA API could not be loaded. Please check the recaptcha API configuration.';
                        }
                        $('#reload-captcha').removeClass('d-none');
                        $('#set_default_captcha_value').val('1');
                        toastr.error(errorMessage)
                        return true;
                    };
                });
            });
        </script>
    @endif
    <script>
        $(document).on('click', '.reloadCaptcha', function() {
            $.ajax({
                url: "{{ route('reload-captcha') }}",
                type: "GET",
                dataType: 'json',
                beforeSend: function() {
                    $('#loading').show()
                    $('.capcha-spin').addClass('active')
                },
                success: function(data) {
                    $('#reload-captcha').html(data.view);
                },
                complete: function() {
                    $('#loading').hide()
                    $('.capcha-spin').removeClass('active')
                }
            });
        });
    </script>
@endpush
