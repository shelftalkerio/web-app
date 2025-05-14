# Run automatically
node updateEnv.js - Answer the questions to update the .env file
node runDockerApp.js - Add the images after updating the .env file

# Stop manually

node stopDockerApp.js - Stop and Remove the docker images

# Run manually

docker-compose --project-name project_env -f docker-compose-{type}.yml up --build      

(or if you want to detach the running process: Not show all the processing running in the terminal)

docker-compose --project-name project_env -f docker-compose-{type}.yml up --build -d

# Set up backend - Laravel

docker-compose --project-name project_env -f docker-compose-laravel.yml run --rm composer install --no-scripts --ignore-platform-reqs &&
docker-compose --project-name project_env -f docker-compose-laravel.yml run --rm artisan key:generate &&
docker-compose --project-name project_env -f docker-compose-laravel.yml run --rm artisan migrate:fresh

# Artisan - Laravel

docker-compose --project-name project_env -f docker-compose-laravel.yml run --rm artisan -----

# Composer - Laravel

docker-compose --project-name project_env -f docker-compose-laravel.yml run --rm composer ----- --no-scripts --ignore-platform-reqs

# PHPUnit Test - Laravel

docker-compose --project-name project_env -f docker-compose-laravel.yml run --rm phpunit ----

# JEST Test - Frontend

docker-compose --project-name project_env -f docker-compose-frontend.yml run --rm jest ----

# Restart docker

sudo service docker restart

# Clear out volumes

docker system prune --all --volumes -f

# Get the log information about a container/image

docker logs <id>

# SSH into docker container

docker exec -it <name> ash