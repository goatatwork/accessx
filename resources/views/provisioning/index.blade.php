@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Provisioning Records</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <dl>
                                <dt>Total Provisioning Records</dt>
                                <dd>There are {{ $provisioning_records->count() }} provisioning records</dd>
                            </dl>
                        </div>
                        <div class="col">
                            <a href="/provisioning/create" class="btn btn-secondary float-right">Provisioning A Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <provisioning-records-table :provisioning-records="{{ $provisioning_records }}"></provisioning-records-table>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Location</th>
                        <th>Package</th>
                        <th>Management IP</th>
                        <th>ONT</th>
                        <th>NetLocation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($provisioning_records as $record)
                        <repeating-table-row>
                            <tr slot="table-row">
                                <td>{{ $record->service_location->customer->customer_name }}</td>
                                <td>{{ $record->service_location->address1 }}, {{ $record->service_location->zip }}</td>
                                <td>{{ $record->ont_profile->name }}</td>
                                <td>{{ $record->ip_address->address }}</td>
                                <td>{{ $record->ont_profile->ont_software->ont->model_number }}</td>
                                <td>
                                    {{ $record->port->slot->aggregator->name }}
                                    <span class="fas fa-long-arrow-alt-right"></span>
                                    Slot {{ $record->port->slot->slot_number }}
                                    <span class="fas fa-long-arrow-alt-right"></span>
                                    Port {{ $record->port->port_number }}
                                </td>
                            </tr>
                        </repeating-table-row>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</div>

@endsection
