<div class="col-4">
    <div class="card mt-3 mb-3">

        <a href="/infrastructure/aggregators/{{ $aggregator->id }}" class="text-dark">
            <div class="card-header text-center">
                {{ $aggregator->name }}
            </div>
        </a>

        <div class="card-body">

            <div class="row">
                <div class="col text-center">

                    <span class="font-weight-bold">{{$aggregator->slots()->populatedOnly()->count()}}</span> of
                    <span class="font-weight-bold">{{$aggregator->slots()->unpopulatedOnly()->count()}}</span> slots populated

                </div>
            </div>

            <div class="row mt-2">
                <div class="col text-center">

                    <span class="font-weight-bold">{{$aggregator->ports()->count()}}</span> total ports

                </div>
            </div>

            <div class="row mt-5">
                <div class="col text-center">
                    <a href="/infrastructure/aggregators/{{ $aggregator->id }}/edit" class="btn btn-sm btn-outline-dark">Edit</a>
                </div>
                <div class="col text-center">
                    <a href="/infrastructure/aggregators/{{ $aggregator->id }}" class="btn btn-sm btn-outline-dark">Show</a>
                </div>
                <div class="col text-center">
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-dark"
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
