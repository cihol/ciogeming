@extends('layouts.app')

@section('content')

<style>
body { background: #f4f7fc; font-family: 'Poppins', sans-serif; }
.dashboard-title { color: #1e3a5f; font-weight: 700; border-left: 5px solid #0d6efd; padding-left: 15px; }
.stat-card { border: none; border-radius: 18px; overflow: hidden; transition: all .3s ease; height: 100%; }
.stat-card:hover { transform: translateY(-6px); box-shadow: 0 12px 25px rgba(0,0,0,.12); }
.stat-card .card-body { padding: 25px; }
.card-title { font-size: 15px; font-weight: 500; margin-bottom: 12px; opacity: .95; }
.card-value { font-size: 38px; font-weight: 700; line-height: 1; margin-bottom: 8px; }
.card-body small { font-size: 13px; opacity: .9; }
.card-merah { background: linear-gradient(135deg, #0d6efd, #4da3ff); color: white; }
.card-abu { background: linear-gradient(135deg, #6c757d, #9aa1a8); color: white; }
.card-putih { background: #ffffff; color: #333; border: 1px solid #e5e7eb; }
.card-putih .card-value { color: #0d6efd; }
.chart-card { border: none; border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.chart-card .card-header { background: white; border-bottom: 1px solid #f0f0f0; border-radius: 18px 18px 0 0 !important; padding: 16px 20px; font-weight: 600; font-size: 14px; color: #1e3a5f; }
.chart-card .card-body { background: white; border-radius: 0 0 18px 18px; padding: 20px; }
.activity-badge { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 600; }
@media (max-width: 768px) { .dashboard-title { font-size: 22px; } .card-value { font-size: 30px; } }
</style>

<h4 class="dashboard-title mb-4">Dashboard Ringkasan Sistem</h4>

{{-- Alert Stok Menipis --}}
@if($stokMenipis->count() > 0)
<div class="alert shadow-sm mb-4" style="background:#fff8e6;border:none;border-left:5px solid #ffc107;border-radius:15px;padding:18px">
    <h6 class="fw-bold text-danger">⚠️ Peringatan Kritis: Stok Barang Menipis!</h6>
    <ul class="mb-0" style="padding-left:20px">
        @foreach($stokMenipis as $b)
            <li style="margin-bottom:6px;color:#555">
                <strong>{{ $b->nama_barang }}</strong> tersisa {{ $b->stok }} unit
                (Kategori: {{ $b->kategori_barang }})
            </li>
        @endforeach
    </ul>
</div>
@endif

{{-- Kartu Statistik --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card card-merah shadow-sm">
            <div class="card-body">
                <h6 class="card-title">📦 Total Ragam Barang</h6>
                <div class="card-value">{{ $totalBarang }}</div>
                <small>Item Inventaris</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card card-abu shadow-sm">
            <div class="card-body">
                <h6 class="card-title">📋 Peminjaman Aktif</h6>
                <div class="card-value">{{ $totalPeminjaman }}</div>
                <small>Transaksi Berjalan</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card card-putih shadow-sm">
            <div class="card-body">
                <h6 class="card-title">👨‍🎓 Siswa Terdaftar</h6>
                <div class="card-value text-danger">{{ $totalPeminjam }}</div>
                <small>Data Peminjam</small>
            </div>
        </div>
    </div>
</div>

{{-- Chart Peminjaman per Bulan --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>📈 Peminjaman per Bulan</span>
                <select class="form-select form-select-sm w-auto" id="tahunFilter" onchange="updateChartBulan()">
                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                    <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                </select>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 mb-3" style="font-size:12px;color:#888">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#0d6efd;margin-right:4px"></span>Dipinjam</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#198754;margin-right:4px"></span>Dikembalikan</span>
                </div>
                <div style="position:relative;height:220px">
                    <canvas id="chartBulan" role="img" aria-label="Grafik peminjaman per bulan">Data peminjaman bulanan.</canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart Kondisi + Kategori --}}
<div class="row mb-4">
    <div class="col-md-5 mb-3">
        <div class="card chart-card h-100">
            <div class="card-header">🔧 Kondisi Barang</div>
            <div class="card-body d-flex flex-column align-items-center">
                <div class="d-flex gap-3 mb-3" style="font-size:12px;color:#888;align-self:flex-start">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#198754;margin-right:4px"></span>Baik {{ $barangBaik ?? 0 }}</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#dc3545;margin-right:4px"></span>Rusak {{ $barangRusak ?? 0 }}</span>
                </div>
                <div style="position:relative;height:200px;width:200px">
                    <canvas id="chartKondisi" role="img" aria-label="Diagram donut kondisi barang">Kondisi barang: Baik dan Rusak.</canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7 mb-3">
        <div class="card chart-card h-100">
            <div class="card-header">📊 Stok per Kategori</div>
            <div class="card-body">
                <div style="position:relative;height:220px">
                    <canvas id="chartKategori" role="img" aria-label="Grafik stok per kategori">Data stok per kategori barang.</canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Aktivitas Terbaru --}}
<div class="row">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header">🕒 Aktivitas Peminjaman Terbaru</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Barang</th>
                                <th>Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aktivitasTerbaru ?? [] as $aktivitas)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $aktivitas->nama_barang }}</td>
                                <td>{{ $aktivitas->nama_peminjam }}</td>
                                <td>{{ optional($aktivitas->tanggal_pinjam)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    @if($aktivitas->status == 'Dipinjam')
                                        <span class="activity-badge bg-warning text-dark">Dipinjam</span>
                                    @elseif($aktivitas->status == 'Dikembalikan')
                                        <span class="activity-badge bg-success text-white">Dikembalikan</span>
                                    @else
                                        <span class="activity-badge bg-danger text-white">Terlambat</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Belum ada aktivitas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const dataBulan = @json($dataPeminjamanBulan ?? array_fill(0, 12, 0));
const dataDikembalikan = @json($dataDikembalikanBulan ?? array_fill(0, 12, 0));
const dataKategori = @json($dataKategori ?? []);
const barangBaik = {{ $barangBaik ?? 0 }};
const barangRusak = {{ $barangRusak ?? 0 }};

const bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
const gridColor = 'rgba(0,0,0,0.06)';
const textColor = '#888';

// Chart Peminjaman per Bulan
const ctxBulan = document.getElementById('chartBulan').getContext('2d');
const chartBulan = new Chart(ctxBulan, {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [
            {
                label: 'Dipinjam',
                data: dataBulan,
                backgroundColor: '#0d6efd',
                borderRadius: 6,
                borderSkipped: false,
            },
            {
                label: 'Dikembalikan',
                data: dataDikembalikan,
                backgroundColor: '#198754',
                borderRadius: 6,
                borderSkipped: false,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                ticks: { color: textColor, autoSkip: false, maxRotation: 0 },
                grid: { display: false },
                border: { display: false }
            },
            y: {
                ticks: { color: textColor, stepSize: 2 },
                grid: { color: gridColor },
                border: { display: false },
                beginAtZero: true
            }
        }
    }
});

// Chart Kondisi Barang (Donut)
new Chart(document.getElementById('chartKondisi').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Baik', 'Rusak'],
        datasets: [{
            data: [barangBaik, barangRusak],
            backgroundColor: ['#198754', '#dc3545'],
            borderWidth: 0,
            hoverOffset: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: { legend: { display: false } }
    }
});

// Chart Stok per Kategori (Horizontal Bar)
const kategoriLabels = dataKategori.map(k => k.kategori_barang);
const kategoriData   = dataKategori.map(k => k.total_stok);
new Chart(document.getElementById('chartKategori').getContext('2d'), {
    type: 'bar',
    data: {
        labels: kategoriLabels.length ? kategoriLabels : ['Belum ada data'],
        datasets: [{
            data: kategoriData.length ? kategoriData : [0],
            backgroundColor: ['#0d6efd','#6f42c1','#fd7e14','#0dcaf0','#6c757d','#20c997','#ffc107'],
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                ticks: { color: textColor },
                grid: { color: gridColor },
                border: { display: false },
                beginAtZero: true
            },
            y: {
                ticks: { color: textColor },
                grid: { display: false },
                border: { display: false }
            }
        }
    }
});
</script>

@endsection
