<li class="clickable"
    role="presentation"
    v-for="state in states"
@click="changeState(state)"
:class="{
                                'active': params.state == state
                            }"
>
<a href="#settings-@{{ state }}"
   aria-controls="settings-@{{ state }}"
   role="tab"
   data-toggle="tab"
   :class="state"
>
    @{{ state | capitalize }}
</a>
</li>