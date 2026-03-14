<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarshipRegistration;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ScholarshipRegistrationController extends Controller
{
    // REGISTER SCHOLARSHIP
    public function registerScholarship(Request $request)
    {
        try {
            $request->validate([
                'fullName' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'scholarshipCountry' => 'required',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $registration = ScholarshipRegistration::create([
                'full_name' => $request->fullName,
                'email' => $request->email,
                'phone' => $request->phone,
                'scholarship_country' => $request->scholarshipCountry,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
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
                Log::info('Email sent to: ' . $request->email);
            } catch (\Exception $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Submission successful',
                'data' => $registration
            ]);

        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Submission failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET ALL REGISTRATIONS - for frontend list
    public function index()
    {
        try {
            $data = ScholarshipRegistration::orderBy('id','DESC')->get();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch registrations'], 500);
        }
    }

    // GET SINGLE REGISTRATION
    public function show($id)
    {
        try {
            $registration = ScholarshipRegistration::find($id);
            if (!$registration) {
                return response()->json(['message' => 'Registration not found'], 404);
            }
            return response()->json($registration);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch registration'], 500);
        }
    }

    // UPDATE REGISTRATION
    public function update(Request $request, $id)
    {
        try {
            $registration = ScholarshipRegistration::find($id);
            if (!$registration) {
                return response()->json(['message' => 'Registration not found'], 404);
            }

            $request->validate([
                'fullName' => 'sometimes|required',
                'email' => 'sometimes|required|email',
                'phone' => 'sometimes|required',
                'scholarshipCountry' => 'sometimes|required',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            if ($request->has('fullName')) {
                $registration->full_name = $request->fullName;
            }
            if ($request->has('email')) {
                $registration->email = $request->email;
            }
            if ($request->has('phone')) {
                $registration->phone = $request->phone;
            }
            if ($request->has('scholarshipCountry')) {
                $registration->scholarship_country = $request->scholarshipCountry;
            }
            if ($request->has('meta_title')) {
                $registration->meta_title = $request->meta_title;
            }
            if ($request->has('meta_description')) {
                $registration->meta_description = $request->meta_description;
            }

            $registration->save();

            return response()->json([
                'message' => 'Registration updated successfully',
                'data' => $registration
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE REGISTRATION
    public function destroy($id)
    {
        try {
            $registration = ScholarshipRegistration::find($id);

            if (!$registration) {
                return response()->json([
                    'message' => 'Registration not found'
                ], 404);
            }

            $registration->delete();

            return response()->json([
                'message' => 'Registration deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}