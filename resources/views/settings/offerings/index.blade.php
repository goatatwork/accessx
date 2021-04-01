@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col pt-5">

            <ga-offerings :all-offerings="{{ $offerings }}"></ga-offerings>

        </div>
    </div>
</div>

@endsection
