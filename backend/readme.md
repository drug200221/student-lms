 # Разработка
1. В phpStorm указать версию php = **7.2**
2. Добавить watcher  `codegenerator.phar controller:routes develompent/app.php .` на контроллеры `file[student-module]:src/*Module/Controllers//*Controller.php`
3. Создать БД и запустить **миграции**:
    ```sql
    CREATE DATABASE abiturient CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci`;
    ```

    ### Миграции
    #### Применить (обновление):
    ```shell
    MIGRATION_ACTION=up docker compose --profile migrations run migrations
    ```
    #### Откатить:
    ```shell
    MIGRATION_ACTION=down docker compose --profile migrations run migrations
    ```
    #### Создать новую:
    ```shell
    docker compose --profile migrations run --rm -it -u$(id -u) migrations ./vendor/bin/yii-db-migration migrate:create NewTable
    ```

## Папка `developent/bin`
1. `executer.sh` - выполнит в запущенном контейнере (docker-compose exec)
2. `runner.sh` - запустить команду в новом контейнере (docker-compose run)

### Устареет
#### Импортировать права
```shell
./backend/development/bin/executer.sh ./vendor/bin/sc-cli.php acl:import vendor/0x3a/student-common/acl/permissions.acl development/app.php
```
#### Создать пользователя
```shell
./backend/development/bin/executer.sh ./vendor/bin/sc-cli.php user:add-admin development/app.php
```
