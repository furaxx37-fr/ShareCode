# 🎬 ShareCode - Code Sharing Platform

ShareCode is a modern, web application for sharing code snippets with beautiful syntax highlighting and a sleek dark interface.

![ShareCode Preview](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

## ✨ Features

- 🎨 **Netflix-inspired dark theme** with smooth animations
- 🌈 **Syntax highlighting** for 14+ programming languages
- 🔗 **Unique shareable URLs** for each code snippet
- 📊 **View counter** and analytics
- 📱 **Fully responsive** design
- 📋 **One-click copy** functionality
- ⚡ **Fast and lightweight**

## 🚀 Quick Installation

### Prerequisites
- Apache2 web server
- PHP 7.4+ with PDO MySQL extension
- MySQL 5.7+ or MariaDB
- Git (for cloning)

### Option 1: Automated Installation (Recommended)

```bash
# Clone the repository
git clone https://github.com/yourusername/sharecode.git
cd sharecode

# Run the installation script
chmod +x scripts/install.sh
sudo ./scripts/install.sh
```

### Option 2: Manual Installation

#### 1. Clone Repository
```bash
git clone https://github.com/yourusername/sharecode.git
cd sharecode
```

#### 2. Copy Files to Web Directory
```bash
sudo cp -r . /var/www/html/sharecode/
sudo chown -R www-data:www-data /var/www/html/sharecode
sudo chmod -R 755 /var/www/html/sharecode
```

#### 3. Create Database
```bash
mysql -u root -p
```

```sql
CREATE DATABASE sharecode;
CREATE USER 'sharecode_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON sharecode.* TO 'sharecode_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### 4. Import Database Schema
```bash
mysql -u root -p sharecode < scripts/database.sql
```

#### 5. Configure Database Connection
Edit `includes/config.php` and update your database credentials:

```php
$host = 'localhost';
$dbname = 'sharecode';
$username = 'sharecode_user';
$password = 'your_secure_password';
```

#### 6. Start Apache
```bash
sudo systemctl start apache2
sudo systemctl enable apache2
```

## 🌐 Usage

1. **Access the application**: `http://your-server-ip/sharecode/`
2. **Create a code snippet**:
   - Enter a title (optional)
   - Select programming language
   - Paste your code
   - Click "Share Code"
3. **Share the generated URL** with others
4. **View shared code** with beautiful syntax highlighting

## 📁 Project Structure

```
sharecode/
├── index.php              # Main application page
├── view.php               # Code viewing page
├── api/
│   ├── create.php         # API endpoint for creating pastes
│   └── get.php            # API endpoint for retrieving pastes
├── includes/
│   └── config.php         # Database configuration
├── scripts/
│   ├── install.sh         # Automated installation script
│   └── database.sql       # Database schema
├── docs/
│   └── DEPLOYMENT.md      # Deployment guide
└── README.md              # This file
```

## 🎨 Supported Languages

- JavaScript
- Python
- PHP
- Java
- C++
- C
- C#
- HTML
- CSS
- SQL
- JSON
- XML
- Bash
- Plain Text

## 🔧 Configuration

### Database Settings
Edit `includes/config.php` to customize database connection settings.

### Security
- Change default database password
- Use HTTPS in production
- Configure proper file permissions
- Regular security updates

## 🚀 Deployment

### Production Deployment
See [DEPLOYMENT.md](docs/DEPLOYMENT.md) for detailed production deployment instructions.

### Docker Deployment (Coming Soon)
Docker support will be added in future releases.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🐛 Bug Reports

If you find a bug, please open an issue on GitHub with:
- Description of the bug
- Steps to reproduce
- Expected behavior
- Screenshots (if applicable)

## 🌟 Support

If you like this project, please give it a ⭐ on GitHub!


---

Made with ❤️ 
