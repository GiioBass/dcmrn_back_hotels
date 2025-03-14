<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomCreateRequest;
use App\Http\Requests\RoomUpdateRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $rooms = Room::all();
            return response()->json([
                'success' => true,
                'data' => $rooms
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomCreateRequest $request)
    {
        try {
            foreach ($request->rooms as $room) {
                Room::create([
                    'type' => $room['type'],
                    'accommodation' => $room['accommodation'],
                    'qty_rooms' => $room['qty_rooms'],
                    'hotel_id' => $request->hotel_id
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Room created successfully'
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($hotelId, $roomId)
    {
        try {
            $room = Room::where('hotel_id', $hotelId)->where('id', $roomId)->get();
            return response()->json([
                'success' => true,
                'data' => $room
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomUpdateRequest $request, $hotel, $room)
    {
        try {
            foreach ($request->rooms as $roomData) {
                Room::where('id', $roomData['id'])
                ->where('hotel_id', $hotel)
                ->update([
                    'type' => $roomData['type'],
                    'accommodation' => $roomData['accommodation'],
                    'qty_rooms' => $roomData['qty_rooms'],
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Room updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hotel, Room $room)
    {
        try {
            Room::where('id', $room->id)->where('hotel_id', $hotel)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Room deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred'
            ], 500);
        }
    }
}
