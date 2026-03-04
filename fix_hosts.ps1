$content = @"
127.0.0.1 localhost
::1 localhost
127.0.0.1 trovaveterinario.test
127.0.0.1 ecommerce.test
127.0.0.1 sistemacase.test
"@
$content | Set-Content -Path C:\Windows\System32\drivers\etc\hosts -Encoding ascii -Force
