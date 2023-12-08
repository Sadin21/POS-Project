@extends('layouts.app')
@section('title', 'Category')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4 align-content-center">
    <h4 class="m-0">Daftar Kategori Barang</h4>
</div>

<div class="row d-flex gap-2 my-4">
    <div class="col">
        <form action="{{ route('category.'. $mode, $mode === 'update'? [ 'id' => $category->id ] : null) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card border-0 p-4">
                <div class="fw-medium pb-5 f18">Tambah Kategori</div>
    
                <div class="d-flex pb-5 gap-5">
                    <div class="w-25 flex-shrink-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="fw-medium text-gray">Nama Kategori</div>
            
                            <div class="badge text-bg-success required-badge fw-medium">Wajib</div>
                        </div>
            
                        <div class="f14 pt-2">
                            *untuk ubah nama kategori klik "ubah" pada daftar kategori lalu ubah pada form disamping
                        </div>
                    </div>
            
                    <div class="flex-grow-1">
                        <input type="text" placeholder="Contoh: Olahraga" class="form-control" id="name" name="name" required value="{{ isset($category) ? $category->name : '' }}">
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end gap-4 pt-3">
                <a class="btn form-btn btn-outline-danger text-decoration-none" href="{{ route('category.index') }}">
                    Batalkan
                </a>
                
                <button class="btn form-btn btn-primary" type="submit">
                    Simpan
                </button>
            </div>
        </form>
    </div>
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
        { field: 'id', headerName: 'ID' },
        { field: 'name', headerName: 'Nama Barang' },
        { field: 'created_at', headerName: 'Tanggal Buat', valueFormatter: ({ value }) => formatDateTime(value), sort: 'desc' },
        { field: 'action', headerName: 'Aksi', minWidth: 200, sortable: false, cellRenderer: AgGridDefaultBtn, cellRendererParams: {
            canShowDetail: true,
            canUpdate: true,
            canDelete: true,
            detailUrl: `{{ route('category.detail-product', 'id') }}`,
            updateUrl: `{{ route('category.update', 'id') }}`,
            deleteUrl: `{{ route('category.delete') }}`,
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
                    url: `{{ route('category.query') }}?keyword=${ keyword }&limit=${ limit }&offset=${ p.startRow }&order=${ sort }&order_by=${ colId }`,
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
