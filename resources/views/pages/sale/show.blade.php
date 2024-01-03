@extends('layouts.app')
@section('title', 'Transaksi')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
@endsection

@section('content')
    <div class="d-flex flex-column gap-4 h-100">
        <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
            <h4 class="m-0">Invoice {{ $sale->sale_no }}</h4>
            {{--
                    <a class="btn btn-primary d-flex align-items-center gap-2 btn-sm" href="{{ route('product.store') }}">
                        <ion-icon name="add" class="f24"></ion-icon>
                        Tambah Barang
                    </a> --}}
        </div>

        <div class="row">
            <div class="col-9">
                <div class="card border-0 flex-grow-1 d-flex flex-column h-100">
                    <div class="border-bottom px-4 pt-4 pb-3 flex-shrink-0">
                        {{-- <div class="position-relative search-box">
                            <ion-icon name="search" class="f24 position-absolute"></ion-icon>
                            <input type="text" id="filter-text-box" class="form-control" placeholder="Ketik untuk mencari..." onchange="search()">
                        </div> --}}
                        <div class="col-12 mb-4">
                        </div>

                        <div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->lines as $line)
                                        <tr>
                                            <td>{{ $line->product->code }}</td>
                                            <td>{{ $line->product->name }}</td>
                                            <td>{{ $line->qty }}</td>
                                            <td>{{ 'Rp' . number_format($line->sale_price, 0, '', '.') }}</td>
                                            <td>{{ 'Rp' . number_format($line->subtotal, 0, '', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="p-1 flex-grow-1">
                        <div id="grid" class="ag-theme-alpine" style="height: 500px"></div>
                    </div>


                </div>
            </div>
            <div class="col">
                <div class="card border-0 h-100 p-3">
                    <div class="d-flex flex-column rounded-2 p-3 pb-3" style="background: #DFDFDF">
                        <div class="d-flex justify-content-between">
                            <h6>Tanggal</h6>
                            <h6>{{ $sale->created_at->isoFormat('D MMMM Y') }}</h6>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <h6>Waktu</h6>
                            <h6>{{ $sale->created_at->isoFormat('hh.mm') }}</h6>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <h6>Kasir</h6>
                            <h6>{{ $sale->cashier }}</h6>
                        </div>
                    </div>
                    <div class="d-flex flex-column rounded-2 p-3 pb-3 my-3 border-bottom border-top">
                        <div class="d-flex justify-content-between">
                            <h5>Total</h5>
                            <h5 id="text-total-product">{{ count($sale->lines) }} Barang</h5>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h3 class="fw-bold" id="text-total">{{ 'Rp' . number_format($sale->grandtotal, 0, '', '.') }}</h3>
                        </div>
                    </div>
                    <div class="d-flex flex-column rounded-2 px-3">
                        <div class="d-flex justify-content-between">
                            <h5>Bayar : {{ 'Rp' . number_format($sale->cash_amount, 0, '', '.') }}</h5>
                        </div>
                    </div>
                    <div class="d-flex flex-column rounded-2 px-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <h5>Sisa : {{ 'Rp' . number_format($sale->change_amount, 0, '', '.') }}</h5>
                        </div>
                    </div>
                </div>
                @csrf
            </div>
        </div>
    </div>

@endsection
