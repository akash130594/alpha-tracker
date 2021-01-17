<?php

namespace App\Http\Controllers\Web\Internal\Project;

use App\Models\Project\Project;
use App\Models\Sjpanel\InviteTemplates;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use League\Flysystem\FileNotFoundException;
use Illuminate\Support\Facades\Redirect;

class ProjectInviteController extends Controller
{
    public function index(Request $request, $id)
    {
        $project = Project::find($id);
        $inviteTemplates = InviteTemplates::where('status', '=', 'active')->get();
        return view('internal.project.edit.panel_invite')
            ->with('inviteTemplates', $inviteTemplates)
            ->with('project', $project);
    }

    public function showCustomEditor(Request $request, $id)
    {
        $project = Project::find($id);
        $get_custom_details = InviteTemplates::where('is_custom','=','1')->first();
        if (!empty($get_custom_details)) {
            return view('internal.project.edit.invite.custom')
                ->with('project', $project)
                ->with('custom_details',$get_custom_details);
        }else{
            return view('internal.project.edit.panel_invite')
                ->with('project', $project);
        }
    }

    public function updateCustomEditor(Request $request)
    {
       $custom_id = $request->custom_id;
       $project_id = $request->id;
       $updated_body = $request->input('custom_invite_body',false);
       $data = [
           'body' => $updated_body,
       ];
      $update_custom = InviteTemplates::where('id',$custom_id)->update($data);
        if($update_custom){
            return redirect()->route('internal.project.edit.panel_invite.show',$project_id)
                ->withFlashSuccess("Templates Updated");
        }else{
            return redirect()->route('internal.project.edit.panel_invite.show',$project_id)
                ->withErrors("Some Error Occurred");
        }
    }
    public function editTemplate(Request $request)
    {
        $inviteTemplateId = $request->template_id;
        $project_id = $request->id;
        $project =  $project = Project::find($project_id);
       $get_invite_info = InviteTemplates::where('id',$inviteTemplateId)->first();
        return view('internal.project.edit.invite.edit')
            ->with('invite_details',$get_invite_info)
            ->with('project',$project);

    }

    public function postTemplate(Request $request)
    {
        $template_id = $request->template_id;
        $project_id = $request->id;
        $subject = $request->input('subject',false);
        $body = $request->input('body',false);
        $data = [
            'subject' => $subject,
            'body' => $body,
        ];
        $update_templates = InviteTemplates::where('id',$template_id)->update($data);
       if($update_templates){
           return redirect()->route('internal.project.edit.panel_invite.show',[$project_id,$template_id])
               ->withFlashSuccess("Templates Updated");
       }else{
           return redirect()->route('internal.project.edit.panel_invite.show',[$project_id,$template_id])
               ->withErrors("Some Error Occurred");
       }
    }
    /*public function upload_image(Request $request)
    {
        if ($request->hasFile('image')) {
            if($request->file('image')->isValid()) {
                try {
                    $file = $request->file('image');
                    $name = time() . '.' . $file->getClientOriginalExtension();
                    $request->file('image')->move(public_path('temp'), $name);

                    $responseData = array(
                        'size' => '200*200',
                        'url' => URL::asset('temp/'.$name),
                    );
                    return response()->json($responseData);
                } catch (FileNotFoundException $e) {

                }
            }
        }
    }

    public function insert_image(Request $request)
    {
        $url = $request->input('url');
        $width = $request->input('width');
        $crop = $request->input('crop');
        $responseData = [
            'url' => $url,
            'size' => [
                $width,
                200
            ],
            'alt' => 'Uploaded',
        ];
        return response()->json($responseData);
    }*/
}
