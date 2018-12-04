@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/onts">ONTs</a></li>
        @if ( ! request()->has('viewsoftware') )
            <li class="breadcrumb-item active" aria-current="page">{{ $ont->model_number }}</li>
        @elseif ( request()->has('viewsoftware') )
            <li class="breadcrumb-item"><a href="/onts/{{ $ont->id }}">{{ $ont->model_number }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Software Version: {{ $view_software->version }}</li>
        @endif
    </ol>
</nav>



@if ($view_software)

    @include('onts._view_software')

@else

    @include('onts._view_software_list')

@endif



@endsection
