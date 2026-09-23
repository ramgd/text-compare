@php
    use App\Support\ToolRegistry;

    /* Registry-driven page header, used by tool views that had no heading of
       their own. Gives every tool page exactly one h1 that matches its title
       tag, plus a one-line description above the controls. */
    $headerTool = $headerTool ?? ToolRegistry::findByPath(request()->path());
@endphp

@if ($headerTool)
    <div class="page-head tool-page-head">
        <h1><i class="{{ $headerTool['icon'] }}" aria-hidden="true"></i> {{ $headerTool['name'] }}</h1>
        <p class="page-lead">{{ $headerTool['summary'] }}</p>
    </div>
@endif
