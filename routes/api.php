<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ReviewTwoController;
use App\Http\Controllers\NursingBlogController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\EngineeringController;
use App\Http\Controllers\FoodHospitalityController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\LeadershipController;
use App\Http\Controllers\CountryRegisterController;
use App\Http\Controllers\ScholarshipRegistrationController;
use App\Http\Controllers\VisaSuccessController;

// ------------------------------
// Mimic app.use("/", route)
// ------------------------------

Route::prefix('/')->group(function () {

    // Registration
    Route::post('/register', [RegistrationController::class, 'registerUser']);
    Route::get('/registrations', [RegistrationController::class, 'index']);
    Route::get('/registrationssearch/search', [RegistrationController::class, 'search']);
    Route::get('/registrations/{id}', [RegistrationController::class, 'show']);
    Route::put('/registrations/{id}', [RegistrationController::class, 'update']);
    Route::delete('/registrationsdelete/{id}', [RegistrationController::class, 'destroy']);

    // Reviews

    Route::post('/reviewpost', [ReviewController::class, 'submitReview']);
    Route::get('/reviewget', [ReviewController::class, 'index']);
    Route::post('/reviewupdate/{id}', [ReviewController::class, 'update']);       // Changed from PUT to POST
    Route::delete('/reviewdelete/{id}', [ReviewController::class, 'destroy']);

    // Auth
    Route::post('/login', [AuthController::class, 'login']);



    // Video routes
    Route::post('/videos', [VideoController::class, 'updateVideoUrl']);
    Route::post('/videos/multiple', [VideoController::class, 'updateMultipleVideos']);
    Route::get('/videos', [VideoController::class, 'getVideoUrls']);
    Route::get('/videos/{id}', [VideoController::class, 'show']);
    Route::delete('/videos/{id}', [VideoController::class, 'destroy']);

    // Blogs
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/{slug}', [BlogController::class, 'show']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::post('/blogs/{id}', [BlogController::class, 'update']); // Changed from PUT to POST
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

    // Review Two
    Route::post('/reviewtwopost', [ReviewTwoController::class, 'submitReviewTwo']);
    Route::get('/reviewtwoget', [ReviewTwoController::class, 'index']);
    Route::post('/reviewtwoupdate/{id}', [ReviewTwoController::class, 'update']); // Changed from PUT to POST
    Route::delete('/reviewtwodelete/{id}', [ReviewTwoController::class, 'destroy']);

    // Nursing Blogs

    Route::post('/course-nurse', [NursingBlogController::class, 'store']);
    Route::get('/course-nurse-get', [NursingBlogController::class, 'index']);
    Route::post('/course-nurse-update/{id}', [NursingBlogController::class, 'update']); // Changed from PUT to POST
    Route::delete('/course-nurse-delete/{id}', [NursingBlogController::class, 'destroy']);



    // Accounting Blog routes - match frontend URLs
    Route::post('/course-accounting', [AccountingController::class, 'store']);
    Route::get('/course-accounting-get', [AccountingController::class, 'index']);
    Route::post('/course-accounting-update/{id}', [AccountingController::class, 'update']); // POST for updates
    Route::delete('/course-accounting-delete/{id}', [AccountingController::class, 'destroy']);

    // Engineering

    Route::post('/course-engineering', [EngineeringController::class, 'store']);
    Route::get('/course-engineering-get', [EngineeringController::class, 'index']);
    Route::post('/course-engineering-update/{id}', [EngineeringController::class, 'update']); // Changed from PUT to POST
    Route::delete('/course-engineering-delete/{id}', [EngineeringController::class, 'destroy']);

    // Food & Hospitality

    Route::post('/course-food-hospitality', [FoodHospitalityController::class, 'store']);
    Route::get('/course-food-hospitality-get', [FoodHospitalityController::class, 'index']);
    Route::post('/course-food-hospitality-update/{id}', [FoodHospitalityController::class, 'update']); // Changed from PUT to POST
    Route::delete('/course-food-hospitality-delete/{id}', [FoodHospitalityController::class, 'destroy']);

    // Business
    Route::post('/course-business', [BusinessController::class, 'store']);
    Route::get('/course-business-get', [BusinessController::class, 'index']);
    Route::post('/course-business-update/{id}', [BusinessController::class, 'update']);
    Route::delete('/course-business-delete/{id}', [BusinessController::class, 'destroy']);

    // Collaboration
    Route::post('/collaborations', [CollaborationController::class, 'store']);           // Create
    Route::get('/collaborations', [CollaborationController::class, 'index']);            // Read all
    Route::post('/collaborations/{id}', [CollaborationController::class, 'update']);     // Update (POST)
    Route::delete('/collaborations/{id}', [CollaborationController::class, 'destroy']);

    // Leadership

    Route::post('/leadership', [LeadershipController::class, 'store']);
    Route::get('/leadership', [LeadershipController::class, 'index']);
    Route::post('/leadership/{id}', [LeadershipController::class, 'update']); // Changed from PUT to POST
    Route::delete('/leadership/{id}', [LeadershipController::class, 'destroy']);

    // Country Registration

    Route::post('/countryregister', [CountryRegisterController::class, 'store']);
    Route::get('/countryregister', [CountryRegisterController::class, 'index']);
    Route::get('/countryregister/{id}', [CountryRegisterController::class, 'show']);
    Route::put('/countryregister/{id}', [CountryRegisterController::class, 'update']);
    Route::delete('/countryregister/{id}', [CountryRegisterController::class, 'destroy']);

    // Scholarship Registration routes - matching frontend URLs
    Route::post('/scholarship-register', [ScholarshipRegistrationController::class, 'registerScholarship']);
    Route::get('/scholarship-register', [ScholarshipRegistrationController::class, 'index']); // Changed from scholarship-registrations
    Route::get('/scholarship-register/{id}', [ScholarshipRegistrationController::class, 'show']);
    Route::put('/scholarship-register/{id}', [ScholarshipRegistrationController::class, 'update']);
    Route::delete('/scholarship-register/{id}', [ScholarshipRegistrationController::class, 'destroy']); // Changed from scholarship-registrations/{id}

    // Visa Success

    Route::get('/visa-success', [VisaSuccessController::class, 'index']);
    Route::post('/visa-success', [VisaSuccessController::class, 'store']);
    Route::post('/visa-success/{id}', [VisaSuccessController::class, 'update']); // Changed from PUT to POST
    Route::delete('/visa-success/{id}', [VisaSuccessController::class, 'destroy']);
});
