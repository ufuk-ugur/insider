# Installation

### Clone git repo
```bash
git clone https://github.com/ufuk-ugur/insider.git
```

### Install dependencies
```bash
composer install
```
```bash
npm install
# or
yarn install
# or
pnpm install
# or
bun install
```

### Copy the `.env.example` file and rename it to `.env` then customize database settings
```bash
cp .env.example .env
```

### Build the front-end
```bash
npm run build
# or
yarn build
# or
pnpm build
# or
bun build
```

### Generate `APP_KEY`
```bash
php artisan key:generate
```

### Create database and rows
```bash
php artisan migrate --seed
```
