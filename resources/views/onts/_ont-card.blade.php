<div class="col-3">
    <div class="card">
        <div class="card-header">
            <a href="/onts/{{ $ont->id }}">
                {{ $ont->model_number }}
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <ul class="list-unstyled">
                        <li><strong>Manufacturer:</strong> {{ $ont->manufacturer ?: '' }}</li>
                        <li><strong>Indoor:</strong>@if ($ont->indoor) Yes @else No @endif</li>
                        <li><strong>Wifi:</strong>@if ($ont->wifi) Yes @else No @endif</li>

                        <li><strong>Ethernet ports:</strong> {{ $ont->number_of_ethernet_ports }}</li>
                        <li><strong>POTS lines:</strong> {{ $ont->number_of_pots_lines }}</li>
                        <li><strong>Notes:</strong> {{ $ont->notes }}</li>
                        <li><strong>Files:</strong> {{ $ont->number_of_files }}</li>
                    </ul>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col text-center">
                    <a href="/onts/{{ $ont->id }}/edit" class="btn btn-link text-dark">Edit</a>
                </div>
                <div class="col text-center">
                    <a href="/onts/{{ $ont->id }}" class="btn btn-link text-dark">Show</a>
                </div>
                <div class="col text-center">
                    <button
                        type="button"
                        class="btn btn-link text-dark"
                        data-toggle="modal"
                        data-target="#delete-ont-{{ $ont->id }}"
                    >
                        Delete
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="modal fade"
                        tabindex="-1"
                        role="dialog"
                        id="delete-ont-{{ $ont->id }}"
                        aria-labelledby="delete-ont-{{ $ont->id }}-label"
                        aria-hidden="true"
                    >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5  id="delete-ont-{{ $ont->id }}-label" class="modal-title">Delete {{ $ont->model_number }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <span class="float-left">Are you sure you want to remove this ont?</span>
                                </div>
                                <div class="modal-footer">
                                    <form method="POST" action="/onts/{{ $ont->id }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                                        <button type="submit" class="btn btn-link text-dark float-right">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer"></div>
    </div>
</div>
