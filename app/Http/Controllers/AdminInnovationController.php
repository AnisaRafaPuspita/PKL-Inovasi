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
            ->whereIn('status', ['published', 'draft'])
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
            'faculty_id' => ['required', 'exists:faculties,id'],
            'innovators_payload' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if (($data['category'] ?? null) === 'other') {
            $request->validate([
                'category_other' => ['required', 'string', 'max:255'],
            ]);

            $data['category'] = trim((string) $request->input('category_other'));
        }

        $data['category'] = isset($data['category']) ? trim((string) $data['category']) : null;
        if ($data['category'] === '') $data['category'] = null;

        $data['source'] = 'admin';
        $data['status'] = $data['status'] ?? 'published';

        $innovation = Innovation::create($data);

        $innovatorIds = $this->collectInnovatorIdsFromPayload(
            $request->input('innovators_payload'),
            (int) $request->input('faculty_id')
        );

        if (count($innovatorIds)) {
            $innovation->innovators()->sync($innovatorIds);
        }

        $this->storeImagesFromRequest($request, $innovation);

        return redirect()
            ->route('admin.innovations.index')
            ->with('success', 'Inovasi berhasil ditambahkan.');
    }

    public function edit(Innovation $innovation)
    {
        abort_if(!in_array($innovation->source, ['admin', 'innovator']), 404);

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
        abort_if(!in_array($innovation->source, ['admin', 'innovator']), 404);

        $data = $this->validateInnovation($request);

        $request->validate([
            'faculty_id' => ['required', 'exists:faculties,id'],
            'innovators_payload' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer'],
        ]);

        if (($data['category'] ?? null) === 'other') {
            $request->validate([
                'category_other' => ['required', 'string', 'max:255'],
            ]);

            $data['category'] = trim((string) $request->input('category_other'));
        }

        $data['category'] = isset($data['category']) ? trim((string) $data['category']) : null;
        if ($data['category'] === '') $data['category'] = null;

        $data['source'] = 'admin';
        $data['status'] = $data['status'] ?? 'published';

        $innovation->update($data);

        $innovatorIds = $this->collectInnovatorIdsFromPayload(
            $request->input('innovators_payload'),
            (int) $request->input('faculty_id')
        );

        $innovation->innovators()->sync($innovatorIds);

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
        abort_if(!in_array($innovation->source, ['admin', 'innovator']), 404);

        $innovation->load(['innovators.faculty', 'images', 'primaryImage']);

        return view('admin.innovations.show', [
            'innovation' => $innovation,
            'faculties' => Faculty::orderBy('name')->get(),
            'innovators' => Innovator::with('faculty')->orderBy('name')->get(),
            'categories' => config('innovation.categories'),
        ]);
    }

    public function destroy(Innovation $innovation)
    {
        abort_if(!in_array($innovation->source, ['admin', 'innovator']), 404);

        $innovation->load(['images', 'primaryImage']);

        if ($innovation->images && $innovation->images->count()) {
            foreach ($innovation->images as $img) {
                if ($img->image_path) {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }
        }

        if ($innovation->primaryImage?->image_path) {
            Storage::disk('public')->delete($innovation->primaryImage->image_path);
        }

        $innovation->innovators()->detach();

        $innovation->delete();

        return redirect()
            ->route('admin.innovations.index')
            ->with('success', 'Inovasi berhasil dihapus.');
    }

    private function validateInnovation(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'category_other' => 'nullable|string|max:255',
            'partner' => 'nullable|string|max:255',
            'hki_status' => 'nullable|string|max:255',
            'hki_registration_number' => 'nullable|string|max:255',
            'hki_patent_number' => 'nullable|string|max:255',
            'video_url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'review' => 'nullable|string',
            'advantages' => 'nullable|string',
            'impact' => 'nullable|string',
            'status' => 'nullable|in:published,draft',
        ]);
    }


    private function collectInnovatorIdsFromPayload(?string $payload, int $fallbackFacultyId): array
    {
        if (!$payload) return [];

        $items = json_decode($payload, true);
        if (!is_array($items)) return [];

        $ids = [];

        foreach ($items as $item) {
            if (!is_array($item)) continue;

            $type = $item['type'] ?? null;

            if ($type === 'existing') {
                $id = $item['id'] ?? null;
                if (!$id) continue;

                $innovator = Innovator::find($id);
                if (!$innovator) continue;

                $facultyId = isset($item['faculty_id']) && $item['faculty_id']
                    ? (int) $item['faculty_id']
                    : null;

                if ($facultyId && (int) $innovator->faculty_id !== $facultyId) {
                    $innovator->update(['faculty_id' => $facultyId]);
                }

                $ids[] = (int) $innovator->id;
                continue;
            }

            if ($type === 'new') {
                $name = trim((string) ($item['name'] ?? ''));
                if ($name === '') continue;

                $facultyId = isset($item['faculty_id']) && $item['faculty_id']
                    ? (int) $item['faculty_id']
                    : $fallbackFacultyId;

                $innovator = Innovator::firstOrCreate(
                    ['name' => $name],
                    ['faculty_id' => $facultyId, 'status' => 'pending']
                );

                if ((int) $innovator->faculty_id !== (int) $facultyId) {
                    $innovator->update(['faculty_id' => $facultyId]);
                }

                $ids[] = (int) $innovator->id;
            }
        }

        $ids = array_values(array_unique(array_filter($ids)));

        return $ids;
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
