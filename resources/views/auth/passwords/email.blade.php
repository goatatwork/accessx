@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-5">
                        <div class="col text-center">
                            <img src="/images/gf-logo.png" class="img-logo center-block">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <form role="form" method="POST" action="{{ url('/password/email') }}">
                                {!! csrf_field() !!}

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label text-lg-right">E-Mail Address</label>

                                    <div class="col-lg-6">
                                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                                        @if ($errors->has('email'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6 offset-lg-4">
                                        <button type="submit" class="btn btn-dark">
                                            Send Password Reset Link
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
