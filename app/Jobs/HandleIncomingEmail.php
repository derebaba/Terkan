<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\IncomingEmail;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use Illuminate\Bus\Queueable;
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
		Mail::send([], [], function ($message) {
			$message->to('erdemderebaba@gmail.com')
				->subject($this->request['subject'])
				->setBody($this->request['html'], 'text/html');
		});
    }
}
