@extends('layouts.app')

@section('content')

<div class="container">
    <pre>{{ $provisioning_record->toJson() }}</pre>
</div>

@endsection
