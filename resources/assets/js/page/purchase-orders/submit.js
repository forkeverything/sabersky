Vue.component('purchase-orders-submit', {
    el: function () {
        return '#purchase-orders-submit';
    },
    data: function () {
        return {
            ajaxReady: true,
            ajaxObject: {},
            response: {},
            projects: [],
            projectID: '',
            purchaseRequests: [],
            sort: '',
            order: '',
            urgent: '',
            searchTerm: '',
            selectedPRs: []
        };
    },
    computed: {
        hasPurchaseRequests: function() {
            return ! _.isEmpty(this.purchaseRequests);
        }
    },
    methods: {
        fetchPurchaseRequests: function (projectID, sort, order, page, search) {
            var self = this;

            sort = sort || 'number';
            order = order || 'asc';
            search = search || '';

            var url = '/api/purchase_requests?' +
                'state=open' +
                '&quantity=1+' +
                '&project_id=' + projectID +
                '&sort=' + sort +
                '&order=' + order +
                '&per_page=3' +
                '&search=' + search;

            if(page) url += '&page=' + page;
            
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            self.ajaxObject = $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    // Update data
                    self.response = response;

                    self.purchaseRequests = _.omit(response.data, 'query_parameters');

                    // Pull flags from response (better than parsing url)
                    self.sort = response.data.query_parameters.sort;
                    self.order = response.data.query_parameters.order;
                    self.urgent = response.data.query_parameters.urgent;

                    self.ajaxReady = true;

                    // self.$nextTick(function() {
                    //     self.finishLoading = true;
                    // })
                    // TODO ::: Add a loader for each request

                },
                error: function (res, status, req) {
                    console.log(status);
                    self.ajaxReady = true;
                }
            });
        },
        changeSort: function (sort) {
            if (this.sort === sort) {
                var newOrder = (this.order === 'asc') ? 'desc' : 'asc';
                this.fetchPurchaseRequests(this.projectID, this.sort, newOrder);
            } else {
                this.fetchPurchaseRequests(this.projectID, sort, 'asc');
            }
        },
        searchPurchaseRequests: function() {
            var self = this;

            if (self.ajaxObject && self.ajaxObject.readyState != 4) self.ajaxObject.abort();

            self.fetchPurchaseRequests(self.projectID, self.sort, self.order, 1, self.searchTerm);
        },
        clearSearch: function() {
            this.searchTerm = '';
            this.searchPurchaseRequests();
        },
        selectPR: function(purchaseRequest) {
            this.alreadySelectedPR(purchaseRequest) ? this.selectedPRs = _.reject(this.selectedPRs, purchaseRequest) : this.selectedPRs.push(purchaseRequest) ;
        },
        alreadySelectedPR: function(purchaseRequest) {
            return _.find(this.selectedPRs, function(pr) {
                return pr.id === purchaseRequest.id;
            });
        }
    },
    events: {
        'go-to-page': function (page) {
            this.fetchPurchaseRequests(this.projectID, this.sort, 'asc', page);
        }
    },
    ready: function () {
        this.$watch('projectID', function (val) {
            if (val)this.fetchPurchaseRequests(val);
        });
    }
});