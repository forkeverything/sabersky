<vendor-add-search inline-template :current-tab="currentTab">
    <div id="vendor-add-search" v-show="currentTab === 'search'">
            <h4>Search for Registered Company</h4>
        <form-errors></form-errors>
        <div class="form-group">
            <select id="vendor-search-company-selecter">
                <option></option>
            </select>
        </div>
        <div class="form-group align-end">
            <button type="button" class="btn btn-solid-blue" @click="addCompanyAsNewVendor">Send Add Vendor Request</button>
        </div>
    </div>
</vendor-add-search>