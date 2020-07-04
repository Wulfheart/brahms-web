<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Ramsey\Uuid\Uuid;

class Viz extends Component
{
    use WithFileUploads;
    public $colors;
    public $fillOpacity = 50;
    public $midi;

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


        $name = Uuid::uuid4()->toString() . '.mid';
        // $path = storage_path('app/' . $name);
        logger($this->midi->temporaryUrl());
        $this->midi->storeAs('midi', $name);
        dd($this->midi);
    }
}
