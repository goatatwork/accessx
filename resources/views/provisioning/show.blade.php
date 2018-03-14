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

            <div class="row">
                <div class="col">
                    <service-location-card :location="{{ $provisioning_record->service_location }}"></service-location-card>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>

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
        <div class="col-10">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <td colspan="7" class="border-0 text-center">
                            <h2>Details for This Provisioning Record</h2>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center border-0"></th>
                        <th class="text-center border-0">Customer</th>
                        <th class="text-center border-0">Location</th>
                        <th class="text-center border-0">Package</th>
                        <th class="text-center border-0">Management IP</th>
                        <th class="text-center border-0">NetLocation</th>
                        <th class="text-center border-0">ONT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center font-italic">
                            <span class="fas fa-map-marker-alt text-success"></span> Created {{ $provisioning_record->created_at->format('l, M j, Y g:i A') }}
                        </td>
                        <td class="text-center">
                            <a href="/customers/{{ $provisioning_record->service_location->customer->id }}" class="text-dark">
                                {{ $provisioning_record->service_location->customer->customer_name }}
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="/provisioning/service_locations/{{ $provisioning_record->service_location->id }}/show" class="text-dark">
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
