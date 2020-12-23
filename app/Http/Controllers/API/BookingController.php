<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Booking;
use Validator;
use DB;
Use Carbon\Carbon;

use App\Http\Resources\Booking as BookingResource;

use Illuminate\Support\Facades\Auth;

use App\Mail\ConfirmBookingEmail;
use Illuminate\Support\Facades\Mail;

class BookingController extends BaseController
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')){
        $bookings = Booking::all();
        
        return $this->sendResponse(BookingResource::collection($bookings), 'Bookingss retrieved successfully.');
        }else{
            $bookings = DB::table('bookings')
                        ->where('user_id', Auth::user()->id)
                        ->get();
        
            return $this->sendResponse(BookingResource::collection($bookings), 'Bookingss retrieved successfully.');
        }
    }
     
    public function store(Request $request)
    {
        $input = $request->all();
        
        $room = DB::table('rooms')
                ->where('id', $input['room_id'])
                ->first();
        $user = Auth::user();

        $mytime = Carbon::now();
        $input['booking_time'] = $mytime->toDateTimeString();
        $input['user_id'] = $user->id;
        $email = $user->email;
        

        if($input['total_person'] <= $room->room_capacity){
            $booking = Booking::create($input);
            try{
                Mail::to($email)->send(new ConfirmBookingEmail($booking, $room->room_name));
            }
            catch (Exception $e){
                return $this->sendError($e->getMessage());
            }
            return $this->sendResponse(new BookingResource($booking), 'Booking created successfully.');
        }else{
            return $this->sendError('Out of room capacity');
        }
    }
     
    public function show($id)
    {
        $booking = Booking::find($id);
        
        if (is_null($booking)) {
            return $this->sendError('Booking not found.');
        }
        
        return $this->sendResponse(new BookingResource($booking), 'Booking retrieved successfully.');
    }
     
    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        
        if (!$booking) {
        return response()->json([
        'success' => false,
        'message' => 'Sorry, booking with id ' . $id . ' cannot be found'
        ], 400);
        }
        $updated = $booking->fill($request->all())
        ->save();
        
        $booking->name = $request->name;
        
        $booking->detail = $request->detail;
        $booking->save();
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'booking with id ' . $id . ' succesfuly be updated'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, booking could not be updated'
            ], 500);
        }
    }
     
    public function destroy($id)
    {
        $booking = Booking::find($id);
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, booking with id ' . $id . ' cannot be found'
            ], 400);
        }
     
        if ($booking->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'booking with id ' . $id . ' succesfuly be deleted'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'booking could not be deleted'
            ], 500);
        }
    }
}
