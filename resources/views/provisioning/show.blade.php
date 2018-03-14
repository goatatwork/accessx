@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/provisioning">Provisioning Records</a></li>
            <li class="breadcrumb-item active" aria-current="page">A Provisioning Record For {{ $provisioning_record->service_location->customer->customer_name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-4">
            <service-location-card :location="{{ $provisioning_record->service_location }}"></service-location-card>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-body text-center">
                    <iframe
                        width="400"
                        height="200"
                        frameborder="0"
                        style="border:0"
                        src="{{ $provisioning_record->service_location->google_maps_embed_api_string }}"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 justify-content-center">
        <div class="col-8">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th colspan="6" class="text-center border-top-0">
                            <h3>Details for This Provisioning Record</h3>
                        </th>
                    </tr>
                    <tr class="table-secondary border-top-0">
                        <th class="text-center">Customer</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Package</th>
                        <th class="text-center">Management IP</th>
                        <th class="text-center">NetLocation</th>
                        <th class="text-center">ONT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">
                            <a href="/customers/{{ $provisioning_record->service_location->customer->id }}">
                                {{ $provisioning_record->service_location->customer->customer_name }}
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="/provisioning/service_locations/{{ $provisioning_record->service_location->id }}/show">
                                {{ $provisioning_record->service_location->address1 }}
                            </a>
                        </td>
                        <td class="text-center">
                            {{ $provisioning_record->ont_profile->name }}
                        </td>
                        <td class="text-center">
                            {{ $provisioning_record->ip_address->address }}
                        </td>
                        <td class="text-center">
                            {{ $provisioning_record->port->slot->aggregator->name }}
                            <span class="fas fa-long-arrow-alt-right"></span>
                            Slot {{ $provisioning_record->port->slot->slot_number }}
                            <span class="fas fa-long-arrow-alt-right"></span>
                            Port {{ $provisioning_record->port->port_number }}
                        </td>
                        <td class="text-center">
                            {{ $provisioning_record->ont_profile->ont_software->ont->model_number }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
