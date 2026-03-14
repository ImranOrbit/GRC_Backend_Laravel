<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    // REGISTER USER
    public function registerUser(Request $request)
    {
        try {
            $request->validate([
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required|email',
                'phone' => ['required', 'regex:/^(?:\+8801|01)[3-9]\d{8}$/'],
                'nearestOffice' => 'required',
                'preferredDestination' => 'required',
                'testStatus' => 'required',
                'fundingPlan' => 'required',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $registration = Registration::create([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'nearest_office' => $request->nearestOffice,
                'preferred_destination' => $request->preferredDestination,
                'test_status' => $request->testStatus,
                'funding_plan' => $request->fundingPlan,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
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
                Log::info('Email sent to: ' . $request->email);
            } catch (\Exception $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Registration successful',
                'data' => $registration
            ]);

        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET ALL REGISTRATIONS
    public function index()
    {
        try {
            $registrations = Registration::orderBy('created_at', 'DESC')->get();
            return response()->json($registrations);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch registrations'], 500);
        }
    }

    // SEARCH REGISTRATIONS
    public function search(Request $request)
    {
        try {
            $query = Registration::query();

            // Search by name
            if ($request->has('name') && !empty($request->name)) {
                $query->where(function($q) use ($request) {
                    $q->where('first_name', 'LIKE', '%' . $request->name . '%')
                      ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
                });
            }

            // Search by email
            if ($request->has('email') && !empty($request->email)) {
                $query->where('email', 'LIKE', '%' . $request->email . '%');
            }

            // Search by phone
            if ($request->has('phone') && !empty($request->phone)) {
                $query->where('phone', 'LIKE', '%' . $request->phone . '%');
            }

            // Pagination
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 10);
            $total = $query->count();

            $results = $query->orderBy('created_at', 'DESC')
                            ->skip(($page - 1) * $limit)
                            ->take($limit)
                            ->get();

            return response()->json([
                'data' => $results,
                'total' => $total,
                'page' => $page,
                'limit' => $limit
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET SINGLE REGISTRATION
    public function show($id)
    {
        try {
            $registration = Registration::find($id);
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
            $registration = Registration::find($id);
            if (!$registration) {
                return response()->json(['message' => 'Registration not found'], 404);
            }

            $request->validate([
                'firstName' => 'sometimes|required',
                'lastName' => 'sometimes|required',
                'email' => 'sometimes|required|email',
                'phone' => ['sometimes', 'required', 'regex:/^(?:\+8801|01)[3-9]\d{8}$/'],
                'nearestOffice' => 'sometimes|required',
                'preferredDestination' => 'sometimes|required',
                'testStatus' => 'sometimes|required',
                'fundingPlan' => 'sometimes|required',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            if ($request->has('firstName')) {
                $registration->first_name = $request->firstName;
            }
            if ($request->has('lastName')) {
                $registration->last_name = $request->lastName;
            }
            if ($request->has('email')) {
                $registration->email = $request->email;
            }
            if ($request->has('phone')) {
                $registration->phone = $request->phone;
            }
            if ($request->has('nearestOffice')) {
                $registration->nearest_office = $request->nearestOffice;
            }
            if ($request->has('preferredDestination')) {
                $registration->preferred_destination = $request->preferredDestination;
            }
            if ($request->has('testStatus')) {
                $registration->test_status = $request->testStatus;
            }
            if ($request->has('fundingPlan')) {
                $registration->funding_plan = $request->fundingPlan;
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
            $registration = Registration::find($id);

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