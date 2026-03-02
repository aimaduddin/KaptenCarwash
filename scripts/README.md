# Deployment Scripts

This repository includes automated deployment scripts for VPS deployment.

## Scripts

### Pre-deployment (`scripts/pre-deploy.sh`)
Run this locally before deploying:
```bash
bash scripts/pre-deploy.sh
```

Checks:
- No uncommitted changes
- All tests pass
- Code style (Pint) passes
- Frontend builds successfully
- APP_DEBUG is false

### Post-deployment (`scripts/post-deploy.sh`)
Run this on the VPS after deployment:
```bash
bash scripts/post-deploy.sh /var/www/kaptencarwash
```

Actions:
- Installs production dependencies
- Clears and caches config/routes/views
- Runs migrations
- Creates storage link
- Optimizes application
- Sets file permissions
- Restarts services

### GitHub Actions (`.github/workflows/deploy.yml`)
Automated CI/CD pipeline that:
1. Runs tests
2. Builds frontend assets
3. Deploys to VPS via SSH/rsync
4. Runs post-deployment script

## GitHub Actions Setup

### Required Secrets

Add these secrets in GitHub repository settings (Settings → Secrets and variables → Actions):

| Secret | Description | Example |
|--------|-------------|---------|
| `SSH_PRIVATE_KEY` | SSH private key for VPS access | Output of `cat ~/.ssh/id_rsa` |
| `VPS_HOST` | VPS IP address or domain | `123.45.67.89` or `vps.example.com` |
| `VPS_USER` | SSH username on VPS | `root`, `ubuntu`, or your username |
| `APP_DIR` | Application directory on VPS | `/var/www/kaptencarwash` |
| `DOMAIN` | Your domain name | `kaptencarwash.com` |

### Setup Steps

1. **Generate SSH key pair** (if you don't have one):
```bash
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/github_actions
```

2. **Add public key to VPS**:
```bash
cat ~/.ssh/github_actions.pub | ssh user@your-vps-ip "mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys"
```

3. **Copy private key to GitHub**:
```bash
cat ~/.ssh/github_actions
```
Add the output as `SSH_PRIVATE_KEY` secret.

4. **Test SSH connection**:
```bash
ssh -i ~/.ssh/github_actions user@your-vps-ip
```

5. **Ensure VPS has required tools**:
```bash
# On VPS
sudo apt install rsync php8.2-cli php8.2-fpm
```

## Manual Deployment

If you prefer manual deployment:

### Local (Pre-deploy)
```bash
bash scripts/pre-deploy.sh
```

### Deploy to VPS
```bash
rsync -avz --delete \
  --exclude='.git' \
  --exclude='node_modules' \
  --exclude='.env' \
  -e ssh \
  ./ user@vps-ip:/var/www/kaptencarwash/
```

### On VPS (Post-deploy)
```bash
cd /var/www/kaptencarwash
bash scripts/post-deploy.sh
```

## Troubleshooting

### SSH Connection Issues
- Verify SSH key is correct
- Check VPS firewall allows SSH (port 22)
- Ensure public key is in VPS `~/.ssh/authorized_keys`

### rsync Permission Denied
- Ensure user has write permissions on target directory
- Check `APP_DIR` path is correct

### Deployment Fails
- Check GitHub Actions logs
- Verify all secrets are set correctly
- Ensure VPS has required PHP version and extensions

## Environment Variables

Ensure your production `.env` file on the VPS has:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Add your other production settings...
```
