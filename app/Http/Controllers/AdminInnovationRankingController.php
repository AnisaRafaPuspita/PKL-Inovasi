<?php

namespace App\Http\Controllers;

use App\Models\InnovationRanking;
use App\Models\InnovationRankingPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminInnovationRankingController extends Controller
{
    public function index()
    {
        $rankings = InnovationRanking::with('photos')->orderBy('rank')->get();
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
            'rank' => 'required|integer|min:1|max:2000',
            'achievement' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:4096',
            'reference_link' => 'required|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('rankings', 'public');
        }

        $ranking = InnovationRanking::create($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('rankings/photos', 'public');
                $ranking->photos()->create(['path' => $path]);
            }
        }

        return redirect()
            ->route('admin.innovation_rankings.index')
            ->with('success', 'Peringkat berhasil ditambahkan.');
    }

    public function edit(InnovationRanking $ranking)
    {
        $ranking->load('photos');

        return view('admin.rankings.form', [
            'mode' => 'edit',
            'ranking' => $ranking,
        ]);
    }

    public function update(Request $request, InnovationRanking $ranking)
    {
        $data = $request->validate([
            'rank' => 'required|integer|min:1|max:2000',
            'achievement' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:4096',
            'delete_photo_ids' => 'nullable|array',
            'delete_photo_ids.*' => 'integer|exists:innovation_ranking_photos,id',
            'reference_link' => 'required|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            if (!empty($ranking->logo)) {
                Storage::disk('public')->delete($ranking->logo);
            }
            $data['logo'] = $request->file('logo')->store('rankings', 'public');
        }

        $ranking->update($data);

        if ($request->filled('delete_photo_ids')) {
            $photosToDelete = InnovationRankingPhoto::where('innovation_ranking_id', $ranking->id)
                ->whereIn('id', $request->input('delete_photo_ids', []))
                ->get();

            foreach ($photosToDelete as $p) {
                Storage::disk('public')->delete($p->path);
                $p->delete();
            }
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('rankings/photos', 'public');
                $ranking->photos()->create(['path' => $path]);
            }
        }

        return redirect()
            ->route('admin.innovation_rankings.index')
            ->with('success', 'Peringkat berhasil diperbarui.');
    }

    public function destroy(InnovationRanking $ranking)
    {
        $ranking->load('photos');

        if (!empty($ranking->logo)) {
            Storage::disk('public')->delete($ranking->logo);
        }

        foreach ($ranking->photos as $p) {
            Storage::disk('public')->delete($p->path);
        }

        $ranking->delete();

        return redirect()
            ->route('admin.innovation_rankings.index')
            ->with('success', 'Peringkat berhasil dihapus.');
    }
}
