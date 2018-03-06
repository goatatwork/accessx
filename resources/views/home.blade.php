@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>

    <div class="row justify-content-center">

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

    </div>
</div>

@endsection
