@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/dhcp">DHCP Shared Networks</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $dhcp_shared_network->name }}</li>
    </ol>
</nav>

<div class="row">
        <div class="col-4">

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

                    <div class="row mt-5 align-self-end">
                        <div class="col text-center">
                            <a href="/dhcp/shared_networks/{{ $dhcp_shared_network->id }}" class="btn btn-sm btn-outline-dark">Show</a>
                        </div>
                        <div class="col text-center">
                            <a href="/dhcp/shared_networks/{{ $dhcp_shared_network->id }}/edit" class="btn btn-sm btn-outline-dark">Edit</a>
                        </div>
                        <div class="col text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-dark"
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

        <div class="col-8 pt-3">

            <subnet-calculator :shared-network="{{ $dhcp_shared_network }}"></subnet-calculator>

            <div id="accordion"> <!-- accordion -->

                <!-- <add-subnet-accordion-card :shared-network="{{ $dhcp_shared_network }}"></add-subnet-accordion-card> -->

                @foreach($dhcp_shared_network->subnets as $subnet)
                    <div class="card"> <!-- card -->
                        <div class="card-header" id="slotHeading-{{ $subnet->id }}">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseArea-{{ $subnet->id }}" aria-expanded="false" aria-controls="#collapseArea-{{ $subnet->id }}">
                                    {{ $subnet->network_address }}
                                </button>
                            </h5>
                        </div>

                        <div id="collapseArea-{{ $subnet->id }}" class="collapse" aria-labelledby="slotHeading-{{ $subnet->id }}" data-parent="#accordion">
                            <div class="card-body">

                                <dhcpbot-option43-toggle :subnet="{{ $subnet }}"></dhcpbot-option43-toggle>

                                <ul class="list-unstyled">
                                    @foreach ($subnet->ip_addresses as $ip)
                                        <li class="list-group-item">{{ $ip->address }}
                                            @if($ip->has_provisioning_records)
                                                *has provisioning records
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div> <!-- /card -->
                @endforeach

            </div> <!-- /accordion -->

        </div>
    </div>
</div>

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

@endsection
