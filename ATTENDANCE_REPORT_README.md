# 📊 Laporan Presensi Pegawai - Dokumentasi

## 🎯 Fitur

### 1. **Tampilan Laporan Web dengan Sticky Columns**
- URL: `/attendance/report`
- Kolom **No, NIP, Nama** tetap terlihat saat scroll horizontal
- Filter bulan dan tahun
- Statistik ringkasan (Total Pegawai, Kehadiran, Izin/Sakit, Tidak Hadir)
- Responsive design
- Badge berwarna untuk status presensi

### 2. **Export ke Excel (CSV)**
- URL: `/attendance/export-excel?year=2025&month=10`
- Format: CSV (compatible dengan Excel, Google Sheets, LibreOffice)
- Encoding: UTF-8 dengan BOM
- File name: `Laporan_Presensi_[Bulan]_[Tahun].csv`

### 3. **Print / PDF**
- URL: `/attendance/print?year=2025&month=10`
- Layout landscape optimized untuk printing
- Tombol print otomatis
- Bisa simpan sebagai PDF via browser print dialog

## 📝 Format Data

### Status Presensi:
- **H / 08:00** - Hadir dengan jam masuk/pulang
- **S** - Sakit
- **I** - Izin
- **C** - Cuti
- **DD** - Dinas Dalam
- **DL** - Dinas Luar
- **TK** - Tidak Hadir (Alpha)
- **0** - Libur
- **TPP** - Tidak Pulang Presensi

## 🚀 Cara Menggunakan

### Dari Browser:

1. **Buka Laporan**
   ```
   http://localhost:8000/attendance/report
   ```

2. **Pilih Periode**
   - Pilih bulan dari dropdown
   - Pilih tahun
   - Klik "Tampilkan"

3. **Export Excel**
   - Klik tombol "Export Excel"
   - File CSV akan otomatis terdownload
   - Buka dengan Excel/Google Sheets

4. **Print**
   - Klik tombol "Print"
   - Akan membuka halaman baru
   - Klik tombol print atau Ctrl+P
   - Pilih printer atau "Save as PDF"

### Parameter URL:

```
/attendance/report?year=2025&month=10
/attendance/export-excel?year=2025&month=10
/attendance/print?year=2025&month=10
```

## 🎨 Sticky Columns

Kolom yang selalu terlihat saat scroll:
- **No**: 50px, left: 0
- **NIP**: 120px, left: 50px
- **Nama**: 200px, left: 170px

Total width sticky area: 370px

## 📁 File Structure

```
app/
├── Http/Controllers/
│   └── AttendanceReportController.php    # Main controller
├── Exports/
│   └── AttendanceExport.php              # Excel export logic
resources/
└── views/
    └── attendance/
        ├── monthly-report.blade.php      # Main report view
        └── print.blade.php               # Print view
routes/
└── web.php                               # Routes definition
```

## 🔧 Technical Details

### CSS Sticky Implementation:
```css
.sticky-col-no {
    position: -webkit-sticky !important;
    position: sticky !important;
    left: 0 !important;
    z-index: 10 !important;
    background-color: white !important;
}
```

### Export Format:
- Row 1: Tanggal (1-31)
- Row 2: Nama Hari (Mon, Tue, etc)
- Row 3+: Data pegawai dengan status presensi

### Browser Compatibility:
- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- IE11: ⚠️ Limited support (no sticky)

## 📊 Data Flow

```
User Request
    ↓
Controller (monthlyReport)
    ↓
Query Employees + Attendances
    ↓
Prepare Dates Array
    ↓
Calculate Statistics
    ↓
Return View with Data
```

## 🎯 Future Enhancements

1. ✅ Export to Excel (CSV) - **DONE**
2. ✅ Print view - **DONE**
3. 🔄 Export to PDF (DomPDF)
4. 🔄 Email report
5. 🔄 Schedule automatic reports
6. 🔄 Advanced filters (department, position)
7. 🔄 Comparison charts

## 🐛 Troubleshooting

### Sticky columns tidak bekerja:
1. Clear browser cache (Ctrl + Shift + R)
2. Pastikan browser support CSS sticky
3. Check console untuk CSS errors

### Export Excel error:
1. Check file permissions
2. Verify data exists untuk periode tersebut
3. Check server logs

### Print tidak bagus:
1. Set layout ke Landscape
2. Adjust margins
3. Disable headers/footers di print settings
