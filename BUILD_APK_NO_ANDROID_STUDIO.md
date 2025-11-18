# Build APK Without Android Studio

You have **2 easy options** to build your APK without installing Android Studio!

---

## ⭐ OPTION 1: Build on GitHub (RECOMMENDED - No Installation Required!)

### What is this?
GitHub Actions will build your APK automatically in the cloud. You don't need to install anything!

### How to use:

1. **Push your code to GitHub** (you've already done this!)

2. **Go to GitHub Actions**:
   - Open: https://github.com/xulqarnain/cash-flow/actions
   - You'll see "Build Android APK" workflow

3. **Trigger the build**:
   - Click on "Build Android APK" workflow
   - Click "Run workflow" button (right side)
   - Select branch: `claude/cash-tracker-app-013kLMPMNjkPaxsmBTW4wZRw`
   - Click green "Run workflow" button

4. **Wait 5-10 minutes** for the build to complete
   - You can watch the progress in real-time
   - Green checkmark = success!

5. **Download your APK**:
   - Click on the completed workflow run
   - Scroll down to "Artifacts" section
   - Download `cash-record-app-debug.zip`
   - Extract it to get `app-debug.apk`

6. **Install on your phone**:
   - Transfer APK to your phone (USB, email, cloud drive, etc.)
   - Open the APK file on your phone
   - Allow "Install unknown apps" if prompted
   - Install and enjoy! 🎉

### Automatic builds:
Every time you push code to GitHub, the APK will build automatically!

---

## 🔧 OPTION 2: Build Locally with Command Line (Requires Android SDK)

If you want to build locally but don't want Android Studio GUI:

### Prerequisites:
You still need Android SDK, but not Android Studio:

```bash
# Install Java 17
sudo apt-get update
sudo apt-get install openjdk-17-jdk

# Install Android SDK command-line tools
# Download from: https://developer.android.com/studio#command-tools
# Or use sdkmanager:
wget https://dl.google.com/android/repository/commandlinetools-linux-9477386_latest.zip
unzip commandlinetools-linux-9477386_latest.zip -d ~/android-sdk
```

### Build steps:

```bash
# 1. Install dependencies
npm install

# 2. Create icons (optional)
cd offline-app
./create-icons.sh
cd ..

# 3. Add Android platform
npx cap add android

# 4. Sync files
npx cap sync android

# 5. Build APK with Gradle
cd android
./gradlew assembleDebug
cd ..

# Your APK is ready:
# android/app/build/outputs/apk/debug/app-debug.apk
```

---

## 📋 Comparison

| Feature | GitHub Actions | Local Command Line |
|---------|---------------|-------------------|
| Installation | None needed | Android SDK + Java |
| Build time | 5-10 min | 2-5 min |
| Internet | Required | Only for first download |
| Difficulty | ⭐ Easy | ⭐⭐ Medium |
| Storage | None (cloud) | ~3GB on your computer |
| Best for | Most users | Developers who build often |

---

## ✅ My Recommendation

**Use GitHub Actions (Option 1)** because:
- ✅ Zero installation required
- ✅ Works on any computer (Windows, Mac, Linux)
- ✅ Builds are reliable and consistent
- ✅ Can download APK anytime
- ✅ Automatic builds on every push
- ✅ Free for public repositories

---

## 🚀 Quick Start with GitHub Actions

```bash
# 1. Make sure code is pushed
git push origin claude/cash-tracker-app-013kLMPMNjkPaxsmBTW4wZRw

# 2. Go to GitHub
https://github.com/xulqarnain/cash-flow/actions

# 3. Click "Build Android APK" → "Run workflow"

# 4. Wait and download APK!
```

---

## 📱 Install APK on Your Phone

Once you have the APK file:

### Method 1: USB Transfer
```bash
# Connect phone to computer
adb install app-debug.apk
```

### Method 2: Direct Transfer
1. Copy APK to phone (via USB, email, Google Drive, etc.)
2. On phone, open file manager
3. Find and tap the APK file
4. Allow installation from unknown sources
5. Install!

### Method 3: Upload to Google Drive
1. Upload APK to Google Drive
2. Open Drive on your phone
3. Download and install

---

## 🔍 Troubleshooting GitHub Actions

### Build fails?
- Check the logs in GitHub Actions
- Make sure `package.json` has all Capacitor dependencies
- Verify `capacitor.config.json` exists and is correct

### Can't find APK?
- Look in "Artifacts" section at bottom of workflow run
- APK is in a ZIP file - extract it first

### Build takes too long?
- First build: 8-10 minutes (normal)
- Subsequent builds: 4-6 minutes (faster)

---

## 🎯 What's Next?

After getting your APK:

1. **Test it** on your phone
2. **Use the app** offline
3. **Make changes** to the code if needed
4. **Push changes** to GitHub
5. **New APK builds automatically**!

---

## 💡 Pro Tips

### Automatic Release
The workflow creates a GitHub Release for every build on `main` branch.
You can download previous APKs anytime from the Releases page.

### Manual Trigger
You can trigger builds manually anytime:
- Go to Actions → Build Android APK → Run workflow

### Multiple Branches
The workflow runs on:
- `main` branch
- Any `claude/**` branch

### Clean Build
If you need a clean build, delete `android/` folder and push:
```bash
rm -rf android/
git commit -am "Clean build"
git push
```

---

## 🆘 Need Help?

**Check GitHub Actions logs**:
1. Go to Actions tab
2. Click on failed workflow
3. Click on "Build APK" job
4. Read the error messages

**Common issues**:
- Missing dependencies → Check package.json
- Icon errors → Icons are auto-generated in workflow
- Gradle errors → Check Java version (should be 17)

---

## 🎉 Summary

**Easiest way**: Use GitHub Actions
1. Push code to GitHub
2. Run workflow
3. Download APK
4. Install on phone

**No Android Studio needed!** 🚀

---

**Happy Building!** 📱
