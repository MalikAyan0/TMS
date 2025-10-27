<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\UserStatus;

class RoleController extends Controller
{
    public function index(): Response|JsonResponse
    {
        try {
            // Get roles with users
            $roles = Role::with('users')->get();
            $permissions = Permission::all()->groupBy('module');
            $statuses = UserStatus::all();
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $roles
                ]);
            }

            return response()->view(
                'content.pages.roles-permissions.roles',
                compact('roles', 'permissions', 'statuses')
            );

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to retrieve roles',
                    'error' => $e->getMessage()
                ], 500);
            }

            return response()->view('errors.general', [
                'message' => 'Failed to retrieve roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('content.pages.roles-permissions.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }


    public function permissionsJson(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($item) {
            return explode('.', $item->name)[0];
        });

        // Format for frontend
        $formatted = [];
        foreach ($permissions as $module => $perms) {
          $formatted[$module] = [];
          foreach ($perms as $perm) {
              [$moduleName, $action] = explode('.', $perm->name, 2);
              $formatted[$module][] = [
                  'id' => $perm->id,
                  'name' => $perm->name,
                  'action' => ucfirst($action ?? '')
              ];
          }
      }
        return response()->json(['permissions' => $formatted]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->name = $request->name;
        $role->save();

        // Sync permissions
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    // Delete
    public function destroy(Role $role)
    {
    if ($role->name === 'Admin') {
        if (request()->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete the Admin role.'], 403);
        }
        return redirect()->route('roles.index')->with('error', 'Cannot delete the Admin role.');
    }

    $role->delete();

    if (request()->wantsJson()) {
        return response()->json(['success' => true, 'message' => 'Role deleted successfully.']);
    }
    return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
}
}
