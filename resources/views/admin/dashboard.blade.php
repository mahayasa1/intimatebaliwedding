@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap');

    /* Stats Grid - Improved Responsive */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #8B7355, #6B5644);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(139, 115, 85, 0.15);
        border-color: #8B7355;
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 0.75rem;
    }

    .stat-title {
        font-family: 'Work Sans', sans-serif;
        font-size: 0.8rem;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex: 1;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: linear-gradient(135deg, #8B7355 0%, #6B5644 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        box-shadow: 0 3px 10px rgba(139, 115, 85, 0.25);
        flex-shrink: 0;
    }

    .stat-value {
        font-family: 'Playfair Display', serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1;
    }

    /* Chart Card - Improved Responsive */
    .chart-card {
        background: white;
        padding: 1.75rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
        margin-bottom: 2rem;
    }

    .chart-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .chart-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.85rem;
        letter-spacing: -0.5px;
    }

    .chart-legend {
        display: flex;
        gap: 1.75rem;
        font-size: 0.9rem;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-family: 'Work Sans', sans-serif;
        font-weight: 500;
        color: #666;
    }

    .legend-dot {
        width: 11px;
        height: 11px;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        flex-shrink: 0;
    }

    .legend-dot.blue {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .legend-dot.green {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    }

    .chart-container {
        position: relative;
        width: 100%;
        height: 320px;
    }

    /* Table Card - Improved Responsive */
    .table-card {
        background: white;
        padding: 1.75rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #e8e8e8;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 1.25rem;
        border-bottom: 2px solid #f0f0f0;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .table-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .table-responsive {
        overflow-x: auto;
        margin: 0 -1.75rem;
        padding: 0 1.75rem;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-family: 'Work Sans', sans-serif;
        min-width: 500px;
    }

    table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    table th {
        text-align: left;
        padding: 0.9rem 0.7rem;
        font-weight: 600;
        font-size: 0.82rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e8e8e8;
        white-space: nowrap;
    }

    table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    table tbody tr:hover {
        background: linear-gradient(135deg, #fafbfc 0%, #f8f9fa 100%);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    table td {
        padding: 1rem 0.7rem;
        font-size: 0.9rem;
        color: #333;
    }

    table td strong {
        font-weight: 600;
        color: #1a1a1a;
    }

    table tr:last-child td {
        border-bottom: none;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.4rem 0.9rem;
        border-radius: 18px;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
        letter-spacing: 0.3px;
    }

    .status-badge::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }

    .status-badge.new {
        background: linear-gradient(135deg, #FFE5E5 0%, #FFD0D0 100%);
        color: #E53E3E;
        border: 1px solid #FFB8B8;
    }

    .status-badge.new::before {
        background: #E53E3E;
    }

    .status-badge.contacted {
        background: linear-gradient(135deg, #FFF3CD 0%, #FFE69C 100%);
        color: #F59E0B;
        border: 1px solid #FFD54F;
    }

    .status-badge.contacted::before {
        background: #F59E0B;
    }

    .status-badge.in-discussion {
        background: linear-gradient(135deg, #D1F4E0 0%, #B8F1CC 100%);
        color: #10B981;
        border: 1px solid #81E6B8;
    }

    .status-badge.in-discussion::before {
        background: #10B981;
    }

    .status-badge.closed {
        background: linear-gradient(135deg, #E5E7EB 0%, #D1D5DB 100%);
        color: #6B7280;
        border: 1px solid #9CA3AF;
    }

    .status-badge.closed::before {
        background: #6B7280;
    }

    /* Responsive Breakpoints */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            font-size: 1.15rem;
        }

        .stat-value {
            font-size: 2rem;
        }

        .stat-title {
            font-size: 0.75rem;
        }

        .chart-card,
        .table-card {
            padding: 1.25rem;
            border-radius: 10px;
        }

        .chart-container {
            height: 260px;
        }

        .chart-title {
            font-size: 1.2rem;
        }

        .chart-legend {
            gap: 1.25rem;
        }

        .table-responsive {
            margin: 0 -1.25rem;
            padding: 0 1.25rem;
        }

        .table-title {
            font-size: 1.1rem;
        }

        table th,
        table td {
            padding: 0.8rem 0.5rem;
            font-size: 0.85rem;
        }

        table th {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .stat-card {
            padding: 1rem;
        }

        .stat-header {
            gap: 0.5rem;
        }

        .stat-icon {
            width: 34px;
            height: 34px;
            font-size: 1.05rem;
        }

        .stat-value {
            font-size: 1.75rem;
        }

        .stat-title {
            font-size: 0.7rem;
        }

        .chart-card,
        .table-card {
            padding: 1rem;
        }

        .chart-container {
            height: 220px;
        }

        .chart-title {
            font-size: 1.05rem;
        }

        .chart-legend {
            gap: 1rem;
            font-size: 0.82rem;
        }

        .table-title {
            font-size: 1rem;
        }

        table {
            min-width: 450px;
        }

        table th {
            font-size: 0.7rem;
            padding: 0.7rem 0.4rem;
        }

        table td {
            font-size: 0.8rem;
            padding: 0.7rem 0.4rem;
        }

        .status-badge {
            font-size: 0.72rem;
            padding: 0.35rem 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Active Packages</div>
            <div class="stat-icon"><i class="fas fa-box"></i></div>
        </div>
        <div class="stat-value">{{ $stats['packages'] ?? 10 }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Enquiries</div>
            <div class="stat-icon"><i class="fas fa-envelope"></i></div>
        </div>
        <div class="stat-value">{{ $stats['enquiries'] ?? 25 }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">New (Today)</div>
            <div class="stat-icon"><i class="fas fa-bell"></i></div>
        </div>
        <div class="stat-value">{{ $stats['new_enquiries'] ?? 2 }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Blog Articles</div>
            <div class="stat-icon"><i class="fas fa-pencil-alt"></i></div>
        </div>
        <div class="stat-value">{{ $stats['blogs'] ?? 13 }}</div>
    </div>
</div>

<!-- Chart Card -->
<div class="chart-card">
    <div class="chart-header">
        <h3 class="chart-title">Enquiries Overview</h3>
        <div class="chart-legend">
            <div class="legend-item">
                <span class="legend-dot blue"></span>
                <span>Last year</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot green"></span>
                <span>This year</span>
            </div>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="enquiryChart"></canvas>
    </div>
</div>

<!-- Recent Enquiries Table -->
<div class="table-card">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fas fa-list"></i> Recent Enquiries
        </h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEnquiries as $enquiry)
                <tr>
                    <td><strong>{{ $enquiry->name }}</strong></td>
                    <td>{{ $enquiry->wedding_type ?: 'Consultation' }}</td>
                    <td>
                        <span class="status-badge {{ strtolower(str_replace('_', '-', $enquiry->status)) }}">
                            {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                        </span>
                    </td>
                    <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Enquiry Chart with responsive configuration
    const ctx = document.getElementById('enquiryChart');
    
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG'],
                datasets: [{
                    label: 'Last year',
                    data: [45, 52, 38, 65, 70, 65, 58, 50],
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3498db',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 3
                }, {
                    label: 'This year',
                    data: [35, 48, 55, 62, 68, 75, 65, 55],
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2ecc71',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        padding: 12,
                        borderRadius: 8,
                        titleFont: {
                            size: 13,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 12
                        },
                        displayColors: true,
                        boxWidth: 10,
                        boxHeight: 10,
                        boxPadding: 5
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            color: '#999'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10,
                                weight: '600'
                            },
                            color: '#999'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
</script>
@endpush