<style>
    .colstyle {
        box-shadow: 0px 0px 6px 0px #f6931d;
    }

    @php $primaryColor =SettingHelper::getSettingValueBySLug('site_primary_color');
    $secondaryColor =SettingHelper::getSettingValueBySLug('site_secondary_color');
    @endphp

    .page-item.active .page-link {
        z-index: 2;
        color: #fff;
        background-color: {{ $primaryColor }};
        border-color: {{ $primaryColor }};
    }

    .page-link:hover {
        color: #fff;
        background-color: {{ $primaryColor }};
        border-color: {{ $primaryColor }};
    }

    .header-inner-custom {
        background-color: {{ $primaryColor }};
    }

    .header.shop .header-inner {
        background-color: {{ $primaryColor }};
    }

    .footer {
        background-color: {{ $primaryColor }};
    }

    .header.sticky .header-inner .nav li a {
        color: #fff;
    }

    .shop-newsletter .newsletter-inner .btn {
        background-color: {{ $primaryColor }};
    }

    .midium-banner .single-banner a:hover {
        background-color: {{ $primaryColor }};
    }

    .hero-slider .hero-text .btn:hover {
        background-color: {{ $primaryColor }};
    }

    #scrollUp i {
        background: {{ $primaryColor }};
    }

    .btn {
        background: {{ $primaryColor }};
    }

    .quickview-content .add-to-cart .btn {
        background: {{ $primaryColor }};
    }

    .accordion-button:not(.collapsed) {
        color: #ffffff;
        background-color: {{ $primaryColor }};
    }

    @if ($secondaryColor)
        .header.shop .nav li.active a {
            color: #fff;
            background-color: {{ $secondaryColor }};
        }

        .header.shop .nav li:hover a {
            color: #fff;
            background-color: {{ $secondaryColor }};
        }

        .header.shop .nav li .dropdown li:hover a {
            color: #fff;
            background-color: {{ $secondaryColor }};
        }

        .footer .links ul li a:hover {
            padding-left: 10px;
            color: {{ $secondaryColor }};
        }

        .footer .about .call a {
            font-size: 20px;
            font-weight: 600;
            color: {{ $secondaryColor }};
        }

        .btn:hover {
            background: {{ $secondaryColor }};
        }

        #scrollUp i:hover {
            background: {{ $secondaryColor }};
        }

        .header.shop .top-left .list-main li i {
            color: {{ $secondaryColor }};
        }

        .header.shop .list-main li i {
            color: {{ $secondaryColor }};
        }

        .header.shop .right-bar .sinlge-bar .single-icon .total-count {
            background: {{ $secondaryColor }};
        }

        .single-product .product-content h3 a:hover {
            color: {{ $secondaryColor }};
        }

        .section-title h2::before {
            background: {{ $secondaryColor }};
        }

        .shopping-summery thead {
            background: {{ $secondaryColor }};
        }

        .header.shop .all-category {
            color: #fff;
            background: transparent;
            position: relative;
            background-color: {{ $secondaryColor }};
        }

        .shop-newsletter .newsletter-inner .btn:hover {
            color: #fff;
            background-color: {{ $secondaryColor }};
        }

        .midium-banner .single-banner a {
            background-color: {{ $secondaryColor }};
        }

        .hero-slider .hero-text .btn {
            background-color: {{ $secondaryColor }};
        }

        .contact-us .single-info i {
            background-color: {{ $secondaryColor }};
        }

        .colstyle {
            box-shadow: 0px 0px 6px 0px {{ $secondaryColor }};
        }
    @endif
</style>
