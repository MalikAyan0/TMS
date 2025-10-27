@php
use Illuminate\Support\Facades\Route;

$configData = Helper::appClasses();
$currentRouteName = Route::currentRouteName();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu"
  @foreach ($configData['menuAttributes'] as $attribute => $value)
    {{ $attribute }}="{{ $value }}"
  @endforeach
>
  <!-- ! Hide app brand if navbar-full -->
  @if (!isset($navbarFull))
    <div class="app-brand demo">
      <a href="{{ url('/') }}" class="app-brand-link">
        <span class="app-brand-logo demo">@include('_partials.macros', ['width' => 150, 'height' => 60])</span>
      </a>
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
        <i class="icon-base ti tabler-x d-block d-xl-none"></i>
      </a>
    </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

      {{-- Skip if permission is set and user does not have it --}}
      @if (isset($menu->permission) && !auth()->user()->can($menu->permission))
        @continue
      @endif

      {{-- Menu Header --}}
      @if (isset($menu->menuHeader))
        <li class="menu-header small">
          <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else
        @php
          $activeClass = null;

          // Direct active
          if ($currentRouteName === $menu->slug) {
              $activeClass = 'active';
          }

          // If has submenu → check submenu slugs
          elseif (isset($menu->submenu)) {
              foreach ($menu->submenu as $submenu) {
                  if ($currentRouteName === $submenu->slug) {
                      $activeClass = 'active open';
                  }
              }
          }
        @endphp

        {{-- Main Menu --}}
        <li class="menu-item {{ $activeClass }}">
          <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
             class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
             @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>
            @isset($menu->icon)
              <i class="{{ $menu->icon }}"></i>
            @endisset
            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
            @isset($menu->badge)
              <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
            @endisset
          </a>

          {{-- Submenu --}}
          @isset($menu->submenu)
            <ul class="menu-sub">
              @foreach ($menu->submenu as $submenu)
                @if (isset($submenu->permission) && !auth()->user()->can($submenu->permission))
                  @continue
                @endif

                <li class="menu-item {{ $currentRouteName === $submenu->slug ? 'active' : '' }}">
                  <a href="{{ url($submenu->url) }}" class="menu-link">
                    @isset($submenu->icon)
                      <i class="{{ $submenu->icon }}"></i>
                    @endisset
                    <div>{{ __($submenu->name) }}</div>
                  </a>
                </li>
              @endforeach
            </ul>
          @endisset
        </li>
      @endif
    @endforeach
  </ul>
</aside>
