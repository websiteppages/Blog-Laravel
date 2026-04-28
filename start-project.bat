@echo off

echo Opening VS Code...
start "" code .

echo Starting Laravel server...
start cmd /k php artisan serve --port=8047

echo Starting Vite dev server...
start cmd /k npm run dev

exit