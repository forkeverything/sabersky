Vue.component('paginator', {
    name: 'paginatoe',
    template: '<div class="api-paginator">' +
    '<ul class="list-unstyled list-inline">' +
    '   <li class="paginate-nav to-first"' +
    '       :class="{' +
    "           'disabled': currentPage < 3  || currentPage > lastPage" +
    '       }"' +
    '       @click="goToPage(1)"' +
    '   >'+
    '       <i class="fa fa-angle-double-left"></i>' +
    '   </li>'+
    '   <li class="paginate-nav prev"' +
    '       :class="{'+
    "           'disabled': (currentPage - 1) < 1 || currentPage > lastPage" +
    '       }"'+
    '       @click="goToPage(currentPage - 1)"'+
    '   >'+
    '       <i class="fa fa-angle-left"></i>'+
    '   </li>'+
    '   <li class="paginate-link"'+
    '       v-for="page in paginatedPages"'+
    '       :class="{' +
                "'current_page': currentPage === page,"+
                "'disabled': page > lastPage"+
    '       }"'+
    '       @click="goToPage(page)"'+
    '   >'+
    '       {{ page }}'+
    '   </li>'+
    '   <li class="paginate-nav next"'+
    '       :class="{'+
                "'disabled': currentPage >= lastPage"+
    '       }"'+
    '       @click="goToPage(currentPage + 1)"'+
    '    >'+
    '       <i class="fa fa-angle-right"></i>'+
    '   </li>'+
    '   <li class="paginate-nav to-last"'+
    '       :class="{'+
    "           'disabled': currentPage > (lastPage - 2)"+
    '       }"'+
    '       @click="goToPage(lastPage)"'+
    '   >'+
    '       <i class="fa fa-angle-double-right"></i>'+
    '   </li>'+
    '</ul>'+
    '</div>',
    data: function() {
        return {

        };
    },
    props: ['response', 'reqFunction', 'event-name'],
    computed: {
        currentPage: function() {
            return this.response.current_page;
        },
        lastPage: function() {
            return this.response.last_page
        },
        paginatedPages: function () {
            var startPage;
            var endPage;
            switch (this.currentPage) {
                case 1:
                case 2:
                    // First 2 pages - always return first 5 pages
                    return this.makePagesArray(1, 5);
                    break;
                case this.lastPage:
                case this.lastPage - 1:
                    // Last 2 pages - return last 5 pages
                        // If we have more than 5 pages count back 4 pages. Else start at page 1
                        startPage = (this.lastPage > 5) ? this.lastPage - 4 : 1;
                        endPage = (this.lastPage > 5 ) ? this.lastPage : 5;
                    return this.makePagesArray(startPage, endPage);
                    break;
                default:
                    startPage = this.currentPage - 2;
                    endPage = this.currentPage + 2;
                    return this.makePagesArray(startPage, endPage);
            }
        }
    },
    methods: {
        makePagesArray: function (startPage, endPage) {
            var pagesArray = [];
            for (var i = startPage; i <= endPage; i++) {
                pagesArray.push(i);
            }
            return pagesArray;
        },
        goToPage: function (page) {
            // if we get a custom event name - fire it
            if(this.eventName) vueEventBus.$emit(this.eventName, page);
            vueEventBus.$emit('go-to-page', page);
            this.$dispatch('go-to-page', page);         // TODO ::: REMOVE WILL BE DEPRACATED Vue 2.0 <
            if (0 < page && page <= this.lastPage && typeof(this.reqFunction) == 'function') this.reqFunction(updateQueryString('page', page));
        }
    },
    events: {

    },
    ready: function() {

    }
});