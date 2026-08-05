Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "  🚀 Starting JanBhasha (Laravel Backend + React Frontend)" -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Starting Laravel API on http://localhost:8000" -ForegroundColor Yellow
Write-Host "Starting React SPA on http://localhost:3000" -ForegroundColor Green
Write-Host ""

npx concurrently -c "#818cf8,#c084fc" "cd backend; php artisan serve --port=8000" "cd frontend; npm run dev" --names="Laravel-API","React-Vite"
