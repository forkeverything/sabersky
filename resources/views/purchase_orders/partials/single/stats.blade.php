
<div class="stats-body no-print">

    <div class="requests">
        <!-- Stats - Requests Table -->
        <table class="table requests">
            <tbody>
            <tr class="row-num-line-items">
            <td class="col-heading"><h3>Requests</h3></td>
            <td class="number fit-to-content no-wrap"><h3>@{{ numLineItems }}</h3></td>
            </tr>
            <tr class="row-paid">
            <td class="col-heading"><h5>Paid</h5></td>
            <td class="number fit-to-content no-wrap"><h5>@{{ numPaidLineItems }}</h5></td>
            </tr>
            <tr class="row-num-received-line-items">
                <td class="col-heading"><h5>Received</h5></td>
                <td class="number fit-to-content no-wrap"><h5>@{{ numReceivedLineItems }}</h5></td>
            </tr>
            <tr class="row-accepted">
            <td class="col-heading">Accepted</td>
            <td class="number fit-to-content no-wrap">@{{ numAcceptedLineItems }}</td>
            </tr>
            <tr class="row-returned">
            <td class="col-heading">Returned</td>
            <td class="number fit-to-content no-wrap">@{{ numReturnedLineItems }}</td>
            </tr>
            </tbody>
        </table>
    </div>



    <div class="items">
        <!-- Stats - Items Table -->
        <table class="table items">
            <tbody>
            <tr class="row-num-items">
                <td class="col-heading"><h3>Number of Items</h3></td>
                <td class="number fit-to-content no-wrap"><h3>@{{ numItems }}</h3></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
