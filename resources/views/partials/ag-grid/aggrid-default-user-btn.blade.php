<script>
    class AgGridDefaultBtn {

        init({
            data,
            api,
            canDelete,
            canUpdate,
            canResetPwd,
            updateText,
            resetText,
            deleteUrl,
            updateUrl,
            resetPwd,
            updateColumn,
            node: {
                nip
            }
        }) {
            this.rowId = nip;
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

        refresh(_) {
            return true;
        }

        destroy() {
            this.dButton?.removeEventListener('click', this.delete);
            this.uButton?.removeEventListener('click', this.update);
            this.uButton?.removeEventListener('click', this.reset);
        }

        getGui() {
            return this.eGui;
        }

        delete({
            target
        }) {
            Swal.fire({
                title: 'Apakah anda yakin untuk menghapus data?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),
                preConfirm: () => {
                    return callApi({
                        url: this.deleteUrl,
                        method: 'POST',
                        body: JSON.stringify({
                            nip: this.data.nip
                        }),
                        next: ({
                            data
                        }) => {
                            // console.log('oke');
                            gridOptions.api.refreshInfiniteCache();
                        }
                    });
                }
            });
        }

        update({
            target
        }) {
            window.location.href = this.updateUrl.replace('nip', this.data.nip);
        }

        reset({
            target
        }) {
            Swal.fire({
                title: 'Apakah anda yakin untuk mengubah password ?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),
                preConfirm: async () => {
                    try {
                        const url = this.resetPwd.replace('nip', this.data.nip);
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                        });
                        if (!response.ok) {
                            return Swal.showValidationMessage(
                                `${JSON.stringify(await response.json())}`);
                        }
                        return response.json();
                    } catch (error) {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    }
                }
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Password berhasil diubah',
                        icon: 'success',
                        text: result.value.data,
                        showConfirmButton: false,
                    })
                }
            });
        }
    }
</script>
