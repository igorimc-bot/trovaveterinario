# Script per aggiungere l'entry nel file hosts
# ESEGUIRE COME AMMINISTRATORE

$hostsPath = "C:\Windows\System32\drivers\etc\hosts"
$entry = "127.0.0.1 astegiudiziarie24.test"

# Controlla se l'entry esiste già
$hostsContent = Get-Content $hostsPath
if ($hostsContent -notcontains $entry) {
    Add-Content -Path $hostsPath -Value "`n$entry"
    Write-Host "✅ Entry aggiunta al file hosts: $entry" -ForegroundColor Green
} else {
    Write-Host "ℹ️  Entry già presente nel file hosts" -ForegroundColor Yellow
}

Write-Host "`nPremi un tasto per chiudere..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
