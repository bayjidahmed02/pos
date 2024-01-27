<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryNameUpdate">
                                <input class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn bg-gradient-success">Update</button>
            </div>
        </div>
    </div>
</div>
<script>
    async function FillupUpdateData(id) {
        document.getElementById('updateID').value = id;
        let res = await axios.post('/category-details', {
            id: id
        });
        document.getElementById('categoryNameUpdate').value = res.data.category.name;
    }

    async function Update() {
        let name = document.getElementById('categoryNameUpdate').value;
        let id = document.getElementById('updateID').value;
        if (name.length === 0) {
            errorToast('Category name is required');
        } else {
            document.getElementById('update-modal-close').click();
            showLoader();
            let res = await axios.post('/category-update', {
                name: name,
                id: id
            });
            hideLoader();
            if (res.data === 1 && res.status === 200) {
                successToast('category Updated');
                await getList();
            } else {
                errorToast('someting went wrong. please try  again')
            }
        }
    }
</script>
