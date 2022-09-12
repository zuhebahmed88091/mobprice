<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Mobile;
use App\Models\MobilePrice;
use App\Models\MobileRegion;
use App\Models\Rating;
use App\Library\PhotoGallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;

class FooterController extends Controller
{
    public function terms()
    {
        return view('front_end.terms');
    }

    public function privacy()
    {
        return view('front_end.privacy');
    }

    public function contact()
    {
        return view('front_end.contact');
    }
    
    public function about()
    {
        return view('front_end.about');
    }
}