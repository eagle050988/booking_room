<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Room;
use Validator;
use App\Http\Resources\Room as RoomResource;

use Illuminate\Support\Facades\Auth;

class RoomController extends BaseController
{
    public function index()
    {
        $rooms = Room::all();
        
        return $this->sendResponse(RoomResource::collection($rooms), 'Rooms retrieved successfully.');
    }
     
    public function store(Request $request)
    {
        if ($request->user()->hasRole('admin')){
            $input = $request->all();

            $validator = Validator::make($input, [
            'room_name' => 'required',
            'room_capacity' => 'required'
            ]);
            
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
            
            $room = Room::create($input);
            
            return $this->sendResponse(new RoomResource($room), 'Room created successfully.');
        }else{
            return $this->sendError('User role not autorized.');
        }
    }
     
    public function show($id)
    {
        $room = Room::find($id);
        
        if (is_null($room)) {
            return $this->sendError('Room not found.');
        }
        
        return $this->sendResponse(new RoomResource($room), 'Room retrieved successfully.');
    }
     
    public function update(Request $request, $id)
    {
        if ($request->user()->hasRole('admin')){
            $room = Room::find($id);
            
            if (!$room) {
            return response()->json([
            'success' => false,
            'message' => 'Sorry, Room with id ' . $id . ' cannot be found'
            ], 400);
            }
            $updated = $room->fill($request->all())
            ->save();
            
            $room->room_name = $request->room_name;
            $room->room_capacity = $request->room_name;
            $room->photo = $request->photo;
            $room->save();
            
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Room with id ' . $id . ' succesfuly be updated'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, Room could not be updated'
                ], 500);
            }
        }else{
            return $this->sendError('User role not autorized.');
        }
    }
     
    public function destroy($id)
    {
        if ($request->user()->hasRole('admin')){
            $room = Room::find($id);
            
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, Room with id ' . $id . ' cannot be found'
                ], 400);
            }
        
            if ($room->delete()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Room with id ' . $id . ' succesfuly be deleted'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Room could not be deleted'
                ], 500);
            }
        }else{
            return $this->sendError('User role not autorized.');
        }
    }
}
