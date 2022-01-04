<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pterodactyl\Models\User as User;
use Pterodactyl\Notifications\Newsletter;
use Illuminate\Support\Facades\Notification;
use Pterodactyl\Http\Controllers\Controller;

class NewsletterController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.newsletter.index');
    }
    
    public function send(Request $request): Response
    {
        $users = User::where('newsletter', 1)->get();
        foreach ($users as $user) {
          try {
              Notification::route('mail', $user->email)
                  ->notify(new Newsletter($user, $request->post('subject'), $request->post('body'), $request->post('urltext'), $request->post('urllink')));
          } catch (Exception $exception) {
              return response($exception->getMessage(), 500);
          }
        }

        return response('', 204);
    }
    
}
