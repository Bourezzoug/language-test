<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\StudentAttempt;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with statistics.
     */
    public function dashboard()
    {
        $stats = [
            'total_tests' => Test::count(),
            'total_attempts' => StudentAttempt::count(),
            'completed_attempts' => StudentAttempt::where('status', 'completed')->count(),
            'in_progress_attempts' => StudentAttempt::where('status', 'in_progress')->count(),
            'average_score' => round(StudentAttempt::where('status', 'completed')->avg('score') ?? 0, 1),
        ];

        $recentAttempts = StudentAttempt::with('test')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAttempts'));
    }

    /**
     * Show list of all student attempts.
     */
    public function attempts(Request $request)
    {
        $query = StudentAttempt::with('test')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $perPage = intval($request->input('per_page', 10));
        if (!in_array($perPage, [5, 10, 20, 50])) {
            $perPage = 10;
        }

        $attempts = $query->paginate($perPage);

        return view('admin.attempts.index', compact('attempts'));
    }

    /**
     * View detailed answers of a single attempt.
     */
    public function showAttempt(StudentAttempt $attempt)
    {
        $attempt->load([
            'test', 
            'answers.question.section', 
            'answers.question.passage', 
            'answers.option', 
            'answers.question.options'
        ]);
        
        return view('admin.attempts.show', compact('attempt'));
    }

    /**
     * Export student attempts to CSV.
     */
    public function exportAttempts()
    {
        $attempts = StudentAttempt::with('test')->orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=placement_test_results.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'ID', 'Candidate Name', 'Email', 'Phone', 'Test Title', 
            'Score', 'Total Questions', 'Recommended Level', 
            'Status', 'Started At', 'Finished At'
        ];

        $callback = function() use($attempts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($attempts as $attempt) {
                fputcsv($file, [
                    $attempt->id,
                    $attempt->full_name,
                    $attempt->email ?? 'N/A',
                    $attempt->phone ?? 'N/A',
                    $attempt->test->title ?? 'Deleted Test',
                    $attempt->score,
                    $attempt->total_questions,
                    $attempt->recommended_level ?? 'N/A',
                    ucfirst($attempt->status),
                    $attempt->started_at ? $attempt->started_at->format('Y-m-d H:i:s') : 'N/A',
                    $attempt->finished_at ? $attempt->finished_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
