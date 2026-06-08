<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiryReceived;
use App\Models\ContactInquiry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SitePageController extends Controller
{
    public function investNow()
    {
        return view('pages.invest');
    }

    public function wanted()
    {
        return redirect()->route('listings.index', ['kind' => 'wanted']);
    }

    public function findRealtor()
    {
        $agents = User::query()
            ->agents()
            ->approved()
            ->withPublishedListingCounts()
            ->orderedByRating()
            ->get();

        return view('pages.find-realtor', compact('agents'));
    }

    public function grabMe()
    {
        return view('pages.grab-me');
    }

    public function about()
    {
        return view('pages.about', [
            'portal' => config('portal'),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'company' => config('portal.company', []),
        ]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $inquiry = ContactInquiry::create($validated);

        $notifyEmail = config('portal.company.email');
        if ($notifyEmail) {
            try {
                Mail::to($notifyEmail)->send(new ContactInquiryReceived($inquiry));
            } catch (\Throwable $e) {
                Log::warning('Contact form saved but notification email failed.', [
                    'inquiry_id' => $inquiry->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()
            ->route('contact')
            ->with('status', 'Thank you for your message. We will get back to you soon.');
    }
}
