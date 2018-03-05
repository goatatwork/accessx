@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/onts">ONTs</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $ont->name }}</li>
        </ol>
    </nav>

    <div class="row">

        <ont-card :ont="{{ $ont }}"></ont-card>

        <div class="col-9">
            <div class="card mt-3">
                <div class="card-header">
                    <span class="h5">Files for the {{ $ont->model_number }}</span>
                </div>
                <div class="card-body">

                    @if (!$ont->media)
                        <div class="row">
                            <div class="col">
                                 There are no files
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col">
                             This is a good place to upload things like manuals, MIBs, and other reference material. These files are NOT used as operational software or configuration files.
                        </div>
                    </div>

                    <media-file-list :list-of="{{$ont->media}}" upload-component="ont-file-uploader"></media-file-list>

                    <ont-file-uploader upload-url="/api/onts/{{ $ont->id }}/files" dropzone-id="dropzone-for-ont-{{ $ont->id }}"></ont-file-uploader>

                </div> <!-- /card body -->
            </div> <!-- /card -->
        </div>
    </div>

    <div class="row">
        <div class="col">
            <ont-software :ont="{{ $ont }}"></ont-software>
        </div>
    </div>
</div>

<!-- <show-ont-page :ont="{{ $ont }}"></show-ont-page> -->

@endsection
