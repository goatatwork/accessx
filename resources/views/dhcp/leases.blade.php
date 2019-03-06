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

        <table class="table table-dark">
            <tr>
                <th>Time</th>
                <th>MAC</th>
                <th>IP</th>
                <th>Host Name</th>
                <th>ClientID</th>
            </tr>

            @foreach($leases as $lease)
                <tr>
                    <td>{{$lease['time']}}</td>
                    <td>{{$lease['mac']}}</td>
                    <td>{{$lease['ip']}}</td>
                    <td>{{$lease['hostname']}}</td>
                    <td>{{$lease['client_id']}}</td>
                </tr>
            @endforeach
        </table>

    </div>
</div>

@endsection
