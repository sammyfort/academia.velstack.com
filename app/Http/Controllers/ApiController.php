<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{

      public function subjects()
    {
        return response()->json([
            'subjects' => school()->subjects()->get(['id', 'name', 'code'])->toArray()
        
        ]);
    }
}
