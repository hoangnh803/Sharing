<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentType;
use App\Models\Report;
use App\Models\Like;
use App\Models\SavedDocument;
use App\Models\Download;
use Illuminate\Support\Facades\Log;
use App\Models\DocumentView;
use App\Events\DocumentUploaded;
use App\Events\DocumentReported;
use App\Events\DocumentApproved;
use App\Events\DocumentRejected;
use App\Notifications\DocumentApprovedNotification;
use App\Notifications\DocumentUploadedNotification;
use App\Notifications\DocumentRejectedNotification;
use App\Notifications\DocumentReportedNotification;
use App\Models\User;
use App\Models\Subject;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file_path' => 'required|string', // Kiểm tra file_path, thay vì file
            'preview' => 'required|string', // Validate preview image
            'type' => ['required', 'string', Rule::in(['university', 'school'])], // Kiểm tra type là 'university' hoặc 'school'
            'university_id' => 'nullable|exists:universities,id|required_without:class_id', // Cần nếu thiếu class_id
            'class_id' => 'nullable|exists:classes,id|required_without:university_id', // Cần nếu thiếu university_id
            'subject_id' => 'required|exists:subjects,id', // Kiểm tra môn học tồn tại
            'document_type_id' => 'required|exists:document_types,id', // Kiểm tra loại tài liệu tồn tại
        ]);

        // Lấy file_path từ frontend gửi về
        $filePath = $request->input('file_path');
        $previewPath = $request->input('preview');
        // Tạo một bản ghi mới trong bảng documents
        $document = Document::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_path' => $filePath, // Lưu file_path nhận được từ frontend
            'preview' => $previewPath, // Lưu preview image
            'type' => $request->input('type'), // Lấy mime type từ file (nếu cần)
            'uploader_id' => \Illuminate\Support\Facades\Auth::user()->id, // Giả sử người dùng đang đăng nhập
            'university_id' => $request->input('university_id'),
            'class_id' => $request->input('class_id'),
            'subject_id' => $request->input('subject_id'),
            'document_type_id' => $request->input('document_type_id'),
            'is_approved' => 'pending', // Tài liệu chưa được duyệt
            'report_count' => 0, // Khởi tạo số lượt xem là 0
        ]);
        // Gửi thông báo cho Admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new DocumentUploadedNotification($document));
        }

        event(new DocumentUploaded($document));


        return response()->json(['message' => 'File uploaded successfully', 'document' => $document], 200);
    }
    // Phương thức lấy danh sách các loại tài liệu
    public function getDocumentTypes()
    {
        // Lấy tất cả các loại tài liệu từ bảng document_types
        $documentTypes = DocumentType::all();

        // Trả về danh sách các loại tài liệu dưới dạng JSON
        return response()->json(['document_types' => $documentTypes], 200);
    }
    public function uploadTemporaryFile(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf,doc,docx,txt|max:102400', // Giới hạn loại và dung lượng file
            'preview' => 'required|string', // Validate preview image
        ]);

        // Kiểm tra và xử lý upload file
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            // Lưu file vào thư mục tạm thời 'temp_uploads'
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('', $fileName, 'public');
            // Get the full path to the folder
            // Lưu ảnh preview
            $previewName = pathinfo($fileName, PATHINFO_FILENAME) . '.jpg';
            $previewPath = $previewName;
            Storage::disk('public')->put($previewPath, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->preview)));
            return response()->json([
                'message' => 'File uploaded temporarily',
                'file_path' => $filePath,
                'preview_path' => $previewPath,
            ], 200);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
    public function deleteTemporaryFile(Request $request)
    {
        // Lấy file_path từ params của URL
        $filePath = $request->query('file_path');
        $previewPath = $request->query('preview_path');

        if (!$filePath) {
            return response()->json(['message' => 'File path is required'], 400);
        }

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        } else {
            return response()->json(['message' => 'File not found', 'file_path' => $filePath], 404);
        }
        // Xóa file preview
        if ($previewPath && Storage::disk('public')->exists($previewPath)) {
            Storage::disk('public')->delete($previewPath);
        }
        return response()->json(['message' => 'File and preview deleted successfully'], 200);
    }
    public function guestGetDocument($documentId)
    {
        // Tìm tài liệu trong cơ sở dữ liệu theo ID, kèm theo các mối quan hệ
        $document = Document::with([
            'uploader',
            'university',
            'class',
            'subject',
            'documentType',
            'likes',
            'downloads',
            'reports.user', // Eager load reports and the user who reported
            'reports.reportReason'
        ])
            ->find($documentId);

        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        $report = Report::where('document_id', $documentId)->with('reportReason')->latest()->first();
        $document->reject_reason = $report ? $report->reportReason->reason : null;
        $document->user_note = ($report && $report->reportReason->reason === 'other') ? $report->user_note : null;
        $viewCount = DocumentView::where('document_id', $documentId)->count();
        $document->view_count = $viewCount;

        return response()->json($document);
    }
    public function userGetDocument($documentId)
    {
        // Tìm tài liệu trong cơ sở dữ liệu theo ID, kèm theo các mối quan hệ
        $document = Document::with([
            'uploader',
            'university',
            'class',
            'subject',
            'documentType',
            'likes',
            'downloads',
            'reports.user', // Eager load reports and the user who reported
            'reports.reportReason'
        ])
            ->find($documentId);

        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        $report = Report::where('document_id', $documentId)->with('reportReason')->latest()->first();
        $document->reject_reason = $report ? $report->reportReason->reason : null;
        $document->user_note = ($report && $report->reportReason->reason === 'other') ? $report->user_note : null;
        // Kiểm tra xem tài liệu đã được lưu bởi người dùng hiện tại chưa
        $userId = Auth::id();

        $isSaved = SavedDocument::where('user_id', $userId)
            ->where('document_id', $documentId)
            ->exists();


        $document->is_saved = $isSaved;
        // Lấy số lượt xem của tài liệu
        $viewCount = DocumentView::where('document_id', $documentId)->count();
        $document->view_count = $viewCount;
        // Trả về thông tin tài liệu dưới dạng JSON
        return response()->json($document);
    }

    public function index()
    {
        $documents = Document::where('uploader_id', Auth::id())
            ->with(['uploader', 'university', 'class', 'subject', 'documentType', 'likes', 'downloads', 'reports'])
            ->get();
        return response()->json($documents);
    }

    public function destroy($id)
    {
        $document = Document::where('id', $id)->where('uploader_id', Auth::id())->first();

        if (!$document) {
            return response()->json([
                'status' => 'error',
                'message' => 'Document not found or you do not have permission to delete this document.'
            ], 404);
        }
        // Lấy file_path từ tài liệu
        $filePath = $document->file_path;

        $document->delete();

        // Kiểm tra nếu file tồn tại trong kho lưu trữ và xóa nó
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Document deleted successfully.'
        ]);
    }
    public function getAlldocument()
    {
        $documents = Document::with(['uploader', 'university', 'class', 'subject', 'documentType', 'likes', 'downloads', 'reports'])
            ->get();

        return response()->json($documents);
    }
    public function approve($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'status' => 'error',
                'message' => 'Document not found.'
            ], 404);
        }

        $document->is_approved = 'approved';
        $document->save();

        // Gửi thông báo cho người upload tài liệu
        $document->uploader->notify(new DocumentApprovedNotification($document));

        event(new DocumentApproved($document));


        return response()->json([
            'status' => 'success',
            'message' => 'Document approved successfully.'
        ]);
    }

    public function reject($id, Request $request)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'status' => 'error',
                'message' => 'Document not found.'
            ], 404);
        }

        $document->is_approved = 'rejected';
        $document->save();

        // Lưu lý do từ chối vào bảng reports
        Report::create([
            'user_id' => Auth::id(),
            'document_id' => $id,
            'report_reason_id' => $request->reason_id,
            'user_note' => $request->reason,
        ]);


        $document->uploader->notify(new DocumentRejectedNotification($document));


        return response()->json([
            'status' => 'success',
            'message' => 'Document rejected successfully.'
        ]);
    }

    public function destroyAdmin($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'status' => 'error',
                'message' => 'Document not found.'
            ], 404);
        }

        // Lấy file_path từ tài liệu
        $filePath = $document->file_path;

        // Xóa tài liệu
        $document->delete();

        // Kiểm tra nếu file tồn tại trong kho lưu trữ và xóa nó
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Document and file deleted successfully.'
        ]);
    }
    public function like($id)
    {
        $document = Document::find($id);
        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        // Logic to like the document
        Like::updateOrCreate(
            ['user_id' => Auth::id(), 'document_id' => $id],
            ['is_like' => true]
        );

        return response()->json(['message' => 'Document liked successfully']);
    }

    public function dislike($id)
    {
        $document = Document::find($id);
        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        // Logic to dislike the document
        Like::updateOrCreate(
            ['user_id' => Auth::id(), 'document_id' => $id],
            ['is_like' => false]
        );

        return response()->json(['message' => 'Document disliked successfully']);
    }

    public function unlike($id)
    {
        $document = Document::find($id);
        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        // Logic to unlike the document
        Like::where('user_id', Auth::id())
            ->where('document_id', $id)
            ->where('is_like', true)
            ->delete();

        return response()->json(['message' => 'Document unliked successfully']);
    }

    public function undislike($id)
    {
        $document = Document::find($id);
        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        // Logic to undislike the document
        Like::where('user_id', Auth::id())
            ->where('document_id', $id)
            ->where('is_like', false)
            ->delete();

        return response()->json(['message' => 'Document undisliked successfully']);
    }
    public function download($id)
    {
        $document = Document::find($id);
        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        $filePath = storage_path('app/public/' . $document->file_path);
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        try {
            // Ghi lại thông tin tải xuống vào bảng downloads
            Download::create([
                'user_id' => Auth::id(),
                'document_id' => $id,
                'downloaded_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error recording download: ' . $e->getMessage());
            return response()->json(['message' => 'Error recording download'], 500);
        }

        return response()->download($filePath);
    }
    public function getDocumentsByUniversity($universityId)
    {
        $documents = Document::with([
            'uploader',
            'university',
            'class',
            'subject',
            'documentType',
            'likes',
            'downloads'
        ])->where('university_id', $universityId)
            ->where('is_approved', 'approved')
            ->get();

        return response()->json($documents);
    }

    public function getDocumentsByClass($classId)
    {
        $documents = Document::with([
            'uploader',
            'university',
            'class',
            'subject',
            'documentType',
            'likes',
            'downloads'
        ])->where('class_id', $classId)
            ->where('is_approved', 'approved')
            ->get();

        return response()->json($documents);
    }
    public function getDocumentsByUniversityAndSubject($universityId, $subjectId)
    {
        $documents = Document::with([
            'uploader',
            'university',
            'class',
            'subject',
            'documentType',
            'likes',
            'downloads'
        ])->where('university_id', $universityId)
            ->where('subject_id', $subjectId)
            ->where('is_approved', 'approved')
            ->get();

        return response()->json($documents);
    }
    public function getDocumentsByClassAndSubject($classId, $subjectId)
    {
        $documents = Document::with([
            'uploader',
            'university',
            'class',
            'subject',
            'documentType',
            'likes',
            'downloads'
        ])->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('is_approved', 'approved')
            ->get();

        return response()->json($documents);
    }
    public function reportDocument(Request $request, $documentId)
    {
        $request->validate([
            'reason_id' => 'required|exists:report_reasons,id',
            'user_note' => 'nullable|string|max:1000',
        ]);

        $document = Document::find($documentId);

        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        Report::create([
            'user_id' => Auth::id(),
            'document_id' => $documentId,
            'report_reason_id' => $request->reason_id,
            'user_note' => $request->user_note,
        ]);
        // Gửi thông báo cho admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new DocumentReportedNotification($document));
        }

        // Gửi thông báo cho người đăng tài liệu
        $document->uploader->notify(new DocumentReportedNotification($document));

        return response()->json(['message' => 'Document reported successfully'], 200);
    }
    public function removeReport($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        // Xóa toàn bộ báo cáo của tài liệu
        Report::where('document_id', $id)->delete();

        return response()->json(['message' => 'All reports removed successfully.']);
    }
    public function saveDocument(Request $request, $documentId)
    {
        $document = Document::find($documentId);

        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        $savedDocument = SavedDocument::create([
            'user_id' => Auth::id(),
            'document_id' => $documentId,
        ]);

        return response()->json(['message' => 'Document saved successfully', 'savedDocument' => $savedDocument], 200);
    }

    public function unsaveDocument(Request $request, $documentId)
    {
        $savedDocument = SavedDocument::where('user_id', Auth::id())
            ->where('document_id', $documentId)
            ->first();

        if (!$savedDocument) {
            return response()->json(['message' => 'Saved document not found'], 404);
        }

        $savedDocument->delete();

        return response()->json(['message' => 'Document unsaved successfully'], 200);
    }
    public function getSavedDocuments()
    {
        $savedDocuments = SavedDocument::with('document.uploader', 'document.university', 'document.class', 'document.subject', 'document.documentType', 'document.likes', 'document.downloads', 'document.reports')
            ->where('user_id', Auth::id())
            ->get()
            ->pluck('document');

        return response()->json($savedDocuments);
    }
    public function recordView($documentId)
    {
        $userId = Auth::id();

        // Kiểm tra xem người dùng đã xem tài liệu này chưa
        $existingView = DocumentView::where('user_id', $userId)
            ->where('document_id', $documentId)
            ->first();

        if (!$existingView) {
            DocumentView::create([
                'user_id' => $userId,
                'document_id' => $documentId,
            ]);
        }

        return response()->json(['message' => 'View recorded successfully']);
    }
    public function search($query)
    {
        // Tìm tài liệu trong cơ sở dữ liệu theo ID, kèm theo các mối quan hệ
        $documents = Document::with([
            'uploader',
            'university',
            'class',
            'subject',
            'documentType',
            'likes',
            'downloads',
            'reports.user', // Eager load reports and the user who reported
            'reports.reportReason'
        ])->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();
        // Tìm kiếm môn học
        $subjects = Subject::with(['universities', 'classes'])
            ->where('name', 'LIKE', "%{$query}%")
            ->get();

        return response()->json([
            'documents' => $documents,
            'subjects' => $subjects,
        ]);
    }
    public function getRecentDocuments(Request $request)
    {
        $userId = $request->input('user_id');

        $recentDocuments = DocumentView::with(['document.uploader', 'document.university', 'document.class', 'document.subject', 'document.documentType'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get()
            ->filter(fn($view) => $view->document !== null) // Bỏ qua nếu document là null
            ->pluck('document'); // Lấy danh sách document hợp lệ


        $viewedDocumentIds = $recentDocuments->pluck('id')->toArray();

        // Lấy danh sách các môn học và trường từ các tài liệu đã xem
        $subjectIds = $recentDocuments->pluck('subject.id')->unique()->toArray();
        $universityIds = $recentDocuments->pluck('university.id')->unique()->toArray();

        // Tìm các tài liệu liên quan dựa trên môn học và trường
        $relatedDocuments = Document::with(['uploader', 'university', 'class', 'subject', 'documentType'])
            ->where(function ($query) use ($subjectIds, $universityIds) {
                $query->whereIn('subject_id', $subjectIds)
                    ->orWhereIn('university_id', $universityIds);
            })
            ->whereNotIn('id', $viewedDocumentIds) // Loại bỏ các tài liệu mà người dùng đã xem
            ->take(8)
            ->get();

        return response()->json([
            'recentDocuments' => $recentDocuments,
            'relatedDocuments' => $relatedDocuments,
        ]);
    }
    public function getUserStatistics($userId)
    {
        // Lấy danh sách tài liệu của người dùng
        $documents = Document::where('uploader_id', $userId)->get();

        // Số tài liệu
        $documentCount = $documents->count();

        // Tổng số lượt xem
        $totalViews = DocumentView::whereIn('document_id', $documents->pluck('id'))->count();

        // Tổng số lượt tải xuống
        $totalDownloads = Download::whereIn('document_id', $documents->pluck('id'))->count();

        return response()->json([
            'document_count' => $documentCount,
            'total_views' => $totalViews,
            'total_downloads' => $totalDownloads,
        ]);
    }
}
