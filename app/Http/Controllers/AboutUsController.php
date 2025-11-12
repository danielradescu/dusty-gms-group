<?php

namespace App\Http\Controllers;

use App\Models\FeaturedMember;

class AboutUsController extends Controller
{
    public function index()
    {
        $featuredMembers = FeaturedMember::with('user')->get();

        return view('about-us.index', compact('featuredMembers'));
    }
}
