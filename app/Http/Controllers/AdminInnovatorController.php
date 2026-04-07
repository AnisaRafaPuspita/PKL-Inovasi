<?php

namespace App\Http\Controllers;

use App\Models\Innovator;
use App\Models\InnovatorOfTheMonth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminInnovatorController extends Controller
{
    public function index()
    {
        $items = InnovatorOfTheMonth::with(['innovator.faculty', 'innovation'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.innovators.index', compact('items'));
    }

    public function create()
    {
        $innovators = Innovator::with(['faculty', 'innovations'])
            ->orderBy('name')
            ->get();

        return view('admin.innovators.form', [
            'mode' => 'create',
            'iotm' => new InnovatorOfTheMonth(),
            'innovators' => $innovators,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedPayload($request);

        $this->assertInnovationBelongsToInnovator(
            (int) $validated['innovator_id'],
            (int) $validated['innovation_id']
        );

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('innovator_of_month', 'public');
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = 1;
        }

        InnovatorOfTheMonth::create($validated);

        return redirect()
            ->route('admin.innovators.index')
            ->with('success', 'Data innovator berhasil ditambahkan');
    }

    public function edit(InnovatorOfTheMonth $item)
    {
        $innovators = Innovator::with(['faculty', 'innovations'])
            ->orderBy('name')
            ->get();

        return view('admin.innovators.form', [
            'mode' => 'edit',
            'iotm' => $item,
            'innovators' => $innovators,
        ]);
    }

    public function update(Request $request, InnovatorOfTheMonth $item)
    {
        $validated = $this->validatedPayload($request, true);

        $this->assertInnovationBelongsToInnovator(
            (int) $validated['innovator_id'],
            (int) $validated['innovation_id']
        );

        if ($request->hasFile('photo')) {
            if (!empty($item->photo)) {
                Storage::disk('public')->delete($item->photo);
            }
            $validated['photo'] = $request->file('photo')->store('innovator_of_month', 'public');
        } else {
            unset($validated['photo']);
        }

        $item->update($validated);

        return redirect()
            ->route('admin.innovators.index')
            ->with('success', 'Data innovator berhasil diperbarui.');
    }

    public function destroy(InnovatorOfTheMonth $item)
    {
        if (!empty($item->photo)) {
            Storage::disk('public')->delete($item->photo);
        }

        $item->delete();

        return redirect()
            ->route('admin.innovators.index')
            ->with('success', 'Data innovator berhasil dihapus.');
    }

    public function updateStatus(Request $request, InnovatorOfTheMonth $item)
    {
        $data = $request->validate([
            'is_active' => ['required', 'in:0,1'],
        ]);

        $item->update([
            'is_active' => (int) $data['is_active'],
        ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'is_active' => (int) $data['is_active'],
                'message' => 'Status berhasil diubah.',
            ]);
        }

        return redirect()
            ->route('admin.innovators.index')
            ->with('success', 'Status berhasil diubah.');
    }

    private function validatedPayload(Request $request, bool $isEdit = false): array
    {
        $rules = [
            'innovator_id' => ['required', 'exists:innovators,id'],
            'innovation_id' => ['required', 'exists:innovations,id'],
            'description' => ['required', 'string'],
            'is_active' => ['nullable', 'in:0,1'],
        ];

        $rules['photo'] = $isEdit
            ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
            : ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];

        $validated = $request->validate($rules);

        if (isset($validated['is_active'])) {
            $validated['is_active'] = (int) $validated['is_active'];
        }

        return $validated;
    }

    private function assertInnovationBelongsToInnovator(int $innovatorId, int $innovationId): void
    {
        $innovator = Innovator::with('innovations:id')->findOrFail($innovatorId);
        $allowed = $innovator->innovations->pluck('id')->map(fn ($v) => (int) $v)->toArray();

        abort_unless(in_array($innovationId, $allowed, true), 422);
    }
}