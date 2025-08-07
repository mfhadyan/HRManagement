<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Models\Access;
use App\Models\Admin;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    private $roles;

    public function __construct()
    {
        $this->middleware('auth');  
        
        $this->roles = resolve(Role::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->roles->paginate();
        return view('pages.roles', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = Menu::all();
        return view('pages.roles_create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $roleId = Role::create(['name' => $request->input('name')])->id;

        if($request->input('is_super_user') == "1") {
            Admin::create([
                'role_id' => $roleId
            ]);
        }

        foreach($request->menuAndAccessLevel as $mna) {
            $key = key($mna);
            Access::create([
                'role_id' => $roleId,
                'menu_id' => $key,
                'status' => $mna[$key]
            ]);
        }

        return redirect()->route('roles.index');
    }

    public function destroy($id)
    {
        $role = $this->roles->find($id);
        $role->delete();
        return redirect()->route('roles.index');
    }

    public function edit($id)
    {
        $role = $this->roles->find($id);
        return view('pages.roles_edit', compact('role'));
    }

    public function update(StoreRoleRequest $request, $id)
    {
        $role = $this->roles->find($id);
        $role->update($request->all());
        return redirect()->route('roles.index');
    }
}