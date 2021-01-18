<?php


namespace App\Usecases\Adverts\Dialogs;

class DialogMessages
{
    public function __construct($oldMessages, $newMessages)
    {
        $this->newMessages = $newMessages;
        $this->oldMessages = $oldMessages;
    }

    public $oldMessages;
    public $newMessages;
}
