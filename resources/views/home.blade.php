@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>

</div>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul class="list-unstyled">
                        <li>You have...</li>
                        <li>
                            {{ $stats['customers_count'] }} customers.
                            <a href="/customers">SHOW ME THE CUSTOMERS</a>
                        </li>
                        <li>
                            {{ $stats['aggregators_count'] }} aggregators.
                            <a href="/infrastructure/aggregators">SHOW ME THE AGGREGATORS</a>
                        </li>
                        <li>
                            {{ $stats['dhcp_shared_networks_count'] }} DHCP shared networks.
                            <a href="/dhcp">SHOW ME THE SHARED NETWORKS</a>
                        </li>
                        <li>
                            {{ $stats['onts_count'] }} ONTs.
                            <a href="/onts">SHOW ME THE ONTs</a>
                        </li>
                        <li>
                            {{ $stats['provisioning_records_count'] }} Provisioning Records.
                            <a href="/provisioning">SHOW ME THE PROVISIONING RECORDS</a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
