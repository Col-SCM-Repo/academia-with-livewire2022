<?php

namespace App\Http\Livewire\Mantenimiento\Usuarios;

use App\Enums\EstadosEntidadEnum;
use App\Repository\EntityRepository;
use App\Repository\UsersRepository;
use Exception;
use Livewire\Component;

class Usuarios extends Component
{
    public  $listaUsuarios;

    public  $usuarioId, $entidadId;
    public  $apellido_paterno, $apellido_materno, $nombre, $direccion, $distrito,
            $celular, $email, $fecha_nacimiento, $genero, $numero_documento,
            $estado_marital, $grado_de_instruccion;
    public  $usuario, $tipo_usuario, $estado, $contrasenia, $contrasenia_confirmacion;

    private  $rulesDatosPersonales = [
        'apellido_paterno' => 'required|string|min:4',
        'apellido_materno' => 'required|string|min:4',
        'nombre' => 'required|string|min:4',
        'direccion' => 'required|string|min:4',
        'distrito' => 'required|string|min:4',
        'celular' => 'required|string|min:8',
        'email' => 'required|string|email',
        'fecha_nacimiento' => 'required|date',
        'genero' => 'required|string|in:male,female',
        'numero_documento' => 'required|string|min:8',
        'estado_marital' => 'required|string|in: single,married,divorcied,widower',
        'grado_de_instruccion' => 'required|string|in:none,elementary_school,high_school,universitary_education',
    ];

    private  $rulesUsuario = [
        'usuario' => 'required|min:8',
        'tipo_usuario' => 'required|in:admin,secretary',
        'estado' => 'required|integer|in:0,1',
        'contrasenia' => 'required|string:8',
        'contrasenia_confirmacion' => 'required|string:8',
    ];

    private $_usuariosRepository, $_entidadRepository;

    public function __construct()
    {
        $this->_usuariosRepository = new UsersRepository();
        $this->_entidadRepository = new EntityRepository();
    }

    public function initialState(){
        $this->reset([  'usuarioId', 'entidadId']);

        $this->reset([  'apellido_paterno','apellido_materno','nombre','direccion','distrito',
                        'celular','email','fecha_nacimiento','genero','numero_documento',
                        'estado_marital','grado_de_instruccion']);

        $this->reset([  'usuario', 'tipo_usuario', 'estado', 'contrasenia', 'contrasenia_confirmacion']);

    }

    public function render()
    {
        $this->listaUsuarios = $this->_usuariosRepository->getListaUsuarios();
        return view('livewire.mantenimiento.usuarios.usuarios');
    }

    public function registrarDatosPersonales(){
        $this->validate( $this->rulesDatosPersonales );
        try {
            $moEntity = self::builEntityModel();
            $this->entidadId =$this->_entidadRepository->registrar($moEntity)->id;
            sweetAlert($this, 'datos personales', EstadosEntidadEnum::CREATED);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function actualizarDatosPersonales(){
        $this->validate(['entidadId'=>'required|integer|min:1', [...$this->rulesDatosPersonales]]);
        try {
            $moEntity = self::builEntityModel();
            $this->_entidadRepository->actualizar($this->entidadId, $moEntity);
            sweetAlert($this, 'datos personales', EstadosEntidadEnum::UPDATED);
        } catch (Exception $err) {
            toastAlert($this, $err->getMessage());
        }
    }

    public function registrarUsuario(){
        $this->validate( $this->rulesUsuario );

    }

    public function actualizarUsuario(){
        $this->validate(['usuarioId'=>'required|integer|min:1', [...$this->rulesUsuario]]);

    }


    public function btnOpenModalNuevoUsuario(){
        self::initialState();
        openModal($this, '#form-modal-usuarios');
    }

    public function btnOpenModalEditUsuario( $usuario_id ){
        $this->usuarioId = $usuario_id;
        // Obtener usuario y entidad usuario

        openModal($this, '#form-modal-usuarios');

    }





    private function builEntityModel(){
        $modelEntity = $this->_entidadRepository->builderModelRepository();
        $modelEntity->apellido_paterno = $this->apellido_paterno;
        $modelEntity->apellido_materno = $this->apellido_materno;
        $modelEntity->nombres = $this->nombre;
        $modelEntity->direccion = $this->direccion;
        $modelEntity->distrito = $this->distrito;
        $modelEntity->telefono = $this->celular;
        $modelEntity->celular = $this->celular;
        $modelEntity->email = $this->email;
        $modelEntity->fecha_nacimiento = $this->fecha_nacimiento;
        $modelEntity->sexo = $this->genero;
        $modelEntity->pais_id = null ;
        $modelEntity->tipo_documento = 'dni' ;
        $modelEntity->dni = $this->numero_documento;
        $modelEntity->estado_marital = $this->estado_marital;
        $modelEntity->grado_de_instruccion = $this->grado_de_instruccion;
        return $modelEntity;
    }

}
