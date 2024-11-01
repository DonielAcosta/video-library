<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



class VideoController extends Controller{


    //ver videos 
    public function index(){
        // Obtener todos los videos
        $videos = Video::all();
    
        return response()->json([
            'data' => $videos,
            'message' => 'Videos obtenidos exitosamente'
        ], 200);
    }

    public function store(Request $request){
        // Validar la solicitud
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'youtube_url' => 'required|url|max:255',
                'user_id' => 'required|numeric|exists:users,id',
            ]
        );

        // Comprobar si la validación falla
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Crear un arreglo con los datos validados
        $videoData = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'youtube_url' => $request->input('youtube_url'),
            'user_id' => $request->input('user_id'),
        ];

        // Crear el video
        $video = Video::create($videoData);

        // Devolver la respuesta en formato JSON
        return response()->json(
            [
                'created' => true,
                'data' => $video,
                'message' => 'Video creado exitosamente'
            ],
            201
        );
    }

    public function show($id){
        // Buscar el video por ID
        $video = Video::find($id);
    
        // Verificar si el video existe
        if (!$video){
            return response()->json(['message' => 'Video not found'], 404);
        }
    
        return response()->json(
            [
                'showed' => true,
                'data' => $video,
                'message' => 'Video obtenido exitosamente'
            ],
            200
        );
    }
    

    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string|max:1000',
                'youtube_url' => 'sometimes|required|url|max:255',
                'user_id' => 'sometimes|required|numeric|exists:users,id',
            ]
        );
    
        // Comprobar si la validación falla
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        // Encontrar el video por ID
        $video = Video::find($id);
    
        // Verificar si el video existe
        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }
    
        $video->fill($request->all());
    
        // Guardar los cambios
        $video->save();
    
        return response()->json(
            [
                'updated' => true,
                'data' => $video,
                'message' => 'Video actualizado exitosamente'
            ],
            200
        );
    }
    

    
    public function destroy($id){
        // Buscar el video por ID
        $video = Video::findOrFail($id);
        
        // Eliminar el video
        $video->delete();
        
        return response()->json([
            'deleted' => true,
            'message' => 'Video eliminado exitosamente'
        ], 200);
    }
    

    public function search(Request $request){
        $search = $request->get('search');

        $videos = Video::with(['analytics'])
            ->when($search, function ($query, $search) {
                return $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%']);

            })
            ->get(); // Obtener todos los resultados sin paginación
            Log::info('Videos encontrados: ' . $videos->count());
    
        foreach ($videos as $video) {
            // Crear o obtener el modelo de Analytics asociado al video
            $analytics = $video->analytics ?? new Analytics(['video_id' => $video->id]);
            
            $analytics->searches++;
            
            $analytics->save();
        }

        return response()->json([
            'listed' => true,
            'local_videos' => $videos,
            'message' => 'Resultados obtenidos exitosamente'
    ], 200);
    }


}
