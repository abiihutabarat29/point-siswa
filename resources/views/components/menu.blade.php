@props(['route', 'name', 'label', 'icon'])
<li class="menu-item {{ request()->segment(1) == $name ? 'active' : '' }}">
    <a href="{{ $route }}" class="menu-link">
        <i class="menu-icon tf-icons bx {{ $icon }}"></i>
        <div data-i18n="Basic" class="text-capitalize">{{ $label }}</div>
    </a>
</li>
