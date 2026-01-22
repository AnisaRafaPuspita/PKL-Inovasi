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
        $innovatorId = $request->query('innovator_id');


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
            })
            ->when($innovatorId, function ($query) use ($innovatorId) {
                $query->whereHas('innovators', function ($q) use ($innovatorId) {
                    $q->where('innovators.id', $innovatorId);
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
            'innovators' => Innovator::orderBy('name')->get(),
            'innovatorId' => $innovatorId,
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
            'innovators' => ['required', 'array', 'min:1'],

            'innovators.*.innovator_id' => ['nullable', 'exists:innovators,id'],
            'innovators.*.name' => ['nullable', 'string', 'max:255'],
            'innovators.*.faculty_id' => ['nullable', 'exists:faculties,id'],

            'category' => ['nullable', 'string'],
            'category_other' => ['nullable', 'string', 'max:255'],
            'partner' => ['nullable', 'string'],
            'hki_status' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url'],
            'description' => ['nullable', 'string'],
            'advantages' => ['nullable', 'string'],
            'impact' => ['nullable', 'string'],
            'images.*' => ['image'],
            'hki_registration_number' => ['nullable', 'string'],
            'hki_patent_number' => ['nullable', 'string'],
        ]);



        

        $innovation = Innovation::create([
            'title' => $validated['title'],
            'category' => $request->category === 'other'
                ? $request->category_other
                : $request->category,
            'partner' => $validated['partner'] ?? null,
            'hki_status' => $validated['hki_status'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'advantages' => $validated['advantages'] ?? null,
            'impact' => $validated['impact'] ?? null,
            'status' => 'pending',
            'views_count' => 0,
            'source' => 'innovator',
            'hki_status' => $request->hki_status,
            'hki_registration_number' => $request->hki_registration_number,
            'hki_patent_number' => $request->hki_patent_number,
        ]);

        foreach ($request->innovators as $item) {

            // existing innovator
            if (!empty($item['innovator_id'])) {
                $innovator = Innovator::findOrFail($item['innovator_id']);
            }
            // innovator baru
            else {
                if (empty($item['name']) || empty($item['faculty_id'])) {
                    return back()
                        ->withErrors([
                            'innovators' => 'Nama dan Fakultas wajib diisi untuk innovator baru.'
                        ])
                        ->withInput();
                }

                $innovator = Innovator::create([
                    'name' => $item['name'],
                    'faculty_id' => $item['faculty_id'],
                    'status' => 'pending',
                ]);
            }

            $innovation->innovators()->syncWithoutDetaching($innovator->id);
        }






        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('innovations', 'public');

                $innovation->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
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