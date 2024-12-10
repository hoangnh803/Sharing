<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\DocumentType;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'file_path' => 'required|string', // Kiểm tra file_path, thay vì file
        'type' => ['required', 'string', Rule::in(['university', 'school'])], // Kiểm tra type là 'university' hoặc 'school'
        'university_id' => 'nullable|exists:universities,id|required_without:class_id', // Cần nếu thiếu class_id
        'class_id' => 'nullable|exists:classes,id|required_without:university_id', // Cần nếu thiếu university_id
        'subject_id' => 'required|exists:subjects,id', // Kiểm tra môn học tồn tại
        'document_type_id' => 'required|exists:document_types,id', // Kiểm tra loại tài liệu tồn tại
    ]);

        // Lấy file_path từ frontend gửi về
        $filePath = $request->input('file_path');

        // Tạo một bản ghi mới trong bảng documents
        $document = Document::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_path' => $filePath, // Lưu file_path nhận được từ frontend
            'type' => $request->input('type'), // Lấy mime type từ file (nếu cần)
            'uploader_id' => \Illuminate\Support\Facades\Auth::user()->id, // Giả sử người dùng đang đăng nhập
            'university_id' => $request->input('university_id'),
            'class_id' => $request->input('class_id'),
            'subject_id' => $request->input('subject_id'),
            'document_type_id' => $request->input('document_type_id'),
            'is_approved' => false, // Tài liệu chưa được duyệt
            'report_count' => 0, // Khởi tạo số lượt xem là 0
        ]);

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
            'document' => 'required|mimes:pdf,doc,docx,txt|max:10240', // Giới hạn loại và dung lượng file
        ]);

        // Kiểm tra và xử lý upload file
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            // Lưu file vào thư mục tạm thời 'temp_uploads'
            $filePath = $file->storeAs('', time() . '_' . $file->getClientOriginalName(), 'public');
            // Get the full path to the folder

            return response()->json(['message' => 'File uploaded temporarily', 'file_path' => $filePath], 200);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
    public function deleteTemporaryFile(Request $request)
    {
        // Lấy file_path từ params của URL
        $filePath = $request->query('file_path');

        if (!$filePath) {
            return response()->json(['message' => 'File path is required'], 400);
        }

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return response()->json(['message' => 'File deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'File not found', 'file_path' => $filePath], 404);
        }
    }
    public function getDocument($documentId)
    {
        // Tìm tài liệu trong cơ sở dữ liệu theo ID
        $document = Document::find($documentId);

        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        // Lấy file_path từ cơ sở dữ liệu (từ cột 'file_path')
        $filePath = $document->file_path;

        // Xác định đường dẫn tệp đầy đủ từ storage
        $fullPath = storage_path('app/private/' . $filePath);

        // Kiểm tra xem tệp có tồn tại không
        if (file_exists($fullPath)) {
            // Trả về tệp PDF như một response
            return response()->file($fullPath);
        }

        // Nếu tệp không tồn tại, trả về lỗi 404
        return response()->json(['message' => 'File not found'], 404);
    }
}
