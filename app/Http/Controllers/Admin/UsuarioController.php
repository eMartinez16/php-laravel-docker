<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Traits\LogsTrait;

class UsuarioController extends Controller
{
    use PasswordValidationRules, LogsTrait;

    private $roles = [
        'colaborador' => 'Colaborador',
        'administrador' => 'Administrador' 
    ];

    private $estados = [
        'activo' => 'Activo',
        'no_activo' => 'No Activo'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->estados == 'todos') {
            $request->estados = null;
        }

        $users = User::when($request->search && $request->estados, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query
                ->orWhere('estado', $request->estados)
                ->orWhere('id', $request['search'])
                ->orWhere('name', 'LIKE', '%'.$request['search'].'%' )
                ->orWhere('apellido', 'LIKE', '%'.$request['search'].'%' )
                ->orWhere('email', 'LIKE', '%'.$request['search'].'%' )
                ->orWhere('dni', 'LIKE', '%'.$request['search'].'%' )
                ->orWhere('telefono', 'LIKE', '%'.$request['search'].'%' );
            });
        })->when($request->estados, function ($query) use ($request) {
            return $query->where('estado', $request->estados);
        })->when($request->search, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query
                ->orWhere('id', $request['search'])
                ->orWhere('name', 'LIKE', '%'.$request['search'].'%' )
                ->orWhere('apellido', 'LIKE', '%'.$request['search'].'%' )
                ->orWhere('email', 'LIKE', '%'.$request['search'].'%' )
                ->orWhere('dni', 'LIKE', '%'.$request['search'].'%' )
                ->orWhere('telefono', 'LIKE', '%'.$request['search'].'%' );
            });
        })->paginate(15);

        $estados_var = [
            'todos' => 'Todos',
            'activo' => 'Activo',
            'no_activo' => 'No Activo'
        ];

        $search = $request->search ?? null;
        $est = $request->estados ?? null;

        return view('admin.usuario.index', compact('users', 'estados_var', 'search', 'est'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = $this->roles;

        return view('admin.usuario.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $validated_data = $this->validarCreateRequest($request);
        if ($validated_data->fails()) {
            foreach ($validated_data->errors()->all() as $error) {
                notify()->error($error,"Error: ","topRight");
            }
            return redirect()->route('admin.usuario.create');
        }

    
        $user = User::create($validated_data->validated());

        $user->password = Hash::make($request->password);
        $user->save();

        $this->creates('Usuarios', User::latest('id')->first()->id, 'Usuario creado con éxito');
        notify()->success("El usuario se guardó correctamente","Éxito: ","topRight");
        return redirect()->route('admin.usuario.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $roles = $this->roles;
        $estados = $this->estados;
        return view('admin.usuario.show', compact('user', 'roles', 'estados'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = $this->roles;
        return view('admin.usuario.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);    
        $passTMP = $user->password;

        if(password_get_info($request->password)['algoName'] == 'unknown') {
          $rules = [
              'name' => ['required', 'string', 'max:255'],
              'apellido' => ['required', 'string', 'max:50'],
              'dni' => ['required', 'string', 'max:20'],
              'telefono' => ['nullable', 'string', 'max:40'],
              'role' => ['required', 'string', 'max:30', Rule::in(['administrador', 'colaborador'])],
          ];
          $input = $request->only(['name', 'apellido', 'email', 'dni', 'telefono', 'role']);
        } else {
          $rules = [
              'name' => ['required', 'string', 'max:255'],
              'apellido' => ['required', 'string', 'max:50'],
              'dni' => ['required', 'string', 'max:20'],
              'telefono' => ['nullable', 'string', 'max:40'],
              'role' => ['required', 'string', 'max:30', Rule::in(['administrador', 'colaborador'])],
          ];
          $input = $request->only(['name', 'apellido', 'email', 'dni', 'telefono', 'role']);          
        }

        if ($request->email === $user->email) {
            //* Except retira el valor indicado pero devuelve un array, yo necesitaba la objeto sin ese value
            // $request->except('email'); 
            $request->offsetUnset('email');
            unset($input['email']);
        } else {
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique(User::class)];
        }

        $validated_data = Validator::make($input, $rules, $messages = [
            'required' => 'El campo :attribute es requerido.',
            'string' => 'El campo :attribute debe ser de tipo string.',
            'email' => 'El campo :attribute debe ser un email válido',
        ]);

        if ($validated_data->fails()) {
            foreach ($validated_data->errors()->all() as $error) {
                notify()->error($error,"Error: ","topRight");
            }
            return redirect()->route('admin.usuario.edit', $id);
        }

        $user->update($validated_data->validated());

        $this->updates('Usuarios', $user->id, 'Usuario actualizado con éxito');
        notify()->success("El usuario se editó correctamente","Éxito: ","topRight");
        return redirect()->route('admin.usuario.index');

    }

    public function usersChangePassword(Request $request){
        $valid = Validator::make($request->all(), [
            'password' => $this->passwordRules()
        ]);  
        
        if ($valid->fails()) {
          return back()->with('error',$valid->errors()->first());      
        }
  
        $user = User::withTrashed()->find($request->id);
        $user->password = Hash::make($request->password);
        
        if ($user->save()){
          $msj = "Se cambio la contraseña con éxito";
          notify()->success($msj,"Éxito: ","topRight");
        } else {
          notify()->error("Hubo un error al cambiar las contraseñas. Por favor, inténtalo nuevamente","Error: ","topRight");
          return redirect()->route('admin.usuario.edit', $request->id);
        }

        return redirect()->route('admin.usuario.index');
      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!User::destroy($id)){
            notify()->error("Hubo un error al eliminar el usuario. Por favor, inténtalo nuevamente.","Error: ","topRight");
            return redirect()->route('admin.usuario.index');
        }
        $this->deletes('Usuarios', $id, 'Usuario eliminado con éxito');
        notify()->success("El usuario se eliminó correctamente","Éxito: ","topRight");
        return redirect()->route('admin.usuario.index');
    }

    /**
     * Validar data de la request
     *
     * @param Request $request
     * @param int $client_id
     * @return validation errors if there they exist.
     */
    private function validarCreateRequest(Request $request)
    {
        //* Esta es otra forma de validar
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'dni' => ['required', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:40'],
            'role' => ['required', 'string', 'max:30', Rule::in(['administrador', 'colaborador'])],
            'password' => ['required'],
        ];

        $input = $request->only(['name', 'apellido', 'email', 'dni', 'telefono', 'role', 'password']);
        return $validator = Validator::make($input, $rules, $messages = [
            'required' => 'El campo :attribute es requerido.',
            'string' => 'El campo :attribute debe ser de tipo string.',
            'email' => 'El campo :attribute debe ser un email válido',
        ]);
    }


    /**
     * Función para activar un usuario
     * @param  int  $id
    */
    public function activar($id) 
    {
        if (auth()->user()->role !== 'administrador') {
            notify()->error("No tienes los permisos suficientes apra realizar esta acción.","Error: ","topRight"); // TODO: transformarlo en policy
            return redirect($this->referer());
        }

        if (!User::findOrFail($id)->update(['estado' => 'activo'])) {
            notify()->error("Hubo un error al activar el Usuario","Error: ","topRight");
            return redirect()->route('admin.usuario.index');
        }

        $this->general('Usuarios', $id, 'Usuario activado', 'Activar');
        notify()->success("El usuario se activó correctamente","Éxito: ","topRight");
        return redirect()->route('admin.usuario.index');
    }

    /**
     * Función para desactivar un usuario
     * @param  int  $id
    */
    public function desactivar($id) 
    {
        if (auth()->user()->role !== 'administrador') {
            notify()->error("No tienes los permisos suficientes apra realizar esta acción.","Error: ","topRight"); // TODO: transformarlo en policy
            return redirect($this->referer());
        }

        if (!User::findOrFail($id)->update(['estado' => 'no_activo'])) {
            notify()->error("Hubo un error al desactivar el Usuario","Error: ","topRight");
            return redirect()->route('admin.usuario.index');
        }

        $this->general('Usuarios', $id, 'Usuario desactivado', 'Desactivar');
        notify()->success("El usuario se desactivó correctamente","Éxito: ","topRight");
        return redirect()->route('admin.usuario.index');
    }
}
