<?php

namespace App\Mailers;

use App\User;

use Illuminate\Support\Facades\Mail;

class AppMailer
{

    /**
     * The sender of the email.
     *
     * @var string
     */
    protected $from = 'noreply@academicresearchassistants.com';

    /**
     * The recipient of the email.
     *
     * @var string
     */
    protected $to;

    /**
     * The view for the email.
     *
     * @var string
     */
    protected $view;

    /**
     * The data associated with the view for the email.
     *
     * @var array
     */
    protected $data = [];

  

    /**
     * Deliver the email confirmation.
     *
     * @param  User $user
     * @return void
     */
    public function sendEmailConfirmationTo(User $user)
    {
        $this->to = $user->email;
        $this->view = 'emails.confirm';
        $this->data = compact('user');

        $this->deliver();
    }

    /**
     * Deliver the email.
     *
     * @return void
     */
    public function deliver()
    {
        Mail::send($this->view, $this->data, function ($message) {
            $message->from($this->from, 'Administrator')
                    ->to($this->to);
        });
    }
}
