Vue.component('select-line-items', {
    name: 'selectLineItems',
    template: '<div class="project-selecter">'+
    '<h5>Project</h5>'+
    '<user-projects-selecter :name.sync="projectID"></user-projects-selecter>'+
    '</div>'+
    '<div class="purchase_requests"'+
    ':class="{'+
    "'inactive': ! projectID"+
    '}"'+
    '>'+
    '<div class="overlay"></div>'+
    '<h5>Purchase Requests</h5>'+
    '<div class="pr-controls">'+
    '<form class="form-pr-search" @submit.prevent="searchPurchaseRequests">'+
    '<input class="form-control input-item-search"'+
    'type="text"'+
    'placeholder="Search by # Number, Item (Brand or Name) or Requester"'+
    '@keyup="searchPurchaseRequests"'+
    'v-model="searchTerm"'+
    ':class="{'+
    "'active': searchTerm && searchTerm.length > 0"+
    '}"'+
    '>'+
    '</form>'+
    '</div>'+
    '<div v-show="hasPurchaseRequests">'+
    '<div class="table-responsive">'+
    '<table class="table table-hover table-standard table-purchase-requests-po-submit">'+
    '<thead>'+
    '<tr>'+
    '<th class="heading-center heading-select-all">'+
    '<div class="checkbox styled">'+
    '<label>'+
    '<i class="fa fa-check-square-o checked" v-show="allPurchaseRequestsChecked"></i>'+
    '<i class="fa fa-square-o empty" v-else></i>'+
    '<input class="clickable hidden"'+
    'type="checkbox"'+
    '@change="selectAllPR"'+
    ':checked="allPurchaseRequestsChecked"'+
    '>'+
    '</label>'+
    '</div>'+
    '</th>'+
    '<th class="clickable"'+
    '@click="changeSort(' + "'number'" +')"'+
    ':class="{'+
    "'current_asc': sort === 'number' && order === 'asc',"+
    "'current_desc': sort === 'number' && order === 'desc'"+
    '}"'+
    '>'+
    'PR'+
    '</th>'+
    '<th class="clickable"'+
    '@click="changeSort(' + "'item_name'" + ')"'+
    ':class="{'+
    "'current_asc': sort === 'item_name' && order === 'asc',"+
    "'current_desc': sort === 'item_name' && order === 'desc'"+
    '}"'+
    '>'+
    'Item'+
    '</th>'+
    '<th class="clickable"'+
    '@click="changeSort(' + "'due'" + ')"'+
    ':class="{'+
    "'current_asc': sort === 'due' && order === 'asc',"+
    "'current_desc': sort === 'due' && order === 'desc'"+
    '}"'+
    '>'+
    'Due</th>'+
    '</tr>'+
    '</thead>'+
    '<tbody>'+
    '<template v-for="purchaseRequest in purchaseRequests">'+
    '<tr class="row-single-pr">'+
    '<td class="col-checkbox">'+
    '<div class="checkbox styled">'+
    '<label>'+
    '<i class="fa fa-check-square-o checked" v-if="alreadySelectedPR(purchaseRequest)"></i>'+
    '<i class="fa fa-square-o empty" v-else></i>'+
    '<input class="clickable hidden"'+
    'type="checkbox"'+
    '@change="selectPR(purchaseRequest)"'+
    ':checked="alreadySelectedPR(purchaseRequest)"'+
    '>'+
    '</label>'+
    '</div>'+
    '</td>'+
    '<td class="no-wrap col-number">'+
    '<a class="dotted clickable" @click="showSinglePR(purchaseRequest)">#{{ purchaseRequest.number }}</a>'+
    '</td>'+
    '<td class="col-item">'+
    '<a class="dotted clickable" @click="showSinglePR(purchaseRequest)">'+
    '<span class="item-brand"'+
    'v-if="purchaseRequest.item.brand.length > 0">{{ purchaseRequest.item.brand }}</span>'+
    '<span class="item-name">{{ purchaseRequest.item.name }}</span>'+
    '</a>'+
    '<div class="bottom">'+
    '<span '+
    'v-if="purchaseRequest.urgent" class="badge-urgent with-tooltip" v-tooltip title="Urgent Request" data-placement="bottom"> <i '+
    'class="fa fa-warning"></i></span>'+
    '<div class="quantity"><label>QTY:</label> {{ purchaseRequest.quantity }}</div>'+
    '</div>'+
    '</td>'+
    '<td class="col-due no-wrap">'+
    '<span class="pr-due">{{ purchaseRequest.due | date }}</span>'+
    '</td>'+
    '</tr>'+
    '</template>'+
    '</tbody>'+
    '</table>'+
    '</div>'+
    '<div class="page-controls bottom">'+
    '<per-page-picker :response="response" :req-function="fetchPurchaseRequests"></per-page-picker>'+
    '<paginator :response="response" :event-name="' + "'po-submit-pr-page'" + '"></paginator>'+
    '</div>'+
    '</div>'+
    '<div class="empty-stage" v-else>'+
    '<i class="fa  fa-hand-rock-o"></i>'+
    '<h4>No Purchase Requests</h4>'+
    '<p>We couldn\'t find any requests to fulfill. Try selecting a different Project or <a '+
    'class="dotted clickable" @click="clearSearch">clear</a> the search.</p>'+
    '</div>'+
    '</div>',
    data: function() {
        return {
            ajaxReady: true,
            ajaxObject: {},
            response: {},
            projectID: '',
            purchaseRequests: [],
            sort: 'number',
            order: 'asc',
            urgent: '',
            searchTerm: ''
        };
    },
    props: ['line-items'],
    computed: {
        hasPurchaseRequests: function () {
            return !_.isEmpty(this.purchaseRequests);
        },
        allPurchaseRequestsChecked: function () {
            var purchaseRequestIDs = _.map(this.purchaseRequests, function (request) {
                return request.id
            });
            var lineItemIDs = _.map(this.lineItems, function (item) {
                return item.id
            });
            return _.intersection(lineItemIDs, purchaseRequestIDs).length === purchaseRequestIDs.length;
        }
    },
    methods: {
        fetchPurchaseRequests: function (page) {
            var self = this;
            page = page || 1;

            var url = '/api/purchase_requests?' +
                'state=open' +
                '&quantity=1+' +
                '&project_id=' + self.projectID +
                '&sort=' + self.sort +
                '&order=' + self.order +
                '&per_page=8' +
                '&search=' + self.searchTerm;

            if (page) url += '&page=' + page;

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
                this.order = (this.order === 'asc') ? 'desc' : 'asc';
                this.fetchPurchaseRequests();
            } else {
                this.sort = sort;
                this.order = 'asc';
                this.fetchPurchaseRequests();
            }
        },
        searchPurchaseRequests: _.debounce(function () {
            var self = this;
            // If we're still waiting on a response cancel, abort, and fire a new request
            if (self.ajaxObject && self.ajaxObject.readyState != 4) self.ajaxObject.abort();
            self.fetchPurchaseRequests();
        }, 200),
        clearSearch: function () {
            this.searchTerm = '';
            this.searchPurchaseRequests();
        },
        selectPR: function (purchaseRequest) {
            this.alreadySelectedPR(purchaseRequest) ? this.lineItems = _.reject(this.lineItems, purchaseRequest) : this.lineItems.push(purchaseRequest);
        },
        alreadySelectedPR: function (purchaseRequest) {
            return _.find(this.lineItems, function (pr) {
                return pr.id === purchaseRequest.id;
            });
        },
        selectAllPR: function () {
            var self = this;
            if (self.allPurchaseRequestsChecked) {
                _.forEach(self.purchaseRequests, function (request) {
                    self.lineItems = _.reject(self.lineItems, request);
                });
            } else {
                _.forEach(self.purchaseRequests, function (request) {
                    if (!self.alreadySelectedPR(request)) self.lineItems.push(request);
                });
            }
        }
    },
    mixins: [modalSinglePR],
    ready: function() {

        // select a new project -> load relevant PRs
        this.$watch('projectID', function (val) {
            if (!val) return;
            this.fetchPurchaseRequests();
        });

        // listen to our custom go to page event name
        vueEventBus.$on('po-submit-pr-page', function (page) {
            this.fetchPurchaseRequests(page);
        }.bind(this));
    }
});