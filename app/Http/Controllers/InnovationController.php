<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\Innovator;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Models\InnovationViewStat;
use App\Models\InnovationPermission;

class InnovationController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $category = $request->query('category');
        $facultyId = $request->query('faculty_id');

        $baseQuery = Innovation::query()
            ->where('status', 'published')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%");
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->when($facultyId, function ($query) use ($facultyId) {
                $query->whereHas('innovators.faculty', function ($q) use ($facultyId) {
                    $q->where('faculties.id', $facultyId);
                });
            });

        $impactInnovations = (clone $baseQuery)
            ->impact()
            ->with('images')
            ->latest()
            ->take(9)
            ->get();

        $innovations = (clone $baseQuery)
            ->product()
            ->with('images')
            ->latest()
            ->paginate(9);

        $innovations->appends($request->query());

        return view('pages.innovations.index', [
            'impactInnovations' => $impactInnovations,
            'innovations' => $innovations,
            'q' => $q,
            'category' => $category,
            'facultyId' => $facultyId,
            'categories' => config('innovation.categories'),
            'faculties' => Faculty::orderBy('name')->get(),
        ]);
    }

    public function show(Innovation $innovation)
    {
        if ($innovation->status !== 'published') {
            abort(404);
        }

        $innovation->load('innovators');

        $innovation->increment('views_count');
        $innovation->refresh();

        $today = now()->toDateString();
        $stat = InnovationViewStat::firstOrCreate(['date' => $today]);
        $stat->increment('views');

        return view('pages.innovations.show', compact('innovation'));
    }

    public function create()
    {
        $innovators = Innovator::with('faculty')->orderBy('name')->get();
        $faculties = Faculty::orderBy('name')->get();
        $categories = config('innovation.categories');

        return view('pages.innovations.create', compact('innovators', 'faculties', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'innovator_id' => ['nullable', 'exists:innovators,id'],
            'new_innovator_name' => ['nullable', 'string', 'max:255'],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
            'category' => ['nullable', 'string', 'max:255'],
            'partner' => ['nullable', 'string', 'max:255'],
            'hki_status' => ['nullable', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'advantages' => ['nullable', 'string'],
            'impact' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $innovatorId = null;

        if ($request->filled('new_innovator_name')) {
            $request->validate([
                'faculty_id' => ['required', 'exists:faculties,id'],
            ]);

            $innovator = Innovator::create([
                'name' => $request->input('new_innovator_name'),
                'faculty_id' => $request->input('faculty_id'),
                'status' => 'pending',
            ]);

            $innovatorId = $innovator->id;
        } else {
            $innovatorId = $validated['innovator_id'] ?? null;
        }

        $innovation = Innovation::create([
            'title' => $validated['title'],
            'category' => $validated['category'] ?? null,
            'partner' => $validated['partner'] ?? null,
            'hki_status' => $validated['hki_status'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'advantages' => $validated['advantages'] ?? null,
            'impact' => $validated['impact'] ?? null,
            'status' => 'pending',
            'views_count' => 0,
            'source' => 'innovator',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('innovations', 'public');

                $innovation->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        if ($innovatorId) {
            $innovation->innovators()->syncWithoutDetaching([$innovatorId]);
        }

        InnovationPermission::firstOrCreate(
            ['innovation_id' => $innovation->id],
            ['status' => 'pending']
        );

        return redirect()
            ->route('home')
            ->with('success', 'Produk berhasil diupload dan sedang menunggu persetujuan admin.')
            ->withInput([]);
    }
}
