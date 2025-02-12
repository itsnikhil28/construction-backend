<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\front\articlecontroller as FrontArticlecontroller;
use App\Http\Controllers\front\membercontroller as FrontMembercontroller;
use App\Http\Controllers\front\projectcontroller as FrontProjectcontroller;
use App\Http\Controllers\front\servicecontroller;
use App\Http\Controllers\front\testinomialcontroller as FrontTestinomialcontroller;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TestinomialController;
use App\Http\Controllers\usercontroller;
use Illuminate\Support\Facades\Route;

Route::post('/login', [usercontroller::class, 'login']);
Route::get('/service', [servicecontroller::class, 'index']);
Route::get('/latestservice', [servicecontroller::class, 'latestservices']);
Route::get('/service-detail/{id}',[servicecontroller::class, 'getsingleservice']);
Route::get('/project', [FrontProjectcontroller::class, 'index']);
Route::get('/latestproject', [FrontProjectcontroller::class, 'latestprojects']);
Route::get('/project-detail/{id}', [FrontProjectcontroller::class, 'getsingleservice']);
Route::get('/article', [FrontArticlecontroller::class, 'index']);
Route::get('/latestarticle', [FrontArticlecontroller::class, 'latestarticles']);
Route::get('/article-detail/{id}', [FrontArticlecontroller::class, 'getsingleservice']);
Route::get('/testinomial', [FrontTestinomialcontroller::class, 'index']);
Route::get('/member', [FrontMembercontroller::class, 'index']);
Route::post('/contact',[usercontroller::class,'contact']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [usercontroller::class, 'logout']);
    Route::apiResource('/services', ServicesController::class);
    Route::apiResource('/projects', ProjectController::class);
    Route::apiResource('/articles', ArticleController::class);
    Route::apiResource('/testinomials', TestinomialController::class);
    Route::apiResource('/members', MemberController::class);
});
