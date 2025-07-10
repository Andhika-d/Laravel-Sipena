<table>
    <thead>
        <tr>
            <th>Nama Guru</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Alfa</th>
            <th>Total Poin</th>
            <th>Persentase</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekap as $item)
            <tr>
                <td>{{ $item['nama_guru'] }}</td>
                <td>{{ $item['hadir'] }}</td>
                <td>{{ $item['izin'] }}</td>
                <td>{{ $item['sakit'] }}</td>
                <td>{{ $item['alfa'] }}</td>
                <td>{{ $item['total'] }}</td>
                <td>{{ $item['persentase'] }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
