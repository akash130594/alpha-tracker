<div class="card">
    <div class="card-header">
        <strong>Security And Screener</strong>
        <span class="float-right">
                                <a href="{{route('internal.project.edit.security_screener.show', [$project->id])}}">Modify</a>
                            </span>
    </div>
    <div class="card-body">
        <table class="col-sm-12">
            <tr>
                <td> <label>Loi Validation:</label>&nbsp{!! (!empty($project->loi_validation))? '<span class="material-icons">check_circle</span>' : '<span class="material-icons">cancel</span>&nbsp;&nbsp' !!}</td>

                <td> <label>Loi Validation Time:</label>&nbsp{!! (!empty($project->loi_validation_time))? '<span class="material-icons">check_circle</span>' : '<span class="material-icons">cancel</span>&nbsp;&nbsp' !!}</td>

                <td> <label>Redirect Flag:</label>&nbsp{!! (!empty($clients->redirector_flag))? '<span class="material-icons">check_circle</span>' : '<span class="material-icons">cancel</span>&nbsp;&nbsp' !!}</td>
            </tr>
        </table>
    </div>
</div>
