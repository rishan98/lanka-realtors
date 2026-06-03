<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;

class ContactInquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $inquiries = ContactInquiry::query()
            ->latest()
            ->paginate(20);

        return view('admin.contact-inquiries.index', compact('inquiries'));
    }

    public function show(ContactInquiry $contactInquiry)
    {
        return view('admin.contact-inquiries.show', [
            'inquiry' => $contactInquiry,
        ]);
    }
}
