#!/bin/bash
# Quick test script for Cash Record offline app

echo "╔════════════════════════════════════════╗"
echo "║   Cash Record - Offline App Tester    ║"
echo "╚════════════════════════════════════════╝"
echo ""

# Check if we're in the right directory
if [ ! -f "index.html" ]; then
    echo "❌ Error: index.html not found!"
    echo "Please run this script from the offline-app directory"
    exit 1
fi

# Check for icons
if [ ! -f "icon-192.png" ] || [ ! -f "icon-512.png" ]; then
    echo "⚠️  Warning: App icons not found!"
    echo "Run ./create-icons.sh to create them (optional for testing)"
    echo ""
fi

echo "Starting web server..."
echo ""

# Try different server options
if command -v python3 &> /dev/null; then
    echo "✓ Using Python 3"
    echo "📱 Open your browser to: http://localhost:8080"
    echo "🛑 Press Ctrl+C to stop the server"
    echo ""
    python3 -m http.server 8080
elif command -v python &> /dev/null; then
    echo "✓ Using Python 2"
    echo "📱 Open your browser to: http://localhost:8080"
    echo "🛑 Press Ctrl+C to stop the server"
    echo ""
    python -m SimpleHTTPServer 8080
elif command -v php &> /dev/null; then
    echo "✓ Using PHP"
    echo "📱 Open your browser to: http://localhost:8080"
    echo "🛑 Press Ctrl+C to stop the server"
    echo ""
    php -S localhost:8080
elif command -v npx &> /dev/null; then
    echo "✓ Using Node.js http-server"
    echo "📱 Open your browser to: http://localhost:8080"
    echo "🛑 Press Ctrl+C to stop the server"
    echo ""
    npx http-server -p 8080
else
    echo "❌ No web server found!"
    echo ""
    echo "Please install one of the following:"
    echo "  • Python 3: sudo apt-get install python3"
    echo "  • PHP: sudo apt-get install php"
    echo "  • Node.js: https://nodejs.org/"
    exit 1
fi
