<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\InnovationPermission;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    public function index()
    {
        $innovations = Innovation::with(['innovators.faculty', 'permission'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.permissions.index', compact('innovations'));
    }

    public function show(Innovation $innovation)
    {
        $innovation->load(['innovators.faculty', 'permission']);
        return view('admin.permissions.show', compact('innovation'));
    }

    public function accept(Innovation $innovation)
    {
        InnovationPermission::updateOrCreate(
            ['innovation_id' => $innovation->id],
            ['status' => 'accepted', 'reviewed_at' => now()]
        );

        return redirect()
            ->route('admin.permissions.show', $innovation->id)
            ->with('success', 'Inovasi berhasil di-accept.');
    }

    public function decline(Innovation $innovation)
    {
        InnovationPermission::updateOrCreate(
            ['innovation_id' => $innovation->id],
            ['status' => 'declined', 'reviewed_at' => now()]
        );

        return redirect()
            ->route('admin.permissions.show', $innovation->id)
            ->with('success', 'Inovasi berhasil di-decline.');
    }
}
