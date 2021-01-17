<?php

namespace App\Http\Controllers\Web\Internal\Client;

use App\Http\Requests\Internal\Client\CreateRequest;
use App\Http\Requests\Internal\Client\UpdateRequest;
use App\Http\Requests\Internal\Client\UpdateValidationRequest;
use App\Models\Client\Client;
use App\Models\Client\ClientSecurityImpl;
use App\Models\Client\ClientSecurityType;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Internal\Client\ClientRepository;
use Illuminate\Support\Facades\DB;



/**
 * This class is used to handle all the functionality as show all the details of all the clients, edit, create new clients.
 *
 * Class ClientController
 * @author Pankaj Jha
 * @author Akash Sharma
 * @access public
 * @package  App\Http\Controllers\Web\Internal\Client\ClientController
 */

class ClientController extends Controller
{


    /**
     * ProfileController constructor.
     *
     */

    public $user_repo;

    /**
     * ClientController constructor.
     * @param ClientRepository $client_repository
     */
    public function __construct(ClientRepository $client_repository)
    {
        $this->user_repo = $client_repository;
        //$this->detailedProfileRepo = $detailedProfileRepo;
    }
    /**
     * This function is used to show all the clients with their details in datatable.
     *
     * @author Pankaj Kumar Jha
     *
     * @return resource client/index.blade.php
     */
    public function index()
    {
        return View('internal.client.index')
            //->with('clients', Client::limit(5))
        ;
    }

    /**
     * This function is used to make a query for getting all the data of clients in order as required to display in datatable.
     *
     * @return array
     */
    public function datatable()
    {
        return Laratables::recordsOf(Client::class);
    }

    /**
     * This function is used to redirect the user to view of create new clients.
     *
     * @param Request $request
     * @return resource client/create.blade.php
     */
    public function createClient(Request $request)
    {

        return view('internal.client.create');
    }

    /**
     * This function is required for posting the crated client and saving the details.
     *
     * @param CreateRequest $request
     * @return mixed
     */
    public function postCreateClient(CreateRequest $request)
    {
        $data = $request->except(['_token']);
        $create_client = $this->user_repo->createClient($data);
        if($create_client){
            return redirect()->route('internal.client.index')
                ->withFlashSuccess('Client Created');
        } else{
            return Redirect::back()
                ->withFlashSuccess('Some Error Occured while Creating');
        }
    }

    /**
     * This function redirect the user to edit view page to edit the particular client.
     * @param Request $request
     * @param $id
     *
     * @return resource client/edit.blade.php
     */
    public function editClient(Request $request, $id)
    {
        $find_client = $this->user_repo->findClient($id);
        //$client = Client::where('id', '=', $id)->with('securityImpl', 'securityImpl.securityType')->first();
        $client = $this->user_repo->getClientDetails($id);
        $study_type = $this->user_repo->getStudyType();
        $clients_details = $client[0];
        $client_security = $client[1];

        return View('internal.client.edit')
            ->with('client_details', $clients_details)
            ->with('client_security', $client_security)
            ->with('study_types',$study_type);
    }

    /**
     * This function is used for posting the edited client data and saving the details.
     *
     * @param UpdateRequest $request
     * @param $id
     * @return mixed
     */
    public function updateClient(UpdateRequest $request, $id)
    {
        $input = $request->except('_token','website','email','phone','enable_url','_method','redirector_flag','redirect_survey_type_flag','study_type','age_url','education_url','gender_url','income_url','ethinicity_url');
        $redirect_screener_parameters = json_encode(array(
            'age' => $request->input('age_url',false),
            'education' => $request->input('education_url',false),
            'gender' => $request->input('gender_url',false),
            'income' => $request->input('income_url',false),
            'ethinicity' => $request->input('ethinicity_url',false),
        ));
        $redirect_survey_type_flag = $request->input('redirect_survey_type_flag',false);
        $redirect_flag = $request->input('redirector_flag',false);
        $study_type_id = $request->input('study_type',false);
        $website = $request->input('website',false);
        $email = $request->input('email',false);
        $phone = $request->input('phone',false);
        $client = $this->user_repo->findClient($id);
        $input_data = [
        'website' => $website,
            'email' => $email,
            'phone' => $phone,
                'redirector_flag' => $redirect_flag,
            'redirector_screener_parameters' => $redirect_screener_parameters,
            'redirector_survey_type_flag' => $redirect_survey_type_flag,
            'redirect_study_type_id' => $study_type_id,
        ];
        $data = array_merge($input,$input_data);
        $status = $this->user_repo->updateClient($data,$id);
        if($status){
            return Redirect::back()
                ->withFlashSuccess('Client Updated');
        }else{
            return Redirect::back()
                ->withError('Some Error Occurred while updating');
        }
    }

    /**
     * This function is used to redirect the user to the edit security view page for a particular client.
     *
     * @param Request $request
     * @param $id
     * @return resource client/security/edit.blade.php
     */
    public function editSecurity(Request $request, $id)
    {
        $security_types = $this->user_repo->getSecurityType();
        /*$clientSecurityImpl = ClientSecurityImpl::where([
            ['client_id', '=', $id],
        ])->with('securityType')->first();*/

        $clientSecurityImpl = $this->user_repo->getSecurityDetails($id);

        return View('internal.client.security.index')
            ->with('types', array_merge([0 => '----Select Any----'],$security_types))
            ->with('client_id', $id)
            ->with('clientSecurityImpl', $clientSecurityImpl);
    }


    public function getSecurityTypeForm(Request $request, $id)
    {
        $type_id = $request->input('type_id');
        $client_id = $id;
       /* $security_type = ClientSecurityType::find($type_id);*/
        $security_type = $this->user_repo->getClientSecurityType($type_id);
       /* $clientSecurityImpl = ClientSecurityImpl::where([
            ['client_id', '=', $client_id],
            ['security_type_id','=', $type_id]
        ])->with('securityType')->first();*/
       $clientSecurityImpl = $this->user_repo->getTypeForm($client_id,$type_id);
        return View('internal.client.security.typeform')
            ->with('fields', json_decode($security_type->field_data))
            ->with('clientSecurityImpl', $clientSecurityImpl)
            ->render();
    }
    public function deleteClient(Request $request, $id)
    {
        $delete_client = $this->user_repo->deleteClient($id);
        if($delete_client){
            return redirect()->route('internal.client.index')
                ->withFlashSuccess('Client Deleted');
        }else{
            return redirect()->route('internal.client.index')
                ->withFlashError('Some Error Occured while deleting');
        }
    }
    public function updateClientSecurityImpl(UpdateValidationRequest $request, $id)
    {
        $method_data = $request->except(['_token', '_method','security_type_id']);
        $security_type_id = $request->input('security_type_id');
        $data = [
            'client_id' => $id,
            'security_type_id' => $security_type_id,
            'method_data' => json_encode($method_data),
        ];
        $data2 = [
            'security_type_id' => $security_type_id,
            'method_data' => json_encode($method_data),
        ];

        /*$status = ClientSecurityImpl::updateOrCreate([
            'client_id' => $id
        ], $data );*/

        $status = $this->user_repo->updateClientValidation($data,$id,$data2);
        if($status){
            /*$client = Client::find($id);*/

            $client = $this->user_repo->getClient($id);
            $client->security_flag = true;
            $client_update_security_flag = $this->user_repo->updateSecurityFlag($id);
           return redirect()->route('internal.client.edit.show', $id)
                ->withFlashSuccess('Security Details Updated');
        }else{
            return Redirect::back()
                ->withFlashError('Some Error Occured while updating');
        }

    }

    public function removeClientValidation(Request $request, $id, $impl_id)
    {
        $client = $this->user_repo->getClient($id);
        $client->security_flag = false;
        $client->save();
       /* ClientSecurityImpl::find($impl_id)->delete();*/
        $delete_validation = $this->user_repo->deleteValidation($impl_id);

        return redirect()->route('internal.client.edit.show', $id)
            ->withFlashSuccess('Validation Removed');
    }

}
