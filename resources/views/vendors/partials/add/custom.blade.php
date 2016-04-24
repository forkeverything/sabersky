<vendor-add-custom inline-template :current-tab="currentTab">
    <div id="vendor-add-custom" v-show="currentTab === 'custom'">
        <h4>Add Custom Vendor</h4>
        @include('errors.list')
        <form action="/vendors/add" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="required">Name</label>
                <input type="text" class="form-control" name="name">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea class="autosize form-control" name="description"></textarea>
            </div>

            <div class="form-group align-end">
                <button type="submit" class="btn btn-solid-blue">Add Vendor</button>
            </div>
        </form>
    </div>
</vendor-add-custom>