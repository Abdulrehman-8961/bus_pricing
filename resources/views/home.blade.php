@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <i class="ti ti-search fs-8 me-3"></i><input type="text" class="form-control shawCalRanges"
                            name="dateRange" id="dateRange">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="fs-6 text-muted">Anfragen</p>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <p class="fs-8 fw-bolder text-dark">70</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="fs-6 text-muted">Angebote</p>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <p class="fs-8 fw-bolder text-dark">50</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="fs-6 text-muted">Rechnungen</p>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <p class="fs-8 fw-bolder text-dark">5</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted fs-6">Afragen</p>
                        <div id="chart_1"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted fs-6">Umsatz</p>
                        <div id="chart_2">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-dark fw-bolder fs-6">Saisonale Preisveranderung in den nachsten 3 Monaten (Seasonal
                            price changes in the next 3 months):</p>
                        @if (count($nextSeasonData) > 0)
                            @foreach ($nextSeasonData as $item)
                                <p>{{ date('d.m.Y', strtotime($item->start_zeitraum)) }} -
                                    {{ date('d.m.Y', strtotime($item->end_zeitraum)) }} {{ $item->name }}
                                    {{ $item->meldung }} + {{ $item->presierhohung }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(".shawCalRanges").daterangepicker();
        var series = {
            monthDataSeries1: {
                prices: [100, 110, 105, 120, 125, 130, 128, 135, 140, 138, 145, 150], // Sample price data
                dates: ['2023-01-01', '2023-02-01', '2023-03-01', '2023-04-01', '2023-05-01', '2023-06-01',
                    '2023-07-01', '2023-08-01', '2023-09-01', '2023-10-01', '2023-11-01', '2023-12-01'
                ] // Sample date labels corresponding to the prices
            }
        };
        console.log(series);
        var options = {
            series: [{
                name: "",
                data: series.monthDataSeries1.prices
            }],
            chart: {
                type: 'area',
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            // title: {
            //     text: 'Fundamental Analysis of Stocks',
            //     align: 'left'
            // },
            // subtitle: {
            //     text: 'Afragen',
            //     align: 'left'
            // },
            labels: series.monthDataSeries1.dates,
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                opposite: true
            },
            legend: {
                horizontalAlign: 'left'
            },
            colors: ['#5cb85c']
        };

        var chart = new ApexCharts(document.querySelector("#chart_1"), options);
        var chart2 = new ApexCharts(document.querySelector("#chart_2"), options);
        chart.render();
        chart2.render();
    </script>
@endsection
