<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Referenced by MemberDonationController::sendDonationConfirmation() but not
 * included in the source dump — added so that call site resolves to a real class.
 */
class DonationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function build()
    {
        return $this->subject('Thank you for your donation')
            ->view('member.emails.donation-confirmation', [
                'donation' => $this->donation,
            ]);
    }
}
