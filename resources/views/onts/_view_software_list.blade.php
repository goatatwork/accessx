<div class="row">
    <div class="col-auto">

        <span class="font-weight-bold">
            You are viewing details for the {{ $ont->manufacturer }} {{ $ont->model_number }}
        </span>

        <ul class="list-unstyled mt-2">
            <li>
                @if ($ont->indoor)
                    <i class="material-icons text-primary">home</i> This is an indoor ONT
                @else
                    <i class="material-icons text-success">landscape</i> This is an outdoor ONT
                @endif
            </li>
            <li>
                @if ($ont->wifi)
                    <i class="material-icons text-success">wifi</i> This ONT has wifi
                @else
                    <i class="material-icons">wifi_off</i> This ONT does not have wifi
                @endif
            </li>
            <li>
                @if ($ont->oem)
                    <i class="material-icons text-warning">new_releases</i> This ONT is OEM branded
                @endif
            </li>
            <li>
                <i class="material-icons text-primary">device_hub</i> {{ $ont->number_of_ethernet_ports }} Ethernet ports
            </li>
            <li>
                <i class="material-icons text-primary">phone</i> {{ $ont->number_of_pots_lines }} POTS lines
            </li>
        </ul>
    </div>

    <div class="col">
        <div class="row">
            <div class="col">
                <ont-software-file-uploader
                    upload-url="/api/onts/{{ $ont->id }}/software"
                    dropzone-id="dropzone-for-ont-software-{{ $ont->id }}"
                ></ont-software-file-uploader>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <hr>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-10">

        <div class="row">
            <div class="col">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th colspan="7" class="text-center border-0">
                                @if ($ont->ont_software()->count() == 1)
                                    There is 1 Software Images For This ONT
                                @else
                                    There are {{ $ont->ont_software()->count() }} Software Images For This ONT
                                @endif
                                <small>
                                    @if ($ont->ont_software()->count())
                                        <span class="text-muted float-right">
                                            Click on a software version to add profiles for it
                                        </span>
                                    @endif
                                </small>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">
                                Software
                            </th>
                            <th class="text-center">
                                File Name
                            </th>
                            <th class="text-left">
                                Size
                            </th>
                            <th class="text-center">
                                Profiles
                            </th>
                            <th class="text-center">
                                Customers
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($ont->ont_software()->count())

                            @foreach($ont->ont_software as $software)
                                <tr>
                                    <td class="text-center">
                                        <a href="?viewsoftware={{ $software->id }}">
                                            {{ $software->version }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <small>
                                            @if ($software->file)
                                                <a href="{{ $software->file->getUrl() }}">{{ $software->file->file_name }}</a>
                                            @else
                                                <span class="font-italic">--</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-left">
                                        <small>
                                            @if ($software->file)
                                                {{ $software->file->human_readable_size }}
                                            @else
                                                <span class="font-italic">--</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        {{ $software->ont_profiles()->count() }}
                                    </td>
                                    <td class="text-center">
                                        {{ $software->provisioning_records()->count() }}
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm float-right"
                                            data-toggle="modal"
                                            data-target="#delete-modal-for-software-{{ $software->id }}"
                                            @if ($software->provisioning_records()->count()) disabled @endif
                                        >
                                            Delete {{ $software->version }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        @else

                            <tr>
                                <td colspan="7">There is no software here</td>
                            </tr>

                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col">
                @if ($ont->ont_software()->count())
                    @foreach ($ont->ont_software as $software)
                        <div class="modal fade"
                            id="delete-modal-for-software-{{ $software->id }}"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="exampleModalLabel"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog" role="document">
                                <form action="/onts/ont_software/{{ $software->id }}" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete ONT Software</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to remove the <span class="font-weight-bold">{{ $software->version }}</span> software?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Confirm Delete {{ $software->version }}</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col">
        <hr>
    </div>
</div>

<div class="row">
    <div class="col">

        <div class="row">
            <div class="col">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th colspan="6" class="text-center border-0">
                                @if ($ont->ont_profiles()->count() == 1)
                                    There is 1 profile for this ONT
                                @else
                                    There are {{ $ont->ont_profiles()->count() }} profiles for this ONT
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">Profile</th>
                            <th class="text-center">File Name</th>
                            <th class="text-left">Size</th>
                            <th class="text-center">Software</th>
                            <th class="text-center">Customers Using</th>
                            <th class="text-center">Notes</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($ont->ont_profiles()->count())
                            @foreach ($ont->ont_profiles as $profile)
                                <tr>
                                    <td class="text-center">{{ $profile->name }}</td>
                                    <td class="text-center">
                                        <small>
                                            @if ($profile->file)
                                                <a href="{{ $profile->file->getUrl() }}">{{ $profile->file->file_name }}</a>
                                            @else
                                                <span class="font-italic">--</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-left">
                                        <small>
                                            @if ($profile->file)
                                                {{ $profile->file->human_readable_size }}
                                            @else
                                                <span class="font-italic">--</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        {{ $profile->ont_software->version }}
                                    </td>
                                    <td class="text-center">
                                        {{ $profile->provisioning_records()->count() }}
                                    </td>
                                    <td class="text-left">
                                        <small>
                                            {{ $profile->notes }}
                                        </small>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm"
                                            data-toggle="modal"
                                            data-target="#delete-modal-for-profile-{{ $profile->id }}"
                                            @if ($profile->provisioning_records()->count()) disabled @endif
                                        >
                                            Delete
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">There are no profiles here</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col">
                @if ($ont->ont_profiles()->count())
                    @foreach ($ont->ont_profiles as $profile)
                        <div class="modal fade"
                            id="delete-modal-for-profile-{{ $profile->id }}"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="exampleModalLabel"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog" role="document">
                                <form action="/onts/ont_profiles/{{ $profile->id }}" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete ONT Profile</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to remove the <span class="font-weight-bold">{{ $profile->name }}</span> profile?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Confirm Delete {{ $profile->name }}</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</div>
