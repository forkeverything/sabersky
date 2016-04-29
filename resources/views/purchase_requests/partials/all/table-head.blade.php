<thead>
<tr>
    <th class="clickable"
    @click="changeSort('number')"
    :class="{
                                            'current_asc': sort === 'number' && order === 'asc',
                                            'current_desc': sort === 'number' && order === 'desc'
                                        }"
    >
    PR
    </th>
    <th class="clickable"
    @click="changeSort('project_name')"
    :class="{
                                            'current_asc': sort === 'project_name' && order === 'asc',
                                            'current_desc': sort === 'project_name' && order === 'desc'
                                        }"
    >
    Project
    </th>
    <th class="clickable"
    @click="changeSort('quantity')"
    :class="{
                                            'current_asc': sort === 'quantity' && order === 'asc',
                                            'current_desc': sort === 'quantity' && order === 'desc'
                                        }"
    >
    Qty
    </th>
    <th class="clickable"
    @click="changeSort('item_name')"
    :class="{
                                            'current_asc': sort === 'item_name' && order === 'asc',
                                            'current_desc': sort === 'item_name' && order === 'desc'
                                        }"
    >
    Item
    </th>
    <th class="clickable"
    @click="changeSort('due')"
    :class="{
                                            'current_asc': sort === 'due' && order === 'asc',
                                            'current_desc': sort === 'due' && order === 'desc'
                                        }"
    >
    Due</th>
    <th class="clickable"
    @click="changeSort('created_at')"
    :class="{
                                            'current_asc': sort === 'created_at' && order === 'asc',
                                            'current_desc': sort === 'created_at' && order === 'desc'
                                        }"
    >
    Requested
    </th>
    <th class="clickable"
    @click="changeSort('requester_name')"
    :class="{
                                            'current_asc': sort === 'requester_name' && order === 'asc',
                                            'current_desc': sort === 'requester_name' && order === 'desc'
                                        }"
    >
    By
    </th>
</tr>
</thead>