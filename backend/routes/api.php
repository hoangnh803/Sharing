<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\DocumentController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/admin/stats', [AdminController::class, 'getStats']);
    Route::get('/admin/chart-data', [AdminController::class, 'getChartData']);
    Route::get('/admin/users', [AdminController::class, 'getUsers']);
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser']);
     // Quản lý trường đại học
     Route::get('/admin/universities', [UniversityController::class, 'index']); // Lấy danh sách các trường đại học
     Route::post('/admin/universities', [UniversityController::class, 'store']); // Thêm trường đại học
     Route::delete('/admin/universities/{id}', [UniversityController::class, 'destroy']); // Xóa trường đại học
     Route::get('/admin/universities/{id}', [UniversityController::class, 'getUniversity']); // Lấy danh sách các trường đại học

     // Routes cho môn học của trường đại học
    Route::get('/admin/universities/{id}/courses', [SubjectController::class, 'index']);
    Route::post('/admin/universities/{id}/courses', [SubjectController::class, 'store']);
    Route::put('/admin/courses/{id}', [SubjectController::class, 'update']);
    Route::delete('/admin/universities/{universityId}/courses/{subjectId}', [SubjectController::class, 'deleteCoursefromUniversity']);
    
    Route::get('/admin/classes/{classId}/subjects', [SubjectController::class, 'indexClass']);
Route::post('/admin/classes/{classId}/subjects', [SubjectController::class, 'storeClass']);
Route::delete('/admin/classes/{classId}/subjects/{subjectId}', [SubjectController::class, 'destroyClass']);

// Quản lý lớp
Route::get('/admin/classes', [ClassController::class, 'index']); 
Route::post('/admin/classes', [ClassController::class, 'store']);
Route::delete('/admin/classes/{id}', [ClassController::class, 'destroy']); 
Route::get('/admin/classes/{id}', [ClassController::class, 'getClass']); // Lấy danh sách các trường đại học

// Quản lý Documents
Route::get('/document-types', [DocumentController::class, 'getDocumentTypes']);
Route::post('/upload-documents', [DocumentController::class, 'store']);
Route::post('/upload-file', [DocumentController::class, 'uploadTemporaryFile']);
Route::delete('/delete-file', [DocumentController::class, 'deleteTemporaryFile']);
Route::get('documents/{documentId}', [DocumentController::class, 'getDocument'])->name('documents.show');

});


