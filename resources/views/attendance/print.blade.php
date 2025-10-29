<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Laporan Presensi {{ $monthName }} {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 16pt;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11pt;
            margin-bottom: 3px;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 8pt;
        }
        
        .attendance-table th,
        .attendance-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        
        .attendance-table th {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        
        .attendance-table .sticky-col-no,
        .attendance-table .sticky-col-nip {
            text-align: center;
        }
        
        .attendance-table .sticky-col-nama {
            text-align: left;
            min-width: 150px;
        }
        
        .weekend-col {
            background-color: #ffe0e0;
        }
        
        .legend {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        
        .legend h3 {
            font-size: 11pt;
            margin-bottom: 10px;
        }
        
        .legend-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            font-size: 9pt;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8pt;
        }
        
        .badge-hadir { background-color: #dcfce7; color: #166534; }
        .badge-sakit { background-color: #fef3c7; color: #92400e; }
        .badge-izin { background-color: #dbeafe; color: #1e40af; }
        .badge-cuti { background-color: #e9d5ff; color: #6b21a8; }
        .badge-dinas { background-color: #cffafe; color: #155e75; }
        .badge-alpha { background-color: #fee2e2; color: #991b1b; }
        .badge-libur { background-color: #f3f4f6; color: #374151; }
        
        @media print {
            body {
                padding: 10px;
            }
            
            .no-print {
                display: none !important;
            }
            
            .attendance-table {
                font-size: 7pt;
            }
            
            .attendance-table th,
            .attendance-table td {
                padding: 2px;
            }
        }
        
        @page {
            size: landscape;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            🖨️ Print
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            ❌ Tutup
        </button>
    </div>

    <div class="header">
        <h1>LAPORAN PRESENSI PEGAWAI</h1>
        <p><strong>Periode: {{ $monthName }} {{ $year }}</strong></p>
        <p>{{ $startDate->format('d F Y') }} s/d {{ $endDate->format('d F Y') }}</p>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th rowspan="2" class="sticky-col-no" style="width: 30px;">No</th>
                <th rowspan="2" class="sticky-col-nip" style="width: 80px;">NIP</th>
                <th rowspan="2" class="sticky-col-nama" style="width: 150px;">Nama</th>
                @foreach($dates as $dateInfo)
                    <th class="{{ $dateInfo['isWeekend'] ? 'weekend-col' : '' }}" style="width: 40px;">
                        {{ $dateInfo['day'] }}
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach($dates as $dateInfo)
                    <th class="{{ $dateInfo['isWeekend'] ? 'weekend-col' : '' }}" style="font-size: 7pt;">
                        {{ $dateInfo['dayName'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $index => $employee)
                <tr>
                    <td class="sticky-col-no">{{ $index + 1 }}</td>
                    <td class="sticky-col-nip" style="font-weight: bold;">{{ $employee->nip }}</td>
                    <td class="sticky-col-nama" style="font-weight: bold;">{{ $employee->name }}</td>
                    
                    @foreach($dates as $dateInfo)
                        @php
                            $attendance = $employee->attendances->first(function($att) use ($dateInfo) {
                                return $att->date->isSameDay($dateInfo['date']);
                            });
                            
                            $cellClass = $dateInfo['isWeekend'] ? 'weekend-col' : '';
                            
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
                        
                        <td class="{{ $cellClass }}">
                            {!! $display !!}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($dates) + 3 }}" style="text-align: center; padding: 20px;">
                        Tidak ada data pegawai
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="legend">
        <h3>Keterangan:</h3>
        <div class="legend-grid">
            <div class="legend-item">
                <span class="badge badge-hadir">H / 08:00</span>
                <span>= Hadir (Jam Masuk/Pulang)</span>
            </div>
            <div class="legend-item">
                <span class="badge badge-sakit">S</span>
                <span>= Sakit</span>
            </div>
            <div class="legend-item">
                <span class="badge badge-izin">I</span>
                <span>= Izin</span>
            </div>
            <div class="legend-item">
                <span class="badge badge-cuti">C</span>
                <span>= Cuti</span>
            </div>
            <div class="legend-item">
                <span class="badge badge-dinas">DD</span>
                <span>= Dinas Dalam</span>
            </div>
            <div class="legend-item">
                <span class="badge badge-dinas">DL</span>
                <span>= Dinas Luar</span>
            </div>
            <div class="legend-item">
                <span class="badge badge-alpha">TK</span>
                <span>= Tidak Hadir</span>
            </div>
            <div class="legend-item">
                <span class="badge badge-libur">0</span>
                <span>= Libur</span>
            </div>
            <div class="legend-item">
                <span class="badge badge-hadir">TPP</span>
                <span>= Tidak Pulang Presensi</span>
            </div>
        </div>
    </div>

    <script>
        // Auto print when opening in new window (optional)
        window.addEventListener('load', function() {
            // Uncomment to auto-print
            // setTimeout(() => window.print(), 500);
        });
    </script>
</body>
</html>
