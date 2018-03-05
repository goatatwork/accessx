@extends('layouts.app')

@section('content')

<provision-by-service-location :location="{{ $service_location }}"></provision-by-service-location>

@endsection
