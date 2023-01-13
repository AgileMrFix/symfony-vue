### 1. Build containers
    docker-compose up -d

### 2.Go into container:
    docker-compose exec kiwi-symfony bash  

### 3. Run commands:
#### Install backend dependencies: 
    composer install
#### DB Execute a migration
    php bin/console doctrine:migrations:migrate
#### Make fake data
    php bin/console doctrine:fixtures:load

### The application is ready to use: 
    http://localhost:7171/
    