const {
    runCommand,
    fs
} = require('./commands.js');

// Node modules in root
const nodeModules = './node_modules'
const envPath = './.env'

// Function to update .env file
const updateEnvFile = (key, value) => {
    const envPath = '.env';

    // Read current .env file content
    let envConfig = fs.existsSync(envPath) ? fs.readFileSync(envPath, 'utf8') : '';

    // Convert env content to an object
    let envVars = envConfig
        .split('\n')
        .filter(line => line.trim() !== '') // Remove empty lines
        .reduce((acc, line) => {
            let [k, v] = line.split('=');
            acc[k.trim()] = v ? v.trim() : '';
            return acc;
        }, {});

    // Update or add new key-value pair
    envVars[key] = value;

    // Convert object back to env format
    const newEnvContent = Object.entries(envVars)
        .map(([k, v]) => `${k}=${v}`)
        .join('\n');

    // Write updated content to .env file
    fs.writeFileSync(envPath, newEnvContent);
    console.log(`✅ Updated .env: ${key}=${value}`);
};

// Main function to ask questions
const updateEnv = async () => {
    
    // Create a .env file
    if (!fs.existsSync(envPath)) {
        console.log("\nCreating a .env file...");
        await runCommand('cp .env.example .env');

    }
    // Search for node modules in root
    if (fs.existsSync(nodeModules)) {
        console.log("\nRun npm update on root directory...");
        await runCommand('npm update');
    }
    else {
        console.log("\nRun npm install on root directory...");
        await runCommand('npm install');
    }

    const inquirer = require('inquirer');
    const dotenv = require('dotenv');

    // Load existing environment variables (if any)
    dotenv.config();
    
    const initialAnswers = await inquirer.prompt([        
        {
            type: 'list',
            name: 'APP_ENV',
            message: 'What environment is this project:',
            choices: ['dev', 'staging', 'production'],
            default: process.env.APP_ENV || 'dev'
        },
        {
            type: 'input',
            name: 'SITE',
            message: 'What is the name of the project:',
            default: process.env.SITE || 'app'
        },
        {
            type: 'list',
            name: 'BACKEND',
            message: 'Which backend would you like to use?',
            choices: ['no','laravel', 'nestjs', 'clone'],
            default: process.env.BACKEND || ''
        },
        {
            type: 'list',
            name: 'FRONTEND',
            message: 'Would you like a frontend?',
            choices: ['no', 'yes', 'clone'],
            default: process.env.FRONTEND || ''
        },
        {
            type: 'list',
            name: 'HEADLESS',
            message: 'Would you like a headless CMS?',
            choices: ['no', 'strapi'],
            default: process.env.HEADLESS || ''
        },
        {
            type: 'list',
            name: 'USE_HEADLESS_DATABASE',
            message: 'Would you like to use a seperate database for your headless CMS?',
            choices: ['no', 'yes'],
            default: process.env.HEADLESS || 'no'
        },
        {
            type: 'list',
            name: 'DATABASE',
            message: 'Which database would you like?',
            choices: ['no', 'mysql', 'postgres', 'mariadb', 'mongodb'],
            default: process.env.DATABASE || ''
        },
        {
            type: 'list',
            name: 'MAIL_SERVER',
            message: 'Would you like a test mail server?',
            choices: ['no', 'mailhog', 'mailpit'],
            default: process.env.MAIL_SERVER || 'no'
        },     
        
    ]);
    // Ask further questions according to the previous answers    
    let databaseAnswers = {}
    if (initialAnswers.DATABASE  !== 'no') {
        databaseAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'DATABASE_NAME',
                message: 'What is the name of the database?',
                default: process.env.DATABASE_NAME || 'homestead'
            },
            {
                type: 'input',
                name: 'DATABASE_USER',
                message: 'What is the name of the database user?',
                default: process.env.DATABASE_USER || 'homestead'
            },
            {
                type: 'input',
                name: 'DATABASE_PASSWORD',
                message: 'What is the database password?',
                default: process.env.DATABASE_PASSWORD || 'secret'
            },
            {
                type: 'input',
                name: 'DATABASE_ROOT_PASSWORD',
                message: 'What is the database root password?',
                default: process.env.DATABASE_ROOT_PASSWORD || 'password'
            },
            {
                type: 'input',
                name: 'DATABASE_PORT',
                message: 'What is the port number for the database?',
                default: process.env.DATABASE_PORT || 3306
            },
            {
                type: 'input',
                name: 'ADMINER_PORT',
                message: 'What is the port number for the database admin?',
                default: process.env.ADMINER_PORT || 8888
            }
        ]);
    }
    let mysqlDatabaseAnswers = {}
    if (initialAnswers.DATABASE === 'mysql') {
        mysqlDatabaseAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'SERVICE_NAME',
                message: 'What is the service name for your mysql database?',
                default: process.env.SERVICE_NAME || 'mysql'
            },
            {
                type: 'input',
                name: 'SERVICE_TAGS',
                message: 'What is the service name for your mysql database?',
                default: process.env.SERVICE_TAGS || 'dev'
            }
        ]);
    }
    let mongoDatabaseAnswers = {}
    if (initialAnswers.DATABASE === 'mongodb') {
        mongoDatabaseAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'MONGO_EXPRESS_PORT',
                message: 'What is the port number for the database admin?',
                default: process.env.MONGO_EXPRESS_PORT || 8081
            }
        ]);
    }
    let strapiAnswers = {}
    if (initialAnswers.HEADLESS === 'strapi') {
        strapiAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'STRAPI_PORT',
                message: 'What is the strapi port number?',
                default: process.env.DATABASE_PASSWORD || 1337
            }                      
        ]);
    }
    let strapiDatabaseAnswers = {}
    if(initialAnswers.USE_HEADLESS_DATABASE === 'yes') {
        strapiDatabaseAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'DATABASE',
                message: 'What is the database type?',
                default: process.env.DATABASE || 'postgres'
            },
            {
                type: 'input',
                name: 'DATABASE_PID',
                message: 'Please give the database a unique name',
                default: process.env.DATABASE_PID || 'db'
            },
            {
                type: 'input',
                name: 'DATABASE_PORT',
                message: 'What is the datbase port number?',
                default: process.env.DATABASE_PORT || 5432
            },
            {
                type: 'input',
                name: 'DATABASE_NAME',
                message: 'What is the name of the database?',
                default: process.env.DATABASE_NAME || 'homestead'
            },
            {
                type: 'input',
                name: 'DATABASE_USER',
                message: 'What is the name of the database user?',
                default: process.env.DATABASE_USER || 'homestead'
            },
            {
                type: 'input',
                name: 'DATABASE_PASSWORD',
                message: 'What is the database password?',
                default: process.env.DATABASE_PASSWORD || 'secret'
            }  
        ])
    }
    let cloneBackendAnswers = {};
    if (initialAnswers.BACKEND === 'clone') {
        cloneBackendAnswers = await inquirer.prompt([
            {
                type: 'list',
                name: 'CLONE_BACKEND',
                message: 'Type of cloned backend project?',
                choices: ['laravel', 'nestjs'],
                default: process.env.CLONE_BACKEND || 'laravel'
            },
            {
                type: 'input',
                name: 'CLONE_BACKEND_LINK',
                message: 'What is the repo link for the cloned backend project?'
            }
        ]);
    }
    let cloneFrontendAnswers = {}
    if (initialAnswers.FRONTEND === 'clone') {
        cloneFrontendAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'CLONE_FRONTEND_LINK',
                message: 'What is the repo link for the frontend project?'
            }
        ]);
    }
    let backendPortAnswers = {};
    if (initialAnswers.BACKEND !== 'no' && cloneBackendAnswers.CLONED_BACKEND !== '') {
        backendPortAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'BACKEND_PORT',
                message: 'What is the port number for the backend?',
                default: process.env.BACKEND_PORT || 80
            }
        ]);
    }
    let frontendPortAnswers = {};
    if (initialAnswers.FRONTEND !== 'no' && cloneFrontendAnswers.CLONE_FRONTEND_LINK !== '') {
        frontendPortAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'FRONTEND_PORT',
                message: 'What is the port number for the frontend?',
                default: process.env.FRONTEND_PORT || 3000
            }
        ]);
    }
    let siteInfoAnswers = {}
    if (initialAnswers.BACKEND !== 'no' || initialAnswers.FRONTEND !== 'no') {
        siteInfoAnswers = await inquirer.prompt([
          {
            type: 'list',
            name: 'SITE_PROTOCOL',
            message: 'What is the url for the site?',
            choices: ['http', 'https'],
            default: process.env.SITE_PROTOCOL || 'http'
        },
        {
            type: 'input',
            name: 'SITE_URL',
            message: 'What is the url for the site?',
            default: process.env.SITE_URL || 'app.local'
        }
        ]);
    }
    let laravelAnswers = {};
    if ((initialAnswers.BACKEND.trim() !== '' && initialAnswers.BACKEND === 'laravel') || cloneBackendAnswers.CLONED_BACKEND === 'laravel') {
        laravelAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'PHP_PORT',
                message: 'What is the port number for PHP?',
                default: process.env.PHP_PORT || 9000
            },
            {
                type: 'input',
                name: 'REDIS_PORT',
                message: 'What is the port number for redis?',
                default: process.env.REDIS_PORT || 6379
            }
        ]);
    }
    let nestJsAnswers = {};
    if (initialAnswers.BACKEND.trim() !== '' && initialAnswers.BACKEND === 'nestjs') {
        nestJsAnswers = await inquirer.prompt([
            {
                type: 'input',
                name: 'NESTJS_PORT',
                message: 'What is the port number for nest?',
                default: process.env.NESTJS_PORT || 5000
            }
        ]);
    }

    // Merge all the answers
    const finalAnswers = { 
        ...initialAnswers, 
        ...databaseAnswers, 
        ...strapiAnswers,
        ...mysqlDatabaseAnswers, 
        ...mongoDatabaseAnswers,
        ...cloneBackendAnswers, 
        ...cloneFrontendAnswers, 
        ...backendPortAnswers, 
        ...frontendPortAnswers,
        ...siteInfoAnswers,
        ...laravelAnswers,
        ...nestJsAnswers 
    };

    // Update .env file with all answers
    Object.entries(finalAnswers).forEach(([key, value]) => updateEnvFile(key, value));
};

// Run the script
updateEnv().then();
