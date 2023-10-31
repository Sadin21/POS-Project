@php
    $title = ($mode === 'store'? 'Tambah' : 'Ubah'). ' Barang';
@endphp

@extends('layouts.app')
@section('title', $title)

@section('content')
<div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4 align-content-center">
    <h4 class="mb-3 pb-1">{{ $title }}</h4>
</div>

<div class="flex-grow-1 d-flex flex-column h-100" id="grap">
    <div class="flex-grow-1">
        <div class="row gap-4">
            <div class="col card p-4">
                <form action="{{ route('product.'. $mode, $mode === 'update'? [ 'id' => $product->id ] : null) }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                            <div class="flex-grow-1 d-flex gap-3">
                                <div>
                                    <input type="file" name="photo" id="photo" class="d-none" />
                                    <label for="photo" class="img-selector pc-logo">
                                        <ion-icon name="add"></ion-icon>
                                    </label>
                                </div>
                                <div id="img-preview" style="width: 135px; height: 60px">
                                    <img src="/assets/imgs/{{ isset($user) ? $user->photo : '' }}" class="img-fluid border border-2 border-primary rounded" alt="">
                                </div>
                            </div>
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
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} {{ isset($product) && $product->category_id === $c->name ? 'selected' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-4">
                            <a class="btn form-btn btn-outline-danger text-decoration-none" href="{{ route('product.index') }}">
                                Batalkan
                            </a>
                            
                            <button class="btn form-btn btn-primary" type="submit">
                                Simpan
                            </button>
                        </div>
                </form>
            </div>
            <div class="col d-flex flex-column">
                <div class="card p-4" style="margin-bottom: 40px">
                    <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="fw-medium pb-5 f18">Upload File Excel</div>
        
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
                            {{-- <a class="btn form-btn btn-outline-danger text-decoration-none" href="{{ route('product.index') }}">
                                Batalkan
                            </a> --}}
                            
                            <button class="btn form-btn btn-primary" type="submit">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
    <script>
        const chooseFile = document.getElementById("photo");
        const imgPreview = document.getElementById("img-preview");
    
        chooseFile.addEventListener("change", function () {
            getImgData();
        });
    
        function getImgData() {
            const files = chooseFile.files[0];
            if (files) {
                const fileReader = new FileReader();
                fileReader.readAsDataURL(files);
                fileReader.addEventListener("load", function () {
                    let imgElement = document.createElement("img");
                    imgElement.setAttribute("src", this.result);
                    imgElement.setAttribute("class", "img-fluid border border-2 border-primary rounded");
                    imgPreview.innerHTML = "";
                    imgPreview.appendChild(imgElement);
                });
            }
        }
    </script>
@endsection
