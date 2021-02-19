# Riešenie zadania BE Developera

## Inštalácia

1. Stiahnutie repozitáru `git clone https://github.com/ipinfil/riesenia-zadanie.git`.
2. Nainštalovanie potrebných balíkov `composer install`
3. Konfigurácia databázového spojenia v [app_local.example.php](config/app_local.example.php) a premenovanie súboru na `app_local.php`.
4. Spustenie [create_scriptu](create_script.sql) v databáze.
5. Spustenie CakePHP servera `bin/cake server`

*Pripravený [create_script](create_script.sql) je vytvorený pre Postgres databázu a podobne pripravená konfigurácia databázy v [app_local.example.php](config/app_local.example.php) je pre Postgres.*

