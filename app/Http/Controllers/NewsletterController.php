<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Simpan email ke database atau kirim ke layanan newsletter
        // Contoh:
        // Newsletter::create(['email' => $request->email]);

        return redirect()->back()->with('success', 'Subscribed successfully!');
    }
}
