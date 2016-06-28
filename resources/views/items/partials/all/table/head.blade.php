<th></th>
<th class="clickable"
@click="changeSort('name')"
:class="{
                                            'current_asc': params.sort === 'name' && params.order === 'asc',
                                            'current_desc': params.sort === 'name' && params.order === 'desc'
                                        }"
>
Details</th>
<th class="clickable"
@click="changeSort('sku')"
:class="{
                                            'current_asc': params.sort === 'sku' && params.order === 'asc',
                                            'current_desc': params.sort === 'sku' && params.order === 'desc'
                                        }"
>
SKU</th>
<th class="heading-center">Projects</th>