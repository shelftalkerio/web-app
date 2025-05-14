const {
  runCommand,
  readEnvFile,
  runInteractiveCommand,
    fs, path
} = require('./commands.js');


// Function to Update the .env file
function UpdateEnvFile(UpdatedVars) {
  const envVars = { ...readEnvFile(), ...UpdatedVars }; // Merge old & new vars
  const UpdatedEnvContent = Object.entries(envVars)
    .map(([key, value]) => `${key}=${value}`)
    .join('\n');

  fs.writeFileSync(envPath, UpdatedEnvContent.trim() + '\n', 'utf-8');
  console.log('.env file Updated successfully!');
}

function UpdateEnvVariables(envPath, variables) { // Read the .env file if it exists
  let env = fs.existsSync(envPath) ? fs.readFileSync(envPath, 'utf8') : '';

  // Convert file content into lines
  let envLines = env.split('\n');
  let keysUpdated = new Set();

  // Update existing keys
  envLines = envLines.map(line => {
    const [key] = line.split('=');
    if (variables[key]) {
      keysUpdated.add(key);
      return `${key}=${variables[key]}`;
    }
    return line;
  });

  // Add new keys that were not found in the file
  for (const [key, value] of Object.entries(variables)) {
    if (!keysUpdated.has(key)) {
      envLines.push(`${key}=${value}`);
    }
  }

  // Write back to the .env file
  fs.writeFileSync(envPath, envLines.join('\n'), 'utf8');
}

// Node modules in root
const nodeModules = path.join(__dirname, 'node_modules')
const envPath = path.join(__dirname, '.env')

// Run Docker Compose commands
async function runDockerCompose() {
  try {
    require('dotenv').config();

    const env = process.env.APP_ENV;
    const site = process.env.SITE;
    const backendTech = process.env.BACKEND
    const frontendTech = process.env.FRONTEND
    const cloneBackend = process.env.CLONE_BACKEND
    const cloneBackendLink = process.env.CLONE_BACKEND_LINK

    const cloneFrontendLink = process.env.CLONE_FRONTEND_LINK
    const database = process.env.DATABASE
    const headlessCMS = process.env.HEADLESS
    const nestPort = process.env.NESTJS_PORT
    const frontendPort = process.env.FRONTEND_PORT

    const siteProtocol = process.env.SITE_PROTOCOL
    const siteUrl = process.env.SITE_URL
    const databaseHost = process.env.DATABASE
    const databasePort = process.env.MYSQL_PORT
    const databaseName = process.env.DATABASE_NAME
    const databaseUser = process.env.DATABASE_USER
    const databasePassword = process.env.DATABASE_PASSWORD
    const mailServer = process.env.MAIL_SERVER

    const projectName = `${site}_${env}`
    const backend = './backend'
    const frontend = './frontend'
    const nestjs = './nestjs'
    const headless = './headless'
    const e2e = './e2e'

    // Check if Docker Compose is available
    console.log("Checking Docker Compose version...");
    await runCommand("docker-compose --version");

    //--  DATABASE --//
    if (database !== 'no') {

      console.log(`\nStart of processing database`);
      const composeDatabaseFilePath = `docker-compose-${database}.yml`;
      const composeAdminerFilePath = 'docker-compose-adminer.yml';
      if (!fs.existsSync('./database')) {
        console.log('Creating a database directory');
        await runCommand('mkdir database');
      }
      // Create a database and Admin panel
      console.log(`\nRunning docker compose for ${database}...`);
      await runCommand(`docker-compose --project-name ${projectName} -f ${composeDatabaseFilePath} up --build -d`);
      console.log('\nRunning docker compose for adminer...');
      await runCommand(`docker-compose --project-name ${projectName} -f ${composeAdminerFilePath} up --build -d`);      
    }
    //--  DATABASE--//

    //--  HEADLESS CMS --//
    if (headlessCMS !== 'no') {
      
      console.log(`\nStart of processing Headless CMS`);
      const composeDatabaseFilePath = `docker-compose-${headlessCMS}.yml`;
      const composeAdminerFilePath = 'docker-compose-adminer.yml';

      if (!fs.existsSync(headless)) {
        console.log('Creating a headless directory');
        await runCommand('mkdir headless');
      }

      // Create a Headless and Admin panel
      console.log(`\nRunning docker compose for ${database}...`);
      await runCommand(`docker-compose --project-name ${projectName} -f ${composeDatabaseFilePath} up --build -d`);
    }
    //--  HEADLESS CMS --//

    //--  BACKEND --//
    if (backendTech !== 'clone' && backendTech !== 'no') {
      const composeTechFilePath = `docker-compose-${backendTech}.yml`;

      if (backendTech === 'nestjs') {

        if (!fs.existsSync(nestjs)) {
          console.log('\nInstall nest cli');
          await runCommand('npm install -g @nestjs/cli');
          console.log('\nCreate a new nest application');
          await runInteractiveCommand(`nest`, ['new', `./${backendTech}`]);
          console.log('\nCopy and paste the dockerfile');
          await runCommand(`cp ./shared/Dockerfile_${backendTech} ./${backendTech}/Dockerfile`);
          // Define the path to package.json
          const packageJsonPath = path.join(__dirname, `${nestjs}/package.json`);

          // Read package.json
          const packageJson = JSON.parse(fs.readFileSync(packageJsonPath, 'utf8'));
          // Update the port for the running nest application
          packageJson.scripts = {
            ...packageJson.scripts,
            "start": `cross-env PORT=${nestPort} nest start`,
            "start:dev": `cross-env PORT=${nestPort} nest start --watch`
          };
          // Add cross-env
          packageJson.devDependencies = {
            ...packageJson.devDependencies,
            "cross-env": "^7.0.3",
          };

          // Write the changes back to package.json
          console.log('\nUpdating the package.json file');
          fs.writeFileSync(packageJsonPath, JSON.stringify(packageJson, null, 2), 'utf8');

        }
      }

      if (backendTech === 'laravel') {
        if (!fs.existsSync(backend)) {

          if (!fs.existsSync('./composer')) {
            console.log("\nCreating composer directory...");
            await runCommand('cp -R ./shared/composer ./composer');
          }
          if (!fs.existsSync('./cron')) {            
            console.log("\nCreating cron directory...");
            await runCommand('cp -R ./shared/cron ./cron');
          }
          if (!fs.existsSync('./nginx')) {
            // Copy and paste nginx directory (all files)
            console.log("\nCreating nginx directory...");
            await runCommand('cp -R ./shared/nginx ./nginx');
          }
          if (!fs.existsSync('./php')) {
            // Create a php directory
            console.log("\nCreating php directory...");
            await runCommand('cp -R ./shared/php ./php');
          }
          if (!fs.existsSync('./redis')) {// Create a redis directory
            console.log("\nCreating redis directory...");
            await runCommand('mkdir redis');
            // await runCommand('chown -R ${USER}:${USER} ./redis');
          }

          if (!fs.existsSync(backend)) {// Create a redis directory
            // Run docker compose
            console.log("\nInstall a new laravel application...");
            await runCommand(`docker-compose --project-name ${projectName} -f ${composeTechFilePath} run --rm composer create-project laravel/laravel .`);
          }

        }
      }

      console.log(`\nRun docker-compose for backend: ${backendTech}`);
      await runCommand(`docker-compose --project-name ${projectName} -f ${composeTechFilePath} up --build -d`); 
    }
    //--  BACKEND --//
    
    //--  CLONE BACKEND --//
    if (backendTech === 'clone') {

      console.log(`\nStart of processing cloned backend`);

      const clonedDirectory = cloneBackend === 'nestjs' ? './nestjs' : './backend'

      const composeTechFilePath = `docker-compose-${cloneBackend}.yml`;

      if (!fs.existsSync(clonedDirectory)) {
        
        console.log("\nCloning into directory...");
        await runCommand(`git clone ${cloneBackendLink} ${clonedDirectory}`);
        if (cloneBackend === 'nestjs') {
          console.log('\nCopy and paste the dockerfile');
          await runCommand(`cp ./shared/Dockerfile_${cloneBackend} ./${cloneBackend}/Dockerfile`);
        }
      }
      
      if (cloneBackend === 'laravel') {

        if (!fs.existsSync('./composer')) {
          console.log("\nCreating composer directory...");
          await runCommand('cp -R ./shared/composer ./composer');
        }
        if (!fs.existsSync('./cron')) {
          console.log("\nCreating cron directory...");
          await runCommand('cp -R ./shared/cron ./cron');

        }
        if (!fs.existsSync('./nginx')) {
          // Copy and paste nginx directory (all files)
          console.log("\nCreating nginx directory...");
          await runCommand('cp -R ./shared/nginx ./nginx');
        }
        if (!fs.existsSync('./php')) {
          // Create a php directory
          console.log("\nCreating php directory...");
          await runCommand('cp -R ./shared/php ./php');
        }
        if (!fs.existsSync('./redis')) {// Create a redis directory
          console.log("\nCreating redis directory...");
          await runCommand('mkdir redis');
          // await runCommand('chown -R ${USER}:${USER} ./redis');
        }
        
        if (!fs.existsSync('./backend/.env')) {
          console.log("\nCreating .env file...");
          await runCommand('cp ./shared/.env_laravel ./backend/.env');
          const envPath = path.join(__dirname, './backend/.env');

          UpdateEnvVariables(envPath, {
            APP_NAME: site,
            APP_ENV: 'local',
            APP_DEBUG: 'true',
            DB_CONNECTION: databaseHost,
            DB_HOST: databaseHost,
            DB_PORT: databasePort,
            DB_DATABASE: databaseName,
            DB_USERNAME: databaseUser,
            DB_PASSWORD: databasePassword,
            APP_URL: `${siteProtocol}://${siteUrl}`
          });         
        }

        if (!fs.existsSync('./backend/database/database.sqlite')) {
          console.log("\nCreating database.sqlite file...");
          await runCommand('touch ./backend/database/database.sqlite');
        }        
      }

      console.log(`\nRun docker-compose for backend: ${backendTech}`);
      await runCommand(`docker-compose --project-name ${projectName} -f ${composeTechFilePath} up --build -d`);

      if (cloneBackend === 'laravel' && !fs.existsSync('./backend/vendor')) { 
        console.log("\nComposer install");
        await runCommand(`docker-compose --project-name ${projectName} -f ${composeTechFilePath} run --rm composer install --ignore-platform-reqs`);
        // Generate the application key
        console.log("\nGenerate Laravel Key");
        await runCommand(`docker-compose --project-name ${projectName} -f ${composeTechFilePath} run --rm artisan key:generate`);        
        console.log("\nMigrate database");
        await runCommand(`docker-compose --project-name ${projectName} -f ${composeTechFilePath} run --rm  artisan migrate:fresh`);
      }

    }
    //--  CLONE BACKEND --//

    //--  FRONTEND --//
    if (frontendTech === 'yes') {

      const composeTechFilePath = `docker-compose-frontend.yml`;

      if (!fs.existsSync(frontend)) {
          console.log("\nRun npm create vite - Choose the frontend...");
        await runInteractiveCommand(`npm`, ['create', 'vite@latest', `${frontend}`]);
          console.log("\nCopy and paste the frontend Dockerfile...");
          await runCommand(`cp ./shared/Dockerfile_frontend ${frontend}/Dockerfile`);
        // Path to your Vite configuration file
       const viteConfig = `${frontend}/vite.config.ts`;

       console.log(`Vite File Path: ${viteConfig}`) 

       if (fs.existsSync(viteConfig)) {
 
         // Read the existing file
         let config = fs.readFileSync(viteConfig, 'utf-8');
 
         // Add a new property to the defineConfig object
         console.log("\nUpdating vite.config file to use the frontend port...");
         if (config.includes('defineConfig')) {
           config = config.replace(
             /defineConfig\(\{([\s\S]*?)\}\)/,
             `defineConfig({$1
             server: {
                 watch: {
                   usePolling: true,
                 },
                 host: true,
                 strictPort: true,
                 port: ${frontendPort},
               }
             })`
           );
         }
 
         // Write the Updated content back to the file
         fs.writeFileSync(viteConfig, config, 'utf-8');
 
       }

      } 
      
      console.log(`\nRun docker-compose for the frontend`);
      await runCommand(`docker-compose --project-name ${projectName} -f ${composeTechFilePath} up --build -d`);

    }
    //--  FRONTEND --//

    //--  CLONE FRONTEND --//
    if (frontendTech  === 'clone' && !fs.existsSync(frontend)) {

      console.log(`\nStart of processing cloned frontend`);

      const composeTechFilePath = `docker-compose-frontend.yml`;

      console.log("\nCloning into directory...");
      await runCommand(`git clone ${cloneFrontendLink} ${frontend}`);
      console.log("\nCopy and paste the frontend Dockerfile...");
      await runCommand(`cp ./shared/Dockerfile_frontend ${frontend}/Dockerfile`);
      
      // Path to your Vite configuration file
      const viteConfig = `${frontend}/vite.config.ts`;

      if (fs.existsSync(viteConfig)) {

        // Read the existing file
        let config = fs.readFileSync(viteConfig, 'utf-8');

        // Add a new property to the defineConfig object
        console.log("\nUpdating vite.config file to use the frontend port...");
        if (config.includes('defineConfig')) {
          config = config.replace(
            /defineConfig\(\{([\s\S]*?)\}\)/,
            `defineConfig({$1
            server: {
                watch: {
                  usePolling: true,
                },
                host: true,
                strictPort: true,
                port: ${frontendPort},
              }
            })`
          );
        }

        // Write the Updated content back to the file
        fs.writeFileSync(viteConfig, config, 'utf-8');

      }
    
      console.log(`\nRun docker-compose for the frontend`);
      await runCommand(`docker-compose --project-name ${projectName} -f ${composeTechFilePath} up --build -d`);

    }
    //--  CLONE FRONTEND --//

    //--  CLONE MAIL SERVER --//
    if(mailServer !== 'no') {
      console.log(`\nStart of processing mail server`);
      const composeDatabaseFilePath = `docker-compose-${mailServer}.yml`;
      console.log(`\nRunning docker compose for ${mailServer}...`);
      await runCommand(`docker-compose --project-name ${projectName} -f ${composeDatabaseFilePath} up --build -d`);
    }
    //--  CLONE MAIL SERVER --//

    if(env !== 'production') {
      console.log(`\nStart of processing for E2E testing...`);
      const composeDatabaseFilePath = `docker-compose-playwright.yml`;

      if (!fs.existsSync(e2e)) {

        // Add the dockerfile from the shared folder to the e2e
        console.log("\nRun npm init playground...");
        await runInteractiveCommand(`npm`, ['init', 'playwright@latest', `${e2e}`]);
        console.log("\nCopy and paste the e2e Dockerfile...");
        await runCommand(`cp ./shared/Dockerfile_e2e ${e2e}/Dockerfile`);

      }

      console.log(`\nRunning docker compose for ${mailServer}...`);
      await runCommand(`docker-compose --project-name ${projectName} -f ${composeDatabaseFilePath} up --build -d`);

    }
    
    console.log("\nThat process has completed.");
    await process.exit(0);
    
  } catch (error) {
    console.error("An error occurred:", error.stderr || error.error);
  }
}

runDockerCompose().then();