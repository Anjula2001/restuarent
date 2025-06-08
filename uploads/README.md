# Uploads Directory

This directory stores uploaded images for menu items.

## How it works across different computers:

1. **Image Upload**: When you upload an image through the admin panel, it gets stored in `uploads/menu-items/` 
2. **Relative Paths**: The system now uses relative paths (like `uploads/menu-items/image.jpg`) instead of absolute URLs
3. **Cross-Platform**: This works on any computer because the paths are relative to the project root
4. **Fallback Images**: If an uploaded image can't be found, the system automatically falls back to default images in the `images/popular/` folder

## When sharing the project:

1. Copy the entire `restuarent` folder to the new computer
2. The uploaded images in this `uploads` folder will work automatically
3. No need to change any image URLs or paths

## Default Images:

- `images/popular/3.png` - Default fallback image for all items
- `images/popular/1.png` - Alternative fallback image
- `images/popular/2.png` - Alternative fallback image

The system automatically handles missing images gracefully.
