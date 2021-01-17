<?php
use App\Http\Controllers\Web\Internal\General\GeneralController;
use App\Http\Controllers\Web\Internal\QueryPL\PLQueryController;


Route::group([
    'namespace' => 'General',
    'as' => 'general.',
    'middleware' => ['permission:access general']
], function () {
    Route::get('general/datatables', [GeneralController::class, 'datatable'])->name('country.datatable');
    Route::get('general', [GeneralController::class, 'index'])->name('country.index');
    Route::get('general/edit/{id}', [GeneralController::class, 'editCountry'])->name('country.edit.show');
    Route::post('general/edit/{id}', [GeneralController::class, 'postCountry'])->name('country.edit.post');
    Route::get('general/create', [GeneralController::class, 'createCountry'])->name('country.create');
    Route::post('general/create', [GeneralController::class, 'postCreateCountry'])->name('country.post');
    Route::get('general/delete/country/{id}', [GeneralController::class, 'deleteCountry'])->name('delete.show');
    Route::get('general/language/datatable', [GeneralController::class, 'datatableLanguage'])->name('language.datatable');
    Route::get('general/language/edit/{id}', [GeneralController::class, 'editLanguage'])->name('language.edit.show');
    Route::post('general/language/edit/{id}', [GeneralController::class, 'postLanguage'])->name('language.post.show');

    Route::get('general/language/create', [GeneralController::class, 'createLanguage'])->name('language.create');
    Route::post('general/language/create', [GeneralController::class, 'postCreateLanguage'])->name('language.post');
    Route::get('general/language/delete/{id}', [GeneralController::class, 'deleteLanguage'])->name('language.delete.show');

    Route::get('general/study_types/datatable', [GeneralController::class, 'datatableStudyType'])->name('study_type.datatable');
    Route::get('general/study_types/edit/{id}', [GeneralController::class, 'editStudyType'])->name('study_type.edit.show');
    Route::post('general/study_types/edit/{id}', [GeneralController::class, 'postStudyType'])->name('study_type.post.show');
    Route::get('general/study_types/create', [GeneralController::class, 'createStudyType'])->name('study_type.create');
    Route::post('general/study_types/create', [GeneralController::class, 'postCreateStudyType'])->name('study_type.create');
    Route::get('general/study/delete/{id}', [GeneralController::class, 'deleteStudy'])->name('study_type.delete.show');


    Route::get('general/survey_topic/datatable', [GeneralController::class, 'datatableSurveyTopic'])->name('survey_topic.datatable');
    Route::get('general/survey_topic/edit/{id}', [GeneralController::class, 'editSurveyTopic'])->name('survey_topic.edit.show');
    Route::post('general/survey_topic/edit/{id}', [GeneralController::class, 'postSurveyTopic'])->name('survey_topic.post.show');
    Route::get('general/survey_topic/create', [GeneralController::class, 'createSurveyTopic'])->name('survey_topic.create');
    Route::post('general/survey_topic/create', [GeneralController::class, 'postCreateSurveyTopic'])->name('survey_topic.post');
    Route::get('general/survey_topic/delete/{id}', [GeneralController::class, 'deleteSurveyTopic'])->name('survey_topic.delete.show');

    Route::get('general/language', [GeneralController::class, 'languageDetails'])->name('language.index');
    Route::get('general/study_type', [GeneralController::class, 'studyTypeDetails'])->name('study_type.index');
    Route::get('general/survey_topic', [GeneralController::class, 'surveyTopicDetails'])->name('survey_topic.index');
    Route::get('monthly/survey', [PLQueryController::class, 'index'])->name('survey.monthly');

});
