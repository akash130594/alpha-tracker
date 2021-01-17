@extends('internal.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('navs.frontend.dashboard')
                        - WELCOME to Alpha Tracker
                    </strong>
                </div><!--card-header-->
            </div>
        </div>
    </div>

    @php
        $month = date('m');
        $year = date('y');
        $dateRange = dates_month( $month, $year );
           $total = traffic_sum($dateRange);
    @endphp
    @php
        $fulcrum_stats = fulcrum_dates_stats($month,$dateRange);
        if($fulcrum_stats){
            $fulcrum_starts = array_sum(array_column($fulcrum_stats,'start'));
            $pl_stats = pl_dates_stats($month,$dateRange);
            $pl_starts = array_sum(array_column($pl_stats,'start'));
        }
    @endphp

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">Traffic</h4>
                    <div class="small text-muted">{{date('M Y')}}</div>
                </div>

                <div class="col-sm-7 d-none d-md-block">
                    <button class="btn btn-primary float-right" type="button">
                        <i class="icon-cloud-download"></i>
                    </button>
                    <div class="btn-group btn-group-toggle float-right mr-3" data-toggle="buttons">
                        <label class="btn btn-outline-secondary active">
                            <input id="option1" type="radio" name="options" autocomplete="off"> Day
                        </label>
                        <label class="btn btn-outline-secondary">
                            <input id="option2" type="radio" name="options" autocomplete="off" checked=""> Month
                        </label>
                        <label class="btn btn-outline-secondary">
                            <input id="option3" type="radio" name="options" autocomplete="off"> Year
                        </label>
                    </div>
                </div>
            </div>
            <div class="chart-wrapper" style="height:300px;margin-top:40px;">
                <canvas class="chart" id="main-chart" height="300"></canvas>
            </div>
        <div class="card-footer">
            <div class="row text-center">
                <div class="col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Starts</div>
                    <strong>{{$total?$total['start']:0}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Completes</div>
                    <strong>{{$total?$total['completes']:0}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Quotafull</div>
                    <strong>{{$total?$total['quotafull']:0}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Terminates</div>
                    <strong>{{$total?$total['terminates']:0}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Abandons</div>
                    <strong>{{$total?$total['abandons']:0}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
    <script src="{{asset('vendors/coreui-plugin-custom-tooltips/js/custom-tooltips.min.js')}}"></script>
    <script>
        Chart.defaults.global.pointHitDetectionRadius=1;
        Chart.defaults.global.tooltips.enabled=false;
        Chart.defaults.global.tooltips.mode='index';
        Chart.defaults.global.tooltips.position='nearest';
        Chart.defaults.global.tooltips.custom=CustomTooltips;
        var cardChart1=new Chart($('#card-chart1'), {
                type:'line', data: {
                    labels:JSON.parse('{!! json_encode($dateRange) !!}'), datasets:[ {
                        label: 'Starts', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($fulcrum_stats, 'start')) !!}')
                    },
                    {
                        label: 'Completes', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($fulcrum_stats, 'completes')) !!}')
                    },
                    {
                        label: 'Qutafull', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($fulcrum_stats, 'quotafull')) !!}')
                    },
                    {
                        label: 'Terminates', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($fulcrum_stats, 'terminates')) !!}')
                    },
                    {
                        label: 'Abandons', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($fulcrum_stats, 'abandons')) !!}')
                    }
                    ]
                }
                , options: {
                    maintainAspectRatio:false, legend: {
                        display: false
                    }
                    , scales: {
                        xAxes:[ {
                            gridLines: {
                                color: 'transparent', zeroLineColor: 'transparent'
                            }
                            , ticks: {
                                fontSize: 2, fontColor: 'transparent'
                            }
                        }
                        ], yAxes:[ {
                            display:false, ticks: {
                                display: false, min: 35, max: 89
                            }
                        }
                        ]
                    }
                    , elements: {
                        line: {
                            borderWidth: 1
                        }
                        , point: {
                            radius: 4, hitRadius: 10, hoverRadius: 4
                        }
                    }
                }
            }

        );
        var cardChart2=new Chart($('#card-chart2'), {
                type:'line', data: {
                    labels:JSON.parse('{!! json_encode($dateRange) !!}'), datasets:[ {
                    label: 'Starts', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($pl_stats, 'start')) !!}')
                },
                    {
                        label: 'Completes', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($pl_stats, 'completes')) !!}')
                    },
                    {
                        label: 'Qutafull', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($pl_stats, 'quotafull')) !!}')
                    },
                    {
                        label: 'Terminates', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($pl_stats, 'terminates')) !!}')
                    },
                    {
                        label: 'Abandons', backgroundColor: getStyle('--primary'), borderColor: 'rgba(255,255,255,.55)', data: JSON.parse('{!! json_encode(array_column($pl_stats, 'abandons')) !!}')
                    }
                    ]
                }
                , options: {
                    maintainAspectRatio:false, legend: {
                        display: false
                    }
                    , scales: {
                        xAxes:[ {
                            gridLines: {
                                color: 'transparent', zeroLineColor: 'transparent'
                            }
                            , ticks: {
                                fontSize: 2, fontColor: 'transparent'
                            }
                        }
                        ], yAxes:[ {
                            display:false, ticks: {
                                display: false, min: -4, max: 39
                            }
                        }
                        ]
                    }
                    , elements: {
                        line: {
                            tension: 0.00001, borderWidth: 1
                        }
                        , point: {
                            radius: 4, hitRadius: 10, hoverRadius: 4
                        }
                    }
                }
            }

        );
        @php
            $dates_stats = dates_stats($dateRange);
        @endphp

        var mainChart=new Chart($('#main-chart'), {
                type:'line', data: {
                    labels: JSON.parse('{!! json_encode($dateRange) !!}'), datasets:[ {
                            label: 'Starts', backgroundColor: hexToRgba(getStyle('--info'), 10), borderColor: getStyle('--info'), pointHoverBackgroundColor: '#fff', borderWidth: 2, data: JSON.parse('{!! json_encode(array_column($dates_stats, 'start')) !!}')
                        }
                        , {
                            label: 'Completes', backgroundColor: 'transparent', borderColor: getStyle('--success'), pointHoverBackgroundColor: '#fff', borderWidth: 2, data: JSON.parse('{!! json_encode(array_column($dates_stats, 'completes')) !!}')
                        }
                        , {
                            label: 'Qutafull', backgroundColor: 'transparent', borderColor: getStyle('--danger'), pointHoverBackgroundColor: '#fff', borderWidth: 1, borderDash: [8, 5], data: JSON.parse('{!! json_encode(array_column($dates_stats, 'quotafull')) !!}')
                        }, {
                            label: 'Terminates', backgroundColor: 'transparent', borderColor: getStyle('--danger'), pointHoverBackgroundColor: '#fff', borderWidth: 1, borderDash: [8, 5], data: JSON.parse('{!! json_encode(array_column($dates_stats, 'terminates')) !!}')
                        }, {
                            label: 'Abandons', backgroundColor: 'transparent', borderColor: getStyle('--danger'), pointHoverBackgroundColor: '#fff', borderWidth: 1, borderDash: [8, 5], data: JSON.parse('{!! json_encode(array_column($dates_stats, 'abandons')) !!}')
                        }
                    ]
                }
                , options: {
                    maintainAspectRatio:false, legend: {
                        display: false
                    }
                    , scales: {
                        xAxes:[ {
                            gridLines: {
                                drawOnChartArea: false
                            }
                        }
                        ], yAxes:[ {
                            ticks: {
                                beginAtZero: true, maxTicksLimit: 5, stepSize: Math.ceil(250/5), max: 250
                            }
                        }
                        ]
                    }
                    , elements: {
                        point: {
                            radius: 0, hitRadius: 10, hoverRadius: 4, hoverBorderWidth: 3
                        }
                    }
                }
            }

        );

    </script>
@endpush
