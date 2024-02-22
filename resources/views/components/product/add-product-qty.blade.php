<div class="modal animated zoomIn" id="add-quantity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
            </div>
            <div class="modal-body">
                <form id="quantity-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <label class="form-label mt-2">Add Product Quantity</label>
                                <input type="text" class="form-control" id="addProductQty">
                                <input type="text" class="form-control d-none" id="addProductId">

                            </div>
                        </div>
                    </div>
                </form>
            </div>


            <div class="modal-footer">
                <button onclick="closeModal()" id="add-modal-close" class="btn bg-gradient-primary mx-2"
                    data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="addQty()" id="save-btn" class="btn bg-gradient-success">Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    function closeModal() {
        showLoader()
        $('#add-quantity').modal('hide');
        hideLoader()
    }
    async function addQty() {
        let id = document.getElementById('addProductId').value;
        let addQty = document.getElementById('addProductQty').value;
        if (id.length === 0 || addQty.length === 0) {
            errorToast('Quantity Required')
        } else {
            document.getElementById('add-modal-close').click()
            $('#add-quantity').modal('hide')
            showLoader()
            let res = await axios.post('/add-quantity', {
                id: id,
                qty: addQty
            });
            hideLoader();
            if (res.data.status === 'success') {
                successToast(res.data.msg)
                document.getElementById('quantity-form').reset()
                await getList()
            } else {
                errorToast(res.data.msg)
            }
        }

    }
</script>
