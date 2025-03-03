@extends('layouts.eshop')
@section('style')
@endsection

@section('content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs bgImage d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center align-items-center">
                    <a href="#">Contact us</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Contact -->
    <section id="contact-us" class="contact-us section">
        <div class="container">
            <div class="single-head row">
                <div class="single-info col-md-12">
                    We encouraged you to get in touch if you face any issue & you did not find the answers. we were looking
                    for here ABF customer service. we value your opinion & we look forward to hearing from you.
                </div>
                <div class="single-info col-md-4 col-sm-12 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa fa-phone"></i>
                    <h4 class="title">Call us Now:</h4>
                    <ul>
                        <li>
                            <a
                                href="tel:{{ SettingHelper::getSettingValueBySLug('site_phone') ? SettingHelper::getSettingValueBySLug('site_phone') : env('APP_PHONE') }}">{{ SettingHelper::getSettingValueBySLug('site_phone') ? SettingHelper::getSettingValueBySLug('site_phone') : env('APP_PHONE') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="single-info col-md-4 col-sm-12 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa fa-envelope-open"></i>
                    <h4 class="title">Email:</h4>
                    <ul>
                        <li class="text-center">
                            <a
                                href="mailto:{{ SettingHelper::getSettingValueBySLug('site_email') ? SettingHelper::getSettingValueBySLug('site_email') : env('MAIL_FROM_ADDRESS') }}">{{ SettingHelper::getSettingValueBySLug('site_email') ? SettingHelper::getSettingValueBySLug('site_email') : env('MAIL_FROM_ADDRESS') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="single-info col-md-4 col-sm-12 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa fa-location-arrow"></i>
                    <h4 class="title">Our Address:</h4>
                    <ul>
                        <li class="text-center px-4">
                            ABF Cosmetics Lahore, Pakistan
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Contact -->
@endsection

@section('script')
@endsection
