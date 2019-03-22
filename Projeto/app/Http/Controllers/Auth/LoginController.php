<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;

// use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('adminlte::auth.login');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

     /**
     * Rota que faz a requisição dos dados de usuário do Twitter;
     *
     * @return \Illuminate\Http\Response
     */
    public function twitterRedirectToProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * Obtem informações de usuário do Twitter e salva no banco de dados.
     *
     * @return \Illuminate\Http\Response
     */

    //Rota que receve a resopsta do Twitter
    //Trata os dados
    //Faz Login e Redireciona
    public function twitterHandleProviderCallback()
    {
        //Pega o usuário da resposta do Twitter.
        $user = Socialite::driver('twitter')->user();
        
//Pega a lista de usuários cadastrados no banco de dados com o mesmo e-mail do usuário recebido.
        $userCadastrado = User::where('email', $user->getEmail())->get();

        //Verifica se já há usuários cadastrados com esse e-mail.
        if($userCadastrado->count() === 0){
            //Se não houver, salva no banco de dados e faz o login.
            //Para o exemplo estamos salvando a senha como o token retornado
            //pois a senha não é retornada pelo Twitter
            $data = [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'rede_social' => 'Twitter',
                'token_rede' => $user->token,
                'password' => $user->token,
            ];
        
            //Realiza o login e salva o usuário no banco de dados
            Auth::login(User::firstOrCreate($data));
        }else{
            //Se o usuário já estviver cadastrado

            //Atualiza o Token do usuário
            $userCadastrado[0]->token_rede =  $user->token;
            $userCadastrado[0]->save();

            //Realiza o login
            Auth::login($userCadastrado[0]);
        }

        //Redireciona para a página desejada
        return redirect($this->redirectPath());
    }
}
