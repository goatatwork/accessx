@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/dhcp">Dhcp</a></li>
        <li class="breadcrumb-item active" aria-current="page">DHCP LEASES FILE</li>
    </ol>
</nav>

<div class="row mb-3">
    <div class="col">
        <h4>Current contents of the <span class="font-italic">dnsmasq.leases</span> file...</h4>
    </div>
</div>

<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                {{ $leases }}
            </div>
        </div>
    </div>
</div>

@endsection
