<settings-company inline-template :settings-view.sync="settingsView" :user.sync="user">
    <div id="settings-company"  v-show="settingsView === 'company'">
        <h2>Company Information</h2>
        <p>
            View and update your company information as well as define system-wide company settings for your team.
        </p>

        <div class="information">
            <form-errors></form-errors>
            <div class="form-group">
                <h5>Name</h5>
                <input type="text" v-model="user.company.name" class="form-control">
            </div>
            <div class="form-group">
                <h5>Description</h5>
            <textarea class="form-control autosize"
                      v-model="user.company.description"
            ></textarea>
            </div>
            <div class="form-group">
                <h5>Default Currency</h5>
                <currency-selecter :name.sync="user.company.settings.currency_id" :default="user.company.settings.currency"></currency-selecter>
            </div>
            <div class="form-group">
                <h5>Money Decimal Places</h5>
                <number-input :model.sync="user.company.settings.currency_decimal_points" :placeholder="'Decimals'" :class="['form-control']"></number-input>
            </div>
            <div class="row">
                <div class="col-xs-12 align-end">
                    <button class="btn btn-solid-blue"
                    @click="updateCompany"
                    :disabled="! canUpdateCompany"
                    >
                    Update</button>
                </div>
            </div>


        </div>
    </div>
</settings-company>
