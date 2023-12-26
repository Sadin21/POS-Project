@extends('layouts.app')
@section('title', 'User')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4 align-content-center">
        <h4 class="m-0">Daftar Akun</h4>
        <a class="btn btn-primary d-flex align-items-center gap-2" href="{{ route('user.store') }}">
            <ion-icon name="add" class="f24"></ion-icon>
            Tambah Akun
        </a>
    </div>
    <div class="card border-0 flex-grow-1 d-flex flex-column h-100 mt-4" id="table">
        <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
            <div class=" px-4 pt-4 pb-3 flex-shrink-0">
                <div class="position-relative search-box">
                    <ion-icon name="search" class="f24 position-absolute"></ion-icon>
                    <input type="text" id="filter-text-box" class="form-control" placeholder="Ketik untuk mencari..."
                        onchange="search()">
                </div>
            </div>
        </div>

        <div class="p-1 flex-grow-1 p-4 w-100">
            <table class="table w-100 border" id="user-table" style="border-radius: 10px">
                <thead>
                    <tr style="background-color: #F8F8F8">
                        <th>Nip</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Alamat</th>
                        <th>Nomor HP</th>
                        <th>Foto</th>
                        <th>Role</th>
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
    @include('partials.ag-grid.aggrid-default-user-btn')

    <script>
        $.ajax({
            url: `{{ route('user.query') }}`,
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                originalData = res.data;

                var rotationTable = $('#user-table').DataTable({
                    data: originalData,
                    columns: [
                        {data: 'nip', name: 'nip'},
                        {data: 'name', name: 'nama'},
                        {data: 'username', name: 'username'},
                        {data: 'address', name: 'alamat'},
                        {data: 'phone', name: 'nomor_hp'},
                        {
                            data: 'photo', 
                            name: 'foto',
                            render: function (data) {
                                return `
                                    <img src="/assets/imgs/${data}" class="img-fluid border border-2 border-primary rounded w-40 h-50" alt="">
                                `;
                            }
                        },
                        {data: 'role_name', name: 'role'},
                        {data: 'created_at', name: 'tanggal_buat'},
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <a class="btn btn-sm btn-light border border-1" href="#" onclick="editRow(${row.nip})">Ubah</a>
                                    <a class="btn btn-sm btn-primary" href="#" onclick="resetRow(${row.nip})">Reset</a>
                                    <a class="btn btn-sm btn-danger" href="#" onclick="hapusRow(${row.nip})">Hapus</a>
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

        function editRow(nip) {
            window.location.href = `{{ route('user.update', 'nip') }}`.replace('nip', nip);
        }

        function resetRow(nip) {
            Swal.fire({
                title: 'Apakah anda yakin untuk mengubah password ?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),
                preConfirm: async () => {
                    try {
                        const url = window.location.origin + `/user/${nip}/reset`;
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                        });
                        if (!response.ok) {
                            return Swal.showValidationMessage(
                                `${JSON.stringify(await response.json())}`);
                        }
                        return response.json();
                    } catch (error) {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    }
                }
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Password berhasil diubah',
                        icon: 'success',
                        text: result.value.data,
                        showConfirmButton: false,
                    })
                }
            });
        }

        function hapusRow(nip) {
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
                        url: `{{ route('user.delete', ['id' => 'id']) }}`.replace('id', nip),
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
