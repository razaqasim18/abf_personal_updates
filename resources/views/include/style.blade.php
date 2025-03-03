<style>
    span#countunreadnotification {
        position: absolute;
        top: 4px;
        right: 0px;
        font-weight: 300;
        padding: 3px 6px;
        border-radius: 10px;
    }

    @if (!empty(SettingHelper::getSettingValueBySLug('site_primary_color')))
        .theme-white .navbar {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
        }

        .theme-white .navbar .nav-link .feather {
            color: #ffffff;
        }

        .theme-white a {
            color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
        }

        .theme-white .text-primary {
            color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .selectgroup-input:focus+.selectgroup-button,
        .theme-white .selectgroup-input:checked+.selectgroup-button {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
        }

        .theme-white .btn-primary {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
        }

        .theme-white .btn-primary {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
        }

        .theme-white .primary {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
        }

        .theme-white .btn-primary:hover {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .card.card-primary {
            border-top: 2px solid {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .form-control:focus {
            border-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .btn-primary:active {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .btn-primary:active {
            border-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .btn-primary:focus:active {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .btn-primary:focus {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .custom-checkbox .custom-control-input:checked~.custom-control-label::after {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .badge.badge-primary {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .page-item.active .page-link {
            color: #fff;
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
            border-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }

        .theme-white .page-item.disabled .page-link {
            color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }


    @endif

    @if (!empty(SettingHelper::getSettingValueBySLug('site_secondary_color')))
        .theme-white .btn-secondary {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
            color: #fff;
        }

        .theme-white .secondary {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
        }

        .theme-white .btn-secondary:hover {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }} !important;
        }

        .badge.badge-secondary {
            background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }} !important;
        }
    @endif
</style>
