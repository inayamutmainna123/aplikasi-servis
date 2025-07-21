<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 15px;
            background-color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .header {
            text-align: center;
        }

        .ucapan {
            position: relative;
            text-align: center;
            top: 200px;
        }

        th {
            border: 1px solid #000;
            padding: 15px;
            background-color: rgb(128, 191, 231);
            left: 50px;
            top: 100px;
        }

        td {
            border: 1px solid #000;
            background-color: whitesmoke;
            padding: 15px;
            text-align: left;
            columns: center;
            left: -60px;
            width: 90px;
            top: 100px;
        }

        #home {
            width: 70px;
            height: 80px;
            position: relative;
            top: -10px;
            left: 0px;


        }


        h3 {
            top: -120px;
            left: 30px;
            position: relative;
            text-align: center;
        }

        .data {
            position: relative;
            top: -70px;
            left: 20px;

        }

        h6 {
            top: -140px;
            left: 30px;
            font-family: sans-serif;
            position: relative;
            text-align: center;

        }

        .nama {
            position: relative;
            top: -60px;
            left: 300px;
        }

        h4 {
            position: relative;
            top: -5px;
            left: 0px;
        }

        #jats {
            position: relative;
            top: -5px;
            left: 450px;
        }

        .harga {
            position: relative;
            top: -5px;
            left: 560px;
            border-radius: 0px;
            margin: 10px;

        }
    </style>
    <img id="home" src="image/smk.png" width="200" height="200">
    <img id="home" src="image/logo.png" width="200" height="200">
    <img id="jats" src="image/jats.png" width="100" height="100" left="100">
    <h3>AUTO JATS SERVIS SMKN 1 ANGGANA</h3>
    <h6> Jl. Handil Soppeng , RT. 10,Kutai Lama, Kec. Anggana, Kab.Kutai Kartanegara,<br> KalimantanTimur| Kode Pos 75381</h6>

</html>
</head>

<body>

    @php
    @endphp
    <div class="data">
        <p><strong>Customer:</strong> {{ $pembayaran->costumer->nama_costumer }}</p>
        <p><strong>Alamat:</strong> {{ $pembayaran->costumer->alamat_costumer }}</p>
        <p><strong>No Hp:</strong> {{ $pembayaran->costumer->no_hp_costumer }}</p>
        <p><strong>Email:</strong> {{ $pembayaran->costumer->email_costumer }}</p>
        <p><strong>Tanggal:</strong> {{ $pembayaran->tanggal_pembayaran }}</p>
    </div>
    <h4>Detail Pembayaran:</h4>
    <table>
        <thead>
            <th> Nama Sparepart</th>
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

    <div class="harga">
        <p><strong>Total Harga:</strong> Rp{{ number_format($pembayaran->total_harga, 0, ',', '.') }}</p>
        <p><strong>Total Bayar:</strong> Rp{{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</p>
        <p><strong>Kembalian:</strong> Rp{{ number_format($pembayaran->total_kembali, 0, ',', '.') }}</p>
    </div>
    <div class="ucapan">
        Terima kasih telah menggunakan layanan kami!<br>
        Semoga kendaraan Anda selalu dalam kondisi terbaik.
    </div>
</body>

</html>