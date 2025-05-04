<?php

namespace App\Http\Controllers\Admin;

use App\Models\About;
use App\Models\Contact;
use App\Models\Voucher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard Admin
    public function index()
    {
        return view('admin.dashboard');
    }

    // About Section Management
    public function about()
    {
        $abouts = About::all();
        return view('admin.about.index', compact('abouts'));
    }

    public function createAbout()
    {
        return view('admin.about.create');
    }

    public function storeAbout(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        About::create($request->all());
        return redirect()->route('admin.about');
    }

    public function editAbout($id)
    {
        $about = About::findOrFail($id);
        return view('admin.about.edit', compact('about'));
    }

    public function updateAbout(Request $request, $id)
    {
        $about = About::findOrFail($id);
        $about->update($request->all());
        return redirect()->route('admin.about');
    }

    public function deleteAbout($id)
    {
        $about = About::findOrFail($id);
        $about->delete();
        return redirect()->route('admin.about');
    }

    // Contact Section Management
    public function contact()
    {
        $contacts = Contact::all();
        return view('admin.contact.index', compact('contacts'));
    }

    public function createContact()
    {
        return view('admin.contact.create');
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Contact::create($request->all());
        return redirect()->route('admin.contact');
    }

    public function editContact($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.contact.edit', compact('contact'));
    }

    public function updateContact(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update($request->all());
        return redirect()->route('admin.contact');
    }

    public function deleteContact($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('admin.contact');
    }

    // Voucher Management
    public function vouchers()
    {
        $vouchers = Voucher::all();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function createVoucher()
    {
        return view('admin.vouchers.create');
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers',
            'discount' => 'required|numeric',
            'expiration_date' => 'required|date',
        ]);

        Voucher::create($request->all());
        return redirect()->route('admin.vouchers');
    }

    public function editVoucher($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function updateVoucher(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());
        return redirect()->route('admin.vouchers');
    }

    public function deleteVoucher($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return redirect()->route('admin.vouchers');
    }
}
