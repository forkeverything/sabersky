<div class="title">
    <h1 class="main">@{{ title }}</h1>
    <h4 v-show="dateMin && dateMax" class="date-range">for period between @{{ dateMin }} until @{{ dateMax }}</h4>
    <h4 v-show="dateMin && ! dateMax" class="date-range">from @{{ dateMin }} until now</h4>
    <h4 v-show="! dateMin && dateMax" class="date-range">before @{{ dateMax }}</h4>
</div>