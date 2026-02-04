<?php

namespace App\Http\Controllers;

use App\Models\InnovatorOfTheMonth;
use App\Models\Innovator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminInnovatorOfTheMonthController extends Controller
{
    public function index()
    {
        $items = InnovatorOfTheMonth::with(['innovator.faculty', 'innovation'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.innovator_of_the_month.index', compact('items'));
    }

    public function create()
    {
        $innovators = Innovator::with(['faculty', 'innovations'])
            ->orderBy('name')
            ->get();

        return view('admin.innovator_of_the_month.form', [
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

        [$month, $year] = $this->nextAvailableMonthYear();
        $validated['month'] = $month;
        $validated['year']  = $year;

        InnovatorOfTheMonth::create($validated);

        return redirect()
            ->route('admin.innovator_of_month.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(InnovatorOfTheMonth $iotm)
    {
        $innovators = Innovator::with(['faculty', 'innovations'])
            ->orderBy('name')
            ->get();

        return view('admin.innovator_of_the_month.form', [
            'mode' => 'edit',
            'iotm' => $iotm,
            'innovators' => $innovators,
        ]);
    }

    public function update(Request $request, InnovatorOfTheMonth $iotm)
    {
        $validated = $this->validatedPayload($request, isEdit: true);

        $this->assertInnovationBelongsToInnovator(
            (int) $validated['innovator_id'],
            (int) $validated['innovation_id']
        );

        if ($request->hasFile('photo')) {
            if (!empty($iotm->photo)) {
                Storage::disk('public')->delete($iotm->photo);
            }
            $validated['photo'] = $request->file('photo')->store('innovator_of_month', 'public');
        } else {
            unset($validated['photo']);
        }

        unset($validated['month'], $validated['year']);

        $iotm->update($validated);

        return redirect()
            ->route('admin.innovator_of_month.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(InnovatorOfTheMonth $iotm)
    {
        if (!empty($iotm->photo)) {
            Storage::disk('public')->delete($iotm->photo);
        }

        $iotm->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    private function validatedPayload(Request $request, bool $isEdit = false): array
    {
        return $request->validate([
            'innovator_id' => ['required', 'exists:innovators,id'],
            'innovation_id'=> ['required', 'exists:innovations,id'],
            'description'  => ['required', 'string'],
            'photo'        => [$isEdit ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function assertInnovationBelongsToInnovator(int $innovatorId, int $innovationId): void
    {
        $innovator = Innovator::with('innovations:id')->findOrFail($innovatorId);
        $allowed = $innovator->innovations->pluck('id')->map(fn ($v) => (int) $v)->toArray();

        if (!in_array($innovationId, $allowed, true)) {
            abort(422, 'Inovasi unggulan harus berasal dari innovator yang dipilih.');
        }
    }

    private function nextAvailableMonthYear(): array
    {
        $month = (int) now()->month;
        $year  = (int) now()->year;

        for ($i = 0; $i < 240; $i++) { 
            $exists = InnovatorOfTheMonth::where('month', $month)->where('year', $year)->exists();
            if (!$exists) return [$month, $year];

            $month++;
            if ($month > 12) {
                $month = 1;
                $year++;
            }
        }

        return [(int) now()->month, (int) now()->year + 100];
    }
}
