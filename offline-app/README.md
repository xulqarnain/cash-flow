# Cash Record - Offline Version

This is the fully offline version of Cash Record built with Alpine.js and Capacitor SQLite.

## Features

✅ **100% Offline** - No internet or server required
✅ **Local Storage** - SQLite database on Android, IndexedDB on web
✅ **Beautiful UI** - Same design as Laravel version
✅ **Smooth Animations** - Native app-like experience
✅ **PWA Support** - Can be installed on mobile from browser
✅ **Native APK** - Build real Android app with Capacitor

## Files

- **index.html** - Main app UI built with Alpine.js
- **app.js** - Business logic, database operations, CRUD functions
- **service-worker.js** - Offline caching and PWA support
- **manifest.json** - PWA manifest for installable web app
- **offline.html** - Fallback page when offline
- **icon-192.png** - App icon (small)
- **icon-512.png** - App icon (large)

## Quick Start

### Test in Browser (PWA Mode)

1. Start a simple HTTP server:
   ```bash
   # Option 1: Python
   python3 -m http.server 8080

   # Option 2: Node.js
   npx http-server -p 8080

   # Option 3: PHP
   php -S localhost:8080
   ```

2. Open browser: http://localhost:8080

3. Try the app:
   - Add people
   - Add transactions
   - View balances
   - Check history

4. Install as PWA:
   - Chrome menu → "Install app"
   - Works offline after installation

### Build Android APK

See the comprehensive guide: [BUILD_OFFLINE_APK.md](../BUILD_OFFLINE_APK.md)

Quick version:
```bash
# Install dependencies
npm install

# Add Android platform
npx cap add android

# Sync files
npx cap sync android

# Open in Android Studio
npx cap open android

# Build → Build APK(s)
```

## Architecture

### Frontend
- **Alpine.js** - Lightweight reactive framework (~15KB)
- **Tailwind CSS** - Utility-first CSS (via CDN)
- **Vanilla JavaScript** - For database operations

### Database
- **Android**: Native SQLite via @capacitor-community/sqlite
- **Web**: IndexedDB fallback
- **Schema**:
  - `people` table: id, name, created_at
  - `transactions` table: id, person_id, type, amount, date, created_at

### Offline Support
- Service Worker caches all static assets
- Cache-first strategy for instant loading
- Works completely offline after first visit

## Data Flow

```
User Action
    ↓
Alpine.js Event Handler
    ↓
app.js Function (addPerson, addTransaction, etc.)
    ↓
Database Layer (SQLite or IndexedDB)
    ↓
Data Persistence (Phone Storage)
    ↓
UI Update (Alpine.js Reactivity)
```

## Key Functions

### Database Operations
- `initDatabase()` - Initialize SQLite/IndexedDB
- `createTables()` - Create database schema

### People CRUD
- `loadPeople()` - Fetch all people
- `addPerson(person)` - Add new person
- `updatePerson(id, person)` - Update existing person
- `deletePerson(id)` - Delete person and their transactions

### Transactions CRUD
- `loadTransactions()` - Fetch all transactions
- `addTransaction(transaction)` - Add new transaction
- `deleteTransaction(id)` - Delete transaction

### Calculations
- `getTotalInFlow()` - Sum of all 'receive' transactions
- `getTotalOutFlow()` - Sum of all 'give' transactions
- `getTotalBalance()` - Net balance (in - out)
- `getPersonBalance(personId)` - Balance for specific person

## Customization

### Change Colors
Edit the gradient in `index.html`:
```html
<body class="bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600">
```

### Add New Fields
1. Update database schema in `app.js` → `createTables()`
2. Add form fields in `index.html`
3. Update CRUD functions to include new fields

### Add Features
Common additions:
- Categories for transactions
- Search and filter
- Export to CSV
- Backup/Restore
- Charts with Chart.js
- Dark mode

## Browser Compatibility

✅ Chrome/Edge (Android/Desktop)
✅ Safari (iOS/Mac) - Limited PWA support
✅ Firefox (Android/Desktop)
⚠️ iOS PWA has limitations (no background sync, limited storage)

## Storage Limits

- **Android SQLite**: Unlimited (phone storage)
- **Web IndexedDB**: ~50MB (varies by browser)
- **Recommended**: Use native APK for unlimited storage

## Troubleshooting

### Service Worker Not Registering
- Check browser console for errors
- Must use HTTPS or localhost
- Clear browser cache and reload

### Database Not Persisting
- **Web**: Check if IndexedDB is enabled
- **Android**: Check SQLite plugin is registered
- **Both**: Check browser/app permissions

### Icons Not Showing
- Create icon-192.png and icon-512.png
- Must be in same directory as index.html
- Update manifest.json paths if needed

### Animations Laggy
- Check if too many transactions (>1000)
- Consider pagination or virtual scrolling
- Reduce animation complexity

## Development

### Hot Reload During Development
```bash
# Terminal 1: Start HTTP server
python3 -m http.server 8080

# Terminal 2: Watch for changes (optional)
# Use browser dev tools to disable cache
```

### Testing on Real Device
```bash
# Option 1: Use ngrok for HTTPS
ngrok http 8080
# Use ngrok URL on phone

# Option 2: Connect via local network
# Find your IP: ifconfig | grep inet
# Visit http://YOUR_IP:8080 on phone
```

### Debugging
```javascript
// Add console.log in app.js
async addPerson(person) {
    console.log('Adding person:', person);
    // ... rest of function
}
```

Check console in:
- **Browser**: F12 → Console tab
- **Android**: Chrome DevTools → Remote Devices
- **Android (alternative)**: `adb logcat | grep Capacitor`

## Performance

- **Initial Load**: < 1 second
- **Database Query**: < 10ms
- **UI Update**: Instant (reactive)
- **App Size**: ~5-10 MB (with icons)

## Security

- All data stored locally on device
- No network requests (after initial load)
- No authentication needed (single-user app)
- Consider adding PIN/biometric lock for sensitive data

## Future Enhancements

Possible improvements:
- [ ] Cloud sync (optional)
- [ ] Multiple accounts/currencies
- [ ] Recurring transactions
- [ ] Budget tracking
- [ ] Reports and analytics
- [ ] Export to PDF
- [ ] Biometric authentication
- [ ] Widget support
- [ ] Wear OS companion

## Resources

- [Alpine.js Docs](https://alpinejs.dev/)
- [Capacitor Docs](https://capacitorjs.com/)
- [Capacitor SQLite](https://github.com/capacitor-community/sqlite)
- [Tailwind CSS](https://tailwindcss.com/)
- [Service Workers](https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API)

## License

Same as main Cash Record project

---

**Enjoy your offline cash tracking! 💰**
