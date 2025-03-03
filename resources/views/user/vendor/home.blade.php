@extends('layouts.vendor')
@section('title')
    Vendor || Dasboard
@endsection
@section('style')
    <style>
        @media (max-width: 600px) {
            img.customsize {
                width: 80px !important;
                height: 80px !important;
                margin-top: 50px;
            }

            .custumh4 {
                /*font-size: 4vh !important;*/
                font-size: 20px;
            }

            .banner {
                height: 218px !important;
            }
        }

        img.customsize {
            width: 150px;
            height: 150px;
        }

        .custumh4 {
            font-size: 18px;
            margin: 0;
        }

        /*.banner {*/
        /*    height: 460px;*/
        /*}*/
    </style>
@endsection
@section('content')
    <div class="main-content" style=" padding-right: 30px; padding-top: 80px;">
        <section class="section">
            <div class="section-body">
                <div class="row">

                    <div class="col-12 col-md-12 col-lg-12 banner">
                        <div style="display:block">

                            @if (!count($dashboardbanner))
                                <!--banner-->
                                <img alt="image" src="{{ asset('images/user-panel-banner-original.jpeg') }}" class=""
                                    width="auto" height="auto" style="max-width:100%;">
                            @else
                                <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">

                                    @php $count = 1 @endphp
                                    <ol class="carousel-indicators">
                                        @for ($i = 0; $i < count($dashboardbanner); $i++)
                                            <li data-target="#carouselExampleIndicators2"
                                                data-slide-to="{{ $count }}"
                                                @if ($count == 1) class="active" @endif></li>
                                            @php $count++; @endphp
                                        @endfor
                                    </ol>


                                    <div class="carousel-inner">
                                        @php $counter = 1 @endphp
                                        @foreach ($dashboardbanner as $image)
                                            <div class="carousel-item @if ($counter == 1) active @endif">
                                                <img class="d-block w-100" src="{{ $image->getFirstMediaUrl('images') }}"
                                                    alt="{{ $counter }}" style="max-width:100%;">
                                            </div>
                                            @php $counter++; @endphp
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button"
                                        data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button"
                                        data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            @endif
                        </div><br />
                        <div class="author-box-center" style="top: -75px;position: relative; display: flex;">
                            <div>
                                <img alt="image"
                                    src="{{ Auth::guard('web')->user()->image ? asset('uploads/user_profile') . '/' . Auth::guard('web')->user()->image : asset('img/users/user-3.png') }}"
                                    class="rounded-circle rounded-circle customsize">
                            </div>
                            <div style="margin: 45px 0 0 10px;">
                                <div class="author-box-name">
                                    <p class="mt-2 custumh4"><b>{{ Auth::guard('web')->user()->name }}</b>
                                        {{ Auth::guard('web')->user()->userpoint ? (Auth::guard('web')->user()->userpoint->commission ? '(' . Auth::guard('web')->user()->userpoint->commission->title . ')' : '') : '' }}
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-12">
                        <div class="card ">
                            <div class="card-header">
                                <h4>Revenue chart</h4>
                                <div class="card-header-action">

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <div id="lineChart"></div>
                                        <div class="row mt-2">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <div class="list-inline text-center">
                                                    <div class="list-inline-item p-r-30">
                                                        <i data-feather="arrow-up-circle" class="col-green"></i>
                                                        <h5 class="m-b-0">{{ $earning['month'] ? $earning['month'] : 0 }}
                                                        </h5>
                                                        <p class="text-muted font-14 m-b-0">Monthly Sales</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <div class="list-inline text-center">
                                                    <div class="list-inline-item p-r-30">
                                                        <i data-feather="arrow-up-circle" class="col-green"></i>
                                                        <h5 class="m-b-0">{{ $earning['total'] ? $earning['total'] : 0 }}
                                                        </h5>
                                                        <p class="text-muted font-14 m-b-0">Total Sales</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <div class="list-inline text-center">
                                                    <div class="list-inline-item p-r-30">
                                                        <i data-feather="arrow-up-circle" class="col-green"></i>
                                                        <h5 class="m-b-0">
                                                            {{ $earning['plateForm'] ? $earning['plateForm'] : 0 }}
                                                        </h5>
                                                        <p class="text-muted font-14 m-b-0">Platform</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <div class="list-inline text-center">
                                                    <div class="list-inline-item p-r-30">
                                                        <i data-feather="arrow-up-circle" class="col-green"></i>
                                                        <h5 class="m-b-0">
                                                            {{ $earning['outstanding'] ? $earning['outstanding'] : 0 }}
                                                        </h5>
                                                        <p class="text-muted font-14 m-b-0">Outstanding Amount</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 text-center">
                                        <div class="card card-primary">
                                            <div class="card-header text-center"
                                                style="display:block; padding:6px; color: #212529;">
                                                <h6>Pending Orders</h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h4> {{ $order['pending']->count }} </h4>
                                                    </div>
                                                    <div class="col-6">
                                                        <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-primary">
                                            <div class="card-header text-center"
                                                style="display:block; padding:6px;  color: #212529;">
                                                <h6>Inprocess Orders</h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h4> {{ $order['inprocess']->count }} </h4>
                                                    </div>
                                                    <div class="col-6">
                                                        <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-primary">
                                            <div class="card-header text-center"
                                                style="display:block; padding:6px;     color: #212529;">
                                                <h6>Approved Orders</h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h4> {{ $order['approved']->count }} </h4>
                                                    </div>
                                                    <div class="col-6">
                                                        <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-primary">
                                            <div class="card-header text-center"
                                                style="display:block; padding:6px;     color: #212529;">
                                                <h6>Delivered Orders</h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h4> {{ $order['delivered']->count }} </h4>
                                                    </div>
                                                    <div class="col-6">
                                                        <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="card card-primary">
                                            <div class="card-header text-center"
                                                style="display:block; padding:6px; color: #212529;">
                                                <h6>Reject Orders</h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h4> {{ $order['cancelled']->count }} </h4>
                                                    </div>
                                                    <div class="col-6">
                                                        <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="card card-primary">
                                            <div class="card-header text-center"
                                                style="display:block; padding:6px;     color: #212529; color: #212529;">
                                                <h4>Total Orders</h4>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h4> {{ $order['total']->count }} </h4>
                                                    </div>
                                                    <div class="col-6">
                                                        <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <!-- JS Libraies -->
    <script src="{{ asset('bundles/amcharts4/core.js') }}"></script>
    <script src="{{ asset('bundles/amcharts4/charts.js') }}"></script>
    <script src="{{ asset('bundles/amcharts4/animated.js') }}"></script>
    <script src="{{ asset('bundles/amcharts4/worldLow.js') }}"></script>
    <script src="{{ asset('bundles/amcharts4/maps.js') }}"></script>
    <script src="{{ asset('js/page/chart-amchart.js') }}"></script>
    <script>
        $(function() {

            $.ajax({
                url: "{{ route('vendor.revenue') }}",
                type: 'GET',
                dataType: 'json',
                // data: {
                //     "id": id,
                //     "_token": token,
                // },
                beforeSend: function() {
                    $(".loader").show();
                },
                complete: function() {
                    $(".loader").hide();
                },
                success: function(response) {
                    if (response) {
                        lineChart(response);
                    }
                }
            });


            function lineChart($value) {
                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end

                // Create chart instance
                var chart = am4core.create("lineChart", am4charts.XYChart);

                // Add data
                chart.data = $value;

                // Create axes
                var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                dateAxis.renderer.labels.template.fill = am4core.color("#9aa0ac");
                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.labels.template.fill = am4core.color("#9aa0ac");

                // Create series
                var series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "value";
                series.dataFields.dateX = "date";
                series.tooltipText = "{value}"
                series.strokeWidth = 2;
                series.minBulletDistance = 15;

                // Drop-shaped tooltips
                series.tooltip.background.cornerRadius = 20;
                series.tooltip.background.strokeOpacity = 0;
                series.tooltip.pointerOrientation = "vertical";
                series.tooltip.label.minWidth = 40;
                series.tooltip.label.minHeight = 40;
                series.tooltip.label.textAlign = "middle";
                series.tooltip.label.textValign = "middle";

                // Make bullets grow on hover
                var bullet = series.bullets.push(new am4charts.CircleBullet());
                bullet.circle.strokeWidth = 2;
                bullet.circle.radius = 4;
                bullet.circle.fill = am4core.color("#fff");

                var bullethover = bullet.states.create("hover");
                bullethover.properties.scale = 1.3;

                // Make a panning cursor
                chart.cursor = new am4charts.XYCursor();
                chart.cursor.behavior = "panXY";
                chart.cursor.xAxis = dateAxis;
                chart.cursor.snapToSeries = series;

                // Create vertical scrollbar and place it before the value axis
                chart.scrollbarY = new am4core.Scrollbar();
                chart.scrollbarY.parent = chart.leftAxesContainer;
                chart.scrollbarY.toBack();

                // Create a horizontal scrollbar with previe and place it underneath the date axis
                chart.scrollbarX = new am4charts.XYChartScrollbar();
                chart.scrollbarX.series.push(series);
                chart.scrollbarX.parent = chart.bottomAxesContainer;

                chart.events.on("ready", function() {
                    dateAxis.zoom();
                });

            }
        });
    </script>
@endsection
