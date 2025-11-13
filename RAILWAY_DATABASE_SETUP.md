# Railway Database Setup Guide

## ⚠️ IMPORTANT: Railway Configuration

**CRITICAL**: Make sure Railway is NOT using custom start commands!

### Check Your Railway Service Settings:

1. Go to your **filehub** service in Railway
2. Click on **"Settings"** tab
3. Find **"Start Command"** section
4. **REMOVE/EMPTY** any custom start command (should be empty!)
5. Find **"Pre-deploy Command"** section  
6. **REMOVE/EMPTY** any pre-deploy command (should be empty!)

**Why?** Custom commands bypass the Dockerfile's `ENTRYPOINT`, which means:
- Database connection check won't run
- Migrations won't run automatically
- Config caching won't happen
- Your entrypoint script is completely ignored!

The Dockerfile has `ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]` which should handle everything automatically.

## How to Add PostgreSQL Database to Your Railway Project

### Step 1: Add PostgreSQL Service
1. Go to your Railway project dashboard: https://railway.app
2. Click on your project
3. Click the **"+ New"** button (or **"+ Add Service"**)
4. Select **"Database"** → **"Add PostgreSQL"**
5. Railway will automatically create a PostgreSQL database service

### Step 2: Link Database to Your App
1. In your project, you should now see two services:
   - Your app service (FILEHUB)
   - The PostgreSQL database service
2. Click on your **app service** (FILEHUB)
3. Go to the **"Variables"** tab
4. Railway should automatically add `DATABASE_URL` when you link the database
5. If not, click **"Add Reference"** and select your PostgreSQL service
6. Railway will automatically inject the `DATABASE_URL` environment variable

### Step 3: Verify Environment Variables
In your app service's **Variables** tab, you should see:
- `DATABASE_URL` - Full PostgreSQL connection string (automatically set by Railway)

### Step 4: Verify Settings (CRITICAL!)
1. Go to your **filehub** service **Settings** tab
2. **Start Command**: Should be EMPTY (not `apache2-foreground` or anything else!)
3. **Pre-deploy Command**: Should be EMPTY (not `php artisan migrate --force` or anything else!)

### Step 5: Redeploy
After fixing the settings:
1. The app will automatically redeploy, OR
2. You can manually trigger a redeploy from the Railway dashboard

### What Happens Next (When Settings Are Correct)
- The entrypoint script will run (`/usr/local/bin/docker-entrypoint.sh`)
- You'll see: `"=== Starting application entrypoint ==="`
- The script will detect `DATABASE_URL`
- It will wait for the database to be ready
- Run migrations automatically
- Cache Laravel config, routes, and views
- Start Apache
- Your app will be fully functional

## Alternative: Using Railway CLI

If you prefer using the CLI:

```bash
# Add PostgreSQL database
railway add --database postgres

# Link it to your service (if not automatic)
railway variables set DATABASE_URL=$DATABASE_URL

# Redeploy
railway up
```

## Troubleshooting

### Database not connecting?
1. Check that the database service is running (green status in Railway)
2. Verify `DATABASE_URL` is set in your app service variables
3. Check the deployment logs for connection errors
4. Ensure both services are in the same Railway project

### Manual Migration
If migrations don't run automatically, you can run them manually:
```bash
railway run php artisan migrate --force
```

