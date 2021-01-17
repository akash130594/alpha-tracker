<?php

use Illuminate\Database\Seeder;
use App\Models\Sjpanel\InviteTemplates;
class InviteTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Sjpanel\InviteTemplates::create([
            'name' => 'Templates 1',
            'label' => 'Templates 1',
            'description' => 'Templates 1',
            'subject' => 'SJ panel New Survey Opportunity for you',
            'body' => 'Dear Valued Panelist,
                            SJ Panel invites you to share your opinions in a new study.
                            If you qualify and complete this survey, your SJ Panel account will be credited with {%S_POINTS%} points. 
                            As always, we will keep your responses in the strictest confidence, and they will be used for research purposes only.
                            Survey Number: {%S_CODE%}
                            Estimated length of survey: {%S_LOI%} minutes
                            Points: {%S_POINTS%}
                            Survey ends on: {%S_EDATE%}
                            
                            <a href="{%S_LINK%}">Start Survey</a>
                            
                            You can also copy & paste this link in your web browser to start the survey: {%S_LINK%}
                            
                            
                            This survey is coming from an international company.
                            
                            
                            If you have any problems, please send an e-mail to help@sjpanel.com.
                            
                            Thank you for making SJ panel the trusted connection between people and research.
                            
                            Best Regards,
                            SJ Panel Team',
            'image_url' => 'template_1.png',
            'is_custom' => '0',
            'status' => 'active',
            'order' => '10',
        ]);

        \App\Models\Sjpanel\InviteTemplates::create([
            'name' => 'Templates 2',
            'label' => 'Templates 2',
            'description' => 'Templates 2',
            'subject' => 'New Survey',
            'body' => 'Dear Valued Panelist,

                        SJ Panel invites you to share your opinions in a new study.
                        
                        If you qualify and complete this survey, your SJ Panel account will be credited with {%S_POINTS%} points. 
                        As always, we will keep your responses in the strictest confidence, and they will be used for research purposes only.
                        
                        Survey Number: {%S_CODE%}
                        Estimated length of survey: {%S_LOI%} minutes
                        Points: {%S_POINTS%}
                        Survey ends on: {%S_EDATE%}
                        
                        <a href="{%S_LINK%}">Start Survey</a>
                        
                        You can also copy & paste this link in your web browser to start the survey: {%S_LINK%}
                        
                        
                        This survey is coming from an international company.
                        
                        
                        If you have any problems, please send an e-mail to help@sjpanel.com.
                        
                        Thank you for making SJ panel the trusted connection between people and research.
                        
                        Best Regards,
                        SJ Panel Team',
            'image_url' => 'template_2.jpg',
            'is_custom' => '0',
            'status' => 'active',
            'order' => '10',
        ]);

        \App\Models\Sjpanel\InviteTemplates::create([
            'name' => 'Templates 3',
            'label' => 'Templates 3',
            'description' => 'Templates 3',
            'subject' => 'Custom Survey',
            'body' => 'Dear Valued Panelist,

                        SJ Panel invites you to share your opinions in a new study.
                        
                        If you qualify and complete this survey, your SJ Panel account will be credited with {%S_POINTS%} points. 
                        As always, we will keep your responses in the strictest confidence, and they will be used for research purposes only.
                        
                        Survey Number: {%S_CODE%}
                        Estimated length of survey: {%S_LOI%} minutes
                        Points: {%S_POINTS%}
                        Survey ends on: {%S_EDATE%}
                        
                        <a href="{%S_LINK%}">Start Survey</a>
                        
                        You can also copy & paste this link in your web browser to start the survey: {%S_LINK%}
                        
                        
                        This survey is coming from an international company.
                        
                        
                        If you have any problems, please send an e-mail to help@sjpanel.com.
                        
                        Thank you for making SJ panel the trusted connection between people and research.
                        
                        Best Regards,
                        SJ Panel Team',
            'image_url' => 'template_3.jpg',
            'is_custom' => '1',
            'status' => 'active',
            'order' => '10',
        ]);


    }
}
