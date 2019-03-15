@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/infrastructure/aggregators">Aggregators</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $aggregator->name }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-3">
        <div class="card mt-3 mb-3">

            <a href="/infrastructure/aggregators/{{ $aggregator->id }}" class="text-dark">
                <div class="card-header text-center">
                    <span class="font-weight-bold"><i class="material-icons">storage</i> {{ $aggregator->name }}</span>
                </div>
            </a>

            <div class="card-body">
                <div class="row">
                    <div class="col text-center">

                        <ul class="list-unstyled">
                            <li>
                                Platform: <span class="font-italic small">{{ $aggregator->platform->name }}</span>
                            </li>
                            <li>
                                <span class="font-weight-bold">{{$aggregator->slots()->populatedOnly()->count()}}</span> of
                                <span class="font-weight-bold">{{$aggregator->slots()->unpopulatedOnly()->count()}}</span> slots populated
                            </li>
                            <li>
                                <span class="font-weight-bold">{{$aggregator->ports()->count()}}</span> total ports
                            </li>
                        </ul>

                    </div>
                </div>

                @if($aggregator->notes)
                    <div class="row">
                        <div class="col text-center">
                            <button class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#aggregateor-notes-{{ $aggregator->id }}">
                                <i class="material-icons">chat</i> Notes for {{ $aggregator->name }}
                            </button>
                        </div>
                    </div>
                @endif

                <div class="row mt-5">
                    <div class="col text-center">
                        <a href="/infrastructure/aggregators/{{ $aggregator->id }}/edit" class="btn btn-sm btn-outline-dark">Edit</a>
                    </div>
    <!--                 <div class="col text-center">
                        <a href="/infrastructure/aggregators/{{ $aggregator->id }}" class="btn btn-sm btn-outline-dark">Show</a>
                    </div> -->
                    <div class="col text-center">
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-dark"
                            data-toggle="modal"
                            data-target="#delete-aggregator-{{ $aggregator->id }}"
                            @if ($aggregator->has_provisioning_records) {{ 'disabled' }} @endif
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <small>
                    <span class="font-italic">
                        Created on {{ $aggregator->created_at->toFormattedDateString() }} at {{ $aggregator->created_at->toTimeString() }}
                    </span>
                </small>
            </div>
        </div>
    </div>

    <div class="col pt-3">
        <div id="accordion"> <!-- accordion -->

            @foreach($aggregator->slots as $aggregator_slot)
                <div class="card mb-5"> <!-- card -->
                    <div class="card-header" id="slotHeading-{{ $aggregator_slot->id }}">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseArea-{{ $aggregator_slot->id }}" aria-expanded="false" aria-controls="#collapseArea-{{ $aggregator_slot->id }}">
                                Slot {{ $aggregator_slot->slot_number }}
                                @if (!$aggregator_slot->populated)
                                    <small><span class="font-italic">[ not populated ]</span></small>
                                @else
                                    <small><span class="font-italic">{{ $aggregator_slot->module_type->name }}</span></small>
                                @endif
                            </button>
                        </h5>
                    </div>

                    <div id="collapseArea-{{ $aggregator_slot->id }}" class="collapse" aria-labelledby="slotHeading-{{ $aggregator_slot->id }}" data-parent="#accordion">
                        <div class="card-body">

                            @if(!$aggregator_slot->populated)
                                <div class="row">
                                    <div class="col">
                                        <form method="POST" action="/infrastructure/slots/{{ $aggregator_slot->id }}/populate">
                                            {{ method_field('POST') }}
                                            {{ csrf_field() }}

                                            <div class="form-group row">
                                                <select class="custom-select" name="module_type_id" value="{{ old('module_type_id') }}">
                                                    <option>Populate With...</option>
                                                    @foreach($aggregator->platform->module_types as $module_type)
                                                        <option value="{{ $module_type->id }}">{{ $module_type->name }}</option>
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('module_type_id'))
                                                    <div class="invalid-feedback">
                                                        <strong>{{ $errors->first('module_type_id') }}</strong>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        Save
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button
                                                        type="reset"
                                                        class="btn btn-danger btn-sm"
                                                        data-toggle="collapse"
                                                        data-target="#collapseArea-{{ $aggregator_slot->id }}"
                                                    >
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            @else

                                <slot-port-creator :agg-slot="{{ $aggregator_slot }}"></slot-port-creator>

                                @include('infrastructure.aggregators._populated_slot')

                            @endif

                        </div>
                    </div>
                </div> <!-- /card -->
            @endforeach

        </div> <!-- /accordion -->
    </div>
</div>

<div class="modal fade"
    tabindex="-1"
    role="dialog"
    id="delete-aggregator-{{ $aggregator->id }}"
    aria-labelledby="delete-aggregator-{{ $aggregator->id }}-label"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5  id="delete-aggregator-{{ $aggregator->id }}-label" class="modal-title">Delete {{ $aggregator->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this aggregator?
            </div>
            <div class="modal-footer">
                <form method="POST" action="/infrastructure/aggregators/{{ $aggregator->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-link text-dark float-right">Delete</button>
                    <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    tabindex="-1"
    role="dialog"
    id="aggregateor-notes-{{ $aggregator->id }}"
    aria-labelledby="aggregateor-notes-{{ $aggregator->id }}-label"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5  id="aggregateor-notes-{{ $aggregator->id }}-label" class="modal-title">{{ $aggregator->name }} Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <marked-content marked-content="{{ $aggregator->notes }}"></marked-content>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
            </div>
        </div>
    </div>
</div>

@endsection
