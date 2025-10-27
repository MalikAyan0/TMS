<?php
namespace App\Http\Controllers\pages;


use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $menuData = config('menu'); // or however you're getting Vuexy's menu array

        $menuData[0]->menu = collect($menuData[0]->menu)
            ->filter(function ($menu) {
                return $this->canAccessMenu($menu);
            })
            ->values()
            ->toArray();

        return view('layouts.app', compact('menuData'));
    }

    private function canAccessMenu($menu)
    {
        // If no permission is set, allow
        if (!isset($menu['permission'])) {
            return true;
        }

        // If permission exists, check Spatie
        if (!Auth::user()->can($menu['permission'])) {
            return false;
        }

        // If it has submenu, filter them too
        if (isset($menu['submenu'])) {
            $menu['submenu'] = collect($menu['submenu'])
                ->filter(function ($submenu) {
                    return $this->canAccessMenu($submenu);
                })
                ->values()
                ->toArray();

            // If submenu is empty after filtering, hide the parent
            if (empty($menu['submenu'])) {
                return false;
            }
        }

        return true;
    }
}
