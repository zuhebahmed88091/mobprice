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

class MobileDetailsController extends Controller
{
    public function disclaimer()
    {
        return view('front_end.disclaimer');
    }

    public function priceDisclaimer()
    {
        return view('front_end.price_disclaimer');
    }

}
