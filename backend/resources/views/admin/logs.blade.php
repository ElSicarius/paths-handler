@extends('admin.layout')

@section('title', 'Logs - PathHandler Admin')

@section('content')
<h1>Logs</h1>

<form method="POST" action="logs/clear?_method=DELETE" style="display:inline;">
    <button class="btn btn-warning" type="submit">Clear all logs</button>
</form>

<div class="accordion" id="logsAccordion">
    
  @foreach($logs as $log)
    @php
      $preview = substr($log->raw_request, 0, 60).'...';
      $created = $log->created_at->format('Y-m-d H:i:s');
      $emoji = $log->matched ? '✅' : '❌';
    @endphp

    <div class="accordion-item bg-dark text-white">
      <h2 class="accordion-header" id="heading-{{ $log->id }}">
        <button class="accordion-button collapsed bg-dark text-white" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse-{{ $log->id }}"
                aria-expanded="false"
                aria-controls="collapse-{{ $log->id }}">
          #{{ $log->id }} - {{ $created }} - {{ $preview }}
        </button>
      </h2>
      <div id="collapse-{{ $log->id }}" class="accordion-collapse collapse"
           aria-labelledby="heading-{{ $log->id }}"
           data-bs-parent="#logsAccordion">
        <div class="accordion-body">
          <pre class="text-white">{{ $log->raw_request }}</pre>
        </div>
      </div>
    </div>
  @endforeach
</div>

@php
  $nbPages = (int) ceil($total / $perPage);
@endphp
@if($nbPages > 1)
  <nav>
    <ul class="pagination">
      @for($i = 1; $i <= $nbPages; $i++)
        <li class="page-item {{ $i === $page ? 'active' : '' }}">
          <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
        </li>
      @endfor
    </ul>
  </nav>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
