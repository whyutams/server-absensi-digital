import readline from 'node:readline';

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout,
    terminal: false,
});

const lines = [];
let resolvers = [];

rl.on('line', (line) => {
    if (resolvers.length > 0) {
        const resolve = resolvers.shift();
        resolve(line);
    } else {
        lines.push(line);
    }
});

function ask(promptText) {
    process.stdout.write(promptText);
    if (lines.length > 0) {
        return Promise.resolve(lines.shift());
    }
    return new Promise((resolve) => {
        resolvers.push(resolve);
    });
}

async function main() {
    console.log('=== GENERATOR NAMA FILE APK PEMBARUAN ===\n');

    let versionName = '';
    while (!versionName) {
        const inputVersion = await ask('Masukkan Version Name (contoh: 2.1 atau 1.0.1): ');
        const cleaned = (inputVersion || '').trim();
        if (/^[0-9]+(?:\.[0-9]+)*$/.test(cleaned)) {
            versionName = cleaned;
        } else {
            console.log('Format version name tidak valid! Hanya boleh angka dan titik (misal: 2.1). Coba lagi.\n');
        }
    }

    let isBeta = 0;
    while (true) {
        const inputBeta = await ask('Apakah ini versi Beta? (yes/no): ');
        const cleanedBeta = (inputBeta || '').trim().toLowerCase();
        if (cleanedBeta === 'yes' || cleanedBeta === 'y') {
            isBeta = 1;
            break;
        } else if (cleanedBeta === 'no' || cleanedBeta === 'n') {
            isBeta = 0;
            break;
        } else {
            console.log('Pilihan tidak valid! Masukkan "yes" atau "no".\n');
        }
    }

    const timestamp = Math.floor(Date.now() / 1000);
    const fileName = `${timestamp}-${versionName}-${isBeta}.apk`;

    console.log('\n========================================');
    console.log('HASIL NAMA FILE APK:');
    console.log(fileName);
    console.log('========================================\n');

    rl.close();
    process.exit(0);
}

main().catch((err) => {
    console.error('Error:', err);
    rl.close();
    process.exit(1);
});
