<h4 class="po-number">Order #@{{ purchaseOrder.number }}</h4>
<span class="submitted-date">@{{ purchaseOrder.created_at | dateTime }}</span>
<span class="by-user">@{{ purchaseOrder.user.name }}</span>