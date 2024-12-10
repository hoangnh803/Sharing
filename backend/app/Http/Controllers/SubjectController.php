<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\Classes;

class SubjectController extends Controller
{
    // Lấy danh sách môn học của một trường đại học
    public function index($universityId)
    {
        $university = University::find($universityId);
        if (!$university) {
            return response()->json(['error' => 'University not found'], 404);
        }

        return $university->Subjects;
    }

    // Thêm môn học cho một trường đại học
    public function store(Request $request, $universityId)
    {
        // Tìm trường đại học
        $university = University::find($universityId);
        if (!$university) {
            return response()->json(['error' => 'University not found'], 404);
        }

        // Xác thực dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Kiểm tra xem môn học đã tồn tại chưa
        $subject = Subject::where('name', $validated['name'])->first();
        if (!$subject) {
            // Nếu chưa tồn tại, tạo mới môn học
            $subject = Subject::create($validated);
        }

        // Gắn môn học với trường đại học qua bảng trung gian
        $university->subjects()->syncWithoutDetaching($subject->id);

        return response()->json(['message' => 'Subject added successfully', 'subject' => $subject], 201);
    }

    // Sửa thông tin môn học
    public function update(Request $request, $id)
    {
        $Subject = Subject::find($id);
        if (!$Subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $Subject->update($validated);
        return response()->json($Subject);
    }

    // Xóa môn học
    public function deleteCoursefromUniversity($universityId, $subjectId)
    {
        // Kiểm tra xem trường đại học có tồn tại không
        $university = University::find($universityId);
        if (!$university) {
            return response()->json(['error' => 'University not found'], 404);
        }

        // Kiểm tra mối quan hệ giữa trường và môn học
        $relationship = $university->subjects()->where('subject_id', $subjectId)->first();
        if (!$relationship) {
            return response()->json(['error' => 'Subject not associated with this university'], 404);
        }

        // Xóa mối quan hệ
        $university->subjects()->detach($subjectId);

        return response()->json(['message' => 'Subject removed from university'], 200);
    }
    public function indexClass($classId)
    {
        $class = Classes::find($classId);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        $subjects = $class->subjects; // Lấy các môn học của lớp
        return response()->json($subjects);
    }
    public function storeClass(Request $request, $classId)
    {
        // Tìm trường đại học
        $class = Classes::find($classId);
        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        // Xác thực dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Kiểm tra xem môn học đã tồn tại chưa
        $subject = Subject::where('name', $validated['name'])->first();
        if (!$subject) {
            // Nếu chưa tồn tại, tạo mới môn học
            $subject = Subject::create($validated);
        }

        // Gắn môn học với trường đại học qua bảng trung gian
        $class->subjects()->syncWithoutDetaching($subject->id);

        return response()->json(['message' => 'Subject added successfully', 'subject' => $subject], 201);
    }
    public function destroyClass($classId, $subjectId)
    {
        $class = Classes::find($classId);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        $class->subjects()->detach($subjectId); // Gỡ môn học khỏi lớp
        return response()->json(['message' => 'Subject removed from class'], 204);
    }
}
