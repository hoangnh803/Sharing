<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportReasonController;
use App\Http\Controllers\NotificationController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/admin/universities', [UniversityController::class, 'index']); // Lấy danh sách các trường đại học
Route::get('/admin/classes', [ClassController::class, 'index']);
Route::get('/admin/universities/{id}', [UniversityController::class, 'getUniversity']); // Lấy danh sách các trường đại học
Route::get('/admin/classes/{id}', [ClassController::class, 'getClass']); // Lấy danh sách các trường đại học
Route::get('documents/university/{universityId}', [DocumentController::class, 'getDocumentsByUniversity']);
Route::get('documents/class/{classId}', [DocumentController::class, 'getDocumentsByClass']);
Route::get('documents/university/{universityId}/subject/{subjectId}', [DocumentController::class, 'getDocumentsByUniversityAndSubject']);
Route::get('documents/class/{classId}/subject/{subjectId}', [DocumentController::class, 'getDocumentsByClassAndSubject']);
Route::get('/document-types', [DocumentController::class, 'getDocumentTypes']);
Route::get('documents/{documentId}', [DocumentController::class, 'guestGetDocument'])->name('documents.show');
Route::get('documents/search/{query}', [DocumentController::class, 'search']);
Route::post('documents/recent', [DocumentController::class, 'getRecentDocuments']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/admin/stats', [AdminController::class, 'getStats']);
    Route::get('/admin/chart-data', [AdminController::class, 'getChartData']);
    Route::get('/admin/users', [AdminController::class, 'getUsers']);
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser']);
    // Quản lý trường đại học
    Route::post('/admin/universities', [UniversityController::class, 'store']); // Thêm trường đại học
    Route::delete('/admin/universities/{id}', [UniversityController::class, 'destroy']); // Xóa trường đại học

    // Routes cho môn học của trường đại học
    Route::get('/admin/universities/{id}/courses', [SubjectController::class, 'index']);
    Route::post('/admin/universities/{id}/courses', [SubjectController::class, 'store']);
    Route::put('/admin/courses/{id}', [SubjectController::class, 'update']);
    Route::delete('/admin/universities/{universityId}/courses/{subjectId}', [SubjectController::class, 'deleteCoursefromUniversity']);

    Route::get('/admin/classes/{classId}/subjects', [SubjectController::class, 'indexClass']);
    Route::post('/admin/classes/{classId}/subjects', [SubjectController::class, 'storeClass']);
    Route::delete('/admin/classes/{classId}/subjects/{subjectId}', [SubjectController::class, 'destroyClass']);

    // Quản lý lớp
    Route::post('/admin/classes', [ClassController::class, 'store']);
    Route::delete('/admin/classes/{id}', [ClassController::class, 'destroy']);
    //Quản lý tài liệu
    Route::get('/admin/documents', [DocumentController::class, 'getAlldocument']);
    Route::delete('/admin/documents/{id}', [DocumentController::class, 'destroyAdmin']);
    Route::post('/admin/documents/{id}/approve', [DocumentController::class, 'approve']);
    Route::post('documents/{id}/reject', [DocumentController::class, 'reject']);
    Route::get('report-reasons', [ReportReasonController::class, 'index']);    // Quản lý Documents
    Route::post('/upload-documents', [DocumentController::class, 'store']);
    Route::post('/upload-file', [DocumentController::class, 'uploadTemporaryFile']);
    Route::delete('/delete-file', [DocumentController::class, 'deleteTemporaryFile']);
    Route::post('documents/{id}/remove-report', [DocumentController::class, 'removeReport']);

    // Cập nhật thông tin cá nhân người dùng
    Route::put('user/profile', [UserController::class, 'updateProfile']);

    Route::get('user/documents', [DocumentController::class, 'index']);
    Route::delete('user/documents/{id}', [DocumentController::class, 'destroy']);

    //Thao tác với tài liệu
    Route::post('documents/{id}/like', [DocumentController::class, 'like']);
    Route::post('documents/{id}/dislike', [DocumentController::class, 'dislike']);
    Route::post('documents/{id}/unlike', [DocumentController::class, 'unlike']);
    Route::post('documents/{id}/undislike', [DocumentController::class, 'undislike']);
    Route::get('documents/{id}/download', [DocumentController::class, 'download']);
    Route::post('documents/{id}/report', [DocumentController::class, 'reportDocument']);
    Route::post('documents/{id}/save', [DocumentController::class, 'saveDocument']);
    Route::post('documents/{id}/unsave', [DocumentController::class, 'unsaveDocument']);
    Route::get('saved-documents', [DocumentController::class, 'getSavedDocuments']);
    Route::get('user/documents/{documentId}', [DocumentController::class, 'userGetDocument'])->name('documents.show');
    Route::post('documents/{id}/view', [DocumentController::class, 'recordView']);
    Route::get('user/{userId}/statistics', [DocumentController::class, 'getUserStatistics']); // Thêm route này

    //Thông báo
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
