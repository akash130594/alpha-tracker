<?php
/**
 * Created by PhpStorm.
 * User: Sample Junction
 * Date: 3/28/2019
 * Time: 1:10 AM
 */

namespace App\Listeners\Internal\Project\SourceAPI\SJPanel\traits\methods;


trait SJPanelMethods
{
    public function getApiHeaders()
    {
        return [
            'headers' => [
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer ". $this->apiKey,
            ]
        ];
    }

    public function applyUrlChange($actionUrl)
    {
        $translations = [
            '{{url}}' => $this->url,
            '{{SurveyNumber}}' => $this->survey_number,
        ];
        $translatedUrl = strtr($actionUrl, $translations);
        return $translatedUrl;

    }
}
