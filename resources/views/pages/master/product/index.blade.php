@extends('layouts.app')
@section('title', 'Daftar Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4 align-content-center">
    <h4 class="m-0">Daftar Barang</h4>
    <a class="btn btn-primary d-flex align-items-center gap-2" href="{{ route('product.store') }}">
        <ion-icon name="add" class="f24"></ion-icon>
        Tambah Barang
    </a>
</div>
<div class="card border-0 flex-grow-1 d-flex flex-column h-100 mt-4" id="table">
    <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
        <div class="border-bottom px-4 pt-4 pb-3 flex-shrink-0">
            <div class="position-relative search-box">
                <ion-icon name="search" class="f24 position-absolute"></ion-icon>
                <input type="text" id="filter-text-box" class="form-control" placeholder="Ketik untuk mencari..." onchange="search()">
            </div>
        </div>
    </div>

    <div class="p-1 flex-grow-1 mt-4">
        <div id="grid" class="ag-theme-alpine h-100"></div>
    </div>
</div>

@include('partials.ag-grid.aggrid')
@include('partials.ag-grid.aggrid-default-btn')

<script>
    gridOptions.columnDefs = [
        { field: 'code', headerName: 'Kode Barang' },
        { field: 'name', headerName: 'Nama Barang' },
        { field: 'category_id', headerName: 'Kategori Barang' },
        { field: 'sale_price', headerName: 'Harga' },
        { field: 'qty', headerName: 'Stok Total'},
        { field: 'available_qty', headerName: 'Stok Barang Tersedia' },
        { field: 'created_at', headerName: 'Tanggal Buat', valueFormatter: ({ value }) => formatDateTime(value), sort: 'desc' },
        { field: 'action', headerName: 'Aksi', minWidth: 200, sortable: false, cellRenderer: AgGridDefaultBtn, cellRendererParams: {
            canUpdate: true,
            canDelete: true,
            updateUrl: `{{ route('product.update', 'id') }}`,
            deleteUrl: `{{ route('product.delete') }}`,
        }}
    ];
    
    gridOptions.onGridReady = ({ api }) => {
        const source = {
            getRows: (p) => {
                api.showLoadingOverlay();

                const limit = p.endRow - p.startRow;
                const { sort, colId } = p.sortModel[0];
                const keyword = document.getElementById('filter-text-box').value;

                callApi({
                    url: `{{ route('product.query') }}?keyword=${ keyword }&limit=${ limit }&offset=${ p.startRow }&order=${ sort }&order_by=${ colId }`,
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
@endsection
