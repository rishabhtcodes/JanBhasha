@echo off
echo ========================================================
echo   🚀 Starting JanBhasha (Laravel Backend + React Frontend)
echo ========================================================
echo.
echo Starting Laravel API on http://localhost:8000
echo Starting React SPA on http://localhost:3000
echo.

npx concurrently -c "#818cf8,#c084fc" "cd backend && php artisan serve --port=8000" "cd frontend && npm run dev" --names="Laravel-API,React-Vite"
