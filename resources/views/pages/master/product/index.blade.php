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
        <div class=" px-4 pt-4 pb-3 flex-shrink-0 d-flex gap-3 align-items-center">
            <div class="position-relative search-box">
                <ion-icon name="search" class="f24 position-absolute"></ion-icon>
                <input type="text" id="search-data" class="form-control" placeholder="Cari nama atau kode barang">
            </div>
            <div class="d-flex">
                <div class="pt-4 pb-3 flex-shrink-0">
                    <button id="btn-filter" class="btn btn-primary">Filter</button>
                </div>
                <div class="pt-4 pb-3 ms-2 flex-shrink-0">
                    <button id="btn-cancel" class="btn btn-primary">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="p-1 flex-grow-1 p-4 w-100">
        <table class="table w-100 border" id="product-table" style="border-radius: 10px">
            <thead>
                <tr style="background-color: #F8F8F8">
                    <th>Kode Barang</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Foto</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok Total</th>
                    <th>Stok Tersedia</th>
                    <th>Tanggal Buat</th>
                    <th width="180px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>        
    </div>
</div>

@include('partials.ag-grid.aggrid-product')
@include('partials.ag-grid.aggrid-default-btn')

<script>

    var filteredDate = {};
    var doneTypeInterval = 500;
    var typingTimer;

    document.getElementById('search-data').addEventListener('input', getInputSearchValue);

    document.getElementById('btn-filter').addEventListener('click', findDataOnTable);
    document.getElementById('btn-cancel').addEventListener('click', cancelData);

    function getInputSearchValue() {
        clearTimeout(typingTimer);
        searchData = document.getElementById('search-data').value;
        typingTimer = setTimeout(() => {
            searchData = searchData.trim().toLowerCase();
        }, doneTypeInterval);
    }

    function findDataOnTable() {
        if (searchData) {
            showData(searchData);
        } else {
            showData();
        }
    }

    function cancelData() {
        document.getElementById('search-data').value = '';

        showData();
    }

    function showData(searchData) {
        $('#product-table').DataTable().destroy();

        console.log(searchData);
        
        $.ajax({
            url: `{{ route('product.query') }}?name=${searchData?? 0}&code=${searchData?? 0}`,
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                originalData = res.data;

                var rotationTable = $('#product-table').DataTable({
                    data: originalData,
                    columns: [
                        {data: 'code', name: 'kode_barang'},
                        {data: 'name', name: 'nama'},
                        {data: 'category_name', name: 'kategori'},
                        {
                            data: 'photo', 
                            name: 'foto',
                            render: function (data) {
                                return `
                                    <img src="/assets/imgs/${data}" class="img-fluid border border-2 border-primary rounded w-40 h-50" alt="">
                                `;
                            }
                        },
                        {data: 'buy_price', name: 'harga_beli'},
                        {data: 'sale_price', name: 'harga_jual'},
                        {data: 'qty', name: 'stok_total'},
                        {data: 'available_qty', name: 'stok_barang_tersedia'},
                        {data: 'created_at', name: 'tanggal_buat'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <a class="btn btn-sm btn-light border border-1" href="#" onclick="editRow(${row.id})">Ubah</a>
                                    <a class="btn btn-sm btn-danger" href="#" onclick="hapusRow(${row.id})">Hapus</a>
                                `;
                            }
                        },
                    ],
                    'searching': false,
                    'responsive': (screen.width > 960) ? true : false,
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                throw new Error(errorThrown);
            }
        });
    }

    function editRow(id) {
        window.location.href = `{{ route('product.update', 'id') }}`.replace('id', id);
    }

    function hapusRow(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Anda yakin ingin menghapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: `{{ route('product.delete', ['id' => 'id']) }}`.replace('id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        Swal.fire('Berhasil!', data.message, 'success');
                        location.reload(true);
                    },
                    error: function (error) {
                        Swal.fire('Gagal', error.responseJSON.message, 'error');
                    }
                });
            }
        });
    }

    showData();
</script>
@endsection
