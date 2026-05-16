<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Models\Guvohnoma;
use App\Models\Sertifikat;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'        => User::count(),
            'total_templates'    => DocumentTemplate::count(),
            'total_sertifikatlar' => Sertifikat::count(),
            'total_guvohnomalar' => Guvohnoma::count(),
        ];

        return view('dashboard.index', compact('stats'));
    }
}