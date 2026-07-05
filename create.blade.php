<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengukuran Lahan Baru - LahanKu</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
        html, body { height: 100%; margin: 0; padding: 0; overflow: hidden; }
        .floating-search-container { 
            z-index: 9999 !important; 
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
        }
        @media (min-width: 768px) {
            .floating-search-container { width: 460px; }
        }
        .search-results-box { max-height: 200px; overflow-y: auto; }
        .panel-scroll { max-height: calc(100vh - 170px); overflow-y: auto; }
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
            <span class="text-md font-bold tracking-tight text-slate-900">Lahan<span class="text-emerald-600 font-medium">Ku</span></span>
        </div>
        <a href="{{ route('lands.index') }}" class="text-xs font-semibold px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </nav>

    <div class="flex flex-1 h-[calc(100vh-56px)] w-full overflow-hidden">
        
        <aside class="w-full md:w-[390px] lg:w-[430px] bg-white border-r border-slate-200 flex flex-col h-full shrink-0 z-40 shadow-xl">
            
            <div class="p-4 bg-slate-50 border-b border-slate-200 grid grid-cols-2 gap-3 shrink-0">
                <div onclick="openMetricModal()" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-emerald-400 shadow-2xs metric-card cursor-pointer transition-all hover:bg-emerald-50/30 group">
                    <span class="block text-[10px] font-bold text-slate-400 group-hover:text-emerald-600 uppercase tracking-wider">Luas Wilayah <i class="fa-solid fa-expand text-[9px] ml-0.5 opacity-50"></i></span>
                    <div class="mt-0.5 flex items-baseline gap-1 overflow-hidden">
                        <span id="display-luas" class="text-lg md:text-xl font-mono font-bold text-slate-900 tracking-tight metric-value">0,00</span>
                        <span class="text-xs font-semibold text-slate-500 shrink-0">m²</span>
                    </div>
                </div>
                <div onclick="openMetricModal()" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-emerald-400 shadow-2xs metric-card cursor-pointer transition-all hover:bg-emerald-50/30 group">
                    <span class="block text-[10px] font-bold text-slate-400 group-hover:text-emerald-600 uppercase tracking-wider">Keliling Lahan <i class="fa-solid fa-expand text-[9px] ml-0.5 opacity-50"></i></span>
                    <div class="mt-0.5 flex items-baseline gap-1 overflow-hidden">
                        <span id="display-keliling" class="text-lg md:text-xl font-mono font-bold text-slate-900 tracking-tight metric-value">0,00</span>
                        <span class="text-xs font-semibold text-slate-500 shrink-0">m</span>
                    </div>
                </div>
            </div>

            <div class="panel-scroll flex-1 p-4 space-y-4">
                <form action="{{ route('lands.store') }}" method="POST" id="land-form">
                    @csrf
                    
                    <input type="hidden" name="luas_meter" id="input-luas" value="0">
                    <input type="hidden" name="keliling_meter" id="input-keliling" value="0">
                    <input type="hidden" name="koordinat_polygon" id="input-koordinat">

                    <div class="space-y-3.5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Nama Pemilik Properti</label>
                            <input type="text" name="nama_pemilik" required class="mt-1 block w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Nama / Label Identitas Lahan</label>
                            <input type="text" name="nama_lahan" required class="mt-1 block w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none" placeholder="Misal: Kebun Sawit Utama">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Lokasi / Alamat Lahan</label>
                            <div class="relative mt-1">
                                <input type="text" name="lokasi_alamat" id="input-alamat" required class="block w-full pl-3 pr-10 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none" placeholder="Alamat otomatis terisi setelah menggambar lahan...">
                                <div id="address-loader" class="hidden absolute right-3 top-2.5">
                                    <i class="fa-solid fa-circle-notch animate-spin text-emerald-600 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Deskripsi Tambahan</label>
                            <textarea name="deskripsi" rows="3" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 mt-4">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm cursor-pointer uppercase tracking-wider">
                            Simpan Hasil Pengukuran
                        </button>
                    </div>
                </form>
            </div>
        </aside>

        <main class="flex-1 relative h-full bg-slate-100">
            <div class="absolute top-4 floating-search-container space-y-1">
                <div class="flex items-center bg-white border border-slate-200 rounded-xl shadow-lg px-3 py-1.5 focus-within:border-emerald-500 transition-colors">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm mr-2.5"></i>
                    <input type="text" id="gmaps-search-input" placeholder="Cari lokasi desa/kecamatan di Lahan ku..." class="w-full bg-transparent py-1 text-xs text-slate-800 outline-none font-medium">
                    <button type="button" id="btn-clear-search" class="hidden text-slate-400 hover:text-slate-600 ml-2">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
                <div id="search-suggestions" class="hidden bg-white border border-slate-200 rounded-xl shadow-2xl search-results-box divide-y divide-slate-100"></div>
            </div>

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
                    <div class="text-base font-mono font-bold text-slate-900 mt-0.5"><span id="modal-luas-m2">0,00</span> <span class="text-xs font-sans font-medium text-slate-500">m² (Meter Persegi)</span></div>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Konversi Hektar</span>
                    <div class="text-base font-mono font-bold text-emerald-600 mt-0.5"><span id="modal-luas-ha">0,0000</span> <span class="text-xs font-sans font-medium text-slate-500">Ha (Hektar)</span></div>
                </div>
                <hr class="border-slate-100">
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keliling Bidang Standar</span>
                    <div class="text-base font-mono font-bold text-slate-900 mt-0.5"><span id="modal-keliling-m">0,00</span> <span class="text-xs font-sans font-medium text-slate-500">m (Meter)</span></div>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Konversi Kilometer</span>
                    <div class="text-base font-mono font-bold text-blue-600 mt-0.5"><span id="modal-keliling-km">0,0000</span> <span class="text-xs font-sans font-medium text-slate-500">Km (Kilometer)</span></div>
                </div>
            </div>
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button onclick="closeMetricModal()" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-xs">Selesai</button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

    <script>
        let currentLuasRaw = 0;
        let currentKelilingRaw = 0;

        const map = L.map('map', { zoomControl: false }).setView([-2.548926, 118.014863], 5);
        L.control.zoom({ position: 'topright' }).addTo(map);
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}').addTo(map);

        const drawnItems = new L.FeatureGroup(); map.addLayer(drawnItems);

        function openMetricModal() {
            document.getElementById('modal-luas-m2').innerText = currentLuasRaw.toLocaleString('id-ID', { minimumFractionDigits: 2 });
            document.getElementById('modal-luas-ha').innerText = (currentLuasRaw / 10000).toLocaleString('id-ID', { minimumFractionDigits: 4 });
            document.getElementById('modal-keliling-m').innerText = currentKelilingRaw.toLocaleString('id-ID', { minimumFractionDigits: 2 });
            document.getElementById('modal-keliling-km').innerText = (currentKelilingRaw / 1000).toLocaleString('id-ID', { minimumFractionDigits: 4 });
            document.getElementById('metric-modal').classList.remove('hidden');
        }
        function closeMetricModal() { document.getElementById('metric-modal').classList.add('hidden'); }

        // Nominatim Engine (Pencarian Manual)
        const searchInput = document.getElementById('gmaps-search-input');
        const suggestionsBox = document.getElementById('search-suggestions');
        const btnClear = document.getElementById('btn-clear-search');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length > 0) { btnClear.classList.remove('hidden'); }
            else { btnClear.classList.add('hidden'); suggestionsBox.classList.add('hidden'); return; }

            fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&polygon_geojson=1&addressdetails=1&limit=5&countrycodes=id`)
                .then(res => res.json()).then(data => {
                    suggestionsBox.innerHTML = '';
                    if (data.length === 0) { suggestionsBox.classList.add('hidden'); return; }
                    data.forEach(item => {
                        const option = document.createElement('div');
                        option.className = "p-2.5 text-xs hover:bg-slate-50 cursor-pointer text-slate-800";
                        option.innerHTML = `<b>${item.name}</b> <div class="text-[10px] text-slate-400 truncate">${item.display_name}</div>`;
                        option.addEventListener('click', function() {
                            searchInput.value = item.name;
                            document.getElementById('input-alamat').value = item.display_name;
                            suggestionsBox.classList.add('hidden');
                            map.setView([item.lat, item.lon], 16);
                        });
                        suggestionsBox.appendChild(option);
                    });
                    suggestionsBox.classList.remove('hidden');
                });
        });
        btnClear.addEventListener('click', function() { searchInput.value = ''; this.classList.add('hidden'); suggestionsBox.classList.add('hidden'); });

        const drawControl = new L.Control.Draw({
            draw: { polygon: { allowIntersection: false, shapeOptions: { color: '#10b981', weight: 3 } }, polyline: false, circle: false, rectangle: false, marker: false, circlemarker: false },
            edit: { featureGroup: drawnItems }
        });
        map.addControl(drawControl);

        // FITUR BARU: Ambil Alamat Otomatis Berdasarkan Titik Polygon yang Selesai Digambar
        map.on(L.Draw.Event.CREATED, function (e) {
            const layer = e.layer;
            drawnItems.clearLayers();
            drawnItems.addLayer(layer);
            const geojson = layer.toGeoJSON();
            
            // Kalkulasi data spasial via Turf.js
            currentLuasRaw = turf.area(geojson);
            currentKelilingRaw = turf.length(geojson, { units: 'meters' });

            // Tampilkan ke komponen UI
            document.getElementById('display-luas').innerText = currentLuasRaw.toLocaleString('id-ID', { minimumFractionDigits: 2 });
            document.getElementById('display-keliling').innerText = currentKelilingRaw.toLocaleString('id-ID', { minimumFractionDigits: 2 });
            document.getElementById('input-luas').value = currentLuasRaw.toFixed(2);
            document.getElementById('input-keliling').value = currentKelilingRaw.toFixed(2);
            document.getElementById('input-koordinat').value = JSON.stringify(geojson.geometry.coordinates[0]);

            // Dapatkan titik pusat (Centroid) dari polygon untuk akurasi alamat terdekat
            const centroid = turf.centroid(geojson);
            const lng = centroid.geometry.coordinates[0];
            const lat = centroid.geometry.coordinates[1];

            // Panggil API Reverse Geocoding Nominatim
            const loader = document.getElementById('address-loader');
            const alamatInput = document.getElementById('input-alamat');
            
            loader.classList.remove('hidden');
            alamatInput.value = "Sedang mengambil alamat titik lahan...";

            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1`)
                .then(res => res.json())
                .then(data => {
                    loader.classList.add('hidden');
                    if (data && data.display_name) {
                        alamatInput.value = data.display_name;
                    } else {
                        alamatInput.value = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    }
                })
                .catch(err => {
                    loader.classList.add('hidden');
                    alamatInput.value = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    console.error("Gagal memuat reverse geocode alamat:", err);
                });
        });
    </script>
</body>
</html>