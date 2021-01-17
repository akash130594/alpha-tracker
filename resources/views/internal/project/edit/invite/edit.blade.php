@extends('internal.layouts.new-app')


@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
          <div class="row mb-4">
             <div class="col">
                 <div class="card">
                    <div class="card-header">
                        <strong>
                            <i class="fas fa-tachometer-alt"></i> @lang('Edit Project')
                        </strong>
                    </div><!--card-header-->

                        <div class="card-body">
                            <div class="row">
                                 <div class="col-sm-12">
                                         @include('internal.project.includes.edit_tabs')
                                        <div class="card">
                                             <div class="card-body">
                                                <div class="card-header">
                                                    <label class="font-weight-bold">Edit</label>
                                                </div>
                                                {{html()->form('post',route('internal.project.templates.edit.post',[$project->id,$invite_details->id]))->open()}}
                                                 <div class="card-body">
                                                   <div class="col-sm-7 float-left">
                                                       <div class="row">
                                                           <label class="font-weight-bold">Name:</label>&nbsp&nbsp
                                                           <span>
                                                               {{$invite_details->name}}
                                                           </span>
                                                       </div>
                                                       <hr>
                                                       <div class="row">
                                                           <label class="font-weight-bold">Label:</label>&nbsp&nbsp
                                                          <span>
                                                            {{$invite_details->label}}
                                                          </span>
                                                       </div>
                                                       <hr>
                                                       <div class="row">
                                                           <label class="font-weight-bold">Subject:</label>&nbsp&nbsp
                                                           <input type="text" class="form-control" size="15" name="subject" value="{{$invite_details->subject}}">
                                                       </div>
                                                       <hr>
                                                   </div>
                                                    <div class="col-sm-12 float-left">
                                                       <div class="row">
                                                           <label class="font-weight-bold">Body:</label>&nbsp&nbsp
                                                           <textarea name="custom_invite_body" id="custom_invite_body" class="form-control disabled_modal_input custom_invite_body")>{{$invite_details->body}}</textarea>
                                                       </div>
                                                    </div>
                                               </div>
                                           </div>
                                           <div class="card-footer">
                                               <button class="btn btn-primary" type="submit">Update</button>
                                           </div>
                                        {{html()->form()->close()}}
                                   </div>
                               </div>
                           </div>
                        </div>
                    </div><!-- row -->
                </div> <!-- card-body -->
            </div><!-- card -->
@endsection

@push('after-styles')
    <style>
        /* for invite section */
        .custom-contact-box.center-version{
            border: 1px solid #e7eaec;
            padding: 0;
        }
        .custom-contact-box.center-version > a {
            display: block;
            background-color: #ffffff;
            padding: 20px 10px;
            text-align: center;
        }
        .custom-contact-box .contact-box-footer {
            text-align: center;
            background-color: #ffffff;
            border-top: 1px solid #e7eaec;
            padding: 15px 20px;
        }
        .input-hidden {
            position: absolute;
            left: -9999px;
        }

        input[type=radio]:checked + label>img {
            border: 1px solid #fff;
            box-shadow: 0 0 3px 3px #090;
        }

        /* Stuff after this is only to make things more pretty */
        input[type=radio] + label>img {
            border: 1px dashed #444;
            width: 150px;
            height: 150px;
            transition: 500ms all;
        }

        input[type=radio]:checked + label>img {
            transform:
                rotateZ(-10deg)
                rotateX(10deg);
        }

    </style>
@endpush

@push('after-scripts')
    <!-- Toastr style -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
    <script src="{{ asset('vendors/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendors/tinymce/js/tinymce/jquery.tinymce.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('#custom_invite_body').tinymce({
                theme: "modern",
            });
            var $value = $('.disabled_modal_input').attr("data-body");
            console.log($value);

        });
        /*tinymce.init({
            selector: '#custom_invite_body'
        });*/
    </script>


@endpush
