<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\StoreRequest;
use App\Mail\ContactMail;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * @return View
     */
    public function form(): View
    {
        return \view('contact.form')
            ->with('title', __('Kontak Kami'))
            ->with('description', __('Sampaikan hal berupa kritik maupun saran demi perkembangan aplikasi :app', ['app' => config('app.name')]));
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $request->merge([
            'user_id' => Auth::id()
        ]);

        $contact = Contact::create($request->all());

        Mail::to(config('mail.from.address'))->send(new ContactMail($contact));

        return redirect()
            ->route('contact')
            ->with('contact', $contact);
    }
}
