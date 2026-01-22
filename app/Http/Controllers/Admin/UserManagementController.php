<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Services\PhotoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserManagementController extends Controller
{
    protected $photoService;

    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('department', 'designation', 'userRoles')->paginate(10);
        $departments = Department::all();
        return view('admin.users.index', compact('users', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = ['admin', 'director', 'dean', 'faculty'];
        $departments = Department::all();
        $designations = Designation::all();
        return view('admin.users.create', compact('roles', 'departments', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:admin,director,dean,faculty',
            'is_active' => 'boolean',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        // Store roles separately
        $roles = $validated['roles'];
        unset($validated['roles']);

        // Create user
        $user = User::create($validated);

        // Assign roles
        foreach ($roles as $role) {
            $user->assignRole($role);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('userRoles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'director', 'dean', 'faculty'];
        $departments = Department::all();
        $designations = Designation::all();
        $user->load('userRoles');
        return view('admin.users.edit', compact('user', 'roles', 'departments', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:admin,director,dean,faculty',
            'is_active' => 'boolean',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Handle roles separately
        $newRoles = $validated['roles'];
        unset($validated['roles']);

        // Update user data
        $user->update($validated);

        // Update roles
        $currentRoles = $user->roles();
        
        // Remove roles that are no longer selected
        foreach ($currentRoles as $role) {
            if (!in_array($role, $newRoles)) {
                $user->removeRole($role);
            }
        }

        // Add new roles
        foreach ($newRoles as $role) {
            if (!in_array($role, $currentRoles)) {
                $user->assignRole($role);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if (auth()->user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account');
        }

        // Delete photos
        $this->photoService->deleteAllUserPhotos($user);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot toggle your own status');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.users.index')->with('success', "User {$status} successfully");
    }
}