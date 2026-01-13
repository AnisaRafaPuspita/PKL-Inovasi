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
            ->orderByDesc('created_at')
            ->get();

        return view('admin.permissions.index', compact('innovations'));
    }


    public function show(Innovation $innovation)
    {
        abort_if($innovation->source !== 'innovator', 404);

        $innovation->load(['innovators.faculty', 'permission']);
        return view('admin.permissions.show', compact('innovation'));
    }

    public function accept(Innovation $innovation)
    {
        InnovationPermission::updateOrCreate(
            ['innovation_id' => $innovation->id],
            ['status' => 'accepted', 'reviewed_at' => now()]
        );

        $innovation->forceFill(['source' => 'admin'])->save();

        return redirect()
            ->route('admin.innovations.index')
            ->with('success', 'Inovasi di-accept dan dipindahkan ke Manage Innovations.');
    }

    public function decline(Innovation $innovation)
    {
        abort_if($innovation->source !== 'innovator', 404);

        InnovationPermission::updateOrCreate(
            ['innovation_id' => $innovation->id],
            ['status' => 'declined', 'reviewed_at' => now()]
        );

        return redirect()
            ->route('admin.permissions.show', $innovation->id)
            ->with('success', 'Inovasi berhasil di-decline.');
    }
}
