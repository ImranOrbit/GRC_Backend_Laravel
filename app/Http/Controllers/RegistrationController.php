<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{

    // REGISTER USER
    public function registerUser(Request $request)
    {

        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'phone' => ['required','regex:/^(?:\+8801|01)[3-9]\d{8}$/'],
            'nearestOffice' => 'required',
            'preferredDestination' => 'required',
            'testStatus' => 'required',
            'fundingPlan' => 'required'
        ]);

        $registration = Registration::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'nearest_office' => $request->nearestOffice,
            'preferred_destination' => $request->preferredDestination,
            'test_status' => $request->testStatus,
            'funding_plan' => $request->fundingPlan
        ]);


        // Email content
        $emailText = "
Dear {$request->firstName} {$request->lastName},

Thank you for registering with Global Routeway Consult. We have received your information and will contact you shortly.

Here are your registration details:

Name: {$request->firstName} {$request->lastName}
Email: {$request->email}
Phone: {$request->phone}
Nearest Office: {$request->nearestOffice}
Study Destination: {$request->preferredDestination}
English Test Status: {$request->testStatus}
Funding Plan: {$request->fundingPlan}

Regards,
Global Routeway Consult Team
";

        try {

            Mail::raw($emailText, function ($message) use ($request) {
                $message->to($request->email)
                        ->subject("Registration Successful - Global Routeway Consult");
            });

            return response()->json([
                'message' => 'Registration successful and email sent'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Registration successful but email failed to send'
            ]);
        }

    }


    // GET ALL REGISTRATIONS
    public function index()
    {
        $registrations = Registration::orderBy('created_at','DESC')->get();

        return response()->json($registrations);
    }


    // DELETE REGISTRATION
    public function destroy($id)
    {
        $registration = Registration::find($id);

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