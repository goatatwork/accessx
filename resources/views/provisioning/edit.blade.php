@extends('layouts.app')

@section('content')

<div class="container">
    <edit-provisioning-record-page :provisioning-record="{{ $provisioning_record }}" :service-location="{{ $service_location }}"></edit-provisioning-record-page>
</div>

@endsection
