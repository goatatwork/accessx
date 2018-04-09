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
    <div class="col-3">
        <div class="card mt-3 mb-3">
            <div class="card-header">

                <span class="float-left">
                    <a href="/infrastructure/aggregators/{{ $aggregator->id }}" class="text-dark">{{ $aggregator->name }}</a>
                </span>

                <span class="float-right">

                </span>

            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col">
                        <div class="media mt-3">
                            <span class="fas fa-2x fa-circle text-dark mr-3"></span>
                            <div class="media-body">
                                {{$aggregator->platform->number_of_slots}} slots.
                                <ul>
                                    <li>{{$aggregator->slots()->populatedOnly()->count()}} populated.</li>
                                    <li>{{$aggregator->slots()->unpopulatedOnly()->count()}} unpopulated.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="media mt-3">
                            <span class="fas fa-2x fa-circle text-dark mr-3"></span>
                            <div class="media-body">
                                {{$aggregator->ports()->count()}} ports.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="media mt-3">
                            <span class="fas fa-2x fa-file-alt text-dark mr-3"></span>
                            <div class="media-body">
                                @if ($aggregator->notes)
                                    <button
                                        ype="button"
                                        class="btn btn-link text-dark"
                                        data-toggle="modal"
                                        data-target="#aggregator-notes-{{ $aggregator->id }}"
                                    >
                                        View Notes
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        class="btn btn-link text-dark"
                                        data-toggle="modal"
                                        data-target="#aggregator-notes-{{ $aggregator->id }}" disabled
                                    >
                                        No Notes
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col text-center">
                        <a href="/infrastructure/aggregators/{{ $aggregator->id }}/edit" class="btn btn-link text-dark">Edit</a>
                    </div>
                    <div class="col text-center">
                        <a href="/infrastructure/aggregators/{{ $aggregator->id }}" class="btn btn-link text-dark">Show</a>
                    </div>
                    <div class="col text-center">
                        <button
                            type="button"
                            class="btn btn-link text-dark"
                            data-toggle="modal"
                            data-target="#delete-aggregator-{{ $aggregator->id }}"
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
                <div class="card"> <!-- card -->
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
                                                <div class="col-1">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        Save
                                                    </button>
                                                </div>
                                                <div class="co-1">
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
                    <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                    <button type="submit" class="btn btn-link text-dark float-right">Delete</button>
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
