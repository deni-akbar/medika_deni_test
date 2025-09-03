<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    public function next(Request $request)
    {
        $current = Antrian::find($request->id);

        if ($current && $current->status === 'proses') {
            $current->update(['status' => 'selesai', 'admin_id' => Auth::id()]);

            $next = Antrian::where('status', 'menunggu')
                ->where('id', '>', $current->id)
                ->orderBy('id', 'asc')
                ->first();

            if ($next) {
                $next->update(['status' => 'proses', 'admin_id' => Auth::id()]);
            }
        } else {
            // Kalau belum ada yg proses, ambil antrian pertama menunggu
            $next = Antrian::where('status', 'menunggu')
                ->orderBy('id', 'asc')
                ->first();

            if ($next) {
                $next->update(['status' => 'proses', 'admin_id' => Auth::id()]);
            }
        }

        return redirect()->route('admin.dashboard');
    }

    public function previous(Request $request)
    {
        $current = Antrian::find($request->id);

        if ($current) {
            if ($current->status === 'proses') {
                $current->update(['status' => 'menunggu', 'admin_id' => Auth::id()]);

                // Cari antrian sebelumnya (yang selesai) untuk dikembalikan jadi proses
                $prev = Antrian::where('status', 'selesai')
                    ->where('id', '<', $current->id)
                    ->orderBy('id', 'desc')
                    ->first();
                if ($prev) {
                    $prev->update(['status' => 'proses', 'admin_id' => Auth::id()]);
                }
            } else if ($current->status === 'selesai') {
                $current->update(['status' => 'proses', 'admin_id' => Auth::id()]);
            } else {
                // Kalau tidak ada yg selesai, ambil antrian pertama menunggu
                $prev = Antrian::where('status', 'menunggu')
                    ->orderBy('id', 'asc')
                    ->first();

                if ($prev) {
                    $prev->update(['status' => 'proses', 'admin_id' => Auth::id()]);
                }
            }
        }

        return redirect()->route('admin.dashboard');
    }


    public function index()
    {
        $antrians = Antrian::orderBy('id', 'desc')->get();
        $getWaiting = Antrian::where('status', 'menunggu')->get();
        $getNoTerkini = Antrian::where('status', 'proses')->first();
        $is_hv_waiting = (count($getWaiting) != 0) ? true : false;

        if ($getNoTerkini) {
            $nomor_terkini = $getNoTerkini;
            $is_hv_process = true;
        } else {
            $getNoTerkini = Antrian::orderBy('id', 'desc')->first();
            $nomor_terkini = $getNoTerkini;
            $is_hv_process = false;
        }
        $allDone = (!$is_hv_process && !$is_hv_waiting);
        // dd($getNoTerkini);
        return view('admin.dashboard', compact('antrians', 'nomor_terkini', 'is_hv_process', 'is_hv_waiting', 'allDone'));
    }

    public function indexUser()
    {
        $antrians = Antrian::orderBy('id', 'desc')->get();
        $getNoTerkini = Antrian::where('status', 'proses')->orderBy('id', 'asc')->first();
        if ($getNoTerkini) {
            $nomor_terkini = $getNoTerkini;
            $is_hv_process = true;
        } else {
            $getNoTerkini = Antrian::orderBy('id', 'asc')->first();
            $nomor_terkini = $getNoTerkini;
            $is_hv_process = false;
        }
        return view('user', compact('antrians', 'nomor_terkini', 'is_hv_process'));
    }

    public function json()
    {
        $nomor_terkini = Antrian::where('status', 'proses')->orderBy('id', 'asc')->first();
        return response()->json([
            'no_antrian' => $nomor_terkini ? $nomor_terkini->no_antrian : '-'
        ]);
    }

    // Menambah data antrian baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        // Generate nomor antrian otomatis
        $last = Antrian::orderBy('id', 'desc')->first();
        if ($last && preg_match('/A(\d{3})/', $last->no_antrian, $match)) {
            $nextNum = intval($match[1]) + 1;
        } else {
            $nextNum = 1;
        }
        $no_antrian = 'A' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        Antrian::create([
            'nama' => $request->nama,
            'no_antrian' => $no_antrian,
            'status' => 'menunggu',
            'admin_id' => Auth::id(),
        ]);
        return redirect()->back()->with('success', 'Data antrian berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->delete();
        return redirect()->back()->with('success', 'Data antrian berhasil dihapus');
    }
}
