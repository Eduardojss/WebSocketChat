<?php

namespace App\Http\Controllers;

use App\Events\IsWriting;
use App\Models\Messages;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Inertia\Inertia;

class UserController extends Controller
{
   public function index(){
   $userLogged = Auth::user();
   $users = User::where('id', '!=', $userLogged->id)->get();
   return response()->json(['users' => $users]);
   }
   public function dashboard(){
   return Inertia::render('Dashboard');
   }
   public function chat(){
   return Inertia::render('Chat');
   }

   public function login(){
      return Inertia::render('Auth/Login');
   }
   public function activeUser(int $user) {
      $userActive = User::find($user);
      return response()->json(['user' => $userActive]);
   }
   public function isWriting(Request $request){

      broadcast(new IsWriting($request['to']))->toOthers();

      //Event::dispatch('IsWriting', $request->to);
   }
}
