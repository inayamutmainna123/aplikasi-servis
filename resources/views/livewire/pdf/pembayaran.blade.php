<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pembayaran</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        #home {
            width: 46px;
            height: 44px;


        }
    </style>
    <img id="home" src="image/logo.png" width="100" height="50">

</html>
</head>

<body>
    @php

    @endphp
    <h2>Pembayaran</h2>
    <p><strong>Tanggal:</strong> {{ $pembayaran->tanggal_pembayaran }}</p>
    <p><strong>Customer:</strong> {{ $pembayaran->costumer->nama_costumer }}</p>
    <p><strong>Alamat:</strong> {{ $pembayaran->costumer->alamat_costumer }}</p>
    <p><strong>No Hp:</strong> {{ $pembayaran->costumer->no_hp_costumer }}</p>
    <p><strong>Email:</strong> {{ $pembayaran->costumer->email_costumer }}</p>

    <h4>Detail Pembayaran:</h4>
    <table>
        <thead>
            <th>Nama Sparepart</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Nama Service</th>
            <th>Jumlah</th>
            <th>Harga</th>
        </thead>
        <tbody>
            @foreach ($pembayaran->items as $item)
            <tr>
                <td>{{ optional($item->sparepart)->nama_sparepart }}</td>
                <td>{{ $item->jumlah_sparepart }}</td>
                <td>Rp{{ number_format($item->harga_sparepart, 0, ',', '.') }}</td>
                <td>{{ optional($item->service_item)->nama_service }}</td>
                <td>{{ $item->jumlah_service }}</td>
                <td>Rp{{ number_format($item->harga_service, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>

    <p><strong>Total Harga:</strong> Rp{{ number_format($pembayaran->total_harga, 0, ',', '.') }}</p>
    <p><strong>Total Bayar:</strong> Rp{{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</p>
    <p><strong>Kembalian:</strong> Rp{{ number_format($pembayaran->total_kembali, 0, ',', '.') }}</p>
</body>

</html>