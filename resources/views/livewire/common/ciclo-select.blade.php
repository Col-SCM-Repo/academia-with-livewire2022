<div >
    <div style="display: inline-block; ">
        <label for="periodo_select"> Ciclo: </label>
        <select class="form-select" id="periodo_select" style="display: inline" wire:model='periodoId' >
            @foreach ($periodos as $periodo)
                <option value="{{ $periodo->id }}"> {{$periodo->name}} </option>
            @endforeach
        </select>
    </div>
</div>
