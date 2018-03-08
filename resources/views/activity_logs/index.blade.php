@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Activity Logs</li>
        </ol>
    </nav>

    <activity-logs-table :activity-logs="{{ $activity_logs }}"></activity-logs-table>

</div>

@endsection
