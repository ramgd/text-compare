@php
    // The compare tool is the home page and the dashboard is the tool hub;
    // neither is "inside" a tool, so neither needs a way back out.
    $currentPath = trim(request()->path(), '/');
    $isHubPage   = in_array($currentPath, ['', 'dashboard'], true);
@endphp

@unless ($isHubPage)
    <nav class="tool-back" aria-label="Breadcrumb">
        <a href="{{ url('/dashboard') }}" class="tool-back-link">
            <span class="tool-back-arrow" aria-hidden="true">&#8592;</span>
            <span>Back to Tools</span>
        </a>
    </nav>
@endunless
