<?php

use App\Entity\Adverts\Category;
use App\Entity\Page;
use App\Entity\Region;
use App\Http\Router\AdvertsPath;
use App\Http\Router\PagePath;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Entity\Adverts\Attribute;


if (!function_exists('adPath')) {
    function adPath(?Region $region, ?Category $category)
    {
        return app()
            ->make(AdvertsPath::class)
            ->withRegion($region)
            ->withCategory($category);
    }
}


if (!function_exists('pagePath')) {
    function pagePath(?Page $page)
    {
        return app()
            ->make(PagePath::class)
            ->withPage($page);
    }
}


if (!function_exists('dtFormat')) {
    function dtFormat(Carbon $dt)
    {
        return $dt->format('Y.m.d - H:i');
    }
}

if (!function_exists('dFormat')) {
    function dFormat(Carbon $d)
    {
        return $d->format('d.m.Y');
    }
}


if (!function_exists('errorOutput')) {
    function errorOutput($error)
    {
        if (starts_with($error, 'Поле attributes.')) {
            $error = Str::replaceFirst('Поле attributes.', '', $error);
            $error = Str::replaceFirst(' обязательно для заполнения.', '', $error);
            $name = Attribute::whereId($error)->first()->name;
            return 'Поле ' . $name . ' обязательно для заполнения.';
        }
        return $error;
    }
}


if (!function_exists('trimPagination')) {
    function trimPagination(array $paginator, $page)
    {
        $all = [];
        foreach ($paginator as $unit) {
            if (!is_string($unit)) {
                foreach ($unit as $key => $link)
                    $all[$key] = $link;
            }
        }


        $keys = array_keys($all);
        $last = last($keys);

        $trim = [];
        foreach ($all as $key => $link) {
            if ($key === 1 || $key === $last || $key === $page || $key === $page - 1 || $key === $page + 1) {
                $trim[$key] = $link;
            }
        }

        $num = 0;
        $last = 0;
        $hasBreak = false;
        foreach ($trim as $key => $link) {
            if ($hasBreak) {
                $hasBreak = false;
                $last = $key;
                $newPaginator[$num][$key] = $link;
            } else {
                if ($key - $last === 1) {
                    $newPaginator[$num][$key] = $link;
                    $last = $key;
                } else {
                    $newPaginator[++$num] = '...';
                    $newPaginator[++$num][$key] = $link;
                    $hasBreak = true;
                }
            }
        }
        return $newPaginator;
    }
}

if (!function_exists('getCategoryControlColorClass')) {
    function getCategoryControlColorClass($depth) : string
    {
        switch ($depth){
            case 0 : return 'btn-outline-danger';
            case 1 : return 'btn-outline-primary';
            case 2 : return 'btn-outline-info';
            case 3 : return 'btn-outline-success';
            case 4 : return 'btn-outline-warning';
            default : return 'btn-outline-secondary';
        }
    }
}

