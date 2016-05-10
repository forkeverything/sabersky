<thead>
<tr>
    <th class="clickable"
    @click="changeSort('number')"
    :class="{
                                            'current_asc': params.sort === 'number' && params.order === 'asc',
                                            'current_desc': params.sort === 'number' && params.order === 'desc'
                                        }"
    >
    PR
    </th>
    <th class="clickable"
    @click="changeSort('project_name')"
    :class="{
                                            'current_asc': params.sort === 'project_name' && params.order === 'asc',
                                            'current_desc': params.sort === 'project_name' && params.order === 'desc'
                                        }"
    >
    Project
    </th>
    <th class="clickable"
    @click="changeSort('quantity')"
    :class="{
                                            'current_asc': params.sort === 'quantity' && params.order === 'asc',
                                            'current_desc': params.sort === 'quantity' && params.order === 'desc'
                                        }"
    >
    Qty
    </th>
    <th class="clickable"
    @click="changeSort('item_name')"
    :class="{
                                            'current_asc': params.sort === 'item_name' && params.order === 'asc',
                                            'current_desc': params.sort === 'item_name' && params.order === 'desc'
                                        }"
    >
    Item
    </th>
    <th class="clickable"
    @click="changeSort('due')"
    :class="{
                                            'current_asc': params.sort === 'due' && params.order === 'asc',
                                            'current_desc': params.sort === 'due' && params.order === 'desc'
                                        }"
    >
    Due</th>
    <th class="clickable"
    @click="changeSort('created_at')"
    :class="{
                                            'current_asc': params.sort === 'created_at' && params.order === 'asc',
                                            'current_desc': params.sort === 'created_at' && params.order === 'desc'
                                        }"
    >
    Requested
    </th>
    <th class="clickable"
    @click="changeSort('requester_name')"
    :class="{
                                            'current_asc': params.sort === 'requester_name' && params.order === 'asc',
                                            'current_desc': params.sort === 'requester_name' && params.order === 'desc'
                                        }"
    >
    By
    </th>
</tr>
</thead>