<?php
use App\Http\Controllers\Web\Internal\Project\ProjectController;
use App\Http\Controllers\Web\Internal\Project\ProjectQuotaController;
use App\Http\Controllers\Web\Internal\Project\ProjectSecurityScreenerController;
use App\Http\Controllers\Web\Internal\Project\ProjectSourceAssignmentController;
use App\Http\Controllers\Web\Internal\Project\ProjectInviteController;
use App\Http\Controllers\Web\Internal\Project\ProjectReviewLaunchController;
use App\Http\Controllers\Web\Internal\Project\ProjectVendorManagementController;
use App\Http\Controllers\Web\Internal\Project\ProjectSurveyManagementController;
use App\Http\Controllers\Web\Internal\Project\ProjectReportController;
use App\Http\Controllers\Web\Internal\Project\ProjectStatusController;
use App\Http\Controllers\Web\Internal\Question\QuestionController;

Route::group([
    'namespace' => 'Project',
    'as' => 'project.',
    'middleware' => ['permission:access projects|access archives']
], function () {
    /*Listing Related Routes*/
    Route::get('project', [ProjectController::class, 'index'])->name('index');
    Route::get('project/datatables', [ProjectController::class, 'datatable'])->name('datatable');
    Route::get('project/filter/', [ProjectController::class, 'filterGetProjectBulk'])->name('filter.show');
    Route::get('project/view-endpages-link', [ProjectController::class, 'viewEndpageLinks'])
        ->name('view_endpage_links');
    /*Creation Related Routes*/
    Route::get('project/create', [ProjectController::class, 'createProject'])->name('create.show');
    Route::post('project/create', [ProjectController::class, 'postCreateProject'])->name('create.post.show');


    /* Edit Related Routes */
    Route::get('project/edit/{id}', [ProjectController::class, 'editProject'])->name('edit.show');
    Route::post('project/edit/{id}', [ProjectController::class, 'postEditProject'])->name('update.basic');

    /********************* Project Traffics Details Export Section******************************************************************/
    Route::get('project/traffic-export/{id}', [ProjectController::class, 'trafficExport'])->name('export.traffic');


    /*************** Project Quota Section Start ***********************/
    Route::get('project/edit/{id}/respondents', [ProjectQuotaController::class, 'editProjectRespondents'])->name('edit.respondent.show');
    Route::patch('project/edit/{id}/respondents', [ProjectQuotaController::class, 'updateProjectRespondents'])->name('update.respondents');
    Route::get('project/edit-status/{project_quota_id}', [ProjectQuotaController::class, 'editQuotaStatus'])->name('edit.quota.status');
    /*************** Project Quota Section Start ***********************/


    /*************** Security & Screener Start ***********************/
    Route::get('project/edit/{id}/security-screener', [ProjectSecurityScreenerController::class, 'index'])->name('edit.security_screener.show');
    Route::patch('project/edit/{id}/security-screener', [ProjectSecurityScreenerController::class, 'updateSecurityScreener'])->name('update.security_screener');
    /*Related To Security Custom Screener*/
    Route::post('project/custom-screener/preview', [ProjectSecurityScreenerController::class, 'showCustomScreenerPreview'])->name('customscreener.preview');
    Route::post('project/custom-screener/preview/run', [ProjectSecurityScreenerController::class, 'runCustomScreenerPreview'])->name('customscreener.preview.run');
    Route::post('project/custom-screener/preview/run/fetch-next', [ProjectSecurityScreenerController::class, 'fetchNextCustomScreenerPreview'])->name('customscreener.preview.fetch_next');
    /*************** Security & Screener End ***********************/

    /*************** Source Quota Assignment Start ***********************/
    Route::get('project/edit/{id}/source-quota', [ProjectSourceAssignmentController::class, 'editProjectSourcesQuota'])->name('edit.sources_quota.show');
    Route::patch('project/edit/{id}/source-quota', [ProjectSourceAssignmentController::class, 'updateProjectSourcesQuota'])->name('update.sources_quota');
    /*************** Source Quota Assignment End ***********************/

    /********************************** Panel Invite Routes Start **********************************/
    Route::get('project/edit/{id}/panel-invite', [ProjectInviteController::class, 'index'])->name('edit.panel_invite.show');
    Route::get('project/edit/{id}/panel-invite/custom', [ProjectInviteController::class, 'showCustomEditor'])->name('edit.panel_invite.custom.show');
    Route::post('project/edit/{id}/panel-invite/custom/{custom_id}', [ProjectInviteController::class, 'updateCustomEditor'])->name('edit.custom.post');

    Route::get('project/edit/{id}/panel-invite/edit/template/{template_id}', [ProjectInviteController::class, 'editTemplate'])->name('edit.templates.edit');
    Route::post('project/edit/{id}/panel-invite/edit/template/{template_id}', [ProjectInviteController::class, 'postTemplate'])->name('templates.edit.post');


    /********************************** Panel Invite Routes End **********************************/

    /*Review & Launch*/
    Route::get('project/edit/{id}/review-launch', [ProjectReviewLaunchController::class, 'index'])->name('edit.review_launch.show');
    Route::patch('project/edit/{id}/review-launch', [ProjectReviewLaunchController::class, 'launchProject'])->name('edit.review_launch.post');
    /*Review & launch End*/

    /*Vendor Management Routes*/
    Route::get('project/{id}/vendors-management', [ProjectVendorManagementController::class, 'index'])->name('vendors.details');
    Route::get('project/{id}/add_vendor', [ProjectVendorManagementController::class, 'addVendor'])->name('add.vendor');
    Route::post('project/{id}/add_vendor', [ProjectVendorManagementController::class, 'postVendor'])->name('add.vendor');
    Route::get('project/{id}/vendors-management/{vendor_id}', [ProjectVendorManagementController::class, 'editVendor'])->name('vendor.edit');
    Route::get('project/get_vendor/details', [ProjectVendorManagementController::class, 'vendorDetails'])->name('get.vendor.details');

    Route::get('project/quick_export/{id}', [ProjectController::class, 'quickExport'])->name('quick.export');
    Route::post('project/{id}/vendors-management/{vendor_id}', [ProjectVendorManagementController::class, 'editPostVendor'])->name('vendor.edit.post');
    Route::get('project/{id}/vendors-management/{vendor_id}', [ProjectVendorManagementController::class, 'editVendor'])->name('vendor.edit');
    Route::post('project/{id}/vendors-management/{vendor_id}', [ProjectVendorManagementController::class, 'editPostVendor'])->name('vendor.edit.post');
    /*Vendor Management Routes Ends here*/

    /*Survey management Routes*/
    Route::post('project/{id}/survey_management/{vendor_id}', [ProjectSurveyManagementController::class, 'postStatus'])->name('surveys.post.status');
    Route::get('project/{id}/survey_management/{vendor_id}', [ProjectSurveyManagementController::class, 'index'])->name('surveys');
    Route::get('project/view_link', [ProjectSurveyManagementController::class, 'viewLinks'])->name('view_link');

    Route::get('project/{id}/vendors-management/{vendor_id}/create-survey/', [ProjectSurveyManagementController::class, 'createSurveys'])->name('create.surveys');
    Route::post('project/{id}/vendors-management/{vendor_id}/create-survey/', [ProjectSurveyManagementController::class, 'postSurveys'])->name('post.surveys');
    Route::post('project/post/modal/status', [ProjectSurveyManagementController::class, 'postModalSurveys'])->name('change.survey.status');

    /*Survey management Routes Ends Here*/

    /*Project Reporting Routes Start */
    Route::get('project/{id}/report/summary', [ProjectReportController::class, 'index'])->name('report.summary.show');
    Route::get('project/{id}/report/quota-summary', [ProjectReportController::class, 'quotaWiseSummary'])->name('report.quota.summary.show');
    /*Project Reporting Routes End */


    /*General Tasks Routes*/
    Route::get('project/client/fetchvars/', [ProjectController::class, 'fetchClientVars'])->name('client.fetch');
    Route::get('project/language/fetchbycountry', [ProjectController::class, 'fetchLanguagesByCountry'])->name('language.fetch');

    Route::post('project/update_selected', [ProjectStatusController::class, 'updateAllSelected'])->name('update.selected');
    Route::get('project/statusflow/fetch', [ProjectStatusController::class, 'getStatusFlow'])->name('status.fetchflow');
    Route::post('project/change_status', [ProjectStatusController::class, 'changeStatus'])->name('change.status');
    Route::get('project/clone_project/{id}', [ProjectController::class, 'cloneProject'])->name('clone');
    //Route::get('project/{id}/vendors-management', [ProjectVendorManagementController::class, 'index'])->name('vendors.details');


    Route::get('project/vendor/status_flow/fetch', [ProjectSurveyManagementController::class, 'getVendorStatusFlow'])->name('vendor.fetchflow');

    Route::get('project/get-question', [QuestionController::class, 'index'])->name('check.question');
    Route::get('project/global-question', [QuestionController::class, 'globalQuestion'])->name('global.question');
    Route::get('project/all-question', [QuestionController::class, 'allProfileQuestion'])->name('all.profile.question');
    Route::get('project/all-project', [QuestionController::class, 'allProject'])->name('covert.json');
    Route::get('project/language', [QuestionController::class, 'dispayConLang'])->name('langauge.show.json');

    Route::get('daily_stats',[\App\Http\Controllers\Web\internal\DailyStats\DailyStatsController::class,'getDailyStats'])->name('daily_stats');
    Route::get('daily_stats/vendor/{source_code}',[\App\Http\Controllers\Web\internal\DailyStats\DailyStatsController::class,'vendorPerDailyStats'])->name('vendor.daily_stats');
    Route::get('daily_stats/hourly',[\App\Http\Controllers\Web\internal\DailyStats\DailyStatsController::class,'hourlyOverallStats'])->name('hourly.daily_stats');




    Route::get('verify-postcodes',[ProjectQuotaController::class,'verifyPostcodes'])->name('verify.postcodes');

});
