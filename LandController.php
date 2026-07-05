<?php

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;

class LandController extends Controller
{
    public function index()
    {
        $lands = Land::orderBy('created_at', 'desc')->get();
        return view('lands.index', compact('lands'));
    }

    public function create()
    {
        return view('lands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik'       => 'required|string|max:255',
            'nama_lahan'         => 'required|string|max:255',
            'luas_meter'         => 'required|numeric',
            'keliling_meter'     => 'required|numeric',
            'koordinat_polygon'  => 'required|string', 
        ]);

        Land::create([
            'nama_pemilik'      => $request->nama_pemilik,
            'nama_lahan'        => $request->nama_lahan,
            'lokasi_alamat'     => $request->lokasi_alamat,
            'deskripsi'         => $request->deskripsi,
            'luas_meter'        => $request->luas_meter,
            'keliling_meter'    => $request->keliling_meter,
            'koordinat_polygon' => json_decode($request->koordinat_polygon, true),
        ]);

        return redirect()->route('lands.index')->with('success', 'Data pengukuran lahan berhasil disimpan!');
    }

    public function show($id)
    {
        $land = Land::findOrFail($id);
        return view('lands.show', compact('land'));
    }

    public function edit($id)
    {
        $land = Land::findOrFail($id);
        return view('lands.edit', compact('land'));
    }

    public function update(Request $request, $id)
    {
        $land = Land::findOrFail($id);

        $request->validate([
            'nama_pemilik'       => 'required|string|max:255',
            'nama_lahan'         => 'required|string|max:255',
            'luas_meter'         => 'required|numeric',
            'keliling_meter'     => 'required|numeric',
            'koordinat_polygon'  => 'required|string',
        ]);

        $land->update([
            'nama_pemilik'      => $request->nama_pemilik,
            'nama_lahan'        => $request->nama_lahan,
            'lokasi_alamat'     => $request->lokasi_alamat,
            'deskripsi'         => $request->deskripsi,
            'luas_meter'        => $request->luas_meter,
            'keliling_meter'    => $request->keliling_meter,
            'koordinat_polygon' => json_decode($request->koordinat_polygon, true),
        ]);

        return redirect()->route('lands.index')->with('success', 'Data pengukuran lahan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $land = Land::findOrFail($id);
        $land->delete();

        return redirect()->route('lands.index')->with('success', 'Data pengukuran lahan berhasil dihapus.');
    }

    // FITUR BARU: Menghapus seluruh riwayat sekaligus
    public function destroyAll()
    {
        Land::truncate();
        return redirect()->route('lands.index')->with('success', 'Seluruh data riwayat pengukuran lahan berhasil dikosongkan!');
    }
}