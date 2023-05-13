<?php

namespace App\Http\Controllers;

use App\Models\usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = usuario::all();
        return response()->json($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'usuario' => 'required|max:150',
            'password' => 'required|max:100',
            'nombre_usu' => 'required|max:150',
            'apellido_usu' => 'required|max:150',
            'cedula_usu' => 'required|max:10',
            'estado_usu' => 'required|boolean',
            'email' => 'required|email|max:150',
            'celular_usu' => 'required|max:10',
            'id_rol' => 'required|integer',
        ]);

        // Crear un nuevo registro en la tabla "usuario"
        $usuario = new Usuario();
        $usuario->usuario = $validatedData['usuario'];
        $usuario->password = bcrypt($request->input('password'));
        $usuario->nombre_usu = $validatedData['nombre_usu'];
        $usuario->apellido_usu = $validatedData['apellido_usu'];
        $usuario->cedula_usu = $validatedData['cedula_usu'];
        $usuario->estado_usu = $validatedData['estado_usu'];
        $usuario->email = $validatedData['email'];
        $usuario->celular_usu = $validatedData['celular_usu'];
        $usuario->id_rol = $validatedData['id_rol'];
        $usuario->save();

        // Devolver una respuesta satisfactoria con el nuevo registro
        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'usuario' => $usuario,
        ], 201);
    }



    public function buscarPorUsuario(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'usuario' => 'required|max:150',
        ]);
    
        // Buscar el usuario en la tabla "usuarios"
        $usuario = Usuario::where('usuario', $validatedData['usuario'])->first();
    
        // Devolver una respuesta con el usuario encontrado
        if ($usuario) {
            return response()->json([
                'usuario' => $usuario,
            ]);
        } else {
            return response()->json([
                'message' => 'No se encontró ningún usuario con ese nombre de usuario',
            ], 404);
        }
    }



    public function actualizarUsuario(Request $request, $usuario)
    {
        $usuarioDB = Usuario::where('usuario', $usuario)->first();

        if (!$usuarioDB) {
            return response()->json(['mensaje' => 'nose encontro usuario']);
        }
        $usuarioDB->password = bcrypt($request->input('password'));
        $usuarioDB->nombre_usu = $request->input('nombre_usu');
        $usuarioDB->apellido_usu = $request->input('apellido_usu');
        $usuarioDB->cedula_usu = $request->input('cedula_usu');
        $usuarioDB->estado_usu = $request->input('estado_usu');
        $usuarioDB->email = $request->input('email');
        $usuarioDB->celular_usu = $request->input('celular_usu');
        $usuarioDB->id_rol = $request->input('id_rol');
        $usuarioDB->updated_at = now();

        $usuarioDB->save();

        return response()->json(['mensaje' => 'Usuario actualizado con éxito']);
    }

       
    
    public function destroy($usuario)
    {
        $usuarioAEliminar = Usuario::where('usuario', $usuario)->first();

        if (!$usuarioAEliminar) {
            return response()->json('El usuario no se encontró en la base de datos', 404);
        }

        try {
            $usuarioAEliminar->delete();
            return response()->json('Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            return response()->json('No se pudo eliminar el usuario', 500);
        }
    }

}
