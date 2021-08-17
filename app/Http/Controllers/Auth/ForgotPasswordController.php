<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
/*
 * форма ввода адреса почты
 */
    public function form(){
        return view('auth.forgot');
    }
    /*
     * Письмо на почту для восстонавлнеия
     * */
    public function mail(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $token = Str::random(60);
        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );
        //Ссылка для зброса пароля
        $link = route('auth.reset-form',['token'=>$token,'email'=>$request->email]);
        Mail::send(
            'email.reset-password',
            ['link'=>$link],
            function ($message) use ($request){
                $message->to($request->email);
                $message->subject('Восстановление пароля ');
            }
        );
        return back()->with('success','Ссылка для восставновления пароля отправлена на почту ');
    }
}
