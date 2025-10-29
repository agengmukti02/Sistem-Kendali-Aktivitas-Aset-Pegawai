<div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600">{{ \App\Filament\Resources\Attendances\Tables\HorizontalAttendanceTable::getMonthlyStats(now()->year, now()->month)['attendance_rate'] }}%</div>
                <div class="text-xs text-gray-600">Tingkat Kehadiran</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ \App\Filament\Resources\Attendances\Tables\HorizontalAttendanceTable::getMonthlyStats(now()->year, now()->month)['perfect_attendance'] }}</div>
                <div class="text-xs text-gray-600">Kehadiran Sempurna</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ \App\Filament\Resources\Attendances\Tables\HorizontalAttendanceTable::getMonthlyStats(now()->year, now()->month)['working_days'] }}</div>
                <div class="text-xs text-gray-600">Hari Kerja</div>
            </div>
        </div>
        
        <div class="text-right">
            <div class="text-sm text-gray-600">
                📊 <strong>Matrix View</strong> - Analisis pola kehadiran dalam satu pandangan
            </div>
            <div class="text-xs text-gray-500 mt-1">
                Scroll horizontal untuk navigasi • Hover untuk detail • Filter untuk analisis
            </div>
        </div>
    </div>
    
</div>