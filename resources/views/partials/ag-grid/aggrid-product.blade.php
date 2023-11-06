<link rel="stylesheet" href="{{ asset('assets/css/ag-grid.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/ag-theme-alpine.min.css') }}">
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>
<script>
    const gridOptions = {
        rowData: [],
        rowHeight: 100,
        rowModelType: 'infinite', 
        pagination: true, animateRows: true,
        paginationPageSize: 50, cacheBlockSize: 50,
        defaultColDef: { flex: 1, minWidth: 150, sortable: true, sortingOrder: ['asc', 'desc'], resizable: true, unSortIcon: true },
        getRowClass: ({ node: { rowIndex: i } }) => {
            if (i % 2 === 1) return 'ag-grid-odd-rows';
            return '';
        }
    };

    function setPageSize(n = 50) { gridOptions.paginationPageSize = gridOptions.cacheBlockSize = n; }

    function formatDateTime(s) {
        if (s) return ((new Date(s)).toLocaleDateString('id-id', { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' })).replaceAll('.', ':');
        return '';
    }

    function formatPrice(s) {
        if (s !== undefined && s !== null && !isNaN(s)) return `Rp ${ Number(s).toLocaleString('id-ID') }`;
        return '';
    }

    function ImageCellRenderer(params) {
        if (params.value) return `<img src="/assets/imgs/${params.value}" class="w-100 h-200" />`;
        return '';
    }
</script>
