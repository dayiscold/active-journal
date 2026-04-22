<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ImportLog;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with(['group', 'discipline'])
            ->where('teacher_id', Auth::id())
            ->orderByDesc('date')
            ->get();

        $logs = ImportLog::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('import.index', compact('lessons', 'logs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'csv_file'  => 'required|file|mimes:csv,txt|max:2048',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $lesson   = Lesson::findOrFail($request->lesson_id);
        $file     = $request->file('csv_file');
        $filename = $file->getClientOriginalName();

        $log = ImportLog::create([
            'user_id'  => Auth::id(),
            'filename' => $filename,
            'status'   => 'processing',
        ]);

        try {
            $handle    = fopen($file->getPathname(), 'r');
            fgetcsv($handle); // skip header row
            $processed = 0;

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 2) {
                    continue;
                }
                // Teams attendance report: Full Name, Email, Join Time, Leave Time, Duration
                $email = trim($row[1] ?? '');

                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                $student = User::where(function ($q) use ($email) {
                    $q->where('teams_email', $email)->orWhere('email', $email);
                })->where('role', 'student')->first();

                if ($student) {
                    Attendance::updateOrCreate(
                        ['lesson_id' => $lesson->id, 'student_id' => $student->id],
                        ['status' => 'present', 'reason' => 'Импорт из Teams']
                    );
                    $processed++;
                }
            }

            fclose($handle);
            $log->update(['status' => 'completed', 'records_processed' => $processed]);

            return redirect()->route('teacher.import.index')
                ->with('success', "Импорт завершён. Обработано записей: {$processed}");
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            return redirect()->route('teacher.import.index')
                ->with('error', 'Ошибка импорта: ' . $e->getMessage());
        }
    }
}
