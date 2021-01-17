<div class="card">
    <div class="card-body">
        <div class="row">
        {!!Form::hidden('redirector_flag', 0)->id('red_hid')->required(false)!!}
        {!!Form::checkbox('redirector_flag', 'Redirect Screener Data', 1, 0)->checked($client_details->redirector_flag)->required(false)!!}

      {{--  {!!Form::select('status', 'Status', [1 => 'Active', 0 => 'Inactive'],$client_details->status)!!}--}}
        </div>
        <a data-toggle="collapse" aria-expanded="false" aria-controls="client_parameter" href="#client_parameter"> Parameters To Change</a>
        <div class="collapse" id="client_parameter">
                 <div class="row">
                    <div class="col-sm-6">
                        <label>
                            Redirect For Survey Type:
                        </label>&nbsp&nbsp
                        <input type="checkbox" name="redirect_survey_type_flag" value="1" class="select_specific_type">
                    </div>
                    <div class="col-sm-6 select_study_type">
                        <select class="form-control form-control-sm select_study" name="study_type" disabled="disabled">
                        @foreach($study_types as $study_type)
                                <option value="{{$study_type['id']}}">{{$study_type['name']}}</option>
                            @endforeach
                        </select>
                    </div>
              </div>
            <hr>
            <div class="row">
                <div class="col-sm-2">
                <input type="checkbox" name="enable_url" class="age">
                </div>
                <div class="col-sm-4">
                    {!! Form::text('age', 'Age')->placeholder('Age')->disabled()!!}
                </div>
                <div class="col-sm-4">
                    {!! Form::text('age_url', 'Parameter For Age')->placeholder('Enter Parameter for Age')->id('age_url')->disabled()!!}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <input type="checkbox" name="enable_url" class="education">
                </div>
                <div class="col-sm-4">
                    {!! Form::text('education', 'Education')->placeholder('Education')->disabled()!!}
                </div>
                <div class="col-sm-4">
                    {!! Form::text('education_url', 'Parameter For Education')->placeholder('Enter Parameter for Education')->id('education_url')->disabled()!!}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <input type="checkbox" name="enable_url" class="gender">
                </div>
                <div class="col-sm-4">
                    {!! Form::text('gender', 'Gender')->placeholder('Gender')->disabled()!!}
                </div>
                <div class="col-sm-4">
                    {!! Form::text('gender_url', 'Parameter For Gender')->placeholder('Enter Parameter for Gender')->id('gender_url')->disabled()!!}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <input type="checkbox" name="enable_url" class="income">
                </div>
                <div class="col-sm-4">
                    {!! Form::text('income', 'Income')->placeholder('Income')->disabled()!!}
                </div>
                <div class="col-sm-4">
                    {!! Form::text('income_url', 'Parameter For Income')->placeholder('Enter Parameter for Income')->id('income_url')->disabled()!!}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <input type="checkbox" name="enable_url" class="ethinicity">
                </div>
                <div class="col-sm-4">
                    {!! Form::text('ethinicity', 'Ethinicity')->placeholder('Ethinicity')->disabled()!!}
                </div>
                <div class="col-sm-4">
                    {!! Form::text('ethinicity_url', 'Parameter For Ethinicity')->placeholder('Enter Parameter for Ethinicity')->id('ethinicity_url')->disabled()!!}
                </div>
            </div>

        </div>
    </div>
</div>

@push('after-styles')
    <style>
        .select_study_type{
            display: none;
        }
    </style>
    @endpush

@push('after-scripts')
    <script>
        $(document).ready(function () {
            $('.select_specific_type').on('click',function (e) {
                $('.select_study_type').toggle();
                $('.select_study').removeAttr("disabled");
            });
           $('.age').on('change',function (e) {
               if($(this).prop('checked'))
               {
                   $('#age_url').removeAttr("disabled");
               }else{
                   $('#age_url').attr("disabled","disabled");
               }
           })
            $('.education').on('change',function (e) {
                if($(this).prop('checked'))
                {
                    $('#education_url').removeAttr("disabled");
                }else{
                    $('#education_url').attr("disabled","disabled");
                }
            })
            $('.gender').on('change',function (e) {
                if($(this).prop('checked'))
                {
                    $('#gender_url').removeAttr("disabled");
                }else{
                    $('#gender_url').attr("disabled","disabled");
                }
            })
            $('.income').on('change',function (e) {
                if($(this).prop('checked'))
                {
                    $('#income_url').removeAttr("disabled");
                }else{
                    $('#income_url').attr("disabled","disabled");
                }
            })
            $('.ethinicity').on('change',function (e) {
                if($(this).prop('checked'))
                {
                    $('#ethinicity_url').removeAttr("disabled");
                }else{
                    $('#ethinicity_url').attr("disabled","disabled");
                }
            })
        })
    </script>
    @endpush
