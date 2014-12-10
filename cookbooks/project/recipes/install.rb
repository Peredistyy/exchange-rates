execute "cd /var/www/application/ && php composer.phar install"
execute "cd /var/www/application/bin && ./doctrine orm:schema-tool:update --force"
execute "cd /var/www/application/bin && ./doctrine orm:generate:proxies"
execute "/var/www/application/bin/currency load"