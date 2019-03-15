<div class="row">
    <div class="col">

        <div class="row mb-3">
            <div class="col">

                <button
                    type="submit"
                    class="btn btn-danger btn-sm"
                    data-toggle="modal"
                    data-target="#unpopulated-slot-{{ $aggregator_slot->id }}"
                    @if ($aggregator_slot->has_provisioning_records) {{ 'disabled' }} @endif
                    >
                    Unpopulate This Slot
                </button>

                @if ($aggregator_slot->has_provisioning_records)
                <small class="font-italic">
                    <span class="fas fa-long-arrow-alt-left"></span> Disabled because there are provisioned ports here.
                </small>
                @endif

            </div>
        </div>

        <div class="row">
            <div class="col">
                <ul class="list-unstyled">
                    @foreach($aggregator_slot->ports as $port)
                        <li class="list-group-item">

                            @if ($port->has_provisioning_records)

                                <span class="float_left">
                                    <a href="/provisioning/{{ $port->provisioning_records[0]->id }}" class="btn btn-sm btn-primary">
                                        Port {{ $port->port_number }}
                                        @if ($port->module != 1)
                                            Module {{ $port->module }}
                                        @endif
                                    </a>
                                </span>

                                <span>
                                    @if ($port->provisioning_records[0]->service_location->customer->customer_type == 'Residential')
                                        <i class="material-icons">person</i>
                                    @elseif ($port->provisioning_records[0]->service_location->customer->customer_type == 'Business')
                                        <i class="material-icons">business</i>
                                    @endif
                                    <a href="/customers/{{ $port->provisioning_records[0]->service_location->customer->id }}">
                                        {{ $port->provisioning_records[0]->service_location->customer->customer_name }}
                                    </a>
                                </span>

                                <span class="float-right">
                                    {{ $port->provisioning_records[0]->ont_profile->ont_software->ont->model_number }}/{{ $port->provisioning_records[0]->ont_profile->ont_software->version }}/{{ $port->provisioning_records[0]->ont_profile->name }}
                                </span>

                            @else
                                <a href="#" class="btn btn-sm btn-secondary disabled">
                                    Port {{ $port->port_number }}
                                    @if ($port->module != 1)
                                        Module {{ $port->module }}
                                    @endif
                                </a>

                                <span class="font-italic">not provisioned</span>
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
                            <button type="submit" class="btn btn-link text-dark float-right">Unpopulate Slot</button>
                            <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
