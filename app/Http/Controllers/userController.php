<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = User::paginate(10);

         $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'roles' => 'required|string|exists:roles,name',
        ]);

        try {
            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign the role to the user
            $user->assignRole($validated['roles']);

            // Redirect with success message
            return redirect()
                ->route('users.index')
                ->with('success', 'User created successfully with role: ' . ucfirst($validated['roles']));

        } catch (\Exception $e) {
            // Handle any errors
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $user = User::with('roles', 'permissions')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $user = User::findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        return view('users.editor', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'roles' => 'required|string|exists:roles,name',
        ]);

        try {
            // Update user data
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];
            
            // Only update password if provided
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            
            $user->update($userData);
            
            // Sync roles (remove old role and assign new one)
            $user->syncRoles([$validated['roles']]);
            
            return redirect()
                ->route('users.index')
                ->with('success', 'User updated successfully');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting your own account
            if ($user->id === auth()->id()) {
                return redirect()
                    ->route('users.index')
                    ->with('error', 'You cannot delete your own account.');
            }
            
            $user->delete();
            
            return redirect()
                ->route('users.index')
                ->with('success', 'User deleted successfully');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Failed to delete user. Please try again.');
        }
    
    }
}
