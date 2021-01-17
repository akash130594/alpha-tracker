<div class="card">
    <div class="card-header">
        <strong>Project Details</strong>
        <span class="float-right"><a href="{{route('internal.project.edit.show',[$project->id])}}">Modify</a></span>
    </div>
    <div class="card-body">
        <table class="col-sm-12">
            <tr>
                <td> <label>Survey Code: </label> {{$project->code}}</td>
                <td> <label>Survey Name:</label> {{$project->name}}</td>
                <td><label>Study Type:</label> {{$study_type->name}}</td>
                <td><label>Project Topic:</label> {{$project_topic->name}}</td>
            </tr>
            <tr>
                <td> <label>Client Name: </label> {{$project->client_name}}</td>
                <td><label>Client Variable:</label> {{$project->client_var}}</td>
                <td> <label>Client Link:</label> {{$project->client_link}}</td>
                <td><label>Client Project No.:</label> {{$project->client_project_no}}</td>
            </tr>
            <tr>
                @if($project->unique_ids_flag==0)
                    <td><label>Unique Link:</label>No</td>
                @else
                    <td><label>Unique Links:</label>Yes</td>
                @endif
                @if($project->unique_ids_file==null)
                    <td><label>Unique File Attached:</label>No</td>
                @else
                    <td><label>Unique File Attached:</label>Yes</td>
                @endif
                <td><label>Start Date:</label>{{$project->start_date}}</td>
                <td><label>End Date:</label>{{$project->end_date}}</td>
            </tr>
            <tr>
                <td><label>Project Manager:</label>{{$project->created_by}}</td>
                <td><label>Project Quota:</label>{{$project->quota}}</td>
                <td><label>Project CPI:</label>{{$project->cpi}}</td>
            </tr>
        </table>
    </div>
</div>
