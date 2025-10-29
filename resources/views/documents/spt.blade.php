<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Tugas</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .kop {
            text-align: center;
            margin-bottom: 10px;
        }
        .content {
            margin: 0 50px;
        }
        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-info td {
            padding: 4px;
            vertical-align: top;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="kop">
        <h3>PEMERINTAH KABUPATEN X</h3>
        <h4>DINAS Y</h4>
        <p>Jl. Contoh Alamat No. 123, Telp: (021) 123456</p>
        <hr>
    </div>

    <div class="header">
        <h4>SURAT PERINTAH TUGAS (SPT)</h4>
        <p>Nomor: {{ $activity->nomor_surat ?? '-' }}/SPT/{{ \Carbon\Carbon::now()->year }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini memberikan perintah kepada:</p>

        <table class="table-info">
            <tr>
                <td style="width: 150px;">Nama</td>
                <td>: {{ $activity->employee->name }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>: {{ $activity->employee->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{ $activity->employee->jabatan ?? '-' }}</td>
            </tr>
        </table>

        <p style="margin-top: 15px;">
            Untuk melaksanakan tugas berupa <b>{{ ucfirst($activity->jenis) }}</b> 
            dengan keperluan <b>{{ $activity->keperluan ?? '-' }}</b>,
            yang dilaksanakan pada tanggal 
            <b>{{ \Carbon\Carbon::parse($activity->tanggal_awal)->translatedFormat('d F Y') }}</b>
            sampai dengan
            <b>{{ \Carbon\Carbon::parse($activity->tanggal_akhir)->translatedFormat('d F Y') }}</b>.
        </p>

        <p>
            Demikian Surat Perintah Tugas ini dibuat agar dilaksanakan dengan penuh tanggung jawab.
        </p>

        <div class="signature">
            <p>Kabupaten X, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><b>Kepala Dinas</b></p>
            <br><br><br>
            <p><b>______________________</b></p>
            <p>NIP. 123456789</p>
        </div>
    </div>

</body>
</html>
