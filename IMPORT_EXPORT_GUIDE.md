# 📥📤 Import/Export Guide - Cash Flow App

## Overview

The Cash Flow App now includes comprehensive data management features accessible from the **Settings** tab in the footer navigation.

---

## 🎯 Features

### 1. **Export All Data** 📤
Export your complete transaction history to a CSV file for backup or analysis.

**How to use:**
1. Tap the **Settings** icon in the bottom navigation
2. Tap **"Export All Data"** (green button)
3. CSV file downloads automatically as: `cash-flow-backup-YYYY-MM-DD.csv`
4. File includes all transactions with: Date, Person, Type, Amount, Category, Description

**Use cases:**
- Regular backups of your financial data
- Analyzing data in Excel or Google Sheets
- Creating reports for accounting
- Sharing transaction history
- Migrating to another device

---

### 2. **Import Data** 📥
Restore your data from a previously exported CSV file or import transactions from other sources.

**How to use:**
1. Tap the **Settings** icon in the bottom navigation
2. Tap **"Import Data"** (blue button)
3. Select a CSV file from your device
4. App automatically:
   - Creates missing people
   - Imports all transactions
   - Links transactions to correct people
   - Shows success message with count

**CSV Format Required:**
```csv
Date,Person,Type,Amount,Category,Description
2025-11-18,John Doe,Given,500,Food,"Lunch payment"
2025-11-18,Jane Smith,Received,1000,Salary,"Monthly payment"
```

**Important notes:**
- Header row must match: `Date,Person,Type,Amount,Category,Description`
- Type can be: "Given" or "Received"
- Date format: YYYY-MM-DD
- Descriptions with commas must be in quotes
- People are auto-created if they don't exist
- Duplicate transactions may be created if importing same file multiple times

---

### 3. **Clear All Data** 🗑️
Delete all people and transactions permanently.

**How to use:**
1. Tap the **Settings** icon in the bottom navigation
2. Tap **"Clear All Data"** (red button)
3. Confirm the first warning
4. Confirm the second warning (final confirmation)
5. All data is deleted and you return to empty dashboard

**⚠️ WARNING:**
- This action is **PERMANENT** and **CANNOT BE UNDONE**
- Export your data first before clearing!
- Two confirmation dialogs protect against accidental deletion
- Use this when:
  - Starting fresh with new data
  - Testing the app
  - Removing all financial records

---

## 📊 Settings Information Panel

The Settings view also displays useful app information:

- **Version**: Current app version (1.0.0)
- **Total People**: Number of people/accounts tracked
- **Total Transactions**: Number of all transactions
- **Storage**: Database type (IndexedDB - Offline)
- **Status**: Online/Offline indicator

---

## 🔄 Common Workflows

### Backup Before Clearing
```
1. Settings → Export All Data
2. Save the CSV file somewhere safe
3. Settings → Clear All Data
4. Confirm deletions
```

### Moving to New Device
```
Device 1:
1. Settings → Export All Data
2. Share/email the CSV file to yourself

Device 2:
1. Install app
2. Settings → Import Data
3. Select the CSV file
4. All your data is restored!
```

### Regular Backups
```
Weekly/Monthly:
1. Settings → Export All Data
2. Save to cloud storage (Google Drive, Dropbox, etc.)
3. Keep multiple backup copies
```

### Importing from Excel
```
1. Create CSV with correct format in Excel
2. Columns: Date, Person, Type, Amount, Category, Description
3. Save as CSV (.csv file)
4. Settings → Import Data
5. Select your CSV file
```

---

## 🛡️ Data Safety Tips

1. **Regular Backups**: Export your data weekly or monthly
2. **Multiple Copies**: Keep backups in different locations
3. **Before Updates**: Export before updating the app
4. **Test Imports**: Try importing on test data first
5. **Verify Data**: Check transactions after import
6. **Cloud Storage**: Upload backups to Google Drive, Dropbox, etc.

---

## 🐛 Troubleshooting

### Import Failed
- **Check CSV format**: Must match required header exactly
- **Check date format**: Must be YYYY-MM-DD
- **Check file encoding**: Should be UTF-8
- **Check for special characters**: May cause parsing issues
- **Try smaller file**: Import in batches if file is very large

### Export Shows "No data"
- Make sure you have created transactions first
- Check that people exist in the database
- Try refreshing the app

### Clear Data Not Working
- Make sure you confirm both warning dialogs
- Check browser console for errors
- Try refreshing and clearing again

---

## 📝 CSV Format Details

### Header Row (Required)
```
Date,Person,Type,Amount,Category,Description
```

### Data Rows
- **Date**: YYYY-MM-DD format (e.g., 2025-11-18)
- **Person**: Any text name (e.g., "John Doe")
- **Type**: Either "Given" or "Received" (case-insensitive)
- **Amount**: Numeric value (e.g., 500 or 500.50)
- **Category**: One of: Food, Transport, Shopping, Bills, Entertainment, Health, Other
- **Description**: Any text (wrap in quotes if contains commas)

### Example Valid CSV
```csv
Date,Person,Type,Amount,Category,Description
2025-11-01,John,Given,100,Food,Lunch at restaurant
2025-11-02,Mary,Received,500,Salary,Weekly payment
2025-11-03,Shop A,Given,250.50,Shopping,"Books, pens, and supplies"
2025-11-04,Clinic,Given,1000,Health,Medical checkup
```

---

## 🔐 Privacy & Security

- **All data stays local**: No cloud sync, no servers
- **Offline storage**: Uses browser IndexedDB
- **Your device only**: Data never leaves your phone
- **Manual backups**: You control where CSV files go
- **No tracking**: App doesn't send any analytics

---

## ✨ Advanced Tips

### Excel Analysis
After exporting:
1. Open CSV in Excel
2. Use Pivot Tables for analysis
3. Create charts and graphs
4. Filter by category or person
5. Calculate totals and averages

### Google Sheets Integration
1. Export CSV from app
2. Upload to Google Sheets
3. Share with family/accountant
4. Collaborate on budgets
5. Create automated reports

### Scheduled Backups
Set a reminder to:
- Export data every Sunday
- Save with date in filename
- Upload to cloud storage
- Delete old backups after 3 months

---

## 🎉 Summary

The import/export feature gives you complete control over your financial data:

✅ **Export**: Backup your data anytime as CSV
✅ **Import**: Restore from CSV files
✅ **Clear**: Start fresh when needed
✅ **Safe**: Multiple confirmations prevent accidents
✅ **Portable**: Move data between devices easily
✅ **Compatible**: Works with Excel, Google Sheets, etc.

All accessible from the **Settings** tab! 🎯
