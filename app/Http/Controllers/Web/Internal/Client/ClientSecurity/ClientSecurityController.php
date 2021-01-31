<?php

namespace App\Http\Controllers\Web\Internal\Client\ClientSecurity;

use App\Models\Client\ClientSecurityType;
use App\Repositories\Internal\Client\ClientRepository;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;




class ClientSecurityController extends Controller
{
    /**
     * @param $clientRepo
     * @var ClientRepository
     */
    public $clientRepo;

    /**
     * ClientSecurityController constructor.
     * @param ClientRepository $client_repository
     */
    public function __construct(ClientRepository $client_repository)
    {
        $this->clientRepo = $client_repository;
        //$this->detailedProfileRepo = $detailedProfileRepo;
    }

    /**
     * This function is used the details of all the client's hash security details and redirecting the user to view of
     * Client Security .
     *
     * @return resource client/client_security/index.blade.php
     */
    public function index()
    {
        return View('internal.client.client_security.index')
            //->with('clients', Client::limit(5))
            ;
    }

    /**
     * This function is used to make a query for getting all the data required to show in datatable using Ajax.
     *
     * @return array
     */
    public function datatable()
    {
        return Laratables::recordsOf(ClientSecurityType::class);
    }

    /**
     * This function is used to display the view of edit client security of particular client.
     *
     * @param Request $request
     * @return resource client_security/edit.blade.php
     */
    public function editClientSecurity(Request $request)
    {
        $security_type_id = $request->id;
        $security_type_data = ClientSecurityType::where('id','=',$security_type_id)->first();
        return view('internal.client.client_security.edit')
            ->with('client_security',$security_type_data);
    }

    /**
     * This function is used to post the edited client security of the client and saving it.
     *
     * @param Request $request
     * @return mixed
     */
    public function postEditClientSecurity(Request $request)
    {
        dd($request->all());
        $security_type_id = $request->id;
        $code = $request->input('code', false);
        $name = $request->input('name', false);
        $field_data = $request->input('field_data', false);
        $update_client_security_type = $this->clientRepo->updateClientSecurityType($name,$code,$field_data,$security_type_id);
        if($update_client_security_type){
            return \Redirect::back()
                ->withFlashSuccess("Security Type Updated");
        } else{
            return \Redirect::back()
                ->withDanger("Security Type Cannot be updated");
        }
    }

    /**
     * This function is used for showing the form of Create new security Type.
     *
     * @return resource client_security/create.blade.php
     */
    public function createSecurityType()
    {
        return view('internal.client.client_security.create');
    }

    /**This function is used for posting the newly created security type and saving it.
     *
     * @param Request $request
     * @return mixed
     */
    public function postCreateSecurityType(Request $request)
    {
        $code = $request->input('code',false);
        $name = $request->input('name',false);
        $field_data = $request->input('name',false);
        $create_security_type = $this->clientRepo->createSecurityType($name,$code,$field_data);
        if($create_security_type){
            return \Redirect::back()
                ->withFlashSuccess("Security Type Created");
        } else{
            return \Redirect::back()
                ->withDanger("Security Type Cannot be Created");
        }
    }
}
