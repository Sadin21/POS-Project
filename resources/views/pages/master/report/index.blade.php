@extends('layouts.app')
@section('title', 'Kategori Barang')

@section('content')
<div class="d-flex flex-column gap-4 h-100">
    <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
        <h4 class="m-0">Laporan Penjualan</h4>

    </div>
    <div class="card border-0 flex-grow-1 d-flex flex-column h-100">
        <div class="border-bottom px-4 pt-4 pb-3 flex-shrink-0">
            <canvas id="myChart" height="100px"></canvas>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
  
    var labels =  {{ Js::from($labels) }};
    var datas =  {{ Js::from($data) }};

    const data = {
      labels: labels,
      datasets: [{
        label: 'Data Penjualan',
        backgroundColor: 'rgb(0, 0, 255)',
        borderColor: 'rgb(0, 0, 255)',
        data: datas,
      }]
    };

    const config = {
      type: 'line',
      data: data,
      options: {}
    };

    const myChart = new Chart(
      document.getElementById('myChart'),
      config
    );

</script>

@endsection
