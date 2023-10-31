@extends('layouts.app')
@section('title', 'Laporan Penjualan')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4 align-content-center">
        <h4 class="m-0">Laporan Penjualan</h4>
        <ul class="nav nav-underline mb-0">
            <li class="nav-item me-4">
                <a class="nav-link active" aria-current="page" href="#grap" id="grap-button" onclick="showTab('grap')">Grap</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#table" id="table-button" onclick="showTab('table')">Table</a>
            </li>
        </ul>
    </div>

    <div class="card border-0 flex-grow-1 d-flex flex-column h-100 mt-4 d-none px-4" id="grap">
        <div class="d-flex justify-content-end align-items-center flex-shrink-0 gap-4">
            <form method="get" action="{{ route('transaction.index') }}" class="d-flex mb-4">
                <div class="pt-4 pb-3 flex-shrink-0">
                  <div class="position-relative search-box">
                      <input type="date" id="" class="form-control" name="start_date" placeholder="">
                  </div>
                </div>
                <div class="px-2 pt-4 pb-3 flex-shrink-0">
                  <div class="position-relative search-box">
                      <input type="date" id="" class="form-control" name="end_date" placeholder="">
                  </div>
                </div>
                <div class="pt-4 pb-3 flex-shrink-0">
                  <button id="" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
        <div class="p-1 flex-grow-1 mt-4">
            <canvas id="myChart" height="100px"></canvas>
        </div>
    </div>
  
    <div class="card border-0 flex-grow-1 d-flex flex-column h-100 mt-4 d-none" id="table">
        <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
            <div class="border-bottom px-4 pt-4 pb-3 flex-shrink-0">
                <div class="position-relative search-box">
                    <ion-icon name="search" class="f24 position-absolute"></ion-icon>
                    <input type="text" id="filter-text-box" class="form-control" placeholder="Ketik untuk mencari..." onchange="search()">
                </div>
            </div>

            <div class="px-4 pt-4 pb-3 flex-shrink-0">
            <a href="{{ route('transaction.pdf') }}" id="" class="btn btn-primary">Download Laporan</a>
            </div>
        </div>

        <div class="p-1 flex-grow-1 mt-4">
            <div id="grid" class="ag-theme-alpine h-100"></div>
        </div>
    </div>
  
  @include('partials.ag-grid.aggrid')
  @include('partials.ag-grid.aggrid-default-user-btn')
  
  <script>
    window.onload = function() {
        showDefaultTab('grap');
    }

    function showDefaultTab(defaultTab) {
        const chartButton = document.getElementById('grap-button');
        const tableButton = document.getElementById('table-button');
        
        const chartView = document.getElementById('grap');
        const tableView = document.getElementById('table');

        if (defaultTab === 'table') {
            chartView.classList.add('d-none');
            tableView.classList.remove('d-none');

            chartButton.classList.remove('active');
            tableButton.classList.add('active');
        } else {
            chartView.classList.remove('d-none');
            tableView.classList.add('d-none');

            chartButton.classList.add('active');
            tableButton.classList.remove('active');
        }
    }

    function showTab(tabId) {
        const chartButton = document.getElementById('grap-button');
        const tableButton = document.getElementById('table-button');
        
        const chartView = document.getElementById('grap');
        const tableView = document.getElementById('table');

        if (tabId === 'table') {
            chartView.classList.add('d-none');
            tableView.classList.remove('d-none');

            chartButton.classList.remove('active');
            tableButton.classList.add('active');
        } else {
            chartView.classList.remove('d-none');
            tableView.classList.add('d-none');

            chartButton.classList.add('active');
            tableButton.classList.remove('active');
        }
    }
  </script>

  <script>
      gridOptions.columnDefs = [
          { field: 'sale_no', headerName: 'Nomor Pembelian' },
          { field: 'subtotal', headerName: 'Subtotal' },
          { field: 'grandtotal', headerName: 'Grandtotal' },
          { field: 'total_qty', headerName: 'Total Barang' },
          { field: 'discount', headerName: 'Discount' },
          { field: 'payment', headerName: 'Pembayaran' }, 
          { field: 'cash_amount', headerName: 'Uang Dibayar'},
          { field: 'status', headerName: 'Status'},
          { field: 'created_at', headerName: 'Tanggal Pembelian', valueFormatter: ({ value }) => formatDateTime(value), sort: 'desc' },
      ];
      
      gridOptions.onGridReady = ({ api }) => {
          const source = {
              getRows: (p) => {
                  api.showLoadingOverlay();
  
                  const limit = p.endRow - p.startRow;
                  const { sort, colId } = p.sortModel[0];
                  const keyword = document.getElementById('filter-text-box').value;
  
                  callApi({
                      url: `{{ route('report.query') }}?keyword=${ keyword }&limit=${ limit }&offset=${ p.startRow }&order=${ sort }&order_by=${ colId }`,
                      error: () => p.failCallback(),
                      next: ({ data }) => {
                          api.hideOverlay();
  
                          if (data.length === 0 && p.startRow === 0) api.showNoRowsOverlay();
                          p.successCallback(data, data.length < limit? p.startRow + data.length : null);
                      } 
                  });
              }
          };
          api.setDatasource(source);
      };
  
      function search() {
          gridOptions.api.refreshInfiniteCache();
      }
  
      document.addEventListener('DOMContentLoaded', () => (new agGrid.Grid(document.getElementById('grid'), gridOptions)));
  </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="text/javascript">
        var labels = {{ Js::from($labels) }};
        var datas = {{ Js::from($data) }};
        console.log(labels);
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
