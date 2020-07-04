<div>
    <div>

        <div>
            <div>
                <label>Midi File</label>
                <input type="file" name="" accept="audio/midi"
                       wire:model="midi">
                       @error('midi')
                           {{ $message }}
                       @enderror
            </div>
            <div>
                <div>
                    @foreach ($colors as $color)
                    <input type="color" value="{{ $color }}">
                    @endforeach
                    
                </div>
                <div>
                    <button type="button" wire:click="addColor">+</button>
                    <button type="button" {{ count($colors) <= 2 ? 'disabled' : '' }} wire:click="subColor"> -</button>
                    <button type="button" wire:click="randomGradient">Random
                        Gradient</button>
                        @error('colors')
                           {{ $message }}
                       @enderror
                </div>
                <div x-data="{fillOpacity: {{ $fillOpacity }}}">
                    <label>Fill Opacity</label>
                    <input type="range" min="10" max="100" step="5" wire:model.debounce="fillOpacity" x-model="fillOpacity">
                    <span x-text="fillOpacity / 100"></span>
                    @error('fillOpacity')
                           {{ $message }}
                       @enderror
                </div>
            </div>
            <button type="button" wire:click="viz">Submit</button>
        </div>
        <div>
            {!! $svg !!}
        </div>
    </div>
</div>
