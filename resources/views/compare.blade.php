@extends('layouts.app')

@section('content')

<div class="boxes">

<textarea id="text1"></textarea>

<textarea id="text2"></textarea>

</div>

<div class="buttons">

<button class="switch" onclick="switchText()">Switch</button>

<button class="compare" onclick="compare()">Compare</button>
    
<button class="clear" onclick="clearText()">Clear</button>

</div>

<div id="result" class="result" style="display:none">

<div class="diff-container">

<div id="left" class="diff-box"></div>

<div class="nav-strip">

<button onclick="first()">⏮</button>
<button onclick="prev()">⬆</button>
<button onclick="next()">⬇</button>
<button onclick="last()">⏭</button>

</div>

<div id="right" class="diff-box"></div>

</div>

</div>

@endsection