<!DOCTYPE html>
<html>
    <head>
        <style>
        table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        }

        tr:nth-child(even) {
        background-color: #dddddd;
        }
        </style>
    </head>
    <body>
    <h2>Laporan Penjualan</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nomor Pembelian</th>
            <th>Nama Barang</th>
            <th>Total Barang</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Pembayaran</th>
            <th>Uang Dibayar</th>
            <th>Kasir</th>
            <th>Tanggal Pembelian</th>
        </tr>
        @foreach($data as $data)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $data->sale_no }}</td>
            <td>{{ $data->product_name }}</td>
            <td>{{ $data->total_qty }}</td>
            <td>Rp. {{ number_format($data->product_buy_price, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($data->product_sale_price, 0, ',', '.') }}</td>
            <td>{{ $data->payment }}</td>
            <td>Rp. {{ number_format($data->change_amount, 0, ',', '.') }}</td>
            <td>{{ $data->cashier }}</td>
            <td>{{ $data->created_at }}</td>
        </tr>
        @endforeach
    </table>
    <table style="margin-top: 50px">
        <tr>
            <td>Total barang terjual</td>
            <td>{{ $totalSaledQty }}</td>
        </tr>
        <tr>
            <td>Keuntungan</td>
            <td>{{ $totalIncome }}</td>
        </tr>
    </table>
    </body>
</html>
