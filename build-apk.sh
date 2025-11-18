#!/bin/bash

# Cash Record APK Builder
# Builds APK using GitHub Actions (no Android Studio needed!)

echo "╔════════════════════════════════════════════════════════╗"
echo "║       Cash Record - APK Builder (GitHub Actions)      ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Check if we're in a git repo
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo -e "${RED}❌ Not a git repository!${NC}"
    exit 1
fi

# Check if gh CLI is installed
if ! command -v gh &> /dev/null; then
    echo -e "${YELLOW}⚠️  GitHub CLI (gh) not found${NC}"
    echo ""
    echo "You have 2 options:"
    echo ""
    echo "Option 1: Install GitHub CLI (easier automation)"
    echo "  • Ubuntu/Debian: sudo apt install gh"
    echo "  • Mac: brew install gh"
    echo "  • Windows: winget install GitHub.cli"
    echo "  • Or download from: https://cli.github.com/"
    echo ""
    echo "Option 2: Use GitHub website (manual)"
    echo "  1. Push your code: git push"
    echo "  2. Go to: https://github.com/xulqarnain/cash-flow/actions"
    echo "  3. Click 'Build Android APK'"
    echo "  4. Click 'Run workflow'"
    echo "  5. Wait and download APK from Artifacts"
    echo ""
    exit 1
fi

# Get current branch
BRANCH=$(git branch --show-current)
echo -e "${BLUE}Current branch:${NC} $BRANCH"
echo ""

# Check for uncommitted changes
if ! git diff-index --quiet HEAD --; then
    echo -e "${YELLOW}⚠️  You have uncommitted changes${NC}"
    echo ""
    read -p "Do you want to commit them? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        git add -A
        read -p "Commit message: " COMMIT_MSG
        git commit -m "$COMMIT_MSG"
        echo -e "${GREEN}✓ Changes committed${NC}"
    else
        echo -e "${YELLOW}⚠️  Building with uncommitted changes...${NC}"
    fi
    echo ""
fi

# Push to GitHub
echo -e "${BLUE}Pushing to GitHub...${NC}"
if git push origin "$BRANCH"; then
    echo -e "${GREEN}✓ Code pushed successfully${NC}"
else
    echo -e "${RED}❌ Failed to push code${NC}"
    exit 1
fi
echo ""

# Trigger GitHub Actions workflow
echo -e "${BLUE}Triggering APK build on GitHub Actions...${NC}"
echo ""

if gh workflow run build-apk.yml --ref "$BRANCH"; then
    echo -e "${GREEN}✓ Build triggered successfully!${NC}"
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    echo -e "${GREEN}🚀 Your APK is being built in the cloud!${NC}"
    echo ""
    echo "Build progress:"
    echo "  • View: https://github.com/xulqarnain/cash-flow/actions"
    echo "  • Or run: gh run list --workflow=build-apk.yml"
    echo ""
    echo "Expected time: 5-10 minutes"
    echo ""
    echo "When complete, download APK:"
    echo "  • Go to Actions → Select your run → Download 'cash-record-app-debug'"
    echo "  • Or run: gh run download (latest run)"
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""

    # Offer to watch the build
    read -p "Watch build progress? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo ""
        echo "Waiting for workflow to start..."
        sleep 5

        # Get the latest run
        RUN_ID=$(gh run list --workflow=build-apk.yml --limit 1 --json databaseId --jq '.[0].databaseId')

        if [ -n "$RUN_ID" ]; then
            echo "Watching build (Ctrl+C to stop watching)..."
            echo ""
            gh run watch "$RUN_ID"

            # Check if successful
            STATUS=$(gh run view "$RUN_ID" --json conclusion --jq '.conclusion')
            if [ "$STATUS" = "success" ]; then
                echo ""
                echo -e "${GREEN}╔════════════════════════════════════════╗${NC}"
                echo -e "${GREEN}║   ✓ APK Built Successfully!           ║${NC}"
                echo -e "${GREEN}╚════════════════════════════════════════╝${NC}"
                echo ""

                read -p "Download APK now? (y/n) " -n 1 -r
                echo
                if [[ $REPLY =~ ^[Yy]$ ]]; then
                    echo "Downloading APK..."
                    gh run download "$RUN_ID" --name cash-record-app-debug
                    echo ""
                    echo -e "${GREEN}✓ APK downloaded!${NC}"
                    echo ""
                    ls -lh app-debug.apk 2>/dev/null && {
                        echo "APK location: $(pwd)/app-debug.apk"
                        echo ""
                        echo "Install on phone:"
                        echo "  • USB: adb install app-debug.apk"
                        echo "  • Or copy to phone and install manually"
                    }
                fi
            else
                echo ""
                echo -e "${RED}❌ Build failed!${NC}"
                echo "Check logs at: https://github.com/xulqarnain/cash-flow/actions"
            fi
        fi
    else
        echo ""
        echo -e "${BLUE}Build running in background.${NC}"
        echo "Check status: gh run list --workflow=build-apk.yml"
    fi
else
    echo -e "${RED}❌ Failed to trigger workflow${NC}"
    echo ""
    echo "Manual steps:"
    echo "  1. Go to: https://github.com/xulqarnain/cash-flow/actions"
    echo "  2. Click 'Build Android APK'"
    echo "  3. Click 'Run workflow'"
    echo "  4. Select branch: $BRANCH"
    echo "  5. Click 'Run workflow' button"
    exit 1
fi

echo ""
echo -e "${GREEN}Done!${NC}"
