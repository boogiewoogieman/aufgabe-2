Getting started
===============

1. Start docker with database
```
docker-compose up -d
```

2. Create database
```
bin/console doctrine:schema:create
```

3. Install dependencies
```
composer install
yarn install
```

4. Build frontend
```
yarn dev
```

5. Start local server
```
symfony serve -d
```

6. Open frontend in browser

https://localhost:8000
