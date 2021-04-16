@extends('layouts.app')

@section('content')


<edit-provisioning-record-page
    :provisioning-record="{{ $provisioning_record }}"
    :service-location="{{ $service_location }}"
    :speed-packages="{{ $speed_packages }}"
></edit-provisioning-record-page>


@endsection
