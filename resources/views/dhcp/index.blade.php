@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">DHCP</li>
    </ol>
</nav>

<div class="row px-3 mb-3">
    <div class="col">

        <dl class="float-left">
            <dt>Total Shared Networks</dt>
            <dd>There are {{ $dhcp_shared_networks->count() }} shared networks.</dd>
            <dd>The default lease time is {{ config('goldaccess.settings.dhcp_default_lease_time') }}.
                <small>As per the <a href="/settings" class="text-info">system settings</a></small>
            </dd>
        </dl>

    </div>
    <div class="col col-auto d-flex align-items-end pb-3">
        <div class="mr-2">
            <a href="{{ route('dhcp.leases') }}" class="btn btn-sm btn-primary">DHCP Leases</a>
        </div>
        <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#dhcp-config-modal">View DHCP Configuration</button>
    </div>
</div>

<div class="row px-3">
    <div class="col">
        <table class="table table-sm table-hover">
            <thead class="">
                <tr>
                    <th>Shared Network</th>
                    <th>Subnets</th>
                    <th>IPs</th>
                    <th>VLAN</th>
                    <th>Notes</th>
                    <th class="text-right">
                        <a href="/dhcp/shared_networks/create" class="btn btn-sm btn-outline-dark">
                            <i class="material-icons mr-1">add</i>
                            Add A Shared Network
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($dhcp_shared_networks as $sn)
                <tr>
                    <td>
                        <a href="/dhcp/shared_networks/{{ $sn->id }}" class="text-dark">{{ $sn->name }}</a>
                    </td>
                    <td>{{ $sn->subnets_count }}</td>
                    <td>{{ $sn->ip_addresses_count }}</td>
                    <td>{{ $sn->vlan }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($sn->notes, 50) }}</td>
                    <td class="text-right">

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-dark"
                            data-toggle="modal"
                            data-target="#delete-shared-network-{{ $sn->id }}"
                        >
                            Delete
                        </button>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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

    <div class="modal fade"
        tabindex="-1"
        role="dialog"
        id="dhcp-config-modal"
        aria-labelledby="dhcp-config-modal-label"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  id="dhcp-config-modal-label" class="modal-title">Current Dnsmasq Configuration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col">
<pre>
{{ $dnsmasq_config }}
</pre>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>

@endsection
