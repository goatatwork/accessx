<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GoldAccess\Utilities\SubnetCalculator;
use App\Http\Requests\SubnetCalculatorRequest;

class SubnetCalculatorApiController extends Controller
{
    public function store(SubnetCalculatorRequest $request)
    {
        $calc = new SubnetCalculator($request->ip, $request->cidr, $request->gateway_preference);
        return $calc->calculate();
    }
}
