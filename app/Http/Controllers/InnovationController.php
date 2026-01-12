<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\Innovator;
use App\Models\InnovationImage;
use App\Models\Faculty;
use Illuminate\Http\Request;

class InnovationController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $innovations = Innovation::query()
            ->where('status', 'published')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(9); 
            
        $innovations->appends(request()->query());

        // kamu bisa bikin view listing sendiri, atau sementara reuse page home.
        // saran: bikin resources/views/pages/innovations/index.blade.php
        return view('pages.innovations.index', compact('innovations', 'q'));
    }

    public function show(Innovation $innovation)
    {
        $innovation->load('innovators');

        // increment views (optional, kalau sudah siap)
        $innovation->increment('views_count');

        return view('pages.innovations.show', compact('innovation'));
    }

    public function create()
    {
        $innovators = Innovator::with('faculty')->orderBy('name')->get();
        $faculties = Faculty::orderBy('name')->get();

        return view('pages.innovations.create', compact('innovators', 'faculties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'innovator_id' => ['nullable', 'exists:innovators,id'],
            'new_innovator_name' => ['nullable', 'string', 'max:255'],
            'faculty_id' => ['nullable', 'exists:faculties,id'],

            'category'     => ['nullable', 'string', 'max:255'],
            'partner'     => ['nullable', 'string', 'max:255'],
            'hki_status'    => ['nullable', 'string', 'max:255'],
            'video_url'    => ['nullable', 'url', 'max:255'],

            'description'  => ['nullable', 'string'],
            'advantages'   => ['nullable', 'string'],
            'impact'       => ['nullable', 'string'],

            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        /** 1️⃣ Tentukan innovator */
        if ($request->filled('new_innovator_name')) {

            $request->validate([
                'faculty_id' => ['required', 'exists:faculties,id'],
            ]);

            $innovator = Innovator::create([
                'name' => $request->new_innovator_name,
                'faculty_id' => $request->faculty_id,
                'status' => 'pending',
            ]);
            $innovatorId = $innovator->id;
        } else {
            $innovatorId = $validated['innovator_id'];
        }

        /** 2️⃣ Upload 1 foto (kalau ada) */
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('innovations', 'public');
            $imageUrl = $path;

        }

        /** 3️⃣ Create innovation (HANYA SEKALI) */
        $innovation = Innovation::create([
            'title'       => $validated['title'],
            'category'    => $validated['category'] ?? null,
            'partner'    => $validated['partner'] ?? null,
            'hki_status'   => $validated['hki_status'] ?? null,
            'is_impact' => false,
            'video_url'   => $validated['video_url'] ?? null,

            'description' => $validated['description'] ?? null,
            'advantages'  => $validated['advantages'] ?? null,
            'impact'      => $validated['impact'] ?? null,

            'image_url'   => $imageUrl,
            'status'      => 'published', // nanti bisa 'pending'
            'views_count' => 0,
        ]);

        /** 4️⃣ Hubungkan ke innovator */
        if ($innovatorId) {
            $innovation->innovators()->syncWithoutDetaching([$innovatorId]);
        }

        return redirect()
            ->route('innovations.show', $innovation->id)
            ->with('success', 'Produk/Inovasi berhasil diupload.');
    }

}

