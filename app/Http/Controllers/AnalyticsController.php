<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;

class AnalyticsController extends Controller{
    public function index() {
        // Obtener todas las analíticas de videos
        $analytics = Analytics::with('video')->get();

        return response()->json([
            'data' => $analytics,
            'message' => 'Analíticas obtenidas exitosamente'
        ], 200);
    }

    public function show($id){
        // Intentar encontrar el video por ID
        $video = Video::find($id);
    
        // Si el video no se encuentra, devolver un error 404
        if (!$video) {
            return response()->json(['error' => 'Not Found'], 404);
        }
    
        // Si el video se encuentra, obtener sus analíticas
        $analytics = $video->analytics ?? ['views' => 0, 'searches' => 0];
    
        return response()->json([
            'data' => $analytics,
            'message' => 'Analíticas obtenidas exitosamente',
        ], 200);
    }
    
    
    public function incrementViews(Request $request, $id) {
        // Validar que el video existe
        $validator = Validator::make(
            ['video_id' => $id], // Solo estamos validando el ID del video
            [
                'video_id' => 'required|numeric|exists:videos,id', 
            ]
        );
    
        // Comprobar si la validación falla
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        // Encontrar el video por ID
        $video = Video::findOrFail($id); // Obtiene el video o devuelve un error 404
    
        // Acceso o creación de analíticas
        $analytics = $video->analytics ?? new Analytics(['video_id' => $video->id]);
        
        // Incrementar el contador de vistas
        $analytics->views++;
        
        // Guardar cambios
        $analytics->save();
    
        return response()->json([
            'updated' => true,
            'data' => $analytics,
            'message' => 'Contador de vistas actualizado exitosamente'
        ], 200);
    }
    
}
