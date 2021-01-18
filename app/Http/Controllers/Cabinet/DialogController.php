<?php

namespace App\Http\Controllers\Cabinet;


use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Dialog\Dialog;
use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\MessageRequest;
use App\Usecases\Adverts\Dialogs\DialogService;
use App\Usecases\Adverts\Dialogs\DialogUsersRoles;
use DomainException;
use Illuminate\Support\Facades\Auth;

class DialogController extends Controller
{
    private $dialogService;

    public function __construct(DialogService $dialog)
    {
        $this->dialogService = $dialog;
    }

    public function index()
    {
        $dialogs = $this->dialogService->getCurrentUserDialogsQueryBuilder()->paginate(20);
        return view('cabinet.dialogs.index', compact('dialogs'));
    }

    public function dialog(Advert $advert)
    {
        $dialog = $this->dialogService->getDialog($advert);
        $messages = $this->dialogService->getDialogMessages($dialog);
        $otherUser = $this->dialogService->getOtherUser($dialog);
        $this->dialogService->readMessages($dialog);
        return view('cabinet.dialogs.dialog', compact('advert', 'messages', 'otherUser', 'dialog'));
    }

    public function write(Dialog $dialog, MessageRequest $request)
    {
        try {
            $this->dialogService->writeMessage($dialog, $request['message']);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back();
    }
}
