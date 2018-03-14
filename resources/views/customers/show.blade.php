@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/customers">Customers</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $customer->customer_name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col">
            <div class="media mb-5 border-bottom mt-5">
                <span class="fas fa-2x {{ ($customer->customer_type == 'Business') ? 'fa-building' : 'fa-user' }} mr-3"></span>
                <div class="media-body">
                    <div class="row">
                        <div class="col-4">
                            <a href="/customers/{{ $customer->id }}" class="h4">{{ $customer->customer_name }}</a><br>
                            <span class="font-italic">
                                Created on {{ $customer->created_at->toFormattedDateString() }} at {{ $customer->created_at->toTimeString() }}
                            </span>
                        </div>
                        <div class="col">
                            ..
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-center">
            <span class="h3">Service Locations</span>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div id="service-locations-carousel" class="carousel slide" data-pause="hover" data-interval="0">

                <ol class="carousel-indicators">
                    @foreach($customer->service_locations as $service_location)
                        <li class="bg-secondary" data-target="#service-locations-carousel" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                    @endforeach
                </ol>

                <div class="carousel-inner">
                    @foreach($customer->service_locations as $service_location)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <div class="row d-block w-100" style="height:025em;">
                                <div class="col">

                                    <div class="row justify-content-center">
                                        <div class="col-8">

                                            <div class="row mb-3">
                                                <div class="col-6">

                                                    <div class="row">
                                                        <div class="col">
                                                            @if($service_location->has_provisioning_records)
                                                                <a href="/provisioning/service_locations/{{ $service_location->id }}/show">See Provisioning Records</a>
                                                            @else
                                                                <a href="/provisioning/service_locations/{{ $service_location->id }}/create">Provision Service Here</a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <address>
                                                        <strong>Location: {{ $service_location->id }} {{ $service_location->name }}</strong><br>
                                                        @if($service_location->poc_name)
                                                            <strong>Contact:</strong> {{ $service_location->poc_name }}
                                                            @if($service_location->poc_email)
                                                                <a href="mailto:{{ $service_location->poc_email }}"><span class="fas fa-envelope"></span></a><br>
                                                            @else
                                                                <br>
                                                            @endif
                                                        @endif
                                                        <strong>Location Information</strong><br>
                                                        @if($service_location->address1)
                                                            {{ $service_location->address1 }}<br>
                                                        @endif
                                                        @if($service_location->address2)
                                                            {{ $service_location->address2 }}<br>
                                                        @endif
                                                        {{ $service_location->city }}, {{ $service_location->state }}&nbsp;&nbsp;{{ $service_location->zip }}<br>
                                                        @if($service_location->phone1)
                                                            Phone: {{ $service_location->phone1 }}<br>
                                                        @endif
                                                        @if($service_location->phone2)
                                                            Alt. Phone: {{ $service_location->phone2 }}<br>
                                                        @endif
                                                        @if($service_location->notes)
                                                            <span class="fas fa-file-alt"></span>
                                                        @endif
                                                    </address>

                                                </div>
                                                <div class="col-6">

                                                    <iframe
                                                        width="400"
                                                        height="200"
                                                        frameborder="0"
                                                        style="border:0"
                                                        src="{{ $service_location->google_maps_embed_api_string }}"
                                                        allowfullscreen
                                                    ></iframe>

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="carousel-caption d-none d-md-block text-dark">
                                <p class="font-italic">{{ $loop->iteration }} of {{ $loop->count }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a class="carousel-control-prev" href="#service-locations-carousel" role="button" data-slide="prev">
                    <span class="fas fa-chevron-left text-secondary"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#service-locations-carousel" role="button" data-slide="next">
                    <span class="fas fa-chevron-right text-secondary"></span>
                    <span class="sr-only">Previous</span>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
