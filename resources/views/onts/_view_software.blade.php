<div class="row">
    <div class="col-auto">

        <span class="font-weight-bold">
            <i class="material-icons text-secondary">sd_storage</i>
            SOFTWARE VERSION {{ $view_software->version }}
        </span>

        <ul class="list-unstyled mt-2">
            <li>
                @if ($view_software->file)
                    <a href="#file-rename-modal-for-software-{{ $view_software->id }}" data-toggle="modal" alt="Rename File">
                        <i class="material-icons">file_copy</i>
                    </a>
                    <a href="{{ $view_software->file->getFullUrl() }}">
                        <i class="material-icons">save</i>
                    </a>
                    Filename:
                    <small>
                        <span class="font-weight-bold">
                            {{ $view_software->file->file_name }}
                        </span>
                        <br>
                    </small>
                @else
                    <i class="material-icons">save</i>
                    Filename:
                    <span class="font-italic">no file attached</span>
                @endif
            </li>
            <li>
                <i class="material-icons text-primary">router</i> This image is used on the
                <span class="font-weight-bold">{{ $ont->manufacturer }} {{ $ont->model_number }}</span>
            </li>
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
        <ont-config-file-uploader
            upload-url="/api/onts/ont_software/{{ $view_software->id }}/ont_profiles"
            dropzone-id="dropzone-for-profile-{{ $view_software->id }}">
        </ont-config-file-uploader>
    </div>
</div>

<div class="row">
    <div class="col">
        <hr>
    </div>
</div>

@if ($view_software->file)
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col">
                    <!-- Button here -->
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="modal fade"
                        id="file-rename-modal-for-software-{{ $view_software->id }}"
                        tabindex="-1"
                        role="dialog"
                        aria-labelledby="exampleModalLabel"
                        aria-hidden="true"
                    >
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('ontsoftware.update', ['ont_software' => $view_software->id]) }}" method="POST">
                                <input type="hidden" name="_method" value="PATCH">
                                <input type="hidden" name="rename" value="true">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Rename File</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="form-group"> <!-- input for old_filename -->

                                            <label for="old_filename">New Filename</label>

                                            <input
                                                type="text"
                                                name="old_filename"
                                                class="form-control form-control-sm font-italic {{ $errors->has('old_filename') ? ' is-invalid' : '' }}"
                                                id="old_filename-input"
                                                aria-describedby="old_filename-help"
                                                placeholder="{{ $view_software->file->file_name }}"
                                                value="{{ $view_software->file->file_name }}"
                                                disabled
                                            >

                                            <small id="old_filename-help" class="form-text text-muted">Disabled for easy copying and pasting....</small>

                                            @if ($errors->has('old_filename'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('old_filename') }}</strong>
                                                </div>
                                            @endif

                                        </div> <!-- input for old_filename -->

                                        <div class="form-group"> <!-- input for new_filename -->

                                            <label for="new_filename">New Filename</label>

                                            <input
                                                type="text"
                                                name="new_filename"
                                                class="form-control form-control-sm {{ $errors->has('new_filename') ? ' is-invalid' : '' }}"
                                                id="new_filename-input"
                                                aria-describedby="new_filename-help"
                                                placeholder="new_filename.img"
                                                value="{{ old('new_filename') }}"
                                            >

                                            <small id="new_filename-help" class="form-text text-muted">The new filename....</small>

                                            @if ($errors->has('new_filename'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('new_filename') }}</strong>
                                                </div>
                                            @endif

                                        </div> <!-- input for new_filename -->

                                        <div class="row">
                                            <div class="col text-center text-danger">
                                                This will apply to all profiles that use this file.<br>
                                                Be careful. Zhone ONTs are specific about what filename they will accept.
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Confirm Rename {{ $view_software->version }}</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif

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
                                @if ($view_software->ont_profiles()->count() == 1)
                                    There is 1 profile that uses this software
                                @else
                                    There are {{ $view_software->ont_profiles()->count() }} profiles that use this software
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
                        @if ($view_software->ont_profiles()->count())
                            @foreach ($view_software->ont_profiles as $profile)
                                <tr>
                                    <td class="text-center">{{ $profile->name }}</td>
                                    <td class="text-center">
                                        <small>
                                            @if ($profile->file)
                                                <a href="#profile-file-rename-modal-for-software-{{ $profile->id }}" data-toggle="modal" alt="Rename File">
                                                    <i class="material-icons">file_copy</i>
                                                </a>
                                                <a href="{{ $profile->file->getFullUrl() }}">
                                                    <i class="material-icons">save</i>
                                                </a>
                                                {{ $profile->file->file_name }}
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
                                    <td>
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
                @if ($view_software->ont_profiles()->count())
                    @foreach ($view_software->ont_profiles as $profile)
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

        <div class="row">
            <div class="col">
                @if ($view_software->ont_profiles()->count())
                    @foreach ($view_software->ont_profiles as $profile)
                        @if ($profile->file)
                            <div class="modal fade"
                                id="profile-file-rename-modal-for-software-{{ $profile->id }}"
                                tabindex="-1"
                                role="dialog"
                                aria-labelledby="exampleModalLabel"
                                aria-hidden="true"
                            >
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('ontprofile.update', ['ont_profile' => $profile->id]) }}" method="POST">
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="hidden" name="rename" value="true">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Rename File</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group"> <!-- input for old_filename -->

                                                    <label for="old_filename">New Filename</label>

                                                    <input
                                                        type="text"
                                                        name="old_filename"
                                                        class="form-control form-control-sm font-italic {{ $errors->has('old_filename') ? ' is-invalid' : '' }}"
                                                        id="old_filename-input"
                                                        aria-describedby="old_filename-help"
                                                        placeholder="{{ $profile->file->file_name }}"
                                                        value="{{ $profile->file->file_name }}"
                                                        disabled
                                                    >

                                                    <small id="old_filename-help" class="form-text text-muted">Disabled for easy copying and pasting....</small>

                                                    @if ($errors->has('old_filename'))
                                                        <div class="invalid-feedback">
                                                            <strong>{{ $errors->first('old_filename') }}</strong>
                                                        </div>
                                                    @endif

                                                </div> <!-- input for old_filename -->

                                                <div class="form-group"> <!-- input for new_filename -->

                                                    <label for="new_filename">New Filename</label>

                                                    <input
                                                        type="text"
                                                        name="new_filename"
                                                        class="form-control form-control-sm {{ $errors->has('new_filename') ? ' is-invalid' : '' }}"
                                                        id="new_filename-input"
                                                        aria-describedby="new_filename-help"
                                                        placeholder="new_filename.img"
                                                        value="{{ old('new_filename') }}"
                                                    >

                                                    <small id="new_filename-help" class="form-text text-muted">The new filename....</small>

                                                    @if ($errors->has('new_filename'))
                                                        <div class="invalid-feedback">
                                                            <strong>{{ $errors->first('new_filename') }}</strong>
                                                        </div>
                                                    @endif

                                                </div> <!-- input for new_filename -->

                                                <div class="row">
                                                    <div class="col text-center text-danger">
                                                        Be careful. Zhone ONTs are specific about what filename they will accept.
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Confirm Rename {{ $profile->name }}</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
