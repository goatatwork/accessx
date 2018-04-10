@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
</nav>

<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-body">

                <ul class="list-unstyled list-group list-group-flush">
                    <li>
                        @if ($stats['customers_count'] == 0)
                            There are no <a href="/customers" class="text-primary">customers</a> yet.
                        @elseif ($stats['customers_count'] == 1)
                            There is <span class="font-weight-bold">1</span> <a href="/customers" class="text-primary">customer</a>.
                        @elseif ($stats['customers_count'] > 0)
                            There are <span class="font-weight-bold">{{ $stats['customers_count'] }}</span> <a href="/customers" class="text-primary">customers</a>.
                        @endif
                    </li>

                    <li>
                        @if ($stats['service_locations_count'] == 0)
                            There are no service locations yet.
                        @elseif ($stats['service_locations_count'] == 1)
                            There is <span class="font-weight-bold">1</span> service location.
                        @elseif ($stats['service_locations_count'] > 0)
                            There are <span class="font-weight-bold">{{ $stats['service_locations_count'] }}</span> service locations.
                        @endif
                    </li>

                    <li>
                        @if ($stats['provisioning_records_count'] == 0)
                            There are no <a href="/provisioning">provisioning records</a> yet.
                        @elseif ($stats['provisioning_records_count'] == 1)
                            There is <span class="font-weight-bold">1</span> <a href="/provisioning">provisioning record</a>.
                        @elseif ($stats['provisioning_records_count'] > 0)
                            There are <span class="font-weight-bold">{{ $stats['provisioning_records_count'] }}</span> <a href="/provisioning">provisioning records</a>.
                        @endif
                    </li>

                    <li><hr></li>

                    <li class="p-3">
                        @if ($stats['aggregators_count'] == 0)
                            There are no aggregators yet.
                        @elseif ($stats['aggregators_count'] == 1)
                            @if ($stats['slots_count'] == 1)
                                There is <span class="font-weight-bold">1 aggregator</span> with <span class="font-weight-bold">1 slot</span> that has <span class="font-weight-bold">{{ $stats['ports_count'] }} ports</span>.
                            @else
                                There is <span class="font-weight-bold">1 aggregator</span> with <span class="font-weight-bold">{{ $stats['slots_count'] }} slots</span> making up a total of <span class="font-weight-bold">{{ $stats['ports_count'] }} ports</span>.
                            @endif
                        @elseif ($stats['aggregators_count'] > 0)
                            There are <span class="font-weight-bold">{{ $stats['aggregators_count'] }} aggregators</span> making up <span class="font-weight-bold">{{ $stats['slots_count'] }} slots</span> with a total of <span class="font-weight-bold">{{ $stats['ports_count'] }} ports</span>.
                        @endif
                    </li>

                    <li class="p-3">
                        @if ($stats['dhcp_subnets_count'] == 0)
                            There are no DHCP subnets yet.
                        @else
                            @if($stats['dhcp_subnets_count'] == 1)
                                There is <span class="font-weight-bold">1 DHCP subnet</span> totalling <span class="font-weight-bold">{{ $stats['dhcp_ip_addresses_count'] }} IP addresses</span>.
                            @else
                                There are <span class="font-weight-bold">{{ $stats['dhcp_subnets_count'] }} DHCP subnets</span> totalling <span class="font-weight-bold">{{ $stats['dhcp_ip_addresses_count'] }} IP addresses</span>.
                            @endif
                        @endif
                    </li>
                </ul>

            </div>
        </div>
    </div>

    <div class="col-3">
        <dnsmasq-server-status-card></dnsmasq-server-status-card>
    </div>

    <div class="col-3">
        <activity-logs-card :count="{{ $stats['activity_logs_count'] }}"></activity-logs-card>
    </div>

</div>

<!-- <div class="row justify-content-center">

    <div class="col-2">
        <div class="card">
            <a href="/customers" class="text-dark">
                <div class="card-header text-center">
                    <span class="fas fa-4x fa-users mb-3"></span><br>
                    <span class="h5">CUSTOMERS</span>
                </div>
            </a>
            <div class="card-body text-center">
                <span class="h2">{{ $stats['customers_count'] }}</span>
            </div>
        </div>
    </div>

    <div class="col-2">
        <div class="card">
            <a href="/provisioning" class="text-dark">
                <div class="card-header text-center">
                    <span class="fas fa-4x fa-sitemap mb-3"></span><br>
                    <span class="h5">PROVISIONED</span>
                </div>
            </a>
            <div class="card-body text-center">
                <span class="h2">{{ $stats['provisioning_records_count'] }}</span>
            </div>
        </div>
    </div>

    <div class="col-2">
        <div class="card">
            <a href="/infrastructure/aggregators" class="text-dark">
                <div class="card-header text-center">
                    <span class="fas fa-4x fa-server mb-3"></span><br>
                    <span class="h5">AGGREGATORS</span>
                </div>
            </a>
            <div class="card-body text-center">
                <span class="h2">{{ $stats['aggregators_count'] }}</span>
            </div>
        </div>
    </div>

    <div class="col-2">
        <div class="card">
            <a href="/dhcp" class="text-dark">
                <div class="card-header text-center">
                    <span class="fas fa-4x fa-cloud mb-3"></span><br>
                    <span class="h5">DHCP SUBNETS</span>
                </div>
            </a>
            <div class="card-body text-center">
                <span class="h2">{{ $stats['dhcp_subnets_count'] }}</span>
            </div>
        </div>
    </div>

    <div class="col-2">
        <div class="card">
            <a href="/onts" class="text-dark">
                <div class="card-header text-center">
                    <span class="fas fa-4x fa-hdd mb-3"></span><br>
                    <span class="h5">ONTs</span>
                </div>
            </a>
            <div class="card-body text-center">
                <span class="h2">{{ $stats['onts_count'] }}</span>
            </div>
        </div>
    </div>

</div> -->

<!-- <div class="row justify-content-center mt-5">

    <div class="col-2">
        <activity-logs-card :count="{{ $stats['activity_logs_count'] }}"></activity-logs-card>
    </div>

    <div class="col-2">
        <dnsmasq-server-status-card></dnsmasq-server-status-card>
    </div>

</div> -->

@endsection
