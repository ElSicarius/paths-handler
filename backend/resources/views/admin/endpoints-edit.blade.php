@extends('admin.layout')

@section('title', 'Edit Endpoint #'.$endpoint->id)

@section('content')
<style>
  /* Make the central content 90% wide */
  .content {
    width: 90% !important; /* or any measure that suits your layout */
    margin-left: auto;
    margin-right: auto;
  }
  /* Adjust the CodeMirror editor to occupy enough vertical space */
  .CodeMirror {
    height: 300px;
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

  <!-- RESPONSE BODY with CodeMirror -->
  <div class="mb-3">
    <label class="form-label">Response Body</label>
    <!-- Hidden field that will be submitted -->
    <textarea id="responseBodyField" class="form-control d-none" name="response_body" rows="4">
      {{ $endpoint->response_body }}
    </textarea>

    <!-- CodeMirror container for response body -->
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

  <!-- HEADERS JSON with CodeMirror -->
  <div class="mb-3">
    <label class="form-label">Headers JSON</label>
    <!-- Hidden field that will be submitted -->
    <textarea id="headersField" class="form-control d-none" name="headers_json" rows="2">
@if(!empty($endpoint->headers_json))
{{ $endpoint->headers_json }}
@endif
</textarea>

    <!-- CodeMirror container for headers -->
    <div id="headersEditor"></div>
  </div>

  <button class="btn btn-primary" type="submit" onclick="copyEditorContent()">Save</button>
</form>

<!-- CodeMirror CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/codemirror.min.css" />

<!-- CodeMirror Core JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/codemirror.min.js"></script>

<!-- CodeMirror modes (JSON, JS, XML, HTML, etc.) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/css/css.min.js"></script>

<script>
  // --- RESPONSE BODY EDITOR ---
  const responseBodyField = document.getElementById('responseBodyField');
  const responseBodyEditor = CodeMirror(document.getElementById('responseBodyEditor'), {
    value: responseBodyField.value.trim(),
    lineNumbers: true,
    mode: 'javascript', // or 'htmlmixed', 'xml', 'plaintext', etc.
    theme: 'default'
  });

  // --- HEADERS EDITOR ---
  const headersField = document.getElementById('headersField');
  // If headers JSON is empty in DB, provide a default
  let defaultHeaders = `{
  "Content-Type": "application/json",
  "Access-Control-Allow-Origin": "*"
}`;

  let initialHeadersValue = headersField.value.trim() || defaultHeaders;

  const headersEditor = CodeMirror(document.getElementById('headersEditor'), {
    value: initialHeadersValue,
    lineNumbers: true,
    mode: 'javascript',
    theme: 'default'
  });

  // On Save, copy editors' contents into the hidden fields
  function copyEditorContent() {
    responseBodyField.value = responseBodyEditor.getValue();
    headersField.value = headersEditor.getValue();
  }
</script>
@endsection
