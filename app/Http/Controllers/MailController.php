<?php

namespace App\Http\Controllers;

use \App\Jobs\HandleIncomingEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\SendgridParse;

class MailController extends Controller
{
    public function pipeEmail(Request $request) {
		//$mail = new SendgridParse($request);
		
		$to = $request->input('to');
		$from = $request->input('from');
		$html = $request->input('html');
		$subject = $request->input('subject');
		dispatch(new HandleIncomingEmail($request));

		return response()->json(['success' => 'success'], 200);
	}
}
