<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Mail\InviteUser;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * @param UserRepository $userRepository
     * @param Request $request
     * @return mixed
     */
    public function index(UserRepository $userRepository, Request $request)
    {
        $page = $request->query('page');
        return $userRepository->get(Session::get('campaign_id'), $page['number'], $page['size']);
    }

    public function store(UserRepository $userRepository, Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:191',
            'name' => 'required|string|max:191',
            'password' => 'required|string|min:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]/',
        ]);
    }

    public function invite(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:191',
        ]);

        $mail = new InviteUser();
    }
}