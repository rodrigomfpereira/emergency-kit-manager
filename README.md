# Full-Stack Monorepo (Laravel + Svelte)

Full-stack monorepo project containing a backend built with Laravel and a frontend built with Svelte. This setup allows both applications to be developed and maintained within a single repository while remaining fully independent.

> **Note:** This project is intended for development purposes. Backend and frontend are separated but managed under a single Git repository (monorepo architecture).

This project demonstrates a modern full-stack architecture with a REST API backend and a reactive frontend consuming it.

## Features

- **Monorepo Structure:** Single repository containing both backend and frontend applications.
- **Backend API (Laravel):** RESTful API built with Laravel.
- **Frontend (Svelte):** Modern reactive SPA frontend using Svelte and Vite.
- **Independent Development:** Both applications can be run and developed separately.
- **Separation of Concerns:** Clear division between backend and frontend responsibilities.
- **API-based Communication:** Frontend communicates with backend via HTTP requests.

## Project Structure

**backend/** : Laravel backend (API)
**frontend/** : Svelte frontend (UI)

## Requirements

### Backend
- PHP 8+
- Composer
- Laravel dependencies

### Frontend
- Node.js 16+
- npm

## Installation

### Backend (Laravel)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
> **Note:** Backend runs at: http://127.0.0.1:8000

### Frontend (Svelte)

```bash
cd frontend
npm install
npm run dev
```
> **Note:** Frontend runs at: http://localhost:5173

## API Communication

The frontend communicates with the backend via REST API requests.

**Base API URL:** http://127.0.0.1:8000/api

Make sure CORS is properly configured in the Laravel backend if the frontend is running on a different port.

## API Communication

Run backend and frontend in separate terminals.
**Backend:**
```bash
cd backend
php artisan serve
```
**Frontend:**
```bash
cd frontend
npm run dev
```

> **Note:** This repository follows a monorepo architecture for simplicity, intended for local development and learning purposes.