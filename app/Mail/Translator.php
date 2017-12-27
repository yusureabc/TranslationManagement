<?php

namespace App\Mail;

use App\Service\Admin\LanguageService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Translator extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $language_name;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $data, $language_name )
    {
        $this->data = $data;
        $this->language_name = $language_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view( 'email.translator.notice' );
    }
}
