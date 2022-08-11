<?php

namespace App\Listeners;

use App\Repository\PeriodoRepository;
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
        $cicloVigente = (new PeriodoRepository())->cicloVigente();
        if ($cicloVigente)
            Session::put('periodo', $cicloVigente);
    }
}
