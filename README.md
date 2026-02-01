# 🎌 Zoro.to - Clone

> **Status**: 🚀 **Project Revival in Progress**

This project is a revival of an anime streaming platform. Feel free to customize it as you like!


## 📋 Requirements

### System Requirements
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher (or MariaDB)
- **Web Server**: Apache with mod_rewrite enabled
- **Extensions**: mysqli, json, curl

### Development Environment
- **XAMPP** (recommended for local development)

## 🚀 Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/SailorSammyy/zoro.to-clone.git
cd zoro.to-clone
```

### 2. Database Setup
1. Import the database schema
2. Create a database named `anime` (or your preferred name)

### 3. Configuration
Edit `_config.php` with your settings:

```php
<?php 
// Database Configuration
$conn = mysqli_connect("localhost", "root", "", "anime") or die("Connection failed");

// Website Settings
$websiteTitle = "Zoro";
$websiteUrl = "//{$_SERVER['SERVER_NAME']}"; // if on localhost then add a slash(/) after the '}' and type the folder name
$websiteLogo = $websiteUrl . "/files/images/logo_zoro.png";
$contactEmail = "your-email@gmail.com";
$version = "1.0";

// Social Links
$discord = "https://dsc.gg/sailorsammyy";
$github = "https://github.com/sailorsammyy";
$twitter = "https://x.com/";

// External Services
$disqus = "https://your-disqus-shortname.disqus.com/embed.js";
$api = "https://your-api-endpoint.com"; // No slash(/) at the end

$banner = $websiteUrl . "/files/images/banner.png";
?>
```

### 4. Local Development (XAMPP)

1. **Install XAMPP**: Download from [apachefriends.org](https://www.apachefriends.org/)

2. **Enable mod_rewrite**

3. **Start Services**:
   - Start Apache and MySQL in XAMPP Control Panel

4. **Access Your Site**:
   - Visit: `http://localhost/zoro`


## 🔧 Configuration Guide

### Database Configuration
| Parameter | Description | Example |
|-----------|-------------|---------|
| `hostname` | Database host | `localhost` |
| `username` | DB username | `root` |
| `password` | DB password | `` (empty for localhost) |
| `database` | Database name | `anime` |

## 🤝 Contributing

We welcome contributions! Feel free to commit, suggest improvements, or add new features.

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 🙏 Acknowledgements

- **[AniWatch API](https://github.com/ghoshRitesh12/aniwatch-api)** - Provides anime data and streaming links
- **Bootstrap** - For responsive design components
- **Font Awesome** - For beautiful icons
- **jQuery** - For interactive functionality

## 📋 TODO - Zoro.to Clone Roadmap


- [x] **Authentication System**
  - [x] Registration and login
  - [x] Profile
  - [x] Watch history tracking
  - [x] Favorites/Bookmarks system

- [ ] **Search**
  - [ ] Advanced search filters
  - [ ] Recently added anime section

- [ ] **User Experience**
  - [ ] Dark/Light theme toggle
  - [ ] Progressive Web App (PWA)
  - [ ] Offline viewing capabilities
  - [ ] Download episodes feature

- [ ] **Features**
  - [ ] User reviews and ratings
  - [ ] Comment system for episodes
  - [ ] sharing integration
  - [ ] User recommendations

- [ ] **Performance**
  - [ ] Database optimization
  - [ ] Caching system implementation

- [ ] **Advanced Features**
  - [ ] AI powered anime recommendations
  - [ ] Watch party functionality
  - [ ] Anime news integration

### 🐛 Known Issues
- [ ] Schedule loading

---

## 💬 Community & Support

[![Join our Discord server!](https://invidget.switchblade.xyz/SUsQnPWvxT)](https://discord.com/invite/SUsQnPWvxT)

- **Discord**: Join our community for support and discussions
- **Issues**: Report bugs or request features on GitHub



---

<div align="center">
  <p>⭐ Star this repo if you found it helpful!</p>
</div>
