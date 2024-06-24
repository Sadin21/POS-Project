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
        <div class=" px-4 pt-4 pb-3 flex-shrink-0 d-flex gap-3 align-items-center">
            <div class="position-relative search-box">
                <ion-icon name="search" class="f24 position-absolute"></ion-icon>
                <input type="text" id="search-data" class="form-control" placeholder="Cari nama kategori">
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
        <table class="table w-100 border" id="category-table" style="border-radius: 10px">
            <thead>
                <tr style="background-color: #F8F8F8">
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Tanggal Buat</th>
                    <th width="180px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('partials.ag-grid.aggrid')
@include('partials.ag-grid.aggrid-default-btn')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

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
        $('#category-table').DataTable().destroy();

        $.ajax({
            url: `{{ route('category.query') }}?name=${searchData?? 0}`,
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                originalData = res.data;
                var table = $('#category-table').DataTable({
                    data: originalData,
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'nama_kategori'},
                        {data: 'created_at', name: 'tanggal_buat'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <a class="btn btn-sm btn-light border border-1" href="#" onclick="editRow(${row.id})">Ubah</a>
                                    <a class="btn btn-sm btn-primary" href="#" onclick="detailRow(${row.id})">Detail</a>
                                    <a class="btn btn-sm btn-danger" href="#" onclick="hapusRow(${row.id}, event)">Hapus</a>
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
        window.location.href = (baseUrl + "/category/:id").replace(':id', id);
    }

    function detailRow(id) {
        Swal.fire({
            title: "Pnecarian data produk",
            text: "Mohon tunggu sebentar...",
            showConfirmButton: false,
            didOpen: () => {
                axios.get(`/api/category/detail-product/${id}`)
                    .then(response => {
                        const products = response.data.products;

                        if (products.length > 0) {
                            let htmlContent = '<div>';

                            products.forEach(productData => {
                                htmlContent += `
                                    <p>${productData.name}</p>
                                    `;
                            });

                            htmlContent += '</div>';

                            Swal.fire({
                                title: "Detail Produk",
                                html: htmlContent,
                            });
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'Oops...',
                                text: 'Tidak ada produk yang terdaftar!',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        Swal.fire({
                            icon: 'error',
                            // title: 'Oops...',
                            text: 'Tidak ada produk yang terdaftar!',
                        });
                    });
            }
        });
    }

    function hapusRow(id, event) {
        event.preventDefault();
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
                    url: deleteCategoryUrl.replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success == false) {
                            Swal.fire('Gagal', data.message, 'error');
                            return;
                        }

                        Swal.fire('Berhasil!', data.message, 'success').then(() => {
                            location.reload();
                        });
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
