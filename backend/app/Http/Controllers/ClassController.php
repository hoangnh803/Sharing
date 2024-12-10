<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;

class ClassController extends Controller
{
     // Lấy danh sách các lớp và môn học của từng lớp
     public function index()
     {
         return Classes::with('subjects')->get(); // Lấy các lớp kèm môn học của từng lớp
     }
 // Lấy thông tin lớp học theo ID
 public function getClass($id)
 {
     // Tìm trường đại học theo ID
     $university = Classes::with('subjects')->find($id);

     // Kiểm tra nếu không tìm thấy trường đại học
     if (!$university) {
         return response()->json(['error' => 'Class not found'], 404);
     }

     return response()->json($university);
 }
     // Thêm lớp học mới
     public function store(Request $request)
     {
         $validated = $request->validate([
             'name' => 'required|string|max:255',
         ]);
 
         $class = Classes::create($validated);
 
         return response()->json($class, 201);
     }
 
     // Xóa lớp học
     public function destroy($id)
     {
         $class = Classes::find($id);
 
         if ($class) {
             $class->delete();
             return response()->json(null, 204);
         }
 
         return response()->json(['error' => 'Class not found'], 404);
     }

}
