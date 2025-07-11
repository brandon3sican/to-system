<?php

namespace App\Http\Controllers;

use App\Models\DivSecUnit;
use Illuminate\Http\Request;

class DivSecUnitController extends Controller
{
    public function index()
    {
        $divSecUnits = DivSecUnit::paginate(10);
        return view('divsecunits.index', compact('divSecUnits'));
    }

    public function create()
    {
        return view('divsecunits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:div_sec_units|max:100',
            'description' => 'nullable|max:65535'
        ]);

        DivSecUnit::create($request->all());
        return redirect()->route('divsecunits.index')->with('success', 'Division/Section/Unit created successfully');
    }

    public function edit(DivSecUnit $divSecUnit)
    {
        return view('divsecunits.edit', compact('divSecUnit'));
    }

    public function update(Request $request, DivSecUnit $divSecUnit)
    {
        $request->validate([
            'name' => 'required|unique:div_sec_units,name,' . $divSecUnit->id . '|max:100',
            'description' => 'nullable|max:65535'
        ]);

        $divSecUnit->update($request->all());
        return redirect()->route('divsecunits.index')->with('success', 'Division/Section/Unit updated successfully');
    }

    public function destroy(DivSecUnit $divSecUnit)
    {
        if ($divSecUnit->positions()->count() > 0) {
            return back()->with('error', 'Cannot delete division/section/unit with assigned positions');
        }

        $divSecUnit->delete();
        return redirect()->route('divsecunits.index')->with('success', 'Division/Section/Unit deleted successfully');
    }
}
