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
                <td> <label>Loi Validation:</label>&nbsp{!! (!empty($project->loi_validation))? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>&nbsp;&nbsp' !!}</td>

                <td> <label>Loi Validation Time:</label>&nbsp{!! (!empty($project->loi_validation_time))? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>&nbsp;&nbsp' !!}</td>

                <td> <label>Redirect Flag:</label>&nbsp{!! (!empty($clients->redirector_flag))? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>&nbsp;&nbsp' !!}</td>
            </tr>
        </table>
    </div>
</div>
