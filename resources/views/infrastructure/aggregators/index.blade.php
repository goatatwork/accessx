@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Aggregators</li>
    </ol>
</nav>

<div class="row">
    <div class="col">

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col">
                        <dl>
                            <dt>Total Aggregators</dt>
                            <dd>
                                @if ($aggregators->count() > 1)
                                    {{ $aggregators->count() }} aggregators
                                @elseif ($aggregators->count() == 1)
                                    1 aggregator
                                @else
                                    No aggregators
                                @endif
                            </dd>
                        </dl>
                    </div>
                    <div class="col">
                        <a href="/infrastructure/aggregators/create" class="btn btn-secondary float-right">Add An Aggregator</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


<div class="row">
    @foreach($aggregators as $aggregator)
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
                                    {{$aggregator->platform->number_of_slots}} slots
                                    <ul>
                                        <li>{{$aggregator->slots()->populatedOnly()->count()}} populated</li>
                                        <li>{{$aggregator->slots()->unpopulatedOnly()->count()}} unpopulated</li>
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
                                    {{$aggregator->ports()->count()}} ports
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
    @endforeach
</div>

@foreach($aggregators as $aggregator)
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
@endforeach

@endsection
