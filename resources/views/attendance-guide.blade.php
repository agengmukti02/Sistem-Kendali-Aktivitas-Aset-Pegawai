@extends('filament::layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📊 Sistem Presensi - Panduan Navigasi</h1>
            <p class="text-gray-600">Pilih format tampilan yang sesuai kebutuhan Anda</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Horizontal Matrix -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 p-6 rounded-xl border border-blue-200 shadow-lg">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-full mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-900">Rekap Horizontal</h3>
                    <p class="text-sm text-blue-700">Format Matrix Spreadsheet</p>
                </div>
                
                <div class="bg-white rounded-lg p-3 mb-4 text-xs font-mono">
                    <div class="grid grid-cols-7 gap-1 text-center">
                        <div class="font-bold">NIP</div>
                        <div class="font-bold">Nama</div>
                        <div class="font-bold">1</div>
                        <div class="font-bold">2</div>
                        <div class="font-bold">3</div>
                        <div class="font-bold">...</div>
                        <div class="font-bold">Rekap</div>
                        <div>001</div>
                        <div>John</div>
                        <div>🟢</div>
                        <div>🔴</div>
                        <div>🟡</div>
                        <div>...</div>
                        <div>20H 2S</div>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm text-blue-800">
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Satu baris = Satu pegawai
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Kolom horizontal = Hari (1-31)
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Visual icons dengan tooltip
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Summary rekap per pegawai
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="/admin/horizontal-attendances" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        🎯 Format Yang Anda Inginkan
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Vertical Monthly -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-100 p-6 rounded-xl border border-purple-200 shadow-lg">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-600 rounded-full mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-purple-900">Rekap Vertikal</h3>
                    <p class="text-sm text-purple-700">Format Monthly List</p>
                </div>
                
                <div class="bg-white rounded-lg p-3 mb-4 text-xs">
                    <div class="space-y-1">
                        <div class="grid grid-cols-4 gap-2 font-bold border-b">
                            <div>Pegawai</div>
                            <div>Tanggal</div>
                            <div>Status</div>
                            <div>Jam</div>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <div>John</div>
                            <div>1 Oct</div>
                            <div>Hadir</div>
                            <div>8h</div>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <div>John</div>
                            <div>2 Oct</div>
                            <div>Sakit</div>
                            <div>-</div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm text-purple-800">
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Format list vertikal
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Detail per hari
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Filter bulanan
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="/admin/monthly-attendances" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        📋 Lihat Format Vertikal
                    </a>
                </div>
            </div>

            <!-- Data Management -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-100 p-6 rounded-xl border border-green-200 shadow-lg">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-green-600 rounded-full mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-900">Data Presensi</h3>
                    <p class="text-sm text-green-700">Management & CRUD</p>
                </div>
                
                <div class="bg-white rounded-lg p-3 mb-4 text-xs">
                    <div class="space-y-1">
                        <div class="grid grid-cols-3 gap-2 font-bold border-b">
                            <div>Employee</div>
                            <div>Date</div>
                            <div>Action</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>Jane</div>
                            <div>Oct 20</div>
                            <div class="text-blue-600">Edit</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>Jane</div>
                            <div>Oct 21</div>
                            <div class="text-blue-600">Edit</div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm text-green-800">
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Create, Read, Update, Delete
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Edit individual records
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        Raw data management
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="/admin/attendances" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        🗄️ Kelola Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Recommendation -->
        <div class="mt-8 p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white">
            <div class="text-center">
                <h3 class="text-xl font-bold mb-2">🎯 Rekomendasi untuk Anda</h3>
                <p class="text-blue-100 mb-4">
                    Berdasarkan permintaan Anda untuk format horizontal matrix seperti spreadsheet:
                </p>
                <div class="bg-white/10 rounded-lg p-4 mb-4">
                    <code class="text-yellow-300 text-sm">
                        NIP | Nama | Jabatan | 1 | 2 | 3 | 4 | 5 | ... | 31 | Rekap<br>
                        001 | John Doe | Manager | 🟢 | 🟢 | 🔴 | 🟡 | ⚪ | ... | 🟢 | 20H 2S 1I 8L
                    </code>
                </div>
                <a href="/admin/horizontal-attendances" 
                   class="inline-flex items-center bg-white text-blue-600 font-bold py-3 px-6 rounded-lg hover:bg-gray-100 transition duration-200">
                    🚀 Akses Rekap Horizontal Matrix
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection