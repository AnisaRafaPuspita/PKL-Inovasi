<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
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
        // sementara placeholder, nanti inovator login + form submit (pending)
        return 'Upload Produk page (nanti inovator login + form)';
    }
}

