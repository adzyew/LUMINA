# PowerShell wrapper for Laravel Sail / Docker Compose on Windows

param(
    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]]$SailArgs
)

# Ensure Sail user/group variables are populated on Windows
if (-not $env:WWWGROUP) {
    $env:WWWGROUP = "1000"
}
if (-not $env:WWWUSER) {
    $env:WWWUSER = "1000"
}

if (-not $SailArgs -or $SailArgs.Count -eq 0) {
    docker compose ps
    exit 0
}

$subcommand = $SailArgs[0]
$remaining = @()
if ($SailArgs.Count -gt 1) {
    $remaining = $SailArgs[1..($SailArgs.Count - 1)]
}

switch ($subcommand) {
    "up" {
        docker compose up $remaining
    }
    "down" {
        docker compose down $remaining
    }
    "build" {
        docker compose build $remaining
    }
    "restart" {
        docker compose restart $remaining
    }
    "ps" {
        docker compose ps $remaining
    }
    "logs" {
        docker compose logs $remaining
    }
    "artisan" {
        docker compose exec -u sail laravel.test php artisan $remaining
    }
    "composer" {
        docker compose exec -u sail laravel.test composer $remaining
    }
    "npm" {
        docker compose exec -u sail laravel.test npm $remaining
    }
    "node" {
        docker compose exec -u sail laravel.test node $remaining
    }
    "test" {
        docker compose exec -u sail laravel.test php artisan test $remaining
    }
    "bash" {
        docker compose exec -u sail laravel.test bash $remaining
    }
    "shell" {
        docker compose exec -u sail laravel.test bash $remaining
    }
    "tinker" {
        docker compose exec -u sail laravel.test php artisan tinker $remaining
    }
    "mysql" {
        docker compose exec -u sail mysql mysql -ularavel -ppassword lumina $remaining
    }
    default {
        docker compose exec -u sail laravel.test $SailArgs
    }
}
