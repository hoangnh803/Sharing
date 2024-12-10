<?php

namespace App\Http\Controllers;

use App\Models\University;
use App\Models\Subject;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    // Lấy danh sách các trường đại học và môn học của chúng
    public function index()
    {
        return University::with('subjects')->get(); // Lấy các trường đại học kèm môn học của từng trường
    }
    // Lấy thông tin trường đại học theo ID
    public function getUniversity($id)
    {
        // Tìm trường đại học theo ID
        $university = University::with('subjects')->find($id);

        // Kiểm tra nếu không tìm thấy trường đại học
        if (!$university) {
            return response()->json(['error' => 'University not found'], 404);
        }

        return response()->json($university);
    }


    // Thêm trường đại học
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $university = University::create($validated);

        return response()->json($university, 201);
    }

    // Xóa trường đại học
    public function destroy($id)
    {
        $university = University::find($id);

        if ($university) {
            $university->delete();
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'University not found'], 404);
    }

    // Thêm môn học cho trường đại học
    public function addSubject(Request $request, $universityId)
    {
        $university = University::find($universityId);
        if (!$university) {
            return response()->json(['error' => 'University not found'], 404);
        }

        $subject = Subject::find($request->subject_id);
        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        $university->subjects()->attach($subject);

        return response()->json(['message' => 'Subject added successfully']);
    }

    // Xóa môn học khỏi trường đại học
    public function removeSubject($universityId, $subjectId)
    {
        $university = University::find($universityId);
        if (!$university) {
            return response()->json(['error' => 'University not found'], 404);
        }

        $subject = Subject::find($subjectId);
        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        $university->subjects()->detach($subject);

        return response()->json(['message' => 'Subject removed successfully']);
    }
}
