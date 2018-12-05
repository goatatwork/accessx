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
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th colspan="5" class="border-0">
                        @if ($ont->ont_profiles()->count() == 1)
                            There is {{ $ont->ont_profiles()->count() }} profile for this ONT
                        @else
                            There are {{ $ont->ont_profiles()->count() }} profiles for this ONT
                        @endif
                    </th>
                </tr>
                <tr>
                    <th>Profile Name</th>
                    <th>Description</th>
                    <th>Software Version</th>
                    <th>File</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if ($ont->ont_profiles()->count())
                    @foreach ($ont->ont_profiles as $profile)
                        <tr>
                            <td>{{ $profile->name }}</td>
                            <td>{{ $profile->notes }}</td>
                            <td>
                                <a href="?viewsoftware={{ $profile->ont_software->id }}">
                                    {{ $profile->ont_software->version }}
                                </a>
                            </td>
                            <td>
                                @if ($profile->file)
                                    <a href="{{ $profile->file->url }}">{{ $profile->file->file_name }}</a>
                                    {{$profile->file->human_readable_size}}
                                @else
                                    'no file'
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-auto">
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th>
                        Software Version
                    </th>
                    <th>
                        Number Of Profiles
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
                                {{ $software->ont_profiles()->count() }}
                            </td>
                        </tr>
                    @endforeach

                @else

                    <tr>
                        <td colspan="2">There is no software here</td>
                    </tr>

                @endif
            </tbody>
        </table>
    </div>
</div>
