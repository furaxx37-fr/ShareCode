#!/bin/bash

# ShareCode Automated Installation Script
# This script installs ShareCode on Ubuntu/Debian systems

set -e

echo "🎬 ShareCode Installation Script"
echo "================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   print_error "This script must be run as root (use sudo)"
   exit 1
fi

print_status "Starting ShareCode installation..."

# Update system packages
print_status "Updating system packages..."
apt update

# Install required packages
print_status "Installing required packages..."
apt install -y apache2 php php-mysql php-pdo mysql-server

# Start and enable services
print_status "Starting services..."
systemctl start apache2
systemctl enable apache2
systemctl start mysql
systemctl enable mysql

# Create web directory
print_status "Setting up web directory..."
mkdir -p /var/www/html/sharecode

# Copy application files
print_status "Copying application files..."
cp -r api /var/www/html/sharecode/
cp -r includes /var/www/html/sharecode/
cp index.php /var/www/html/sharecode/
cp view.php /var/www/html/sharecode/

# Set proper permissions
print_status "Setting file permissions..."
chown -R www-data:www-data /var/www/html/sharecode
chmod -R 755 /var/www/html/sharecode

# Database setup
print_status "Setting up database..."
echo "Enter MySQL root password (press Enter if no password):"
read -s mysql_root_password
echo "Enter password for ShareCode database user:"
read -s sharecode_password

if [ -z "$mysql_root_password" ]; then
    mysql_cmd="mysql -u root"
else
    mysql_cmd="mysql -u root -p$mysql_root_password"
fi

# Create database and user
print_status "Creating database and user..."
$mysql_cmd << MYSQL_EOF
CREATE DATABASE IF NOT EXISTS sharecode;
CREATE USER IF NOT EXISTS 'sharecode_user'@'localhost' IDENTIFIED BY '$sharecode_password';
GRANT ALL PRIVILEGES ON sharecode.* TO 'sharecode_user'@'localhost';
FLUSH PRIVILEGES;
MYSQL_EOF

# Import database schema
print_status "Importing database schema..."
$mysql_cmd sharecode < database.sql

# Update configuration file
print_status "Updating configuration..."
sed -i "s/ShareCode2024/$sharecode_password/g" /var/www/html/sharecode/includes/config.php

# Get server IP
SERVER_IP=$(curl -s ifconfig.me 2>/dev/null || echo "localhost")

print_success "Installation completed successfully!"
echo ""
echo "🎉 ShareCode is now installed and ready to use!"
echo ""
echo "📍 Access URLs:"
echo "   Main Application: http://$SERVER_IP/sharecode/"
echo "   Local Access: http://localhost/sharecode/"
echo ""
echo "🔧 Database Details:"
echo "   Database: sharecode"
echo "   User: sharecode_user"
echo "   Password: [as entered during installation]"
echo ""
echo "📝 Next Steps:"
echo "   1. Visit the application URL"
echo "   2. Create your first code snippet"
echo "   3. Share the generated URL with others"
echo ""
echo "🔒 Security Notes:"
echo "   - Consider using HTTPS in production"
echo "   - Regularly update your system packages"
echo "   - Backup your database regularly"
echo ""
print_success "Happy coding! 🚀"
