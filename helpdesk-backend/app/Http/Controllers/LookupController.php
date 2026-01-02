<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Vessel;
use App\Models\ServiceLine;
use App\Models\Category;
use App\Models\User;

class LookupController extends Controller
{
    public function organizations()
    {
        return Organization::select('id', 'name')->get();
    }

    public function vessels(Request $request)
    {
        $query = Vessel::select('id', 'name', 'organization_id');

        if ($request->has('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }

        return $query->get();
    }

    public function serviceLines()
    {
        return ServiceLine::select('id', 'name')->get();
    }

    public function categories()
    {
        return Category::select('id', 'name')->get();
    }

    public function users()
    {
        return User::select('id', 'name')->get();
    }
}
