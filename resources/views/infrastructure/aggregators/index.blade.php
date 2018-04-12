@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Aggregators</li>
    </ol>
</nav>

<div class="row mb-5">
    <div class="col">

        <dl class="float-left">
            <dt>Total Aggregators</dt>
            <dd>There are {{ $aggregators->count() }} aggregator.</dd>
        </dl>

        <span class="float-right">
            <a href="/infrastructure/aggregators/create" class="btn btn-secondary"><i class="material-icons mr-2">add</i>Add An Aggregator</a>
        </span>

    </div>
</div>

<div class="row">

        <div class="col">
            <div class="card-deck">
                @foreach($aggregators as $aggregator)
                    @include('infrastructure.aggregators._aggregator-card')
                @endforeach
            </div>
        </div>

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
