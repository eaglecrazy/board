<?php


namespace App\Usecases\Banners;


class  BannerService
{
    public function cancelModeration($id){}
    public function changeFile($id, \App\Http\Requests\Banner\FileRequest $request){}
    public function editByAdmin($id, \App\Http\Requests\Banner\EditRequest $request){}
    public function editByOwner(\App\Entity\Banner\Banner $banner, \App\Http\Requests\Banner\EditRequest $request){}
    public function moderate($id){}
    public function pay(\App\Entity\Banner\Banner $banner){}
    public function reject(\App\Entity\Banner\Banner $banner, \App\Http\Requests\Banner\RejectRequest $request){}
    public function removeByAdmin(\App\Entity\Banner\Banner $banner){}
    public function removeByOwner($id){}
    public function sendToModeration($id){}

}
