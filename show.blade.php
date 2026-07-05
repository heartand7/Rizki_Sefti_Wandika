<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengukuran: {{ $land->nama_lahan }} - LahanKu</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
        html, body { height: 100%; margin: 0; padding: 0; overflow: hidden; }
        .panel-scroll { max-height: calc(100vh - 56px); overflow-y: auto; }
        .metric-card { min-width: 0; overflow: hidden; }
        .metric-value { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100%; }
    </style>
</head>
<body class="bg-slate-900 text-slate-800 font-sans antialiased h-screen flex flex-col">

    <nav class="bg-white border-b border-slate-200 shadow-xs h-14 flex items-center justify-between px-6 shrink-0 z-50">
        <div class="flex items-center gap-3">
            <div class="bg-emerald-600 text-white p-2 rounded-lg shadow-xs flex items-center justify-center">
                <i class="fa-solid fa-map-location-dot text-sm"></i>
            </div>
            <span class="text-md font-bold tracking-tight text-slate-900">Lahan<span class="text-emerald-600 font-medium">Viewer</span></span>
        </div>
        <a href="{{ route('lands.index') }}" class="text-xs font-semibold px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </nav>

    <div class="flex flex-1 h-[calc(100vh-56px)] w-full overflow-hidden">
        
        <aside class="w-full md:w-[390px] lg:w-[430px] bg-white border-r border-slate-200 flex flex-col h-full shrink-0 z-40 shadow-xl">
            <div class="panel-scroll flex-1 p-5 space-y-6">
                
                <div>
                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider bg-emerald-50 px-2.5 py-1 rounded-md">Arsip Informasi Lahan ku</span>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight mt-2.5">{{ $land->nama_lahan }}</h1>
                    <p class="text-xs text-slate-400 mt-1">Dibuat pada: {{ $land->created_at->format('d M Y, H:i') }} WIB</p>
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-2 gap-4">
                    <div onclick="openMetricModal()" class="bg-white p-3.5 rounded-xl border border-slate-200 hover:border-emerald-400 shadow-2xs metric-card cursor-pointer transition-all hover:bg-emerald-50/30 group">
                        <span class="block text-[10px] font-bold text-slate-400 group-hover:text-emerald-600 uppercase tracking-wider">Luas Tanah <i class="fa-solid fa-expand text-[9px] ml-0.5 opacity-50"></i></span>
                        <div class="mt-1 flex items-baseline gap-1 overflow-hidden">
                            <span class="text-xl font-mono font-bold text-slate-900 metric-value">{{ number_format($land->luas_meter, 2, ',', '.') }}</span>
                            <span class="text-xs font-semibold text-slate-500 shrink-0">m²</span>
                        </div>
                    </div>
                    <div onclick="openMetricModal()" class="bg-white p-3.5 rounded-xl border border-slate-200 hover:border-emerald-400 shadow-2xs metric-card cursor-pointer transition-all hover:bg-emerald-50/30 group">
                        <span class="block text-[10px] font-bold text-slate-400 group-hover:text-emerald-600 uppercase tracking-wider">Keliling Bidang <i class="fa-solid fa-expand text-[9px] ml-0.5 opacity-50"></i></span>
                        <div class="mt-1 flex items-baseline gap-1 overflow-hidden">
                            <span class="text-xl font-mono font-bold text-slate-900 metric-value">{{ number_format($land->keliling_meter, 2, ',', '.') }}</span>
                            <span class="text-xs font-semibold text-slate-500 shrink-0">m</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Pemilik Hak</span>
                        <div class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-user-tie text-slate-400"></i> {{ $land->nama_pemilik }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi / Alamat</span>
                        <div class="text-xs font-medium text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <i class="fa-solid fa-map-pin text-rose-500 mr-1"></i> {{ $land->lokasi_alamat ?? 'Alamat belum diisi.' }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan Lapangan</span>
                        <div class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100 italic">
                            {{ $land->deskripsi ?? 'Tidak ada catatan tambahan.' }}
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex gap-2">
                    <a href="{{ route('lands.edit', $land->id) }}" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl text-center transition-colors shadow-sm flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square"></i> Ubah Batas Lahan
                    </a>
                </div>
            </div>
        </aside>

        <main class="flex-1 relative h-full bg-slate-100">
            <div id="map" class="w-full h-full"></div>
        </main>
    </div>

    <div id="metric-modal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-[10000] p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-all duration-200">
            <div class="p-4 bg-emerald-50 border-b border-emerald-100 flex items-center justify-between">
                <div class="flex items-center gap-2 text-emerald-950 font-bold text-sm">
                    <i class="fa-solid fa-chart-pie text-emerald-600"></i> Rincian Hasil Kalkulasi Lahan ku
                </div>
                <button onclick="closeMetricModal()" class="text-slate-400 hover:text-slate-600 text-xs p-1 cursor-pointer"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Luas Lahan Standar</span>
                    <div class="text-base font-mono font-bold text-slate-900 mt-0.5">{{ number_format($land->luas_meter, 2, ',', '.') }} <span class="text-xs font-sans font-medium text-slate-500">m²</span></div>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Konversi Hektar</span>
                    <div class="text-base font-mono font-bold text-emerald-600 mt-0.5">{{ number_format(($land->luas_meter / 10000), 4, ',', '.') }} <span class="text-xs font-sans font-medium text-slate-500">Ha</span></div>
                </div>
                <hr class="border-slate-100">
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keliling Bidang Standar</span>
                    <div class="text-base font-mono font-bold text-slate-900 mt-0.5">{{ number_format($land->keliling_meter, 2, ',', '.') }} <span class="text-xs font-sans font-medium text-slate-500">m</span></div>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Konversi Kilometer</span>
                    <div class="text-base font-mono font-bold text-blue-600 mt-0.5">{{ number_format(($land->keliling_meter / 1000), 4, ',', '.') }} <span class="text-xs font-sans font-medium text-slate-500">Km</span></div>
                </div>
            </div>
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button onclick="closeMetricModal()" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-xs">Selesai</button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        window.addEventListener('load', function() {
            window.openMetricModal = function() { document.getElementById('metric-modal').classList.remove('hidden'); }
            window.closeMetricModal = function() { document.getElementById('metric-modal').classList.add('hidden'); }

            const originalKoordinatRaw = {!! is_string($land->koordinat_polygon) ? $land->koordinat_polygon : json_encode($land->koordinat_polygon) !!};
            let rawData = typeof originalKoordinatRaw === 'string' ? JSON.parse(originalKoordinatRaw) : originalKoordinatRaw;

            function parseToLeaflet(data) { return data.map(pt => [parseFloat(pt[1]), parseFloat(pt[0])]); }
            let koordinatLeaflet = parseToLeaflet(rawData);

            const map = L.map('map', { zoomControl: false, center: koordinatLeaflet[0], zoom: 16 });
            L.control.zoom({ position: 'topright' }).addTo(map);
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}').addTo(map);

            let polygonLahan = L.polygon(koordinatLeaflet, { color: '#10b981', weight: 3.5, fillColor: '#10b981', fillOpacity: 0.25 }).addTo(map);
            map.fitBounds(polygonLahan.getBounds(), { padding: [50, 50] });
        });
    </script>
</body>
</html>