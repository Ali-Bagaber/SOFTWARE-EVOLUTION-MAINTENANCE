<?php

namespace App\Http\Controllers\Module4;
use App\Http\Controllers\Controller;
use App\Models\Module4\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Optional: for logging errors

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $routeName = $request->route()->getName();
        $targetUserId = null;
        $viewName = '';
        $notifications = collect(); // Default to empty collection

        // Check if user is authenticated
        if (!Auth::check()) {
            
            return redirect()->route('login')->with('error', 'Please log in to view notifications.');
        }

        // Always use the currently authenticated user's ID
        $targetUserId = Auth::id();

        // Determine which view to use based on the route
        if ($routeName === 'admin.notifications') {
            $viewName = 'module_4.MCMC_Admin.notification_list';
        } else {
            $viewName = 'module_4.user_public.notification_list';
        }

        // Mark all as read for the target user
        Notification::where('user_id', $targetUserId)
            ->where('is_read', 0) // Use 0 instead of false
            ->update(['is_read' => 1]); // Use 1 instead of true

        // Fetch notifications
        $notifications = Notification::where('user_id', $targetUserId)
            ->with('inquiry')
            ->orderBy('date_sent', 'desc')
            ->paginate(10);

        return view($viewName, compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->update(['is_read' => 1]); // Use 1 instead of true

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', 0) // Use 0 instead of false
            ->update(['is_read' => 1]); // Use 1 instead of true

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    public function getUnreadCount(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['count' => 0, 'error' => 'User not authenticated.'], 401);
        }

        // Always use the currently authenticated user's ID
        $targetUserId = Auth::id();

        $count = Notification::where('user_id', $targetUserId)
            ->where('is_read', 0) // Use 0 instead of false for database compatibility
            ->count();

        return response()->json([
            'count' => $count
        ]);
    }
}