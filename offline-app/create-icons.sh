#!/bin/bash
# Simple icon creation script

echo "Creating app icons..."

# Check if ImageMagick is installed
if command -v convert &> /dev/null; then
    echo "Using ImageMagick to create icons..."
    
    # Create 192x192 icon
    convert -size 192x192 -gravity center \
      -background "#5468ff" -fill white \
      -pointsize 100 -font Arial-Bold \
      label:"CR" icon-192.png
    
    # Create 512x512 icon
    convert -size 512x512 -gravity center \
      -background "#5468ff" -fill white \
      -pointsize 300 -font Arial-Bold \
      label:"CR" icon-512.png
    
    echo "✓ Icons created successfully!"
    ls -lh icon-*.png
else
    echo "ImageMagick not found."
    echo ""
    echo "Alternative options:"
    echo "  1. Install ImageMagick: sudo apt-get install imagemagick"
    echo "  2. Use online tool: https://www.pwabuilder.com/imageGenerator"
    echo "  3. Create manually in any image editor (192x192 and 512x512 PNG files)"
    echo ""
    echo "For testing, you can skip icons - they're optional for browser testing"
fi

echo ""
echo "Current icon files:"
ls -lh icon-*.png 2>/dev/null || echo "  No icons found yet"
