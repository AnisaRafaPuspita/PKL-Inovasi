<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\Innovator;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\InnovationPermission;

class AdminInnovationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $year = $request->query('year');
        $perPage = (int) $request->query('per_page', 20);

        if (!in_array($perPage, [20, 50, 100])) {
            $perPage = 20;
        }

        $innovations = Innovation::with(['innovators.faculty', 'images', 'primaryImage'])
            ->whereIn('status', ['published', 'draft'])
            ->whereIn('source', ['admin', 'innovator'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', '%' . $search . '%')
                        ->orWhereHas('innovators', function ($innovatorQuery) use ($search) {
                            $innovatorQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when(in_array($status, ['published', 'draft']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(!empty($year), function ($query) use ($year) {
                $query->whereYear('created_at', $year);
            })
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $years = Innovation::query()
            ->whereIn('status', ['published', 'draft'])
            ->whereIn('source', ['admin', 'innovator'])
            ->selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('admin.innovations.index', compact(
            'innovations',
            'years',
            'perPage'
        ));
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

        $data['leader_email'] = isset($data['leader_email']) && trim((string)$data['leader_email']) !== ''
            ? trim((string)$data['leader_email'])
            : null;

        $innovation = Innovation::create($data);

        InnovationPermission::firstOrCreate(
            ['innovation_id' => $innovation->id],
            ['status' => 'accepted']
        );

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

        $data['leader_email'] = isset($data['leader_email']) && trim((string)$data['leader_email']) !== ''
            ? trim((string)$data['leader_email'])
            : null;

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
            'category' => 'required|string|max:255',
            'category_other' => 'nullable|string|max:255',
            'partner' => 'nullable|string|max:255',
            'ki_type' => 'required|string|max:255',
            'ki_status' => 'required|string|max:255',
            'ki_number' => 'required|string|max:255',

            'leader_email' => 'nullable|email|max:255',

            'video_url' => 'nullable|url|max:255',
            'description' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $text = trim(preg_replace('/\s+/', ' ', (string) $value));
                    $words = $text === '' ? 0 : count(explode(' ', $text));

                    if ($words > 200) {
                        $fail("Deskripsi maksimal 200 kata. Saat ini {$words} kata.");
                    }
                },
            ],
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

    public function updateStatus(Request $request, Innovation $innovation)
    {
        abort_if(!in_array($innovation->source, ['admin', 'innovator']), 404);

        $data = $request->validate([
            'status' => ['required', 'in:published,draft'],
        ]);

        $innovation->update([
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Status inovasi berhasil diubah.');
    }
}