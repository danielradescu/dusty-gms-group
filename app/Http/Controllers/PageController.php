<?php

namespace App\Http\Controllers;

use App\Models\FeaturedMember;

class PageController extends Controller
{
    public function aboutUs()
    {
        $featuredMembers = FeaturedMember::with('user')->get();

        return view('page.about-us', compact('featuredMembers'));
    }

    public function privacyPolicy()
    {
        return view('page.privacy-policy');
    }

    public function termsOfService()
    {
        return view('page.terms-of-service');
    }
}
