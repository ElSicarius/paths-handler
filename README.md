# PathHandler

**PathHandler** is a self-hosted tool designed for penetration testers or security researchers who need to rapidly set up arbitrary HTTP endpoints with custom responses. For instance, if you have a SSRF scenario, you can configure this server so that when a vulnerable application requests a path (e.g., `/v1/test`), you can serve custom JSON, text, or binary data — along with custom headers and status codes.

## Key Features

1. **Dynamic Endpoints**: Create and manage any path (e.g. `/v1/test`, `/api/whatever`) with HTTP methods (`GET`, `POST`, `PUT`, `DELETE`).  
2. **Custom Responses**: Control the status code, body, and headers. You can serve JSON, plain text, or arbitrary content.  
3. **Admin Interface**: A protected admin panel (with a random or custom path) lets you create, edit, or delete these endpoints.  
4. **Logging**: All incoming requests (except those to the admin panel) can be logged, for easier debugging or demonstration.  
5. **Dockerized**: The entire stack (PHP Lumen, Caddy, PostgreSQL) is set up via Docker Compose, making it easy to deploy.

---

## Stack Overview

- **Reverse Proxy**: [Caddy](https://caddyserver.com/)  
  - Handles HTTP/2, HTTPS, and automatic TLS.  
- **Backend**: [PHP Lumen](https://lumen.laravel.com/)  
  - Lightweight Laravel-based micro-framework handling dynamic routes and the admin panel.  
- **Database**: [PostgreSQL](https://www.postgresql.org/)  
  - Stores endpoints, logs, etc.  
- **Docker**:  
  - `docker-compose.yml` orchestrates the above services.

---

## Setup & Configuration

/!\ You need the latest version of `docker compose` (not `docker-compose` but `docker compose` :) ) /!\

### 1) Edit Docker Credentials

In **`docker-compose.yml`**, you will find environment variables for the PostgreSQL container, for example:

```yaml
db:
  image: postgres:15
  container_name: path-handler-db
  environment:
    - POSTGRES_USER=myuser
    - POSTGRES_PASSWORD=mysecretpass
    - POSTGRES_DB=pathhandler
```

Change `POSTGRES_USER`, `POSTGRES_PASSWORD` as desired.

### 2) Edit `backend/.env`

In **`backend/.env`**, update:

```
DB_HOST=db
DB_PORT=5432
DB_DATABASE=pathhandler
DB_USERNAME=myuser
DB_PASSWORD=mysecretpass

ADMIN_PATH=admin-8d6544cfc5f8ec0cd5edc4415e9e47d9
```

- Make sure `DB_USERNAME` and `DB_PASSWORD` match the values you set in `docker-compose.yml`.  
- `ADMIN_PATH` defines the admin panel’s path segment. For example, if `ADMIN_PATH=admin-xxxx`, then your admin URL is `https://<host>/admin-xxxx/`.

### 3) Update Caddy config

In **`caddy/Caddyfile`**, update `yourdomain` with your actual domain name, or remove the 443 port if you only have an IP (dirty solution):

```
:80 {
    reverse_proxy backend:8000
}

yourdomain:443{
    tls admin@yourdomain
    reverse_proxy backend:8000
}
```

### 4) Build & Start

From the root folder:

```bash
docker compose build
docker compose up -d
```

This will spin up three containers: `caddy`, `backend`, and `db`.

### 5) Run Migrations

Run the Lumen migrations to create the necessary tables:

```bash
docker compose exec backend php artisan migrate
```

### 6) Access the Admin Panel

Go to `https://<your_host>/<ADMIN_PATH>/` (e.g., `https://paths.p1s.me/admin-yourrandomstring`)  
- Use the credentials-you've changed-on `backend/.env`.  
- From here, create new endpoints (e.g., `/v1/test`) with a custom method, status code, headers, body, etc.

---

## Demo

### Setup UI

When editing an endpoint, you can configure everything from path, method, headers, status code, to the response body. Below is a screenshot of the admin interface for an endpoint `/v1/test`:

![Demo Setup UI](/img/demo_setup.png)

### Example Response

Here is a sample `curl` request and the returned response:

![Demo Response](/img/demo_response.png)

Notice the custom headers with the red arrow.

---

## Usage Tips

- **Multiple Methods**: If you want the same path to handle multiple methods (GET, POST, PUT, etc.), create separate endpoints or rely on catch-all logic.  
- **Headers**: You can set custom headers like `Content-Type: text/plain`, or `Access-Control-Allow-Origin: *` to handle CORS easily.  
- **Security**: This tool is intended for local testing, demo, or controlled lab environments. Use caution before deploying it to a public server.

---

## Contributing & License

Feel free to customize or extend the project for your SSRF, LFI, or other pentest scenarios. PRs welcomed. License is [MIT](LICENSE).