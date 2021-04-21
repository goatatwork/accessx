@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/provisioning">Provisioning Records</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            A Provisioning Record For {{ $provisioning_record->service_location->customer->customer_name }}
            @if($provisioning_record->len)
                <strong>LEN: {{$provisioning_record->len}}</strong>
            @endif
        </li>
    </ol>
</nav>

<div class="row">
  <div class="col">
    @if(session('status')) status @endif
    @if(session('flash')) flash @endif
  </div>
</div>

<div class="row">
    <div class="col-4">

        <div class="row">
            <div class="col">
                <service-location-card :location="{{ $provisioning_record->service_location }}"></service-location-card>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">

                        <div class="row mb-2">
                            <div class="col">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-dark"
                                    data-toggle="modal"
                                    data-target="#show-dnsmasq-config-file-{{ $provisioning_record->id }}"
                                >
                                    <span class="fas fa-file-alt mr-1"></span> View DHCP Config File
                                </button>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    data-toggle="modal"
                                    data-target="#delete-service-modal-{{ $provisioning_record->id }}"
                                >
                                    <span class="fas fa-trash-alt mr-1"></span> Remove Service
                                </button>
                            </div>
                        </div>

                        @if($provisioning_record->is_suspended)
                        <div class="row mb-2">
                            <div class="col">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-secondary"
                                    data-toggle="modal"
                                    data-target="#unsuspend-service-modal-{{ $provisioning_record->id }}"
                                >
                                    <span class="fas fa-pause-circle mr-1"></span> Unsuspend Service
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="row mb-2">
                            <div class="col">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    data-toggle="modal"
                                    data-target="#suspend-service-modal-{{ $provisioning_record->id }}"
                                >
                                    <span class="fas fa-pause-circle mr-1"></span> Suspend Service
                                </button>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-2">
                            <div class="col">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    data-toggle="modal"
                                    data-target="#factory-reset-ont-modal-{{ $provisioning_record->id }}"
                                >
                                    <span class="fas fa-recycle mr-1"></span> Reset To Factory Defaults
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <a href="http://{{ $provisioning_record->ip_address->address }}" target="_blank"
                                    class="btn btn-sm btn-dark"
                                ><span class="fas fa-external-link-alt mr-1"></span> Open ONT Web Interface</a>
                            </div>
                            <div class="col">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal fade"
                    tabindex="-1"
                    role="dialog"
                    id="show-dnsmasq-config-file-{{ $provisioning_record->id }}"
                    aria-labelledby="show-dnsmasq-config-file-{{ $provisioning_record->id }}-label"
                    aria-hidden="true"
                >
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5  id="show-dnsmasq-config-file-{{ $provisioning_record->id }}-label" class="modal-title">DHCP Config</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Here is the DHCP config that is being loaded for this service.</p>

                                @if (app('dhcpbot')->isDeployed($provisioning_record, 'dhcp_management_ip'))
                                <pre>
{{ \Storage::disk('dhcp_configs')->get(app('dhcpbot')->getDeployPath($provisioning_record, 'dhcp_management_ip'))}}
                                </pre>
                                @endif
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
                    id="delete-service-modal-{{ $provisioning_record->id }}"
                    aria-labelledby="delete-service-modal-{{ $provisioning_record->id }}-label"
                    aria-hidden="true"
                >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5  id="delete-service-modal-{{ $provisioning_record->id }}-label" class="modal-title">Remove Service</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>This will tear down this service. Are you sure?</p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="/provisioning/{{ $provisioning_record->id }}">
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
                    id="factory-reset-ont-modal-{{ $provisioning_record->id }}"
                    aria-labelledby="factory-reset-ont-modal-{{ $provisioning_record->id }}-label"
                    aria-hidden="true"
                >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5  id="factory-reset-ont-modal-{{ $provisioning_record->id }}-label" class="modal-title">Factory Reset</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form method="POST" action="/provisioning/{{ $provisioning_record->id }}/factory">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <div class="modal-body">

                                    Are you sure you wish to reset this ONT to factory defaults?

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>

                                    <button type="submit" class="btn btn-link text-dark float-right">Yes, Reset This ONT To Factory Defaults</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="modal fade show"
                    tabindex="-1"
                    role="dialog"
                    id="factory-reset-status-modal"
                    aria-labelledby="factory-reset-status-modal-label"
                    aria-hidden="true"
                >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5  id="factory-reset-status-modal-label" class="modal-title">Status</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                Resetting!
                                </p>

                                <p>
                                    The ONT is being reset to factory defaults. This can take up to 10 minutes. Please see the <a href="/activity_logs">Activity Logs</a> for more information.
                                </p>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light float-right" data-dismiss="modal">Dismiss</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade"
                    tabindex="-1"
                    role="dialog"
                    id="suspend-service-modal-{{ $provisioning_record->id }}"
                    aria-labelledby="suspend-service-modal-{{ $provisioning_record->id }}-label"
                    aria-hidden="true"
                >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5  id="suspend-service-modal-{{ $provisioning_record->id }}-label" class="modal-title">Suspend Service</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="/provisioning/{{ $provisioning_record->id }}/suspend">
                                <div class="modal-body">

                                    @if (! $provisioning_record->ont_profile->ont_software->has_suspend_config)
                                        <p>
                                            The {{ $provisioning_record->ont_profile->ont_software->version }} software does not currently have a "Suspended" profile.  You can create one <a href="/onts/{{ $provisioning_record->ont_profile->ont_software->ont->id }}">here.</a><br>
                                        </p>
                                    @else
                                        <p>This will suspend service. Are you sure?</p>
                                        <div class="form-group">
                                            <label for="notes">Notes</label>
                                            <textarea class="form-control" name="notes"></textarea>
                                        </div>
                                    @endif

                                </div>
                                <div class="modal-footer">
                                        {{ csrf_field() }}
                                        {{ method_field('PATCH') }}
                                        <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                                        @if ($provisioning_record->ont_profile->ont_software->has_suspend_config)
                                            <button type="submit" class="btn btn-link text-dark float-right">Yes, Suspend Service</button>
                                        @endif
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="modal fade"
                    tabindex="-1"
                    role="dialog"
                    id="unsuspend-service-modal-{{ $provisioning_record->id }}"
                    aria-labelledby="unsuspend-service-modal-{{ $provisioning_record->id }}-label"
                    aria-hidden="true"
                >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5  id="unsuspend-service-modal-{{ $provisioning_record->id }}-label" class="modal-title">Suspend Service</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form method="POST" action="/provisioning/{{ $provisioning_record->id }}/unsuspend">
                                <div class="modal-body">
                                    <p>This will unsuspend service. Are you sure?</p>

                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" name="notes"></textarea>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                        {{ csrf_field() }}
                                        {{ method_field('PATCH') }}
                                        <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                                        <button type="submit" class="btn btn-link text-dark float-right">Yes, Unsuspend Service</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>


                <div class="modal fade show"
                    tabindex="-1"
                    role="dialog"
                    id="suspend-status-modal"
                    aria-labelledby="suspend-status-modal-label"
                    aria-hidden="true"
                >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5  id="suspend-status-modal-label" class="modal-title">Status</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                Suspended!
                                </p>

                                <p>
                                    The ONT is rebooting. This can take up to 10 minutes. Please see the <a href="/activity_logs">Activity Logs</a> for more information.
                                </p>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light float-right" data-dismiss="modal">Dismiss</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade show"
                    tabindex="-1"
                    role="dialog"
                    id="unsuspend-status-modal"
                    aria-labelledby="unsuspend-status-modal-label"
                    aria-hidden="true"
                >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5  id="unsuspend-status-modal-label" class="modal-title">Status</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                Unuspended! Service has been set back to the <strong>{{ $provisioning_record->ont_profile->name }}</strong> profile.
                                </p>

                                <p>
                                    The ONT is rebooting. This can take up to 10 minutes. Please see the <a href="/activity_logs">Activity Logs</a> for more information.
                                </p>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light float-right" data-dismiss="modal">Dismiss</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
    <div class="col-8">
        @if($provisioning_record->is_suspended)
            <div class="row">
                <div class="col">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Please note:</strong> Service is suspended for this ONT.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <iframe
                            width="400"
                            height="200"
                            frameborder="0"
                            style="border:0"
                            src="{{ $provisioning_record->service_location->google_maps_embed_api_string }}"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 mb-5 justify-content-center">
    <div class="col">
        <table class="table table-sm">
            <thead>
                <tr>
                    <td colspan="7" class="border-0 text-center">
                        <h2>Details for This Provisioning Record</h2>
                    </td>
                </tr>
                <tr>
                    <th class="text-center border-0"></th>
                    <th class="text-center border-0">Management IP</th>
                    <th class="text-center border-0">NetLocation</th>
                    <th class="text-center border-0">ONT/Software</th>
                    <th class="text-center border-0">Speed</th>
                    <th class="text-center border-0">Package</th>
                    <th class="text-center border-0">LEN</th>
                    @if ($provisioning_record->circuit_id != '')
                        <th class="text-center border-0">Circuit ID</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="/provisioning/{{ $provisioning_record->id }}/edit" class="btn btn-sm btn-dark">EDIT</a></td>

                    <td class="text-center">
                        <a href="http://{{ $provisioning_record->ip_address->address }}" target="_blank"
                            class="btn btn-sm btn-dark"
                        >
                        {{ $provisioning_record->ip_address->address }}</a>
                    </td>
                    <td class="text-center">
                        <small>
                            {{ $provisioning_record->port->slot->aggregator->name }}
                            <span class="fas fa-long-arrow-alt-right"></span>
                            Slot {{ $provisioning_record->port->slot->slot_number }}
                            <span class="fas fa-long-arrow-alt-right"></span>
                            Port {{ $provisioning_record->port->port_number }}
                        </small>
                    </td>
                    <td class="text-center">
                        <small>
                            {{ $provisioning_record->ont_profile->ont_software->ont->model_number }}
                            <span class="fas fa-long-arrow-alt-right"></span>
                            {{ $provisioning_record->ont_profile->ont_software->version }}
                        </small>
                    </td>

                    <td class="text-center">
                        <small>
                            @if ($provisioning_record->packages()->first())
                                {{ $provisioning_record->packages->sortByDesc('pivot.created_at')->first()->name }}
                            @else
                                No Package Selected
                            @endif

                            <a href="{{ route('change-package', [
                                'provisioning_record'=>$provisioning_record->id,
                            ]) }}" class="text-info">[Edit]</a>
                            </a>
                        </small>
                    </td>
                    <td class="text-center">

                        @if(count($provisioning_record->ont_profile->ont_software->ont_profiles) < 2)
                            {{ $provisioning_record->ont_profile->name }}
                        @else
                            <instant-edit-select
                                div-id="instant-select-component"
                                select-id="select-0"
                                select-options="{{ $other_possible_packages_json }}"
                                current-value="{{ $provisioning_record->ont_profile->id }}"
                                form-action="/api/provisioning/{{ $provisioning_record->id }}"
                                success-action="/provisioning/{{ $provisioning_record->id }}"
                                model-name="Package"
                                field-to-patch="ont_profile_id"
                            >

                            </instant-edit-select>
                        @endif

                    </td>
                    <td class="text-center font-italic">
                        {{ $provisioning_record->len ? $provisioning_record->len : '' }}
                    </td>
                    @if ($provisioning_record->circuit_id != '')
                        <td class="text-center font-italic">
                            {{ $provisioning_record->circuit_id ? $provisioning_record->circuit_id : '' }}
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('footer-scripts')
    @if (session('status') == 'suspended')
        <script type="text/javascript">
            $(document).ready(function(){
                $("#suspend-status-modal").modal('show');
            });
        </script>
    @endif

    @if (session('status') == 'unsuspended')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#unsuspend-status-modal").modal('show');
        });
    </script>
    @endif

    @if (session('status') == 'factory')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#factory-reset-status-modal").modal('show');
        });
    </script>
    @endif
@endsection
