<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategoryUpdate">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPriceUpdate">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnitUpdate">
                                <br />
                                <img style="width: 100px" id="oldImg" src="{{ asset('images/default.jpg') }}" />
                                <br />
                                <label class="form-label mt-2">Image</label>
                                <input oninput="oldImg.src=window.URL.createObjectURL(this.files[0])" type="file"
                                    class="form-control" id="productImgUpdate">

                                {{-- <input type="file" oninput="oldImg.src=window.URL.createObjectURL(this.files[0])"
                                    class="form-control" id="productImgUpdate"> --}}

                                <input type="text" class="d-none" id="updateID">
                                <input type="text" class="d-none" id="filePath">
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
    async function UpdateFillupCategory() {
        let res = await axios.get('/category-list');
        let dropdown = $('#productCategoryUpdate');
        dropdown.empty()
        res.data.forEach(function(item) {
            let option = `<option value="${item.id}">${item.name}</option>`
            dropdown.append(option)
        });
    }
    async function FillupUpdateForm(id, img_url) {
        document.getElementById('update-form').reset()
        document.getElementById('updateID').value = id
        document.getElementById('filePath').value = img_url
        document.getElementById('oldImg').src = img_url
        showLoader();
        await UpdateFillupCategory()
        let res = await axios.post('/product-details', {
            id: id
        })
        hideLoader();
        document.getElementById('productCategoryUpdate').value = res.data.category_id
        document.getElementById('productNameUpdate').value = res.data.name
        document.getElementById('productPriceUpdate').value = res.data.price
        document.getElementById('productUnitUpdate').value = res.data.unit
    }

    async function Update() {
        let productCategoryUpdate = document.getElementById('productCategoryUpdate').value
        let productNameUpdate = document.getElementById('productNameUpdate').value
        let productPriceUpdate = document.getElementById('productPriceUpdate').value
        let productUnitUpdate = document.getElementById('productUnitUpdate').value
        let updateID = document.getElementById('updateID').value
        let filePath = document.getElementById('filePath').value
        let productImgUpdate = document.getElementById('productImgUpdate').files[0]

        if (productCategoryUpdate.length === 0 || productNameUpdate.length === 0 || productPriceUpdate.length ===
            0 || productUnitUpdate.length === 0) {
            errorToast('All Fields are Required')
        } else {
            document.getElementById('update-modal-close').click();
            let formData = new FormData();
            formData.append('img', productImgUpdate)
            formData.append('id', updateID)
            formData.append('name', productNameUpdate)
            formData.append('price', productPriceUpdate)
            formData.append('unit', productUnitUpdate)
            formData.append('category_id', productCategoryUpdate)
            formData.append('old_img', filePath)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            showLoader()
            let res = await axios.post('/product-update', formData, config)
            hideLoader()
            if (res.data === 1) {
                successToast('Product Updated')
                document.getElementById('update-form').reset()
                await getList()
            } else {
                errorToast('Cannot Updated')
            }
        }
    }
</script>
