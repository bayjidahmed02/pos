<!DOCTYPE html>
<html>

<head>
    <style>
        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 12px !important;
        }

        .customers td,
        .customers th {
            /* Update selector here */
            border: 1px solid #ddd;
            padding: 8px;
        }

        .customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .customers tr:hover {
            background-color: #ddd;
        }

        .customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            padding-left: 6px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>

<body>

    <h3>Summary</h3>

    <table class="customers">
        <thead>
            <tr>
                <th>Report</th>
                <th>Date</th>
                <th>Total</th>
                <th>Discount</th>
                <th>Vat</th>
                <th>Payable</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sales Report</td>
                <td>{{ $fromDate }} to {{ $toDate }}</td>
                <td>{{ $total }}</td>
                <td>{{ $discount }}</td>
                <td>{{ $vat }}</td>
                <td>{{ $payable }}</td>
            </tr>
        </tbody>
    </table>


    <h3>Details</h3>
    <table class="customers">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Total</th>
                <th>Discount</th>
                <th>Vat</th>
                <th>Payable</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->customer->name }}</td>
                    <td>{{ $customer->customer->mobile }}</td>
                    <td>{{ $customer->customer->email }}</td>
                    <td>{{ $customer->total }}</td>
                    <td>{{ $customer->discount }}</td>
                    <td>{{ $customer->vat }}</td>
                    <td>{{ $customer->payable }}</td>
                    <td>{{ $customer->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
