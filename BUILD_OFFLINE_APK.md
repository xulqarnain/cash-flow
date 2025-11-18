# Build Offline Cash Record APK - Complete Guide

This guide shows you how to build a fully offline Android APK using Capacitor and SQLite.

## What You're Building

**Offline Cash Record App** with:
- ✅ Works 100% offline (no internet/server needed)
- ✅ SQLite database for local data storage
- ✅ Same beautiful UI with smooth animations
- ✅ All features: track people, transactions, balances
- ✅ Can be installed on any Android phone
- ✅ No Laravel backend required

## Architecture

- **Frontend**: Alpine.js (lightweight reactive framework)
- **Database**: Capacitor SQLite (native SQLite on Android)
- **Styling**: Tailwind CSS (via CDN)
- **Build Tool**: Capacitor (web → native APK)

---

## Prerequisites

Before starting, make sure you have:

1. **Node.js & npm** (version 18+)
   ```bash
   node --version  # Should be 18 or higher
   npm --version
   ```

2. **Android Studio** installed
   - Download from: https://developer.android.com/studio
   - Install with Android SDK

3. **Java JDK 17+** installed
   ```bash
   java --version  # Should be 17 or higher
   ```

4. **Environment Variables** (Android Studio should set these):
   - `ANDROID_HOME` or `ANDROID_SDK_ROOT`
   - `JAVA_HOME`

---

## Step 1: Install Dependencies

Navigate to your project and install all required packages:

```bash
cd /home/user/cash-flow

# Install all dependencies including Capacitor
npm install

# Verify Capacitor is installed
npx cap --version
```

**Expected output**: Should show Capacitor version 6.2.0 or higher

---

## Step 2: Create App Icons

You need two icon files in the `offline-app` directory:

### Option A: Use ImageMagick (Recommended)

```bash
# Install ImageMagick first if you don't have it
# sudo apt-get install imagemagick  (Linux)
# brew install imagemagick  (Mac)

# Create 192x192 icon
convert -size 192x192 -gravity center \
  -background "#5468ff" -fill white \
  -pointsize 100 -font Arial-Bold \
  label:"CR" offline-app/icon-192.png

# Create 512x512 icon
convert -size 512x512 -gravity center \
  -background "#5468ff" -fill white \
  -pointsize 300 -font Arial-Bold \
  label:"CR" offline-app/icon-512.png
```

### Option B: Use Online Tools

1. Go to: https://www.pwabuilder.com/imageGenerator
2. Upload any image or create a simple design
3. Download 192x192 and 512x512 sizes
4. Save as `icon-192.png` and `icon-512.png` in `offline-app/` folder

### Option C: Manual Creation

Use any image editor (Photoshop, GIMP, Canva) to create:
- 192×192 pixels PNG
- 512×512 pixels PNG
- Save both in `offline-app/` directory

---

## Step 3: Add Android Platform

```bash
# Add Android platform to Capacitor
npx cap add android

# This creates an 'android' folder with native Android project
```

**If you see errors**:
- Make sure `capacitor.config.json` points to `webDir: "offline-app"`
- Ensure Android Studio is properly installed

---

## Step 4: Sync Files to Android

Every time you make changes to your web code, sync them:

```bash
npx cap sync android
```

This copies your `offline-app` files into the Android project.

---

## Step 5: Configure SQLite Plugin

The SQLite plugin needs to be registered in Android:

```bash
# Open Android project in Android Studio
npx cap open android
```

Android Studio will open. Now configure the SQLite plugin:

1. Wait for Gradle sync to complete (may take a few minutes first time)

2. Open `android/app/src/main/java/com/cashrecord/app/MainActivity.java`

3. Add this import at the top:
   ```java
   import com.getcapacitor.community.database.sqlite.CapacitorSQLite;
   ```

4. Inside the `onCreate` method, add:
   ```java
   this.init(savedInstanceState, new ArrayList<Class<? extends Plugin>>() {{
       add(CapacitorSQLite.class);
   }});
   ```

5. Save the file

---

## Step 6: Build Debug APK

### Method A: Using Android Studio (Easiest)

1. Make sure Android Studio is open (from Step 5)

2. Click **Build** → **Build Bundle(s) / APK(s)** → **Build APK(s)**

3. Wait for build to complete (2-5 minutes)

4. Click **"locate"** link in the notification

5. Your APK is at: `android/app/build/outputs/apk/debug/app-debug.apk`

### Method B: Using Command Line

```bash
cd android
./gradlew assembleDebug
cd ..
```

APK location: `android/app/build/outputs/apk/debug/app-debug.apk`

---

## Step 7: Install APK on Your Phone

### Method A: USB Cable (Recommended)

1. **Enable Developer Mode** on your phone:
   - Go to Settings → About Phone
   - Tap "Build Number" 7 times
   - Developer Options will appear in Settings

2. **Enable USB Debugging**:
   - Go to Settings → Developer Options
   - Turn on "USB Debugging"

3. **Connect phone to computer** with USB cable

4. **Install APK**:
   ```bash
   adb devices  # Verify phone is connected
   adb install android/app/build/outputs/apk/debug/app-debug.apk
   ```

5. **Open the app** on your phone!

### Method B: Direct File Transfer

1. Copy `app-debug.apk` to your phone (via USB, email, cloud drive, etc.)

2. On your phone:
   - Open file manager
   - Find the APK file
   - Tap to install

3. If prompted, allow "Install unknown apps" for your file manager

4. Install and open!

---

## Step 8: Test Your App

Once installed, test these features:

1. ✅ **Add a person**: Tap the + button, add name and amount
2. ✅ **View balance**: Check dashboard shows correct totals
3. ✅ **Add transaction**: Add more transactions to existing people
4. ✅ **View history**: Switch to History tab
5. ✅ **Delete transaction**: Long press or click delete icon
6. ✅ **Offline mode**: Turn off internet, verify app still works
7. ✅ **Data persistence**: Close and reopen app, data should remain

---

## Building Signed APK (For Production/Play Store)

Debug APK works fine for personal use. For publishing to Google Play Store, you need a signed release APK.

### Step 1: Generate Keystore

```bash
# Create a keystore (one-time only)
keytool -genkey -v -keystore my-release-key.keystore \
  -alias cash-record-key \
  -keyalg RSA -keysize 2048 -validity 10000

# Enter password when prompted (REMEMBER THIS!)
# Fill in organization details
```

**IMPORTANT**:
- Save `my-release-key.keystore` in a safe place
- Never commit it to git
- You'll need it for ALL future updates

### Step 2: Configure Signing in Android Studio

1. Open Android Studio
2. Go to **Build** → **Generate Signed Bundle / APK**
3. Select **APK**
4. Click **Next**
5. Choose your keystore file
6. Enter keystore password and key alias
7. Select **release** build type
8. Click **Finish**

Signed APK: `android/app/release/app-release.apk`

### Step 3: Upload to Google Play Console

1. Create Google Play Developer account ($25 one-time fee)
2. Create new app in Play Console
3. Upload `app-release.apk`
4. Fill in store listing, screenshots, description
5. Submit for review

---

## Troubleshooting

### Build Errors

**Error: Gradle sync failed**
```bash
cd android
./gradlew clean
cd ..
npx cap sync android
```

**Error: SQLite not found**
- Make sure you added SQLite to MainActivity.java
- Rebuild project in Android Studio

**Error: Icons not found**
- Create icon-192.png and icon-512.png in offline-app/
- Run `npx cap sync android` again

### App Crashes

**Check Android logs**:
```bash
adb logcat | grep -i "capacitor\|sqlite\|cashrecord"
```

**Common issues**:
- SQLite plugin not registered → Follow Step 5 again
- Database initialization failed → Check browser console in app
- JavaScript errors → Check app.js for syntax errors

### Data Not Persisting

**Web version (browser)**:
- Uses IndexedDB (fallback)
- Check browser console for errors
- Clear browser data and try again

**Android version**:
- Uses native SQLite
- Should persist perfectly
- If not, check logcat for SQLite errors

---

## Making Changes and Rebuilding

When you update your app code:

```bash
# 1. Make changes to offline-app/index.html or offline-app/app.js

# 2. Sync changes to Android
npx cap sync android

# 3. Rebuild APK
cd android
./gradlew assembleDebug
cd ..

# 4. Install updated APK
adb install -r android/app/build/outputs/apk/debug/app-debug.apk
```

The `-r` flag reinstalls without losing data.

---

## File Structure

```
cash-flow/
├── offline-app/
│   ├── index.html          # Main app UI (Alpine.js)
│   ├── app.js              # Business logic & SQLite
│   ├── manifest.json       # PWA manifest
│   ├── icon-192.png        # App icon (small)
│   └── icon-512.png        # App icon (large)
├── android/                # Native Android project
│   └── app/build/outputs/apk/
│       └── debug/
│           └── app-debug.apk  # Your installable APK
├── capacitor.config.json   # Capacitor configuration
├── package.json            # Dependencies
└── BUILD_OFFLINE_APK.md    # This guide
```

---

## Features

### ✅ Implemented
- Alpine.js reactive UI
- SQLite local database
- Add/Edit/Delete people
- Add/Edit/Delete transactions
- Calculate balances (In Flow, Out Flow, Net)
- Smooth animations
- Beautiful gradient UI
- Bottom navigation
- Month selector
- Transaction history
- Offline-first architecture

### 🔄 Can Be Added (Optional Enhancements)
- Export data to CSV
- Backup/Restore database
- Charts with real data visualization
- Search and filter
- Categories for transactions
- Multiple accounts
- Dark mode
- Biometric lock
- Recurring transactions

---

## Performance

- **App Size**: ~5-10 MB
- **Load Time**: < 1 second
- **Database**: Unlimited storage (phone dependent)
- **Performance**: Instant (no network calls)

---

## Publishing to Google Play Store

1. **Build signed release APK** (see above)

2. **Create Play Console account**: https://play.google.com/console
   - Pay $25 one-time fee

3. **Prepare materials**:
   - App icon (512×512)
   - Feature graphic (1024×500)
   - Screenshots (at least 2)
   - App description
   - Privacy policy URL

4. **Create app listing**:
   - Upload APK
   - Fill in all required fields
   - Set category: Finance
   - Set pricing: Free

5. **Submit for review**:
   - Usually takes 1-3 days
   - Fix any issues Google finds
   - App goes live!

---

## Quick Reference Commands

```bash
# Install dependencies
npm install

# Add Android platform (one-time)
npx cap add android

# Sync changes to Android
npx cap sync android

# Open in Android Studio
npx cap open android

# Build debug APK (command line)
cd android && ./gradlew assembleDebug && cd ..

# Install on connected phone
adb install android/app/build/outputs/apk/debug/app-debug.apk

# View app logs
adb logcat | grep Capacitor

# Uninstall from phone
adb uninstall com.cashrecord.app
```

---

## Support & Resources

- **Capacitor Docs**: https://capacitorjs.com/docs
- **Capacitor SQLite**: https://github.com/capacitor-community/sqlite
- **Alpine.js Docs**: https://alpinejs.dev/
- **Android Developer**: https://developer.android.com/

---

## Summary

You now have a **fully offline Cash Record app** that:
- Works without internet
- Stores data locally in SQLite
- Can be installed on any Android phone
- Has the same beautiful UI as the web version
- Requires no backend server

**Next Steps**:
1. Follow this guide step-by-step
2. Build your APK
3. Install on your phone
4. Enjoy tracking your cash flow offline!

**Need help?** Check the troubleshooting section or Android Studio's build output for specific errors.

---

**Happy Building! 🚀**
