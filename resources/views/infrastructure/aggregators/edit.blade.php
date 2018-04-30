@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/infrastructure/aggregators">Aggregators</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit {{ $aggregator->name }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-10 text-center">
        <h4>Edit {{ $aggregator->name }}</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-10">

        <form method="POST" action="/infrastructure/aggregators/{{ $aggregator->id }}">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="form-group row"> <!-- name -->
                <div class="col text-right">
                    <label for="name" class="col-form-label">Name</label>
                </div>
                <div class="col">
                    <input
                            id="name"
                            type="text"
                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                            name="name"
                            value="{{ old('name') ?: $aggregator->name }}"
                            required
                    >

                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /name -->

            <div class="form-group row"> <!-- platform -->
                <div class="col text-right">
                    <label for="platform" class="col-form-label">platform</label>
                </div>
                <div class="col">
                    <select class="custom-select" name="platform" required>
                        <option>Select A platform</option>
                        @foreach(App\Platform::all()->sortBy('name') as $platform)
                            @if($platform->id == $aggregator->platform_id)
                                <option value="{{ $platform->code }}" selected>{{ $platform->name }}</option>
                            @else
                                <option value="{{ $platform->code }}">{{ $platform->name }}</option>
                            @endif
                        @endforeach
                    </select>

                    @if ($errors->has('platform'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('platform') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /platform -->

            <div class="form-group row"> <!-- fqdn -->
                <div class="col text-right">
                    <label for="fqdn" class="col-form-label">FQDN</label>
                </div>
                <div class="col">
                    <input
                            id="fqdn"
                            type="text"
                            class="form-control{{ $errors->has('fqdn') ? ' is-invalid' : '' }}"
                            name="fqdn"
                            value="{{ old('fqdn') ?: $aggregator->fqdn }}"
                    >

                    @if ($errors->has('fqdn'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('fqdn') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /fqdn -->

            <div class="form-group row"> <!-- management_ip -->
                <div class="col text-right">
                    <label for="management_ip" class="col-form-label">management_ip</label>
                </div>
                <div class="col">
                    <input
                            id="management_ip"
                            type="text"
                            class="form-control{{ $errors->has('management_ip') ? ' is-invalid' : '' }}"
                            name="management_ip"
                            value="{{ old('management_ip') ?: $aggregator->management_ip }}"
                            required
                    >

                    @if ($errors->has('management_ip'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('management_ip') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /management_ip -->

            <div class="form-group row"> <!-- monitoring_ip -->
                <div class="col text-right">
                    <label for="monitoring_ip" class="col-form-label">monitoring_ip</label>
                </div>
                <div class="col">
                    <input
                            id="monitoring_ip"
                            type="text"
                            class="form-control{{ $errors->has('monitoring_ip') ? ' is-invalid' : '' }}"
                            name="monitoring_ip"
                            value="{{ old('monitoring_ip') ?: $aggregator->monitoring_ip }}"
                    >

                    @if ($errors->has('monitoring_ip'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('monitoring_ip') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /monitoring_ip -->

            <div class="form-group row"> <!-- management_mac -->
                <div class="col text-right">
                    <label for="management_mac" class="col-form-label">management_mac</label>
                </div>
                <div class="col">
                    <input
                            id="management_mac"
                            type="text"
                            class="form-control{{ $errors->has('management_mac') ? ' is-invalid' : '' }}"
                            name="management_mac"
                            value="{{ old('management_mac') ?: $aggregator->management_mac }}"
                    >

                    @if ($errors->has('management_mac'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('management_mac') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /management_mac -->

            <div class="form-group row"> <!-- notes -->
                <div class="col text-right">
                    <label for="notes" class="col-form-label">Notes</label>
                </div>
                <div class="col">
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') ?: $aggregator->notes}}</textarea>

                    @if ($errors->has('notes'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('notes') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- notes -->

            <div class="form-group row">
                <div class="col text-right">
                    <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection
