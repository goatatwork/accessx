@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/onts">ONTs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit {{ $ont->model_number }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-10 text-center">
        <h4>Edit {{ $ont->model_number }}</h4>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">

        <form method="POST" action="/onts/{{ $ont->id }}">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="form-group"> <!-- input for model_number -->

                <label for="model_number">Model Number</label>

                <input
                    type="text"
                    name="model_number"
                    class="form-control {{ $errors->has('model_number') ? ' is-invalid' : '' }}"
                    id="model_number-input"
                    aria-describedby="model_number-help"
                    placeholder="model_number"
                    value="{{ old('model_number') ?: $ont->model_number }}"
                >

                <small id="model_number-help" class="form-text text-muted">Model number....</small>

                @if ($errors->has('model_number'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('model_number') }}</strong>
                    </div>
                @endif

            </div> <!-- input for model_number -->

            <div class="form-group"> <!-- input for manufacturer -->

                <label for="manufacturer">Manufacturer</label>

                <input
                    type="text"
                    name="manufacturer"
                    class="form-control {{ $errors->has('manufacturer') ? ' is-invalid' : '' }}"
                    id="manufacturer-input"
                    aria-describedby="manufacturer-help"
                    placeholder="manufacturer"
                    value="{{ old('manufacturer') ?: $ont->manufacturer }}"
                >

                <small id="manufacturer-help" class="form-text text-muted">Manufacturer...</small>

                @if ($errors->has('manufacturer'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('manufacturer') }}</strong>
                    </div>
                @endif

            </div> <!-- input for manufacturer -->

            <div class="row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="wifi" id="wifi-input" value="1"
                            @if($ont->wifi)
                                checked
                            @endif
                        >
                        <label class="form-check-label" for="wifi">
                            Wifi
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="indoor" id="indoor-input" value="1"
                            @if($ont->indoor)
                                checked
                            @endif
                        >
                        <label class="form-check-label" for="indoor">
                            Indoor
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="oem" id="oem-input" value="1"
                            @if($ont->oem)
                                checked
                            @endif
                        >
                        <label class="form-check-label" for="oem">
                            OEM
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group"> <!-- input for number_of_pots_lines -->

                <label for="number_of_pots_lines">number_of_pots_lines</label>

                <input
                    type="text"
                    name="number_of_pots_lines"
                    class="form-control {{ $errors->has('number_of_pots_lines') ? ' is-invalid' : '' }}"
                    id="number_of_pots_lines-input"
                    aria-describedby="number_of_pots_lines-help"
                    placeholder="number_of_pots_lines"
                    value="{{ old('number_of_pots_lines') ?: $ont->number_of_pots_lines }}"
                >

                <small id="number_of_pots_lines-help" class="form-text text-muted">number_of_pots_lines...</small>

                @if ($errors->has('number_of_pots_lines'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('number_of_pots_lines') }}</strong>
                    </div>
                @endif

            </div> <!-- input for number_of_pots_lines -->

            <div class="form-group"> <!-- input for number_of_ethernet_ports -->

                <label for="number_of_ethernet_ports">number_of_ethernet_ports</label>

                <input
                    type="text"
                    name="number_of_ethernet_ports"
                    class="form-control {{ $errors->has('number_of_ethernet_ports') ? ' is-invalid' : '' }}"
                    id="number_of_ethernet_ports-input"
                    aria-describedby="number_of_ethernet_ports-help"
                    placeholder="number_of_ethernet_ports"
                    value="{{ old('number_of_ethernet_ports') ?: $ont->number_of_ethernet_ports }}"
                >

                <small id="number_of_ethernet_ports-help" class="form-text text-muted">number_of_ethernet_ports...</small>

                @if ($errors->has('number_of_ethernet_ports'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('number_of_ethernet_ports') }}</strong>
                    </div>
                @endif

            </div> <!-- input for number_of_ethernet_ports -->

            <div class="form-group"> <!-- input for notes -->

                <label for="notes">Your Note</label>

                <textarea
                    class="form-control {{ $errors->has('notes') ? ' is-invalid' : '' }}"
                    id="notes-input"
                    rows="5"
                    name="notes"
                >{{ old('notes') ?: $ont->notes }}</textarea>

                @if ($errors->has('notes'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('notes') }}</strong>
                    </div>
                @endif

            </div> <!-- input for notes -->

            <div class="form-group row">
                <div class="col text-right">
                    <button dusk="edit-ont-submit-button" type="submit" class="btn btn-primary">
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

@endsection
