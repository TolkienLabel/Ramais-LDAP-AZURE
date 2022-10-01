<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Metrogistics\AzureSocialite\AzureOauthProvider;
use App\Models\AzureUsers;

class RamaisController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Função reponsável por fazer a solicitação dos usuários para a MODEL e enviar para a VIEW Blade/Vue.js
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = new AzureUsers();
        $userslist = $users->GetUsers();
        return view('home', compact('userslist'));
    }
    /**
     * Função usada para enventuais chamadas de API para /GetUsersAPI
     * @return JSON com todos os usuários (sem imagem)
     */
    public function GetUsersAPI(){
        $users = new AzureUsers();
        return $users->GetUsers();

    }
    /**
     * Função usada para enviar foto do perfil do usuário para o front-end baseado no GID
     * @return PNG 
     */
    public function GetUsersPhotoAPI($id){
       $user = new AzureUsers();
       return $user->GetPicture($id);
    }

}
