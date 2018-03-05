@extends('layouts.app')

@section('content')

<service-location-page :location="{{ $service_location }}"></service-location-page>

@endsection
