<?php

namespace App\Http\Controllers;

use App\Models\InnovationRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminInnovationRankingController extends Controller
{
    public function index()
    {
        $rankings = InnovationRanking::orderBy('rank')->get();
        return view('admin.rankings.index', compact('rankings'));
    }

    public function create()
    {
        return view('admin.rankings.form', [
            'mode' => 'create',
            'ranking' => new InnovationRanking(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rank' => 'required|integer|min:1|max:100',
            'achievement' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rankings', 'public');
        }

        InnovationRanking::create($data);

        return redirect()
            ->route('admin.innovation_rankings.index')
            ->with('success', 'Peringkat berhasil ditambahkan.');
    }

    public function edit(InnovationRanking $ranking)
    {
        return view('admin.rankings.form', [
            'mode' => 'edit',
            'ranking' => $ranking,
        ]);
    }

    public function update(Request $request, InnovationRanking $ranking)
    {
        $data = $request->validate([
            'rank' => 'required|integer|min:1|max:100',
            'achievement' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($ranking->image)) {
                Storage::disk('public')->delete($ranking->image);
            }

            $data['image'] = $request->file('image')->store('rankings', 'public');
        }

        $ranking->update($data);

        return redirect()
            ->route('admin.innovation_rankings.index')
            ->with('success', 'Peringkat berhasil diperbarui.');
    }

    public function destroy(InnovationRanking $ranking)
    {
        if (!empty($ranking->image)) {
            Storage::disk('public')->delete($ranking->image);
        }

        $ranking->delete();

        return redirect()
            ->route('admin.innovation_rankings.index')
            ->with('success', 'Peringkat berhasil dihapus.');
    }
}
