<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
        <td colspan="9" style="font-weight: bold; font-size: 14px;">
            Laporan Presensi Bulanan {{ $bulan->nama_bulan }}
        </td>
        </tr>
        <tr>
            <th>No</th>
            <th>Kelas</th>
            <th>S</th>
            <th>I</th>
            <th>A</th>
            <th>Hadir</th>
            <th>Tidak Hadir</th>
            <th>Tepat Waktu</th>
            <th>Terlambat</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($statistikPerKelas as $data)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data['kelas']->nama_kelas }}</td>
                <td>{{ $data['presensi']->where('status', 'sakit')->count() }}</td>
                <td>{{ $data['presensi']->where('status', 'izin')->count() }}</td>
                <td>{{ $data['presensi']->where('status', 'alpha')->count() }}</td>
                <td>{{ $data['persentaseTidakHadir'] }}%</td>
                <td>{{ $data['persentaseHadir'] }}%</td>
                <td>{{ $data['persentaseTerlambat'] }}%</td>
                <td>{{ $data['persentaseTepatWaktu'] }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
