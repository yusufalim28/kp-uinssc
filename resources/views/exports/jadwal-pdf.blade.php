<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* dompdf cuma dukung CSS2.1 + sebagian CSS3 — jangan pakai flexbox/grid */
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1F1F1F;
        }
        .header {
            border-bottom: 3px solid #F4D000;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 4px 0;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #1F1F1F;
            color: #ffffff;
            text-align: left;
            padding: 6px 8px;
            font-size: 10px;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 10px;
        }
        tr:nth-child(even) td {
            background-color: #FAFAF7;
        }
        .footer {
            margin-top: 16px;
            font-size: 9px;
            color: #999;
            text-align: right;
        }
        .empty {
            text-align: center;
            padding: 20px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Jadwal Praktikum</h1>
        <p>Diunduh pada {{ $tanggalExport }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Jam</th>
                <th>Mata Kuliah</th>
                <th>Kelas</th>
                <th>Dosen</th>
                <th>Ruang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwal as $j)
                <tr>
                    <td>{{ $j->hari }}</td>
                    <td>{{ substr($j->jam_mulai, 0, 5) }}&ndash;{{ substr($j->jam_selesai, 0, 5) }}</td>
                    <td>{{ $j->mataKuliah->nama_mk }}</td>
                    <td>{{ $j->kelas->nama_kelas }}</td>
                    <td>{{ $j->dosen->nama }}</td>
                    <td>{{ $j->ruangPraktikum->nama_ruang }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty">Tidak ada jadwal yang sesuai filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Sistem Informasi Manajemen Jadwal Praktikum — {{ count($jadwal) }} jadwal
    </div>

</body>
</html>
