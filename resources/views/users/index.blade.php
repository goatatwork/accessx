@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
</nav>

<div class="row mb-5">
    <div class="col">

        <dl class="float-left">
            <dt>Total Users</dt>
            <dd>There are {{ $users->count() }} users.</dd>
        </dl>

        <span class="float-right">
            <button class="btn btn-secondary float-right" data-toggle="modal" data-target="#create-user-modal"><i class="material-icons mr-2">add</i>Add A User</button>
        </span>

    </div>
</div>

<user-management :users="{{ $users->toJson() }}" :roles="{{ $roles->toJson() }}" :permissions="{{ $permissions->toJson() }}"></user-management>
<create-user-modal></create-user-modal>

@endsection
