<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function districts(Request $request)
    {
        $regionId = $request->integer('region_id');

        $query = District::query()->select('id', 'region_id', 'name_uz', 'name_oz', 'name_ru', 'name_en');

        if ($regionId) {
            $query->where('region_id', $regionId);
        }

        return response()->json(
            $query->orderBy('name_uz')->get()
        );
    }
}