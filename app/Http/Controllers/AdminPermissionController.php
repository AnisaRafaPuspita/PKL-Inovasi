<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\InnovationPermission;

class AdminPermissionController extends Controller
{
    public function index()
    {
        $innovations = Innovation::with(['innovators.faculty', 'permission'])
            ->where('source', 'innovator')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.permissions.index', compact('innovations'));
    }

    public function show(Innovation $innovation)
    {
        abort_if($innovation->source !== 'innovator', 404);

        $innovation->load(['innovators.faculty', 'permission', 'images', 'primaryImage']);
        return view('admin.permissions.show', compact('innovation'));
    }

    public function accept(Innovation $innovation)
    {
        abort_if($innovation->source !== 'innovator', 404);

        InnovationPermission::updateOrCreate(
            ['innovation_id' => $innovation->id],
            ['status' => 'accepted', 'reviewed_at' => now()]
        );

        $innovation->update(['status' => 'published']);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Inovasi berhasil di-accept dan dipublish.');
    }

    public function decline(Innovation $innovation)
    {
        abort_if($innovation->source !== 'innovator', 404);

        InnovationPermission::updateOrCreate(
            ['innovation_id' => $innovation->id],
            ['status' => 'declined', 'reviewed_at' => now()]
        );

        $innovation->update([
            'status' => 'draft',
            'review' => 'Ditolak admin'
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Inovasi berhasil di-decline dan dikembalikan ke draft.');
    }
}
