<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class OmneySafariController extends Controller
{
    public function home(): View
    {
        return view('omney-safari');
    }

    public function bookings(): View
    {
        return view('omney-safari.bookings', [
            'bookings' => auth()->user()?->safariBookings()->latest()->get() ?? collect(),
        ]);
    }

    public function faq(): View
    {
        return view('omney-safari.faq');
    }

    public function about(): View
    {
        return view('omney-safari.about');
    }

    public function scr(): View
    {
        return view('omney-safari.scr');
    }
}
