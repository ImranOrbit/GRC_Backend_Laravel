<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarshipRegistration;
use Illuminate\Support\Facades\Mail;

class ScholarshipRegistrationController extends Controller
{

    // REGISTER SCHOLARSHIP
    public function registerScholarship(Request $request)
    {

        $request->validate([
            'fullName' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'scholarshipCountry' => 'required'
        ]);

        ScholarshipRegistration::create([
            'full_name' => $request->fullName,
            'email' => $request->email,
            'phone' => $request->phone,
            'scholarship_country' => $request->scholarshipCountry
        ]);


        $emailText = "
Dear {$request->fullName},

Thank you for registering for a scholarship consultation with Global Routeway Consult.

Details:
Full Name: {$request->fullName}
Email: {$request->email}
Phone: {$request->phone}
Scholarship Country: {$request->scholarshipCountry}

We will get back to you shortly.

Regards,
Global Routeway Consult Team
";


        try {

            Mail::raw($emailText, function ($message) use ($request) {
                $message->to($request->email)
                        ->subject("Scholarship Registration Confirmation - Global Routeway Consult");
            });

            return response()->json([
                'message' => 'Submission successful and email sent'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Submission successful but email failed'
            ]);
        }
    }


    // GET ALL REGISTRATIONS
    public function index()
    {
        $data = ScholarshipRegistration::orderBy('id','DESC')->get();

        return response()->json($data);
    }


    // DELETE REGISTRATION
    public function destroy($id)
    {
        $registration = ScholarshipRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'message' => 'Registration not found'
            ],404);
        }

        $registration->delete();

        return response()->json([
            'message' => 'Registration deleted successfully'
        ]);
    }

}