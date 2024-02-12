<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h5>Invoices</h5>
                    </div>
                    <div class="align-items-center col">
                        <a href="{{ route('sale') }}" class="float-end btn m-0 bg-gradient-primary">Create Sale</a>
                    </div>
                </div>
                <hr class="bg-dark " />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>No</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Total</th>
                            <th>Vat</th>
                            <th>Discount</th>
                            <th>Payable</th>
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
        let res = await axios.get('/invoice-list')
        hideLoader();

        let tableData = $('#tableData');
        let tableList = $('#tableList');

        tableData.DataTable().destroy();
        tableList.empty();

        res.data.forEach(function(item, index) {
            let rows =
                `<tr>
                    <td>${index+1}</td>
                    <td>${item.customer.name}</td>
                    <td>${item.customer.mobile}</td>
                    <td>${item.total}</td>
                    <td>${item.vat}</td>
                    <td>${item.discount}</td>
                    <td>${item.payable}</td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-sm btn-success px-3 py-2 m-0">View</a>
                        <a class="btn btn-sm btn-danger px-3 py-2 m-0">Delete</a>
                    </td>
                </tr>`
            tableList.append(rows);
        });
        tableData.DataTable();
    }
</script>
