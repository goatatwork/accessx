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


        <div class="row" style="{{ $aggregator->notes ? '' : 'visibility:hidden;'}}">
            <div class="col text-center">
                <button class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#aggregateor-notes-{{ $aggregator->id }}">
                    <i class="material-icons">chat</i> Notes for {{ $aggregator->name }}
                </button>
            </div>
        </div>

        <div class="row mt-5 align-self-end">
            <div class="col text-center">
                <a href="/infrastructure/aggregators/{{ $aggregator->id }}" class="btn btn-sm btn-outline-dark">Show</a>
            </div>
            <div class="col text-center">
                <a href="/infrastructure/aggregators/{{ $aggregator->id }}/edit" class="btn btn-sm btn-outline-dark">Edit</a>
            </div>
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
