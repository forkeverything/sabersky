<input type="checkbox"
       id="checkbox-pr-urgent"
       v-model="params.urgent"
@click="toggleUrgentOnly"
>
<label class="clickable"
       for="checkbox-pr-urgent"
><i class="fa fa-warning badge-urgent"></i> Urgent only</label>