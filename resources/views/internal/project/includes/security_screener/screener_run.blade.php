<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <style>
            .sk-spinner-wave.sk-spinner {
                margin: 0 auto;
                width: 50px;
                height: 30px;
                text-align: center;
                font-size: 10px;
            }
            .sk-spinner-wave div {
                background-color: #1ab394;
                height: 100%;
                width: 6px;
                display: inline-block;
                -webkit-animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
                animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
            }
            .sk-spinner-wave .sk-rect2 {
                -webkit-animation-delay: -1.1s;
                animation-delay: -1.1s;
            }
            .sk-spinner-wave .sk-rect3 {
                -webkit-animation-delay: -1s;
                animation-delay: -1s;
            }
            .sk-spinner-wave .sk-rect4 {
                -webkit-animation-delay: -0.9s;
                animation-delay: -0.9s;
            }
            .sk-spinner-wave .sk-rect5 {
                -webkit-animation-delay: -0.8s;
                animation-delay: -0.8s;
            }
            @-webkit-keyframes sk-waveStretchDelay {
                0%,
                40%,
                100% {
                    -webkit-transform: scaleY(0.4);
                    transform: scaleY(0.4);
                }
                20% {
                    -webkit-transform: scaleY(1);
                    transform: scaleY(1);
                }
            }
            @keyframes sk-waveStretchDelay {
                0%,
                40%,
                100% {
                    -webkit-transform: scaleY(0.4);
                    transform: scaleY(0.4);
                }
                20% {
                    -webkit-transform: scaleY(1);
                    transform: scaleY(1);
                }
            }
        </style>
    </head>
    <body class="container">
        <div class="row">
            <div class="col-12 question_section_col">
                <form type="post" class="custom_screener_run_form">
                    <div class="question_loader" style="display:none;">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                    </div>
                    <div class="card question_section">
                        @include('internal.project.includes.security_screener.screener_single_question')
                    </div>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js" integrity="sha384-zDnhMsjVZfS3hiP7oCBRmfjkQC4fzxVxFhBx8Hkz2aZX8gEvA/jsP3eXRCvzTofP" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
        <script>
            $(document).ready(function () {

            });
            var questionForm = jQuery('.custom_screener_run_form');
            questionForm.validate({
                errorPlacement: function(error, element) {
                    error.appendTo('.errorTxt');
                }
            });
            function saveAndFetchNextQuestion() {

                if(!questionForm.valid()){
                    return false;
                }

                var headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                };

                var postData = questionForm.serialize();

                jQuery('.question_section_col > .card').hide('slow');
                jQuery('.question_loader').show();

                var ajaxObj = axios.post("{{ route('internal.project.customscreener.preview.fetch_next') }}",postData,headers);
                processSingleQuestionAjaxs(ajaxObj);
            }
            function processSingleQuestionAjaxs(ajaxObj){
                ajaxObj
                    .then(function (response) {
                        var questionHtml = response.data;
                        if(questionHtml instanceof Object === false){
                            var questionHtmlResponse = $.parseHTML( questionHtml );
                            jQuery("div.question_section").hide().html(questionHtmlResponse).slideDown('slow');
                        }else{
                            alert("No Question");
                        }


                    }).catch(function (error) {
                    alert('some Error occured');
                    console.log(error);

                }).then(function () {
                    // always executed
                    console.log('always executed');
                    jQuery('.question_loader').hide();
                });
            }
        </script>
    </body>
</html>
