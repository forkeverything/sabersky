<li class="clickable"
    role="presentation"
    v-for="status in statuses"
@click="changeStatus(status)"
:class="{
                                'active': params.status === status
                            }"
>
<a href="#settings-@{{ status }}"
   aria-controls="settings-@{{ status }}"
   role="tab"
   data-toggle="tab"
   :class="status"
>
    @{{ status | capitalize }}
</a>
</li>