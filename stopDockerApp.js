const {
    runCommand,
    Docker
} = require('./commands.js');

const docker = new Docker();

require("dotenv").config();

const site = process.env.SITE;

async function isContainerRunning(containerName) {
    try {
        const containers = await docker.listContainers();
        return containers.some(container => 
            container.Names.includes(`/${containerName}`)
        );
    } catch (error) {
        console.error(`Error checking container ${containerName}:`, error);
        return false;
    }
}

async function checkAllContainers(containerNames) {
    console.log('\nStopping and removing containers...');
    for (const name of containerNames) {
        const running = await isContainerRunning(name);
        if(running) {
            runCommand(`docker stop ${name} && docker rm ${name}`)
        }
    }
}

// Docker containers
const containerNames = [
  `adminer_${site}`, 
  `backend_${site}`,
  `cron_${site}`, 
  `frontend_${site}`, 
  `mariadb_${site}`,
  `mongodb_${site}`,
  `mssql_${site}`,
  `mysql_${site}`,
  `nestjs_${site}`,
  `pgadmin_${site}`,
  `php_${site}`,
  `postgres_${site}`,
  `redis_${site}`,
  `strapi_${site}`,
  `mailpit_${site}`,
  `mailhog_${site}`,
  `mailtrap_${site}`,
  `playwright_${site}`,
  `playwright_headed_${site}`,
  `horizon_${site}`,

];

checkAllContainers(containerNames).then();