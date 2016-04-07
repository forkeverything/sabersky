<div class="pr-paginate">
    <ul class="list-unstyled list-inline">
        <li class="paginate-nav to-first"
            :class="{
                                'disabled': currentPage < 3
                            }"
        @click="goToPage(1)"
        >
        <i class="fa fa-angle-double-left"></i>
        </li>
        <li class="paginate-nav prev"
            :class="{
                                'disabled': (currentPage - 1) < 1
                            }"
        @click="goToPage(currentPage - 1)"
        >
        <i class="fa fa-angle-left"></i>
        </li>
        <li class="paginate-link"
            v-for="page in paginatedPages"
            :class="{
                                        'current_page': currentPage === page
                                    }"
        @click="goToPage(page)"
        >
        @{{ page }}
        </li>
        <li class="paginate-nav next"
            :class="{
                                'disabled': currentPage === lastPage
                            }"
        @click="goToPage(currentPage + 1)"
        >
        <i class="fa fa-angle-right"></i>
        </li>
        <li class="paginate-nav to-last"
            :class="{
                                'disabled': currentPage > (lastPage - 2)
                            }"
        @click="goToPage(lastPage)"
        >
        <i class="fa fa-angle-double-right"></i>
        </li>
    </ul>
</div>