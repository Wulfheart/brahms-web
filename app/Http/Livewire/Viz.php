<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Viz extends Component
{
    use WithFileUploads;
    public $colors;
    public $fillOpacity = 50;
    public $midi;
    public $svg;
    private $filepath;

    public function mount(){
        $this->randomGradient();

    }

    public function render()
    {
        return view('livewire.viz');
    }

    public function addColor(){
        $this->colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    public function subColor(){
        array_pop($this->colors);
//      
    }

    public function randomGradient(){
        $this->colors = config('gradients')->random()['colors'];
    }

    public function viz(){
        $this->validate([
            'midi' => 'required',
            'colors' => 'min:2|required',
            'fillOpacity' => 'integer|between:10,100|required'
        ]);

        logger($this->filepath);

        $name = Uuid::uuid4()->toString() . '.mid';
        // $path = storage_path('app/' . $name);
        $path = $this->midi->storeAs('midi', $name);
        $this->filepath = config('filesystems.disks.' .config('filesystems.default') . '.root') . '/' . $path;
        $process = new Process([config('tools.brahms'), '-i', $this->filepath, '-c', collect($this->colors)->transform(function ($item, $key) {
            return trim($item);
        })->join(','), '--midi2csv', config('tools.midicsv')
        ]);
        logger($path);
        $process->run();
        
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->svg = $process->getOutput();
    }
}
