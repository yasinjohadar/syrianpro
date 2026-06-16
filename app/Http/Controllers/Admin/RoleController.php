<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Support\PermissionRegistry;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use RespondsWithAjaxTable;

public function __construct()
{
    // يمكنه فقط رؤية قائمة الصلاحيات (index)
    $this->middleware(['permission:role-list'])->only('index');

    // يمكنه فقط إنشاء صلاحية جديدة (create + store)
    $this->middleware(['permission:role-create'])->only(['create', 'store']);

    // يمكنه فقط تعديل الصلاحية (edit + update)
    $this->middleware(['permission:role-edit'])->only(['edit', 'update']);

    // يمكنه فقط حذف الصلاحية (destroy)
    $this->middleware(['permission:role-delete'])->only('destroy');
}




    public function index(Request $request)
    {
        $data = $this->buildRolesIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.pages.roles.partials.list',
            'admin.pages.roles.partials.modals'
        )) {
            return $response;
        }

        return view('admin.pages.roles.index', $data);
    }

    /**
     * @return array{roles: \Illuminate\Contracts\Pagination\LengthAwarePaginator, stats: array<string, int>}
     */
    private function buildRolesIndexData(Request $request): array
    {
        $rolesQuery = Role::withCount(['permissions', 'users']);

        if ($request->filled('query')) {
            $search = $request->input('query');
            $rolesQuery->where('name', 'like', "%{$search}%");
        }

        $roles = $rolesQuery->orderBy('name')->paginate(15)->withQueryString();

        $allRoles = Role::withCount(['permissions', 'users'])->get();

        $stats = [
            'total' => Role::count(),
            'permissions' => Permission::count(),
            'assigned_permissions' => (int) $allRoles->sum('permissions_count'),
            'users' => (int) $allRoles->sum('users_count'),
            'filtered' => $roles->total(),
        ];

        return compact('roles', 'stats');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        PermissionRegistry::syncToDatabase();

        return view('admin.pages.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions(PermissionRegistry::namesFromRequest($request->input('permissions')));

        return redirect()->route('admin.roles.index')->with('success', 'تم إضافة الدور بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        PermissionRegistry::syncToDatabase();
        $role = Role::findOrFail($id);

        return view('admin.pages.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions(PermissionRegistry::namesFromRequest($request->input('permissions')));

        return redirect()->route('admin.roles.index')->with('success', 'تم تعديل الدور بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request )
    {
        $role = Role::findOrFail($request->id);
        $role->delete();
        return redirect()->route("admin.roles.index")->with("success" , "تم حذف الدور بنجاح");
    }
}
