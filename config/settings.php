<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Default Settings Store
	|--------------------------------------------------------------------------
	|
	| This option controls the default settings store that gets used while
	| using this settings library.
	|
	| Supported: "json", "database"
	|
	*/
	'store' => 'json',

	/*
	|--------------------------------------------------------------------------
	| JSON Store
	|--------------------------------------------------------------------------
	|
	| If the store is set to "json", settings are stored in the defined
	| file path in JSON format. Use full path to file.
	|
	*/
	'path' => storage_path().'/settings.json',

	/*
	|--------------------------------------------------------------------------
	| Database Store
	|--------------------------------------------------------------------------
	|
	| The settings are stored in the defined file path in JSON format.
	| Use full path to JSON file.
	|
	*/
	// If set to null, the default connection will be used.
	'connection' => null,
	// Name of the table used.
	'table' => 'settings',
	// If you want to use custom column names in database store you could 
	// set them in this configuration
	'keyColumn' => 'key',
	'valueColumn' => 'value',

    'FL_API' => [
        'API_URL' => env('FL_API_URL', 'https://sandbox.techops.engineering'),
        'API_KEY' => env('FL_API_KEY', '188F4EEB-ED0A-49EA-B166-E97EA2383B5A'),
        'ACCOUNT_ID' => env('FL_ACCOUNT_ID', 1),
        'SOURCE_CODE' => "1185",
    ],

    'SJPANEL_API' => [
        'API_URL' => env('SJPANEL_API_URL', 'http://sjpanel-new.local'),
        'API_KEY' => env('SJPANEL_API_KEY', '5F4943277A063EF26D25D7BBC57298FFB2029915'),
        'SOURCE_CODE' => "SJPL",
    ],

];
