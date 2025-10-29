<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Presensi {{ $monthName }} {{ $year }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Sticky column styles */
        .attendance-table {
            position: relative;
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            max-width: 100%;
        }
        
        .attendance-table table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.875rem;
        }
        
        .attendance-table th,
        .attendance-table td {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
            text-align: center;
            white-space: nowrap;
        }
        
        /* Sticky columns - CRITICAL: Must have position sticky! */
        .sticky-col-no {
            position: -webkit-sticky !important;
            position: sticky !important;
            left: 0 !important;
            z-index: 10 !important;
            background-color: white !important;
            min-width: 50px;
            box-shadow: 2px 0 5px -2px rgba(0, 0, 0, 0.1);
        }
        
        .sticky-col-nip {
            position: -webkit-sticky !important;
            position: sticky !important;
            left: 50px !important;
            z-index: 10 !important;
            background-color: white !important;
            min-width: 120px;
            box-shadow: 2px 0 5px -2px rgba(0, 0, 0, 0.1);
        }
        
        .sticky-col-nama {
            position: -webkit-sticky !important;
            position: sticky !important;
            left: 170px !important;
            z-index: 10 !important;
            background-color: white !important;
            min-width: 200px;
            box-shadow: 2px 0 5px -2px rgba(0, 0, 0, 0.1);
            border-right: 2px solid #9ca3af !important;
        }
        
        /* Header sticky should be on top */
        thead th.sticky-col-no,
        thead th.sticky-col-nip,
        thead th.sticky-col-nama {
            z-index: 20 !important;
            background-color: #f9fafb !important;
        }
        
        /* Striped rows - maintain background on sticky columns */
        tbody tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        
        tbody tr:nth-child(even) .sticky-col-no,
        tbody tr:nth-child(even) .sticky-col-nip,
        tbody tr:nth-child(even) .sticky-col-nama {
            background-color: #f9fafb !important;
        }
        
        /* Weekend column */
        .weekend-col {
            background-color: #fee2e2 !important;
            color: #991b1b;
        }
        
        /* Attendance badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1.2;
        }
        
        .badge-hadir { background-color: #dcfce7; color: #166534; }
        .badge-sakit { background-color: #fef3c7; color: #92400e; }
        .badge-izin { background-color: #dbeafe; color: #1e40af; }
        .badge-cuti { background-color: #e9d5ff; color: #6b21a8; }
        .badge-dinas { background-color: #cffafe; color: #155e75; }
        .badge-alpha { background-color: #fee2e2; color: #991b1b; }
        .badge-libur { background-color: #f3f4f6; color: #374151; }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .attendance-table {
                border: 1px solid #000;
            }
            
            body {
                font-size: 10pt;
            }
            
            /* Ensure sticky columns print correctly */
            .sticky-col-no,
            .sticky-col-nip,
            .sticky-col-nama {
                position: static !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 no-print">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Laporan Presensi Bulanan</h1>
                    <p class="text-gray-600 mt-1">{{ $monthName }} {{ $year }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('attendance.print', ['year' => $year, 'month' => $month]) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </a>
                    <a href="{{ route('attendance.export-excel', ['year' => $year, 'month' => $month]) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export CSV
                    </a>
                    <a href="/admin/attendances" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Kembali
                    </a>
                </div>
            </div>
            
            <!-- Date Filter -->
            <form method="GET" class="flex gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="month" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $key => $monthName)
                            <option value="{{ $key + 1 }}" {{ $month == $key + 1 ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="year" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @for($y = 2023; $y <= 2026; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Tampilkan
                </button>
            </form>
        </div>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 no-print">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-600">Total Pegawai</div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_employees'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-600">Total Kehadiran</div>
                <div class="text-2xl font-bold text-green-600">{{ $stats['total_present'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-600">Total Izin/Sakit/Cuti</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['total_leave'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-600">Total Tidak Hadir</div>
                <div class="text-2xl font-bold text-red-600">{{ $stats['total_absent'] }}</div>
            </div>
        </div>
        
        <!-- Print Header (only visible when printing) -->
        <div class="hidden print:block mb-6">
            <div class="text-center mb-4">
                <h2 class="text-xl font-bold">LAPORAN PRESENSI PEGAWAI</h2>
                <p class="text-lg">Bulan: {{ $monthName }} {{ $year }}</p>
                <p class="text-sm">Periode: {{ $startDate->format('d F Y') }} s/d {{ $endDate->format('d F Y') }}</p>
            </div>
        </div>
        
        <!-- Attendance Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="attendance-table">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2" class="sticky-col-no">No</th>
                            <th rowspan="2" class="sticky-col-nip">NIP</th>
                            <th rowspan="2" class="sticky-col-nama">Nama</th>
                            @foreach($dates as $dateInfo)
                                <th class="{{ $dateInfo['isWeekend'] ? 'weekend-col' : 'bg-gray-50' }}" style="min-width: 80px;">
                                    {{ $dateInfo['day'] }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($dates as $dateInfo)
                                <th class="{{ $dateInfo['isWeekend'] ? 'weekend-col' : 'bg-gray-50' }}" style="font-size: 0.7rem;">
                                    {{ $dateInfo['dayName'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $index => $employee)
                            <tr>
                                <td class="sticky-col-no">{{ $index + 1 }}</td>
                                <td class="sticky-col-nip font-semibold">{{ $employee->nip }}</td>
                                <td class="sticky-col-nama text-left font-semibold">{{ $employee->name }}</td>
                                
                                @foreach($dates as $dateInfo)
                                    @php
                                        $attendance = $employee->attendances->first(function($att) use ($dateInfo) {
                                            return $att->date->isSameDay($dateInfo['date']);
                                        });
                                        
                                        $cellClass = $dateInfo['isWeekend'] ? 'bg-red-50' : '';
                                        
                                        if (!$attendance) {
                                            if ($dateInfo['isWeekend']) {
                                                $display = '<span class="badge badge-libur">0</span>';
                                            } else {
                                                $display = '<span class="badge badge-alpha">TK</span>';
                                            }
                                        } else {
                                            $statusColors = [
                                                'hadir' => 'hadir',
                                                'sakit' => 'sakit',
                                                'izin' => 'izin',
                                                'cuti' => 'cuti',
                                                'dinas_dalam' => 'dinas',
                                                'dinas_luar' => 'dinas',
                                                'alpha' => 'alpha',
                                                'libur' => 'libur',
                                            ];
                                            
                                            $badgeClass = 'badge-' . ($statusColors[$attendance->status] ?? 'libur');
                                            
                                            if ($attendance->status === 'libur') {
                                                $text = '0';
                                            } elseif ($attendance->status === 'alpha') {
                                                $text = 'TK';
                                            } elseif ($attendance->status === 'hadir') {
                                                $text = '';
                                                if ($attendance->check_in) {
                                                    $text .= $attendance->check_in->format('H:i');
                                                }
                                                if ($attendance->check_out) {
                                                    $text .= '<br>' . $attendance->check_out->format('H:i');
                                                } elseif ($attendance->check_in) {
                                                    $text .= '<br>TPP';
                                                }
                                                $text = $text ?: 'H';
                                            } else {
                                                $codes = [
                                                    'sakit' => 'S',
                                                    'izin' => 'I',
                                                    'cuti' => 'C',
                                                    'dinas_dalam' => 'DD',
                                                    'dinas_luar' => 'DL',
                                                ];
                                                $text = $codes[$attendance->status] ?? 'TK';
                                            }
                                            
                                            $display = "<span class='badge {$badgeClass}'>{$text}</span>";
                                        }
                                    @endphp
                                    
                                    <td class="{{ $cellClass }}" style="font-size: 0.7rem;">
                                        {!! $display !!}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($dates) + 3 }}" class="text-center py-8 text-gray-500">
                                    Tidak ada data pegawai
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Legend -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="font-bold text-lg mb-4">Keterangan:</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="flex items-center gap-2">
                    <span class="badge badge-hadir">H / 08:00</span>
                    <span class="text-sm">Hadir (Jam Masuk/Pulang)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-sakit">S</span>
                    <span class="text-sm">Sakit</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-izin">I</span>
                    <span class="text-sm">Izin</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-cuti">C</span>
                    <span class="text-sm">Cuti</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-dinas">DD</span>
                    <span class="text-sm">Dinas Dalam</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-dinas">DL</span>
                    <span class="text-sm">Dinas Luar</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-alpha">TK</span>
                    <span class="text-sm">Tidak Hadir (Alpha)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-libur">0</span>
                    <span class="text-sm">Libur</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-hadir">TPP</span>
                    <span class="text-sm">Tidak Pulang Presensi</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
