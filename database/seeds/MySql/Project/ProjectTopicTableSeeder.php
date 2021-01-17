<?php

use Illuminate\Database\Seeder;
use App\Models\Project\ProjectTopic;

class ProjectTopicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = '[{"Code":"AUTO","Id":"1","IsActive":true,"Name":"Automotive","SortOrder":1},{"Code":"BEAU","Id":"2","IsActive":true,"Name":"Beauty/Cosmetics","SortOrder":2},{"Code":"BEV-AL","Id":"3","IsActive":true,"Name":"Beverages - Alcoholic","SortOrder":3},{"Code":"BEV-NONAL","Id":"4","IsActive":true,"Name":"Beverages - Non Alcoholic","SortOrder":4},{"Code":"EDUC","Id":"5","IsActive":true,"Name":"Education","SortOrder":5},{"Code":"ELEC","Id":"6","IsActive":true,"Name":"Electronics/Computer/Software","SortOrder":6},{"Code":"ENTE","Id":"7","IsActive":true,"Name":"Entertainment (Movies, Music, TV, etc)","SortOrder":7},{"Code":"FASH","Id":"8","IsActive":true,"Name":"Fashion/Clothing","SortOrder":8},{"Code":"FINA","Id":"9","IsActive":true,"Name":"Financial Services/Insurance","SortOrder":9},{"Code":"FOOD","Id":"10","IsActive":true,"Name":"Food/Snacks","SortOrder":10},{"Code":"GAMB","Id":"11","IsActive":true,"Name":"Gambling/Lottery","SortOrder":11},{"Code":"HEAL","Id":"12","IsActive":true,"Name":"Healthcare/Pharmaceuticals","SortOrder":12},{"Code":"HOME-UT","Id":"13","IsActive":true,"Name":"Home (Utilities, Appliances, ...)","SortOrder":13},{"Code":"HOME-EN","Id":"14","IsActive":true,"Name":"Home Entertainment (DVD, VHS)","SortOrder":14},{"Code":"HOME-IM","Id":"15","IsActive":true,"Name":"Home Improvement/Real Estate/Construction","SortOrder":15},{"Code":"IT","Id":"16","IsActive":true,"Name":"IT (Servers, Databases, etc)","SortOrder":16},{"Code":"PERS","Id":"17","IsActive":true,"Name":"Personal Care/Toiletries","SortOrder":17},{"Code":"PETS","Id":"18","IsActive":true,"Name":"Pets","SortOrder":18},{"Code":"POLI","Id":"19","IsActive":true,"Name":"Politics","SortOrder":19},{"Code":"PUBL","Id":"20","IsActive":true,"Name":"Publishing (Newspaper, Magazines, Books)","SortOrder":20},{"Code":"REST","Id":"21","IsActive":true,"Name":"Restaurants","SortOrder":21},{"Code":"SPOR","Id":"22","IsActive":true,"Name":"Sports","SortOrder":22},{"Code":"TELE","Id":"23","IsActive":true,"Name":"Telecommunications (phone, cell phone, cable)","SortOrder":23},{"Code":"TOBA","Id":"24","IsActive":true,"Name":"Tobacco (Smokers)","SortOrder":24},{"Code":"TOYS","Id":"25","IsActive":true,"Name":"Toys","SortOrder":25},{"Code":"TRAN","Id":"26","IsActive":true,"Name":"Transportation/Shipping","SortOrder":26},{"Code":"TRAV","Id":"27","IsActive":true,"Name":"Travel","SortOrder":27},{"Code":"VIDE","Id":"28","IsActive":true,"Name":"Video Games","SortOrder":28},{"Code":"WEBS","Id":"29","IsActive":true,"Name":"Websites/Internet/E-Commerce","SortOrder":29},{"Code":"Z_OT","Id":"30","IsActive":true,"Name":"Other","SortOrder":30},{"Code":"SENS","Id":"31","IsActive":true,"Name":"Sensitive Content","SortOrder":31},{"Code":"EXPL","Id":"32","IsActive":true,"Name":"Explicit Content","SortOrder":32}]';
        $decoded = json_decode($data, true);
        foreach ($decoded as $topic) {
            ProjectTopic::create([
                'code' => $topic['Code'],
                'name' => $topic['Name'],
            ]);
        }
    }
}
