<div class="row">
    <div class="col-auto">

        <span class="font-weight-bold">
            <i class="material-icons text-secondary">sd_storage</i>
            SOFTWARE VERSION {{ $view_software->version }}
        </span>

        <ul class="list-unstyled mt-2">
            <li>
                <i class="material-icons text-primary">router</i> This image is used on the {{ $ont->manufacturer }} {{ $ont->model_number }}
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

        <div class="row">
            <div class="col">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th colspan="4" class="border-0">
                                @if ($view_software->ont_profiles()->count() == 1)
                                    There is 1 profile that uses this software
                                @else
                                    There are {{ $view_software->ont_profiles()->count() }} profiles that use this software
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th>Profile</th>
                            <th>File Name</th>
                            <th>Notes</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($view_software->ont_profiles()->count())
                            @foreach ($view_software->ont_profiles as $profile)
                                <tr>
                                    <td>{{ $profile->name }}</td>
                                    <td>
                                        @if ($profile->file)
                                            <a href="{{ $profile->file->getUrl() }}">{{ $profile->file->file_name }}</a>{{ $profile->file->human_readable_size }}
                                        @else
                                            'no file'
                                        @endif
                                    </td>
                                    <td>{{ $profile->notes }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm"
                                            data-toggle="modal"
                                            data-target="#delete-modal-for-profile-{{ $profile->id }}"
                                        >
                                            Delete
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">There are no profiles here</td>
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

    </div>
</div>
