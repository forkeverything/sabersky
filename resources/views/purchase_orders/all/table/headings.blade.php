<!-- Number -->
<th class="clickable"
@click="changeSort('number')"
:class="{
                                            'current_asc': params.sort === 'number' && params.order === 'asc',
                                            'current_desc': params.sort === 'number' && params.order === 'desc'
                                        }"
>
Order
</th>

<!-- Submitted (Date)-->
<th class="clickable"
@click="changeSort('created_at')"
:class="{
                                            'current_asc': params.sort === 'created_at' && params.order === 'asc',
                                            'current_desc': params.sort === 'created_at' && params.order === 'desc'
                                        }"
>
Submitted
</th>


<!-- Made by (User) -->
<th class="clickable"
@click="changeSort('user_name')"
:class="{
                                            'current_asc': params.sort === 'user_name' && params.order === 'asc',
                                            'current_desc': params.sort === 'user_name' && params.order === 'desc'
                                        }"
>
by
</th>

<!-- Vendor -->
<th class="clickable"
@click="changeSort('vendor_name')"
:class="{
                                            'current_asc': params.sort === 'vendor_name' && params.order === 'asc',
                                            'current_desc': params.sort === 'vendor_name' && params.order === 'desc'
                                        }"
>
Vendor
</th>



<!-- Status -->
<th class="clickable"
    @click="changeSort('status')"
    :class="{
                                            'current_asc': params.sort === 'status' && params.order === 'asc',
                                            'current_desc': params.sort === 'status' && params.order === 'desc'
                                        }"
>
Approval
</th>

<!-- Currency (heading spacer) -->
<th class="padding-even">
</th>


<!-- Total -->
<th class="clickable heading-right"
@click="changeSort('total')"
:class="{
                                            'current_asc': params.sort === 'total' && params.order === 'asc',
                                            'current_desc': params.sort === 'total' && params.order === 'desc'
                                        }"
>
Total
</th>

