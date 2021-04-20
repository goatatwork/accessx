@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/provisioning">Provisioning Index</a></li>
        <li class="breadcrumb-item"><a href="{{ route('provisioning-show', [
            'provisioning_record' => $provisioning_record
        ]) }}">Show Provisioning Record</a></li>
        <li class="breadcrumb-item active" aria-current="page">Change Package</li>
    </ol>
</nav>

<div class="container">
    <div class="row">
        <div class="col-6">

            <div class="row">
              <div class="col">
                <span class="font-weight-bold">{{ $provisioning_record->getDeets()['customer_name'] }}</span>
                @if($provisioning_record->package)
                    currently has the <span class="font-weight-bold">{{ $provisioning_record->package->name }}</span> package. You can this by using the dropdown.
                @else
                    is not currently assigned a package. You can assign one by using the dropdown.
                @endif
              </div>
            </div>

            <hr>

            <div class="row">
              <div class="col">

                <form action="{{ route('change-package-action', ['provisioning_record'=>$provisioning_record]) }}"
                    method="POST">

                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <select name="package" class="custom-select">
                            @foreach(App\Package::all()->sortBy('name') as $package)
                                <option value="{{$package->id}}"
                                    @if($provisioning_record->package)
                                        @if($provisioning_record->package->id == $package->id) selected @endif
                                    @endif
                                >{{$package->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Change</button>
                    </div>
                </form>

              </div>
            </div>

        </div>
    </div>
</div>

@endsection
