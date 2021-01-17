<div class="card">
    <div class="card-header card text-white bg-dark mb-3">
        <strong>
            <i class="fas fa-tachometer-alt"></i> {{$source->name}}&nbsp;@lang('- STANDARD END PAGES')
        </strong>
    </div><!--card-header-->
    <div class="card-body border link">
          @if($source->complete_url)
              <label class="text-dark font-weight-bold">Complete URL</label>
              <div class="form-group">
                  <div class="input-group">
                      <input readonly class="form-control" type="text" autocomplete="off" value="{{$source->complete_url}}">
                  </div>
              </div>
          @endif
          @if($source->terminate_url)
              <label class="text-dark font-weight-bold">Terminate URL</label>
              <div class="form-group">
                  <div class="input-group">
                      <input readonly class="form-control" type="text" autocomplete="off" value="{{$source->terminate_url}}">
                  </div>
              </div>
          @endif
          @if($source->quotafull_url)
              <label class="text-dark font-weight-bold">Quota Full URL</label>
              <div class="form-group">
                  <div class="input-group">
                      <input readonly class="form-control" type="text" autocomplete="off" value="{{$source->quotafull_url}}">
                  </div>
              </div>
          @endif
          @if($source->quality_term_url)
              <label class="text-dark font-weight-bold">Quality Term URL</label>
              <div class="form-group">
                  <div class="input-group">
                      <input readonly class="form-control" type="text" autocomplete="off" value="{{$source->quality_term_url}}">
                  </div>
              </div>
          @endif
    </div>
</div>
