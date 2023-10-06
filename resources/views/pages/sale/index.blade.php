@extends('layouts.app')
@section('title', 'Transaksi')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endsection

@section('content')
<div class="d-flex flex-column gap-4 h-100">
    <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
        <h4 class="m-0">Transaksi Barang</h4>
{{-- 
        <a class="btn btn-primary d-flex align-items-center gap-2 btn-sm" href="{{ route('product.store') }}">
            <ion-icon name="add" class="f24"></ion-icon>
            Tambah Barang
        </a> --}}
    </div>

    <div class="row">
        <div class="col-9">
            <div class="card border-0 flex-grow-1 d-flex flex-column h-100">
                <div class="border-bottom px-4 pt-4 pb-3 flex-shrink-0">
                    {{-- <div class="position-relative search-box">
                        <ion-icon name="search" class="f24 position-absolute"></ion-icon>
                        <input type="text" id="filter-text-box" class="form-control" placeholder="Ketik untuk mencari..." onchange="search()">
                    </div> --}}
                    <div class="col-12 mb-4">
                        <label class="form-label" for="item">Produk</label>
                        <select id="product-select" class="w-100"></select>
                    </div>
                </div>
        
                <div class="p-1 flex-grow-1">
                    <div id="grid" class="ag-theme-alpine" style="height: 500px"></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 h-100 p-3">
                <div class="d-flex flex-column rounded-2 p-3 pb-3" style="background: #DFDFDF">
                    <div class="d-flex justify-content-between">
                        <h6>Tanggal</h6>
                        <h6>03 Oktober 2023</h6>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <h6>Waktu</h6>
                        <h6>20.53</h6>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <h6>Kasir</h6>
                        <h6>Daffa</h6>
                    </div>
                </div>
                <div class="d-flex flex-column rounded-2 p-3 pb-3 my-3 border-bottom border-top">
                    <div class="d-flex justify-content-between">
                        <h5>Subtotal</h5>
                        <h5>3 Barang</h5>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h3 class="fw-bold">300.000</h3>
                    </div>
                </div>
                <div class="d-flex flex-column rounded-2 px-3">
                    <div class="d-flex justify-content-between">
                        <h5>Bayar</h5>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Rp</span>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                      </div>
                </div>
                <div class="d-flex flex-column rounded-2 px-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <h5>Sisa</h5>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Rp</span>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                </div>
                <button type="button" class="btn btn-primary mt-3">Bayar</button>
            </div>
        </div>
    </div>
</div>

@include('partials.ag-grid.aggrid')
@include('partials.ag-grid.aggrid-default-btn')

<script>
    gridOptions.columnDefs = [
        { field: 'code', headerName: 'Kode Barang' },
        { field: 'name', headerName: 'Nama Barang' },
        { field: 'qty', headerName: 'Jumlah Barang'},
        { field: 'price', headerName: 'Total Harga' },
        { field: 'created_at', headerName: 'Tanggal Buat', valueFormatter: ({ value }) => formatDateTime(value), sort: 'desc' },
        { field: 'action', headerName: 'Aksi', minWidth: 200, sortable: false, cellRenderer: AgGridDefaultBtn, cellRendererParams: {
            canDelete: true,
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

    $(document).ready(function() {
        $('#product-select').select2({
            allowClear: true,
            placeholder: 'Pilih Produk',
            ajax: {
                delay: 250,
                url: `{{ route('product.query') }}`,
                data: ({ term }) => ({ keyword: term, limit: 20, offset: 0, order: 'ASC', orderBy: 'name' }),
                processResults: ({ data }) => ({
                    results: $.map(data, ({ id, name, barcode, sell_price }) => ({
                        id: id,
                        text: name,
                        name, barcode, sell_price
                    }))
                })
            }
        });
    });
</script>

<script src="{{ asset('assets/js/select2.min.js') }}"></script>

@endsection
