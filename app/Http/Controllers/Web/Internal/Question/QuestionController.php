<?php

namespace App\Http\Controllers\Web\Internal\Question;

use App\Models\Client\Client;
use App\Models\General\Country;
use App\Models\MasterQuestion\GlobalQuestion;
use App\Models\Project\Project;
use App\Models\Project\ProjectQuota;
use App\Models\Sjpanel\ProfileQuestion;
use App\Models\Traffics\Traffic;
use App\Repositories\BaseMongoRepository;
use App\Repositories\Internal\MasterQuestion\GlobalQuestionsRepository;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public $fulcrum_vendor_code = 'FL';

    public $globalQuesRepo;

    public function __construct(GlobalQuestionsRepository $globalQuesRepo)
    {
        $this->globalQuesRepo = $globalQuesRepo;
    }

    public function index()
    {
        $countriesLangs = [
            240 => [
                'code' => 'US',
                'id' => 240,
                'LANG' => [
                    'EN',
                    'ES'
                ]
            ]
        ];
        $questionItems = [];
        $profileQuestions = $this->getProfileQuestions();
        foreach ($profileQuestions as $profile_question) {
            $questionItems[] = [
                'id' => $profile_question->display_name,
                'display_name' => $profile_question->display_name,
                'profile_section_id' => $profile_question->profile_section_id,
                'country_code' => $profile_question->country_code,
                'translated' => $this->getQuestionTranslation($profile_question),
                /*'question_mapping' => $this->getQuestionsMapping($profile_question, $profile_question),*/
            ];
            //$question_item['answers'] = $this->getQuestionsAnswers($country, $profile_question);
            // $questionItems[$profile_question->id] = $question_item;
        }

        dd($questionItems);
        $connection = BaseMongoRepository::getConnection()->collection('questions_master');
        /*foreach ($questionItems as $id => $question) {
            $connection->insert($question);
        }*/
        dd('done');
    }

    public function getQuestionTranslation($profile_question)
    {
        $translated = [];
        $country_lang = $this->getCountryLang($profile_question->country_id);
        $language = $country_lang->language;
        $language = explode(",", $language);
        foreach ($language as $lang) {
            $locale = strtolower($lang) . "_" . $profile_question->country_code;

            $local_temp[] = $locale;
            $questions_translated = DB::connection('mysql_sjpanel')->table('profile_question_translations')
                ->select('*')
                ->where('locale', '=', $locale)
                ->where('profile_question_id', '=', $profile_question->id)
                ->first();
            $translated['translated'][] = [
                'con_lang' => $profile_question->country_code . "-" . $lang,
                'country_code' => $profile_question->country_code,
                'language_code' => $lang,
                'text' => $questions_translated->label,
                'hint' => $questions_translated->hint,
                'answer' => $this->getAnswer($profile_question, $locale),
                'question_mapping' => $this->getFulcrumQuestion($profile_question, $lang),
            ];
        }
        return $translated;
    }

    public function getAnswer($profile_question, $locale)
    {
        $profile_answers = [];
        /*  $data = DB::connection('mysql_sjpanel')->table('profile_answers')
              ->select('*')
              ->where('profile_question_id','=',$profile_question->id)
              ->where('locale',$locale)
              ->get();*/
        $data = DB::connection('mysql_sjpanel')
            ->table('profile_answers AS pa')
            ->select('pa.id', 'pa.display_name', 'pa.precode', 'pa.precode_type', 'trans.answer_text as text', 'trans.hint as hint')
            ->leftJoin('profile_answer_translations as trans', 'pa.id', '=', 'trans.profile_answer_id')
            ->where('pa.profile_question_id', '=', $profile_question->id)
            ->where('trans.locale', '=', $locale)
            ->get();
        foreach ($data as $profileAnswer) {
            $profile_answers[] = [
                'display_name' => $profileAnswer->display_name,
                'precode' => $profileAnswer->precode,
                'precode_type' => $profileAnswer->precode_type,
                'text' => $profileAnswer->text,
            ];
        }
        return $profile_answers;
    }


    public function getCountryLang($country_id)
    {
        $data = DB::table('countries')
            ->select('*')
            ->where('id', $country_id)
            ->first();
        return $data;
    }

    private function getQuestionsMapping($country, $profile_question)
    {
        $mappingData = [];
        foreach ($country['LANG'] as $language_code) {
            $get_language_id = $this->getLanguageId($language_code);
            $mappingData[$language_code] = [
                $this->fulcrum_vendor_code => $this->getFLMappingData($profile_question, $language_code, $country, $get_language_id)
            ];
        }
        return $mappingData;
    }

    private function getQuestionsAnswers($country, $profile_question)
    {
        $answerItems = [];
        $profileAnswers = $this->getProfileQuestionAnswers($profile_question->id);
        foreach ($profileAnswers as $answer) {
            $answerItem = [];
            $answerItem['answer_id'] = $answer->id;
            $answerItem['precode'] = $answer->precode;
            $answerItem['precode_type'] = $answer->precode_type;
            $answerItem['display_name'] = $answer->display_name;
            $answerItem['answers_mapping'] = $this->getAnswersMapping($country, $profile_question, $answer);
            $answerItems[$answer->id] = $answerItem;
        }
        return $answerItems;
    }

    private function getAnswersMapping($country, $profile_question, $answer)
    {
        $mappingData = [];
        foreach ($country['LANG'] as $language_code) {
            $mappingData[$language_code] = [
                $this->fulcrum_vendor_code => $this->getFLAnswerMappingData($profile_question, $answer, $language_code, $country)
            ];
        }
        return $mappingData;
    }

    private function getFLMappingData($profile_question, $language_code, $country, $get_language_id)
    {
        $fulcrumData = [];
        $get_fulcrum_questions = $this->getFulcrumQuestion($profile_question, $language_code, $country, $get_language_id);
        if (!empty($get_fulcrum_questions->fulcrum_question_id)) {
            $fulcrumData['question_id'] = $get_fulcrum_questions->fulcrum_question_id;
        } else {
            $fulcrumData['question_id'] = '';
        }
        $fulcrumData['country_id'] = $get_fulcrum_questions->fulcrum_country_language_id;
        $fulcrumData['lang_id'] = $get_fulcrum_questions->language_id;
        return $fulcrumData;
    }

    private function getFLAnswerMappingData($profile_question, $answer, $language_code, $country)
    {
        $fulcrumData = [];
        $get_language_id = DB::table('languages')->select('id')->where('code', $language_code)->first();
        $get_fulcrum_answer = DB::connection('mysql_sjpanel')->table('fulcrum_answers')
            ->where('profile_question_id', $profile_question->id)
            ->where('language_id', $get_language_id->id)
            ->where('country', $country['code'])
            ->get();
        foreach ($get_fulcrum_answer as $fulcrum_answer) {
            if (!empty($fulcrum_answer->fulcrum_question_id)) {
                $fulcrumData['question_id'] = $fulcrum_answer->fulcrum_question_id;
            } else {
                $fulcrumData['question_id'] = '';
            }
            $fulcrumData['precode'] = $fulcrum_answer->precode;
            $fulcrumData['country_id'] = '';
            $fulcrumData['language_id'] = $fulcrum_answer->fulcrum_country_language_id;
        }
        return $fulcrumData;
    }

    public function getProfileQuestionAnswers($question_id)
    {
        $data = DB::connection('mysql_sjpanel')->table('profile_answers')
            ->select('*')
            ->where('profile_answers.profile_question_id', '=', $question_id)
            ->get();
        return $data;

    }

    private function getProfileQuestions()
    {
        $questions = DB::connection('mysql_sjpanel')
            ->table('profile_questions')
            ->select('*')
            ->take(1)
            /*->where('id','=',18)*/
            /*->skip(10)
            ->take(5)*/
            ->get();
        return $questions;
    }

    public function getFulcrumData($get_language_id)
    {
        $lang = [];
        foreach ($get_language_id as $lang_id) {
            $lang[] = $lang_id;
        }
        $data = DB::connection('mysql_sjpanel')->table('fulcrum_question')
            ->select('*')
            ->where('question_id', '=', 1)
            ->get();
        return $data;
    }

    public function getLanguageId($language_code)
    {
        $data = DB::table('languages')
            ->select('id')
            ->where('code', $language_code)
            ->first();
        return $data;
    }

    public function getFulcrumAnswer($profile_question, $fulcrum_data)
    {
        $fulcrum_answer = [];
        $data = DB::connection('mysql_sjpanel')->table('fulcrum_answers')
            ->select('*')
            ->where('fulcrum_country_language_id', $fulcrum_data->fulcrum_country_language_id)
            ->where('profile_question_id', $profile_question->id)
            ->get();
        foreach ($data as $answer) {
            $fulcrum_answer[] = [
                'display_name' => $answer->display_name,
                'precode' => $answer->fulcrum_precode,
                'text' => $answer->display_name,
            ];
        }
        return $fulcrum_answer;
    }

    public function getFulcrumQuestion($profile_question, $lang)
    {
        $question_map = [];
        $get_lang_id = DB::table('languages')->select('id')->where('code', $lang)->first();
        $data = DB::connection('mysql_sjpanel')->table('fulcrum_question')
            ->select('*')
            ->where('question_id', $profile_question->id)
            ->where('language_id', $get_lang_id->id)
            ->where('country', $profile_question->country_code)
            ->first();
        $question_map['FL'] = [
            'fulcrum_question_id' => $data->fulcrum_question_id,
            'display_name' => $data->display_name,
            'fulcrum_country_language_code' => $data->fulcrum_country_language_code,
            'country' => $data->country,
        ];


        return $question_map;
    }


    /*---------------------------------------------------------------------------------------------------------------------*/

    public function globalQuestion()
    {


       /* $get_country_details = Country::all()->toArray();
        $country_details = [];
        foreach($get_country_details as $key => $value){
            $country_details[$key] = $value;
            unset($country_details[$key]['id']);

        }
        $json_country = json_encode($country_details);
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR."country.json", $json_country);
        dd("done");*/


        set_time_limit(0);
        $countriesLangs = [
            240 => [
                'code' => 'US',
                'id' => 240,
                'LANG' => [
                    'EN',
                    'ES'
                ]
            ]
        ];
        $globalQues = [

            'GLOBAL_AGE' => [
                'general_name' => 'GLOBAL_AGE',
                'display_name' => 'GLOBAL_AGE',
                'type' => 'open',
                'show_as' => 'text',
                'order' => 10,
                'profile_section_id' => 0,
                'countries_map' => [
                    /* 'US' => [
                         'langs' => ['EN', 'ES'],
                         'q_id' => 1,
                     ],
                     'UK' => [
                         'langs' => ['EN'],
                         'q_id' => 4,
                     ],
                     'CA' => [
                         'langs' => ['EN','FR'],
                         'q_id' => 22,
                     ],
                     'FR' => [
                         'langs' => ['EN', 'FR'],
                         'q_id' => 13,
                     ],
                     'ES' => [
                         'langs' => ['EN', 'ES'],
                         'q_id' => 277,
                     ],
                     'DE' => [
                         'langs' => ['EN','DE'],
                         'q_id' => 337,
                     ],
                     'IT' => [
                         'langs' => ['EN', 'IT'],
                         'q_id' => 398,
                     ],
                     'IN' => [
                         'langs' => ['IN'],
                         'q_id' => 462,
                     ],
                     'AU' => [
                         'langs' => ['EN'],
                         'q_id' => 522,
                         'aq_id' => 9,
                     ],
                     'QA ' => [
                         'langs' => ['EN','AR'],
                         'q_id' => 522,
                         'aq_id' => 9,
                     ],
                     'SK' => [
                         'langs' => ['EN','KO'],
                         'q_id' => 522,
                         'aq_id' => 9,
                     ],
                     'VN' => [
                         'langs' => ['VI'],
                         'q_id' => 522,
                         'aq_id' => 9,
                     ],
                     'EG' => [
                         'langs' => ['EN','AR'],
                         'q_id' => 522,
                         'aq_id' => 9,
                     ],*/

                ],

            ],

            'GLOBAL_GENDER' => [
                'general_name' => 'GLOBAL_GENDER',
                'display_name' => 'GLOBAL_GENDER',
                'type' => 'single',
                'show_as' => 'radio',
                'order' => 10,
                'profile_section_id' => 0,
                'countries_map' => [
                    /*'US' => [
                        'langs' => ['EN', 'ES'],
                        'q_id' => 1,
                    ],
                    'UK' => [
                        'langs' => ['EN'],
                        'q_id' => 4,
                    ],
                    'CA' => [
                        'langs' => ['EN','FR'],
                        'q_id' => 22,
                    ],
                    'FR' => [
                        'langs' => ['EN', 'FR'],
                        'q_id' => 13,
                    ],
                    'ES' => [
                        'langs' => ['EN', 'ES'],
                        'q_id' => 277,
                    ],
                    'DE' => [
                        'langs' => ['EN','DE'],
                        'q_id' => 337,
                    ],
                    'IT' => [
                        'langs' => ['EN', 'IT'],
                        'q_id' => 398,
                    ],
                    'IN' => [
                        'langs' => ['IN'],
                        'q_id' => 462,
                    ],
                    'AU' => [
                        'langs' => ['EN'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],
                    'QA ' => [
                        'langs' => ['EN','AR'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],
                    'SK' => [
                        'langs' => ['EN','KO'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],
                    'VN' => [
                        'langs' => ['VI'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],
                    'EG' => [
                        'langs' => ['EN','AR'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],*/

                ],

            ],
            'GLOBAL_ZIP' => [
                'general_name' => 'GLOBAL_ZIP',
                'display_name' => 'GLOBAL_ZIP',
                'type' => 'open',
                'show_as' => 'text',
                'order' => 10,
                'profile_section_id' => 0,
                'countries_map' => [
                    /*'US' => [
                        'langs' => ['EN', 'ES'],
                        'q_id' => 1,
                    ],
                    'UK' => [
                        'langs' => ['EN'],
                        'q_id' => 4,
                    ],
                    'CA' => [
                        'langs' => ['EN','FR'],
                        'q_id' => 22,
                    ],
                    'FR' => [
                        'langs' => ['EN', 'FR'],
                        'q_id' => 13,
                    ],
                    'ES' => [
                        'langs' => ['EN', 'ES'],
                        'q_id' => 277,
                    ],
                    'DE' => [
                        'langs' => ['EN','DE'],
                        'q_id' => 337,
                    ],
                    'IT' => [
                        'langs' => ['EN', 'IT'],
                        'q_id' => 398,
                    ],
                    'IN' => [
                        'langs' => ['IN'],
                        'q_id' => 462,
                    ],
                    'AU' => [
                        'langs' => ['EN'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],
                    'QA ' => [
                        'langs' => ['EN','AR'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],
                    'SK' => [
                        'langs' => ['EN','KO'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],
                    'VN' => [
                        'langs' => ['VI'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],
                    'EG' => [
                        'langs' => ['EN','AR'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],*/
                ],

            ],

            'GLOBAL_EDUCATION' => [
                'general_name' => 'GLOBAL_EDUCATION',
                'display_name' => 'GLOBAL_EDUCATION',
                'type' => 'single',
                'show_as' => 'select',
                'order' => 10,
                'profile_section_id' => 0,
                'countries_map' => [
                    'US' => [
                        'langs' => ['EN', 'ES'],
                        'q_id' => 1,
                    ],
                    'UK' => [
                        'langs' => ['EN'],
                        'q_id' => 94,
                    ],
                    'CA' => [
                        'langs' => ['EN', 'FR'],
                        'q_id' => 156,
                    ],
                    'FR' => [
                        'langs' => ['EN', 'FR'],
                        'q_id' => 217,
                    ],
                    'ES' => [
                        'langs' => ['EN', 'ES'],
                        'q_id' => 277,
                    ],
                    'DE' => [
                        'langs' => ['EN', 'DE'],
                        'q_id' => 337,
                    ],
                    'IT' => [
                        'langs' => ['EN', 'IT'],
                        'q_id' => 398,
                    ],
                    'IN' => [
                        'langs' => ['EN', 'HI'],
                        'q_id' => 462,
                    ],
                    'AU' => [
                        'langs' => ['EN'],
                        'q_id' => 522,
                        'aq_id' => 9,
                    ],
                    'AR' => [
                        'skip' => true,
                        'translations' => [
                            'ES' => [
                                'text' => '¿Cuál es el nivel más alto de educación que ha completado?',
                                'fulcrum_id' => 633,
                                'fulcrum_country_language_code' => 'SPA-AR',
                                'fulcrum_country_language_id' => '19',
                            ],
                            'EN' => [
                                'text' => 'What is the highest level of education you have completed?',
                                'fulcrum_id' => 633,
                                'fulcrum_country_language_code' => 'ENG-AR',
                                'fulcrum_country_language_id' => '107',
                            ],
                        ],
                    ],

                    'AT' => [
                        'skip' => true,
                        'translations' => [
                            'DE' => [
                                'text' => 'Welches ist das höchste Bildungsniveau, das Sie abgeschlossen haben?',
                                'fulcrum_id' => 633,
                                'fulcrum_country_language_code' => 'GER-AT',
                                'fulcrum_country_language_id' => '38',
                            ],
                        ],
                    ],

                    'BE' => [
                        'skip' => true,
                        'translations' => [
                            'FR' => [
                                'text' => 'Quel est le niveau de scolarité le plus élevé que vous avez atteint?',
                                'fulcrum_id' => 48741,
                                'fulcrum_country_language_code' => 'FRE-BE',
                                'fulcrum_country_language_id' => '26',
                            ],
                            'NL' => [
                                'text' => 'Wat is het hoogste opleidingsniveau dat je hebt voltooid?',
                                'fulcrum_id' => 0,
                                'fulcrum_country_language_code' => 'NLD-BE',
                                'fulcrum_country_language_id' => '88',
                            ],
                            'DE' => [
                                'text' => 'Welches ist das höchste Bildungsniveau, das Sie abgeschlossen haben?',
                                'fulcrum_id' => 0,
                                'fulcrum_country_language_code' => 'GER-BE',
                                'fulcrum_country_language_id' => '94',
                            ],
                        ],
                    ],
                    'BR' => [
                        'skip' => true,
                        'translations' => [
                            'PT' => [
                                'text' => 'Qual é o nível mais alto de educação que você completou?',
                                'fulcrum_id' => 633,
                                'fulcrum_country_language_code' => 'POR-BR',
                                'fulcrum_country_language_id' => '16',
                            ],

                        ],
                    ],
                    'CL' => [
                        'skip' => true,
                        'translations' => [
                            'ES' => [
                                'text' => '¿Cuál es el nivel más alto de educación que ha completado?',
                                'fulcrum_id' => 633,
                                'fulcrum_country_language_code' => 'SPA-CL',
                                'fulcrum_country_language_id' => '47',
                            ],
                        ],
                    ],
                    'CN' => [
                        'skip' => true,
                        'translations' => [
                            'ZH' => [
                                'text' => '你完成的最高教育水平是多少？',
                                'fulcrum_id' => 0,
                                'fulcrum_country_language_code' => 'YUE-CN',
                                'fulcrum_country_language_id' => '161',
                            ],
                        ],
                    ],
                    'AE' => [
                        'skip' => true,
                        'translations' => [
                            'EN' => [
                                'text' => 'What is the highest level of education you have completed?',
                                'fulcrum_id' => 633,
                                'fulcrum_country_language_code' => 'ENG-AE',
                                'fulcrum_country_language_id' => '93',
                            ],
                            'AR' => [
                                'text' => 'ما هو أعلى مستوى تعليمي قمت بإكماله؟',
                                'fulcrum_id' => 633,
                                'fulcrum_country_language_code' => 'ARA-AE',
                                'fulcrum_country_language_id' => '82',
                            ],
                        ],
                    ],
                    'BO' => [
                        'skip' => true,
                        'translations' => [
                            'ES' => [
                                'text' => '¿Cuál es el nivel más alto de educación que ha completado?',
                                'fulcrum_id' => 0,
                                'fulcrum_country_language_code' => 'SPA-BO',
                                'fulcrum_country_language_id' => '189',
                                'language_code' => 'SPA',
                            ],
                        ],
                    ],

                    "TW" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "ZH" =>
                                        [
                                            "text" => "你完成的最高教育水平是多少？",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "CHI-TW",
                                            "fulcrum_country_language_id" => 3,
                                        ],

                                ],

                        ],

                    "CO" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "ES" =>
                                        [
                                            "text" => "¿Cuál es el nivel más alto de educación que ha completado?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "SPA-CO",
                                            "fulcrum_country_language_id" => 20,
                                        ],

                                ],

                        ],

                    "DK" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "DA" =>
                                        [
                                            "text" => "Hvilket uddannelsesniveau har du?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "DAN-DK",
                                            "fulcrum_country_language_id" => 31,
                                        ],

                                ],

                        ],

                    "FI" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ENG-FI",
                                            "fulcrum_country_language_id" => 147
                                            ,
                                        ],

                                    "FI" =>
                                        [
                                            "text" => "Mikä on korkein koulutustaso, jonka olet suorittanut?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "FIN-FI",
                                            "fulcrum_country_language_id" => 32
                                            ,
                                        ],
                                ],
                        ],

                    "GR" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "GR" =>
                                        [
                                            "text" => "Ποιο είναι το μορφωτικό σας επίπεδο; ",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "GRE-GR",
                                            "fulcrum_country_language_id" => 40,
                                        ],

                                ],

                        ],

                    "HK" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "ZH" =>
                                        [
                                            "text" => "您的最高教育程度是什麽?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "CHI-HK",
                                            "fulcrum_country_language_id" => 2,
                                        ],
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "ENG-HK",
                                            "fulcrum_country_language_id" => 73,
                                        ],

                                ],

                        ],

                    "IS" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "AR" =>
                                        [
                                            "text" => "Hver er hæsta stig menntunar sem þú hefur lokið",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ICE-IS",
                                            "fulcrum_country_language_id" => 42,
                                        ],
                                ],
                        ],

                    "ID" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [

                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "ENG-IE",
                                            "fulcrum_country_language_id" => 59,
                                        ],

                                    "ID" =>
                                        [
                                            "text" => "Apa tingkat tertinggi Anda pendidikan?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "IND-ID",
                                            "fulcrum_country_language_id" => 52
                                        ],

                                ],

                        ],

                    "IE" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "ENG-IE",
                                            "fulcrum_country_language_id" => 43,
                                        ],

                                ],

                        ],

                    "JP" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "JP" =>
                                        [
                                            "text" => "学歴",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "JAP-JP",
                                            "fulcrum_country_language_id" => 14,
                                        ],

                                ],

                        ],

                    "MY" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [


                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "ENG-MY",
                                            "fulcrum_country_language_id" => 61,
                                        ],

                                    "ZH" =>
                                        [
                                            "text" => "你完成的最高教育水平是多少？",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "CHI-MY",
                                            "fulcrum_country_language_id" => 91,
                                        ],

                                    "MS" =>
                                        [
                                            "text" => "Apakah tahap pendidikan tertinggi yang telah anda selesaikan?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "MAL-MY",
                                            "fulcrum_country_language_id" => 53,
                                        ],

                                ],
                        ],

                    "MX" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "ES" =>
                                        [
                                            "text" => "¿Cuál es su nivel de educación?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "SPA-MX",
                                            "fulcrum_country_language_id" => 21,
                                        ],

                                ],

                        ],

                    "NL" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "NL" =>
                                        [
                                            "text" => "Wat is uw opleidingsniveau?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "CHI-TW",
                                            "fulcrum_country_language_id" => 3,
                                        ],
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "ENG-TW",
                                            "fulcrum_country_language_id" => 95,
                                        ],

                                ],

                        ],

                    "NO" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [

                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ENG-NO",
                                            "fulcrum_country_language_id" => 148,
                                        ],
                                    "NO" =>
                                        [
                                            "text" => "Hva er ditt utdanningsnivå?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "NOR-NO",
                                            "fulcrum_country_language_id" => 30,
                                        ],

                                ],

                        ],

                    "NZ" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " ENG-NZ",
                                            "fulcrum_country_language_id" => 57,
                                        ],
                                ],

                        ],


                    "PK" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => " ENG-PK",
                                            "fulcrum_country_language_id" => 120
                                        ],
                                    "UR" =>
                                        [
                                            "text" => "آپ نے مکمل کیا تعلیم کی بلند ترین سطح کیا ہے؟",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => " URD-PK",
                                            "fulcrum_country_language_id" => 121
                                        ],

                                ],

                        ],

                    "PE" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "ES" =>
                                        [
                                            "text" => "¿Cuál es el nivel más alto de educación que ha completado?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " SPA-PE",
                                            "fulcrum_country_language_id" => 80,
                                        ],

                                ],

                        ],

                    "PH" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "FP" =>
                                        [
                                            "text" => "Ano ang pinakamataas na antas ng edukasyon na natapos mo?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => " TAG-PH",
                                            "fulcrum_country_language_id" => 55,
                                        ],
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " ENF-PH",
                                            "fulcrum_country_language_id" => 58,
                                        ],
                                ],

                        ],

                    "PL" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "PL" =>
                                        [
                                            "text" => "Jaki jest najwyższy poziom wykształcenia, który ukończyłeś?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " POL-PL",
                                            "fulcrum_country_language_id" => 15,
                                        ],

                                ],

                        ],

                    "PT" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "PT" =>
                                        [
                                            "text" => "Quais as suas habilitações literárias?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " POR-PT",
                                            "fulcrum_country_language_id" => " 17",
                                        ],

                                ],

                        ],

                    "QA" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "AR" =>
                                        [
                                            "text" => "ما هو أعلى مستوى تعليمي قمت بإكماله؟",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => " ARA-QA",
                                            "fulcrum_country_language_id" => 83,
                                        ],
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => " ENG-QA",
                                            "fulcrum_country_language_id" => 238,
                                        ],

                                ],

                        ],

                    "RU" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "RU" =>
                                        [
                                            "text" => "Какое у вас образование?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " RUS-RU",
                                            "fulcrum_country_language_id" => 18,
                                        ],

                                ],

                        ],

                    "SA" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [

                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " ENG-SA",
                                            "fulcrum_country_language_id" => 149,
                                        ],

                                    "AR" =>
                                        [
                                            "text" => "المستوى التعليمي",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " ARA-SA",
                                            "fulcrum_country_language_id" => 29,
                                        ],
                                ],
                        ],

                    "SG" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [

                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " ENG-SG",
                                            "fulcrum_country_language_id" => 50,
                                        ],
                                    "ZH" =>
                                        [
                                            "text" => "您的受教育水平如何？",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " CHI-SG",
                                            "fulcrum_country_language_id" => 90,
                                        ],

                                ],

                        ],

                    "VN" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "VI" =>
                                        [
                                            "text" => "Trình độ học vấn cao nhất bạn đã hoàn thành là gì?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => " VIE-VN",
                                            "fulcrum_country_language_id" => 81,
                                        ],

                                ],

                        ],

                    "ZA" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " ENG-ZA",
                                            "fulcrum_country_language_id" => 49,
                                        ],

                                ],

                        ],

                    "SE" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "SV" =>
                                        [
                                            "text" => "Vilken utbildningsnivå har du?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => " SWE-SE",
                                            "fulcrum_country_language_id" => 23,
                                        ],

                                ],

                        ],
                    "CH" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "ENG-CH",
                                            "fulcrum_country_language_id" => 36,
                                        ],

                                    "DE" =>
                                        [
                                            "text" => "Was ist der höchste, von Ihnen erreichte,Ausbildungsabschluss?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "GER-CH",
                                            "fulcrum_country_language_id" => 12,
                                        ],
                                    "FR" =>
                                        [
                                            "text" => "Quel est le niveau de scolarité le plus élevé que vous avez atteint?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "FRE-CH",
                                            "fulcrum_country_language_id" => 34,
                                        ],

                                ],

                        ],

                    "TH" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "TH" =>
                                        [
                                            "text" => "ระดับการศึกษาของคุณคืออะไร",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "THA-TH",
                                            "fulcrum_country_language_id" => 54,
                                        ],

                                ],

                        ],

                    "TR" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "TR" =>
                                        [
                                            "text" => "Eğitim seviyeniz nedir?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "TUR-TR",
                                            "fulcrum_country_language_id" => 37,
                                        ],

                                ],

                        ],

                    "EG" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "AR" =>
                                        [
                                            "text" => "ما هو أعلى مستوى تعليمي قمت بإكماله؟",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ARA-EG",
                                            "fulcrum_country_language_id" => 77,
                                        ],
                                    "EN" =>
                                        [
                                            "text" => "What is the highest level of education you have completed?",
                                            "fulcrum_id" => 633,
                                            "fulcrum_country_language_code" => "ENG-EG",
                                            "fulcrum_country_language_id" => 128,
                                        ],

                                ],

                        ],

                    "SK" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "SK" =>
                                        [
                                            "text" => "Aká je najvyššia úroveň vzdelania, ktorú ste dosiahli?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "SLK-SK",
                                            "fulcrum_country_language_id" => 78,
                                        ],

                                ],
                        ],
                ],
            ],

            'GLOBAL_ETHNICITY' => [
                'general_name' => 'GLOBAL_ETHNICITY',
                'display_name' => 'GLOBAL_ETHNICITY',
                'profile_section_id' => 0,
                'type' => 'single',
                'show_as' => 'select',
                'order' => 10,
                'countries_map' => [
                    'US' => [
                        'langs' => ['EN', 'ES'],
                        'q_id' => 5,
                    ],
                    'UK' => [
                        'langs' => ['EN'],
                        'q_id' => 99,
                    ],
                    'CA' => [
                        'langs' => ['EN', 'FR'],
                        'q_id' => 157,
                    ],

                ],
            ],
            'GLOBAL_INCOME' => [
                'general_name' => 'GLOBAL_INCOME',
                'display_name' => 'GLOBAL_INCOME',
                'profile_section_id' => 0,
                'type' => 'single',
                'show_as' => 'select',
                'order' => 10,
                'countries_map' => [
                    'US' => [
                        'langs' => ['EN', 'ES'],
                        'q_id' => 9,
                    ],
                    'UK' => [
                        'langs' => ['EN'],
                        'q_id' => 100,
                    ],
                    'CA' => [
                        'langs' => ['EN', 'FR'],
                        'q_id' => 160,
                    ],
                    'FR' => [
                        'langs' => ['EN', 'FR'],
                        'q_id' => 220,
                    ],
                    'ES' => [
                        'langs' => ['EN', 'ES'],
                        'q_id' => 280,
                    ],
                    'DE' => [
                        'langs' => ['EN', 'DE'],
                        'q_id' => 341,
                    ],
                    'IT' => [
                        'langs' => ['EN', 'IT'],
                        'q_id' => 402,
                    ],
                    'IN' => [
                        'langs' => ['EN', 'HI'],
                        'q_id' => 465,
                    ],
                    'AU' => [
                        'langs' => ['EN'],
                        'q_id' => 526,
                    ],
                    'AR' => [
                        'skip' => true,
                        'translations' => [
                            'ES' => [
                                'text' => '¿Cuánto ingresos totales combinados ganan todos los miembros de su hogar antes de impuestos?',
                                'fulcrum_id' => 14887,
                                'fulcrum_country_language_code' => 'SPA-AR',
                                'fulcrum_country_language_id' => '19',
                            ],
                            'EN' => [
                                'text' => 'How much total combined income do all members of your household earn before taxes?',
                                'fulcrum_id' => 14887,
                                'fulcrum_country_language_code' => 'ENG-AR',
                                'fulcrum_country_language_id' => '107',
                            ],
                        ],
                    ],
                    'AT' => [
                        'skip' => true,
                        'translations' => [
                            'DE' => [
                                'text' => 'Wie viel Gesamteinkommen verdienen alle Mitglieder Ihres Haushalts vor Steuern?',
                                'fulcrum_id' => 14887,
                                'fulcrum_country_language_code' => 'GER-AT',
                                'fulcrum_country_language_id' => '38',
                            ],
                        ],
                    ],
                    'BE' => [
                        'skip' => true,
                        'translations' => [
                            'FR' => [
                                'text' => 'Quel est le revenu total combiné de tous les membres de votre ménage avant impôts?',
                                'fulcrum_id' => 14887,
                                'fulcrum_country_language_code' => 'FRE-BE',
                                'fulcrum_country_language_id' => '26',
                            ],
                            'NL' => [
                                'text' => 'Hoeveel verdient het totale inkomen van alle leden van uw huishouden vóór belastingen?',
                                'fulcrum_id' => 0,
                                'fulcrum_country_language_code' => 'NLD-BE',
                                'fulcrum_country_language_id' => '88',
                            ],
                            'DE' => [
                                'text' => 'Wie viel Gesamteinkommen verdienen alle Mitglieder Ihres Haushalts vor Steuern?',
                                'fulcrum_id' => 0,
                                'fulcrum_country_language_code' => 'GER-BE',
                                'fulcrum_country_language_id' => '94',
                            ],
                        ],
                    ],
                    'BR' => [
                        'skip' => true,
                        'translations' => [
                            'PT' => [
                                'text' => 'Quanto rendimento combinado total todos os membros do seu agregado familiar ganham antes dos impostos?',
                                'fulcrum_id' => 14887,
                                'fulcrum_country_language_code' => 'POR-BR',
                                'fulcrum_country_language_id' => '16',
                            ],
                        ],
                    ],
                    'CL' => [
                        'skip' => true,
                        'translations' => [
                            'ES' => [
                                'text' => '¿Cuánto ingresos totales combinados ganan todos los miembros de su hogar antes de impuestos?',
                                'fulcrum_id' => 14887,
                                'fulcrum_country_language_code' => 'SPA-CL',
                                'fulcrum_country_language_id' => '47',
                            ],
                        ],
                    ],
                    'CN' => [
                        'skip' => true,
                        'translations' => [
                            'ZH' => [
                                'text' => '您所有家庭成员在税前获得的总收入是多少？',
                                'fulcrum_id' => 0,
                                'fulcrum_country_language_code' => 'YUE-CN',
                                'fulcrum_country_language_id' => '161',
                            ],
                        ],
                    ],
                    'AE' => [
                        'skip' => true,
                        'translations' => [
                            'EN' => [
                                'text' => 'How much total combined income do all members of your household earn before taxes?',
                                'fulcrum_id' => 14887,
                                'fulcrum_country_language_code' => 'ENG-AE',
                                'fulcrum_country_language_id' => '93',
                            ],
                            'AR' => [
                                'text' => 'ما هو إجمالي الدخل المجمع الذي يحصل عليه جميع أفراد أسرتك قبل الضرائب؟',
                                'fulcrum_id' => 14887,
                                'fulcrum_country_language_code' => 'ARA-AE',
                                'fulcrum_country_language_id' => '82',
                            ],
                        ],
                    ],
                    'BO' => [
                        'skip' => true,
                        'translations' => [
                            'ES' => [
                                'text' => '¿Cuánto ingresos totales combinados ganan todos los miembros de su hogar antes de impuestos?',
                                'fulcrum_id' => 0,
                                'fulcrum_country_language_code' => 'SPA-BO',
                                'fulcrum_country_language_id' => '189',
                            ],
                        ],
                    ],

                    "TW" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "ZH" =>
                                        [
                                            "text" => "您所有家庭成员在税前获得的总收入是多少？",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "CHI-TW",
                                            "fulcrum_country_language_id" => 3,
                                        ],

                                ],

                        ],

                    "CO" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "ES" =>
                                        [
                                            "text" => "¿Cuánto ingresos totales combinados ganan todos los miembros de su hogar antes de impuestos?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "SPA-CO",
                                            "fulcrum_country_language_id" => 20,
                                        ],

                                ],

                        ],

                    "DK" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "DA" =>
                                        [
                                            "text" => "Hvad er din husstands indtægt før skat?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "DAN-DK",
                                            "fulcrum_country_language_id" => 31,
                                        ],

                                ],

                        ],

                    "FI" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ENG-FI",
                                            "fulcrum_country_language_id" => 147,
                                        ],
                                    "FI" =>
                                        [
                                            "text" => "Mitkä ovat perheesi vuositulot ennen veroja?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "FIN-FI",
                                            "fulcrum_country_language_id" => 32,
                                        ],

                                ],

                        ],

                    "GR" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "GR" =>
                                        [
                                            "text" => "Πόσο είναι το ετήσιο εισόδημα του νοικοκυριού σας χωρίς τους φόρους",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "GRE-GR",
                                            "fulcrum_country_language_id" => 40,
                                        ],

                                ],

                        ],

                    "HK" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [


                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-HK",
                                            "fulcrum_country_language_id" => 73,
                                        ],
                                    "ZH" =>
                                        [
                                            "text" => "您的家庭年收入税前是多少？",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "CHI-HK",
                                            "fulcrum_country_language_id" => 2,
                                        ],

                                ],

                        ],

                    "IS" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "AR" =>
                                        [
                                            "text" => "ما هو إجمالي الدخل المجمع الذي يحصل عليه جميع أفراد أسرتك قبل الضرائب؟",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ICE-IS",
                                            "fulcrum_country_language_id" => 42,
                                        ],

                                ],

                        ],

                    "ID" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [


                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-ID",
                                            "fulcrum_country_language_id" => 59,
                                        ],
                                    "ID" =>
                                        [
                                            "text" => "Berapa total gabungan penghasilan seluruh anggota rumah tangga Anda sebelum pajak?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "IND-ID",
                                            "fulcrum_country_language_id" => 52
                                        ],

                                ],

                        ],

                    "IE" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-IE",
                                            "fulcrum_country_language_id" => 43,
                                        ],

                                ],

                        ],

                    "JP" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "JP" =>
                                        [
                                            "text" => "あなたの現在の一世帯あたりの月収（税前）をお答えください。",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "JAP-JP",
                                            "fulcrum_country_language_id" => 14,
                                        ],

                                ],

                        ],

                    "MY" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [

                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-MY",
                                            "fulcrum_country_language_id" => 61,
                                        ],

                                    "ZH" =>
                                        [
                                            "text" => "您所有家庭成员在税前获得的总收入是多少？",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "CHI-MY",
                                            "fulcrum_country_language_id" => 91,
                                        ],
                                    "MS" =>
                                        [
                                            "text" => "Berapa jumlah pendapatan gabungan yang dilakukan oleh semua ahli keluarga anda sebelum cukai?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "MAL-MY",
                                            "fulcrum_country_language_id" => 53,
                                        ],
                                ],
                        ],

                    "MX" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "ES" =>
                                        [
                                            "text" => "¿Cuáles son los ingresos brutos anuales{antes de impuestos) de todos los miembros de su familia que viven con usted?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "SPA-MX",
                                            "fulcrum_country_language_id" => 21,
                                        ],

                                ],

                        ],

                    "NL" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "NL" =>
                                        [
                                            "text" => "Wat is uw gecombineerd jaarlijks gezinsinkomen voor aftrek van belastingen?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "DUT-NL",
                                            "fulcrum_country_language_id" => 4,
                                        ],
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-NL",
                                            "fulcrum_country_language_id" => 95,
                                        ],

                                ],

                        ],

                    "NO" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [

                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ENG-NO",
                                            "fulcrum_country_language_id" => 148,
                                        ],

                                    "NO" =>
                                        [
                                            "text" => "Hva er husholdningens årlige inntekt før skatt?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "NOR-NO",
                                            "fulcrum_country_language_id" => 30,
                                        ],

                                ],

                        ],

                    "NZ" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-NZ",
                                            "fulcrum_country_language_id" => 57,
                                        ],

                                ],

                        ],


                    "PK" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [


                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ENG-PK",
                                            "fulcrum_country_language_id" => 120,
                                        ],
                                    "UR" =>
                                        [
                                            "text" => "آپ کے گھر کے تمام اراکین ٹیکس سے پہلے کتنی مجموعی آمدنی کرتے ہیں؟",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "URD-PK",
                                            "fulcrum_country_language_id" => 121,
                                        ],

                                ],

                        ],

                    "PE" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "ES" =>
                                        [
                                            "text" => "¿Cuánto ingresos totales combinados ganan todos los miembros de su hogar antes de impuestos?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "SPA-PE",
                                            "fulcrum_country_language_id" => 80,
                                        ],

                                ],

                        ],

                    "PH" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "FP" =>
                                        [
                                            "text" => "Gaano karaming kabuuang pinagsamang kita ang kumita ng lahat ng mga miyembro ng iyong sambahayan bago ang mga buwis?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "TAG-PH",
                                            "fulcrum_country_language_id" => 55,
                                        ],
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-PH",
                                            "fulcrum_country_language_id" => 14887,
                                        ],

                                ],

                        ],

                    "PL" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "PL" =>
                                        [
                                            "text" => "Ile łącznego dochodu łącznego zarabiają wszyscy członkowie twojego gospodarstwa domowego przed opodatkowaniem?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "POL-PL",
                                            "fulcrum_country_language_id" => 15,
                                        ],

                                ],

                        ],

                    "PT" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "PT" =>
                                        [
                                            "text" => "Qual o seu rendimento familiar anual antes de impostos?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "POR-PT",
                                            "fulcrum_country_language_id" => 17,
                                        ],

                                ],

                        ],

                    "QA" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "AR" =>
                                        [
                                            "text" => "ما هو إجمالي الدخل المجمع الذي يحصل عليه جميع أفراد أسرتك قبل الضرائب؟",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ARA-QA",
                                            "fulcrum_country_language_id" => 83,
                                        ],
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ENG-QA",
                                            "fulcrum_country_language_id" => 238,
                                        ],
                                ],
                        ],

                    "RU" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "RU" =>
                                        [
                                            "text" => "Оцените, пожалуйста, средний месячный доход вашей семьи",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "RUS-RU",
                                            "fulcrum_country_language_id" => 18,
                                        ],

                                ],

                        ],

                    "SA" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [

                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ENG-SA",
                                            "fulcrum_country_language_id" => 149,
                                        ],

                                    "AR" =>
                                        [
                                            "text" => "كم يبلغ دخل أسرتك السنوي قبل الضرائب؟",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ARA-SA",
                                            "fulcrum_country_language_id" => 29,
                                        ],

                                ],

                        ],

                    "SG" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-SG",
                                            "fulcrum_country_language_id" => 50,
                                        ],
                                    "ZH" =>
                                        [
                                            "text" => "您的家庭年收入税前是多少？",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "CHI-SG",
                                            "fulcrum_country_language_id" => 90,
                                        ],

                                ],

                        ],

                    "VN" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "VI" =>
                                        [
                                            "text" => "Tổng thu nhập của tất cả các thành viên trong gia đình bạn kiếm được trước thuế là bao nhiêu?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "VIE-VN",
                                            "fulcrum_country_language_id" => 81,
                                        ],

                                ],

                        ],

                    "ZA" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-ZA",
                                            "fulcrum_country_language_id" => 49,
                                        ],
                                ],
                        ],
                    "SE" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "SV" =>
                                        [
                                            "text" => "Vilken är hushållets årliga inkomst före skatt?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "SWE-SE",
                                            "fulcrum_country_language_id" => 23,
                                        ],
                                ],
                        ],
                    "CH" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "ENG-CH",
                                            "fulcrum_country_language_id" => 36,
                                        ],

                                    "DE" =>
                                        [
                                            "text" => "Quels-sont les revenus de votre foyer, avant impôts ?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "GER-CH",
                                            "fulcrum_country_language_id" => 12,
                                        ],
                                    "FR" =>
                                        [
                                            "text" => "Quel est le revenu total combiné de tous les membres de votre ménage avant impôts?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "FRE-CH",
                                            "fulcrum_country_language_id" => 34,
                                        ],

                                ],

                        ],

                    "TH" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "TH" =>
                                        [
                                            "text" => "รายได้ของครัวเรือนรายปีของคุณก่อนหักภาษีเท่ากับเท่าไร",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "THA-TH",
                                            "fulcrum_country_language_id" => 54,
                                        ],
                                ],
                        ],

                    "TR" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "TR" =>
                                        [
                                            "text" => "Vergiler kesilmeden önce hane halkı geliriniz nedir?",
                                            "fulcrum_id" => 14887,
                                            "fulcrum_country_language_code" => "TUR-TR",
                                            "fulcrum_country_language_id" => 37,
                                        ],

                                ],

                        ],

                    "EG" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "AR" =>
                                        [
                                            "text" => "ما هو إجمالي الدخل المجمع الذي يحصل عليه جميع أفراد أسرتك قبل الضرائب؟",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ARA-EG",
                                            "fulcrum_country_language_id" => 77,
                                        ],
                                    "EN" =>
                                        [
                                            "text" => "How much total combined income do all members of your household earn before taxes?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "ENG-EG",
                                            "fulcrum_country_language_id" => 128,
                                        ],

                                ],

                        ],

                    "SK" =>
                        [
                            "skip" => true,
                            "translations" =>
                                [
                                    "KO" =>
                                        [
                                            "text" => "Koľko celkových kombinovaných príjmov zarobí všetci členovia vašej domácnosti pred zdanením?",
                                            "fulcrum_id" => 0,
                                            "fulcrum_country_language_code" => "SLK-SK",
                                            "fulcrum_country_language_id" => 78,
                                        ],
                                ],
                        ],
                ],
            ],
           
        ];
        $global_profile_match_quest = ["STANDARD_EDUCATION","STANDARD_EDUCATION_UK","STANDARD_EDUCATION_CA","STANDARD_EDUCATION_FR","STANDARD_EDUCATION_ES","STANDARD_EDUCATION_DE","STANDARD_EDUCATION_IT","STANDARD_EDUCATION_IN_IN","STANDARD_EDUCATION_v2_AU","ETHNICITY","STANDARD_UK_ETHNICITY_UK","STANDARD_CANADA_ETHNICITY_CA","STANDARD_HHI_US","STANDARD_HHI_INT_UK","STANDARD_HHI_INT_CA","STANDARD_HHI_INT_FR","STANDARD_HHI_INT_ES","STANDARD_HHI_INT_DE","STANDARD_HHI_INT_IT","STANDARD_HHI_INT_IN_IN","STANDARD_HHI_INT_AU"];

        $fl_code = 'FL';
        $sj_code = 'SJPL';
        $questionItems = [];
        foreach ($globalQues as $id => $question) {
            $question_item = [];
            $question_item['id'] = $id;
            $question_item['general_name'] = $question['general_name'];
            $question_item['display_name'] = $question['display_name'];
            $question_item['type'] = $question['type'];
            $question_item['show_as'] = $question['show_as'];
            $question_item['profile_section_id'] = $question['profile_section_id'];
            $question_item['order'] = $question['order'];
            //$question_item['translations'] = $this->getQuestionTranslations($question['countries_map']);
            if (empty($question['countries_map'])) {
                $question['countries_map'] = $this->getCountries();
                foreach ($question['countries_map'] as $code => $country) {
                    $get_gender_details = $this->getGenderDetails($code, $id);
                    if ($get_gender_details) {
                        $question_id = $get_gender_details->id;
                        $langauges = $country['langs'];
                        $langauges = explode(",", $langauges);
                        foreach ($langauges as $langauge) {
                            $all_combinations[] = $code."_".$langauge;
                            $translated = $this->getProfileQuestionTranslation($fl_code, $question_id, $code, $langauge, $id);
                            if ($translated) {
                                $question_item['translated'][] = [
                                    'con_lang' => $code . '-' . $langauge,
                                    'country_code' => $code,
                                    'language_code' => $langauge,
                                    'text' => $translated->label,
                                    'hint' => $translated->hint,
                                    'mapping' => $this->getFulcrumMappingData($sj_code,$global_profile_match_quest,$translated->name,$fl_code, $id, $get_gender_details, $code, $langauge),
                                    'answers' => ($id == "GLOBAL_GENDER") ? $this->getGlobalQuestionAnswers($sj_code,$global_profile_match_quest,$translated->name,$fl_code, $get_gender_details, $question_id, $code, $langauge, $id) : [],
                                ];
                            }
                        }
                    }
                }
            } else {
                foreach ($question['countries_map'] as $code => $country) {
                    $translationItem = [];
                    $country_code = $code;
                    if (isset($country['skip']) && $country['skip'] === true) {
                        $question_id = "";
                        $get_country_code[] = $code;
                        $langauges = $country['translations'];
                        $get_gender_details = $question_id;
                        foreach ($country['translations'] as $lang => $value) {
                            $all_code[] = $code;
                            $vendorsMapping = [];
                            if (!empty($value['fulcrum_id'])) {
                                $vendorsMapping[$fl_code] = [
                                    'fulcrum_question_id' => $value['fulcrum_id'],
                                    'fulcrum_country_language_code' => $value['fulcrum_country_language_code'],
                                    'fulcrum_country_language_id' => $value['fulcrum_country_language_id'],
                                ];
                            }
                            $question_item['translated'][] = [
                                'con_lang' => $code . '-' . $lang,
                                'country_code' => $code,
                                'language_code' => $lang,
                                'text' => $value['text'],
                                'hint' => "",
                                'mapping' => $vendorsMapping,
                                'answers' => $this->getGlobalAnswer($fl_code, $id, $value, $lang, $code),
                            ];
                            $all_combinations[] = $code."_".$lang;
                        }
                    } else {
                        $question_id = $country['q_id'];
                        $langauges = $country['langs'];
                        $get_gender_details = $question_id;
                        foreach ($langauges as $langauge) {
                            $translated = $this->getProfileQuestionTranslation($fl_code, $question_id, $code, $langauge, $id);
                            if ($translated) {
                                $all_combinations[] = $code."_".$langauge;
                                $all_code[] = $code;
                                $question_item['translated'][] = [
                                    'con_lang' => $code . '-' . $langauge,
                                    'country_code' => $code,
                                    'language_code' => $langauge,
                                    'text' => $translated->label,
                                    'hint' => $translated->hint,
                                    'mapping' => $this->getFulcrumMappingData($sj_code,$global_profile_match_quest,$translated->name,$fl_code, $id, $get_gender_details, $code, $langauge),
                                    'answers' => $this->getGlobalQuestionAnswers($sj_code,$global_profile_match_quest,$translated->name,$fl_code, $get_gender_details, $question_id, $code, $langauge, $id)
                                ];
                            }
                        }

                    }
                }
            }
            $questionItems[] = $question_item;
        }
        $json = json_encode($questionItems);
        $sliced = array_slice($questionItems[0]['translated'], 0, 10, true);
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR. "global_questions.json", $json);
        //$connection->insert($questionItems);
        /*foreach ($questionItems as $id => $question) {
            $connection->insert($question);
        }*/
        dd('done');
    }

    private function getGlobalAnswer($fl_code, $id, $value, $lang, $code)
    {
        $mapping_data = [];
        $locale = strtolower($lang) . "_" . $code;
        $data = DB::connection('mysql_sjpanel')->table('temp_fulcrum_global_answer')
            ->where('locale', $locale)
            ->where('display_name', $id)
            ->get();
        foreach ($data as $answer) {
            $answer_item = [];
            $answer_item['text'] = $answer->text;
            $answer_item['precode'] = $answer->precode;
            $answer_item['country_id'] = $answer->country_id;
            if ($answer->fulcrum_question_id == 0) {
                $answer_item['mapping'] = [];
                $mapping_data[] = $answer_item;
            } else {
                $answer_item['mapping'][$fl_code]['fulcrum_question_id'] = $answer->fulcrum_question_id;
                $answer_item['mapping'][$fl_code]['precode'] = $answer->fulcrum_precode;
                $answer_item['mapping'][$fl_code]['fulcrum_country_language_code'] = $answer->fulcrum_country_language_code;
                $answer_item['mapping'][$fl_code]['fulcrum_country_language_id'] = $answer->fulcrum_country_language_id;
                $mapping_data[] = $answer_item;
            }
        }
        return $mapping_data;
    }

    private function getFulcrumMappingData($sj_code,$global_profile_match_quest,$display_name,$fl_code, $id, $get_gender_details, $code, $langauge)
    {
        $mapping_data = [];
        if ($id == 'GLOBAL_GENDER' || $id == 'GLOBAL_AGE' || $id == 'GLOBAL_ZIP') {
            $lang = DB::table('languages')->select('id')->where('code', $langauge)->first();
            if ($lang) {
                $data = DB::connection('mysql_sjpanel')->table('fulcrum_question')
                    ->where('question_id', $get_gender_details->id)
                    ->where('country', '=', $get_gender_details->country_code)
                    ->where('language_id', $lang->id)
                    ->first();
                if ($data) {
                    if ($data->fulcrum_question_id == 0) {
                        $mapping_data = [];
                    } else {
                        $mapping_data[$fl_code] = [
                            'fulcrum_question_id' => $data->fulcrum_question_id,
                            'display_name' => $data->display_name,
                            'fulcrum_country_language_code' => $data->fulcrum_country_language_code,
                            'fulcrum_country_language_id' => $data->fulcrum_country_language_id,
                            'country' => $data->country,
                        ];
                    }
                }
            }
        } else {
            $lang = DB::table('languages')->select('id')->where('code', $langauge)->first();
            if ($lang) {
                $data = DB::connection('mysql_sjpanel')->table('fulcrum_question')
                    ->where('question_id', $get_gender_details)
                    ->where('country', '=', $code)
                    ->where('language_id', $lang->id)
                    ->first();
                if ($data) {
                    if ($data->fulcrum_question_id == 0) {
                        $mapping_data = [];
                    } else {
                        $mapping_data[$fl_code] = [
                            'fulcrum_question_id' => $data->fulcrum_question_id,
                            'display_name' => $data->display_name,
                            'fulcrum_country_language_code' => $data->fulcrum_country_language_code,
                            'fulcrum_country_language_id' => $data->fulcrum_country_language_id,
                            'country' => $data->country,
                        ];
                    }
                }
                if(in_array($display_name,$global_profile_match_quest)){
                    $mapping_data[$sj_code] = [
                        'sj_question_id' => $display_name,
                        'display_name' => $display_name,
                        'sj_country_language_code' => $code.'_'.$langauge,
                        'country' => $code,
                    ];
                }
            }
        }
        return $mapping_data;
    }

    private function getCountries()
    {
        $countries = [];
        $data = DB::table('countries')
            ->select('*')
            ->get();
        foreach ($data as $country_details) {
            $countries["$country_details->country_code"] = [
                'langs' => "$country_details->language",
            ];
        }
        return $countries;
    }

    private function getGenderDetails($code, $id)
    {
        $id = explode("_", $id);
        $general_name = $id[1];
        $data = DB::connection('mysql_sjpanel')->table('temp_profile_question')
            ->where('general_name', 'like', "%$general_name%")
            ->where('country_code', '=',$code)
            ->first();
        return $data;
    }

    private function getGlobalAnswerStructure($count)
    {
        $returnArray = [];
        for ($i = 1; $i <= $count; $i++) {
            $returnArray[] = $i;
        }
        return $returnArray;
    }

    private function getQuestionTranslations($countries)
    {
        $translations = [];
        foreach ($countries as $code => $country) {
            $translationItem = [];
            $country_code = $code;
            $question_id = $country['q_id'];
            $langauges = $country['langs'];
            foreach ($langauges as $langauge) {
                $translated = $this->getProfileQuestionTranslation($question_id, $code, $langauge);
                $translations[$code . '-' . $langauge] = [
                    'text' => $translated->label,
                    'hint' => $translated->hint,
                ];
            }

        }
        return $translations;
    }

    private function getProfileQuestionTranslation($fl_code, $question_id, $code, $langauge, $id)
    {
        $locale = strtolower($langauge) . "_" . $code;
        if ($id == 'GLOBAL_GENDER' || $id == 'GLOBAL_AGE' || $id == 'GLOBAL_ZIP') {
            $data = DB::connection('mysql_sjpanel')
                ->table('temp_profile_question_translations')
                ->select('label', 'hint','name')
                ->where('locale', $locale)
                ->where('profile_question_id', '=', $question_id)
                ->first();
        } else {
            $data = DB::connection('mysql_sjpanel')
                ->table('profile_question_translations')
                ->select('label', 'hint','name')
                ->where('locale', '=', $locale)
                ->where('profile_question_id', '=', $question_id)
                ->first();
        }
        /*if (empty($data)) {

            if ($question_id == 'GLOBAL_GENDER') {
                $data = $this->getGenderData($code, $langauge);
            }*/
        /*$data = $this->getProfileQuestionTranslation($question_id, 'US', 'EN');
        if (!empty($data)) {
            return $data;
        }*/
        /*dd($question_id, $code, $langauge);*/
        /*}*/
        return $data;
    }

    private function getGlobalQuestionAnswers($sj_code,$global_profile_match_quest,$display_name,$fl_code, $get_gender_details, $question_id, $country_code, $language_code, $id)
    {
        $return_array = [];
        $locale = strtolower($language_code) . '_' . $country_code;
        if ($id == 'GLOBAL_GENDER') {
            $answers = $this->getGenderAnswer($question_id, $locale);
            foreach ($answers as $answer) {
                $answer_item = [];
                $answer_item['display_name'] = $answer->display_name;
                $answer_item['precode'] = $answer->precode;
                $answer_item['precode_type'] = $answer->precode_type;
                $answer_item['text'] = $answer->text;
                $answer_item['hint'] = $answer->hint;
                $answer_item['mapping'] = $this->getFulcrumAnswerMapping($answer,$sj_code,$global_profile_match_quest,$display_name,$fl_code, $language_code, $get_gender_details->id, $answer->precode, $country_code);
                $return_array[] = $answer_item;
            }
            /* $return_array['answer_mapping'] = $this->getAnswerMapping($id,$get_gender_details,$country_code, $language_code);*/
        } else {
            $answers = $this->globalAnswers($question_id, $locale);
            if ($answers) {
                foreach ($answers as $answer) {
                    $answer_item = [];
                    $answer_item['display_name'] = $answer->display_name;
                    $answer_item['precode'] = $answer->precode;
                    $answer_item['precode_type'] = $answer->precode_type;
                    $answer_item['text'] = $answer->text;
                    $answer_item['hint'] = $answer->hint;
                    $answer_item['mapping'] = $this->getFulcrumAnswerMapping($answer,$sj_code,$global_profile_match_quest,$display_name,$fl_code, $language_code, $get_gender_details, $answer->precode, $country_code);
                    $return_array[] = $answer_item;
                }
            }
            /*$return_array['answer_mapping'] = $this->getAnswerMapping($id,$get_gender_details,$country_code, $language_code);*/
        }
        return $return_array;
    }


    private function getFulcrumAnswerMapping($answer,$sj_code,$global_profile_match_quest,$display_name,$fl_code, $language_code, $get_gender_details, $precode, $country_code)
    {
        $mapping_answer = [];
        $lang = DB::table('languages')->select('id')->where('code', $language_code)->first();
        if ($lang) {
            $data = DB::connection('mysql_sjpanel')->table('fulcrum_answers')
                ->where('profile_question_id', $get_gender_details)
                ->where('precode', $precode)
                ->where('country', $country_code)
                ->where('language_id', $lang->id)
                ->first();
            if ($data) {
                if ($data->fulcrum_question_id == 0) {
                    $mapping_answer = [];
                } else {
                    $mapping_answer[$fl_code] = [
                        'text' => $data->display_name,
                        'precode' => $data->fulcrum_precode,
                        'fulcrum_country_id' => $data->fulcrum_country_language_id,
                        'fulcrum_country_code' => $data->fulcrum_country_language_code,
                    ];
                }
            }
            if(in_array($display_name,$global_profile_match_quest)){
                $mapping_answer[$sj_code] = [
                    'text' => $answer->display_name,
                    'precode' => $answer->precode,
                    'sj_country_code' => $country_code,
                ];
            }
        }
        return $mapping_answer;
    }

    private function getAnswerMapping($id, $get_gender_details, $country_code, $language_code)
    {
        $mapping_answer = [];

        if ($id == 'GLOBAL_GENDER' || $id == 'GLOBAL_AGE' || $id == 'GLOBAL_ZIP') {
            $lang = DB::table('languages')->select('id')->where('code', $language_code)->first();
            $data = DB::connection('mysql_sjpanel')->table('fulcrum_answers')
                ->select('*')
                ->where('profile_question_id', $get_gender_details->id)
                ->where('country', $country_code)
                ->where('language_id', $lang->id)
                ->get();
            if ($data) {
                foreach ($data as $answer) {
                    $mapping_answer['FL'][] = [
                        'text' => $answer->display_name,
                        'fulcrum_precode' => $answer->fulcrum_precode,
                        'country' => $answer->country,
                    ];
                }
            }
        } else {
            $lang = DB::table('languages')->select('id')->where('code', $language_code)->first();
            if ($lang) {
                $data = DB::connection('mysql_sjpanel')->table('fulcrum_answers')
                    ->select('*')
                    ->where('profile_question_id', $get_gender_details)
                    ->where('country', $country_code)
                    ->where('language_id', $lang->id)
                    ->get();
                if ($data) {
                    foreach ($data as $answer) {
                        $mapping_answer['FL'][] = [
                            'text' => $answer->display_name,
                            'fulcrum_precode' => $answer->fulcrum_precode,
                            'country' => $answer->country,
                        ];
                    }
                }
            }
        }
        return $mapping_answer;
    }

    private function getGenderAnswer($question_id, $locale)
    {
        $data = DB::connection('mysql_sjpanel')
            ->table('temp_profile_answers AS pa')
            ->select('pa.id', 'pa.display_name', 'pa.precode', 'pa.precode_type', 'trans.answer_text as text', 'trans.hint as hint')
            ->leftJoin('temp_profile_answer_translations as trans', 'pa.id', '=', 'trans.profile_answer_id')
            ->where('pa.profile_question_id', '=', $question_id)
            ->where('trans.locale', '=', $locale)
            ->get();
        return $data;
    }

    private function globalAnswers($question_id, $locale)
    {
        $data = DB::connection('mysql_sjpanel')
            ->table('profile_answers AS pa')
            ->select('pa.id', 'pa.display_name', 'pa.precode', 'pa.precode_type', 'trans.answer_text as text', 'trans.hint as hint')
            ->leftJoin('profile_answer_translations as trans', 'pa.id', '=', 'trans.profile_answer_id')
            ->where('pa.profile_question_id', '=', $question_id)
            ->where('trans.locale', '=', $locale)
            ->get();
        if (empty($data)) {
            dd($question_id, 'Answer');
        }
        return $data;
    }

    /*private function getIncomeAnswers()
    {
        $profile_answers = array(
            array('id' => '2866','profile_question_id' => '9','display_name' => 'Less than $15,000','precode' => '1','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2867','profile_question_id' => '9','display_name' => '$15,000 to $19,999','precode' => '2','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2868','profile_question_id' => '9','display_name' => '$20,000 to $24,999','precode' => '3','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2869','profile_question_id' => '9','display_name' => '$25,000 to $29,999','precode' => '4','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2870','profile_question_id' => '9','display_name' => '$30,000 to $34,999','precode' => '5','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2871','profile_question_id' => '9','display_name' => '$35,000 to $39,999','precode' => '6','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2872','profile_question_id' => '9','display_name' => '$40,000 to $44,999','precode' => '7','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2873','profile_question_id' => '9','display_name' => '$45,000 to $49,999','precode' => '8','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2874','profile_question_id' => '9','display_name' => '$50,000 to $59,999','precode' => '9','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2875','profile_question_id' => '9','display_name' => '$60,000 to $74,999','precode' => '10','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2876','profile_question_id' => '9','display_name' => '$75,000 to $84,999','precode' => '11','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2877','profile_question_id' => '9','display_name' => '$85,000 to $99,999','precode' => '12','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2878','profile_question_id' => '9','display_name' => '$100,000 to $124,999','precode' => '13','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2879','profile_question_id' => '9','display_name' => '$125,000 to $149,999','precode' => '14','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2880','profile_question_id' => '9','display_name' => '$150,000 to $174,999','precode' => '15','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2881','profile_question_id' => '9','display_name' => '$175,000 to $199,999','precode' => '16','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2882','profile_question_id' => '9','display_name' => '$200,000 and above','precode' => '17','precode_type' => 'general','status' => 'active','order' => '10'),
            array('id' => '2883','profile_question_id' => '9','display_name' => 'Prefer not to answer','precode' => '18','precode_type' => 'general','status' => 'active','order' => '10')
        );

        return json_decode(json_encode($profile_answers));
    }*/
    /*-------------------------------------All profile Questions---------------------------------------------------------------------------------------------------------------------------------------------------------*/



    public function getCountryMapping()
    {
        $country_language_code = ["AE-AR","AE-EN","AR-EN","AR-ES","AT-DE","AU-EN","BE-DE","BE-FR","BE-NL","BO-ES","BR-PT","CA-EN","CA-FR","CH-DE","CH-EN","CH-FR","CL-ES","CN-ZH","CO-ES","DE-DE","DE-EN","DK-DA","EG-AR","EG-EN","ES-EN","ES-ES","FI-EN","FI-FI","FR-EN","FR-FR","GR-GR","HK-EN","HK-ZH","ID-EN","ID-ID","IE-EN","IN-EN","IN-HI","IT-EN","IT-IT","JP-JP","MX-ES","MY-EN","MY-MS","MY-ZH","NL-EN","NL-NL","NO-EN","NO-NO","NZ-EN","PE-ES","PH-EN","PH-FP","PK-EN","PK-UR","PL-PL","PT-PT","QA-AR","QA-EN","RU-RU","SA-AR","SA-EN","SE-SV","SG-EN","SG-ZH","SK-EN","SK-SK","TH-TH","TR-TR","TW-ZH","US-EN","US-ES","VN-VI","UK-EN","ZA-EN"];
        $country_mapp_data = [];
        foreach($country_language_code as $con_lang){
            $data = explode('-',$con_lang);
            $con = $data[0];
            $lang = $data[1];
            $language_name = DB::table('languages')->select('name')->where('code','=',$lang)->first();
            if($language_name){
                $fulcrum_country_data = DB::connection('mysql_sjpanel')->table('fulcrum_country_data')
                        ->join('countries','fulcrum_country_data.country','=','countries.name')
                        ->select('*','countries.country_code as sj_con_code','countries.language as sj_lang')
                        ->where('countries.country_code','=',$con)
                        ->where('fulcrum_country_data.language_name','=',$language_name->name)
                        ->first();
                if($fulcrum_country_data) {
                    $all_con[] = $con;
                    $country_mapp_data[] = [
                        "SJ_CON_CODE" => $con,
                        "SJ_LANG_CODE" => $lang,
                        "FL_CON_LANG_ID" => $fulcrum_country_data->country_id,
                        "FL_CON_LANG_CODE" => $fulcrum_country_data->country_language_code,
                        "FL_COUNTRY" => $fulcrum_country_data->country,
                        "FL_LANGUAGE" => $fulcrum_country_data->language_name,
                    ];
                }
            }
        }
        $json = json_encode($country_mapp_data);
       // $sliced = array_slice($questionItems[0]['translated'], 0, 10, true);
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR. "country_map_data.json", $json);
        dd("done");
        /*$fulcrum_country_data = DB::connection('mysql_sjpanel')->table('fulcrum_country_data')
            ->join('countries','fulcrum_country_data.country','=','countries.name')
            ->select('*','countries.country_code as sj_con_code','countries.language as sj_lang')
            ->get();
        foreach($fulcrum_country_data as $mapping_data){
           $sj_lang_code = $mapping_data->sj_lang;
           $sj_lang = DB::table('languages')->whereIn('name',explode(',',$sj_lang_code))->get();
           dd($sj_lang);
        }*/
    }

    public function allProfileQuestion()
    {
        set_time_limit(0);

        $questionItems = [];
        $fl_code = 'FL';
        $sj_code = 'SJPL';

        //$get_country_details = $this->getCountryDetails();
        $get_country_details = [
            /*'IN' => [
                'langs' => "EN,HI",
            ],
            'ES' => [
                'langs' => "EN,ES",
            ],*/
            /*'CA' => [
                'langs' => "EN,FR",
                'q_id' => 22,
            ],*/
            /*'FR' => [
                'langs' => "EN,FR",
                'q_id' => 13,
            ],*/

            /*'AU' => [
                'langs' => 'EN',
            ],*/
            /*'DE' => [
                'langs' => "EN,DE",
                'q_id' => 337,
            ],*/
            /*'IT' => [
                'langs' => "EN,IT",
                'q_id' => 398,
            ],*/
            /*"US" =>  [
                 "langs" => "EN,ES",
                ],*/
            "UK" =>  [
                     "langs" => "EN",
                ],
        ];


        $global_profile_match_quest = ["STANDARD_EDUCATION","STANDARD_EDUCATION_UK","STANDARD_EDUCATION_CA","STANDARD_EDUCATION_FR","STANDARD_EDUCATION_ES","STANDARD_EDUCATION_DE","STANDARD_EDUCATION_IT","STANDARD_EDUCATION_IN_IN","STANDARD_EDUCATION_v2_AU","ETHNICITY","STANDARD_UK_ETHNICITY_UK","STANDARD_CANADA_ETHNICITY_CA","STANDARD_HHI_US","STANDARD_HHI_INT_UK","STANDARD_HHI_INT_CA","STANDARD_HHI_INT_FR","STANDARD_HHI_INT_ES","STANDARD_HHI_INT_DE","STANDARD_HHI_INT_IT","STANDARD_HHI_INT_IN_IN","STANDARD_HHI_INT_AU"];
        $parental_status = ["Parental_Status_Standard","Parental_Status_Standard_UK","Parental_Status_Standard_CA","Parental_Status_Standard_FR","Parental_Status_Standard_ES","Parental_Status_Standard_DE","Parental_Status_Standard_IT","Parental_Status_Standard_IN_IN","Parental_Status_Standard_UK_AU"];
        foreach ($get_country_details as $code=>$country){
            $get_profile_question = $this->getQuestions($code);
            if($get_profile_question){
               foreach($get_profile_question as $profile_question){
                   $question_item = [];
                   $id = $profile_question->display_name;
                       $question_item['id'] = $id;
                       $question_item['q_id'] = $profile_question->id;
                       $question_item['general_name'] = $profile_question->general_name;
                       $question_item['display_name'] = $profile_question->display_name;
                       $question_item['country_code'] = $profile_question->country_code;
                       $question_item['dependency'] =$this->getDependency($profile_question->id,$profile_question->section_name,$parental_status,$profile_question->general_name);
                       $question_item['order'] = $profile_question->order;
                       $question_item['type'] = $profile_question->type;
                       $question_item['profile_section_id'] = $profile_question->profile_section_id;
                       $question_item['profile_section'] = $profile_question->section_name;
                       $question_item['profile_section_code'] = str_replace(" ","_",strtoupper($profile_question->section_name));
                           $languages = $country['langs'];
                           if ($languages) {
                               $languages = explode(",",$languages);
                               foreach ($languages as $lang) {
                                   $get_question_id = $profile_question->id;
                                   if ($get_question_id) {
                                       $translated = $this->getProfileQuestion($get_question_id, $code, $lang);
                                       if ($translated) {
                                           $question_item['translated'][] = [
                                               'con_lang' => $code . '-' . $lang,
                                               'country_code' => $code,
                                               'language_code' => $lang,
                                               'text' => $translated->label,
                                               'hint' => $translated->hint,
                                               'mapping' => $this->getFulcrumProfileMappingData($global_profile_match_quest,$sj_code,$fl_code, $id, $get_question_id, $code, $lang),
                                               'answers' => $this->getProfileQuestionAnswer($global_profile_match_quest,$sj_code,$fl_code, $get_question_id, $code, $lang, $id) ,
                                       ];
                                   }
                               }
                           }
                       }
                   $questionItems[] = $question_item;
                 }
            }
        }
        $data = json_encode($questionItems);
        $data = substr($data, 1, -1).',';
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR. "Questions.json", $data, FILE_APPEND);
      /*  file_put_contents(__DIR__ . DIRECTORY_SEPARATOR, $data, FILE_APPEND);*/
        dd("done");
    }

    private function getDependency($get_question_id,$profile_section_name,$parental_status,$general_name)
    {
       $question_dependency = DB::connection('mysql_sjpanel')->table('profile_questions')
           ->select('dependency')
           ->where('id','=',$get_question_id)
           ->first();

       if(!empty($question_dependency->dependency)){
          $get_dependency = json_decode($question_dependency->dependency);
          if($get_dependency){
              $temp = [];
              foreach($get_dependency as $value){
                $temp[$value->id][] = $value->val;
              }
                $output = [];
                foreach($temp as $key=>$val){
                    $output[] = [
                        'id' => $key,
                        'val' => $val,
                    ];
                }
              if(in_array($general_name,$parental_status)){
                  $dependency[] = [
                      'question_code' => "GLOBAL_GENDER",
                      'profile_section_name' =>  "BASIC",
                      'precode' => [1],
                  ];
              }
              foreach($output as $dep){
                  $get_question_code = $this->getQuestionCode($dep['id']);
                  $precode = $dep['val'];
                  $dependency[] = [
                      'question_code' => $get_question_code->display_name,
                      'profile_section_name' =>  str_replace(" ","_",strtoupper($profile_section_name)),
                      'precode' => $precode,
                  ];
              }
              $json_data = json_encode($dependency);
          }
       } else{
         $json_data = null;
       }
       return $json_data;
    }

    private function getQuestionCode($question_id)
    {
        $data = DB::connection('mysql_sjpanel')->table('profile_questions')
            ->select('display_name')
            ->where('id','=',$question_id)
            ->first();

        return $data;
    }
    private function getQuestions($code)
    {
        $data = DB::connection('mysql_sjpanel')->table('profile_questions')
            ->select('profile_questions.*','profile_sections.display_name as section_name')
            ->join('profile_sections','profile_questions.profile_section_id','=','profile_sections.id')
            ->where('profile_questions.country_code',$code)
            ->get();
       return $data;
    }

    private function getQuestion_id($id, $code)
    {
        $data = DB::connection('mysql_sjpanel')
            ->table('profile_questions')
            ->select('id')
            ->where('country_code', $code)
            ->where('display_name', 'like', "%$id%")
            ->first();
        return $data;
    }

    private function getCountryDetails()
    {
        $countries = [];
        $data = DB::table('countries')->select('*')->where('is_filterable','=','1')->get();
        foreach ($data as $country) {
            $countries["$country->country_code"] = [
                'langs' => "$country->language",
            ];
        }
        return $countries;
    }

    private function getProfileQuestion($id, $code, $languages)
    {
        $locale = strtolower($languages) . "_" . $code;
        $data = DB::connection('mysql_sjpanel')->table('profile_question_translations')
            ->select('label','hint')
            ->where('profile_question_id',  $id)
            ->where('locale', $locale)
            ->first();
        return $data;
    }

    private function getFulcrumProfileMappingData($global_profile_match_quest,$sj_code,$fl_code, $id, $profile_question_id, $code, $lang)
    {
        $mapping_data = [];
        $lang_id = DB::table('languages')->where('code', $lang)->first();
        if ($lang_id) {
            $data = DB::connection('mysql_sjpanel')
                ->table('fulcrum_question')
                ->where('question_id', $profile_question_id)
                ->where('language_id', $lang_id->id)
                ->where('display_name', 'like', "%$id%")
                ->first();
            if ($data) {
                if ($data->fulcrum_question_id == 0) {
                    $mapping_data = [];
                } else {
                    $mapping_data[$fl_code] = [
                        'fulcrum_question_id' => $data->fulcrum_question_id,
                        'display_name' => $data->display_name,
                        'fulcrum_country_language_code' => $data->fulcrum_country_language_code,
                        'fulcrum_country_language_id' => $data->fulcrum_country_language_id,
                        'country' => $data->country,
                    ];
                }
            }
        }
        if(in_array($id,$global_profile_match_quest)){
            $mapping_data[$sj_code] = [
                'sj_question_id' => $id,
                'display_name' => $id,
                'language' => $lang,
                'country' => $code
            ];
        }
      return $mapping_data;
    }

    private function getProfileQuestionAnswer($global_profile_match_quest,$sj_code,$fl_code, $get_question_id, $code, $lang, $id)
    {
        $answer_return  = [];
        $locale = strtolower($lang)."_".$code;
        $get_profile_answer = $this->getProfileAnswer($get_question_id,$locale);
        if($get_profile_answer){
            foreach($get_profile_answer as $answer){
                $answer_item = [];
                $answer_item['display_name'] = $answer->display_name;
                $answer_item['precode'] = $answer->precode;
                $answer_item['precode_type'] = $answer->precode_type;
                $answer_item['text'] = $answer->text;
                $answer_item['hint'] = $answer->hint;
                $answer_item['mapping'] = $this->getAnswerMap($answer,$global_profile_match_quest,$sj_code,$id,$answer->precode,$fl_code,$get_question_id,$lang,$code);
                $answer_return[] = $answer_item;
            }
        }
       return $answer_return;
    }

    private function getProfileAnswer($get_question_id,$locale)
    {
        $data = DB::connection('mysql_sjpanel')
            ->table('profile_answers AS pa')
            ->select('pa.id', 'pa.display_name', 'pa.precode', 'pa.precode_type', 'trans.answer_text as text', 'trans.hint as hint')
            ->leftJoin('profile_answer_translations as trans', 'pa.id', '=', 'trans.profile_answer_id')
            ->where('pa.profile_question_id', '=', $get_question_id)
            ->where('trans.locale', '=', $locale)
            ->get();
     return $data;
    }

    private function getAnswerMap($answer,$global_profile_match_quest,$sj_code,$id,$precode,$fl_code,$get_question_id,$lang,$code)
    {
        $mapping_data = [];
        $lang_id = DB::table('languages')->where('code', $lang)->first();
        if($lang_id){
            $data = DB::connection('mysql_sjpanel')
                ->table('fulcrum_answers')
                ->where('profile_question_id',$get_question_id)
                ->where('country',$code)
                ->where('language_id',$lang_id->id)
                ->where('precode',$precode)
                ->get();
            if($data){
                foreach ($data as $fulcrum_map_answer){
                    if($fulcrum_map_answer->fulcrum_question_id==0){
                        $mapping_data = [];
                    }else{
                        $mapping_data[$fl_code] = [
                            'text' => $fulcrum_map_answer->display_name,
                            'precode' => $fulcrum_map_answer->fulcrum_precode,
                            'fulcrum_country_id' => $fulcrum_map_answer->fulcrum_country_language_id,
                            'fulcrum_country_code' => $fulcrum_map_answer->fulcrum_country_language_code,
                        ];
                    }
                }
            }
            if (in_array($id, $global_profile_match_quest)) {
                $mapping_data[$sj_code] = [
                    'text' => $answer->display_name,
                    'precode' => $answer->precode,
                    'sj_country_code' => $code,
                ];
            }
        }
        return $mapping_data;
    }


    public function allProject()
    {
        $get_quota_details = ProjectQuota::where('project_id','=','1')->get()->toArray();
        $project_details = Project::whereBetween('id',['2','17'])->get()->toArray();
        foreach($project_details as $project){
            foreach ($get_quota_details as $quota){
                $quota_details = $quota;
                unset($quota_details['id'],$quota_details['project_id']);
                $project_id = $project['id'];
                $data = [
                    'project_id' => $project_id,
                ];
                $new_data = array_merge($quota_details,$data);
                $insert_data = ProjectQuota::create($new_data);
            }
        }
        dd("done");
        /*$project = [];
        $projects = Project::all()->toArray();
        foreach($projects as $key=>$value){
        $project[$key] = $value;
        }
        $get_client = Client::all()->first()->toArray();
        unset($get_client['id'],$get_client['name'],$get_client['code'],$get_client['cvars'],$get_client['phone']);
        $get_remaining_client = Client::all()->toArray();
        unset($get_remaining_client[0]);
        $create_data = [];
        for($i=5;$i<=29;$i++){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $name = "Test Client".$i;
            $cvars = str_random(3);
            $code = "TCL".$i;
            $phone = rand(1111111111,mt_getrandmax());

            $create_data = [
               'name' =>  $name,
                'cvars' => $cvars,
                'code' => $code,
                'phone' => $phone,
            ];
            $data[] = array_merge($create_data,$get_client);
        }
           $new_data = array_merge_recursive($get_remaining_client,$data);
        $final_data = json_encode($new_data);
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR, $final_data, FILE_APPEND);
        dd("done");*/
    }

    public function dispayConLang(Request $request)
    {
        $question_id = $request->input('qid', 'GLOBAL_GENDER');
        $age = $request->input('GLOBAL_AGE', 'GLOBAL_GENDER');
        $zip = $request->input('GLOBAL_ZIP', 'GLOBAL_GENDER');
        $education = $request->input('GLOBAL_EDUCATION', 'GLOBAL_GENDER');
        $income = $request->input('GLOBAL_INCOME', 'GLOBAL_GENDER');

        $question = $this->globalQuesRepo->getGlobalQuestionLangs($question_id);
        $languages = collect($question->translated);
        $education_array = $languages->flatten()->sort()->values()->all();
        dd($education_array);

    }

    public function countryMasterData()
    {

        $general_name = 'GLOBAL_GENDER';
       /* $country_code = GlobalQuestion::where('general_name','=','GLOBAL_AGE')
            ->project($project)
            ->first();*/
        $data = GlobalQuestion::raw(function($collection) use ($general_name) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'general_name' => $general_name,
                    ]
                ],
                [
                    '$project' => [
                        'translated' => [
                            'con_lang' =>1,
                        ],
                    ]
                ]
            ]);
        })->first()->toArray();

        $country_code = [];
        foreach($data['translated'] as $key ){
          foreach ($key as $value){
              $country_code[] = $value;
          }
        }


        dd($country_code);


        $global_master_data = [];
        $us_data = $this->getUSData();
        $ca_data = $this->getCAData();
        $uk_data = $this->getUKData();
        $fr_data = $this->getFRData();
        $global_master_data[] = $us_data;
        $global_master_data[] = $ca_data;
        $global_master_data[] = $uk_data;
        $global_master_data[] = $fr_data;
        $json = json_encode($global_master_data);
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR. "master_country_data.json", $json);
        dd("done");
    }


    private function getFRData()
    {
        $fr_master_data = DB::connection('mysql_sjpanel')->table('master_table_fr')
            ->get();
        $master_data = [];
        $quota_master_mapping = [
        'DEPARTMENT' => "department",
        'REGION' => "region",
        'SUBDIVISION' => "subdivision",
        'MASTER_ID' => 'id',
    ];
        $master_data['country_code'] = 'FR';
        $master_data['country_name'] = 'France';
        $master_data['fillable'] = $quota_master_mapping ;
        foreach($fr_master_data as $data){
            $fr_master = [];
            $fr_master = [
                'department' => $data->department,
                'region' => $data->region,
                'subdivision' => $data->subdivision,
            ];
            $master_data['country_data'][] = $fr_master;
            $master_data['field'] = array_keys($fr_master);
        }
       return $master_data;
    }
    private function getUSData()
    {
        $us_master_data = DB::connection('mysql_sjpanel')->table('us_zip_data')
            ->first();
        dd($us_master_data);
        $master_data = [];
        $fillables = [
            'STATE' => "state",
            'DMA' => "dma_code",
            'DMA_NAME' => "dma_name",
            'DIVISON' => "division",
            'REGION' => "region",
            'MASTER_ID' => 'id',
        ];
        $master_data['country_code'] = 'US';
        $master_data['country_name'] = 'United States';
        $master_data['fillable'] = $fillables;

        foreach($us_master_data as $data){
            $us_master = [];
            $us_master = [
                'zip' => $data->zip,
                'state' => $data->state,
                'city' => $data->city,
                'division' => $data->division,
                'state_code' => $data->state_code,
                'fips' => $data->fips,
                'matched' => $data->matched,
                'county' => $data->county,
                'msa_code' => $data->msa_code,
                'msa_description' => $data->msa_description,
                'pmsa_code' => $data->pmsa_code,
                'pmsa_description' => $data->pmsa_description,
                'dma_code' => $data->dma_code,
                'dma_name' => $data->dma_name,
                'market' => $data->market,
            ];
            $master_data['country_data'][] = $us_master;
            $master_data['field'] = array_keys($us_master);
        }
        return $master_data;
    }

    private function getCAData()
    {
        $ca_data =  DB::connection('mysql_sjpanel')->table('canada_postal_code')
            ->get();
        $master_data = [];
        $quota_master_mapping = [
            'PROVINCE' => "province",
            'REGION' => "region",
            'MASTER_ID' => 'id',
        ];
        $master_data['country_code'] = 'CA';
        $master_data['country_name'] = 'Canada';
        $master_data['fillable'] = $quota_master_mapping;
        foreach($ca_data as $data){
            $ca_master = [];
            $ca_master = [
                'postcode' => $data->postcode,
                'province' => $data->province,
                'region' => $data->region,
            ];
            $master_data['country_data'][] = $ca_master;
            $master_data['field'] = array_keys($ca_master);
        }
        return $master_data;
    }

    private function getUKData()
    {
        $uk_data =  DB::connection('mysql_sjpanel')->table('uk_master_table')
            ->get();
        $master_data = [];
        $fillables = [
            'REGION' => "european_electoral_region",
            'COUNTY' => "nuts",
        ];
        $master_data['country_code'] = 'UK';
        $master_data['country_name'] = 'United Kingdom';
        $master_data['fillable'] = $fillables;
        foreach($uk_data as $data){
            $uk_master = [];
            $uk_master = [
                'county' => $data->county,
                'region' => $data->region,
            ];
            $master_data['country_data'][] = $uk_master;
            $master_data['field'] = array_keys($uk_master);
        }
        return $master_data;
    }
}
