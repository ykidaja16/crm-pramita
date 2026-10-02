@extends('layouts.main')

@section('title', 'Dashboard - Medical Lab CRM')

@section('styles')
<style>
    /* ── Stat Card Premium ── */
    .stat-card {
        border: none;
        border-radius: 16px;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        box-shadow: 0 4px 18px rgba(0,0,0,.13);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 110px; height: 110px;
        border-radius: 50%;
        background: rgba(255,255,255,.10);
        pointer-events: none;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: -40px; left: -20px;
        width: 140px; height: 140px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
        pointer-events: none;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(0,0,0,.20);
    }
    .stat-card .card-body { position: relative; z-index: 1; }
    .stat-card h6 { font-size: .82rem; font-weight: 500; opacity: .92; letter-spacing: .3px; }
    .stat-card h3 { font-size: 2rem; font-weight: 700; margin-top: 4px; }
    .stat-card .stat-icon { opacity: .22; font-size: 3rem; }
    .stat-card .detail-link {
        font-size: .78rem;
        color: rgba(255,255,255,.80);
        text-decoration: none;
        border-top: 1px solid rgba(255,255,255,.20);
        padding-top: 8px;
        display: block;
        margin-top: 10px;
        transition: color .15s;
    }
    .stat-card .detail-link:hover { color: #fff; }

    /* ── Gradient palette ── */
    .gc-blue     { background: linear-gradient(135deg, #2563EB 0%, #4F46E5 100%) !important; }
    .gc-slate    { background: linear-gradient(135deg, #475569 0%, #1E293B 100%) !important; }
    .gc-teal     { background: linear-gradient(135deg, #0891B2 0%, #0E7490 100%) !important; }
    .gc-amber    { background: linear-gradient(135deg, #D97706 0%, #B45309 100%) !important; }
    .gc-rose     { background: linear-gradient(135deg, #E11D48 0%, #9F1239 100%) !important; }
    .gc-emerald  { background: linear-gradient(135deg, #059669 0%, #047857 100%) !important; }
    .gc-orange   { background: linear-gradient(135deg, #EA580C 0%, #C2410C 100%) !important; }
    .gc-bluegray { background: linear-gradient(135deg, #64748B 0%, #334155 100%) !important; }
</style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary mb-0">Dashboard Utama</h3>
            @if(!$isMultiCabang && $cabangNama)
                <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>Cabang: <strong>{{ $cabangNama }}</strong></small>
            @endif
        </div>
    </div>

    @if($isMultiCabang)
        {{-- ===== MULTI-CABANG: Tab per cabang ===== --}}
        <div class="card mb-4">
            <div class="card-header bg-white p-0">
                <ul class="nav nav-tabs border-0 flex-wrap" id="cabangTabs" role="tablist">
                    @foreach($perCabangStats as $cabId => $cab)
                    @php $isActiveTab = ($activeCabang === $cabId); @endphp
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $isActiveTab ? 'active' : '' }} rounded-0"
                                id="tab-{{ $cabId }}-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-{{ $cabId }}"
                                type="button" role="tab">
                            <i class="fas fa-building me-1 opacity-75"></i>{{ $cab['nama'] }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body p-3">
                <div class="tab-content" id="cabangTabsContent">
                    @foreach($perCabangStats as $cabId => $cab)
                    <div class="tab-pane fade {{ ($activeCabang === $cabId) ? 'show active' : '' }}"
                         id="tab-{{ $cabId }}" role="tabpanel">

                        {{-- Statistik Cards --}}
                        @php $p = ['cabang_id' => $cabId]; @endphp
                        <div class="row mb-3 g-3">
                            @foreach([
                                ['gc'=>'gc-blue',  'icon'=>'fa-users',          'label'=>'Total Pelanggan',              'value'=>$cab['totalPelanggan'],             'type'=>'total'],
                                ['gc'=>'gc-slate', 'icon'=>'fa-calendar-minus', 'label'=>'Kunjungan Bulan Kemarin',      'value'=>$cab['totalKunjunganBulanKemarin'], 'type'=>'kunjungan_bulan_kemarin'],
                                ['gc'=>'gc-teal',  'icon'=>'fa-chart-line',     'label'=>'Kunjungan Tahun Ini',          'value'=>$cab['totalKunjunganTahunIni'],     'type'=>'kunjungan_tahun_ini'],
                                ['gc'=>'gc-amber', 'icon'=>'fa-user-plus',      'label'=>'Pelanggan Baru Bulan Kemarin', 'value'=>$cab['pelangganBaruBulanKemarin'],  'type'=>'pelanggan_baru_bulan_kemarin'],
                            ] as $card)
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="card stat-card {{ $card['gc'] }} h-100">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-center flex-grow-1">
                                            <div>
                                                <h6 class="mb-0">{{ $card['label'] }}</h6>
                                                <h3 class="mb-0">{{ number_format($card['value']) }}</h3>
                                            </div>
                                            <i class="fas {{ $card['icon'] }} stat-icon"></i>
                                        </div>
                                        <a href="{{ route('dashboard.detail', array_merge($p, ['type'=>$card['type']])) }}" class="detail-link">
                                            <i class="fas fa-eye me-1"></i>Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Statistik Per Klasifikasi --}}
                        <div class="row g-3">
                            @foreach([
                                ['gc'=>'gc-rose',     'icon'=>'fa-crown', 'label'=>'Total Pelanggan Prioritas', 'value'=>$cab['totalPelangganPrioritas'], 'type'=>'prioritas'],
                                ['gc'=>'gc-emerald',  'icon'=>'fa-heart', 'label'=>'Total Pelanggan Loyal',     'value'=>$cab['totalPelangganLoyal'],     'type'=>'loyal'],
                                ['gc'=>'gc-orange',   'icon'=>'fa-star',  'label'=>'Total Pelanggan Potensial', 'value'=>$cab['totalPelangganPotensial'], 'type'=>'potensial'],
                                ['gc'=>'gc-bluegray', 'icon'=>'fa-user',  'label'=>'Total Pelanggan Umum',      'value'=>$cab['totalPelangganUmum'],      'type'=>'umum'],
                            ] as $card)
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="card stat-card {{ $card['gc'] }} h-100">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-center flex-grow-1">
                                            <div>
                                                <h6 class="mb-0">{{ $card['label'] }}</h6>
                                                <h3 class="mb-0">{{ number_format($card['value']) }}</h3>
                                            </div>
                                            <i class="fas {{ $card['icon'] }} stat-icon"></i>
                                        </div>
                                        <a href="{{ route('dashboard.detail', array_merge($p, ['type'=>$card['type']])) }}" class="detail-link">
                                            <i class="fas fa-eye me-1"></i>Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>{{-- end tab-pane --}}
                    @endforeach
                </div>
            </div>
        </div>

    @else
        {{-- ===== SINGLE CABANG: layout asli ===== --}}
        @php $sc = $singleCabangId; @endphp

        <!-- Statistik Cards -->
        <div class="row mb-4 g-3">
            @foreach([
                ['gc'=>'gc-blue',  'icon'=>'fa-users',          'label'=>'Total Pelanggan',              'value'=>$totalPelanggan,             'type'=>'total'],
                ['gc'=>'gc-slate', 'icon'=>'fa-calendar-minus', 'label'=>'Kunjungan Bulan Kemarin',      'value'=>$totalKunjunganBulanKemarin, 'type'=>'kunjungan_bulan_kemarin'],
                ['gc'=>'gc-teal',  'icon'=>'fa-chart-line',     'label'=>'Kunjungan Tahun Ini',          'value'=>$totalKunjunganTahunIni,     'type'=>'kunjungan_tahun_ini'],
                ['gc'=>'gc-amber', 'icon'=>'fa-user-plus',      'label'=>'Pelanggan Baru Bulan Kemarin', 'value'=>$pelangganBaruBulanKemarin,  'type'=>'pelanggan_baru_bulan_kemarin'],
            ] as $card)
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card stat-card {{ $card['gc'] }} h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center flex-grow-1">
                            <div>
                                <h6 class="mb-0">{{ $card['label'] }}</h6>
                                <h3 class="mb-0">{{ number_format($card['value']) }}</h3>
                            </div>
                            <i class="fas {{ $card['icon'] }} stat-icon"></i>
                        </div>
                        <a href="{{ route('dashboard.detail', ['type'=>$card['type'],'cabang_id'=>$sc]) }}" class="detail-link">
                            <i class="fas fa-eye me-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Statistik Per Klasifikasi -->
        <div class="row mb-4 g-3">
            @foreach([
                ['gc'=>'gc-rose',     'icon'=>'fa-crown', 'label'=>'Total Pelanggan Prioritas', 'value'=>$totalPelangganPrioritas, 'type'=>'prioritas'],
                ['gc'=>'gc-emerald',  'icon'=>'fa-heart', 'label'=>'Total Pelanggan Loyal',     'value'=>$totalPelangganLoyal,     'type'=>'loyal'],
                ['gc'=>'gc-orange',   'icon'=>'fa-star',  'label'=>'Total Pelanggan Potensial', 'value'=>$totalPelangganPotensial, 'type'=>'potensial'],
                ['gc'=>'gc-bluegray', 'icon'=>'fa-user',  'label'=>'Total Pelanggan Umum',      'value'=>$totalPelangganUmum,      'type'=>'umum'],
            ] as $card)
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card stat-card {{ $card['gc'] }} h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center flex-grow-1">
                            <div>
                                <h6 class="mb-0">{{ $card['label'] }}</h6>
                                <h3 class="mb-0">{{ number_format($card['value']) }}</h3>
                            </div>
                            <i class="fas {{ $card['icon'] }} stat-icon"></i>
                        </div>
                        <a href="{{ route('dashboard.detail', ['type'=>$card['type'],'cabang_id'=>$sc]) }}" class="detail-link">
                            <i class="fas fa-eye me-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    @endif

    <!-- Filter Grafik -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <i class="fas fa-filter text-primary me-2"></i> Filter Grafik
        </div>
        <div class="card-body">
            <form method="GET" class="row align-items-end" id="filterForm">
                @if($isMultiCabang)
                <input type="hidden" name="active_cab" id="activeTabInput" value="{{ $activeCabang }}">
                @endif
                <div class="col-md-3">
                    <label class="form-label">Tipe Filter</label>
                    <select name="filter_type" class="form-select" onchange="this.form.submit()">
                        <option value="monthly" {{ $filterType == 'monthly' ? 'selected' : '' }}>Per Bulan</option>
                        <option value="yearly" {{ $filterType == 'yearly' ? 'selected' : '' }}>Per Tahun</option>
                        <option value="class" {{ $filterType == 'class' ? 'selected' : '' }}>Per Klasifikasi</option>
                    </select>
                </div>

                @if($filterType == 'monthly')
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @for($i = date('Y') - 5; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                @endif

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Grafik -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i> {{ $chartTitle }}</h5>
        </div>
        <div class="card-body">
            <canvas id="growthChart" height="200"></canvas>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Klik tab → reload halaman dengan active_cab baru agar chart ikut terupdate
    document.querySelectorAll('#cabangTabs button[data-bs-toggle="tab"]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var cabId = e.currentTarget.getAttribute('data-bs-target').replace('#tab-', '');
            var url   = new URL(window.location.href);
            url.searchParams.set('active_cab', cabId);
            window.location.href = url.toString();
        });
    });
</script>
<script>
    const ctx = document.getElementById('growthChart').getContext('2d');

    // Data dari PHP
    const labels     = <?= json_encode($chartLabels  ?? []) ?>;
    const data       = <?= json_encode($chartData    ?? []) ?>;
    const filterType = <?= json_encode($filterType   ?? 'monthly') ?>;

    // Warna berdasarkan tipe filter
    let backgroundColors, borderColors;

    if (filterType === 'class') {
        backgroundColors = [
            'rgba(225, 29, 72, 0.85)',     // Prioritas - rose
            'rgba(5, 150, 105, 0.85)',     // Loyal - emerald
            'rgba(234, 88, 12, 0.85)',     // Potensial - orange
            'rgba(100, 116, 139, 0.85)'    // Umum - blue-gray
        ];
        borderColors = [
            'rgba(225, 29, 72, 1)',
            'rgba(5, 150, 105, 1)',
            'rgba(234, 88, 12, 1)',
            'rgba(100, 116, 139, 1)'
        ];
    } else {
        backgroundColors = 'rgba(37, 99, 235, 0.65)';
        borderColors = 'rgba(37, 99, 235, 1)';
    }

    new Chart(ctx, {
        type: filterType === 'class' ? 'pie' : 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pasien',
                data: data,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: filterType === 'class',
                    position: 'bottom'
                }
            },
            scales: filterType === 'class' ? {} : {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
