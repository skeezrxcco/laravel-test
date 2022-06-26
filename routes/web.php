<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/all-tasks/', [TodoController::class, 'index']);
Route::post('/new-task/', [TodoController::class, 'store']);
Route::put('/update-task/', [TodoController::class, 'update']);
Route::delete('/delete-task/', [TodoController::class, 'destroy']);


Route::get('/test', function () {
    // Delete All TODOS
    $response = Http::get('https://arfkcpx7m7.execute-api.us-east-1.amazonaws.com/dev/todos');
    $aResponse = json_decode($response->body());
    foreach ($aResponse as $value) {
        $response = Http::delete('https://arfkcpx7m7.execute-api.us-east-1.amazonaws.com/dev/todos/' . $value->id);
    }
    $response = Http::get('https://arfkcpx7m7.execute-api.us-east-1.amazonaws.com/dev/todos');
    dd(json_decode($response->body()));
    return;
});

Route::get('/update', function () {
    // Delete All TODOS
    $toto = "8e271e20-f5a5-11ec-b20c-8d4643443b75";
    $response = Http::put('https://arfkcpx7m7.execute-api.us-east-1.amazonaws.com/dev/todos/'.$toto,[
        'text' =>"toto",
        'checked' => true,
    ]);

    $response = Http::get('https://arfkcpx7m7.execute-api.us-east-1.amazonaws.com/dev/todos');
    dd(json_decode($response->body()));
    return;
});


