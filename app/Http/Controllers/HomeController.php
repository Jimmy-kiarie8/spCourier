<?php

namespace App\Http\Controllers;

use App\FileManagement\Props;
use App\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class HomeController extends Controller
{
    public function courier()
    {
        // $companies = Company::where('id', Auth::user()->company_id)->get();
        // foreach ($companies as $company) {
        //     $company_logo = $company->logo;
        // }
        $permissions = [];
        foreach (Permission::all() as $permission) {
            if (Auth::user()->can($permission->name)) {
                $permissions[$permission->name] = true;
            } else {
                $permissions[$permission->name] = false;
            }
        }
        $user = Auth::user();
        // $users->transform(function ($user, $key) {
        //     $country = Country::find($user->country_id);
        //     $user->country_name = $country->name;
        //     return $user;
        // });
        // dd(json_decode(json_encode((Auth::user()), false)));
        $auth_user = array_prepend($user->toArray(), $permissions, 'can');
        return view('welcome', compact('rolename', 'auth_user'));
    }
    public function courierHome()
    {
        return redirect('/')->where('name', '[A-Za-z]+');
    }
    /**
     * Load homepage
     *
     * @param  Request $request
     * @return view
     */
    public function index(Request $request)
    {
        $props = Props::get();
        // return redirect('/login');
        if (Auth::check()) {
            return redirect('/courier');
        }
    }

    /**
     * Delete an attachment
     *
     * @param  Request $request
     * @return Response
     */
    public function deleteAttachment(Request $request)
    {
        try {

            if (!Auth::check()) {
                throw new Exception("User has to be logged in", 1);
            }

            $attachment_id = $request->input('attachment_id');

            if (!$attachment_id) {
                throw new Exception("You are trying to delete a non-existing file", 1);
            }

            $attachment = $this->attachmentRepo->delete(intval($attachment_id));

            return response()->json(array(
                'success' => true,
                'data' => $attachment,
                'errors' => []
            ), 200);

        } catch (\Exception $e) {

            return response()->json(array(
                'success' => false,
                'data' => 'Server error happened',
                'errors' => $e->getMessage(),
            ), 200);

        }
    }
}
