<?php

namespace App\Http\Controllers\Web\Internal\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Internal\Dashboard\DashboardRepository;
use App\Http\Requests\Internal\User\PasswordRequest;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public $profile_repo;
    public function __construct(DashboardRepository $dashRepo)
    {
        $this->profile_repo= $dashRepo;
    }

    public function getProfileDetails(Request $request)
    {
        $user = $request->user()->id;
        $get_user_details = $this->profile_repo->getUserDetails($user);
        return view('internal.user.profile')
            ->with('user_details', $get_user_details);
    }

    public function updateProfile(Request $request)
    {
        $user_id = $request->id;
        $get_user_details = $this->profile_repo->getUserDetails($user_id);
         return view('internal.user.update')
             ->with('user_info', $get_user_details);
    }

    public function postUpdateProfile(Request $request)
    {
        $id = $request->id;
        $input = $request->except(['_token']);
        $update = $this->profile_repo->updateProfile($id, $input);
        if($update){
            return Redirect::back()
                ->withFlashSuccess('Profile Updated');
        }
    }

    public function changePassword()
    {
        return view('internal.user.password');
    }

    public function editPassword(PasswordRequest $request, $expired = false)
    {
        $input = $request->except(['_token']);
        $id = $request->id;
        $get_user_details = $this->profile_repo->getUserDetails($id);
        $current_pass = $request->input('current_password', false);
        $new_password = Hash::make($request->input('new_password', false));

        if (Hash::check($current_pass, $get_user_details->password)) {
            if ($expired) {
                $request->user()->password_changed_at = Carbon::now()->toDateTimeString();
            }
            $update_pass = $this->profile_repo->updatePassword($id, $new_password);
            return Redirect::back()
                ->withFlashSuccess('Password Updated');
        } else{
            return Redirect::back()
                ->withFlashSuccess('Password not Updated');
        }
    }

    public function setting()
    {
        return redirect()->route('internal.dashboard');
    }

}
