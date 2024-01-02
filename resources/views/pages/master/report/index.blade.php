@extends('layouts.app')
@section('title', 'Laporan Penjualan')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4 align-content-center">
        <h4 class="m-0">Laporan Penjualan</h4>
        <ul class="nav nav-underline mb-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#table" id="table-button"
                    onclick="showTab('table')">Table</a>
            </li>
            <li class="nav-item me-4">
                <a class="nav-link" aria-current="page" href="#grap" id="grap-button" onclick="showTab('grap')">Grap</a>
            </li>

        </ul>
    </div>

    <div class="card border-0 flex-grow-1 d-flex flex-column h-100 mt-4 px-4" id="grap">
        <div class="d-flex justify-content-end align-items-center flex-shrink-0 gap-4">
            <div class="pt-4 pb-3 flex-shrink-0">
                <div class="position-relative search-box">
                    <input type="date" id="start_date" class="form-control" name="start_date" placeholder="">
                </div>
            </div>
            <div class="px-2 pt-4 pb-3 flex-shrink-0">
                <div class="position-relative search-box">
                    <input type="date" id="end_date" class="form-control" name="end_date" placeholder="">
                </div>
            </div>
            <div class="pt-4 pb-3 flex-shrink-0">
                <button id="btn-filter" class="btn btn-primary">Filter</button>
            </div>
            <div class="pt-4 pb-3 ms-2 flex-shrink-0">
                <button id="btn-cancel" class="btn btn-primary">Cancel</button>
            </div>
        </div>
        <div class="p-1 flex-grow-1 mt-4">
            <canvas id="myChart" height="100px"></canvas>
        </div>
    </div>

    <div class="card border-0 flex-grow-1 d-flex flex-column h-100 mt-4 d-none" id="table">
        <div class="d-flex justify-content-between align-items-center flex-shrink-0 gap-4">
            <div class="px-4 pt-4 pb-3 flex-shrink-0 d-flex gap-3 align-items-center">
                <div class="position-relative search-box" style="margin-bottom: 0">
                    <ion-icon name="search" class="f24 position-absolute"></ion-icon>
                    <input type="text" id="search-data" class="form-control" placeholder="Ketik untuk mencari...">
                </div>

                <div class="d-flex">
                    <div class="pt-4 pb-3 flex-shrink-0">
                        <div class="position-relative search-box">
                            <input type="date" id="start_date" class="form-control" name="start_date" placeholder="">
                        </div>
                    </div>
                    <div class="px-2 pt-4 pb-3 flex-shrink-0">
                        <div class="position-relative search-box">
                            <input type="date" id="end_date" class="form-control" name="end_date" placeholder="">
                        </div>
                    </div>
                    <div class="pt-4 pb-3 flex-shrink-0">
                        <button id="btn-filter" class="btn btn-primary">Filter</button>
                    </div>
                    <div class="pt-4 pb-3 ms-2 flex-shrink-0">
                        <button id="btn-cancel" class="btn btn-primary">Cancel</button>
                    </div>
                </div>
            </div>


            <div class="px-4 pt-4 pb-3 flex-shrink-0">
                <a href="{{ route('transaction.pdf') }}" id="" class="btn btn-primary">Download Laporan</a>
            </div>
        </div>

        <div class="p-1 flex-grow-1 mt-4">
            <table class="table w-100 border" id="report-table" style="border-radius: 10px">
                <thead>
                    <tr style="background-color: #F8F8F8">
                        {{-- <th>No</th> --}}
                        <th>Nomor Pembelian</th>
                        <th>Subtotal</th>
                        <th>Grandtotal</th>
                        <th>Total Barang</th>
                        <th>Discount</th>
                        <th>Pembayaran</th>
                        <th>Uang Dibayar</th>
                        <th>Status</th>
                        <th>Tanggal Pembelian</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <div class="row">
                <div class="col d-flex">
                    <p>Total barang terjual</p>
                    <p class="ms-2 fw-bold" id="totalSaledQty">300</p>
                </div>
                <div class="col d-flex">
                    <p>Keuntungan</p>
                    <p class="ms-2 fw-bold" id="totalIncome">300</p>
                </div>
            </div>

        </div>
    </div>

    @include('partials.ag-grid.aggrid')
    @include('partials.ag-grid.aggrid-default-user-btn')

    <script>

        var filteredDate = {};
        var saleNo;
        var doneTypeInterval = 500;
        var typingTimer;

        document.getElementById('search-data').addEventListener('input', getInputSearchValue);

        document.getElementById('start_date').addEventListener('input', getInputDateValues);
        document.getElementById('end_date').addEventListener('input', getInputDateValues);
        document.getElementById('btn-filter').addEventListener('click', findDataOnTable);
        document.getElementById('btn-cancel').addEventListener('click', cancelData);

        function getInputDateValues() {
            filteredDate.startDate = document.getElementById('start_date').value;
            filteredDate.endDate = document.getElementById('end_date').value;
        }

        function getInputSearchValue() {
            clearTimeout(typingTimer);
            searchValue = document.getElementById('search-data').value;
            typingTimer = setTimeout(() => {
                // showData(0, 0, searchValue.trim().toLowerCase());
                // findDataOnTable(searchValue.trim().toLowerCase());
                saleNo = searchValue.trim().toLowerCase();
            }, doneTypeInterval);
        }

        console.log(saleNo);
        function findDataOnTable() {
            if (filteredDate.startDate && filteredDate.endDate) {
                var fromDate = filteredDate.startDate;
                var toDate = filteredDate.endDate;

                showData(fromDate, toDate);
            } else if (saleNo) {
                console.log(saleNo);
            } else {
                showData();
            }
        }

        function cancelData() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';

            showData();
        }

        function showData(fromDate, toDate, searchValue) {
            $('#report-table').DataTable().destroy();
            // console.log(searchValue);

            $.ajax({
                url: `{{ route('report.query') }}?start_date=${fromDate?? 0}&end_date=${toDate?? 0}&sale_no=${searchValue?? 0}`,
                type: "GET",
                dataType: "JSON",
                success: function (res) {
                    originalData = res.data.sale;

                    $('#totalSaledQty').html(res.data.totalSaledQty);
                    $('#totalIncome').html(res.data.totalIncome);

                    var rotationTable = $('#report-table').DataTable({
                        data: originalData,
                        columns: [
                            // {data: '1', name: 'no'},
                            {data: 'sale_no', name: 'nomor_pembelian'},
                            {data: 'subtotal', name: 'subtotal'},
                            {data: 'grandtotal', name: 'grandtotal'},
                            {data: 'total_qty', name: 'total_barang'},
                            {data: 'discount', name: 'discount'},
                            {data: 'payment', name: 'pembayaran'},
                            {data: 'cash_amount', name: 'uang_dibayar'},
                            {data: 'status', name: 'status'},
                            {data: 'created_at', name: 'tanggal_pembelian'},
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

        showData();

    </script>

    <script>
        window.onload = function() {
            showDefaultTab('table');
        }

        function showDefaultTab(defaultTab) {
            const chartButton = document.getElementById('grap-button');
            const tableButton = document.getElementById('table-button');

            const chartView = document.getElementById('grap');
            const tableView = document.getElementById('table');

            if (defaultTab === 'table') {
                chartView.classList.add('d-none');
                tableView.classList.remove('d-none');

                chartButton.classList.remove('active');
                tableButton.classList.add('active');
            } else {
                chartView.classList.remove('d-none');
                tableView.classList.add('d-none');

                chartButton.classList.add('active');
                tableButton.classList.remove('active');
            }
        }

        function showTab(tabId) {
            const chartButton = document.getElementById('grap-button');
            const tableButton = document.getElementById('table-button');

            const chartView = document.getElementById('grap');
            const tableView = document.getElementById('table');

            if (tabId === 'table') {
                chartView.classList.add('d-none');
                tableView.classList.remove('d-none');

                chartButton.classList.remove('active');
                tableButton.classList.add('active');
            } else {
                chartView.classList.remove('d-none');
                tableView.classList.add('d-none');

                chartButton.classList.add('active');
                tableButton.classList.remove('active');
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="text/javascript">

        var filteredDate = {};
        var searchValue = '';

        document.getElementById('start_date').addEventListener('input', getInputDateValues);
        document.getElementById('end_date').addEventListener('input', getInputDateValues);
        document.getElementById('btn-filter').addEventListener('click', findData);
        document.getElementById('btn-cancel').addEventListener('click', cancelData);

        function getInputDateValues() {
            filteredDate.startDate = document.getElementById('start_date').value;
            filteredDate.endDate = document.getElementById('end_date').value;
        }

        function findData() {
            if (filteredDate.startDate && filteredDate.endDate) {
                var fromDate = filteredDate.startDate;
                var toDate = filteredDate.endDate;

                getApiData(fromDate, toDate);
            } else {
                alert('Silahkan isi tanggal terlebih dahulu');
            }
        }

        function cancelData() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';

            getApiData();
        }

        function getApiData(fromDate, toDate) {
            $.ajax({
                url: `{{ route('report.chart-data') }}?start_date=${fromDate?? 0}&end_date=${toDate?? 0}`,
                dataType: "json",
                type: "GET",
                success: function (data) {
                    fetchDataAndRenderChart(data);
                },
                error: function (error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            fetchDataAndRenderChart();
        });

        var myChart = null;

        function fetchDataAndRenderChart(data) {
            var labels = data.labels;
            var datas = data.data;

            const dataForChart = {
                labels: labels,
                datasets: [{
                    label: 'Data Penjualan',
                    backgroundColor: 'rgb(0, 0, 255)',
                    borderColor: 'rgb(0, 0, 255)',
                    data: datas,
                }]
            };

            const chartConfig = {
                type: 'line',
                data: dataForChart,
                options: {}
            };

            if (myChart !== null && typeof myChart === 'object') {
                myChart.destroy();
            }

            myChart = new Chart(
                document.getElementById('myChart'),
                chartConfig
            );
        }

        getApiData();
    </script>

@endsection
