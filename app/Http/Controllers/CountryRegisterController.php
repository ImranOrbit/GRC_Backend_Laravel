<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CountryRegistration;
use Illuminate\Support\Facades\Mail;

class CountryRegisterController extends Controller
{
    // CREATE registration
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'countries' => 'required|array|min:1',
            'universities' => 'required|array|min:1'
        ]);

        $countryList = implode(', ', $request->countries);
        $universityList = implode(', ', $request->universities);

        $registration = CountryRegistration::create([
            'name' => $request->name,
            'email' => $request->email,
            'country' => $countryList,
            'universities' => $universityList
        ]);

        // Send Email
        $emailData = [
            'name' => $request->name,
            'email' => $request->email,
            'countries' => $countryList,
            'universities' => $universityList
        ];

        try {
            Mail::raw(
                "Dear {$emailData['name']},\n\nThank you for selecting universities with Global Routeway Consult.\n\nDetails:\nName: {$emailData['name']}\nEmail: {$emailData['email']}\nCountry: {$emailData['countries']}\nUniversities: {$emailData['universities']}\n\nRegards,\nGlobal Routeway Consult Team",
                function ($message) use ($emailData) {
                    $message->to($emailData['email'])
                            ->subject('University Selection - Global Routeway Consult');
                }
            );

            return response()->json(['message' => 'Submission successful and email sent'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Submission successful but email failed'], 200);
        }
    }

    // GET all registrations
    public function index()
    {
        $registrations = CountryRegistration::orderBy('id','desc')->get();
        return response()->json($registrations, 200);
    }

    // DELETE registration
    public function destroy($id)
    {
        $registration = CountryRegistration::find($id);
        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        $registration->delete();
        return response()->json(['message' => 'Registration deleted successfully'], 200);
    }
}