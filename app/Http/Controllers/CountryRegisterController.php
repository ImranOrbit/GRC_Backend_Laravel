<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CountryRegistration;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CountryRegisterController extends Controller
{
    // CREATE registration
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'countries' => 'required|array|min:1',
                'universities' => 'required|array|min:1',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $countryList = implode(', ', $request->countries);
            $universityList = implode(', ', $request->universities);

            $registration = CountryRegistration::create([
                'name' => $request->name,
                'email' => $request->email,
                'country' => $countryList,
                'universities' => $universityList,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
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

                Log::info('Email sent successfully to: ' . $emailData['email']);
            } catch (\Exception $e) {
                Log::error('Email sending failed: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Submission successful',
                'data' => $registration
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Submission failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET all registrations
    public function index()
    {
        try {
            $registrations = CountryRegistration::orderBy('id','desc')->get();
            return response()->json($registrations, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch registrations'], 500);
        }
    }

    // GET single registration
    public function show($id)
    {
        try {
            $registration = CountryRegistration::find($id);
            if (!$registration) {
                return response()->json(['message' => 'Registration not found'], 404);
            }
            return response()->json($registration, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch registration'], 500);
        }
    }

    // UPDATE registration
    public function update(Request $request, $id)
    {
        try {
            $registration = CountryRegistration::find($id);
            if (!$registration) {
                return response()->json(['message' => 'Registration not found'], 404);
            }

            $request->validate([
                'name' => 'sometimes|string',
                'email' => 'sometimes|email',
                'countries' => 'sometimes|array',
                'universities' => 'sometimes|array',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            if ($request->has('name')) {
                $registration->name = $request->name;
            }
            if ($request->has('email')) {
                $registration->email = $request->email;
            }
            if ($request->has('countries')) {
                $registration->country = implode(', ', $request->countries);
            }
            if ($request->has('universities')) {
                $registration->universities = implode(', ', $request->universities);
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
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE registration
    public function destroy($id)
    {
        try {
            $registration = CountryRegistration::find($id);
            if (!$registration) {
                return response()->json(['message' => 'Registration not found'], 404);
            }

            $registration->delete();
            return response()->json(['message' => 'Registration deleted successfully'], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}