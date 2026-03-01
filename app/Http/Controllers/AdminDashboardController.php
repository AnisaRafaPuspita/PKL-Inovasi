<?php

namespace App\Http\Controllers;

use App\Models\InnovatorOfTheMonth;
use App\Models\Innovation;
use App\Models\Innovator;
use App\Models\InnovationViewStat;
use App\Models\HomePamflet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $days = 14;

        $from = now()->subDays($days - 1)->toDateString();
        $to = now()->toDateString();

        $rows = InnovationViewStat::whereBetween('date', [$from, $to])
            ->orderBy('date')
            ->get(['date', 'views'])
            ->keyBy(function ($r) {
                return Carbon::parse($r->date)->toDateString();
            });

        $chartLabels = [];
        $chartData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $chartLabels[] = Carbon::parse($d)->format('M j');
            $chartData[] = (int) (($rows[$d]->views ?? 0));
        }

        $stats = [
            'total_innovations' => Innovation::count(),
            'total_innovators'  => Innovator::count(),
            'total_visited'     => (int) InnovationViewStat::sum('views'),
        ];

        $now = Carbon::now();

        $innovatorOfMonth = InnovatorOfTheMonth::with(['innovation', 'innovator.faculty'])
            ->where('is_active', 1)
            ->latest()
            ->first();

        $homePamflet = HomePamflet::first() ?? HomePamflet::create();

        return view('admin.dashboard', compact(
            'stats',
            'chartLabels',
            'chartData',
            'innovatorOfMonth',
            'homePamflet'
        ));
    }

    public function updateHomePamflet(Request $request)
    {
        $homePamflet = HomePamflet::first() ?? HomePamflet::create();

        if ($request->filled('delete_slot')) {
            $slot = (int) $request->input('delete_slot');

            if (!in_array($slot, [1, 2, 3], true)) {
                return back()->with('error', 'Slot pamflet tidak valid.');
            }

            $key = "pamflet_{$slot}";
            $oldPath = $homePamflet->$key;

            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $homePamflet->update([$key => null]);

            return back()->with('success', "Pamflet {$slot} berhasil dihapus.");
        }

        $request->validate([
            'pamflet_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'pamflet_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'pamflet_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        foreach (['pamflet_1', 'pamflet_2', 'pamflet_3'] as $key) {
            if ($request->hasFile($key)) {
                if (!empty($homePamflet->{$key}) && Storage::disk('public')->exists($homePamflet->{$key})) {
                    Storage::disk('public')->delete($homePamflet->{$key});
                }

                $path = $request->file($key)->store('home-pamflet', 'public');
                $homePamflet->{$key} = $path;
            }
        }

        $homePamflet->save();

        return back()->with('success', 'Pamflet berhasil diupdate.');
    }


    public function deletePamflet(int $slot)
    {
        abort_unless(in_array($slot, [1,2,3], true), 404);

        $homePamflet = HomePamflet::first();
        if (!$homePamflet) {
            return back()->with('success', 'Pamflet sudah kosong.');
        }

        $key = "pamflet_{$slot}";

        if (!empty($homePamflet->{$key})) {
            Storage::disk('public')->delete($homePamflet->{$key});
        }

        $homePamflet->update([$key => null]);

        return back()->with('success', "Pamflet {$slot} berhasil dihapus.");
    }
}
