@extends('admin.layout')

@section('title', 'Edit Endpoint #'.$endpoint->id)

@section('content')
<style>
  /* Make the central content 90% wide */
  .content {
    width: 90% !important; /* or whatever measure suits your layout */
    margin-left: auto;
    margin-right: auto;
  }
  /* Adjust the CodeMirror editor to occupy enough vertical space */
  .CodeMirror {
    height: 300px; /* example default height */
    border: 1px solid #495057;
    border-radius: 4px;
    background-color: #343a40;
    color: #fff;
  }
</style>

<h2>Edit Endpoint #{{ $endpoint->id }}</h2>

<form method="POST" action="../{{ $endpoint->id }}?_method=PUT">
  <div class="mb-3">
    <label class="form-label">Path</label>
    <input class="form-control" name="path" value="{{ $endpoint->path }}" required />
  </div>
  <div class="mb-3">
    <label class="form-label">Method</label>
    <input class="form-control" name="method" value="{{ $endpoint->method }}" required />
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" name="is_active" @if($endpoint->is_active) checked @endif />
    <label class="form-check-label">Active?</label>
  </div>
  <div class="mb-3">
    <label class="form-label">Params JSON</label>
    <textarea class="form-control" name="params_json" rows="2">{{ $endpoint->params_json }}</textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Response JSON</label>
    <textarea class="form-control" name="response_json" rows="2">{{ $endpoint->response_json }}</textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Response Body</label>
    <!-- Actual field to be submitted -->
    <textarea id="responseBodyField" class="form-control d-none" name="response_body" rows="4">{{ $endpoint->response_body }}</textarea>

    <!-- CodeMirror container -->
    <div id="responseBodyEditor"></div>
  </div>

  <div class="mb-3">
    <label class="form-label">Status Code</label>
    <input class="form-control" name="status_code" value="{{ $endpoint->status_code ?? '200' }}" />
  </div>
  <div class="mb-3">
    <label class="form-label">Status Message</label>
    <input class="form-control" name="status_message" value="{{ $endpoint->status_message }}" />
  </div>
  <div class="mb-3">
    <label class="form-label">Headers JSON</label>
    <textarea class="form-control" name="headers_json" rows="2">{{ $endpoint->headers_json }}</textarea>
  </div>
  <button class="btn btn-primary" type="submit" onclick="copyEditorContent()">Save</button>
</form>

<!-- CodeMirror CSS/JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/codemirror.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/codemirror.min.js"></script>

<!-- Some modes for highlighting (JSON, XML, HTML, JS, etc.) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/css/css.min.js"></script>

<!-- 
  Optionally load more add-ons (lint, search, brackets matching, etc.) if desired.
  https://cdnjs.com/libraries/codemirror
-->

<script>
  // Initialize CodeMirror
  const responseBodyField = document.getElementById('responseBodyField');
  const responseBodyEditor = CodeMirror(document.getElementById('responseBodyEditor'), {
    value: responseBodyField.value,
    lineNumbers: true,
    mode: 'javascript', // default to JS or 'htmlmixed', etc.
    theme: 'default'    // you could also load and set a dark theme
  });

  // On Save button click, we copy the editor content back into the hidden field
  function copyEditorContent() {
    responseBodyField.value = responseBodyEditor.getValue();
  }
</script>
@endsection
