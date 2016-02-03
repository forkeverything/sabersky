<div class="table-responsive">
    <table class="table table-hover table-purchase-requests table-sort">
        <thead>
        <tr>
            <template v-for="heading in headings">
                <th
                    v-if="heading[0] !== 'specification'"
                @click="changeSort(heading[0])"
                class="unselectable"
                :class="{
                            'active': field == heading[0],
                            'asc' : order == '',
                            'desc': order == '-1'
                        }"
                >
                @{{ heading[1] }}
                </th>
                <th
                        v-else
                >Specification</th>
            </template>
        </tr>
        </thead>
        <tbody>
        <template v-for="purchaseRequest in purchaseRequests | orderBy field order | filterBy urgent in 'urgent'">
            <tr
                @click="loadSinglePR(purchaseRequest.id)"
                class="unselectable"
                :class="{
                'urgent': purchaseRequest.urgent
                }"
                v-show="checkShow(purchaseRequest)"
            >
                <td>@{{ purchaseRequest.due | easyDate }}</td>
                <td>@{{ purchaseRequest.project.name }}</td>
                <td>@{{ purchaseRequest.item.name }}</td>
                <td>@{{ purchaseRequest.item.specification | limitString 140 }}</td>
                <td>@{{ purchaseRequest.quantity }}</td>
                <td>@{{ purchaseRequest.user.name }}</td>
                <td>@{{ purchaseRequest.created_at | diffHuman }}</td>
            </tr>
        </template>
        </tbody>
    </table>
</div>