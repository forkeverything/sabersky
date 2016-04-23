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
    props: ['response', 'reqFunction'],
    computed: {
        currentPage: function() {
            return this.response.current_page;
        },
        lastPage: function() {
            return this.response.last_page
        },
        paginatedPages: function () {
            switch (this.currentPage) {
                case 1:
                case 2:
                    if(this.lastPage > 0) {
                        var endPage = (this.lastPage < 5) ? this.lastPage : 5;
                        return this.makePagesArray(1, endPage);
                    } else {
                        return this.makePagesArray(1, 5);
                    }
                    break;
                case this.lastPage:
                case this.lastPage - 1:
                    var startPage = (this.lastPage > 5) ? this.lastPage - 4 : 1;
                    var endPage = this.lastPage;
                    return this.makePagesArray(startPage, endPage);
                    break;
                default:
                    var startPage = this.currentPage - 2;
                    var endPage = this.currentPage + 2;
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
            this.$dispatch('go-to-page', page);
            if (0 < page && page <= this.lastPage && typeof(this.reqFunction) == 'function') this.reqFunction(updateQueryString('page', page));
        }
    },
    events: {

    },
    ready: function() {

    }
});