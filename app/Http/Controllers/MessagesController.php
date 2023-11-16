<?php

namespace App\Http\Controllers;

use App\Events\SendChatMessages;
use App\Models\Messages;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class MessagesController extends Controller
{
    public function messages(int $user){
        $userFrom = Auth::user()->id;
        $userT = User::find($user);
        $userTo = $userT->id;

        $messages = Messages::where([['from', $userFrom],['to', $userTo]])->orWhere([['to', $userFrom],['from', $userTo]])->orderBy('created_at', 'ASC')->get();
       
        return response()->json(['messages' => $messages], Response::HTTP_OK);
     }

     public function sendMessage(Request $request){
         $message = new Messages;
         $message->from = Auth::user()->id;
         $message->to = $request->to;
         $message->content = $request->content;
         $message->created_at = now();
         $message->updated_at = now();
         $message->save();

         Event::dispatch(new SendChatMessages($message, $request->to));
    }


}
