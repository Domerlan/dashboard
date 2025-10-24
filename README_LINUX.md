# Установка на Linux (Ubuntu/Debian)

Ниже — краткое руководство по установке проекта на чистый сервер Linux. Команды приведены для Ubuntu 22.04/24.04.

## 1) Зависимости

- Git, Nginx, PHP (8.2+), Composer, Node.js LTS (18/20), MariaDB/MySQL
- PHP‑расширения: mbstring, curl, openssl, pdo_mysql, zip, fileinfo, bcmath, intl

```bash
sudo apt update
sudo apt install -y nginx git curl unzip software-properties-common \
  php-fpm php-cli php-mbstring php-curl php-xml php-zip php-intl php-bcmath php-gd php-mysql
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
sudo apt install -y mariadb-server mariadb-client
```

## 2) База данных

```sql
CREATE DATABASE courier_plus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'courier_user'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON courier_plus.* TO 'courier_user'@'localhost';
FLUSH PRIVILEGES;
```

## 3) Деплой кода

```bash
sudo mkdir -p /var/www && cd /var/www
sudo git clone https://github.com/Domerlan/dashboard.git courier-plus
cd courier-plus
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
cp .env.example .env
php artisan key:generate
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

## 4) Nginx + PHP-FPM (пример)

Файл `/etc/nginx/sites-available/courier-plus`:

```nginx
server {
    listen 80;
    server_name _;

    root /var/www/courier-plus/public;
    index index.php index.html;

    location / { try_files $uri $uri/ /index.php?$query_string; }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; # проверьте версию PHP
    }

    location ~* \.(png|jpg|jpeg|gif|svg|ico|css|js|woff2?)$ {
        expires 7d;
        access_log off;
    }
}
```

Активируйте сайт и перезапустите службы:

```bash
sudo ln -s /etc/nginx/sites-available/courier-plus /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
sudo systemctl enable --now php8.2-fpm
```

## 5) Планировщик/очереди (опционально)

Cron:
```bash
sudo crontab -u www-data -e
# * * * * * php /var/www/courier-plus/artisan schedule:run >> /dev/null 2>&1
```

Очередь (systemd unit `/etc/systemd/system/courier-queue.service`):
```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/courier-plus/artisan queue:work --queue=default --sleep=3 --tries=3 --timeout=90

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now courier-queue
```

## 6) Быстрый запуск (Dev)

```bash
git clone https://github.com/Domerlan/dashboard.git
cd dashboard
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve & npm run dev
```
