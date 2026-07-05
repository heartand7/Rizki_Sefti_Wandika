<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengukuran Lahan - LahanKu</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-slate-200 shadow-xs sticky top-0 z-50 shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center gap-3">
                <div class="bg-emerald-600 text-white p-2.5 rounded-xl shadow-md shadow-emerald-200 flex items-center justify-center transition-transform hover:scale-105">
                    <i class="fa-solid fa-map-location-dot text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-md font-black tracking-tight text-slate-900 leading-none">Lahan<span class="text-emerald-600">Ku</span></span>
                    <span class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase mt-0.5">Sistem Informasi Spasial</span>
                </div>
            </div>

            <a href="{{ route('lands.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm shadow-emerald-200 hover:shadow-md cursor-pointer uppercase tracking-wider">
                <i class="fa-solid fa-square-plus text-sm"></i> Tambah Ukuran
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-1">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Riwayat Arsip Pengukuran</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola, tinjau, dan dokumentasikan seluruh batas koordinat bidang tanah Anda di aplikasi Lahan ku.</p>
            </div>
            
            <div class="flex items-center gap-3 bg-white border border-slate-200/80 p-3 rounded-xl shadow-2xs">
                <div class="bg-slate-100 text-slate-600 p-2 rounded-lg text-xs"><i class="fa-solid fa-chart-simple"></i></div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Data Tersimpan</span>
                    <span class="text-sm font-bold text-slate-900">{{ $lands->count() }} Bidang Tanah</span>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3 text-emerald-950 text-xs shadow-2xs">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base mt-0.5"></i>
            <div>
                <span class="font-bold block">Tindakan Berhasil</span>
                <span class="text-slate-600 mt-0.5 block">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-5 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Identitas Lahan & Pemilik</th>
                            <th class="py-3.5 px-4">Lokasi / Alamat</th>
                            <th class="py-3.5 px-4 text-center">Luas Wilayah</th>
                            <th class="py-3.5 px-4 text-center">Keliling Bidang</th>
                            <th class="py-3.5 px-4 text-center w-36">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($lands as $index => $land)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-5 text-center font-mono font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900">{{ $land->nama_lahan }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                    <i class="fa-solid fa-user text-[10px]"></i> Pemilik: {{ $land->nama_pemilik }}
                                </div>
                            </td>
                            <td class="py-4 px-4 max-w-[240px]">
                                <div class="text-slate-600 truncate" title="{{ $land->lokasi_alamat }}">
                                    <i class="fa-solid fa-map-pin text-rose-500 mr-1"></i> {{ $land->lokasi_alamat ?? 'Tidak ditentukan' }}
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center font-mono font-bold text-slate-900">
                                {{ number_format($land->luas_meter, 2, ',', '.') }} <span class="font-sans font-medium text-slate-400 text-[11px]">m²</span>
                            </td>
                            <td class="py-4 px-4 text-center font-mono font-bold text-slate-900">
                                {{ number_format($land->keliling_meter, 2, ',', '.') }} <span class="font-sans font-medium text-slate-400 text-[11px]">m</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('lands.show', $land->id) }}" class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" title="Lihat Peta Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lands.edit', $land->id) }}" class="p-2 text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors" title="Ubah Batas Lahan">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('lands.destroy', $land->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengukuran lahan ini secara permanen dari aplikasi Lahan ku?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer" title="Hapus Data">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 px-4 text-center">
                                <div class="max-w-xs mx-auto flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-inner">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900">Belum Ada Riwayat Pengukuran</h3>
                                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Anda belum mencatat atau memetakan bidang tanah apapun di Lahan ku. Mulai ukur lahan pertama Anda sekarang!</p>
                                    <a href="{{ route('lands.create') }}" class="mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-lg transition-colors shadow-xs uppercase tracking-wide">
                                        Ukur Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="mt-auto py-6 border-t border-slate-200 bg-white text-center text-[11px] font-medium text-slate-400 shrink-0">
        &copy; 2026 <span class="text-slate-600 font-bold">LahanKu</span> Engine Spasial. All Rights Reserved.
    </footer>

</body>
</html>