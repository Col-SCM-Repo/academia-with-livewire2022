<?php

namespace App\Listeners;

use App\Repository\PeriodoRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class InicioSesionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Log::debug("INICIO SESION");
        $cicloVigente = (new PeriodoRepository())->cicloVigente();
        if($cicloVigente)
            Session::put('periodo', $cicloVigente);
        //Log::debug(Session::get('periodo'));
        
    }
}
