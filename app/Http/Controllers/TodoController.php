<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Http::get('https://arfkcpx7m7.execute-api.us-east-1.amazonaws.com/dev/todos');
        return $response; // we could Definitely have a message being returned here... oh well...
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Http::post('https://arfkcpx7m7.execute-api.us-east-1.amazonaws.com/dev/todos', [
            'text' => $request->text,
        ]);
        if ($response->successful()) {
            return $response; // we could Definitely have a message being returned here... oh well...
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $text = $request->text;
        $checked = filter_var($request->checked, FILTER_VALIDATE_BOOLEAN);
        $response = Http::put('https://arfkcpx7m7.execute-api.us-east-1.amazonaws.com/dev/todos/' . $id, [
            'text' => $text,
            'checked' => $checked,
        ]);
        if ($response->successful()) {
            return $response;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $response = Http::delete('https://arfkcpx7m7.execute-api.us-east-1.amazonaws.com/dev/todos/' . $id);
        if ($response->status() == 200) {
            return true;
        }
        return false;
    }
}
