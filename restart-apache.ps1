# Script per riavviare Apache in Laragon
# Questo script termina e riavvia Apache per applicare le nuove configurazioni

Write-Host "🔄 Riavvio Apache..." -ForegroundColor Cyan

# Termina i processi Apache esistenti
Get-Process httpd -ErrorAction SilentlyContinue | Stop-Process -Force
Start-Sleep -Seconds 2

# Avvia Apache
$apachePath = "C:\laragon\bin\apache\httpd-2.4.62-240904-win64-VS17\bin\httpd.exe"
if (Test-Path $apachePath) {
    Start-Process $apachePath -WindowStyle Hidden
    Write-Host "✅ Apache riavviato con successo!" -ForegroundColor Green
}
else {
    Write-Host "❌ Apache non trovato in: $apachePath" -ForegroundColor Red
    Write-Host "ℹ️  Riavvia Apache manualmente da Laragon" -ForegroundColor Yellow
}

Write-Host "`nPremi un tasto per chiudere..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
