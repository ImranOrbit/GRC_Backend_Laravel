<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\VideoController;

use App\Http\Controllers\BlogController;

use App\Http\Controllers\ReviewTwoController;
// use App\Http\Controllers\NursingController;
use App\Http\Controllers\NursingBlogController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\EngineeringController;
use App\Http\Controllers\FoodHospitalityController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\LeadershipController;
use App\Http\Controllers\CountryRegisterController;
// use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\ScholarshipRegistrationController;
use App\Http\Controllers\VisaSuccessController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::apiResource('register', RegisterController::class);
Route::post('/register', [RegistrationController::class,'registerUser']);
Route::get('/registrations', [RegistrationController::class,'index']);
Route::delete('/registrations/{id}', [RegistrationController::class,'destroy']);

// Route::apiResource('review', ReviewController::class);
Route::post('/reviews', [ReviewController::class,'submitReview']);
Route::get('/reviews', [ReviewController::class,'index']);
Route::put('/reviews/{id}', [ReviewController::class,'update']);
Route::delete('/reviews/{id}', [ReviewController::class,'destroy']);

// Route::apiResource('auth', AuthController::class);
Route::post('/login', [AuthController::class,'login']);

// Route::apiResource('video', VideoController::class);
Route::post('/videos', [VideoController::class,'updateVideoUrl']);
Route::get('/videos', [VideoController::class,'getVideoUrls']);

// Route::apiResource('blog', BlogController::class);
Route::get('/blogs', [BlogController::class,'index']); 
Route::get('/blogs/{slug}', [BlogController::class,'show']); 
Route::post('/blogs', [BlogController::class,'store']); 
Route::put('/blogs/{id}', [BlogController::class,'update']);
Route::delete('/blogs/{id}', [BlogController::class,'destroy']);

// Route::apiResource('review-two', ReviewTwoController::class);
Route::post('/reviewtwo', [ReviewTwoController::class,'submitReviewTwo']);
Route::get('/reviewtwo', [ReviewTwoController::class,'index']);
Route::put('/reviewtwo/{id}', [ReviewTwoController::class,'update']);
Route::delete('/reviewtwo/{id}', [ReviewTwoController::class,'destroy']);



// Route::apiResource('nursing', NursingController::class);
Route::post('/nursing-blog', [NursingBlogController::class,'store']);
Route::get('/nursing-blog', [NursingBlogController::class,'index']);
Route::put('/nursing-blog/{id}', [NursingBlogController::class,'update']);
Route::delete('/nursing-blog/{id}', [NursingBlogController::class,'destroy']);


// Route::apiResource('accounting', AccountingController::class);
Route::post('/accounting', [AccountingController::class,'store']);
Route::get('/accounting', [AccountingController::class,'index']);
Route::put('/accounting/{id}', [AccountingController::class,'update']);
Route::delete('/accounting/{id}', [AccountingController::class,'destroy']);


// Route::apiResource('engineering', EngineeringController::class);
Route::post('/engineering', [EngineeringController::class,'store']);
Route::get('/engineering', [EngineeringController::class,'index']);
Route::put('/engineering/{id}', [EngineeringController::class,'update']);
Route::delete('/engineering/{id}', [EngineeringController::class,'destroy']);

// Route::apiResource('food-hospitality', FoodHospitalityController::class);
Route::post('/food-hospitality', [FoodHospitalityController::class,'store']);
Route::get('/food-hospitality', [FoodHospitalityController::class,'index']);
Route::put('/food-hospitality/{id}', [FoodHospitalityController::class,'update']);
Route::delete('/food-hospitality/{id}', [FoodHospitalityController::class,'destroy']);

// Route::apiResource('business', BusinessController::class);
Route::post('/business', [BusinessController::class,'store']);
Route::get('/business', [BusinessController::class,'index']);
Route::put('/business/{id}', [BusinessController::class,'update']);
Route::delete('/business/{id}', [BusinessController::class,'destroy']);

// Route::apiResource('collaboration', CollaborationController::class);
Route::post('/collaboration', [CollaborationController::class,'store']);
Route::get('/collaboration', [CollaborationController::class,'index']);
Route::put('/collaboration/{id}', [CollaborationController::class,'update']);
Route::delete('/collaboration/{id}', [CollaborationController::class,'destroy']);

// Route::apiResource('leadership', LeadershipController::class);
Route::post('/leadership', [LeadershipController::class,'store']);
Route::get('/leadership', [LeadershipController::class,'index']);
Route::put('/leadership/{id}', [LeadershipController::class,'update']);
Route::delete('/leadership/{id}', [LeadershipController::class,'destroy']);

Route::apiResource('country-register', CountryRegisterController::class);

// Route::apiResource('scholarship', ScholarshipController::class);
Route::post('/scholarship-register', [ScholarshipRegistrationController::class,'registerScholarship']);
Route::get('/scholarship-registrations', [ScholarshipRegistrationController::class,'index']);
Route::delete('/scholarship-registrations/{id}', [ScholarshipRegistrationController::class,'destroy']);

// Route::apiResource('visa-success', VisaSuccessController::class);
Route::get('/visa-success', [VisaSuccessController::class,'index']);
Route::post('/visa-success', [VisaSuccessController::class,'store']);
Route::put('/visa-success/{id}', [VisaSuccessController::class,'update']);
Route::delete('/visa-success/{id}', [VisaSuccessController::class,'destroy']);