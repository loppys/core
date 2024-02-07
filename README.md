# Немного информации:
- на код ревью отдавать loppysCR (там стоят уведомления)
- для веток develop и master создавать ветки feature/.. и hotfix/..
- поддежка идёт только по последним 3м минорным версиям (правки и новвоведения в старые версии приниматься по-прежнему будут)

# Документация

https://doc.vengine.ru/

# Установка

composer.json
```
{
    "scripts": {
        "post-update-cmd": [
            "php vendor/vengine/core/install.php"
        ]
    },
    "require": {
        "vengine/core": "*"
    }
}

```

`composer require vengine/core` - если нужен только функционал
