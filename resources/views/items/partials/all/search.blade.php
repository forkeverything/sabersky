<input class="form-control input-item-search"
       type="text"
       placeholder="Search by SKU, Brand or Name"
@keyup="searchTerm"
v-model="params.search"
:class="{
                                    'active': params.search && params.search.length > 0
                               }"
>