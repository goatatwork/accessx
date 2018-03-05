@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Customers</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col">
                            <dl>
                                <dt>Total Customers</dt>
                                <dd>There are {{ $customers->count() }} customers</dd>
                            </dl>
                        </div>
                        <div class="col">
                            <a href="/customers/create" class="btn btn-secondary float-right">Create A Customer</a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col">

            @foreach ($customers as $customer)
                <div class="media mb-5 ml-5 border-bottom {{ ($loop->first) ? 'mt-5' : '' }}">
                    <span class="fas fa-2x {{ ($customer->customer_type == 'Business') ? 'fa-building' : 'fa-user' }} mr-3"></span>
                    <div class="media-body">

                        <div class="row">
                            <div class="col">
                                <a href="/customers/{{ $customer->id }}" class="h4">{{ $customer->customer_name }}</a><br>
                                <span class="font-italic">
                                    Created on {{ $customer->created_at->toFormattedDateString() }} at {{ $customer->created_at->toTimeString() }}
                                </span>
                            </div>
                            <div class="col">
                                <address>
                                    @if ($customer->service_locations()->exists())
                                        @if ($customer->service_locations()->count() == 1)

                                            @if ($customer->service_locations[0]->name != 'Default')
                                                <strong>{{ $customer->service_locations[0]->name }}</strong><br>
                                            @endif

                                            @if($customer->service_locations[0]->poc_name)
                                                {{ $customer->service_locations[0]->poc_name }}<br>
                                            @endif

                                            @if($customer->service_locations[0]->poc_email)
                                                <a href="mailto:{{ $customer->service_locations[0]->poc_email }}">
                                                    <span class="fas fa-envelope"></span>
                                                    {{ $customer->service_locations[0]->poc_email }}
                                                </a><br>
                                            @endif

                                            @if($customer->service_locations[0]->phone1)
                                                {{ $customer->service_locations[0]->phone1 }}<br>
                                            @endif

                                            @if($customer->service_locations[0]->phone2)
                                                {{ $customer->service_locations[0]->phone2 }}<br>
                                            @endif

                                            @if($customer->service_locations[0]->address1)
                                                {{ $customer->service_locations[0]->address1 }}<br>
                                            @endif

                                            @if($customer->service_locations[0]->address2)
                                                {{ $customer->service_locations[0]->address2 }}<br>
                                            @endif

                                            {{ $customer->service_locations[0]->city }}, {{ $customer->service_locations[0]->state }}&nbsp;&nbsp;{{ $customer->service_locations[0]->zip }}<br>

                                            @if ($customer->service_locations[0]->notes)
                                                <small>
                                                    {{ $customer->service_locations[0]->notes }}<br>
                                                </small>
                                            @endif

                                        @endif
                                    @else
                                        <span class="font-italic">NO SERVICE LOCATIONS</span>
                                    @endif
                                </address>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>

@endsection
