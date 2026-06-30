<?php

namespace App\Http\Controllers;

use App\Models\HomePamflet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPamfletController extends Controller
{
    public function index()
    {
        $homePamflet = HomePamflet::first() ?? HomePamflet::create();

        return view('admin.pamflet.index', compact('homePamflet'));
    }

    public function update(Request $request)
    {
        $homePamflet = HomePamflet::first() ?? HomePamflet::create();

        $request->validate([
            'pamflet_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'pamflet_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'pamflet_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        foreach (['pamflet_1', 'pamflet_2', 'pamflet_3'] as $key) {
            if ($request->hasFile($key)) {
                if (!empty($homePamflet->{$key}) && Storage::disk('s3')->exists($homePamflet->{$key})) {
                    Storage::disk('s3')->delete($homePamflet->{$key});
                }

                $path = $request->file($key)->store('home-pamflet', 's3');
                $homePamflet->{$key} = $path;
            }
        }

        $homePamflet->save();

        return back()->with('success', 'Pamflet berhasil diupdate.');
    }

    public function destroy(int $slot)
    {
        abort_unless(in_array($slot, [1,2,3], true), 404);

        $homePamflet = HomePamflet::first();
        if (!$homePamflet) {
            return back()->with('success', 'Pamflet sudah kosong.');
        }

        $key = "pamflet_{$slot}";

        if (!empty($homePamflet->{$key})) {
            Storage::disk('s3')->delete($homePamflet->{$key});
        }

        $homePamflet->update([$key => null]);

        return back()->with('success', "Pamflet {$slot} berhasil dihapus.");
    }
}