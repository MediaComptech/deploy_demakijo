<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\ActivityLog;

class LogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) { redirect('/login'); }
    }

    public function index(Request $request)
    {
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'newest';
        $logName = $_GET['log_name'] ?? 'all';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $query = ActivityLog::with('causer');

        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%")
                  ->orWhereHas('causer', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($logName !== 'all') {
            $query->where('log_name', $logName);
        }

        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'description') {
            $query->orderBy('description', 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); // default: newest
        }

        $total = $query->count();
        $pages = ceil($total / $limit);
        if ($pages < 1) $pages = 1;
        if ($page > $pages) {
            $page = $pages;
            $offset = ($page - 1) * $limit;
        }

        $data = $query->skip($offset)->take($limit)->get();

        // Get unique log names for filter dropdown
        $logNames = ActivityLog::distinct()->pluck('log_name')->toArray();

        return view('backend.log.index', compact('data', 'search', 'sort', 'logName', 'logNames', 'page', 'pages', 'total', 'limit'));
    }
}
