<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Operario;
use App\Models\User;
use App\Traits\ImageTrait;

class OperarioController extends Controller
{
    use ImageTrait;
    /**
     * Format a Operario model
     */
    private function format(Operario $operario)
    {
        return [
            'nombre_completo'=>$operario->nombre_completo,
            'ci'=>$operario->ci,
            'telefono'=>$operario->telefono,
            'direccion'=>$operario->direccion,
            'rol'=>$operario->rol,
            'cargo'=>$operario->cargo,
            'fecha_inicio'=>$operario->fecha_inicio,
            'fecha_fin'=>$operario->fecha_fin,
            'src_foto'=>$operario->src_foto,
            'foto'=>$operario->foto,
            'foto_thumbnail'=>$operario->foto_thumbnail,
            'foto_thumbnail_sm'=>$operario->foto_thumbnail_sm,
            'user'=>$operario->user,
        ];
    }

    /**
     * Register a Operario
     * 
     * @group Operario
     * @authenticated
     * 
     * @bodyParam nombre_completo string requried
     * @bodyParam ci string requried
     * @bodyParam telefono string requried
     * @bodyParam direccion string
     * @bodyParam rol string
     * @bodyParam cargo string
     * @bodyParam fecha_inicio string
     * @bodyParam fecha_fin string
     * @bodyParam foto string
     * @bodyParam email email
     * @bodyParam password string
     * @response 
     */
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'nombre_completo' => ['required'],
                'ci' => ['required'],
                'telefono' => ['required'],
                'direccion' => ['nullable'],
                'rol' => ['required'],
                'cargo' => ['required'],
                'fecha_inicio' => ['nullable'],
                'fecha_fin' => ['nullable'],
                'src_foto' => ['nullable'],
                'email' => 'required|email|unique:users|max:50',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                http_response_code(422);
                throw new \Exception($validator->errors()->first());
            }

            $operario = new Operario();
            $operario->nombre_completo = $request->nombre_completo;
            $operario->ci = $request->ci;
            $operario->telefono = $request->telefono;
            $operario->direccion = $request->direccion;
            $operario->rol = $request->rol;
            $operario->cargo = $request->cargo;
            $operario->fecha_inicio = $request->fecha_inicio;
            $operario->fecha_fin = $request->fecha_fin;
            $operario->src_foto = $request->src_foto;
            $operario->save();

            $image = $request->foto;
            if ($image) {
                $this->saveImage($image, $operario, 'operario');
            }

            $user = new User();
            $user->name = $operario->nombre_completo;
            $user->email = $request->email;
            $user->operario_id = $operario->id;
            $user->password = bcrypt($request->password);
            $user->save();

            DB::commit();
            return response()->json($this->format($operario), 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update a Operario
     * 
     * @group Operario
     * @authenticated
     * 
     * @bodyParam id integer requried
     * @bodyParam nombre_completo string requried
     * @bodyParam ci string requried
     * @bodyParam telefono string requried
     * @bodyParam direccion string
     * @bodyParam rol string
     * @bodyParam cargo string
     * @bodyParam fecha_inicio string
     * @bodyParam fecha_fin string
     * @bodyParam src_foto string
     * @bodyParam email email
     * @bodyParam password string
     * @response 
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'nombre_completo' => ['required'],
                'ci' => ['required'],
                'telefono' => ['required'],
                'direccion' => ['nullable'],
                'rol' => ['required'],
                'cargo' => ['required'],
                'fecha_inicio' => ['nullable'],
                'fecha_fin' => ['nullable'],
                'src_foto' => ['nullable'],
                'email' => 'required|string|max:50|unique:users,email,' . $request->id,
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                http_response_code(422);
                throw new \Exception($validator->errors()->first());
            }

            $operario = Operario::findOrFail($request->id);
            $operario->nombre_completo = $request->nombre_completo;
            $operario->ci = $request->ci;
            $operario->telefono = $request->telefono;
            $operario->direccion = $request->direccion;
            $operario->rol = $request->rol;
            $operario->cargo = $request->cargo;
            $operario->fecha_inicio = $request->fecha_inicio;
            $operario->fecha_fin = $request->fecha_fin;
            $operario->src_foto = $request->src_foto;
            $operario->save();

            $operario->user->name = $operario->nombre_completo;
            $operario->user->email = $request->email;
            $operario->user->save();

            $image = $request->foto;
            if ($image) {
                $this->saveImage($image, $operario, 'operario', $operario->src_foto ? true : false);
            }

            DB::commit();
            return response()->json($this->format($operario), 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * search a Socios
     * 
     * @group Operario
     * @authenticated
     * 
     * @bodyParam nombre_completo string
     * @bodyParam ci string
     * @bodyParam telefono string
     * @response 
     */
    public function search(Request $request)
    {
        $data = Operario::when($request->nombre_completo, function ($query) use ($request) {
            $query->where('nombre_completo', 'like', '%' . $request->nombre_completo . '%');
        })
            ->when($request->ci, function ($query) use ($request) {
                $query->where('ci', 'like', '%' . $request->ci . '%');
            })
            ->when($request->telefono, function ($query) use ($request) {
                $query->where('telefono', 'like', '%' . $request->telefono . '%');
            })
            ->paginate(5);
        $this->resetPage();
        return $data;
    }

    /**
     * show a Operario
     * 
     * @group Operario
     * @authenticated
     * 
     * @bodyParam id integer requried
     * @response 
     */
    public function show($id)
    {
        $operario = Operario::findOrFail($id);
        return response()->json($this->format($operario), 200);
    }

    /**
     * delete a Operario
     * 
     * @group Operario
     * @authenticated
     * 
     * @bodyParam id integer requried
     * @response 
     */
    public function delete($id)
    {
        $operario = Operario::findOrFail($id);
        $operario->user->delete();
        $operario->delete();
        DB::commit();
        return response()->json([
            'message' => 'Eliminado correctamente',
            'id' => $id
        ], 200);
    }

    /**
     * restore a Operario
     * 
     * @group Operario
     * @authenticated
     * 
     * @bodyParam id integer requried
     * @response 
     */
    public function restore($id)
    {
        $operario = Operario::withTrashed()->findOrFail($id);
        $operario->user->restore();
        $operario->restore();
        DB::commit();
        return response()->json([
            'message' => 'Restaurado correctamente',
            'id' => $id
        ], 200);
    }
}
