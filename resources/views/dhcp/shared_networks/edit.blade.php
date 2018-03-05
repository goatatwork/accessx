@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/dhcp">DHCP</a></li>
            <li class="breadcrumb-item active"><a href="#">Edit {{ $shared_network->name }}</a></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-10 text-center">
            <h4>Edit {{ $shared_network->name }}</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">

            <form method="POST" action="/dhcp/shared_networks/{{ $shared_network->id }}">
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
                                value="{{ old('name') ?: $shared_network->name }}"
                                required
                        >

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- /name -->

                <div class="form-group row"> <!-- vlan -->
                    <div class="col text-right">
                        <label for="vlan" class="col-form-label">vlan</label>
                    </div>
                    <div class="col">
                        <input
                                id="vlan"
                                type="text"
                                class="form-control{{ $errors->has('vlan') ? ' is-invalid' : '' }}"
                                name="vlan"
                                value="{{ old('vlan') ?: $shared_network->vlan }}"
                        >

                        @if ($errors->has('vlan'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('vlan') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- /vlan -->

                <div class="form-group row"> <!-- management -->
                    <div class="col">
                    </div>
                    <div class="col">
                        <div class="form-check">
                            @if ($shared_network->management)
                                <input class="form-check-input" type="checkbox" name="management" id="management-input" value="1" checked>
                            @else
                                <input class="form-check-input" type="checkbox" name="management" id="management-input" value="1">
                            @endif
                            <label class="form-check-label" for="management ">
                                This is a management network
                            </label>
                        </div>
                    </div>
                </div> <!-- /management -->

                <div class="form-group row"> <!-- notes -->
                    <div class="col text-right">
                        <label for="notes" class="col-form-label">Notes</label>
                    </div>
                    <div class="col">
                        <textarea class="form-control" id="notes" name="notes" rows="3" aria-describedby="notes-help" data-autosize>{{ old('notes') ?: $shared_network->notes }}</textarea>

                        @if ($errors->has('notes'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('notes') }}</strong>
                            </div>
                        @endif

                        <small id="notes-help" class="form-text text-muted">This textarea supports Markdown</small>

                    </div>
                </div> <!-- notes -->

                <div class="form-group row">
                    <div class="col text-right">
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </div>
                    <div class="col">
                        <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a>
                    </div>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
