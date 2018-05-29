#backend

1. docker-compose up -d
2. docker-compose exec php-fpm bash
3. composer install
4. bin/console doc:schema:create

Route /data-import will import data from CSV file
servers/list and locations/list are the two api's implemented.

Will be running at http://localhost:8081/
