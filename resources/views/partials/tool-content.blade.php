@php
    use App\Support\ToolRegistry;

    /* Resolve the tool from the current path so a tool view only has to
       @include this partial - no per-page wiring. */
    $tool    = $tool ?? ToolRegistry::findByPath(request()->path());
    $content = $tool['content'] ?? null;
@endphp

@if ($content)
<section class="tool-doc" aria-label="About this tool">

    {{-- What it is --}}
    <div class="tool-doc-block">
        <h2>About {{ $tool['name'] }}</h2>
        <p>{{ $content['intro'] }}</p>

        @if (!empty($content['input']) || !empty($content['output']))
            <dl class="tool-io">
                @if (!empty($content['input']))
                    <dt>What it accepts</dt>
                    <dd>{{ $content['input'] }}</dd>
                @endif
                @if (!empty($content['output']))
                    <dt>What you get back</dt>
                    <dd>{{ $content['output'] }}</dd>
                @endif
            </dl>
        @endif
    </div>

    {{-- How to use --}}
    @if (!empty($content['steps']))
        <div class="tool-doc-block">
            <h2 id="how-to-use">How to use {{ $tool['name'] }}</h2>
            <ol class="tool-steps">
                @foreach ($content['steps'] as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    @include('partials.ads.slot', ['position' => 'in-content'])

    {{-- Features --}}
    @if (!empty($content['features']))
        <div class="tool-doc-block">
            <h2>Features</h2>
            <ul class="tool-features">
                @foreach ($content['features'] as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Longer explanatory sections (used where a tool needs real detail) --}}
    @if (!empty($content['sections']))
        @foreach ($content['sections'] as $section)
            <div class="tool-doc-block">
                <h2>{{ $section['heading'] }}</h2>
                <p>{{ $section['body'] }}</p>
            </div>
        @endforeach
    @endif

    {{-- Worked example --}}
    @if (!empty($content['example']))
        @php $example = $content['example']; @endphp
        <div class="tool-doc-block">
            <h2>Example</h2>
            <p>{{ $example['caption'] }}</p>
            <div class="tool-example">
                <div class="tool-example-col">
                    <h3>{{ $example['left_label'] }}</h3>
                    <pre>{{ $example['left'] }}</pre>
                </div>
                <div class="tool-example-col">
                    <h3>{{ $example['right_label'] }}</h3>
                    <pre>{{ $example['right'] }}</pre>
                </div>
            </div>
            @if (!empty($example['result']))
                <p class="tool-example-note">{{ $example['result'] }}</p>
            @endif
        </div>
    @endif

    {{-- FAQs. The FAQPage structured data below is emitted only when these
         questions are genuinely visible on the page. --}}
    @if (!empty($content['faqs']))
        <div class="tool-doc-block">
            <h2 id="faq">Frequently asked questions</h2>
            <div class="faq-list">
                @foreach ($content['faqs'] as $faq)
                    <details class="faq-item">
                        <summary>{{ $faq['q'] }}</summary>
                        <p>{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Related tools in the same category --}}
    @php
        $related = ToolRegistry::inCategory($tool['category'])
            ->reject(fn ($t) => $t['slug'] === $tool['slug'])
            ->take(4);
    @endphp
    @if ($related->isNotEmpty())
        <div class="tool-doc-block">
            <h2>Related tools</h2>
            <div class="related-tools">
                @foreach ($related as $item)
                    <a class="related-tool" href="{{ url($item['path']) }}">
                        <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
                        <span class="related-tool-name">{{ $item['name'] }}</span>
                        <span class="related-tool-desc">{{ $item['summary'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</section>

{{-- Structured data that mirrors what is actually rendered above. --}}
@push('structured-data')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'SoftwareApplication',
    'name' => $tool['name'],
    'applicationCategory' => 'UtilitiesApplication',
    'operatingSystem' => 'Any device with a modern web browser',
    'url' => rtrim(config('site.url'), '/') . $tool['path'],
    'description' => $tool['summary'],
    'offers' => [
        '@type' => 'Offer',
        'price' => '0',
        'priceCurrency' => 'USD',
    ],
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@if (!empty($content['faqs']))
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($content['faqs'])->map(fn ($faq) => [
        '@type' => 'Question',
        'name' => $faq['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
@endpush
@endif
