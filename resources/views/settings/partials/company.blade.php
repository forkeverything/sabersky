<div id="settings-company" v-show="settingsView === 'company'">
    <h2>Company Information</h2>
    <p>
        View and update your company information as well as define system-wide company settings for your team.
    </p>

    <div class="information">
        <form-errors></form-errors>
        <div class="form-group">
            <h5>Name</h5>
            <input type="text" v-model="company.name" class="form-control">
        </div>
        <div class="form-group">
            <h5>Description</h5>
            <textarea class="form-control autosize"
                      v-model="company.description"
            ></textarea>
        </div>
        <div class="form-group">
            <h5>Currency Symbol</h5>
            <input type="text" v-model="company.currency" class="form-control">
        </div>
        <div class="row">
            <div class="col-sm-offset-9 col-sm-3">
                <button class="btn btn-solid-blue"
                @click="updateCompany"
                :disabled="! canUpdateCompany"
                >
                Update</button>
            </div>
        </div>


    </div>


</div>