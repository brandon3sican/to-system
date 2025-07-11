<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\DivSecUnit;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('divSecUnit')->paginate(10);
        $divSecUnits = DivSecUnit::all();
        return view('positions.index', compact('positions', 'divSecUnits'));
    }

    public function create()
    {
        $divSecUnits = DivSecUnit::all();
        return view('positions.create', compact('divSecUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:positions|max:100',
            'div_sec_unit_id' => 'required|exists:div_sec_units,id'
        ]);

        Position::create($request->all());
        return redirect()->route('positions.index')->with('success', 'Position created successfully');
    }

    public function edit(Position $position)
    {
        $divSecUnits = DivSecUnit::all();
        return view('positions.edit', compact('position', 'divSecUnits'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|unique:positions,name,' . $position->id . '|max:100',
            'div_sec_unit_id' => 'required|exists:div_sec_units,id'
        ]);

        $position->update($request->all());
        return redirect()->route('positions.index')->with('success', 'Position updated successfully');
    }

    public function destroy(Position $position)
    {
        if ($position->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete position with assigned employees');
        }

        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Position deleted successfully');
    }
}
