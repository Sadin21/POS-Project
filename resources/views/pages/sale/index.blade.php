@extends('layouts.app')
@section('title', 'Transaksi')

@section('style')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesh
    eet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('content')
    <div class="d-flex flex-column gap-4 h-100">
        <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
            <h4 class="m-0">Transaksi Barang</h4>
        </div>

        <div class="row">
            <div class="col-9">
                <div class="card border-0 flex-grow-1 d-flex flex-column h-100">
                    <div class="border-bottom px-4 pt-4 pb-3 flex-shrink-0">
                        <div class="col-12 mb-4">
                            <label class="form-label" for="item">Produk</label>
                            <select id="product-select" class="form-control" class="w-100">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                            data-code="{{ $product->code }}" data-price="{{ $product->sale_price }}"
                                            data-qty="{{ $product->available_qty }}">
                                        {{ $product->code  }} - {{ $product->name }}
                                    </option>
                                @endforeach
                                <option value="Cari"></option>
                            </select>
                            <button id="add-cart" class="btn btn-primary mt-2">Tambah ke Keranjang</button>
                        </div>

                        <div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody id="cart">
                                <!-- Data keranjang akan ditampilkan di sini -->
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
                            <h6 id="dateNow">Tanggal</h6>
                            <!-- <p id="p1">Hello World!</p> -->
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <h6>Waktu</h6>
                            <h6 id="timeNow">20.53</h6>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <h6>Kasir</h6>
                            <h6>{{ auth()->user()->name }}</h6>
                        </div>
                    </div>
                    <div class="d-flex flex-column rounded-2 p-3 pb-3 my-3 border-bottom border-top">
                        <div class="d-flex justify-content-between">
                            <h5>Total</h5>
                            <h5 id="text-total-product">0 Barang</h5>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h3 class="fw-bold" id="text-total"></h3>
                        </div>
                    </div>
                    <div class="d-flex flex-column rounded-2 px-3">
                        <div class="d-flex justify-content-between">
                            <h5>Metode Pembayaran</h5>
                        </div>
                        <div class="input-group mb-3">
                            <select name="payment" id="payment" class="form-select">
                                <option value="cash" {{ old('payment') == 'cash' ? 'selected' : (empty(old('payment')) ? 'selected' : '') }}>Cash</option>
                                <option value="qris" {{ old('payment') == 'qris' ? 'selected' : '' }}>Qris</option>
                                <option value="debit" {{ old('payment') == 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-column rounded-2 px-3">
                        <div class="d-flex justify-content-between">
                            <h5>Bayar</h5>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="input-pay">
                        </div>
                    </div>
                    <div class="d-flex flex-column rounded-2 px-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <h5>Sisa</h5>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="input-return">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-3" id="btn-save">Bayar</button>
                </div>
                @csrf
            </div>
        </div>
    </div>

    <script>


        $(document).ready(function () {

            let currentDate = new Date().toJSON().slice(0, 10);
            let date = new Date();

            document.getElementById('dateNow').innerText = currentDate;
            document.getElementById('timeNow').innerText = date.toLocaleTimeString().slice(0, 4);

            function calculate() {
                const rowElements = $("#cart tr")
                let totalProduct = 0;
                let total = 0;

                rowElements.each(function () {
                    const totalPrice = $(this).data("total-price");
                    if (!isNaN(totalPrice)) {
                        total += parseFloat(totalPrice);
                    }
                    totalProduct += parseInt($(this).find("input[name='jumlah']").val());
                });

                $("#text-total-product").text(totalProduct + " Barang");
                $("#text-total").text(total);
            }

            $("#add-cart").on('click', function () {
                const selectedOption = $("#product-select option:selected");
                const code = selectedOption.data("code");
                const name = selectedOption.data("name");
                const price = selectedOption.data("price");
                const qty = selectedOption.data("qty");
                const iteration = $("#cart tr").length + 1;

                const rowId = "row-" + code;
                if ($("#" + rowId).length > 0) return;

                const jumlahInput = `<input type="number" name="jumlah" value="1" min="1" max="${qty}" id="input-${rowId}">`;
                const row = `
                    <tr id="${rowId}" data-code="${code}" data-name="${name}" data-qty="1" data-price="${price}" data-total-price="${price}">
                        <td >${iteration}.</td>
                        <td >${code}</td>
                        <td >${name}</td>
                        <td>${jumlahInput}</td>
                        <td  id="text-price-${rowId}">${price}</td>
                        <td  id="text-total-${rowId}">${price}</td>
                        <td><button class="btn btn-danger btn-sm" data-row-id="${rowId}" id="btn-delete-${rowId}">Hapus</button></td>
                    </tr>
                `;
                $("#cart").append(row);

                calculate();
            });

            $(document).on("change", "input[name='jumlah']", function () {
            const inputField = $(this);

            const inputId = inputField.attr("id");
            const code = inputId.split("-")[2];

            const originalValue = inputField.data('original-value') || inputField.val();

            $.ajax({
                type: "GET",
                url: `api/product/${code}`,
                success: function (response) {
                    const qty = response[0].available_qty;
                    // console.log("Available qty from API:", qty);
                    // console.log("Input value:", inputField.val());

                    if (parseFloat(inputField.val()) > parseFloat(qty)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Jumlah barang melebihi stok!',
                        });
                        inputField.val(originalValue); // Reset to original value
                        inputField.data('original-value', originalValue); // Update the original value
                        $("#text-total-product").text(originalValue + " Barang");
                        $("#text-total").text(0);
                        return;
                    }

                    const rowId = "row-" + code;
                    const rowElement = $('#' + rowId);
                    const subtotal = parseFloat(inputField.val()) * parseFloat(rowElement.data("price"));
                    rowElement.data('total-price', subtotal);
                    rowElement.data('qty', inputField.val());

                    const subtotalElement = $('#text-total-' + rowId);
                    subtotalElement.text(subtotal);
                    calculate();
                },
                error: function (error) {
                    alert("Terjadi kesalahan saat menyimpan data.\n" + error.responseText);
                }
            });

            inputField.data('original-value', inputField.val());
        });


            $(document).on("click", "button[id^='btn-delete-']", function () {
                const buttonId = $(this).data("row-id");
                const rowId = buttonId.replace("btn-", "");

                $("#" + rowId).remove();
                calculate();
            });

            $("#input-pay").on("keyup", function () {
                const newValue = $(this).val();
                const returnValue = newValue - $("#text-total").text();
                if (returnValue < 0 || $("#cart tr").length < 1) return;
                $("#input-return").val(returnValue);
            });

            $("#btn-save").on("click", function () {
                const dataCart = [];

                if ($("#cart tr").length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Tidak ada data barang!',
                    });
                    return;
                }

                const payment = $("#payment").val();
                const pay = $("#input-pay").val();
                const returnVal = $("#input-return").val();

                if (pay < $("#text-total").text()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Jumlah uang pembayaran kurang',
                    });
                    $("#input-pay").val("");
                    return;
                }

                // if (!payment || !pay || !returnVal) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Peringatan',
                //         text: 'Masukan jumlah uang pembayaran',
                //     });
                //     return;
                // }

                $("#cart tr").each(function () {
                    const row = $(this);
                    const code = row.data("code");
                    const qty = row.data("qty");
                    dataCart.push({
                        code: code,
                        qty: qty
                    });
                });

                if (dataCart.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Tidak ada data barang!',
                    });
                    return;
                }

                const requestData = {
                    payment: payment,
                    pay: pay,
                    return: returnVal,
                    cart: dataCart
                }

                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "POST",
                    url: "{{ route('sale.store') }}",
                    data: { ...requestData, _token: csrfToken },
                    success: function (response) {
                        window.location.href = "{{ route('sale.show', ['sale' => '']) }}" + "/" + response.sale_no;
                    },
                    error: function (error) {
                        alert("Terjadi kesalahan saat menyimpan data.\n" + error.responseText);
                    }
                });
            });

            $('#product-select').select2({
                allowClear: true,
                placeholder: 'Pilih produk',
                ajax: {
                    delay: 250,
                    url: `{{ route('product.query') }}`,
                    dataType: 'json',
                    type: 'GET',
                    data: function (params) {
                        return {
                            code: params.term,
                            name: params.term
                        };
                    },
                    processResults: function (data) {
                        var select2Data = $.map(data.data, function (obj) {
                            obj.id = obj.id;
                            obj.text = obj.code + ' - ' + obj.name;

                            return obj;
                        })

                        return {
                            results: select2Data
                        }
                    },
                    cache: true
                }
            });

        });
    </script>

    {{-- <script src="{{ asset('assets/js/select2.min.js') }}"></script> --}}

@endsection
