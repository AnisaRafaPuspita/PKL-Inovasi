<?php

namespace App\Http\Controllers;

use App\Mail\DeclinedMail;
use App\Models\Innovation;
use App\Models\InnovationPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

   
    public function decline(Request $request, Innovation $innovation)
    {
        abort_if($innovation->source !== 'innovator', 404);

        $request->validate([
            'reason' => ['nullable', 'string'],
        ]);

        $reason = trim((string) $request->input('reason', ''));
        if ($reason === '') {
            $reason = 'Inovasi belum memenuhi kriteria yang ditentukan. Silakan lakukan perbaikan dan ajukan kembali.';
        }

        InnovationPermission::updateOrCreate(
            ['innovation_id' => $innovation->id],
            ['status' => 'declined', 'reviewed_at' => now()]
        );

        $innovation->update([
            'status' => 'draft',
            'review' => $reason,
        ]);

        $innovation->loadMissing('innovators');

        $to = trim((string) ($innovation->leader_email ?? ''));
        if ($to !== '') {
            Mail::to($to)->send(new DeclinedMail($innovation, $reason));
        }

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Inovasi ditolak dan email sudah dikirim.');
    }
}