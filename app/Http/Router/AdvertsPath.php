<?php


namespace App\Http\Router;


use App\Entity\Adverts\Category;
use App\Entity\Region;
use Illuminate\Contracts\Routing\UrlRoutable;

class AdvertsPath implements UrlRoutable
{
    public $region;
    public $category;

    public function withRegion(?Region $region): self
    {
        $clone = clone $this;
        $clone->region = $region;
        return $clone;
    }

    public function withCategory(?Category $category): self
    {
        $clone = clone $this;
        $clone->category = $category;
        return $clone;
    }

    public function getRouteKey()
    {
        $segments = [];
        if ($result = $this->makeRegionPath()) {
            $segments[] = $result;
        }
        if ($result = $this->makeCategoryPath()) {
            $segments[] = $result;
        }
        return implode('/', $segments);
    }

    private function makeRegionPath(): string
    {
        if ($this->region) {
            return $this->region->getPath();
        }
        return '';
    }

    private function makeCategoryPath(): string
    {
        if ($this->category) {
            return $this->category->getPath();
        }
        return '';
    }

    public function getRegionUrl(Region $region): string
    {
        $segments = [];
        if ($path = $this->makeRegionPath()) {
            $segments[] = $path;
        }
        $segments[] = $region->slug;
        if ($path = $this->makeCategoryPath()) {
            $segments[] = $path;
        }
        return implode('/', $segments);
    }

    public function getCategoryUrl(Category $category): string
    {
        $segments = [];
        if ($path = $this->makeRegionPath()) {
            $segments[] = $path;
        }
        if ($path = $this->makeCategoryPath()) {
            $segments[] = $path;
        }
        $segments[] = $category->slug;
        return implode('/', $segments);
    }


    public function getRouteKeyName()
    {
        return 'adverts_path';
    }

    public
    function resolveRouteBinding($value)
    {
        $chunks = explode('/', $value);

        $region = null;
        do {
            $slug = reset($chunks);
            if ($slug &&
                //поиск региона с таким слагом + у которого родитель текущий регион или нулл
                $next = Region::where('slug', $slug)
                    ->where('parent_id', $region ? $region->id : null)
                    ->first()) {
                $region = $next;
                array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        $category = null;
        do {
            $slug = reset($chunks);

            $next = Category::where('slug', $slug)
                ->where('parent_id', $category ? $category->id : null)
                ->first();

//            if ($slug &&
//                $next = Category::where('slug', $slug)
//                    ->where('parent_id', $category ? $category->id : null)
//                    ->first()) {
            if ($slug && $next) {
                $category = $next;
                array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        if (!empty($chunks)) {
            abort(404);
        }

        return $this->withRegion($region)->withCategory($category);
    }

}
