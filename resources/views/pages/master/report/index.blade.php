@extends('layouts.app')
@section('title', 'Kategori Barang')

@section('content')
    <div class="d-flex flex-column gap-4 mb-5">
        <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
            <h4 class="m-0">Laporan Penjualan</h4>
            <form method="get" action="{{ route('transaction.index') }}" class="d-flex">
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
        <div class="card border-0 flex-grow-1 d-flex flex-column h-100">
            <div class="border-bottom px-4 pt-4 pb-3 flex-shrink-0">
                <canvas id="myChart" height="100px"></canvas>
            </div>
        </div>
    </div>

    {{-- <div class="d-flex flex-column gap-4 h-100">
      <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
          <h4 class="m-0">Laporan Pembelian</h4>
  
          <form method="get" action="{{ route('transaction.download') }}">
              <input type="date" name="start_date" required>
              <input type="date" name="end_date" required>
              <button type="submit">Filter</button>
          </form>
          <a class="btn btn-primary d-flex align-items-center gap-2 btn-sm" href="{{ route('transaction.pdf') }}">
              <ion-icon name="add" class="f24"></ion-icon>
              Download Laporan
          </a>
      </div> --}}
  
      <div class="card border-0 flex-grow-1 d-flex flex-column h-100 mt-4">
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

          {{-- <a class="px-4 pt-4 pb-3 btn btn-primary d-flex align-items-center gap-2 btn-sm" href="{{ route('transaction.pdf') }}"> --}}
            {{-- <ion-icon name="add" class="f24"></ion-icon> --}}
            {{-- Download Laporan --}}
          {{-- </a> --}}
        </div>
  
        <div class="p-1 flex-grow-1">
            <div id="grid" class="ag-theme-alpine h-100"></div>
        </div>
      </div>
  </div>
  
  @include('partials.ag-grid.aggrid')
  @include('partials.ag-grid.aggrid-default-user-btn')
  
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
