<div class="shift-label-input no-validate">
    <input type="text" class="not-required"
           v-model="swift" :class="{
                                    'filled': swift.length > 0
                                }">
    <label placeholder="SWIFT / IBAN"></label>
</div>