<form class="form-search" @submit.prevent="searchTerm">
    <input class="form-control input-search"
           type="text"
           placeholder="Search..."
    @keyup="searchTerm"
    v-model="params.search"
    :class="{
                                    'active': params.search && params.search.length > 0
                               }"
    >
</form>