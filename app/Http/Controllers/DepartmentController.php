<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $departments = Department::latest()->paginate();

        return view('department.index', compact('departments'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Department::create($request->all());

        return redirect()->route('department.index')
            ->with('success','Department created successfully.');
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Department $department)
    {
        return view('department.show', compact('department'));
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Department $department)
    {
        return view('department.edit', compact('department'));
    }

    /**
     * @param Request $request
     * @param Department $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $department->update($request->all());

        return redirect()->route('department.index')
            ->with('success','Department updated successfully.');
    }

    /**
     * @param Department $department
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Department $department)
    {
        if ($department->users()->count() > 0) {
            return redirect()->route('department.index')
                ->with('error','Department has users associated.');
        }

        $department->delete();

        return redirect()->route('department.index')
            ->with('success','Department deleted successfully');
    }
}
