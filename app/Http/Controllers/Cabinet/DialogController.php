<?php

namespace App\Http\Controllers\Cabinet;


use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Dialog\Dialog;
use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\MessageRequest;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DialogController extends Controller
{
    public function dialog(Advert $advert)
    {
        $user = Auth::user();
        if ($advert->user_id === $user->id) {
            $clientId = Dialog::where('advert_id', $advert->id)
                ->where('user_id', $user->id)->first()->client_id;
            $client = User::where('id', $clientId)->first();
        } else {
            $client = $user;
        }

        $dialog = $advert->getDialogWith($client->id);
        $messages = $dialog->messages()->orderBy('updated_at')->get();
        $newMessages = collect([]);

        if ($client = $user) {
            if ($dialog->client_new_messages) {
                $newMessages = $messages->splice($messages->count() - $dialog->client_new_messages, $dialog->client_new_messages);
            }
            $dialog->readByClient();
        } else {
            if ($dialog->user_new_messages) {
                $newMessages = $messages->splice($messages->count() - $dialog->user_new_messages, $dialog->user_new_messages);
            }
            $dialog->readByOwner();
        }
        return view('cabinet.dialogs.dialog', compact('advert', 'messages', 'newMessages'));
    }

    public function index()
    {
        $dialogsIds = DB::table('advert_dialogs')
            ->where('user_id', Auth::id())
            ->orWhere(function ($query) {
                $query->where('client_id', Auth::id());
            })->pluck('id');
        $dialogs = Dialog::whereIn('id', $dialogsIds)->paginate(20);
        return view('cabinet.dialogs.index', compact('dialogs'));
    }

    public function write(Advert $advert, MessageRequest $request)
    {
        try {
            $advert->writeClientMessage(Auth::id(), request('message'));
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back();
    }
}
