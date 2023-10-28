@extends('layouts.app')
@section('title', 'Kategori Barang')

@section('content')
<div class="d-flex flex-column gap-4 h-100">
    <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
        <h4 class="m-0">Laporan Penjualan</h4>

        {{-- <a class="btn btn-primary d-flex align-items-center gap-2 btn-sm" href="">
            <ion-icon name="add" class="f24"></ion-icon>
            Tambah Kategori
        </a> --}}
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
    var users =  {{ Js::from($data) }};

    const data = {
      labels: labels,
      datasets: [{
        label: 'Data Penjualan',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: users,
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

{{-- @section('script')
import Chart from 'chart.js/auto';

const labels = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
];

const data = {
    labels: labels,
    datasets: [{
        label: 'My First dataset',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: [0, 10, 5, 2, 20, 30, 45],
    }]
};

const config = {
    type: 'line',
    data: data,
    options: {}
};

new Chart(
    document.getElementById('myChart'),
    config
); --}}
@endsection
