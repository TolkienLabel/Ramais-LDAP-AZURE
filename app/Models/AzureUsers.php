<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model as GraphModel;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
class AzureUsers extends Model
{
    /**
     * Função responsável por gerar Token para acesso ao GRAPH no formado aplicação
     * o valor do retorno é string contendo o Access Token gerado. 
     */
    public function GetToken(){
        $guzzle = new \GuzzleHttp\Client();
       
        $tenantId = env("TENANTID");

        // Obtem o ID e chave no arquivo ENV
        $clientId = env("AZURE_AD_CLIENT_ID");
        $clientSecret = env("AZURE_AD_CLIENT_SECRET");


        $url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/token?api-version=1.0';
        $token = json_decode($guzzle->post($url, [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'resource' => 'https://graph.microsoft.com/',
                'scope'=>'user.read.all',
                'grant_type' => 'client_credentials',
            ],
        ])->getBody()->getContents());
        $accessToken = $token->access_token;
        return $accessToken;
    }
    /**
     * Função responsável por listar todos os usuários usando a API do GRAPH
     * retorno é um objeto no formado GraphModel ou JSON
     */
    public function GetUsers(){
        $graph = new Graph();
        $graph->setAccessToken($this->GetToken());

        $users = $graph->createRequest("GET", '/users/?$select=businessPhones,displayName,jobTitle,mail,mobilePhone,officeLocation,userPrincipalName,id,Department,Office')
                      ->setReturnType(GraphModel\User::class)
                      ->execute();

        return $users;
    }
    /**
     * Função responsável por obter a foto de perfil do usuário com base no GID. 
     * retorno é no formado PNG
     * @error - em caso de falha esta função retorna uma foto temporária com as iniciais do usuário.
     */
    public function GetPicture($id){
       
        try{
            $graph = new Graph();
            $graph->setAccessToken($this->GetToken());
            $userpicture = $graph->createRequest("GET", "/users/{$id}/photo/")->execute();
       
            return $userpicture;

        }catch(\Exception $e){
            $graph = new Graph();
            $graph->setAccessToken($this->GetToken());
            $users = $graph->createRequest("GET", "/users/$id/?\$select=displayName")
            ->setReturnType(GraphModel\User::class)
            ->execute();
         
            $avatar = new InitialAvatar();
            $image = $avatar->size(250)->name($users->getDisplayName())->generate();
            return $image->stream('png', 100);
         
        }
       
    }
}
