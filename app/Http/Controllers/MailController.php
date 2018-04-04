<?php

namespace App\Http\Controllers;

use \App\Jobs\HandleIncomingEmail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function pipeEmail(Request $request) {
		/*$to = $request->input('to');
		$from = $request->input('from');
		$html = $request->input('html');
		$subject = $request->input('subject');*/
		dispatch(new HandleIncomingEmail($request));

		return response()->json(['success' => 'success'], 200);
	}
}
