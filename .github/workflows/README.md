# GitHub Actions Workflows

## Deploy to GitHub Pages

The `deploy.yml` workflow automatically deploys the frontend to GitHub Pages when you push to the `main` branch.

### What it does:

1. Checks out the code
2. Sets up Node.js (for any build steps)
3. Copies `public/front/*` files to `_site/` directory
4. Creates `.nojekyll` file to disable Jekyll processing
5. Injects API base URL from secrets (if configured)
6. Deploys to GitHub Pages

### Manual Trigger

You can manually trigger the deployment from:
- GitHub Actions tab → "Deploy to GitHub Pages" → "Run workflow"

### Requirements

- GitHub Pages must be enabled in repository settings
- Source must be set to "GitHub Actions" (not "Deploy from a branch")

