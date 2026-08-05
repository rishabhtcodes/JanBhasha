# ──────────────────────────────────────────────────────────────────────────────
# Stage 1: Node.js asset builder
# Build CSS/JS assets in a separate stage so the final image stays lean
# ──────────────────────────────────────────────────────────────────────────────
FROM node:20-alpine AS node_builder

WORKDIR /app

# Copy only package files first for layer-caching npm install
COPY package.json package-lock.json ./
RUN npm ci --prefer-offline

# Copy source files needed for the Vite build
COPY resources/ resources/
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY public/ public/

# Compile production assets
RUN npm run build

# ──────────────────────────────────────────────────────────────────────────────
# Stage 2: PHP / Apache runtime
# ──────────────────────────────────────────────────────────────────────────────
FROM php:8.2-apache

# ── System dependencies ────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    pkg-config \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# ── PHP extensions ─────────────────────────────────────────────────────────
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql bcmath opcache

# ── MongoDB PHP extension (pinned stable release via install-php-extensions) ──
# Pinning to a specific release tag prevents breakage if upstream changes
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/download/2.7.12/install-php-extensions \
    /usr/local/bin/install-php-extensions
# ext-mongodb 2.x is required by mongodb/mongodb ^2.3 and mongodb/laravel-mongodb ^1.21|^2
RUN install-php-extensions mongodb-2.3.1

# ── Apache configuration ────────────────────────────────────────────────────
RUN a2enmod rewrite headers

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
      /etc/apache2/sites-available/*.conf \
      /etc/apache2/apache2.conf \
      /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides (needed for Laravel routing)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# ── OPcache configuration ───────────────────────────────────────────────────
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

# ── Composer (pinned to v2) ─────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── PHP dependencies ────────────────────────────────────────────────────────
# Copy composer files first for layer-caching
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --optimize-autoloader

# ── Application source ──────────────────────────────────────────────────────
COPY . .

# ── Bring in pre-built frontend assets from Stage 1 ────────────────────────
COPY --from=node_builder /app/public/build public/build

# ── Re-run autoloader with app files present ────────────────────────────────
RUN composer dump-autoload --optimize --no-scripts

# ── Permissions ─────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ── Startup script ──────────────────────────────────────────────────────────
# Run Laravel boot tasks at container start (not build time) so env vars are available
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
