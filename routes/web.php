<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Redirecionamento padrão para OAUTH2 da Microsoft
Route::get("/login", function(){return redirect("/login/microsoft");})->name("login");

// Acesso a raiz passando por middleware de autenticação 
Route::get('/', 'RamaisController@index')->name('home')->middleware("auth"); 

// Acesso a raiz passando por middleware de autenticação 
Route::get('/home', 'RamaisController@index')->name('home')->middleware("auth");

// API que retorna JSON com todos os usuários passando por middleware de autenticação
Route::get('/GetUsersAPI', "RamaisController@GetUsersAPI")->name('api')->middleware("auth");

// API que retorna PNG com a foto do usuário passando por middleware de autenticação
Route::Get('GetUsersPhotoAPI/{id}', "RamaisController@GetUsersPhotoAPI")->name('api_foto')->middleware("auth");
