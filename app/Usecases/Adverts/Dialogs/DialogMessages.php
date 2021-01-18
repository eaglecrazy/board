<?php


namespace App\Usecases\Adverts\Dialogs;

class DialogMessages
{
    public function __construct($oldMessages, $newMessages)
    {
        $this->newMessages = $newMessages;
        $this->oldMessages = $oldMessages;
    }

    public function hasMessages(): bool
    {
        if($this->newMessages->isEmpty() && $this->oldMessages->isEmpty()){
            return false;
        }
        return true;
    }

    public $oldMessages;
    public $newMessages;
}
