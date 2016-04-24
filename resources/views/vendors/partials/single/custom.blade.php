<vendor-custom inline-template>
    <div class="container" id="vendor-single-custom">
        <input type="hidden" value="{{ $vendor->id }}" v-model="vendorID">
        <div class="page-body">
            <form-errors></form-errors>
            <section class="description">
                @can('edit', $vendor)
                <h5 class="loading-header"
                    :class="{
                        'loading': savedDescription === 'saving',
                        'success': savedDescription === 'success',
                        'error': savedDescription === 'error'
                    }"
                >Description</h5>
                <div class="form-group">
                    <p v-if="description.length > 0" @click="startEditDescription" v-show="! editDescription"
                    >@{{ description }}</p>
                    <span v-else class="no-description" @click="startEditDescription" v-show="! editDescription">None -
                    click to write a description</span>
                    <textarea class="autosize description-editor form-control live-editor" v-model="description"
                              v-show="editDescription" @blur="saveDescription">{{ $vendor->description }}</textarea>
                </div>
                @else
                    <h5>Description</h5>
                    @if($vendor->description)
                        <p>{{ $vendor->description }}</p>
                    @else
                        <span class="no-description">None</span>
                    @endif
                    @endcan
            </section>
            <section class="addresses">
                <h5>Addresses</h5>
                @can('edit', $vendor)
                    <add-address-modal :model-id="{{ $vendor->id }}" :model-type="'vendor'"></add-address-modal>
                @endcan
                @if($vendor->addresses->first())
                    <ul class="list-unstyled list-inline">
                        @foreach($vendor->addresses as $address)
                            {{ $address }}
                        @endforeach
                    </ul>
                @else
                    <em>None</em>
                @endif
            </section>
        </div>
    </div>
</vendor-custom>