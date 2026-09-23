@php
    use App\Support\ToolRegistry;

    $navCategories = ToolRegistry::categoriesWithTools();

    /* Work out which nav item should be highlighted. On a tool page that is
       the tool's category, which is why this is decided here rather than by
       the path-matching in common.js - a tool's URL is not its category's. */
    $currentPath = '/' . trim(request()->path(), '/');
    $currentTool = ToolRegistry::findByPath(request()->path());

    $activeNav = match (true) {
        $currentPath === '/'                    => '/',
        $currentPath === '/tools'               => '/tools',
        $currentPath === '/about'               => '/about',
        str_starts_with($currentPath, '/tools/') => $currentPath,
        $currentTool !== null                   => '/tools/' . ToolRegistry::category($currentTool['category'])['slug'],
        default                                 => null,
    };
@endphp

<header class="header">

    <!-- MOBILE MENU BUTTON (hidden on desktop via CSS) -->
    <button type="button"
            id="navToggle"
            class="header-btn nav-toggle"
            aria-controls="toolsNav"
            aria-expanded="false"
            aria-label="Open navigation menu">
        <span class="nav-toggle-bars" aria-hidden="true"></span>
    </button>

    <a class="header-title header-brand" href="{{ url('/') }}">{{ config('site.name') }}</a>

    <!-- DARK MODE ICON -->
    <button type="button"
            id="darkToggle"
            class="header-btn"
            onclick="toggleDarkMode()"
            aria-label="Toggle dark mode"
            title="Toggle dark mode">
        🌙
    </button>
</header>

<nav class="tools ultra-nav" id="toolsNav" aria-label="Main">
    <div class="nav-indicator" id="navIndicator" aria-hidden="true"></div>

    <button data-url="/" class="{{ $activeNav === '/' ? 'active' : '' }}"
            @if ($activeNav === '/') aria-current="page" @endif
            onclick="location.href='{{ url('/') }}'">Home</button>

    <button data-url="/tools" class="{{ $activeNav === '/tools' ? 'active' : '' }}"
            @if ($activeNav === '/tools') aria-current="page" @endif
            onclick="location.href='{{ url('/tools') }}'">All Tools</button>

    @foreach ($navCategories as $category)
        @php $catPath = '/tools/' . $category['slug']; @endphp
        <button data-url="{{ $catPath }}" class="{{ $activeNav === $catPath ? 'active' : '' }}"
                @if ($activeNav === $catPath) aria-current="page" @endif
                onclick="location.href='{{ url($catPath) }}'">{{ $category['name'] }}</button>
    @endforeach

    <button data-url="/about" class="{{ $activeNav === '/about' ? 'active' : '' }}"
            @if ($activeNav === '/about') aria-current="page" @endif
            onclick="location.href='{{ url('/about') }}'">About</button>
</nav>
