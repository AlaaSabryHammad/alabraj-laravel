<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Correspondence;
use App\Models\CorrespondenceReply;

class MyTasksController extends Controller
{
    /**
     * Display the user's assigned correspondences
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get employee record for current user
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الموظف الخاص بك');
        }

        $query = Correspondence::with(['project', 'user', 'replies.user'])
                    ->withCount('replies')
                    ->where('assigned_to', $employee->id)
                    ->where('type', 'incoming') // Only incoming correspondences can be assigned
                    ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $correspondences = $query->paginate(15);

        return view('my-tasks.index', compact('correspondences'));
    }

    /**
     * Show correspondence details for reply
     */
    public function show($id)
    {
        $user = Auth::user();

        // Get employee record for current user
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الموظف الخاص بك');
        }

        $correspondence = Correspondence::with(['project', 'user', 'replies.user'])
                            ->where('id', $id)
                            ->where('assigned_to', $employee->id)
                            ->firstOrFail();

        return view('my-tasks.show', compact('correspondence'));
    }

    /**
     * Store reply for correspondence
     */
    public function storeReply(Request $request, $id)
    {
        $user = Auth::user();

        // Get employee record for current user
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الموظف الخاص بك');
        }

        $correspondence = Correspondence::where('id', $id)
                            ->where('assigned_to', $employee->id)
                            ->firstOrFail();

        $request->validate([
            'reply_content' => 'required|string',
            'files.*' => 'file|max:10240', // Max 10MB per file
        ]);

        // Create reply
        $reply = new CorrespondenceReply();
        $reply->correspondence_id = $correspondence->id;
        $reply->user_id = $user->id;
        $reply->reply_content = $request->reply_content;
        $reply->status = 'sent';

        // Handle file uploads
        if ($request->hasFile('files')) {
            $file = $request->file('files')[0]; // Take first file for now
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/replies'), $filename);
            $reply->file_path = 'uploads/replies/' . $filename;
            $reply->file_name = $file->getClientOriginalName();
            $reply->file_size = $file->getSize();
            $reply->file_type = $file->getMimeType();
        }

        $reply->save();

        // Update correspondence status
        $correspondence->status = 'replied';
        $correspondence->replied_at = now();
        $correspondence->save();

        return redirect()->route('my-tasks.show', $id)
                        ->with('success', 'تم إرسال الرد بنجاح');
    }

    /**
     * Update correspondence status
     */
    public function updateStatus(Request $request, Correspondence $correspondence)
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Check if this correspondence is assigned to current user
        if (!$employee || $correspondence->assigned_to !== $employee->id) {
            return redirect()->route('my-tasks.index')->with('error', 'ليس لديك صلاحية لتحديث هذه المراسلة');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,replied,closed',
        ]);

        $correspondence->update(['status' => $validated['status']]);

        return redirect()->route('my-tasks.show', $correspondence)
            ->with('success', 'تم تحديث حالة المراسلة بنجاح');
    }

    /**
     * Download reply file
     */
    public function downloadReply(CorrespondenceReply $reply)
    {
        $user = Auth::user();

        // Check if this reply belongs to current user or the correspondence is assigned to them
        if ($reply->user_id !== $user->id) {
            $employee = $user->employee;
            if (!$employee || $reply->correspondence->assigned_to !== $employee->id) {
                return redirect()->back()->with('error', 'ليس لديك صلاحية لتحميل هذا الملف');
            }
        }

        if (!$reply->file_path || !file_exists(public_path($reply->file_path))) {
            return redirect()->back()->with('error', 'الملف غير موجود');
        }

        return response()->download(
            public_path($reply->file_path),
            $reply->file_name
        );
    }
}
