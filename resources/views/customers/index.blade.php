@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Customers</li>
    </ol>
</nav>

<div class="row mb-5">
    <div class="col">

        <span class="float-right">
            <a href="/customers/create" class="btn btn-secondary float-right"><i class="material-icons mr-2">add</i>Add A Customer</a>
        </span>

    </div>
</div>

<customers-table :customers-list="{{ $customers->toJson() }}"></customers-table>

@endsection
