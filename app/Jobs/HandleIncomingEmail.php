<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\IncomingEmail;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use Illuminate\Bus\Queueable;
use App\Models\SendgridParse;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class HandleIncomingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$mail = new SendgridParse($this->request);
		Mail::send([], [], function ($message) use ($mail) {
			$message->to('erdemderebaba@gmail.com')
				->from($mail->from['email'], $mail->from['name'])
				->subject($mail->subject)
				->setBody($mail->html, 'text/html');
		});
    }
}
