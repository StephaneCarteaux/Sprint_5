<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function play(Request $request)
    {
        $request->validate([
            //'id' => 'required|integer'
        ]);

        $dice_1 = rand(1, 6);
        $dice_2 = rand(1, 6);

        $this->store($request->id, $dice_1, $dice_2);

        return response()->json([
            'dice_1' => $dice_1,
            'dice_2' => $dice_2]);
    }

    public function store($user_id, $dice_1, $dice_2){
        Game::create([
            'user_id' => $user_id,
            'dice_1' => $dice_1,
            'dice_2' => $dice_2
        ]);
        
    }
}
