<div class="row">
    <div class="col">

        <div class="row mb-3">
            <div class="col">
                <span class="float-left">This slot is populated with a {{ $aggregator_slot->module_type->name }}</span>
                <br>
                <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#unpopulated-slot-{{ $aggregator_slot->id }}">
                    Unpopulated This Slot
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <ul class="list-unstyled">
                    @foreach($aggregator_slot->ports as $port)
                        <li class="list-group-item">
                            Port {{ $port->port_number }}
                            @if ($port->populated)
                                provisioned
                            @else
                                not provisioned
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="modal fade"
            tabindex="-1"
            role="dialog"
            id="unpopulated-slot-{{ $aggregator_slot->id }}"
            aria-labelledby="unpopulated-slot-{{ $aggregator_slot->id }}-label"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5  id="unpopulated-slot-{{ $aggregator_slot->id }}-label" class="modal-title">Unpopulated Slot {{ $aggregator_slot->slot_number }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to unpopulate this slot?
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="/infrastructure/slots/{{ $aggregator_slot->id }}/unpopulate">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                            <button type="submit" class="btn btn-link text-dark float-right">Unpopulate Slot</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
