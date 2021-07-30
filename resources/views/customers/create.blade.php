@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/customers">Customers</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create A Customer</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-10 text-center">
        <h4>Create A Customer</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-10">

        <form method="POST" action="/customers">
            {{ method_field('POST') }}
            {{ csrf_field() }}

            <div class="form-group row"> <!-- company_name -->
                <div class="col text-right">
                    <label for="company_name" class="col-form-label">Company Name</label>
                </div>
                <div class="col">
                    <input
                            id="company_name"
                            type="text"
                            class="form-control{{ $errors->has('company_name') ? ' is-invalid' : '' }}"
                            name="company_name"
                            value="{{ old('company_name') }}"
                    >

                    @if ($errors->has('company_name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('company_name') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /company_name -->

            <div class="form-group row"> <!-- first_name -->
                <div class="col text-right">
                    <label for="first_name" class="col-form-label">First Name</label>
                </div>
                <div class="col">
                    <input
                            id="first_name"
                            type="text"
                            class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            required
                    >

                    @if ($errors->has('first_name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /first_name -->

            <div class="form-group row"> <!-- last_name -->
                <div class="col text-right">
                    <label for="last_name" class="col-form-label">Last Name</label>
                </div>
                <div class="col">
                    <input
                            id="last_name"
                            type="text"
                            class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            required
                    >

                    @if ($errors->has('last_name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /last_name -->

            <div class="form-group row"> <!-- address1 -->
                <div class="col text-right">
                    <label for="address1" class="col-form-label">Address</label>
                </div>
                <div class="col">
                    <input
                            id="address1"
                            type="text"
                            class="form-control{{ $errors->has('address1') ? ' is-invalid' : '' }}"
                            name="address1"
                            value="{{ old('address1') }}"
                            required
                    >

                    @if ($errors->has('address1'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('address1') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /address1 -->

            <div class="form-group row"> <!-- address2 -->
                <div class="col text-right">
                    <label for="address2" class="col-form-label">Address</label>
                </div>
                <div class="col">
                    <input
                            id="address2"
                            type="text"
                            class="form-control{{ $errors->has('address2') ? ' is-invalid' : '' }}"
                            name="address2"
                            value="{{ old('address2') }}"
                    >

                    @if ($errors->has('address2'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('address2') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /address2 -->

            <div class="form-group row"> <!-- city -->
                <div class="col text-right">
                    <label for="city" class="col-form-label">City</label>
                </div>
                <div class="col">
                    <input
                            id="city"
                            type="text"
                            class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
                            name="city"
                            value="{{ old('city') }}"
                            required
                    >

                    @if ($errors->has('city'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('city') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /city -->

            <div class="form-group row"> <!-- state -->
                <div class="col text-right">
                    <label for="state" class="col-form-label">State</label>
                </div>
                <div class="col">
                    <select class="custom-select" name="state" required>
                        <option selected>Select A State</option>
                        @foreach(App\State::all()->sortBy('name') as $state)
                            <option value="{{ $state->code }}">{{ $state->name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('state'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('state') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- /state -->

            <div class="form-group row"> <!-- zip -->
                <div class="col text-right">
                    <label for="zip" class="col-form-label">Zip</label>
                </div>
                <div class="col">
                    <input
                            id="zip"
                            type="text"
                            class="form-control{{ $errors->has('zip') ? ' is-invalid' : '' }}"
                            name="zip"
                            value="{{ old('zip') }}"
                            required
                    >

                    @if ($errors->has('zip'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('zip') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- zip -->

            <div class="form-group row"> <!-- phone1 -->
                <div class="col text-right">
                    <label for="phone1" class="col-form-label">Phone</label>
                </div>
                <div class="col">
                    <input
                            id="phone1"
                            type="text"
                            class="form-control{{ $errors->has('phone1') ? ' is-invalid' : '' }}"
                            name="phone1"
                            value="{{ old('phone1') }}"
                    >

                    @if ($errors->has('phone1'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('phone1') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- phone1 -->

            <div class="form-group row"> <!-- phone2 -->
                <div class="col text-right">
                    <label for="phone2" class="col-form-label">Alt. Phone</label>
                </div>
                <div class="col">
                    <input
                            id="phone2"
                            type="text"
                            class="form-control{{ $errors->has('phone2') ? ' is-invalid' : '' }}"
                            name="phone2"
                            value="{{ old('phone2') }}"
                    >

                    @if ($errors->has('phone2'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('phone2') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- phone2 -->

            <div class="form-group row"> <!-- email -->
                <div class="col text-right">
                    <label for="email" class="col-form-label">Email</label>
                </div>
                <div class="col">
                    <input
                            id="email"
                            type="email"
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                            name="email"
                            value="{{ old('email') }}"
                    >

                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- phone1 -->

            <div class="form-group row"> <!-- notes -->
                <div class="col text-right">
                    <label for="notes" class="col-form-label">Notes</label>
                </div>
                <div class="col">
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>

                    @if ($errors->has('notes'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('notes') }}</strong>
                        </div>
                    @endif
                </div>
            </div> <!-- notes -->

            <div class="form-group row"> <!-- use_same_address_for_billing -->
                <div class="col text-right">

                    <div class="form-check">
                        <input id="use_same_address_for_billing" type="checkbox" name="use_same_address_for_billing" class="form-check-input" checked>
                        <label for="use_same_address_for_billing" class="form-check-label">Use the same address for billing</label>
                    </div>

                    @if ($errors->has('use_same_address_for_billing'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('use_same_address_for_billing') }}</strong>
                        </div>
                    @endif

                </div>
            </div> <!-- use_same_address_for_billing -->

            <!-- BILLING INFORMATION -->
            <div id="billing-info" style="display:none;">
                <div class="form-group row"> <!-- billing_contact_name -->
                    <div class="col text-right">
                        <label for="billing_contact_name" class="col-form-label">Billing Contact Name</label>
                    </div>
                    <div class="col">
                        <input
                                id="billing_contact_name"
                                type="text"
                                class="form-control{{ $errors->has('billing_contact_name') ? ' is-invalid' : '' }}"
                                name="billing_contact_name"
                                value="{{ old('billing_contact_name') }}"
                        >

                        @if ($errors->has('billing_contact_name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_contact_name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- /billing_contact_name -->

                <div class="form-group row"> <!-- billing_contact_email -->
                    <div class="col text-right">
                        <label for="billing_contact_email" class="col-form-label">Biling Contact Email</label>
                    </div>
                    <div class="col">
                        <input
                                id="billing_contact_email"
                                type="billing_contact_email"
                                class="form-control{{ $errors->has('billing_contact_email') ? ' is-invalid' : '' }}"
                                name="billing_contact_email"
                                value="{{ old('billing_contact_email') }}"
                        >

                        @if ($errors->has('billing_contact_email'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_contact_email') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- billing_contact_email -->

                <div class="form-group row"> <!-- billing_address1 -->
                    <div class="col text-right">
                        <label for="billing_address1" class="col-form-label">Billing Address</label>
                    </div>
                    <div class="col">
                        <input
                                id="billing_address1"
                                type="text"
                                class="form-control{{ $errors->has('billing_address1') ? ' is-invalid' : '' }}"
                                name="billing_address1"
                                value="{{ old('billing_address1') }}"
                        >

                        @if ($errors->has('billing_address1'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_address1') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- /billing_address1 -->

                <div class="form-group row"> <!-- billing_address2 -->
                    <div class="col text-right">
                        <label for="billing_address2" class="col-form-label">Billing Address</label>
                    </div>
                    <div class="col">
                        <input
                                id="billing_address2"
                                type="text"
                                class="form-control{{ $errors->has('billing_address2') ? ' is-invalid' : '' }}"
                                name="billing_address2"
                                value="{{ old('billing_address2') }}"
                        >

                        @if ($errors->has('billing_address2'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_address2') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- /billing_address2 -->

                <div class="form-group row"> <!-- billing_city -->
                    <div class="col text-right">
                        <label for="billing_city" class="col-form-label">Billing City</label>
                    </div>
                    <div class="col">
                        <input
                                id="billing_city"
                                type="text"
                                class="form-control{{ $errors->has('billing_city') ? ' is-invalid' : '' }}"
                                name="billing_city"
                                value="{{ old('billing_city') }}"
                        >

                        @if ($errors->has('billing_city'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_city') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- /billing_city -->

                <div class="form-group row"> <!-- billing_state -->
                    <div class="col text-right">
                        <label for="billing_state" class="col-form-label">Billing State</label>
                    </div>
                    <div class="col">
                        <select class="custom-select" name="billing_state" required>
                            <option selected>Select A Billing State</option>
                            @foreach(App\State::all()->sortBy('name') as $billing_state)
                                <option value="{{ $billing_state->code }}">{{ $billing_state->name }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('billing_state'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_state') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- /billing_state -->

                <div class="form-group row"> <!-- billing_zip -->
                    <div class="col text-right">
                        <label for="billing_zip" class="col-form-label">Billing Zip</label>
                    </div>
                    <div class="col">
                        <input
                                id="billing_zip"
                                type="text"
                                class="form-control{{ $errors->has('billing_zip') ? ' is-invalid' : '' }}"
                                name="billing_zip"
                                value="{{ old('billing_zip') }}"
                        >

                        @if ($errors->has('billing_zip'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_zip') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- billing_zip -->

                <div class="form-group row"> <!-- billing_phone1 -->
                    <div class="col text-right">
                        <label for="billing_phone1" class="col-form-label">Billing Phone</label>
                    </div>
                    <div class="col">
                        <input
                                id="billing_phone1"
                                type="text"
                                class="form-control{{ $errors->has('billing_phone1') ? ' is-invalid' : '' }}"
                                name="billing_phone1"
                                value="{{ old('billing_phone1') }}"
                        >

                        @if ($errors->has('billing_phone1'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_phone1') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- billing_phone1 -->

                <div class="form-group row"> <!-- billing_phone2 -->
                    <div class="col text-right">
                        <label for="billing_phone2" class="col-form-label">Billing Alt. Phone</label>
                    </div>
                    <div class="col">
                        <input
                                id="billing_phone2"
                                type="text"
                                class="form-control{{ $errors->has('billing_phone2') ? ' is-invalid' : '' }}"
                                name="billing_phone2"
                                value="{{ old('billing_phone2') }}"
                        >

                        @if ($errors->has('billing_phone2'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_phone2') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- billing_phone2 -->

                <div class="form-group row"> <!-- billing_notes -->
                    <div class="col text-right">
                        <label for="billing_notes" class="col-form-label">Billing Notes</label>
                    </div>
                    <div class="col">
                        <textarea class="form-control" id="billing_notes" name="billing_notes" rows="3"></textarea>

                        @if ($errors->has('billing_notes'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_notes') }}</strong>
                            </div>
                        @endif
                    </div>
                </div> <!-- billing_notes -->
            </div>
            <!-- /BILLING INFORMATION -->

            <div class="form-group row">
                <div class="col text-right">
                    <button type="submit" class="btn btn-primary">
                        Create Customer
                    </button>
                </div>
                <div class="col">
                    <a class="btn btn-danger" href="/customers">Cancel</a>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection

@section('footer-scripts')
    <script>
        $('input[type=checkbox]').change(function() {
            if (this.checked) {
                // $('#billing-info').removeClass('show').hide();
                $('#billing-info').css('display', 'none');
            } else {
                // $('#billing-info').addClass('show').show();
                $('#billing-info').css('display', 'block');
            }
        });
    </script>
@endsection
