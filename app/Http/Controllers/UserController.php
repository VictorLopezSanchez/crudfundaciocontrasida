<?php

namespace App\Http\Controllers;

use App\Department;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::latest()->paginate();

        return view('user.index', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all();
        return view('user.create', compact('departments'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        $department = Department::find($request->get('department'));
        if (empty($department)) {
            throw ValidationException::withMessages(['department' => 'This value doesn"t exist']);
        }

        $user = new User($request->all());
        $department->users()->save($user);

        return redirect()->route('user.index')
            ->with('success','User created successfully.');
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $data['departments'] = Department::all();
        $data['user'] = $user;

        return view('user.edit', ['data' => $data]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'department' => 'required',
        ]);

        $department = Department::find($request->get('department'));
        if (empty($department)) {
            throw ValidationException::withMessages(['department' => 'This value doesn"t exist']);
        }

        $data = [
            "name" => $request->get('name'),
            "phone" => $request->get('phone'),
            "department_id" => $request->get('department')
        ];

        $user->update($data);

        return redirect()->route('user.index')
            ->with('success','User updated successfully.');
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')
            ->with('success','User deleted successfully');
    }}
