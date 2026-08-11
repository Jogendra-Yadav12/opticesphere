<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = Newsletter::latest()->get();

        return view('admin.newsletters', compact('subscribers'));
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        return redirect()->route('admin.newsletter.index')->with('success', 'Subscriber removed.');
    }
}
