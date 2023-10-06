<div class="row d-flex gap-2">
    <div class="col">
        <form action="{{ route('category.'. $mode, $mode === 'update'? [ 'id' => $category->id ] : null) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card border-0 p-4">
                <div class="fw-medium pb-5 f18">Informasi Service</div>
    
                <div class="d-flex pb-5 gap-5">
                    <div class="w-25 flex-shrink-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="fw-medium text-gray">Nama Kategori</div>
            
                            <div class="badge text-bg-success required-badge fw-medium">Wajib</div>
                        </div>
            
                        <div class="f14 pt-2">
                            Judul akan ditampilkan pada setiap halaman yang dituju.
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
