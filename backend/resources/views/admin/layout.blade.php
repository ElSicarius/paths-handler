<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: #212529;
      color: #f8f9fa;
    }
    .sidebar {
      background-color: #343a40;
      min-width: 200px;
      height: 100vh;
      padding: 1rem;
    }
    .sidebar a {
      color: #f8f9fa;
      display: block;
      padding: 0.5rem 0;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .content {
      margin-left: 210px;
      padding: 1rem;
    }
  </style>
</head>
<body>
@php
    $adminPath = env('ADMIN_PATH', 'admin');
@endphp

<div class="d-flex flex-row">
  <div class="sidebar">
    <h3>PathHandler</h3>
    <a href="@yield('home_link', '/'.$adminPath)">Home</a>
    <a href="@yield('endpoints_link', '/'.$adminPath.'/endpoints')">Endpoints</a>
    <a href="@yield('logs_link', '/'.$adminPath.'/logs')">Logs</a>
    <a href="@yield('logout_link', '/'.$adminPath.'/logout')">Logout</a>
  </div>
  <div class="content">
    @yield('content')
  </div>
</div>
</body>
</html>
