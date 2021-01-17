<?php
/**
 * Created by PhpStorm.
 * User: SampleJunction
 * Date: 05-12-2018
 * Time: 11:11 PM
 */

namespace App\Repositories\Internal\Client;

use App\Models\Client\Client;
use App\Models\Client\ClientSecurityType;
use App\Models\Project\StudyType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;


class ClientRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Client::class;
    }

    public function findClient($id)
    {
        $data = DB::table('clients')
            ->where('id','=',$id)
            ->select('*')
            ->first();

        return $data;
    }

    public function createClient($details)
    {
        $data = DB::table('clients')
            ->insert($details);
        return $data;

    }

    public function updateClient($input,$id)
    {

        $data = DB::table('clients')
            ->where('id','=',$id)
            ->update($input);
        return $data;
    }

    public function getSecurityType()
    {
        $data = DB::table('client_security_types')
            ->select('*')
            ->pluck('name','id')->toArray();
        return $data;
    }
    public function deleteClient($id)
    {
        $data = DB::table('clients')
            ->where('id','=',$id)
            ->delete();

        return $data;
    }
    public function getClientDetails($id)
    {
        DB::connection()->enableQueryLog();

        $data = DB::table('clients')
            ->select('*')
            ->where('id','=',$id)
            ->first();


        $data1 = DB::table('client_security_impls')
            ->join('client_security_types','client_security_impls.security_type_id', '=', 'client_security_types.id')
           ->where('client_security_impls.client_id','=',$id)
            ->first();
            //dd(DB::getQueryLog());
       return [$data,$data1];
    }

    public function getSecurityDetails($id)
    {
        $data1 = DB::table('client_security_impls')
            ->join('client_security_types','client_security_impls.security_type_id', '=', 'client_security_types.id')
            ->where('client_security_impls.client_id','=',$id)
            ->first();

        return $data1;
    }

    public function deleteValidation($impl_id)
    {
        $data = DB::table('client_security_impls')
            ->where('id','=',$impl_id)
            ->delete();
        return $data;
    }

    public function getClientSecurityType($type_id)
    {
        $data = DB::table('client_security_types')
            ->select('*')
            ->where('id','=',$type_id)
            ->first();
        return $data;
    }

    public function getTypeForm($client_id,$type_id)
    {
        $data1 = DB::table('client_security_impls')
            ->join('client_security_types','client_security_impls.security_type_id', '=', 'client_security_types.id')
            ->select('*')
            ->where([
                ['client_security_impls.client_id', '=', $client_id],
                ['client_security_impls.security_type_id','=', $type_id]
            ])->first();

        return $data1;
    }

    public function updateClientValidation($data, $id, $data2)
    {

        $status = DB::table('client_security_impls')
            ->select('*')
            ->where('client_id','=',$id)
            ->get();

        if($status->isNotEmpty()){
            $data2 = DB::table('client_security_impls')
                ->where('client_id','=',$id)
                ->update($data2);
        } else {
            $data2 = DB::table('client_security_impls')
                ->insert($data);
        }
        return $data2;

    }

    public function getClient($id)
    {
        $data = DB::table('clients')
            ->select('*')
            ->where('id','=',$id)->first();
        return $data;
    }
    public function updateSecurityFlag($id)
    {
        $data = DB::table('clients')
            ->where('id' ,'=',$id)
            ->update(['security_flag' => true]);
        return $data;
    }

    public function getStudyType()
    {
        $data = StudyType::select('id','name')->get()->toArray();
        return $data;
    }

    public function updateClientSecurityType($name,$code,$field_data,$security_type_id)
    {
        $update_data = [
            'name' => $name,
            'code' => $code,
            'field_data' => $field_data,
        ];
        $update = ClientSecurityType::where('id', '=', $security_type_id)
            ->update($update_data);
        return $update;
    }

    public function createSecurityType($name,$code,$field_data)
    {
        $create_data = [
            'name' => $name,
            'code' => $code,
            'field_data' => $field_data,
        ];
        $create = ClientSecurityType::create($create_data);
        return $create;
    }

}
