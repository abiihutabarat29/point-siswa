@props(['route', 'name', 'label'])
<li class="menu-item {{ request()->segment(1) == $name ? 'active' : '' }}">
    <a href="{{ $route }}" class="menu-link">
        <div data-i18n="Basic" class="text-capitalize">{{ $label }}</div>
    </a>
</li>
