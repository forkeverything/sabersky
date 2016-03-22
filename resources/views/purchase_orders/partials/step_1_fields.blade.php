<section class="form-section-project">
    <h2>Step 1: Select Project</h2>
    <div class="form-group">
        <label for="field-project-id">Which Project is this Purchase Order for?</label>
        <select name="project_id" id="field-project-id" class="form-control">
            <option disabled selected>Select a project</option>
            @foreach(Auth::user()->projects as $project)
                <option value="{{ $project->id }}" class="capitalize">{{ $project->name }}</option>
            @endforeach
        </select>
    </div>
</section>