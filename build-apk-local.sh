#!/bin/bash

# Cash Flow App - Local APK Build Script
# This script builds the Android APK with all necessary permissions for import/export

set -e  # Exit on error

echo "🚀 Starting Cash Flow APK build..."
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Step 1: Install dependencies
echo -e "${BLUE}📦 Installing dependencies...${NC}"
npm install --legacy-peer-deps

# Step 2: Create icons
echo -e "${BLUE}🎨 Creating app icons...${NC}"
cd offline-app
if [ -f "./create-icons.sh" ]; then
    chmod +x create-icons.sh
    ./create-icons.sh
else
    echo "Icon creation script not found, skipping..."
fi
cd ..

# Step 3: Add Android platform (if not exists)
if [ ! -d "android" ]; then
    echo -e "${BLUE}📱 Adding Android platform...${NC}"
    npx cap add android
else
    echo -e "${GREEN}✓ Android platform already exists${NC}"
fi

# Step 4: Copy Android resources and configure permissions
echo -e "${BLUE}🔐 Configuring Android permissions for import/export...${NC}"

# Copy AndroidManifest.xml
if [ -f "android-resources/AndroidManifest.xml" ]; then
    cp android-resources/AndroidManifest.xml android/app/src/main/AndroidManifest.xml
    echo -e "${GREEN}✓ AndroidManifest.xml copied (with storage permissions)${NC}"
else
    echo -e "${RED}⚠️  AndroidManifest.xml not found in android-resources/${NC}"
fi

# Create xml directory and copy file_paths.xml
mkdir -p android/app/src/main/res/xml
if [ -f "android-resources/file_paths.xml" ]; then
    cp android-resources/file_paths.xml android/app/src/main/res/xml/file_paths.xml
    echo -e "${GREEN}✓ file_paths.xml copied${NC}"
else
    echo -e "${RED}⚠️  file_paths.xml not found in android-resources/${NC}"
fi

# Step 5: Sync Capacitor
echo -e "${BLUE}🔄 Syncing Capacitor...${NC}"
npx cap sync android

# Step 6: Build APK
echo -e "${BLUE}🔨 Building debug APK...${NC}"
cd android
chmod +x gradlew
./gradlew assembleDebug --no-daemon
cd ..

# Step 7: Show results
echo ""
echo -e "${GREEN}✅ Build completed successfully!${NC}"
echo ""
echo "📍 APK Location:"
echo "   android/app/build/outputs/apk/debug/app-debug.apk"
echo ""
echo "📱 To install on your device:"
echo "   1. Copy app-debug.apk to your phone"
echo "   2. Enable 'Install from unknown sources' in Android settings"
echo "   3. Tap the APK file to install"
echo ""
echo "🔐 Permissions included:"
echo "   ✓ Storage (for import/export CSV files)"
echo "   ✓ Internet (for online/offline detection)"
echo ""
echo -e "${GREEN}Happy tracking! 💰${NC}"
