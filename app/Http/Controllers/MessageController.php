<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use App\Events\MessageSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function user_list()
	{
		$list = User::latest()->where('id','!=',Auth::user()->id)->get();

		if (\Request::ajax()) {
			return response()->json($list,200);
		}
		else{
			return abort(404);
		}

	}
	public function user_message($id=null){
		$user = User::find($id);
		$messages = $this->message_by_user_id($id);
		if (\Request::ajax()) {
			return response()->json([
				'messages'=>$messages,
				'user'=>$user,
			],200);
		}
		else{
			return abort(404);
		}
	}

	public function send_message(Request $request){
		if (!\Request::ajax()){
			return abort(404);
		}
		$messages = Message::create([
			'message'=>$request->message,
			'from'=>Auth::user()->id,
			'to'=>$request->user_id,
			'type'=>0
		]);
		$messages = Message::create([
			'message'=>$request->message,
			'from'=>Auth::user()->id,
			'to'=>$request->user_id,
			'type'=>1
		]);
		broadcast(new MessageSend($messages));
		return response()->json($messages,200);
	}

	public function delete_single_message($id=null){
		if (!\Request::ajax()) {
			return abort(404);
		}else{
			$message = Message::where('id', $id)->delete();
			return response()->json('succesfully Deleted', 200);
		}
		
	}

	public function delete_all_message($id=null){
		$messages = $this->message_by_user_id($id);
		foreach ($messages as $value) {
			Message::where('id', $value->id)->delete();
		}
		return response()->json('all deleted',200);
	}

	public function message_by_user_id($id){
		$messages = Message::where(function($q) use($id){
			$q->where('from',Auth::user()->id);
			$q->where('to',$id);
			$q->where('type',0);
		})->orWhere(function($q) use ($id){
			$q->where('from',$id);
			$q->where('to',Auth::user()->id);
			$q->where('type',1);
		})->with('user')->get();
		return $messages;
	}

}
