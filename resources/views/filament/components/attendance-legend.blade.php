<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        @foreach(\App\Filament\Resources\Attendances\Tables\HorizontalAttendanceTable::getColorLegend() as $icon => $description)
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <span class="text-2xl">{{ explode(' ', $icon)[0] }}</span>
                <div>
                    <div class="font-medium text-sm text-gray-900">
                        {{ explode(' ', $icon, 2)[1] ?? $icon }}
                    </div>
                    <div class="text-xs text-gray-600">{{ $description }}</div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
        <h4 class="font-medium text-blue-900 mb-2">💡 Tips Penggunaan:</h4>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• <strong>Hover</strong> pada icon untuk melihat detail waktu dan keterangan</li>
            <li>• <strong>Scroll horizontal</strong> untuk melihat seluruh hari dalam bulan</li>
            <li>• <strong>Kolom NIP, Nama, Jabatan</strong> akan tetap terlihat saat scroll (sticky)</li>
            <li>• <strong>Kolom Rekap</strong> menampilkan summary kehadiran bulanan</li>
            <li>• <strong>Hari ini</strong> ditandai dengan highlight biru</li>
            <li>• <strong>Weekend</strong> ditandai dengan background merah muda</li>
        </ul>
    </div>
</div>