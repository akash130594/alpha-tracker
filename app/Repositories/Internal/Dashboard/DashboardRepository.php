<?php
/**
 * Created by PhpStorm.
 * User: Sample Junction
 * Date: 12/17/2018
 * Time: 3:47 PM
 */
namespace App\Repositories\Internal\Dashboard;

use App\Models\Client\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;


class DashboardRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Client::class;
    }


    public function getUserDetails($id)
    {
        $data = DB::table('users')
            ->select('*')
            ->where('id','=',$id)
            ->first();

        return $data;
    }

    public function updatePassword($id,$new_password)
    {
      $data = DB::table('users')
      ->where('id','=',$id)
      ->update(['password' => $new_password]);

      return $data;
    }

    public function updateProfile($id, $input)
    {
        $data = DB::table('users')
            ->where('id','=',$id)
            ->update($input);
        return $data;
    }

}
