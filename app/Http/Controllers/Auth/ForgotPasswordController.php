<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


//sendResetLinkEmail


//    /**
//     * Send a reset link to the given user.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
//     */
//    public function sendResetLinkEmail(Request $request)
//    {
//        $this->validateEmail($request);
//
//        // We will send the password reset link to this user. Once we have attempted
//        // to send the link, we will examine the response then see the message we
//        // need to show to the user. Finally, we'll send out a proper response.
//        $response = $this->broker()->sendResetLink(
//            $request->only('email')
//        );
//
//        //passwords.sent
//
//        return $response == Password::RESET_LINK_SENT
//            ? $this->sendResetLinkResponse($response)
//            : $this->sendResetLinkFailedResponse($request, $response);
//    }

}
