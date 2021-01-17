<?php
/**
 * Created by PhpStorm.
 * User: SampleJunction
 * Date: 05-12-2018
 * Time: 11:11 PM
 */

namespace App\Repositories\Internal\Source;

use App\Models\Client\Client;
use App\Models\Source\Source;
use App\Models\Source\SourceType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;


class SourceRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Client::class;
    }
    public function createSource($details, $code)
    {
       $create_source = Source::insert($details);
       return $create_source;
    }

    public function findId($id)
    {
        $find_data = DB::table('sources')
            ->where('id','=',$id)
            ->select('*')
            ->first();
        return $find_data;
    }

    public function updateSource($id, $input)
    {
        $data = DB::table('sources')
            ->where('id','=',$id)
            ->update($input);
        return $data;
    }

    public function deleteSource($id)
    {
        $data = DB::table('sources')
            ->where('id','=',$id)
            ->delete();
        return $data;
    }

    public function sourceType()
    {
        $data = DB::table('source_types')
            ->select('*')->pluck('name','id')->toArray();

        return $data;
    }

    public function getInternalSource()
    {
        $data = Source::where('code', '1111')
            ->first();
        return $data;
    }

    public function getSourceData($source_id)
    {
        $source = Source::where('id', '=', $source_id)->first();
        return $source;
    }


}
