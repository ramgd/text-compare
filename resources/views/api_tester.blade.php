@extends('layouts.app')

@section('content')

<div class="api-container">

    <!-- TOP BAR -->
    <div class="api-top">
        <select id="method">
            <option>GET</option>
            <option>POST</option>
            <option>PUT</option>
            <option>DELETE</option>
        </select>

        <input type="text" id="url" placeholder="Enter API URL..." />

        <button onclick="sendRequest()">Send</button>
        <button onclick="clearAll()">Clear</button>
    </div>

    <!-- PARAMS + HEADERS -->
    <div class="api-tabs">

        <div class="tab">
            <h4>Query Params</h4>
            <div id="params"></div>
            <button onclick="addParam()">+ Add Param</button>
        </div>

        <div class="tab">
            <h4>Headers</h4>
            <div id="headers"></div>
            <button onclick="addHeader()">+ Add Header</button>
        </div>

    </div>

    <!-- BODY + RESPONSE -->
    <div class="api-body">

        <div class="editor-box">
            <div class="editor-header">Request Body</div>
            <textarea id="body"></textarea>
        </div>

        <div class="editor-box">
            <div class="editor-header">
                Response
                <button onclick="copyResponse()">Copy</button>
            </div>
            <div id="response" class="json-viewer"></div>
        </div>

    </div>

    <!-- STATUS -->
    <div class="api-status">
        <span id="status"></span>
        <span id="time"></span>
    </div>

</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/api.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/api.js') }}"></script>
@endpush
