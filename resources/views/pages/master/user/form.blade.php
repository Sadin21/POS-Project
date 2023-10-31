@php
    $title = ($mode === 'store'? 'Tambah' : 'Ubah'). ' Akun';
@endphp

@extends('layouts.app')
@section('title', $title)

@section('content')
<div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4 align-content-center">
    <h4 class="mb-3 pb-1">{{ $title }}</h4>
</div>

<div class="flex-grow-1 d-flex flex-column h-100" id="grap">
    <div class="flex-grow-1">
        <form action="{{ route('user.'. $mode, $mode === 'update'? [ 'nip' => $user->nip ] : null) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row gap-4">
                <div class="col card p-4 h-100">
                    <div class="d-flex pb-5 gap-5">
                        <div class="w-25 flex-shrink-0">
                            <div class="align-items-center gap-2">
                                <div class="fw-medium text-gray">NIP</div>
                
                                <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                            </div>
                        </div>
                        <input type="text" placeholder="Masukkan NIP" class="form-control" id="nip" name="nip" required value="{{ isset($user) ? $user->nip : '' }}">
                    </div>
    
                    <div class="d-flex pb-5 gap-5">
                        <div class="w-25 flex-shrink-0">
                            <div class="align-items-center gap-2">
                                <div class="fw-medium text-gray">Nama</div>
                
                                <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                            </div>
                        </div>
                        <input type="text" placeholder="Masukkan Nama" class="form-control" id="name" name="name" required value="{{ isset($user) ? $user->name : '' }}">
                    </div>
            
                    <div class="d-flex pb-5 gap-5">
                        <div class="w-25 flex-shrink-0">
                            <div class="align-items-center gap-2">
                                <div class="fw-medium text-gray">Username</div>
                
                                <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                            </div>
                        </div>
                        <input type="text" placeholder="Masukkan Username" class="form-control" id="username" name="username" required value="{{ isset($user) ? $user->username : '' }}">
                    </div>
    
                    <div class="d-flex pb-5 gap-5">
                        <div class="w-25 flex-shrink-0">
                            <div class="align-items-center gap-2">
                                <div class="fw-medium text-gray">Alamat</div>
                
                                <div class="badge text-bg-secondary required-badge fw-medium">Opsional</div>
                            </div>
                        </div>
                        <textarea class="form-control" placeholder="Masukkan Alamat" id="address" name="address" style="height: 100px">{{ isset($user) ? $user->address : '' }}</textarea>
                        {{-- <input type="text" placeholder="Masukkan Alamat" class="form-control" id="address" name="address" required value="{{ isset($user) ? '' : '' }}"> --}}
                    </div>
            
                    <div class="d-flex pb-5 gap-5">
                        <div class="w-25 flex-shrink-0">
                            <div class="align-items-center gap-2">
                                <div class="fw-medium text-gray">Foto</div>
                
                                <div class="badge text-bg-secondary required-badge fw-medium">Opsional</div>
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
                </div>
                <div class="col card p-4 h-100">
                    <div class="d-flex align-items-start flex-column h-100">
                        <div class="d-flex pb-5 gap-5" style="width: 100%" style="width: 100%">
                            <div class="w-25 flex-shrink-0">
                                <div class="align-items-center gap-2">
                                    <div class="fw-medium text-gray">Nomor HP</div>
                    
                                    <div class="badge text-bg-secondary required-badge fw-medium">Opsional</div>
                                </div>
                            </div>
                            <input type="text" placeholder="Masukkan Nomor HP" class="form-control" id="phone" name="phone" value="{{ isset($user) ? $user->phone : '' }}">
                        </div>
                
                        @if ($mode == 'store')
                        <div class="d-flex pb-5 gap-5" style="width: 100%">
                            <div class="w-25 flex-shrink-0">
                                <div class="align-items-center gap-2">
                                    <div class="fw-medium text-gray">Password</div>
                    
                                    <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                                </div>
                            </div>
                            <input type="text" placeholder="Masukkan Password" class="form-control" id="password" name="password" required value="{{ isset($user) ? $user->password : '' }}">
                        </div> 
                        @endif
        
                        <div class="d-flex pb-5 gap-5" style="width: 100%">
                            <div class="w-25 flex-shrink-0">
                                <div class="align-items-center gap-2">
                                    <div class="fw-medium text-gray">Role</div>
                    
                                    <div class="badge text-bg-primary required-badge fw-medium">Wajib</div>
                                </div>
                            </div>
                            <select class="form-select" name="role_id">
                                @foreach ($roles as $r)
                                <option value="{{ $r->role_id }}" {{ (isset($user) && $user->role_id == $r->role_id) ? 'selected' : '' }}>
                                    {{ $r->name }}
                                </option>>
                                @endforeach
                            </select>
                        </div>
    
                        @if ($mode == 'update')
                        <div class="d-flex pb-5 gap-5" style="width: 100%">
                            <div class="w-25 flex-shrink-0">
                                <div class="align-items-center gap-2">
                                    <div class="fw-medium text-gray">Ubah Password</div>
                    
                                    <div class="badge text-bg-secondary required-badge fw-medium">Opsional</div>
                                </div>
                            </div>
                            <input type="text" placeholder="Masukkan Password Baru" class="form-control" id="password" name="password" required value="">
                        </div> 
                        @endif
                    </div>
    
                    <div class="d-flex-align-items-end">
                        <div class="d-flex align-items-center justify-content-end gap-4 pt-3">
                            <a class="btn form-btn btn-outline-danger text-decoration-none" href="{{ route('user.index') }}">
                                Batalkan
                            </a>
                            
                            <button class="btn form-btn btn-primary" type="submit">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
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
