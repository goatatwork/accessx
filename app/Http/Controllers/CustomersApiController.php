<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Resources\CustomerCollection;
use Illuminate\Http\Request;
use App\Http\Requests\NewCustomerFormRequest;

class CustomersApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_key = $request->query('sort_key', 'customer_name');
        $sort_order = $request->query('sort_order', 'asc');

        if ($sort_order == 'desc') {
            $customers = Customer::all()->sortByDesc($sort_key, SORT_NATURAL|SORT_FLAG_CASE);
        } else {
            $customers = Customer::all()->sortBy($sort_key, SORT_NATURAL|SORT_FLAG_CASE);
        }

        return new CustomerCollection($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\NewCustomerFormRequest  $form
     * @return \App\Customer
     */
    public function store(NewCustomerFormRequest $form)
    {
        $customer = $form->persist();
        return $customer->load('billing_record')->load('service_locations');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        return tap($customer)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
    }
}
