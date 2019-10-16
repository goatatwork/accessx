<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\NewCustomerFormRequest;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('company_name', 'asc')->orderBy('last_name', 'asc')->paginate(50);

        return view('customers.index')->with('customers', $customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\NewCustomerFormRequest  $form
     * @return \Illuminate\Http\Response
     */
    public function store(NewCustomerFormRequest $form)
    {
        $customer = $form->persist();

        return redirect('/customers');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $customer->load('billing_record')->load('service_locations');
        return view('customers.show')->with('customer', $customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $update_data = $request->all();

        $customer->update($update_data);

        if ($customer->customer_type == 'Residential') {
            $customer->service_locations->each(function($service_location) use ($update_data) {
                $service_location->update([
                    'poc_name' => $update_data['first_name'] . ' ' . $update_data['last_name'],
                    'poc_email' => ''
                ]);
            });

            $customer->billing_record->update([
                'poc_name' => $update_data['first_name'] . ' ' . $update_data['last_name'],
                'poc_email' => ''
            ]);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
