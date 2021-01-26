<?php


namespace App\Http\Composers;


use App\Entity\Page;
use Illuminate\Contracts\View\View;

class MenuPagesComposer
{
    public function compose(View $view){
        $view->with('menuPages', Page::whereIsRoot()->defaultOrder()->getModels());
    }
}
