<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
class RegisterController extends Controller
{
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Форма регистрации
     */
    public function register() {
        return view('auth.register');
    }

    /**
     * Добавление пользователя
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //Ссылка для проверки адреса почты
        $token = md5($user->email . $user->name);
        $link = route('auth.verify-email',['token'=>$token,'id'=>$user->id]);
        Mail::send(
            'email.verify-email',
            ['link'=>$link],
            function ($message) use ($request){
                $message->to($request->email);
                $message->subject('Подтвреждение адреса почты');
            }
        );
        //необходимо подтвердить адрес почты
        return redirect()->route('auth.verify-message');

    }
}
