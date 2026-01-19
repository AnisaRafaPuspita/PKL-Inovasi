<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\Innovator;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminInnovationController extends Controller
{
    public function index()
    {
        $innovations = Innovation::with(['innovators.faculty', 'images', 'primaryImage'])
            ->where('status', 'published')
            // BIAR INOVASI DARI USER (innovator) JUGA MASUK MANAGE
            ->whereIn('source', ['admin', 'innovator'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.innovations.index', compact('innovations'));
    }

    public function create()
    {
        return view('admin.innovations.form', [
            'mode' => 'create',
            'innovation' => new Innovation(),
            'faculties' => Faculty::orderBy('name')->get(),
            'innovators' => Innovator::with('faculty')->orderBy('name')->get(),
            'categories' => config('innovation.categories'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateInnovation($request);

        $request->validate([
            'innovator_id' => ['nullable', 'exists:innovators,id'],
            'new_innovator_name' => ['nullable', 'string', 'max:255'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['source'] = 'admin';
        $data['status'] = $data['status'] ?? 'published';

        $innovatorId = null;

        if ($request->filled('new_innovator_name')) {
            $innovator = Innovator::firstOrCreate(
                ['name' => $request->input('new_innovator_name')],
                ['faculty_id' => $request->input('faculty_id'), 'status' => 'pending']
            );

            if ((int) $innovator->faculty_id !== (int) $request->input('faculty_id')) {
                $innovator->update(['faculty_id' => $request->input('faculty_id')]);
            }

            $innovatorId = $innovator->id;
        } else {
            $innovatorId = $request->input('innovator_id');
        }

        $innovation = Innovation::create($data);

        if ($innovatorId) {
            $innovation->innovators()->syncWithoutDetaching([$innovatorId]);
        }

        $this->storeImagesFromRequest($request, $innovation);

        return redirect()
            ->route('admin.innovations.index')
            ->with('success', 'Inovasi berhasil ditambahkan.');
    }

    public function edit(Innovation $innovation)
    {
        // EDIT TETAP KHUSUS INOVASI ADMIN
        abort_if($innovation->source !== 'admin', 404);

        $innovation->load(['innovators.faculty', 'images', 'primaryImage']);

        return view('admin.innovations.form', [
            'mode' => 'edit',
            'innovation' => $innovation,
            'faculties' => Faculty::orderBy('name')->get(),
            'innovators' => Innovator::with('faculty')->orderBy('name')->get(),
            'categories' => config('innovation.categories'),
        ]);
    }

    public function update(Request $request, Innovation $innovation)
    {
        // UPDATE TETAP KHUSUS INOVASI ADMIN
        abort_if($innovation->source !== 'admin', 404);

        $data = $this->validateInnovation($request);

        $request->validate([
            'innovator_id' => ['nullable', 'exists:innovators,id'],
            'new_innovator_name' => ['nullable', 'string', 'max:255'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer'],
        ]);

        $data['source'] = 'admin';
        $data['status'] = $data['status'] ?? 'published';

        $innovation->update($data);

        $innovatorId = null;

        if ($request->filled('new_innovator_name')) {
            $innovator = Innovator::firstOrCreate(
                ['name' => $request->input('new_innovator_name')],
                ['faculty_id' => $request->input('faculty_id'), 'status' => 'pending']
            );

            if ((int) $innovator->faculty_id !== (int) $request->input('faculty_id')) {
                $innovator->update(['faculty_id' => $request->input('faculty_id')]);
            }

            $innovatorId = $innovator->id;
        } else {
            $innovatorId = $request->input('innovator_id');
        }

        if ($innovatorId) {
            $innovation->innovators()->sync([$innovatorId]);
        }

        if ($request->filled('delete_image_ids')) {
            $innovation->images()
                ->whereIn('id', $request->input('delete_image_ids', []))
                ->get()
                ->each(function ($img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                });
        }

        $this->storeImagesFromRequest($request, $innovation);
        $this->ensurePrimaryImage($innovation);

        return redirect()
            ->route('admin.innovations.show', $innovation->id)
            ->with('success', 'Inovasi berhasil diperbarui.');
    }

    public function show(Innovation $innovation)
    {
        // SHOW BOLEH ADMIN + INNOVATOR (biar published dari user gak 404)
        abort_if(!in_array($innovation->source, ['admin', 'innovator']), 404);

        $innovation->load(['innovators.faculty', 'images', 'primaryImage']);

        return view('admin.innovations.show', [
            'innovation' => $innovation,
            'faculties' => Faculty::orderBy('name')->get(),
            'innovators' => Innovator::with('faculty')->orderBy('name')->get(),
            'categories' => config('innovation.categories'),
        ]);
    }

    private function validateInnovation(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'partner' => 'nullable|string|max:255',
            'hki_status' => 'nullable|string|max:255',
            'video_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'review' => 'nullable|string',
            'advantages' => 'nullable|string',
            'impact' => 'nullable|string|max:255',
            'status' => 'nullable|in:published,draft',
        ]);
    }

    private function storeImagesFromRequest(Request $request, Innovation $innovation): void
    {
        $files = [];

        if ($request->hasFile('images')) {
            $files = array_merge($files, $request->file('images'));
        }

        if ($request->hasFile('photo')) {
            $files[] = $request->file('photo');
        }

        if (!count($files)) {
            return;
        }

        $hasPrimary = $innovation->images()->where('is_primary', true)->exists();

        foreach (array_values($files) as $index => $file) {
            $path = $file->store('innovations', 'public');

            $innovation->images()->create([
                'image_path' => $path,
                'is_primary' => (!$hasPrimary && $index === 0),
            ]);
        }
    }

    private function ensurePrimaryImage(Innovation $innovation): void
    {
        $hasPrimary = $innovation->images()->where('is_primary', true)->exists();
        if ($hasPrimary) return;

        $first = $innovation->images()->orderBy('id')->first();
        if ($first) $first->update(['is_primary' => true]);
    }
}
