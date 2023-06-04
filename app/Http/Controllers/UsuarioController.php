<?php

namespace App\Http\Controllers;

use App\Models\usuario;
use Illuminate\Http\Request;


class UsuarioController extends Controller
{
   

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Número de registros por página (por defecto: 10)
        $search = $request->input('search'); // Término de búsqueda específico
        $page = $request->input('page', 1); // Página actual (por defecto: 1)

        $query = Usuario::query();

        if ($search) {
            $query->where('nombre_usu', 'LIKE', '%' . $search . '%')
                ->orWhere('apellido_usu', 'LIKE', '%' . $search . '%')
                ->orWhere('cedula_usu', 'LIKE', '%' . $search . '%')
                ->orWhere('id_rol', 'LIKE', '%' . $search . '%')
                ->orWhere('usuario', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%');
        }

        $usuarios = $query->paginate($perPage, ['*'], 'page', $page);

        $responseData = $usuarios->items();
        $response = [
            'data' => $responseData,
            'current_page' => $usuarios->currentPage(),
            'per_page' => $usuarios->perPage(),
            'total' => $usuarios->total(),
        ];

        return response()->json($response, 200);
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

        $usuario->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ], 200);
    }
}
