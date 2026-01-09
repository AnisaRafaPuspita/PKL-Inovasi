<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\Innovator;
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

        return view('pages.innovations.create', compact('innovators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'innovator_id' => ['required', 'exists:innovators,id'],

            'category'     => ['nullable', 'string', 'max:255'],
            'partners'     => ['nullable', 'string', 'max:255'],
            'ip_status'    => ['nullable', 'string', 'max:255'],
            'video_url'    => ['nullable', 'url', 'max:255'],

            'description'  => ['nullable', 'string'],
            'advantages'   => ['nullable', 'string'],
            'impact'       => ['nullable', 'string'],

            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // upload gambar (kalau ada)
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('innovations', 'public');
            $imageUrl = 'storage/' . $path; // biar bisa dipakai asset()
        }

        $innovation = Innovation::create([
            'title'       => $validated['title'],
            'category'    => $validated['category'] ?? null,
            'partners'    => $validated['partners'] ?? null,
            'ip_status'   => $validated['ip_status'] ?? null,
            'video_url'   => $validated['video_url'] ?? null,

            'description' => $validated['description'] ?? null,
            'advantages'  => $validated['advantages'] ?? null,
            'impact'      => $validated['impact'] ?? null,

            'image_url'   => $imageUrl,
            'status'      => 'published', // kalau kamu mau admin review dulu, ganti jadi 'draft'/'pending'
            'views_count' => 0,
        ]);

        // nyambungin inovasi ke innovator (pivot innovation_innovator)
        $innovation->innovators()->syncWithoutDetaching([$validated['innovator_id']]);

        return redirect()
            ->route('innovations.show', $innovation->id)
            ->with('success', 'Produk/Inovasi berhasil diupload.');
    }
}

