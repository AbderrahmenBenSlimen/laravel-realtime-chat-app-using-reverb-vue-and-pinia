<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use Inertia\Inertia;
use Illuminate\Http\Request;

class RoomShowController extends Controller
{
    public function __invoke(Room $room)
    {

        return Inertia::render('Room', [
            'room' => RoomResource::make($room)
        ]);
    }
}
