<?php

namespace App\Listeners\Internal\Project\SourceAPI\Fulcrum\traits\methods;


trait FulcrumMethods
{
    public function getApiHeaders()
    {
        return [
            'headers' => [
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $this->apiKey,
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
