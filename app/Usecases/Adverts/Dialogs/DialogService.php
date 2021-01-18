<?php


namespace App\Usecases\Adverts\Dialogs;


use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Dialog\Dialog;
use App\Entity\User\User;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DialogService
{
    private $dialogUsersRoles;

    public static function newMessagesCount(): int
    {
        $id = Auth::id();
        $dialogs = DB::table('advert_dialogs')
            ->where('user_id', $id)
            ->orWhere(function ($query) use ($id) {
                $query->where('client_id', $id);
            })->get();
        $sum = 0;
        foreach ($dialogs as $dialog) {
            if ($dialog->user_id == $id){
                $sum += $dialog->user_new_messages;
            } else if($dialog->client_id == $id){
                $sum += $dialog->client_new_messages;
            }
        }
        return $sum;
    }

    public function getCurrentUserDialogsQueryBuilder()
    {
        $dialogsIds = $this->getCurrentUserDialogsIds();
        return Dialog::whereIn('id', $dialogsIds);
    }

    private function getCurrentUserDialogsIds(): array
    {
        return DB::table('advert_dialogs')
            ->where('user_id', Auth::id())
            ->orWhere(function ($query) {
                $query->where('client_id', Auth::id());
            })->pluck('id')->toArray();
    }

    private function setDialogUsersRolesByAdvert(Advert $advert)
    {
        $users = new DialogUsersRoles();
        $user = Auth::user();
        if ($advert->user_id === $user->id) {
            $clientId = Dialog::where('advert_id', $advert->id)
                ->where('user_id', $user->id)->first()->client_id;
            $users->client = User::where('id', $clientId)->first();
            $users->otherUser = $users->client;
        } else {
            $users->client = $user;
            $users->otherUser = User::whereId($advert->user_id)->first();
        }
        $this->dialogUsersRoles = $users;
    }

    private function getDialogUsersRoles(Dialog $dialog): DialogUsersRoles
    {
        if (empty($this->dialogUsersRoles)) {
            $this->setDialogUsersRolesByAdvert($dialog->getAdvert());
        }
        return $this->dialogUsersRoles;
    }

    public function getOtherUser(Dialog $dialog): User
    {
        return $this->getDialogUsersRoles($dialog)->otherUser;
    }

    public function getDialogMessages(Dialog $dialog): DialogMessages
    {
        $messages = $dialog->messages()->orderBy('updated_at')->get();
        $roles = $this->getDialogUsersRoles($dialog);

        if ($roles->client === Auth::user() && $dialog->client_new_messages) {
            $newMessages = $messages->splice($messages->count() - $dialog->client_new_messages, $dialog->client_new_messages);
        } else if ($roles->client !== Auth::user() && $dialog->user_new_messages) {
            $newMessages = $messages->splice($messages->count() - $dialog->user_new_messages, $dialog->user_new_messages);
        } else {
            $newMessages = collect([]);
        }

        return new DialogMessages($messages, $newMessages);
    }

    public function readMessages(Dialog $dialog)
    {
        if ($this->getDialogUsersRoles($dialog)->client === Auth::user()) {
            $dialog->readByClient();
        } else {
            $dialog->readByOwner();
        }
    }

    public function getDialog(Advert $advert)
    {
        $this->setDialogUsersRolesByAdvert($advert);
        return $advert->getOrCreateDialogWith($this->dialogUsersRoles->client->id);
    }

    public function writeMessage(Dialog $dialog, string $message)
    {
        $advert = $dialog->getAdvert();
        if ($dialog->user_id === Auth::id()) {
            $advert->writeOwnerMessage($dialog->client_id, $message);
        } else {
            $advert->writeClientMessage(Auth::id(), $message);
        }
    }
}


