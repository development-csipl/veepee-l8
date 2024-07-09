<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $details;
    public function __construct($details)
    {
        $this->details = $details;
    }


    /**
     * Build the message.
     *['email' => $row->supplier->email,'subject' => 'Order auto expire on '.$row->orderlast_date, 'expirydate' => date('d-m-Y',strtotime($row->orderlast_date)), 'supplier_name' => $row->supplier->name , 'link' => route('supplier.orders.show', $row->id)];
     * @return $this
     */
    public function build(){
        return $this->view('emails.notify_order')->with(['data' =>$this->details]);
    }
}
