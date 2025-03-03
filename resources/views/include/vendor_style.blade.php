<style>
    span#countunreadnotification {
        position: absolute;
        top: 4px;
        right: 0px;
        font-weight: 300;
        padding: 3px 6px;
        border-radius: 10px;
    }

    .theme-white .navbar {
        background-color: #50C878;
    }

    .theme-white .navbar .nav-link .feather {
        color: #ffffff;
    }

    .theme-white a {
        color: #50C878;
    }

    .theme-white .text-primary {
        color: #50C878 !important;
    }

    .theme-white .selectgroup-input:focus+.selectgroup-button,
    .theme-white .selectgroup-input:checked+.selectgroup-button {
        background-color: #50C878;
    }

    .theme-white .btn-primary {
        background-color: #50C878;
    }

    .theme-white .btn-primary {
        background-color: #50C878;
    }

    .theme-white .primary {
        background-color: #50C878;
    }

    .theme-white .btn-primary:hover {
        background-color: #50C878 !important;
    }

    .theme-white .card.card-primary {
        border-top: 2px solid #50C878 !important;
    }

    .theme-white .form-control:focus {
        border-color: #50C878 !important;
    }

    .theme-white .btn-primary:active {
        background-color: #50C878 !important;
    }

    .theme-white .btn-primary:active {
        border-color: #50C878 !important;
    }

    .theme-white .btn-primary:focus:active {
        background-color: #50C878 !important;
    }

    .theme-white .btn-primary:focus {
        background-color: #50C878 !important;
    }

    .theme-white .custom-checkbox .custom-control-input:checked~.custom-control-label::after {
        background-color: #50C878 !important;
    }

    .badge.badge-primary {
        background-color: #50C878 !important;
    }

    .theme-white .page-item.active .page-link {
        color: #fff;
        background-color: #50C878 !important;
        border-color: #50C878 !important;
    }

    .theme-white .page-item.disabled .page-link {
        color: #50C878 !important;
    }



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
        background-color: #50C878 !important;
    }
</style>
