@extends('layouts.app')

@section('content')


<edit-provisioning-record-page :provisioning-record="{{ $provisioning_record }}" :service-location="{{ $service_location }}"></edit-provisioning-record-page>


@endsection
