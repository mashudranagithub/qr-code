@extends('layouts.app')
@section('title', 'Analytics - ' . $qrCode->name)

@section('content')
<div class="row mt-4">
    <div class="col-md-12 mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary mb-3">&larr; Back to Dashboard</a>
        <h2 class="fw-bold">Analytics: {{ $qrCode->name }}</h2>
        <p class="text-muted">{{ $qrCode->destination_url }}</p>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <h5 class="text-muted mb-1">Total Scans</h5>
            <h2 class="fw-bold text-primary">{{ $scans->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <h5 class="text-muted mb-1">Top Country</h5>
            <h2 class="fw-bold text-success">{{ $scansByCountry->first()->country ?? 'N/A' }}</h2>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <h5 class="text-muted mb-1">Top Device</h5>
            <h2 class="fw-bold text-info">{{ $scansByDevice->first()->device_type ?? 'N/A' }}</h2>
        </div>
    </div>

    <!-- Charts -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 p-4">
            <h5 class="fw-bold mb-4">Scans Trend</h5>
            <canvas id="scansChart" height="150"></canvas>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 p-4">
            <h5 class="fw-bold mb-4">Devices</h5>
            <canvas id="devicesChart"></canvas>
        </div>
    </div>

    <!-- Scan Log -->
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold">Recent Scans</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Time</th>
                                <th>IP Address</th>
                                <th>Country</th>
                                <th>City</th>
                                <th class="pe-4">Device</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($scans as $scan)
                            <tr>
                                <td class="ps-4">{{ $scan->created_at->diffForHumans() }}</td>
                                <td><code>{{ $scan->ip_address }}</code></td>
                                <td>{{ $scan->country }}</td>
                                <td>{{ $scan->city }}</td>
                                <td class="pe-4"><span class="badge bg-light text-dark shadow-sm border">{{ $scan->device_type }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No scans recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Scans Trend Chart
    const scansCtx = document.getElementById('scansChart').getContext('2d');
    new Chart(scansCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($scansByDate->pluck('date')) !!},
            datasets: [{
                label: 'Scans',
                data: {!! json_encode($scansByDate->pluck('count')) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Devices Chart
    const devicesCtx = document.getElementById('devicesChart').getContext('2d');
    new Chart(devicesCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($scansByDevice->pluck('device_type')) !!},
            datasets: [{
                data: {!! json_encode($scansByDevice->pluck('count')) !!},
                backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384']
            }]
        },
        options: {
            plugins: { 
                legend: { position: 'bottom' } 
            }
        }
    });
</script>
@endsection
