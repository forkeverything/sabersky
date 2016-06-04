
<div class="stats-body card">

    <div class="requests">
        <!-- Stats - Requests Table -->
        <table class="table requests">
            <tbody>
            <tr class="row-num-line-items">
            <td class="col-heading"><h2>Requests</h2></td>
            <td class="number fit-to-content no-wrap"><h2>@{{ numLineItems }}</h2></td>
            </tr>
            <tr class="row-paid">
            <td class="col-heading"><h4>Paid</h4></td>
            <td class="number fit-to-content no-wrap"><h4>@{{ numPaidLineItems }}</h4></td>
            </tr>
            <tr class="row-num-received-line-items">
                <td class="col-heading"><h4>Received</h4></td>
                <td class="number fit-to-content no-wrap"><h4>@{{ numReceivedLineItems }}</h4></td>
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
                <td class="col-heading"><h2>Number of Items</h2></td>
                <td class="number fit-to-content no-wrap"><h2>@{{ numItems }}</h2></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
