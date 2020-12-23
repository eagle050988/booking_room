<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmBookingEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    public $room_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking, $room_name)
    {
        //
        $this->booking = $booking;
        $this->room_name = $room_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('testmailfarid@gmail.com')
                   ->view('confirm_mail')
                   ->with(
                    [
                        'room_name' => $this->room_name,
                        'tgl_booking'=> $this->booking->booking_time,
                        'tgl_check_in' => $this->booking->check_in_time,
                        'tgl_check_out' => $this->booking->check_out_time,
                    ]);
    }
}
