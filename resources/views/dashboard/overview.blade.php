<div class="row hidden-xs company-overview full">
    <div class="col-xs-3">
        <div class="panel-body vendors">
            <span class=panel-label>Vendors</span>
            <div class="details">
                <h1 class="number no-wrap">
                    {{ $numVendors }}
                </h1>
                <i class="livicon-evo icon-overview" data-options="name:building.svg; size: 70px; repeat: loop; eventOn: grandparent; style: solid; solidColor: #ffffff; solidColorBg: #FFB400;"></i>
            </div>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="panel-body items">
            <span class="panel-label">Items</span>
            <div class="details">
                <h1 class="number no-wrap">
                    {{ $numItems }}
                </h1>
                <i class="livicon-evo icon-overview" data-options="name: hammer.svg; size: 70px; repeat: loop; eventOn: grandparent; style: solid; solidColor: #ffffff; solidColorBg: #C0392B;"></i>
            </div>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="panel-body requests">
            <span class="panel-label">P.Requests</span>
            <div class="details">
                <h1 class="number no-wrap">
                    {{ $numRequests }}
                </h1>
                <i class="livicon-evo icon-overview" data-options="name: shoppingcart-in.svg; size: 70px; repeat: loop; eventOn: grandparent; style: solid; solidColor: #ffffff; solidColorBg: #27AE60;"></i>
            </div>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="panel-body orders">
            <span class="panel-label">P.Orders</span>
            <div class="details">
                <h1 class="number no-wrap">
                    {{ $numOrders }}
                </h1>
                <i class="livicon-evo icon-overview" data-options="name: print-doc.svg; size: 70px; repeat: loop; eventOn: grandparent; style: solid; solidColor: #ffffff; solidColorBg: #2980B9;"></i>
            </div>
        </div>
    </div>
</div>

<div class="company-overview compact visible-xs">
<h2>Company Overview</h2>
    <ul class="list-unstyled">
        <li><span class="overview-label">
                <i class="livicon-evo icon-overview" data-options="name:building.svg; repeat: loop; eventOn: grandparent; style: lines;"></i>
                Vendors
            </span><span class="number">{{ $numVendors }}</span></li>
        <li><span class="overview-label">
                <i class="livicon-evo icon-overview" data-options="name: hammer.svg; repeat: loop; eventOn: grandparent; style: lines;"></i>
                Items
            </span><span class="number">{{ $numItems }}</span></li>
        <li><span class="overview-label">
                <i class="livicon-evo icon-overview" data-options="name: shoppingcart-in.svg; repeat: loop; eventOn: grandparent; style: lines;"></i>
                P.Requests
            </span><span class="number">{{ $numRequests }}</span></li>
        <li><span class="overview-label">
                <i class="livicon-evo icon-overview" data-options="name: print-doc.svg; repeat: loop; eventOn: grandparent; style: lines;"></i>
                P.Orders
            </span><span class="number">{{ $numOrders }}</span></li>
    </ul>
</div>