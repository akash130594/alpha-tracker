<div class="sk-spinner sk-spinner-wave frame_loader">
    <div class="sk-rect1"></div>
    <div class="sk-rect2"></div>
    <div class="sk-rect3"></div>
    <div class="sk-rect4"></div>
    <div class="sk-rect5"></div>
</div>
<form id="custom_screener_preview_submit_form" target="custom_screener_preview_form" action="{{ route('internal.project.customscreener.preview.run') }}" method="post">
    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
    <input type="hidden" class="global_serialize_preview_field" name="serialize_output" value="{{$screener_data}}" />
    <input type="submit" value="Start">
</form>
<iFrame src="" name="custom_screener_preview_form" id="custom_screener_preview_frame" style="width: 100%;height: 400px;display: none;">
    Your browser does not support inline frames.
</iFrame>

<script>
    $(document).ready(function(){
        $previewForm = $('#custom_screener_preview_submit_form');

        $previewForm.on('submit', function (e) {
            $('#custom_screener_preview_frame').show();
            $(this).hide();
            $('.frame_loader').hide();
        });
    });
</script>
