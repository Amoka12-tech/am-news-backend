# Project Name: News Aggregator

This project is a News Aggregator application that fetches and displays news articles from multiple APIs, including NewsAPI, The Guardian, and The New York Times. It is fully Dockerized to simplify setup and deployment.

## Prerequisites

- Docker: Ensure you have Docker installed on your machine.
- Docker Compose: Verify that Docker Compose is available.

## Project Structure

- **Backend**: Laravel-based backend application.
- **Docker Configuration**: Docker and Docker Compose files for easy setup.

## Installation and Setup

Follow these steps to set up and run the project:

### 1. Clone the Repository

```bash
git clone https://github.com/Amoka12-tech/am-news-backend.git
cd am-news-backend
```

### 2. Environment Configuration

#### Backend

1. Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

2. Update the `.env` file with the necessary API keys and configuration:

   ```env
   NEWS_API_KEY=7dc2117ed4dc4e128530cc64d477da39
   NEWS_API_BASE_URL=https://newsapi.org/v2
   GUARDIAN_API_KEY=91385ece-f297-44f0-8115-8a1d87411712
   NYT_API_KEY=VeGCU3Px9G7qgh4zmFXohki1AkN18n4A
   NYT_API_SECRET=IixtE8geqHS177Rr
   APP_URL=http://localhost
   ``

3. Install dependencies (optional if not using Docker for dependency management):

   ```bash
   composer install
   ```

4. Start runing project on Docker

    ```bash
    docker-compose up --build
    ```
5. Run migeration for docker

    ```bash
    docker-compose exec backend php artisan migrate

6. Populate News api
    ```bash
    docker-compose exec backend php artisan news:scrape

## Testing the Setup

- **Backend**: Use Postman or any HTTP client to test the API endpoints.

## Useful Docker Commands

- Stop all services:

  ```bash
  docker-compose down
  ```

- Rebuild containers:

  ```bash
  docker-compose up --build
  ```

- View container logs:

  ```bash
  docker logs <container-name>
  ```

