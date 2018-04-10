@extends('layouts.app')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Provisioning Records</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <dl>
                                <dt>Total Provisioning Records</dt>
                                <dd>There are {{ $provisioning_records->count() }} provisioning records</dd>
                            </dl>
                        </div>
                        <div class="col">
                            <a href="/provisioning/create" class="btn btn-secondary float-right">Provisioning A Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <provisioning-records-table :provisioning-records="{{ $provisioning_records->toJson() }}"></provisioning-records-table>

@endsection
