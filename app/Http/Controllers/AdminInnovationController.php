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
        $innovations = Innovation::where('source', 'admin')->latest()->get();
        return view('admin.innovations.index', compact('innovations'));
    }

    public function create()
    {
        return view('admin.innovations.form', [
            'mode' => 'create',
            'innovation' => new Innovation(),
            'faculties' => Faculty::orderBy('name')->get(),
            'firstInnovator' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateInnovation($request);

        $request->validate([
            'innovator_name' => 'required|string|max:255',
            'faculty_id'     => 'required|exists:faculties,id',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data['source'] = 'admin';

        $innovator = Innovator::firstOrCreate(
            ['name' => $request->innovator_name],
            ['faculty_id' => $request->faculty_id]
        );

        if ($innovator->faculty_id != $request->faculty_id) {
            $innovator->update(['faculty_id' => $request->faculty_id]);
        }

        if ($request->hasFile('photo')) {
            if ($innovator->photo) {
                Storage::disk('public')->delete($innovator->photo);
            }
            $path = $request->file('photo')->store('innovators', 'public');
            $innovator->update(['photo' => $path]);
        }

        $innovation = Innovation::create($data);
        $innovation->innovators()->sync([$innovator->id]);

        return redirect()
            ->route('admin.innovations.index')
            ->with('success', 'Inovasi berhasil ditambahkan.');
    }

    public function edit(Innovation $innovation)
    {
        abort_if($innovation->source !== 'admin', 404);

        $innovation->load('innovators.faculty');
        $firstInnovator = $innovation->innovators->first();

        return view('admin.innovations.form', [
            'mode' => 'edit',
            'innovation' => $innovation,
            'faculties' => Faculty::orderBy('name')->get(),
            'firstInnovator' => $firstInnovator,
        ]);
    }

    public function update(Request $request, Innovation $innovation)
    {
        abort_if($innovation->source !== 'admin', 404);

        $data = $this->validateInnovation($request);

        $request->validate([
            'innovator_name' => 'required|string|max:255',
            'faculty_id'     => 'required|exists:faculties,id',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data['source'] = 'admin';
        $innovation->update($data);

        $innovator = $innovation->innovators()->first();

        if (!$innovator) {
            $innovator = Innovator::create([
                'name' => $request->innovator_name,
                'faculty_id' => $request->faculty_id,
            ]);
            $innovation->innovators()->sync([$innovator->id]);
        } else {
            $innovator->update([
                'name' => $request->innovator_name,
                'faculty_id' => $request->faculty_id,
            ]);
        }

        if ($request->hasFile('photo')) {
            if ($innovator->photo) {
                Storage::disk('public')->delete($innovator->photo);
            }
            $path = $request->file('photo')->store('innovators', 'public');
            $innovator->update(['photo' => $path]);
        }

        return redirect()
            ->route('admin.innovations.index')
            ->with('success', 'Inovasi berhasil diperbarui.');
    }

    public function show(Innovation $innovation)
    {
        abort_if($innovation->source !== 'admin', 404);

        $innovation->load('innovators.faculty');
        $firstInnovator = $innovation->innovators->first();

        return view('admin.innovations.show', compact('innovation', 'firstInnovator'));
    }

    private function validateInnovation(Request $request): array
    {
        return $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'partner'     => 'nullable|string|max:255',
            'hki_status'  => 'nullable|string|max:255',
            'video_url'   => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'review'      => 'nullable|string',
            'advantages'  => 'nullable|string',
            'impact'      => 'nullable|string|max:255',
            'is_impact'   => 'nullable|boolean',
            'status'      => 'nullable|string|max:255',
        ]);
    }
}
