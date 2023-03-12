<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Medidor;
use App\Models\Propiedad;
use App\Models\Socio;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SocioController extends Controller
{
    use ImageTrait;
    /**
     * Format a Socio model
     */
    private function format(Socio $socio) {
        return [
            'nombre_completo'=>$socio->nombre_completo,
            'ci'=>$socio->ci,
            'telefono'=>$socio->telefono,
            'src_foto'=>$socio->src_foto,
            'propiedades'=>$socio->propiedades,
            'foto' => $socio->foto,
            'foto_thumbnail' => $socio->foto_thumbnail,
            'foto_thumbnail_sm' => $socio->foto_thumbnail_sm,
        ];
    }

    /**
     * Register a Socio
     * 
     * @group Socio
     * @authenticated
     * 
     * @bodyParam nombre_completo string requried
     * @bodyParam ci string requried
     * @bodyParam telefono string requried
     * @bodyParam src_foto string
     * @bodyParam propiedades Array[Propiedad]
     * @bodyParam email email
     * @bodyParam password password
     * @response 
     */
    public function create(Request $request) {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'nombre_completo'=>['required'],
                'ci'=>['required'],
                'telefono'=>['required'],
                'src_foto'=>['nullable'],
                'propiedades'=>['nullable'],
                'email' => 'required|email|unique:users|max:50',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                http_response_code(422);
                throw new \Exception($validator->errors()->first());
            }

            $socio = new Socio();
            $socio->nombre_completo = $request->nombre_completo;
            $socio->ci = $request->ci;
            $socio->telefono = $request->telefono;
            $socio->src_foto = $request->src_foto;
            $socio->propiedades = $request->propiedades;
            $socio->save();

            $image = $request->foto;
            if ($image) {
                $this->saveImage($image, $socio, 'socio');
            }

            $user = new User();
            $user->name = $socio->nombre_completo;
            $user->email = $request->email;
            $user->socio_id = $socio->id;
            $user->password = bcrypt($request->password);
            $user->save();

            if ($request->propieades) {
                $propiedades = $request->propiedades;
                foreach ($propiedades as $keyPropiedad => $itemPropiedad) {
                    $propiedad = new Propiedad();
                    $propiedad->socio_id = $socio->id;
                    $propiedad->direccion = $itemPropiedad->direccion;
                    $propiedad->descripcion = $itemPropiedad->descripcion;
                    if ($itemPropiedad->medidores) {
                        $medidores = $itemPropiedad->medidores;
                        foreach ($medidores as $keyMedidor => $itemMedidor) {
                            $medidor = new Medidor();
                            $medidor->propiedad_id = $propiedad->id;
                            $medidor->codigo_medidor = $request->codigo_medidor;
                            $medidor->estado = $request->estado;
                            $medidor->tarifa_id = $request->tarifa_id;
                            $medidor->save();
                        }
                    }
                }
            }

            DB::commit();
            return response()->json($this->format($socio), 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }    
    }

    /**
     * Update a Socio
     * 
     * @group Socio
     * @authenticated
     * 
     * @bodyParam id integer requried
     * @bodyParam nombre_completo string requried
     * @bodyParam ci string requried
     * @bodyParam telefono string requried
     * @bodyParam src_foto string
     * @bodyParam email email
     * @response 
     */
    public function update(Request $request) {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'nombre_completo' => ['required'],
                'ci' => ['required'],
                'telefono' => ['required'],
                'src_foto' => ['nullable'],
                'propiedades' => ['nullable'],
                'email' => 'required|string|max:50|unique:users,email,'.$request->id,
            ]);

            if ($validator->fails()) {
                http_response_code(422);
                throw new \Exception($validator->errors()->first());
            }

            $socio = Socio::findOrFail($request->id);
            $socio->nombre_completo = $request->nombre_completo;
            $socio->ci = $request->ci;
            $socio->telefono = $request->telefono;
            $socio->src_foto = $request->src_foto;
            $socio->propiedades = $request->propiedades;
            $socio->save();

            $socio->user->name = $socio->nombre_completo;
            $socio->user->email = $request->email;
            $socio->user->save();

            $image = $request->foto;
            if ($image) {
                $this->saveImage($image, $socio, 'socio', $socio->src_foto ? true : false);
            }

            DB::commit();
            return response()->json($this->format($socio), 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }  
    }

    /**
     * search a Socios
     * 
     * @group Socio
     * @authenticated
     * 
     * @bodyParam nombre_completo string
     * @bodyParam ci string
     * @bodyParam telefono string
     * @response 
     */
    public function search(Request $request) {
        $data = Socio::when($request->nombre_completo, function ($query) use ($request) {
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
     * show a Socio
     * 
     * @group Socio
     * @authenticated
     * 
     * @bodyParam id integer requried
     * @response 
     */
    public function show($id)
    {
        $socio = Socio::findOrFail($id);
        return response()->json($this->format($socio), 200);
    }

    /**
     * delete a Socio
     * 
     * @group Socio
     * @authenticated
     * 
     * @bodyParam id integer requried
     * @response 
     */
    public function delete($id) {
        $socio = Socio::findOrFail($id);
        $socio->delete();
        DB::commit();
        return response()->json([
            'message' => 'Eliminado correctamente',
            'id' => $id
        ], 200);
    }

    /**
     * restore a Socio
     * 
     * @group Socio
     * @authenticated
     * 
     * @bodyParam id integer requried
     * @response 
     */
    public function restore($id) {
        $socio = Socio::withTrashed()->findOrFail($id);
        $socio->restore();
        DB::commit();
        return response()->json([
            'message' => 'Restaurado correctamente',
            'id' => $id
        ], 200);
    }
}
