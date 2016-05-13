<!-- Number -->
<th class="clickable"
@click="changeSort('number')"
:class="{
                                            'current_asc': params.sort === 'number' && params.order === 'asc',
                                            'current_desc': params.sort === 'number' && params.order === 'desc'
                                        }"
>
PR
</th>

<!-- Requested (Date) -->
<th class="clickable"
@click="changeSort('created_at')"
:class="{
                                            'current_asc': params.sort === 'created_at' && params.order === 'asc',
                                            'current_desc': params.sort === 'created_at' && params.order === 'desc'
                                        }"
>
Requested
</th>

<!-- By (User) -->
<th class="clickable"
@click="changeSort('requester_name')"
:class="{
                                            'current_asc': params.sort === 'requester_name' && params.order === 'asc',
                                            'current_desc': params.sort === 'requester_name' && params.order === 'desc'
                                        }"
>
By
</th>

<!-- Project -->
<th class="clickable"
@click="changeSort('project_name')"
:class="{
                                            'current_asc': params.sort === 'project_name' && params.order === 'asc',
                                            'current_desc': params.sort === 'project_name' && params.order === 'desc'
                                        }"
>
Project
</th>

<!-- Quantity -->
<th class="clickable heading-center"
@click="changeSort('quantity')"
:class="{
                                            'current_asc': params.sort === 'quantity' && params.order === 'asc',
                                            'current_desc': params.sort === 'quantity' && params.order === 'desc'
                                        }"
>
Qty
</th>

<!-- Item -->
<th class="clickable"
@click="changeSort('item_name')"
:class="{
                                            'current_asc': params.sort === 'item_name' && params.order === 'asc',
                                            'current_desc': params.sort === 'item_name' && params.order === 'desc'
                                        }"
>
Item
</th>

<!-- Due (Date) -->
<th class="clickable"
@click="changeSort('due')"
:class="{
                                            'current_asc': params.sort === 'due' && params.order === 'asc',
                                            'current_desc': params.sort === 'due' && params.order === 'desc'
                                        }"
>
Due
</th>

<!-- State -->
<th class="clickable"
@click="changeSort('state')"
:class="{
                                            'current_asc': params.sort === 'state' && params.order === 'asc',
                                            'current_desc': params.sort === 'state' && params.order === 'desc'
                                        }"
>
    State
</th>



