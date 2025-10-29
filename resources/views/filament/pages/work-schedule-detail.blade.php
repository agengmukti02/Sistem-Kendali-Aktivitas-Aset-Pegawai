<div class="space-y-6">
    {{-- Employee Information --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pegawai</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">NIP</label>
                <p class="mt-1 text-sm text-gray-900">{{ $record->employee->nip }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <p class="mt-1 text-sm text-gray-900">{{ $record->employee->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ $record->employee->email ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                <p class="mt-1 text-sm text-gray-900">{{ $record->employee->position ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Schedule Information --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Jadwal Kerja</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Kerja</label>
                <p class="mt-1 text-sm text-gray-900">{{ $record->work_date->format('d F Y') }} ({{ $record->work_date->format('l') }})</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Shift</label>
                <p class="mt-1">
                    @if($record->shift)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($record->shift === 'Pagi') bg-green-100 text-green-800
                            @elseif($record->shift === 'Siang') bg-yellow-100 text-yellow-800
                            @elseif($record->shift === 'Malam') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $record->shift }}
                        </span>
                    @else
                        <span class="text-gray-500">-</span>
                    @endif
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status Kehadiran</label>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($record->status === 'Hadir') bg-green-100 text-green-800
                        @elseif($record->status === 'Dinas Dalam') bg-blue-100 text-blue-800
                        @elseif($record->status === 'Dinas Luar') bg-cyan-100 text-cyan-800
                        @elseif(in_array($record->status, ['Cuti', 'Izin'])) bg-yellow-100 text-yellow-800
                        @elseif(in_array($record->status, ['Sakit', 'Alpha'])) bg-red-100 text-red-800
                        @elseif($record->status === 'Libur') bg-gray-100 text-gray-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $record->status }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- Attendance Information --}}
    @if(in_array($record->status, ['Hadir', 'Dinas Dalam', 'Dinas Luar']))
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kehadiran</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Waktu Masuk</label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $record->check_in ? $record->check_in->format('H:i') : '-' }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Waktu Keluar</label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $record->check_out ? $record->check_out->format('H:i') : '-' }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Total Jam Kerja</label>
                <p class="mt-1 text-sm text-gray-900">
                    @if($record->check_in && $record->check_out)
                        {{ $record->check_out->diff($record->check_in)->format('%h:%I') }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- Additional Information --}}
    @if($record->reason || $record->notes)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Tambahan</h3>
        @if($record->reason)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Alasan</label>
            <p class="mt-1 text-sm text-gray-900">{{ $record->reason }}</p>
        </div>
        @endif
        @if($record->notes)
        <div>
            <label class="block text-sm font-medium text-gray-700">Catatan</label>
            <p class="mt-1 text-sm text-gray-900">{{ $record->notes }}</p>
        </div>
        @endif
    </div>
    @endif

    {{-- Quick Actions --}}
    @if($record->work_date->isToday() && in_array($record->status, ['Hadir', 'Dinas Dalam', 'Dinas Luar']))
    <div class="bg-blue-50 rounded-lg p-4">
        <h4 class="text-sm font-medium text-blue-900 mb-2">Aksi Cepat</h4>
        <div class="flex space-x-2">
            @if(!$record->check_in)
                <span class="text-sm text-blue-700">✨ Siap untuk check in</span>
            @elseif(!$record->check_out)
                <span class="text-sm text-blue-700">⏰ Siap untuk check out</span>
            @else
                <span class="text-sm text-green-700">✅ Presensi lengkap</span>
            @endif
        </div>
    </div>
    @endif
</div>