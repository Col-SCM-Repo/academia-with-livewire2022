<?php

namespace App\Http\Livewire\Mantenimiento\Usuarios;

use App\Repository\EntityRepository;
use App\Repository\UsersRepository;
use Livewire\Component;

class Usuarios extends Component
{
    public  $listaUsuarios;

    public  $usuarioId;
    public  $username,              // username
            $tipo,                  // tipo
            $father_lastname ,      // father_lastname
            $mother_lastname ,      // mother_lastname
            $name ,                 // name
            $address ,              // address
            $distrito ,             // distrito
            $telephone ,            // telephone
            $mobile_phone ,         // mobile_phone
            $email ,                // email
            $birth_date ,           // birth_date
	        $gender ,               // gender
            $country_id ,           // country_id
            $document_type ,        // document_type
            $document_number ,      // document_number
            $marital_status ,       // marital_status
            $instruction_degree ,   // instruction_degree
            $photo_path ;           // photo_path

    private $_usuariosRepository, $_entidadRepository;

    public function __construct()
    {
        $this->_usuariosRepository = new UsersRepository();
        $this->_entidadRepository = new EntityRepository();
    }

    public function render()
    {
        $this->listaUsuarios = $this->_usuariosRepository->getListaUsuarios();
        return view('livewire.mantenimiento.usuarios.usuarios');
    }

    public function create(){}

    public function update(){}

    public function btnOpenModalNuevoUsuario(){

    }

    public function btnOpenModalEditUsuario(){

    }

}
