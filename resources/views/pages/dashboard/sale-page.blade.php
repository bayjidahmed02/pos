@extends('layout.sidenav-layout')
@section('page_title', 'Generate Invoice')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name: <span id="CName"></span> </p>
                            <p class="text-xs mx-0 my-1">Email: <span id="CEmail"></span></p>
                            <p class="text-xs mx-0 my-1">User ID: <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            <img class="w-50" src="{{ 'images/logo.png' }}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary" />
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="invoiceTable">
                                <thead class="w-100">
                                    <tr class="text-xs">
                                        <td>Name</td>
                                        <td>Qty</td>
                                        <td>Total</td>
                                        <td>Remove</td>
                                    </tr>
                                </thead>
                                <tbody class="w-100" id="invoiceList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary" />
                    <div class="row">
                        <div class="col-12">
                            <p class="text-bold text-xs my-1 text-dark">
                                TOTAL: <i class="bi bi-currency-dollar"></i>
                                <span id="total"></span>
                            </p>
                            <p class="text-bold text-xs my-1 text-dark">
                                VAT(5%): <i class="bi bi-currency-dollar"></i>
                                <span id="vat"></span>
                            </p>
                            <p class="text-bold text-xs my-1 text-dark">
                                Discount: <i class="bi bi-currency-dollar"></i>
                                <span id="discount"></span>
                            </p>
                            <p class="text-bold text-xs my-2 text-dark">
                                PAYABLE: <i class="bi bi-currency-dollar"></i>
                                <span id="payable"></span>
                            </p>
                            <span class="text-xxs">Discount(%):</span>
                            <input onkeydown="return false" value="0" min="0" type="number" step="0.25"
                                onchange="DiscountChange()" class="form-control w-40 " id="discountP" />

                            <button onclick="createInvoice()" class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                        </div>
                        <div class="col-12 p-2">

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table  w-100" id="productTable">
                        <thead class="w-100">
                            <tr class="text-xs text-bold">
                                <td>Product</td>
                                <td>Pick</td>
                            </tr>
                        </thead>
                        <tbody class="w-100" id="productList">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table table-sm w-100" id="customerTable">
                        <thead class="w-100">
                            <tr class="text-xs text-bold">
                                <td>Customer</td>
                                <td>Pick</td>
                            </tr>
                        </thead>
                        <tbody class="w-100" id="customerList">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label d-none">Product ID *</label>
                                    <input type="text" class="form-control d-none" id="PId" readonly>
                                    <label class="form-label mt-2">Product Name *</label>
                                    <input type="text" class="form-control" id="PName" readonly>
                                    <label class="form-label mt-2">Product Price *</label>
                                    <input type="text" class="form-control" id="PPrice" readonly>
                                    <label class="form-label mt-2">Product Qty *</label>
                                    <input type="text" class="form-control" id="PQty">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                        aria-label="Close">Close</button>
                    <button onclick="add()" id="save-btn" class="btn bg-gradient-success">Add</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (async function() {
            showLoader();
            await CustomerList();
            await ProductList();
            hideLoader();
        })();

        invoiceItemList = [];

        function showInvoiceItem() {
            let invoiceTable = $('#invoiceTable');
            let invoiceList = $('#invoiceList');
            invoiceList.empty();
            invoiceItemList.forEach(function(item, index) {
                let shortName = item.name.length > 10 ? item.name.substring(0, 10) + '...' : item.name;
                let rows =
                    `<tr>
                        <td>${shortName}</td>
                        <td>${item.qty}</td>
                        <td>${item.sale_price}</td>
                        <td>
                            <a data-index="${index}" class="removeBtn btn btn-sm text-xxs px-2 py-1 m-0">Remove</a>
                        </td>
                    </tr>`
                invoiceList.append(rows);
            });
            GrandTotal();
            $('.removeBtn').on('click', function() {
                let index = $(this).data('index');
                invoiceItemList.splice(index, 1);
                showInvoiceItem();
            });
        }

        function add() {
            let product_id = document.getElementById('PId').value;
            let name = document.getElementById('PName').value;
            let sale_price = document.getElementById('PPrice').value;
            let qty = document.getElementById('PQty').value;

            let totalPrice = (parseFloat(sale_price) * qty).toFixed(2);

            if (product_id.length === 0 || name.length === 0 || sale_price.length === 0 || qty.length === 0) {
                errorToast('All Fields are Required');
            } else {
                let item = {
                    product_id: product_id,
                    name: name,
                    sale_price: totalPrice,
                    qty: qty
                }
                invoiceItemList.push(item);
                $('#create-modal').modal('hide');
                $('#add-form')[0].reset();
                showInvoiceItem();
            }

        }

        function DiscountChange() {
            GrandTotal();
        }

        function GrandTotal() {
            let total = 0;
            let vat = 0;
            let payable = 0;
            let discount = 0;
            let discountPercentage = parseFloat(document.getElementById('discountP').value);

            invoiceItemList.forEach(function(item, index) {
                total = total + parseFloat(item.sale_price);
            });

            discount = (total * discountPercentage) / 100;
            vat = (total * 5) / 100;
            payable = (total + vat) - discount;

            document.getElementById('total').innerText = total.toFixed(2);
            document.getElementById('vat').innerText = vat.toFixed(2);
            document.getElementById('discount').innerText = discount.toFixed(2);
            document.getElementById('payable').innerText = payable.toFixed(2);
        }
        async function CustomerList() {
            let res = await axios.get('/customer-list')

            let customerTable = $('#customerTable');
            let customerList = $('#customerList');
            customerTable.DataTable().destroy();
            customerList.empty();
            res.data.forEach(function(item, index) {
                let rows =
                    `<tr>
                        <td><i class="bi bi-person"></i> ${item.name}</td>
                        <td>
                            <a data-id="${item.id}" data-email="${item.email}" data-name="${item.name}" class="addCustomer btn btn-sm px-2 py-1 m-0 text-xxs btn-outline-dark rounded">Add</a>
                        </td>

                    </tr>`
                customerList.append(rows)
            });
            customerTable.DataTable({
                info: false,
                lengthChange: false,
            });

            $('.addCustomer').on('click', function() {
                let CID = $(this).data('id');
                let CEmail = $(this).data('email');
                let CName = $(this).data('name');

                document.getElementById('CName').innerText = CName;
                document.getElementById('CEmail').innerText = CEmail;
                document.getElementById('CId').innerText = CID;
            });
        }
        async function ProductList() {
            let res = await axios.get('/product-list')
            let productTable = $('#productTable');
            let productList = $('#productList');

            res.data.forEach(function(item, index) {
                let shortName = item.name.length > 10 ? item.name.substring(0, 20) + '...' : item.name;
                let rows =
                    `<tr>
                        <td>
                            <img src="${item.img_url}" width="30"/>
                            <span>${shortName}</span>
                        </td>

                        <td>
                            <a data-id="${item.id}" data-name="${item.name}" data-price="${item.price}" class="addProduct btn btn-sm btn-outline-dark text-xxs m-0 px-2 py-1 rounded">Add</a>
                        </td>

                    </tr>`
                productList.append(rows);
            });
            productTable.DataTable({
                lengthChange: false,
                info: false
            });

            $('.addProduct').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name')
                let price = $(this).data('price')
                $('#create-modal').modal('show');
                document.getElementById('PId').value = id;
                document.getElementById('PName').value = name;
                document.getElementById('PPrice').value = price;
            });
        }
        async function createInvoice() {
            let total = document.getElementById('total').innerText;
            let discount = document.getElementById('discount').innerText;
            let vat = document.getElementById('vat').innerText;
            let payable = document.getElementById('payable').innerText;
            let customer_id = document.getElementById('CId').innerText;
            let products = invoiceItemList;

            if (customer_id.length === 0) {
                errorToast('Customer Required')
            } else if (products.length === 0) {
                errorToast('Products Required')
            } else {
                let res = await axios.post('/invoice-create', {
                    total: total,
                    discount: discount,
                    vat: vat,
                    payable: payable,
                    customer_id: customer_id,
                    products: products,
                });
                if (res.status === 200 && res.data.status === 'success') {
                    successToast(res.data.msg);
                    window.location.href = '/invoice'
                } else {
                    errorToast(res.data.msg);
                }
            }
        }
    </script>

@endsection
