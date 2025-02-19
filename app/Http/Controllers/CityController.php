<?php

namespace App\Http\Controllers;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller {
    public function getCities($state_id) {
        return response()->json(City::where('state_id', $state_id)->get());
    }
}
