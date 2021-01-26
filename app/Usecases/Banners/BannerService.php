<?php


namespace App\Usecases\Banners;


use App\Entity\Adverts\Category;
use App\Entity\Banner\Banner;
use App\Entity\Region;
use App\Entity\User\User ;
use App\Http\Requests\Banner\BannerCreateRequest;
use App\Http\Requests\Banner\BannerEditRequest;
use App\Http\Requests\Banner\BannerFileRequest;
use App\Http\Requests\Banner\BannerRejectRequest;
use App\Services\Banner\CostCalculator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    private $calculator;

    public function __construct(CostCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function cancelModeration(Banner $banner): void
    {
        $banner->cancelModeration();
    }

    public function changeFile(Banner $banner, BannerFileRequest $request): void
    {
        if (!$banner->canBeChanged()) {
            throw new \DomainException('Баннер недоступен для редактирования.');
        }
        Storage::delete('public/' . $banner->file);
        $banner->update([
            'format' => $request['format'],
            'file' => $request->file('file')->store('banners', 'public'),
        ]);
    }

    public function click(Banner $banner){
        $banner->click();
    }

    public function create(User $user, Category $category, ?Region $region, BannerCreateRequest $request): Model
    {
        $file = $request->file('file')->store('bitems', 'public');

        $banner = Banner::make([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
            'format' => $request['format'],
            'file' => $file,
            'status' => Banner::STATUS_DRAFT,
        ]);

        $banner->user()->associate($user);
        $banner->category()->associate($category);
        $banner->region()->associate($region);

        $banner->saveOrFail();

        return $banner;
    }

    public function edit(Banner $banner, BannerEditRequest $request): void
    {
        if(!($banner->canBeChanged() || Gate::allows('manage-banners', $this))){
            throw new \DomainException('Баннер недоступен для редактирования.');
        }

        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
        ]);
    }

    public function moderate(Banner $banner): void
    {
        $banner->moderate();
    }

    public function order(Banner $banner): Banner
    {
        $cost = $this->calculator->calc($banner->limit);
        $banner->order($cost);
        return $banner;
    }

    public function pay(Banner $banner): void
    {
        $banner->pay(Carbon::now());
    }

    public function reject(Banner $banner, BannerRejectRequest $request): void
    {
        $banner->reject($request['reason']);
    }

    public function removeByAdmin(Banner $banner): void
    {
        $banner->delete();
        File::delete($banner->file);
    }

    public function removeByOwner(Banner $banner): void
    {
        if (!$banner->canBeRemoved()) {
            throw new \DomainException('Баннер недоступен для удаления');
        }
        $banner->delete();
        Storage::delete('public/' . $banner->file);
    }

    public function sendToModeration(Banner $banner): void
    {
        $banner->sendToModeration();
    }

}
