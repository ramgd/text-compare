@extends('layouts.app')

@section('content')

<div class="boxes">

<textarea id="text1" aria-label="Original text" placeholder="Original text…" spellcheck="false"></textarea>

<textarea id="text2" aria-label="Modified text" placeholder="Modified text…" spellcheck="false"></textarea>

</div>

<div class="buttons">

<button type="button" class="switch" onclick="tcSwitchText()">Switch</button>

<button type="button" class="compare" onclick="tcCompare()">Compare</button>

<button type="button" class="clear" onclick="tcClearAll()">Clear</button>

</div>

<div id="result" class="result" style="display:none" role="region" aria-label="Comparison result" aria-live="polite">

<div class="diff-container">

<div id="left" class="diff-box" tabindex="0" role="group" aria-label="Original text differences"></div>

<div class="nav-strip" role="group" aria-label="Jump between differences">

<button type="button" onclick="tcFirst()" aria-label="First difference" title="First difference">⏮</button>
<button type="button" onclick="tcPrev()" aria-label="Previous difference" title="Previous difference">⬆</button>
<button type="button" onclick="tcNext()" aria-label="Next difference" title="Next difference">⬇</button>
<button type="button" onclick="tcLast()" aria-label="Last difference" title="Last difference">⏭</button>

</div>

<div id="right" class="diff-box" tabindex="0" role="group" aria-label="Modified text differences"></div>

</div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/diff_match_patch/20121119/diff_match_patch.js"></script>
<script src="{{ asset('js/compare.js') }}"></script>
@endpush
