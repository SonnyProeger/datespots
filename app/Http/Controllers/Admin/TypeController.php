<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Traits\CrudOperationsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TypeController extends Controller
{
    use CrudOperationsTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Type::class);

        $filters = $request->only('search', 'trashed');

        $query = $this->commonIndexLogic(Type::class, $filters);

        $types = $query->paginate(10)
            ->withQueryString()
            ->through(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'deleted_at' => $type->deleted_at,
                ];
            });

        return Inertia::render('Admin/Types/Index', [
            'types' => $types,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Type::class);

        return Inertia::render('Admin/Types/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Type::class);

        $validatedData = $request->validate([
            'name' => ['required', 'max:50', Rule::unique('types')],
        ]);

        Type::create($validatedData);

        return Redirect::route('types.index')->with('success', 'Type created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $type = Type::withTrashed()->find($id);
        $this->authorize('update', $type);

        return Inertia::render('Admin/Types/Edit', [
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {
        $this->authorize('update', $type);

        $validatedData = $request->validate([
            'name' => ['required', 'max:50', Rule::unique('types')->ignore($type->id)],
        ]);

        $type->update($validatedData);

        return Redirect::back()->with('success', 'Type updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $this->authorize('delete', $type);

        $type->delete();

        return Redirect::route('types.index')->with('success', "$type->name deleted.");
    }

    public function restore(Type $type)
    {
        $this->authorize('restore', $type);
        $type->restore();

        return Redirect::back()->with('succes', 'Type restored.');
    }
}
