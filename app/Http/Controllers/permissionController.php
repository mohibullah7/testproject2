<?php

namespace App\Http\Controllers;

use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of roles with their permissions.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        return view('permissions.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for managing role permissions.
     */
    public function manage(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $selectedRole = null;
        $rolePermissions = [];
        
        if ($request->has('role')) {
            $selectedRole = Role::with('permissions')->where('id', $request->role)->first();
            if ($selectedRole) {
                $rolePermissions = $selectedRole->permissions->pluck('name')->toArray();
            }
        }
        
        return view('permissions.manage', compact('roles', 'permissions', 'selectedRole', 'rolePermissions'));
    }

    /**
     * Update role permissions.
     */
    public function updatePermissions(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        try {
            $role = Role::findById($request->role_id);
            $permissions = $request->permissions ?? [];
            
            // Sync permissions
            $role->syncPermissions($permissions);
            
            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully for role: ' . $role->name,
                'permissions' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new role.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        try {
            $role = Role::create(['name' => $request->name]);
            
            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role: ' . $e->getMessage()
            ], 500);
        }
    }

    

    /**
     * Store a new permission.
     */
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        try {
            $permission = Permission::create(['name' => $request->name]);
            
            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully',
                'permission' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create permission: ' . $e->getMessage()
            ], 500);
        }
    }


  

    /**
     * Delete a role.
     */
    public function destroyRole($id)
    {
        try {
            $role = Role::findById($id);
            
            // Prevent deleting admin role
            if ($role->name === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete admin role'
                ], 403);
            }
            
            $role->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a permission.
     */
    public function destroyPermission($id)
    {
        try {
            $permission = Permission::findById($id);
            $permission->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete permission: ' . $e->getMessage()
            ], 500);
        }
    }


    
}