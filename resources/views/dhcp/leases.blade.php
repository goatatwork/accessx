@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/dhcp">Dhcp</a></li>
        <li class="breadcrumb-item active" aria-current="page">DHCP LEASES FILE</li>
    </ol>
</nav>

<div class="row">
    <div class="col">

        <dhcp-leases :leases="{{ $leases }}"></dhcp-leases>

    </div>
</div>

@endsection
