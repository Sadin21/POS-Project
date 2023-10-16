@php
    $title = ($mode === 'store'? 'Tambah' : 'Ubah'). ' Barang';
@endphp

@extends('layouts.app')
@section('title', $title)

@section('content')
<h4 class="mb-3 pb-1">{{ $title }}</h4>

<div class="row d-flex gap-2">
    <div class="col">
        <form action="{{ route('product.'. $mode, $mode === 'update'? [ 'id' => $product->id ] : null) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card border-0 p-4">
                <div class="fw-medium pb-5 f18">Informasi Barang</div>
        
                <div class="d-flex pb-5 gap-5">
                    <div class="w-25 flex-shrink-0">
                        <div class="align-items-center gap-2">
                            <div class="fw-medium text-gray">Nama Barang</div>
            
                            <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                        </div>
                    </div>
                    <input type="text" placeholder="Masukkan Nama Barang" class="form-control" id="name" name="name" required value="{{ isset($product) ? $product->name : '' }}">
                </div>
        
                <div class="d-flex pb-5 gap-5">
                    <div class="w-25 flex-shrink-0">
                        <div class="align-items-center gap-2">
                            <div class="fw-medium text-gray">Kode Barang</div>
            
                            <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex align-items-center">
                        <div class="flex-grow-1 pe-4">
                            <input type="text" placeholder="Masukkan Kode Barang" class="form-control" name="code" id="code" required value="{{ isset($product) ? $product->code : '' }}">
                        </div>
                        <button class="btn btn-outline-primary btn-sm flex-shrink-0 me-1" id="btn" type="button" onclick="generateCode()">
                            <ion-icon name="qr-code-outline" class="f24 py-1" id="icon"></ion-icon>
                        </button>
                    </div>
                </div>
        
                <div class="d-flex pb-5 gap-5">
                    <div class="w-25 flex-shrink-0">
                        <div class="align-items-center gap-2">
                            <div class="fw-medium text-gray">Foto</div>
            
                            <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                        </div>
                    </div>
                    <input type="file" placeholder="" class="form-control" id="photo" name="photo" {{ $mode === 'store' ?? 'required' }} value="">
                </div>
        
                <div class="d-flex pb-5 gap-5">
                    <div class="w-25 flex-shrink-0">
                        <div class="align-items-center gap-2">
                            <div class="fw-medium text-gray">Harga Jual</div>
            
                            <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text" id="sale_price">Rp</span>
                        <input type="number" placeholder="Masukkan Harga Barang" class="form-control" id="sale_price" name="sale_price" required value="{{ isset($product) ? $product->sale_price : '' }}" aria-describedby="sale_price">
                    </div>
                </div>
        
                <div class="d-flex pb-5 gap-5">
                    <div class="w-25 flex-shrink-0">
                        <div class="align-items-center gap-2">
                            <div class="fw-medium text-gray">Kuantitas</div>
            
                            <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                        </div>
                    </div>
                    <input type="number" placeholder="Masukkan Jumlah Barang" class="form-control" id="qty" name="qty" required value="{{ isset($product) ? $product->qty : '' }}">
                </div>
        
                <div class="d-flex pb-5 gap-5">
                    <div class="w-25 flex-shrink-0">
                        <div class="align-items-center gap-2">
                            <div class="fw-medium text-gray">Kategori Barang</div>
            
                            <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                        </div>
                    </div>
                    <select class="form-select" name="category_id">
                        {{-- <option value="">Pilih Kategori Barang</option> --}}
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} {{ isset($product) && $product->category_id === $c->name ? 'selected' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center justify-content-end gap-4 pt-3">
                    <a class="btn form-btn btn-outline-danger text-decoration-none" href="{{ route('product.index') }}">
                        Batalkan
                    </a>
                    
                    <button class="btn form-btn btn-primary" type="submit">
                        Simpan
                    </button>
                </div>
            </div>
            
        </form>
    </div>
    <div class="col">
        <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card border-0 p-4">
                <div class="fw-medium pb-5 f18">Upload File Data Barang</div>

                <div class="d-flex pb-5 gap-5">
                    <div class="w-25 flex-shrink-0">
                        <div class="align-items-center gap-2">
                            <div class="fw-medium text-gray">File .csv/xls/xlsx</div>
            
                            <div class="badge text-bg-secondary required-badge fw-medium">Optional</div>
                        </div>
                    </div>
                    <input type="file" placeholder="" class="form-control" id="file" name="file" required value="">
                </div>
                <div class="d-flex align-items-center justify-content-end gap-4 pt-3">
                    <a class="btn form-btn btn-outline-danger text-decoration-none" href="{{ route('product.index') }}">
                        Batalkan
                    </a>
                    
                    <button class="btn form-btn btn-primary" type="submit">
                        Simpan
                    </button>
                </div>
            </div>
            
        </form>
    </div>
</div>
@endsection

@section('script')
    <script>
        function generateCode() {
            const codeInput = document.getElementById("code");
            const length = 6;

            const randomCode = Array.from({ length }, () => {
                const charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                const randomIndex = Math.floor(Math.random() * charset.length);
                return charset[randomIndex];
            }).join("");

            codeInput.value = randomCode;
        }
    </script>
@endsection
