@extends('admin.layout')

@section('title', 'Endpoints Management')

@section('content')
<h1>Endpoints</h1>

<table class="table table-dark table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Path</th>
      <th>Method</th>
      <th>Active</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>
    @foreach($endpoints as $ep)
      <tr>
        <td>{{ $ep->id }}</td>
        <td>{{ $ep->path }}</td>
        <td>{{ $ep->method }}</td>
        <td>{!! $ep->is_active ? '&#10003;' : '&#10007;' !!}</td>
        <td>
          <a class="btn btn-primary" href="endpoints/{{ $ep->id }}/edit">Edit</a>
        </td>
        <td>
          <form method="POST" action="endpoints/{{ $ep->id }}?_method=DELETE" style="display:inline;">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<hr>

<form method="POST" action="">
  <h5>Create New Endpoint</h5>
  <div class="mb-1">
    <label>Path</label>
    <input class="form-control" name="path" placeholder="/v1/test" required />
  </div>
  <div class="mb-1">
    <label>Method</label>
    <input class="form-control" name="method" value="GET" required />
  </div>
  <div class="mb-1">
    <label>Active?</label>
    <input type="checkbox" name="is_active" checked />
  </div>
  <button class="btn btn-success" type="submit">Create</button>
</form>
@endsection
