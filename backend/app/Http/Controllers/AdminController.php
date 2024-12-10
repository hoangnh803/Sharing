<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\Download;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function getStats()
    {
        $totalUsers = User::count();
        $totalDocuments = Document::count();
        $totalDownloads = Download::count();

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalDocuments' => $totalDocuments,
            'totalDownloads' => $totalDownloads,
        ]);
    }
    public function getChartData()
    {
        // Người dùng theo thời gian
        $userRegistrations = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tài liệu tải lên theo thời gian
        $documentsUploaded = Document::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tài liệu tải xuống theo thời gian
        $downloads = Download::select(DB::raw('DATE(downloaded_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'userRegistrations' => $userRegistrations,
            'documentsUploaded' => $documentsUploaded,
            'downloads' => $downloads,
        ]);
    }
    // Lấy danh sách người dùng
    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Cập nhật thông tin người dùng
    public function updateUser($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }


}
