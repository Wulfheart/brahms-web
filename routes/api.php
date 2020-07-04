<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('visualize', function (Request $request) {
    logger("HELLO");
    // // TODO: Validate request visually
    $request->validate([
        'midi' => 'required',
        'colors' => 'min:2|required',
        'fillOpacity' => 'integer|required|between:10,100',
    ]);
    logger($request->all());
    $name = Uuid::uuid4()->toString() . '.mid';
    $path = storage_path('app/' . $name);
    Storage::putFileAs("", $request->midi, $name);
    logger([config('tools.brahms'), '-i', $path, '-c', collect($request->colors)->transform(function ($item, $key) {
        return trim($item);
    })->join(','), '--midi2csv', config('tools.midicsv'), '--fill-opacity', $request->fillOpacity / 100]);
    $process = new Process([config('tools.brahms'), '-i', $path, '-c', collect($request->colors)->transform(function ($item, $key) {
        return trim($item);
    })->join(','), '--midi2csv', config('tools.midicsv'), '--fill-opacity', $request->fillOpacity / 100]);

    $process->run();
    // executes after the command finishes
    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }
    logger($process->getErrorOutput());
    Storage::delete($name);

    return $process->getOutput();
})->name('viz.post');
