<?php


namespace App\Usecases\Banners;


use App\Entity\Adverts\Category;
use App\Entity\Banner\Banner;
use App\Entity\Region;
use App\Entity\User;
use App\Http\Requests\Banner\BannerCreateRequest;
use App\Http\Requests\Banner\BannerEditRequest;
use App\Http\Requests\Banner\BannerFileRequest;
use App\Http\Requests\Banner\BannerRejectRequest;
use App\Services\Banner\CostCalculator;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class  BannerService
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
    }

    public function create(User $user, Category $category, ?Region $region, BannerCreateRequest $request): Banner
    {
        $banner = Banner::make([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
            'format' => $request['format'],
            'file' => $request->file()->store('banners', 'public'),
            'status' => Banner::STATUS_DRAFT,
        ]);

        $banner->user()->associate($user);
        $banner->category()->associate($category);
        $banner->region()->associate($region);

        $banner->saveOrFail();

        return $banner;
    }

    public function editByAdmin(Banner $banner, BannerEditRequest $request): void
    {
        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
        ]);
    }

    public function editByOwner(Banner $banner, BannerEditRequest $request): void
    {
        if(!$banner->canBeChanged()){
            throw new \DomainException('Баннер недоступен для редактирования.');
        }
        File::delete($banner->file);
        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
            'file' => $request['file']->store('banners'),
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
        File::delete($banner->file);
    }

    public function sendToModeration(Banner $banner): void
    {
        $banner->sendToModeration();
    }

}
