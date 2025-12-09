<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Agency;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('LoginAlluser');
    }

    /**
     * Show the public user registration form
     */
    public function showPublicRegisterForm()
    {
        return view('ManageUser.publicuser.user_register');
    }

    public function login(Request $request)
    {
        Log::info('Login attempt', ['email' => $request->email]);
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $email = $credentials['email'];
        $user = User::where('email', $email)->first();
        
        Log::info('User fetched', ['user' => $user]);        // If this is an admin email, redirect to admin login
        if (str_ends_with($email, '@admin.com')) {
            Log::info('Admin email detected, redirecting to admin login form');
            return redirect()->route('admin.login')->withInput();
        }        // Handle agency users        
        if (str_ends_with($email, '@agency') || str_ends_with($email, '@agency.com')) {
            if ($user && Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                
                // Mark login time
                $user->last_login_at = now();
                $user->save();                // FIRST: Check if contact number is missing (highest priority)
                if ($user->contact_number === null || empty(trim($user->contact_number))) {
                    // Store the return URL for after profile update
                    session(['return_to' => route('agency.dashboard')]);
                    
                    // Redirect to password update form
                    return redirect()->route('agency.password.update.form')
                        ->with('message', 'Please update your password and contact information before accessing the system.');
                }
                
                // SECOND: For @agency users (not .com), only check default password if they have never logged in
                if (str_ends_with($email, '@agency') && is_null($user->last_login_at)) {
                    // Store the return URL for after password reset
                    session(['return_to' => route('agency.dashboard')]);
                    
                    // Redirect to password recovery for security confirmation
                    return redirect()->route('agency.password.recover')
                        ->with('email', $email)
                        ->with('message', 'For security reasons, please update your password before accessing the system.');
                }
                
                // If all checks pass, proceed to agency dashboard
                return redirect()->route('agency.dashboard')
                    ->with('success', "🎉 Welcome back to Inquira, {$user->name}! Your agency dashboard is ready.");
            }
            return back()
                ->withInput()
                ->with('error', 'Invalid agency credentials.');
        }

        // Handle public users
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $user->last_login_at = now();
            $user->save();
            return redirect()->route('publicuser.dashboard')
                ->with('success', "🎉 Welcome back, {$user->name}! You're now logged into your Inquira dashboard.");
        }

        return back()
            ->withInput()
            ->with('error', 'Invalid login credentials.');
    }
    
    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? 'User';
        
        if (Auth::check() && Auth::user()->isAgency()) {
            // Get the user ID before logging out
            $userId = Auth::user()->user_id;
            
            // Clear the password change flag for this agency user
            $request->session()->forget('password_changed_'.$userId);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
          
        return redirect()->route('login')->with('success', "✅ Goodbye {$userName}! You have been logged out successfully. Thank you for using Inquira!");
    }
    public function dashboard()
    {
        $user = Auth::user();          if ($user && $user->user_role === 'agency') {            // FIRST: Check if contact number is null or empty for any agency user
            if ($user->contact_number === null || empty(trim($user->contact_number))) {
                // Store the return URL
                session(['return_to' => route('agency.dashboard')]);
                
                // Redirect to password update form
                return redirect()->route('agency.password.update.form')
                    ->with('message', 'Please update your password and contact information before accessing the system.');
            }
              
            // SECOND: For @agency users (not .com), only check default password if they have never logged in
            // If they have contact_number and have logged in before, skip password recovery
            if (str_ends_with($user->email, '@agency') && is_null($user->last_login_at)) {
                // Store the return URL
                session(['return_to' => route('agency.dashboard')]);
                    
                // Redirect to password recovery
                return redirect()->route('agency.password.recover')
                    ->with('message', 'For security reasons, please update your password before accessing the system.');            }              // Get agency statistics - check if Inquiry model exists first
            if (class_exists('\App\Models\Inquiry')) {
                try {
                    $inquiries = \App\Models\Inquiry::where('agency_id', $user->agency_id ?? null);
                    $stats = [
                        'assigned' => $inquiries->where('status', 'assigned')->count(),
                        'pending' => $inquiries->where('status', 'pending')->count(),
                        'completed' => $inquiries->where('status', 'completed')->count(),
                        'overdue' => $inquiries->where('status', 'overdue')->count(),
                    ];
                } catch (\Exception $e) {
                    // Fallback to dummy data if there's any database error
                    $stats = [
                        'assigned' => 0,
                        'pending' => 0,
                        'completed' => 0,
                        'overdue' => 0,
                    ];
                }
            } else {
                // Fallback to dummy data since Inquiry model doesn't exist
                $stats = [
                    'assigned' => 12,   // Sample data for demo purposes
                    'pending' => 8,
                    'completed' => 24,
                    'overdue' => 3,
                ];
            }
            
            return view('AgencyHoomePage', [
                'agency' => $user,
                'stats' => $stats
            ]);
        } else {
            return view('PublicUser');
        }
    }
    
    public function showPublicProfile()
    {
        // Show the public user profile page
        return view('ManageUser.publicuser.Manageprofile');
    }

    public function updatePublicProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id', // specify user_id as the PK
            'contact_number' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact_number = $request->contact_number;

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/profile_pictures', $filename, 'public');
            $user->profile_picture = 'uploads/profile_pictures/' . $filename;
        }

        $user->save();

        return redirect()->back()->with('success', '✅ Profile updated successfully! Your changes have been saved.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Please enter your current password.',
            'new_password.min' => 'New password must be at least 6 characters long.',
            'new_password.confirmed' => 'Password confirmation does not match.'
        ]);

        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = \Hash::make($request->new_password); // Hash the new password
        $user->save();

        return back()->with('success', '🔒 Password updated successfully! Your account is now more secure.');
    }

    public function showRecoveryForm(Request $request)
    {
        // Only show the email form if no email is in session
        $step = 'email';
        $email = $request->session()->get('recovery_email', '');
        return view('ManageUser.publicuser.recover_password', compact('step', 'email'));
    }

    public function handleRecoveryEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'We could not find an account with that email address. Please check and try again.'
        ]);
        
        $email = $request->email;
        $request->session()->put('recovery_email', $email);
        
        // Here you would typically send the actual email
        // Mail::to($email)->send(new PasswordResetMail($user));
        
        return redirect()->route('publicuser.recover.password')->with([
            'success' => '📧 Password reset link sent successfully!',
            'info' => "We've sent detailed instructions to {$email}. Please check your inbox and follow the steps to reset your password. The link will expire in 24 hours for security."
        ]);
    }

    public function showPasswordResetForm(Request $request)
    {
        if (!$request->session()->has('recovery_email')) {
            return redirect()->route('publicuser.recover');
        }
        $step = 'password';
        $email = $request->session()->get('recovery_email');
        return view('ManageUser.publicuser.recover_password', compact('step', 'email'));
    }

    public function handlePasswordReset(Request $request)
    {
        // Check if the recovery email is in session
        if (!$request->session()->has('recovery_email')) {
            return redirect()->route('publicuser.recover')->with('error', 'Session expired. Please start the password recovery process again.');
        }
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'new_password.min' => 'Password must be at least 6 characters long.',
            'new_password.confirmed' => 'Password confirmation does not match.'
        ]);
        $email = $request->session()->get('recovery_email');
        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::error('Password reset failed: user not found for email ' . $email);
            $request->session()->forget('recovery_email');
            return redirect()->route('publicuser.recover')->with('error', 'User not found. Please try again.');
        }
        $user->password = \Hash::make($request->new_password); // Always hash explicitly
        $user->save();
        $request->session()->forget('recovery_email');
        Log::info('Password reset successful for user ' . $email);
        
        return redirect()->route('login')->with([
            'success' => '🔒 Password updated successfully! You can now login with your new password.',
            'info' => 'For security reasons, please login again with your new credentials.'
        ]);
    }
    public function registerPublic(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'contact_number' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact_number = $request->contact_number;        // Set user_role based on email domain
        if (str_ends_with($user->email, '@admin.com')) {
            $user->user_role = 'admin';
        } elseif (str_ends_with($user->email, '@agency') || str_ends_with($user->email, '@agency.com')) {
            $user->user_role = 'agency';
        } else {
            $user->user_role = 'publicuser';
        }
        $user->password = bcrypt($request->password);
        $user->save();

        Auth::login($user);
        // Always redirect to the dashboard route for public users
        return redirect()->route('publicuser.dashboard')->with('success', "🎉 Welcome to Inquira, {$user->name}! Your account has been created successfully and you're now logged in.");
    }    // Show agency password update form
    public function showAgencyPasswordUpdateForm()
    {
        $user = Auth::user();

        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }        // Check if password has already been updated
        if (session('password_changed_' . $user->user_id)) {
            return redirect()->route('agency.dashboard')
                ->with('info', 'Your password has already been updated.');
        }

        return view('ManageUser.agency.recover_password', [
            'email' => $user->email,
            'token' => csrf_token()
        ]);
    }    public function loginPublic(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();

            return redirect()->route('publicuser.dashboard')->with('success', 'Welcome to Dashboard!');
        }        return back()->with('error', 'Invalid email or password. Please try again.')->withInput();
    }    public function loginAgency(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $email = $credentials['email'];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();            // FIRST: Check if contact number is missing (highest priority)
            if ($user->contact_number === null || empty(trim($user->contact_number))) {
                session(['return_to' => route('agency.dashboard')]);
                return redirect()->route('agency.password.update.form')
                    ->with('message', 'Please update your contact information before accessing the system.');
            }
            
            // SECOND: For @agency users (not .com), only check default password if they have never logged in
            // If they have contact_number and have logged in before, skip password recovery
            if (str_ends_with($email, '@agency') && is_null($user->last_login_at)) {
                session(['return_to' => route('agency.dashboard')]);
                return redirect()->route('agency.password.recover')
                    ->with('message', 'For security reasons, please update your password before accessing the system.');
            }

            // If all checks pass, proceed to agency dashboard
            return redirect()->route('agency.dashboard')->with('success', 'Welcome to Agency Dashboard!');
        }

        return back()->with('error', 'Invalid email or password. Please try again.');
    }
      public function showAgencyLoginForm()
    {
        return redirect()->route('login');
    }
    
    public function showAgencyPasswordRecovery()
    {
        $user = Auth::user();
        
        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }

        return view('ManageUser.agency.recover_password', [
            'email' => $user->email,
            'token' => csrf_token()
        ]);
    }    public function handleAgencyPasswordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
            'contact_number' => 'required|string|max:20',
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Check if user is authenticated and has agency role
        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Update the password, contact number, and name
        $user->password = Hash::make($request->password);
        $user->contact_number = $request->contact_number;
        $user->name = $request->name;
        $user->save();        // Set session flag to indicate password has been changed
        session(['password_changed_' . $user->user_id => true]);

        // Get the return URL from session, or use agency dashboard as default
        $returnTo = session('return_to') ?? route('agency.dashboard');
        
        // Clear the return_to session variable
        session()->forget('return_to');        // Redirect to the appropriate page (typically agency dashboard)
        return redirect($returnTo)->with('success', 'Your name, password, and contact information have been updated successfully!');
    }
      /**
     * Show the agency profile page
     */
    public function showAgencyProfile()
    {
        $user = Auth::user();
        
        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }
        
        // Load agency information if user has agency_id
        $agency = null;
        if ($user->agency_id) {
            $agency = Agency::find($user->agency_id);
        }
        
        return view('ManageUser.agency.Manageprofile', [
            'user' => $user,
            'agency' => $agency
        ]);
    }
    
    /**
     * Show the agency inquiries page
     */
    public function showAgencyInquiries()
    {
        $user = Auth::user();
        
        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }
        
        // In a real application, you would fetch inquiries related to this agency
        // For now, we'll just pass an empty array to the view
        $inquiries = [];
        
        return view('ManageUser.agency.inquiries', [
            'user' => $user,
            'inquiries' => $inquiries
        ]);
    }
    
    /**
     * Show the agency reports page
     */
    public function showAgencyReports()
    {
        $user = Auth::user();
        
        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }
        
        // In a real application, you would fetch reports data related to this agency
        // For now, we'll just pass placeholder data to the view
        $reports = [
            'total_inquiries' => 0,
            'completed' => 0,
            'pending' => 0,
            'recent_activity' => []
        ];
        
        return view('ManageUser.agency.reports', [
            'user' => $user,
            'reports' => $reports
        ]);
    }
    
    /**
     * Show the agency settings page
     */
    public function showAgencySettings()
    {
        $user = Auth::user();
        
        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }
        
        return view('ManageUser.agency.settings', [
            'user' => $user
        ]);
    }
    
    /**
     * Update agency profile information
     */
    public function updateAgencyProfile(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }
          // Validate the input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->user_id . ',user_id',
            'contact_number' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'agency_name' => 'required|string|max:100',
            'agency_email' => 'nullable|email|max:100',
            'agency_phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);
        
        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact_number = $request->contact_number;
        
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }
            
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('uploads/profile_pictures');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move the file to the public uploads directory
            $file->move($uploadPath, $filename);
            $user->profile_picture = $filename;
        }
        
        $user->save();
        
        // Handle agency information
        if ($user->agency_id) {
            // Update existing agency
            $agency = Agency::find($user->agency_id);
            if ($agency) {
                $agency->agency_name = $request->agency_name;
                $agency->agency_email = $request->agency_email;
                $agency->agency_phone = $request->agency_phone;
                $agency->description = $request->description;
                $agency->save();
            }
        } else {
            // Create new agency if none exists
            $agency = Agency::create([
                'agency_name' => $request->agency_name,
                'agency_email' => $request->agency_email,
                'agency_phone' => $request->agency_phone,
                'description' => $request->description,
            ]);
            
            // Link user to the new agency
            $user->agency_id = $agency->agency_id;
                    $user->save();
        }
        
        return redirect()->route('agency.profile')
            ->with('success', 'Profile updated successfully!');
    }
      /**
     * Update agency user password (from profile page)
     */
    public function updateAgencyPassword(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }
        
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        // Update password directly without current password verification
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return redirect()->route('agency.profile')
            ->with('success', 'Password updated successfully!');
    }
    
    /**
     * Show the agency view page
     */
    public function showAgencyView()
    {
        $user = Auth::user();
        
        if (!$user || $user->user_role !== 'agency') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }
        
        // Load agency information if user has agency_id
        $agency = null;
        if ($user->agency_id) {
            $agency = Agency::find($user->agency_id);
        }
        
        return view('ManageUser.agency.view', [
            'user' => $user,
            'agency' => $agency
        ]);
    }
    
    /**
     * Show the view assigned agencies page for public users
     */
    public function showViewAssigned()
    {
        $user = Auth::user();
        
        if (!$user || $user->user_role !== 'publicuser') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }
        
        // In a real application, you would fetch assigned agencies for this user
        // For now, we'll return a sample/empty list
        $assignedAgencies = collect(); // Empty collection for now
        
        return view('ManageUser.publicuser.view_assigned', [
            'user' => $user,
            'assignedAgencies' => $assignedAgencies
        ]);
    }
}