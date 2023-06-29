<?php

namespace App\Http\Controllers;

use App\Models\usuario;
use Illuminate\Http\Request;


class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $perPage = intval($request->input('per_page', 10)); // Número de elementos por página, valor por defecto: 10
        $page = intval($request->input('page', 1)); // Página actual, valor por defecto: 1
        $search = $request->input('search'); // Término de búsqueda, opcional

        $query = Usuario::query()
        ->orderBy('usuario.id_usuario', 'asc');

        // Aplicar el filtro de búsqueda si se proporciona
        if ($search) {
            $query->where(function ($query) use ($search)  {
                $query->where('usuario.usuario', 'LIKE', "%$search%")
                    ->orWhere('usuario.nombre_usu', 'LIKE', "%$search%")
                    ->orWhere('usuario.apellido_usu', 'LIKE', "%$search%")
                    ->orWhere('usuario.cedula_usu', 'LIKE', "%$search%")
                    ->orWhere('usuario.email', 'LIKE', "%$search%");
            });
        }
        $total = $query->count();

        $registros = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return response()->json([
            'data' => $registros,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'password' => 'required',
            'nombre_usu' => 'required',
            'apellido_usu' => 'required',
            'cedula_usu' => 'required|unique:usuario',
            'estado_usu' => 'required',
            'email' => 'required|email',
            'celular_usu' => 'required',
            'id_rol' => 'required',
        ]);

        $cedula = $request->input('cedula_usu');

        $existingUsuario = Usuario::where('cedula_usu', $cedula)->first();

        if ($existingUsuario) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un usuario con esa cedula',
            ], 400);
        }

        $usuario = new Usuario;
        $usuario->usuario = $request->input('usuario');
        $usuario->password = $request->input('password');
        $usuario->nombre_usu = $request->input('nombre_usu');
        $usuario->apellido_usu = $request->input('apellido_usu');
        $usuario->cedula_usu = $cedula;
        $usuario->estado_usu = $request->input('estado_usu');
        $usuario->email = $request->input('email');
        $usuario->celular_usu = $request->input('celular_usu');
        $usuario->id_rol = $request->input('id_rol');
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'data' => $usuario
        ], 201);
    }


    

    public function show(Usuario $usuario)
    {
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $usuario
        ], 200);
    }

    public function update(Request $request, Usuario $usuario)
    {
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $request->validate([
            'usuario' => 'required',
            'password' => 'required',
            'nombre_usu' => 'required',
            'apellido_usu' => 'required',
            'cedula_usu' => 'required',
            'estado_usu' => 'required',
            'email' => 'required|email',
            'celular_usu' => 'required',
            'id_rol' => 'required',
        ]);

        $usuario->usuario = $request->input('usuario');
        $usuario->password = $request->input('password');
        $usuario->nombre_usu = $request->input('nombre_usu');
        $usuario->apellido_usu = $request->input('apellido_usu');
        $usuario->cedula_usu = $request->input('cedula_usu');
        $usuario->estado_usu = $request->input('estado_usu');
        $usuario->email = $request->input('email');
        $usuario->celular_usu = $request->input('celular_usu');
        $usuario->id_rol = $request->input('id_rol');
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente',
            'data' => $usuario
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }


        $usuario->estado_usu = 0;
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ], 200);
    }
}
