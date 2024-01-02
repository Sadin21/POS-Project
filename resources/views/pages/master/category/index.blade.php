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

<script>
    $.ajax({
        url: `{{ route('category.query') }}`,
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

    function editRow(id) {
        window.location.href = `{{ route('category.update', 'id') }}`.replace('id', id);
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
                    url: `{{ route('category.delete', ['id' => 'id']) }}`.replace('id', id),
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
</script>
@endsection
