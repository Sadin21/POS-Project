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
            <th>Nomor Pembelian</th>
            <th>Subtotal</th>
            <th>Grandtotal</th>
            <th>Total Barang</th>
            <th>Discount</th>
            <th>Pembayaran</th>
            <th>Uang Dibayar</th>
            <th>Tanggal Pembelian</th>
        </tr>
        @foreach($data as $data)
        <tr>
            <td>{{ $data->sale_no }}</td>
            <td>Rp. {{ number_format($data->subtotal, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($data->grandtotal, 0, ',', '.') }}</td>
            <td>{{ $data->total_qty }}</td>
            <td>{{ $data->discount }}</td>
            <td>{{ $data->payment }}</td>
            <td>Rp. {{ number_format($data->cash_amount, 0, ',', '.') }}</td>
            <td>{{ $data->created_at }}</td>
        </tr>
        @endforeach
    </table>
    </body>
</html>
