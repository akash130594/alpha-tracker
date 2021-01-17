<?php

namespace App\Http\Controllers\Web\Internal\Source;

use App\Http\Requests\Internal\Source\CreateRequest;
use App\Http\Requests\Internal\Source\UpdateRequest;
use App\Models\Source\Source;
use App\Models\Source\SourceType;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Internal\Source\SourceRepository;
use Illuminate\Support\Facades\Redirect;

class SourceController extends Controller
{

    /**
     * SourceController constructor.
     */
    public $source_repo;
    public function __construct(SourceRepository $sourceRepo)
    {
        $this->source_repo = $sourceRepo;
        //$this->detailedProfileRepo = $detailedProfileRepo;
    }


    /**
     * @author Pankaj Kumar Jha
     *
     * @return mixed
     */
    public function index()
    {
        return View('internal.source.index')
            //->with('clients', Client::limit(5))
            ;
    }

    public function datatable()
    {
        return Laratables::recordsOf(Source::class);
    }

    public function editSource(Request $request, $id)
    {

        $source = $this->source_repo->findId($id);
       /* $sourceTypes = SourceType::pluck('name', 'id')->toArray();*/
        $sourceTypes = $this->source_repo->sourceType();

        return View('internal.source.edit')
            ->with('source', $source)
            ->with('sourceTypes', $sourceTypes);
}
    public function updateSource(UpdateRequest $request, $id)
    {
        $input = $request->except('_token', '_method');
        $status = $this->source_repo->updateSource($id,$input);

        if($status){
            return redirect()->back()
                ->withFlashSuccess('Source Updated');
        }else{
            return redirect()->back()
                ->withFlashError('Some Error Occured while updating');
        }
    }
    public function createSource(Request $request)
    {
        $source_type = $this->source_repo->sourceType();
        return view('internal.source.create')
            ->with('source_type', $source_type);
    }
    public function postCreateSource(CreateRequest $request)
    {
        $data = $request->except(['_token']);
        $code = $request->input('code',false);
        $create_source = $this->source_repo->createSource($data, $code);
        if($create_source) {
            return redirect()->route('internal.source.index')
                ->withFlashSuccess("Source Successfully created");
        }else{
            return redirect()->back()
                ->withFlashError('Some Error Occured while creating');
        }
    }
    public function deleteSource(Request $request, $id)
    {
        $delete_client = $this->source_repo->deleteSource($id);
        return redirect()->route('internal.source.index')
            ->withFlashSuccess("source has been deleted");
    }

    public function showLink(Request $request)
    {
        $source_id = $request->input('source_id');
        $source_data = $this->source_repo->getSourceData($source_id);
        return view('internal.source.links')
            ->with('source', $source_data);
    }

}
