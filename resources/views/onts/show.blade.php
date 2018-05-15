@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/onts">ONTs</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $ont->name }}</li>
    </ol>
</nav>

<div class="row">

    <ont-card :ont="{{ $ont }}"></ont-card>

    <div class="col-8">
        <div class="card mb-3 border-light">

            <div class="card-body">

                @if (!$ont->media)
                    <div class="row">
                        <div class="col">
                             There are no files
                        </div>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col text-dark">
                         This is a good place to upload things like manuals, MIBs, and other reference material. These files are NOT used as operational software or configuration files.
                    </div>
                </div>

                <media-file-list :list-of="{{$media_files}}" upload-component="ont-file-uploader"></media-file-list>

                <ont-file-uploader upload-url="/api/onts/{{ $ont->id }}/files" dropzone-id="dropzone-for-ont-{{ $ont->id }}"></ont-file-uploader>

            </div> <!-- /card body -->
        </div> <!-- /card -->

        <ont-software :ont="{{ $ont }}"></ont-software>
    </div>
</div>

@endsection
