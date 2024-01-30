<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h4>Product</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-bs-toggle="modal" data-bs-target="#create-modal"
                            class="float-end btn m-0  bg-gradient-primary">Create</button>
                    </div>
                </div>
                <hr class="bg-dark " />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>SL no</th>
                            <th>Image</th>
                            <th>Name</th>
                            {{-- <th>Category Name</th> --}}
                            <th>Price</th>
                            <th>Unit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableList">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    getList();
    async function getList() {
        showLoader();
        let res = await axios.get('/product-list');
        hideLoader();

        let tableData = $('#tableData');
        let tableList = $('#tableList');

        res.data.forEach(function(item, index) {
            let rows =
                `<tr>
                    <td>${index+1}</td>
                    <td><img class="w-30 h-auto" src="${item.img_url}"/></td>
                    <td>${item.name}</td>
                 <!--   <td>${item.category_id}</td> -->
                    <td>${item.price}</td>
                    <td>${item.unit}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success">Edit</button>
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </td>
                </tr>`
            tableList.append(rows);
        });

        tableData.DataTable({
            lengthMenu: [10, 20, 50]
        })
    }
</script>
