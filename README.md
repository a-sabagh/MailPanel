# MailPanel

MailPanel is a web‑based email administration and management panel,
designed for easy deployment and configuration.\
It allows you to manage contacts, users, send rich‑text emails, and more
--- all packaged in a Docker‑friendly environment.

## Features

-   Web‑based control panel for email operations\
-   User login & authentication\
-   Contact list / address book management\
-   Rich‑text email editor\
-   MySQL database backend\
-   Fully containerised setup via Docker & Docker Compose\
-   Installer / setup workflow for first‑time deployment

## Getting Started

### Clone the Repository

``` bash
git clone https://github.com/a-sabagh/MailPanel.git
cd MailPanel
```

### Run with Docker Compose

``` bash
cp .env.example .env
```


``` bash
docker compose up -d
```

### Access the Application

-   Web UI: http://localhost:8088\
-   Database: 127.0.0.1:3308

## Configuration

The application uses environment variables defined inside
docker-compose.yaml.\
On startup, a configuration file is generated automatically.

## Project Structure

    MailPanel/
    ├─ app/
    ├─ Dockerfile
    ├─ docker-compose.yaml
    ├─ entrypoint.sh
    └─ README.md

## License

MIT License (or update as appropriate)
