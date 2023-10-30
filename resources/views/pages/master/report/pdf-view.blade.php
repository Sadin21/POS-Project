<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title') | Kasir</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

        @yield('style')
        <style>
            body.swal2-height-auto { height: 100vh !important; }
        </style>
    </head>

    <body class="d-flex flex-column">
      <table class="table table-striped">
          <thead>
          <tr>
              <th>Nomor Pembelian</th>
              <th>Subtotal</th>
              <th>Grandtotal</th>
              <th>Total Barang</th>
              <th>Discount</th>
              <th>Pembayaran</th>
              <th>Uang Dibayar</th>
              <th>Status</th>
              <th>Tanggal Pembelian</th>
          </tr>
          </thead>
          <tbody>
              @foreach($data as $data)
                  <tr>
                      <td>{{ $data->sale_no }}</td>
                      <td>{{ $data->subtotal }}</td>
                      <td>{{ $data->grandtotal }}</td>
                      <td>{{ $data->total_qty }}</td>
                      <td>{{ $data->discount }}</td>
                      <td>{{ $data->payment }}</td>
                      <td>{{ $data->cash_amount }}</td>
                      <td>{{ $data->status }}</td>
                      <td>{{ $data->created_at }}</td>
                  </tr>
              @endforeach
          </tbody>
      </table>
    </body>
</html>
