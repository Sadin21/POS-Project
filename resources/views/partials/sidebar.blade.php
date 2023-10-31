<a class="sidebar-menu text-decoration-none text-body position-relative mb-1 d-flex align-items-center gap-2 user-select-none cursor-pointer py-2 px-3 {{ str_contains(Route::currentRouteName(), 'sale')? 'active' : '' }}" href="{{ route('sale.index') }}">
    <ion-icon name="home" class="f20"></ion-icon>
    <div class="fw-medium">Transaksi</div>
</a>

<div class="sidebar-menu position-relative mb-1 d-flex align-items-center justify-content-between gap-2 user-select-none cursor-pointer py-2 px-3" data-bs-toggle="collapse" data-bs-target="#product-item" aria-expanded="true" aria-controls="product-item">
    <div class="d-flex align-items-center gap-2">
        <ion-icon name="cube" class="f20"></ion-icon>
        <div class="fw-medium">Kelola Barang</div>
    </div>
    
    <ion-icon name="chevron-up" class="f20"></ion-icon>
</div>
<div class="collapse show ps-4" id="product-item">
    {{-- <a class="sidebar-menu-item text-decoration-none text-body d-block px-2 py-1 f14 mb-1 user-select-none cursor-pointer">
        Tambah Produk
    </a> --}}

    <a class="sidebar-menu-item text-decoration-none text-body d-block px-2 py-1 f14 mb-1 user-select-none cursor-pointer {{ str_contains(Route::currentRouteName(), 'product')? 'active' : '' }}" href="{{ route('product.index') }}">
        Daftar Barang
    </a>

    <a class="sidebar-menu-item text-decoration-none text-body d-block px-2 py-1 f14 mb-1 user-select-none cursor-pointer {{ str_contains(Route::currentRouteName(), 'category')? 'active' : '' }}" href="{{ route('category.index') }}">
        Kategori Barang
    </a>
</div>

<a class="sidebar-menu text-decoration-none text-body position-relative mb-1 d-flex align-items-center gap-2 user-select-none cursor-pointer py-2 px-3 {{ str_contains(Route::currentRouteName(), 'transaksi')? 'active' : '' }}" href="{{ route('transaction.index') }}">
    <ion-icon name="bar-chart" class="f20"></ion-icon>
    <div class="fw-medium">Laporan Penjualan</div>
</a>

{{-- <a class="sidebar-menu text-decoration-none text-body position-relative mb-1 d-flex align-items-center gap-2 user-select-none cursor-pointer py-2 px-3 {{ str_contains(Route::currentRouteName(), 'transaksi')? 'active' : '' }}" href="{{ route('transaction.download') }}">
    <ion-icon name="home" class="f20"></ion-icon>
    <div class="fw-medium">Dowload Laporan</div>
</a> --}}

<div class="border-bottom pt-1 mb-1 mx-2"></div>
@if (auth()->user()->role_id === 1)
    <a class="sidebar-menu text-decoration-none text-body position-relative mb-1 d-flex align-items-center gap-2 user-select-none cursor-pointer py-2 px-3 {{ str_contains(Route::currentRouteName(), 'user')? 'active' : '' }}" href="{{ route('user.index') }}">
        <ion-icon name="person-circle" class="f20"></ion-icon>
        <div class="fw-medium">Kelola Akun</div>
    </a>
@endif
