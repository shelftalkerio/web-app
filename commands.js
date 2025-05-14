const { exec, spawn } = require("child_process");
const fs = require('fs');
const readline = require('readline');
const path = require("path");
const Docker = require('dockerode');
// Function to run a shell command
function runCommand(command, options = {}, input = '') {
    return new Promise((resolve, reject) => {
        const process = exec(command, options, (error, stdout, stderr) => {
            if (error) {
                reject({ error, stderr });
            } else {
                resolve(stdout);
            }
        });

        // Feed input to the process if provided
        if (input) {
            process.stdin.write(input);
            process.stdin.end(); // Close stdin after sending the input
        }

        // Print the command output in real-time
        process.stdout.on('data', (data) => console.log(data.toString()));
        process.stderr.on('data', (data) => console.error(data.toString()));
    });
}

// Function to run a interactive shell command
function runInteractiveCommand(command, args = [], options = {}) {

    return new Promise((resolve, reject) => {
        const child = spawn(command, args, {
            stdio: 'inherit', // Attach child's stdin, stdout, and stderr to the parent process
            cwd: options.cwd || process.cwd(),
            env: options.env || process.env,
        });

        child.on('close', (code) => {
            console.log(`Process exited with code: ${code}`);
            // Start accepting user input after the command completes
            startInteractiveSession();
            resolve(code);
        });

        child.on('error', (err) => {
            console.error(`Error occurred: ${err.message}`);
            reject(err);
        });
    });
}

function startInteractiveSession() {
    const rl = readline.createInterface({
        input: process.stdin,
        output: process.stdout,
    });

    rl.setPrompt('> ');
    rl.prompt();

    rl.on('line', (line) => {
        if (line.trim() === 'exit') {
            console.log('Exiting...');
            rl.close();
        } else {
            console.log(`You entered: ${line}`);
            rl.prompt(); // Prompt for the next input
        }
    });

    rl.on('close', () => {
        console.log('Goodbye!');
        process.exit(0);
    });
}

// Function to read and parse the .env file
function readEnvFile() {
    let envVars = {};
    if (fs.existsSync(envPath)) {
        const envContent = fs.readFileSync(envPath, 'utf-8');
        envVars = envContent.split('\n').reduce((acc, line) => {
            const [key, value] = line.split('=');
            if (key && value) acc[key.trim()] = value.trim();
            return acc;
        }, {});
    }
    return envVars;
}

module.exports = {
    runCommand,
    runInteractiveCommand,
    startInteractiveSession,
    readEnvFile,
    fs,
    path,
    Docker
};