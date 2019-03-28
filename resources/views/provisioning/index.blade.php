@extends('layouts.app')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Provisioning Records</li>
        </ol>
    </nav>

    <div class="row mb-3">
        <div class="col">

            <span class="float-left">
                <dl>
                    <dt>Total Provisioning Records</dt>
                    <dd>There are {{ $provisioning_records->count() }} provisioning records</dd>
                </dl>
            </span>

        </div>
    </div>


    <customers-index :provisioning-records="{{ $provisioning_records->toJson() }}"></customers-index>

@endsection
