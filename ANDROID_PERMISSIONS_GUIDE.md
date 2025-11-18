# 🔐 Android Permissions Guide - Cash Flow App

## Overview

The Cash Flow app requires certain Android permissions to enable **import/export** functionality. This guide explains what permissions are needed, why they're required, and how they're configured.

---

## 📋 Required Permissions

### 1. **Storage Permissions** (For Import/Export)

#### For Android 12 and Below:
- `READ_EXTERNAL_STORAGE` - Read CSV files for import
- `WRITE_EXTERNAL_STORAGE` - Save CSV files for export

#### For Android 13+ (Scoped Storage):
- `READ_MEDIA_IMAGES` - Access to media files
- `READ_MEDIA_VIDEO` - Access to media files
- `READ_MEDIA_AUDIO` - Access to media files

### 2. **Network Permissions** (For Status Display)
- `INTERNET` - Check online/offline status
- `ACCESS_NETWORK_STATE` - Monitor network connectivity

---

## 🎯 Why These Permissions Are Needed

### Storage Permissions:
✅ **Export Data**: Write CSV backup files to Downloads folder
✅ **Import Data**: Read CSV files from device storage
✅ **Backup/Restore**: Access files for data migration

### Network Permissions:
✅ **Online/Offline Indicator**: Show connection status in the app
✅ **No Data Sent**: App doesn't upload any data, just checks connectivity

---

## 🛠️ How Permissions Are Configured

### Automatic Configuration

When you build the APK using either method below, permissions are **automatically configured**:

1. **GitHub Actions Build** (Recommended)
   - Permissions are added during the automated build process
   - No manual steps required

2. **Local Build Script**
   ```bash
   ./build-apk-local.sh
   ```
   - Script automatically copies permission configurations
   - AndroidManifest.xml updated with all permissions

### Files Involved:

**1. capacitor.config.json**
```json
{
  "android": {
    "permissions": [
      "INTERNET",
      "ACCESS_NETWORK_STATE",
      "READ_EXTERNAL_STORAGE",
      "WRITE_EXTERNAL_STORAGE",
      "READ_MEDIA_IMAGES",
      "READ_MEDIA_VIDEO",
      "READ_MEDIA_AUDIO"
    ]
  }
}
```

**2. android-resources/AndroidManifest.xml**
- Contains full permission declarations
- Includes version-specific permissions (Android 12 vs 13+)
- Configured FileProvider for secure file access

**3. android-resources/file_paths.xml**
- Defines accessible file paths
- Allows app to read/write in Downloads and Documents folders

---

## 📱 Permission Prompts on Installation

### First Time Install:

When you first install the app, Android will prompt:

**Android 12 and below:**
> "Cash Record would like to access photos, media, and files on your device"
> - **Allow** ✅ (Required for import/export)
> - Deny ❌ (Import/export won't work)

**Android 13+:**
> "Cash Record would like to access photos and media on your device"
> - **Allow** ✅ (Required for import/export)
> - Deny ❌ (Import/export won't work)

### After Installation:

If you denied permissions, you can enable them:
1. Go to **Settings** → **Apps** → **Cash Record**
2. Tap **Permissions**
3. Enable **Storage** or **Files and media**

---

## 🔒 Privacy & Security

### What the App DOES:
✅ Stores all data **locally** in IndexedDB (offline)
✅ Uses storage permission **only** for CSV import/export
✅ Exports data to **Downloads** folder (your control)
✅ Imports data from files **you select**
✅ Checks network status (no data sent)

### What the App DOES NOT:
❌ Upload data to any server
❌ Send data over the internet
❌ Access photos, contacts, or other personal files
❌ Track your activity
❌ Share data with third parties
❌ Use permissions beyond CSV import/export

---

## 🧪 Testing Permissions

### Verify Permissions After Install:

1. **Go to Android Settings**
   ```
   Settings → Apps → Cash Record → Permissions
   ```

2. **Check Granted Permissions:**
   - ✅ Storage (or Files and media)
   - ✅ (Optional) Network access

3. **Test Import/Export:**
   - Open app → Settings tab
   - Try **Export All Data** (should download CSV)
   - Try **Import Data** (should let you select file)

---

## ❓ Troubleshooting

### Export Not Working:
**Problem**: "Export" button does nothing or shows error

**Solution**:
1. Check storage permission is granted
2. Go to Android Settings → Apps → Cash Record → Permissions
3. Enable "Storage" or "Files and media"
4. Try export again

### Import Not Working:
**Problem**: Cannot select CSV file or import fails

**Solution**:
1. Verify storage permission is granted
2. Make sure CSV file is on device storage (Downloads or Documents)
3. Check CSV format matches required structure
4. Try placing file in Downloads folder

### Permission Denied Error:
**Problem**: App shows "Permission denied" when importing/exporting

**Solution**:
1. Uninstall and reinstall the app
2. When prompted, **Allow** storage permissions
3. Or manually enable in Settings → Apps → Permissions

### Permissions Not Prompted:
**Problem**: App doesn't ask for permissions on install

**Solution**:
1. This might happen on some Android versions
2. Manually grant permissions in Settings → Apps → Cash Record → Permissions
3. Enable "Storage" or "Files and media"

---

## 🔄 Re-granting Permissions

If you previously denied permissions:

### Method 1: Through App Info
```
Settings → Apps → Cash Record → Permissions → Storage → Allow
```

### Method 2: Reinstall
```
1. Export your data first (if possible)
2. Uninstall the app
3. Reinstall from APK
4. Allow permissions when prompted
5. Import your data back
```

---

## 📂 File Access Locations

The app can access files in these locations:

✅ **Downloads** folder (primary location for exports)
✅ **Documents** folder (alternative location)
✅ **App's private storage** (cache and app data)

❌ Cannot access: System files, other apps' data, root directories

---

## 🛡️ Security Best Practices

1. **Only Install from Trusted Sources**
   - Build yourself using GitHub Actions
   - Or build locally from source code
   - Don't download from unknown websites

2. **Review Permissions Before Installing**
   - Check that only necessary permissions are requested
   - This app should only ask for Storage and Network

3. **Keep App Updated**
   - Rebuild periodically for latest security patches
   - Check for updates to Capacitor framework

4. **Backup Regularly**
   - Export your data weekly/monthly
   - Store backups in secure cloud storage

---

## 📊 Permission Usage by Feature

| Feature | Permission Needed | Why |
|---------|------------------|-----|
| Export All Data | `WRITE_EXTERNAL_STORAGE` | Save CSV to Downloads |
| Import Data | `READ_EXTERNAL_STORAGE` | Read CSV from storage |
| Online/Offline Indicator | `INTERNET`, `ACCESS_NETWORK_STATE` | Check connection status |
| Add/Edit Transactions | None | Uses local IndexedDB |
| View Dashboard | None | All data is local |
| Charts & Stats | None | Computed from local data |

---

## 🚀 Summary

### For Users:
✅ **Storage permission is required** for import/export to work
✅ **Allow when prompted** during installation
✅ **All data stays on your device** - no cloud sync
✅ **Permissions are only used** for CSV file access

### For Developers:
✅ Permissions are **auto-configured** during build
✅ Use `build-apk-local.sh` for local builds
✅ Or use GitHub Actions for cloud builds
✅ AndroidManifest.xml includes all necessary declarations
✅ Scoped Storage compliant for Android 13+

---

## 📞 Support

If you have permission-related issues:
1. Check this guide first
2. Try reinstalling with permissions allowed
3. Check Android version compatibility (Android 7.0+)
4. Report issues with device model and Android version

---

**Your privacy is important! 🔒**
The app only uses permissions for the features you explicitly use (import/export).
All financial data stays on your device.
