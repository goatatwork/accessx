@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">DHCP</li>
    </ol>
</nav>

<div class="row">
    <div class="col">

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col">
                        <dl>
                            <dt>Total Shared Networks</dt>
                            <dd>There are {{ $dhcp_shared_networks->count() }} shared networks.</dd>
                        </dl>
                    </div>
                    <div class="col">
                        <a href="/dhcp/shared_networks/create" class="btn btn-secondary float-right">Add A Shared Network</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="row">
    @foreach($dhcp_shared_networks as $dhcp_shared_network)
        <div class="col-3">
            <div class="card mt-3 mb-3">
                <div class="card-header">

                    <span class="float-left">
                        <a href="/dhcp/shared_networks/{{ $dhcp_shared_network->id }}" class="text-dark">{{ $dhcp_shared_network->name }}</a>
                    </span>

                    <span class="float-right">
                        @if ($dhcp_shared_network->management)
                            <span class="fas fa-certificate"></span> <small class="font-italic">Management</small>
                        @endif
                    </span>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="badge badge-info float-right" style="margin-top:-1.5em;margin-right:-1.5em;">
                                VLAN <span class="badge badge-info">{{ $dhcp_shared_network->vlan }}</span>
                                <span class="sr-only">VLAN for this shared network</span>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="media mt-3">
                                <span class="fas fa-2x fa-cloud text-dark mr-3"></span>
                                <div class="media-body">
                                    @if ($dhcp_shared_network->subnets()->count() > 1)
                                        <span class="font-weight-bold">{{ $dhcp_shared_network->subnets()->count() }}</span> Subnets
                                    @elseif ($dhcp_shared_network->subnets()->count() == 1)
                                        <span class="font-weight-bold">1</span> Subnet
                                    @else
                                        <span class="font-weight-bold">0</span> Subnets
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="media mt-3">
                                <span class="fas fa-2x fa-leaf text-dark mr-3"></span>
                                <div class="media-body">
                                    @if ($dhcp_shared_network->ip_addresses()->count() > 1)
                                        <span class="font-weight-bold">{{ $dhcp_shared_network->ip_addresses()->count() }}</span> IP Addresses
                                    @elseif ($dhcp_shared_network->ip_addresses()->count() == 1)
                                        <span class="font-weight-bold">1</span> IP Address
                                    @else
                                        <span class="font-weight-bold">0</span> IP Addresses
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="media mt-3">
                                <span class="fas fa-2x fa-file-alt text-dark mr-3"></span>
                                <div class="media-body">
                                    @if ($dhcp_shared_network->notes)
                                        <button
                                            ype="button"
                                            class="btn btn-link text-dark"
                                            data-toggle="modal"
                                            data-target="#shared-network-notes-{{ $dhcp_shared_network->id }}"
                                        >
                                            View Notes
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            class="btn btn-link text-dark"
                                            data-toggle="modal"
                                            data-target="#shared-network-notes-{{ $dhcp_shared_network->id }}" disabled
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
                            <a href="/dhcp/shared_networks/{{ $dhcp_shared_network->id }}/edit" class="btn btn-link text-dark">Edit</a>
                        </div>
                        <div class="col text-center">
                            <a href="/dhcp/shared_networks/{{ $dhcp_shared_network->id }}" class="btn btn-link text-dark">Show</a>
                        </div>
                        <div class="col text-center">

                            <button
                                type="button"
                                class="btn btn-link text-dark"
                                data-toggle="modal"
                                data-target="#delete-shared-network-{{ $dhcp_shared_network->id }}"
                            >
                                Delete
                            </button>

                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <small>
                        <span class="font-italic">
                            Created on {{ $dhcp_shared_network->created_at->toFormattedDateString() }} at {{ $dhcp_shared_network->created_at->toTimeString() }}
                        </span>
                    </small>
                </div>
            </div>
        </div>
    @endforeach
</div>

@foreach($dhcp_shared_networks as $dhcp_shared_network)
    <div class="modal fade"
        tabindex="-1"
        role="dialog"
        id="shared-network-notes-{{ $dhcp_shared_network->id }}"
        aria-labelledby="shared-network-notes-{{ $dhcp_shared_network->id }}-label"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  id="shared-network-notes-{{ $dhcp_shared_network->id }}-label" class="modal-title">{{ $dhcp_shared_network->name }} Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <marked-content marked-content="{{ $dhcp_shared_network->notes }}"></marked-content>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@foreach($dhcp_shared_networks as $dhcp_shared_network)
    <div class="modal fade"
        tabindex="-1"
        role="dialog"
        id="delete-shared-network-{{ $dhcp_shared_network->id }}"
        aria-labelledby="delete-shared-network-{{ $dhcp_shared_network->id }}-label"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  id="delete-shared-network-{{ $dhcp_shared_network->id }}-label" class="modal-title">Delete {{ $dhcp_shared_network->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove this shared network?
                </div>
                <div class="modal-footer">
                    <form method="POST" action="/dhcp/shared_networks/{{ $dhcp_shared_network->id }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                        <button type="submit" class="btn btn-link text-dark float-right">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
