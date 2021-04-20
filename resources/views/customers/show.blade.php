@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/customers">Customers</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $customer->customer_name }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col text-left">
        <span class="h4" style="display:inline;">
            <i class="material-icons">{{ ($customer->customer_type == 'Business') ? 'business' : 'person' }}</i>
            {{ $customer->customer_name }}
        </span>

        @include('customers.change-customer-name')

    </div>
</div>

<div class="row">
    <div class="col">
        <span class="font-italic small">
            Created on {{ $customer->created_at->toFormattedDateString() }} at {{ $customer->created_at->toTimeString() }}
        </span>
    </div>
    <div class="col-auto">
        <address><small>
            <span class="font-weight-bold">Billing Info</span>
            <a href="#" data-toggle="modal" data-target="#edit-billing-info-modal" class="pull-right text-primary">Edit</a>
            <br>
            <strong>{{ $customer->billing_record->poc_name }}</strong>
            <a href="mailto:{{ $customer->billing_record->poc_email }}"><span class="far fa-envelope"></span></a>
            <br>
            P: {{ $customer->billing_record->phone1 }}

            @if ($customer->billing_record->phone2)
                <br>
                P: {{ $customer->billing_record->phone2 }}
            @endif

            <br>
            {{ $customer->billing_record->address1 }}

            @if ($customer->billing_record->address2)
            <br>
            {{ $customer->billing_record->address2 }}
            @endif

            <br>
            {{ $customer->billing_record->city }}, {{ $customer->billing_record->state }} {{ $customer->billing_record->zip }}


            <br>
            {{ $customer->billing_record->notes }}
        </small></address>
    </div>
</div>

<div class="row">
    <div class="col text-center">
        <span class="h4">Service Locations</span>
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
                        <div class="row d-block w-100 pt-3" style="height:025em;">
                            <div class="col">

                                <div class="row justify-content-center">
                                    <div class="col-8">

                                        <div class="row mb-3">
                                            <div class="col-6">

                                                <div class="row">
                                                    <div class="col">
                                                        @if($service_location->has_provisioning_records)
                                                            <small>
                                                                <span class="font-weight-bold">Services Provisioned Here</span>
                                                                <br>
                                                                @foreach($service_location->provisioning_records as $record)
                                                                    <a href="/provisioning/{{ $record->id }}">
                                                                        View service {{ $loop->iteration }}
                                                                    </a>
                                                                @endforeach
                                                            </small>
                                                        @else
                                                            <a href="/provisioning/service_locations/{{ $service_location->id }}/create">Provision Service Here</a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <address><small>
                                                    <span class="font-weight-bold">Location: {{ $service_location->id }} {{ $service_location->name }}</span>
                                                    <a href="#"
                                                        data-toggle="modal"
                                                        data-target="#edit-service-location-{{$service_location->id}}"
                                                        class="pull-right text-primary">Edit</a>
                                                    <br>

                                                    <strong>{{ $service_location->poc_name }}</strong>
                                                    <a href="mailto:{{ $service_location->poc_email }}"><span class="far fa-envelope"></span></a>
                                                    <br>

                                                    P: {{ $service_location->phone1 }}
                                                    <br>

                                                    @if ($service_location->phone2)
                                                        P: {{ $service_location->phone2 }}
                                                        <br>
                                                    @endif

                                                    @if ($service_location->address1)
                                                        {{ $service_location->address1 }}
                                                        <br>
                                                    @endif

                                                    @if ($service_location->address2)
                                                        {{ $service_location->address2 }}
                                                        <br>
                                                    @endif

                                                    {{ $service_location->city }}, {{ $service_location->state }} {{ $service_location->zip }}
                                                    <br>

                                                    @if ($service_location->notes)
                                                        {{ $service_location->notes }}
                                                    @endif

                                                </small></address>

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

<div class="modal fade" id="edit-billing-info-modal" tabindex="-1" role="dialog" aria-labelledby="edit-billing-info-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-billing-info-modalLabel">Edit Billing Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="/billing_records/{{ $customer->billing_record->id}}">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}

                <div class="modal-body">

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="poc_name" class="sr-only">POC Name</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="poc_name"
                                    placeholder="POC Name"
                                    value="{{$customer->billing_record->poc_name}}"
                                    >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="poc_email" class="sr-only">POC Email</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="poc_email"
                                    placeholder="POC Email"
                                    value="{{$customer->billing_record->poc_email}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="phone1" class="sr-only">Phone 1</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="phone1"
                                    placeholder="Phone 1"
                                    value="{{$customer->billing_record->phone1}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="phone2" class="sr-only">Phone 2</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="phone2"
                                    placeholder="Phone 2"
                                    value="{{$customer->billing_record->phone2}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="address1" class="sr-only">Address 1</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="address1"
                                    placeholder="Address 1"
                                    value="{{$customer->billing_record->address1}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="address2" class="sr-only">Address 2</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="address2"
                                    placeholder="Address 2"
                                    value="{{$customer->billing_record->address2}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="city" class="sr-only">City</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="city"
                                    placeholder="City"
                                    value="{{$customer->billing_record->city}}"
                                >
                            </div>
                        </div>
                        <div class="col-3">
                            <label for="state" class="sr-only">State</label>
                            <select class="form-control form-control-sm" name="state" required>
                                <option selected>Select A State</option>
                                @foreach(App\State::all()->sortBy('name') as $state)
                                    <option value="{{ $state->code }}" @if($customer->billing_record->state == $state->code) {{ 'selected' }} @endif>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="zip" class="sr-only">Zip</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="zip"
                                    placeholder="Zip"
                                    value="{{$customer->billing_record->zip}}"
                                >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>


@foreach($customer->service_locations as $service_location)
<div class="modal fade"
    id="edit-service-location-{{$service_location->id}}"
    tabindex="-1"
    role="dialog"
    aria-labelledby="edit-service-location-{{$service_location->id}}Label"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-service-location-{{$service_location->id}}Label">Edit Billing Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="/service_locations/{{ $service_location->id}}">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}

                <div class="modal-body">

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="sr-only">Service Location Name</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="name"
                                    placeholder="Service Location Name"
                                    value="{{$service_location->name}}"
                                    >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="poc_name" class="sr-only">POC Name</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="poc_name"
                                    placeholder="POC Name"
                                    value="{{$service_location->poc_name}}"
                                    >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="poc_email" class="sr-only">POC Email</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="poc_email"
                                    placeholder="POC Email"
                                    value="{{$service_location->poc_email}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="phone1" class="sr-only">Phone 1</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="phone1"
                                    placeholder="Phone 1"
                                    value="{{$service_location->phone1}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="phone2" class="sr-only">Phone 2</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="phone2"
                                    placeholder="Phone 2"
                                    value="{{$service_location->phone2}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="address1" class="sr-only">Address 1</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="address1"
                                    placeholder="Address 1"
                                    value="{{$service_location->address1}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="address2" class="sr-only">Address 2</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="address2"
                                    placeholder="Address 2"
                                    value="{{$service_location->address2}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="city" class="sr-only">City</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="city"
                                    placeholder="City"
                                    value="{{$service_location->city}}"
                                >
                            </div>
                        </div>
                        <div class="col-3">
                            <label for="state" class="sr-only">State</label>
                            <select class="form-control form-control-sm" name="state" required>
                                <option selected>Select A State</option>
                                @foreach(App\State::all()->sortBy('name') as $state)
                                    <option value="{{ $state->code }}" @if($service_location->state == $state->code) {{ 'selected' }} @endif>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="zip" class="sr-only">Zip</label>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="zip"
                                    placeholder="Zip"
                                    value="{{$service_location->zip}}"
                                >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach

@endsection
