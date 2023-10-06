<script>
    class AgGridInvoiceBtn {

        init({ data, api, onRemove }) {
            this.data = data;
            this.gridApi = api;
            this.onRemove = onRemove;

            this.eGui = document.createElement('div');
            this.eGui.innerHTML = `
                <div class="d-flex align-items-center justify-content-end w-100 h-100">
                    <button type="button" id="delete" class="d-flex align-items-center fsemibold btn btn-sm btn-danger">Hapus</button>
                </div>
            `;

            this.dButton = this.eGui.querySelector('#delete');
            this.dButton?.addEventListener('click', this.delete.bind(this));
        }

        refresh(_) { return true; }
        
        getGui() { return this.eGui; }

        destroy() { 
            this.dButton?.removeEventListener('click', this.delete); 
        }

        delete({ target }) {
            this.onRemove(this.data);
            this.gridApi.applyTransaction({ remove: [ this.data ] });
        }
    }
</script>
