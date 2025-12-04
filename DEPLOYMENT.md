# ShareCode Deployment Guide

This guide provides detailed instructions for deploying ShareCode on various environments.

## 🚀 Quick Installation (Recommended)

### Prerequisites
- Ubuntu 18.04+ or Debian 9+
- Root or sudo access
- Internet connection

### One-Command Installation
```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/ShareCode.git
cd ShareCode

# Run the automated installer
sudo ./install.sh
```

## 📋 Manual Installation

### Step 1: Install Dependencies
```bash
# Update system
sudo apt update

# Install LAMP stack
sudo apt install -y apache2 php php-mysql php-pdo mysql-server
```

### Step 2: Configure Services
```bash
# Start and enable services
sudo systemctl start apache2
sudo systemctl enable apache2
sudo systemctl start mysql
sudo systemctl enable mysql
```

### Step 3: Setup Database
```bash
# Login to MySQL
sudo mysql -u root

# Create database and user
CREATE DATABASE sharecode;
CREATE USER 'sharecode_user'@'localhost' IDENTIFIED BY 'YOUR_PASSWORD';
GRANT ALL PRIVILEGES ON sharecode.* TO 'sharecode_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import database schema
mysql -u root sharecode < database.sql
```

### Step 4: Deploy Application
```bash
# Create web directory
sudo mkdir -p /var/www/html/sharecode

# Copy files
sudo cp -r api includes index.php view.php /var/www/html/sharecode/

# Set permissions
sudo chown -R www-data:www-data /var/www/html/sharecode
sudo chmod -R 755 /var/www/html/sharecode
```

### Step 5: Configure Application
Edit `/var/www/html/sharecode/includes/config.php` and update the database password:
```php
$password = 'YOUR_PASSWORD'; // Replace with your actual password
```

## 🐳 Docker Deployment

### Using Docker Compose
```yaml
version: '3.8'
services:
  web:
    image: php:8.1-apache
    ports:
      - "80:80"
    volumes:
      - ./:/var/www/html/sharecode
    depends_on:
      - db
    environment:
      - DB_HOST=db
      - DB_NAME=sharecode
      - DB_USER=sharecode_user
      - DB_PASS=ShareCode2024

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: sharecode
      MYSQL_USER: sharecode_user
      MYSQL_PASSWORD: ShareCode2024
    volumes:
      - mysql_data:/var/lib/mysql
      - ./database.sql:/docker-entrypoint-initdb.d/init.sql

volumes:
  mysql_data:
```

Run with:
```bash
docker-compose up -d
```

## ☁️ Cloud Deployment

### AWS EC2
1. Launch Ubuntu 20.04 LTS instance
2. Configure security group (HTTP port 80)
3. Connect via SSH
4. Run the installation script

### Google Cloud Platform
1. Create Compute Engine instance
2. Allow HTTP traffic
3. SSH into instance
4. Run the installation script

### DigitalOcean Droplet
1. Create Ubuntu droplet
2. Access via console or SSH
3. Run the installation script

## 🔧 Configuration Options

### Environment Variables
Create `.env` file in the root directory:
```env
DB_HOST=localhost
DB_NAME=sharecode
DB_USER=sharecode_user
DB_PASS=your_password
APP_URL=http://your-domain.com
DEBUG=false
```

### Apache Virtual Host
Create `/etc/apache2/sites-available/sharecode.conf`:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/html/sharecode
    
    <Directory /var/www/html/sharecode>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/sharecode_error.log
    CustomLog ${APACHE_LOG_DIR}/sharecode_access.log combined
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite sharecode
sudo a2enmod rewrite
sudo systemctl reload apache2
```

### SSL/HTTPS Setup
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get SSL certificate
sudo certbot --apache -d your-domain.com
```

## 🔒 Security Hardening

### Database Security
```bash
# Run MySQL secure installation
sudo mysql_secure_installation
```

### File Permissions
```bash
# Secure file permissions
sudo find /var/www/html/sharecode -type f -exec chmod 644 {} \;
sudo find /var/www/html/sharecode -type d -exec chmod 755 {} \;
sudo chown -R www-data:www-data /var/www/html/sharecode
```

### Firewall Configuration
```bash
# Configure UFW
sudo ufw allow ssh
sudo ufw allow http
sudo ufw allow https
sudo ufw enable
```

## 📊 Monitoring and Maintenance

### Log Files
- Apache logs: `/var/log/apache2/`
- MySQL logs: `/var/log/mysql/`
- Application logs: Check browser console for frontend issues

### Database Backup
```bash
# Create backup
mysqldump -u sharecode_user -p sharecode > backup_$(date +%Y%m%d).sql

# Restore backup
mysql -u sharecode_user -p sharecode < backup_file.sql
```

### Updates
```bash
# Update system packages
sudo apt update && sudo apt upgrade

# Backup before updates
cp -r /var/www/html/sharecode /var/www/html/sharecode_backup_$(date +%Y%m%d)
```

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
- Check MySQL service: `sudo systemctl status mysql`
- Verify credentials in `config.php`
- Check MySQL user permissions

**Permission Denied**
- Fix file permissions: `sudo chown -R www-data:www-data /var/www/html/sharecode`
- Check Apache error logs: `sudo tail -f /var/log/apache2/error.log`

**Syntax Highlighting Not Working**
- Check browser console for JavaScript errors
- Verify Prism.js CDN links are accessible
- Clear browser cache

**404 Not Found**
- Check Apache virtual host configuration
- Verify DocumentRoot path
- Enable mod_rewrite: `sudo a2enmod rewrite`

### Performance Optimization

**Database Optimization**
```sql
-- Add indexes for better performance
CREATE INDEX idx_paste_id ON pastes(paste_id);
CREATE INDEX idx_created_at ON pastes(created_at);
```

**Apache Optimization**
Enable compression in `/etc/apache2/apache2.conf`:
```apache
LoadModule deflate_module modules/mod_deflate.so
<Location />
    SetOutputFilter DEFLATE
</Location>
```

## 📞 Support

For issues and questions:
1. Check the troubleshooting section
2. Review application logs
3. Create an issue on GitHub
4. Contact the development team

---

**Happy Coding! 🚀**
