<script>
    class AgGridDefaultBtn {

        init({ data, api, canDelete, canUpdate, canResetPwd, updateText, resetText, deleteUrl, updateUrl, resetPwd, updateColumn, node: { id } }) {
            this.rowId = id;
            this.data = data;
            this.gridApi = api;
            this.deleteUrl = deleteUrl;
            this.updateUrl = updateUrl;
            this.resetPwd = resetPwd;
            this.updateColumn = updateColumn;

            this.eGui = document.createElement('div');
            this.eGui.innerHTML = `
                <div class="d-flex align-items-center justify-content-end w-100 h-100 pt-1 gap-2">
                    ${ canUpdate? `<button type="button" id="update" class="d-flex align-items-center fsemibold btn btn-sm btn-light border">${ updateText || 'Ubah' }</button>` : '' }
                    ${ canResetPwd? `<button type="button" id="reset" class="d-flex align-items-center fsemibold btn btn-sm btn-primary border">${ resetText || 'Reset' }</button>` : '' }
                    ${ canDelete? `<button type="button" id="delete" class="d-flex align-items-center fsemibold btn btn-sm btn-danger">Hapus</button>` : '' }
                </div>
            `;

            this.dButton = this.eGui.querySelector('#delete');
            this.dButton?.addEventListener('click', this.delete.bind(this));

            this.uButton = this.eGui.querySelector('#update');
            this.uButton?.addEventListener('click', this.update.bind(this));

            this.uButton = this.eGui.querySelector('#reset');
            this.uButton?.addEventListener('click', this.reset.bind(this));
        }

        refresh(_) { return true; }

        destroy() { 
            this.dButton?.removeEventListener('click', this.delete); 
            this.uButton?.removeEventListener('click', this.update); 
            this.uButton?.removeEventListener('click', this.reset); 
        }

        getGui() { return this.eGui; }

        delete({ target }) {
            Swal.fire({
                title: 'Apakah anda yakin untuk menghapus data?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),
                preConfirm: () => {
                    return callApi({ url: this.deleteUrl, method: 'POST', body: JSON.stringify({ id: this.data.id }), next: ({ data }) => {
                        gridOptions.api.refreshInfiniteCache();
                    } });
                }
            });            
        }

        update({ target }) {
            window.location.href = this.updateUrl.replace('id', this.data.id);
        }

        reset({ target }) {
            window.location.href = this.resetPwd.replace('id', this.data.id);
        }
    }
</script>
