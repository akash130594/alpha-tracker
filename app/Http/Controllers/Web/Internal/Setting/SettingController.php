<?php

namespace App\Http\Controllers\Web\Internal\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    public function index()
    {
        $report_pagination = setting('reports_per_page');
        $unique_folder_name = setting('unique_folder_name');
        $project_quota = setting('project_quota');
       return view('internal.setting.index')
           ->with('report_pagination',$report_pagination)
           ->with('unique_folder_name',$unique_folder_name)
           ->with('project_quota',$project_quota);
    }
    public function postData(Request $request)
    {
       $folder_name = $request->input('unique_id_folder_name',false);
       $report_per_page = $request->input('report_pagination',false);
       $project_quota = $request->input('project_quota',false);
       $data = [
           'reports_per_page' => $report_per_page,
           'unique_folder_name' => $folder_name,
           'project_quota' => $project_quota,
       ];
       $update_setting = setting($data)->save();
          return Redirect::back()
              ->withFlashSuccess("Setting has been updated");
    }
    public function routerSetting()
    {
        $router_details = setting('router');
        return view('internal.setting.router')
            ->with('router_details',$router_details);
    }
    public function routerPostSetting(Request $request)
    {
        $domain = $request->input('domain',false);
        $start_page = $request->input('start_page',false);
        $end_page = $request->input('end_page',false);
        $data['router'] = [
                'domain' => $domain,
                'start_page' => $start_page,
                'end_page' => $end_page,
        ];
        $update_setting = setting($data)->save();
        return Redirect::back()
            ->withFlashSuccess("Router Setting has been updated");
    }
}
