const {
    fs
} = require('./commands.js');

const source = '.env.example';
const destination = '.env';

if (!fs.existsSync(source)) {
    console.error(`Source file "${source}" does not exist.`);
    process.exit(1);
}

if (fs.existsSync(destination)) {
    console.log(`"${destination}" already exists. Skipping copy.`);
} else {
    fs.copyFileSync(source, destination);
    console.log(`Copied "${source}" to "${destination}".`);
}