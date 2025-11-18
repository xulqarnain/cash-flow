# How to Build APK (No Android Studio!)

## 🎯 The Easiest Way

### Step 1: Push your code to GitHub
```bash
git push origin claude/cash-tracker-app-013kLMPMNjkPaxsmBTW4wZRw
```

### Step 2: Go to GitHub Actions
Open this URL in your browser:
```
https://github.com/xulqarnain/cash-flow/actions
```

### Step 3: Run the workflow
1. Click on **"Build Android APK"** on the left
2. Click **"Run workflow"** button (right side)
3. Select branch: `claude/cash-tracker-app-013kLMPMNjkPaxsmBTW4wZRw`
4. Click green **"Run workflow"** button

### Step 4: Wait 5-10 minutes
Watch the build progress with the spinning icon. When done, you'll see a green checkmark ✓

### Step 5: Download your APK
1. Click on the completed workflow run
2. Scroll to bottom → **"Artifacts"** section
3. Click **"cash-record-app-debug"** to download
4. Extract the ZIP file
5. You'll get **`app-debug.apk`**

### Step 6: Install on your phone
**Option A - USB:**
```bash
adb install app-debug.apk
```

**Option B - Direct:**
1. Copy APK to phone (email, USB, Google Drive, etc.)
2. Open APK file on phone
3. Allow "Install unknown apps"
4. Tap Install

---

## 🚀 Even Easier (With Script)

If you have GitHub CLI installed:

```bash
./build-apk.sh
```

This script will:
- ✓ Push your code
- ✓ Trigger the build
- ✓ Watch progress
- ✓ Download APK automatically

**Install GitHub CLI:**
- Mac: `brew install gh`
- Ubuntu: `sudo apt install gh`
- Windows: Download from https://cli.github.com/

---

## 🤔 Why is this better than Android Studio?

| Android Studio | GitHub Actions |
|---------------|----------------|
| 10+ GB download | Nothing to install |
| Complex setup | Just use GitHub |
| Build on your computer | Build in the cloud |
| Slow on old PCs | Fast servers |

---

## ⏱️ How long does it take?

- **First build**: ~8-10 minutes
- **Subsequent builds**: ~4-6 minutes

The build runs in GitHub's cloud servers, so it doesn't slow down your computer!

---

## 🎁 Bonus Features

### Automatic Builds
Every time you push code to GitHub, APK builds automatically!

### Download Old APKs
All your APKs are saved in the Artifacts section for 30 days.

### Multiple Branches
Works on any `claude/**` branch and `main` branch.

---

## 🆘 Troubleshooting

**Build failed?**
- Check the logs in GitHub Actions
- Click on the failed run → Click "Build APK" job
- Read the error message

**Can't download APK?**
- Make sure build is complete (green checkmark)
- Look for "Artifacts" at the very bottom
- Download will be a ZIP file - extract it

**APK won't install on phone?**
- Enable "Install unknown apps" in phone settings
- Make sure you're installing on Android (not iOS)

---

## 📱 What You Get

After installation, you'll have:
- ✅ Fully offline Cash Record app
- ✅ All data stored locally
- ✅ No internet needed
- ✅ Same beautiful UI
- ✅ Works instantly

---

## 🎯 Summary

**Super Simple Method:**
1. Push code: `git push`
2. Go to: https://github.com/xulqarnain/cash-flow/actions
3. Click "Build Android APK" → "Run workflow"
4. Wait 5-10 minutes
5. Download APK from Artifacts
6. Install on phone!

**No Android Studio needed!** 🎉

---

Need more details? See: **BUILD_APK_NO_ANDROID_STUDIO.md**
