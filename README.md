# An attacks-resilient hybrid system for hosting a website that handles automatic backup and recovery processes.
---
### IAL ER - Solution Architecture and Cyber Security course 2024 
---

## Installation and Configuration Guide for PHP, MariaDB, and Apache on Ubuntu and AWS EC2

## Introduction

This guide provides step-by-step instructions for installing and configuring PHP, MariaDB, and Apache on both Ubuntu and AWS EC2 Amazon Linux. It also covers additional tasks such as setting up HTTPS, configuring DNS, and automating backups.

## Prerequisites

- Access to an Ubuntu VM and an Amazon Linux EC2 instance.
- Basic knowledge of Linux command-line operations.
- AWS CLI installed and configured on your local machine (for S3 operations).
- Basic knowledge of Web Development
- Basic knowledge of Database Systems 
- Basic knowledge of System Desing concepts
- Basic knowledge of Networking
- Basic knowledge of Security

## Installation on Ubuntu

### 1. Update System Packages

```bash
sudo apt update
```

### 2. Install Apache2 Web Server

```bash
sudo apt install apache2
```

### 3. Install PHP and Required Modules

```bash
sudo apt install php libapache2-mod-php php-mysql
```

### 4. Verify PHP Installation

```bash
php -v
```

### 5. Install MariaDB

```bash
sudo apt install mariadb-server
```

### 6. Secure MariaDB Installation

```bash
sudo mysql_secure_installation
```

- Enter the current root password: **ENTER**
- Set root password? [Y/n]: **N**
- Remove anonymous users? [Y/n]: **Y**
- Disallow root login remotely? [Y/n]: **Y**
- Remove test database and access to it? [Y/n]: **Y**
- Reload privilege tables now? [Y/n]: **Y**

### 7. Configure Apache VirtualHost for HTTP and HTTPS

#### For Ubuntu:

1. **HTTP Configuration:**

   Edit the default VirtualHost file for HTTP:

   ```bash
   sudo nano /etc/apache2/sites-available/000-default.conf
   ```

   Update the `DocumentRoot` and other settings as needed.

2. **HTTPS Configuration:**

   Install Certbot for HTTPS:

   ```bash
   sudo apt install certbot python3-certbot-apache
   ```

   Obtain and install an SSL certificate:

   ```bash
   sudo certbot --apache
   ```

   Follow the instructions to configure HTTPS.

### 8. Set Up Website Directory

1. **Download and Unzip Website Files:**

   ```bash
   wget <URL_to_Sito_test.zip>
   unzip Sito_test.zip
   mv Sito_test/ sito_test
   ```

2. **Move Files to Apache Directory:**

   ```bash
   sudo cp -r sito_test /var/www/html/
   ```

3. **Create PHP Info Page:**

   ```bash
   cat <<EOL | sudo tee /var/www/html/sito_test/info.php
   <?php
   phpinfo();
   ?>
   EOL
   ```

4. **Set Permissions:**

   ```bash
   sudo chown -R www-data:www-data /var/www/html/sito_test
   sudo chmod -R 755 /var/www/html/sito_test
   ```

5. **Disable Default Site and Reload Apache:**

   ```bash
   sudo a2dissite 000-default.conf
   sudo systemctl reload apache2
   ```

### 9. Configure Database Connection in PHP

Edit `DBConn.php` with the following settings:

```php
<?php
$hostname_DBConn = "localhost";
$database_DBConn = "your db name";
$username_DBConn = "your username";
$password_DBConn = "your password";

$DBConn = mysqli_connect($hostname_DBConn, $username_DBConn, $password_DBConn, $database_DBConn);
mysqli_set_charset($DBConn, "utf8");

if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$query = "SELECT VERSION()";
if ($result = mysqli_query($DBConn, $query)) {
    $row = mysqli_fetch_assoc($result);
    // echo "Connected to database. MySQL version: " . $row['VERSION()'];
    mysqli_free_result($result);
} else {
    echo "Error executing query: " . mysqli_error($DBConn);
}

$GLOBALS['DBConn'] = $DBConn;
?>
```

Ensure to replace the `$hostname_DBConn`, `$database_DBConn`, `$username_DBConn`, and `$password_DBConn` variables with the credentials set during MariaDB installation.

### 10. Troubleshooting

- **MariaDB Error Logs:**

  ```bash
  sudo tail -f /var/log/mysql/error.log
  ```

## Installation on AWS EC2 Amazon Linux

Create an EC2 instance in the AWS console.

---

### IMPORTANT:
Remember to properly configure the Security Group to allow connection via SSH only from authorized machines.
For convenience, at the moment the IP rule for SSH connection is set to 0.0.0.0, so it can be reached from anywhere, but for production, it is absolutely necessary to set this rule with the IP of the machines that can actually access it.

TIPS:
1. Consider creating regular snapshots of your EC2 volumes to easily recover your instance in case you lose it or if you have any other problem affecting your machine. You can create a snapshot manually or schedule a plan with the Lifecycle Policy. In my case, the snapshots will be taken every Sunday starting at 01:00 UTC and will be retained for 1 week. 

2. Since the learning purposes of this project, it is preferable to switch it off when it is not necessary to reduce the operational costs.
---


### 1. Add Bash Script to EC2 User Data

When launching your EC2 instance, include the following script in the User Data field to automate the setup:

```bash
#!/bin/bash

# Update existing packages
sudo yum update -y

# Install Apache
sudo yum install -y httpd

# Run and enable Apache
sudo systemctl start httpd
sudo systemctl enable httpd

# Install PHP and its necessary modules
sudo amazon-linux-extras install -y php8.0
sudo yum install -y php-mysqlnd php-xml php-mbstring

# Restart Apache to apply PHP changes
sudo systemctl restart httpd

# Install MariaDB
sudo yum install -y mariadb-server

# Run and enable MariaDB
sudo systemctl start mariadb
sudo systemctl enable mariadb

# Configure MariaDB
echo "Start MariaDB configuration..."
sudo mysql_secure_installation <<EOF

ENTER
n
y
y
y
y
EOF

# Configure the firewall to allow HTTP and HTTPS traffic
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload

# Create a directory for the website
sudo mkdir -p /var/www/html/sito_test

# Set the privileges for the directory
sudo chown -R apache:apache /var/www/html/sito_test
sudo chmod -R 755 /var/www/html/sito_test

# Create a PHP file for test
cat <<EOL | sudo tee /var/www/html/sito_test/info.php
<?php
phpinfo();
?>
EOL

# Set the permissions for the PHP test file
sudo chown apache:apache /var/www/html/sito_test/info.php
sudo chmod 644 /var/www/html/sito_test/info.php

# Restart Apache to apply all the changes
sudo systemctl restart httpd

# Final feedback
echo "The installation is completed. Visit http://<your-ec2-ip>/sito_test/info.php to test your web server."
```

### 2. Configure DNS and HTTPS

- Set up a DNS alias for your website. I'm using a free service called NO-IP (https://www.noip.com/it-IT) to obtain a free domain name for the public website exposed by Cassiopea (EC2). Alternatively, you can choose a Route 53 service provided by AWS (with cost).
- Enable HTTPS with Let’s Encrypt by following the [Certbot instructions](https://certbot.eff.org/instructions).

### 3. Upload and Backup Website to S3

1. **Upload Website to S3 Bucket:**

   ```bash
   aws s3 cp /var/www/html/sito_test s3://pegaso-s3-ial-pw --recursive
   ```

2. **Automate Backup with Crontab:**

   **Ubuntu Crontab:**

   ```bash
   0 * * * * /var/www/html/sito_test/backup_script.sh >/dev/null 2>&1
   ```

   **EC2 Crontab:**

   ```bash
   3 * * * * /bin/bash /home/ec2-user/restore_site_and_db.sh >> /home/ec2-user/restore_site_and_db.log 2>&1
   ```

   **Install Crontab on EC2 if Needed:**

   ```bash
   sudo yum install cronie -y
   ```

   **Enable and Start Crontab:**

   ```bash
   sudo systemctl enable crond
   sudo systemctl start crond
   sudo systemctl status crond
   ```

   **Set and Verify Cron Jobs:**

   ```bash
   crontab -e
   crontab -l
   ```

   **Identify Logs of Cron Jobs:**

   ```bash
   grep CRON /var/log/syslog
   ```

   **Clean Log Files (if needed):**

   ```bash
   sudo truncate -s 0 /var/log/syslog
   sudo rm /var/log/syslog
   sudo logrotate /etc/logrotate.conf
   ```

   **Optionally Configure Log Rotation:**

   ```bash
   sudo nano /etc/logrotate.d/cron
   ```

   Add the following configuration:

   ```ini
   /var/log/syslog {
       rotate 7
       daily
       missingok
       notifempty
       delaycompress
       compress
       postrotate
           /etc/init.d/rsyslog rotate > /dev/null
       endscript
   }
   ```

   For EC2, check systemd logs:

   ```bash
   sudo journalctl -u cron -r
   sudo journalctl -u cron | grep restore_site_and_db.sh
   ```

## Additional Information

### Fail2ban Installation from Source (Optional)

If Fail2ban is not available via `pip`, you can install it from source:

1. **Install Dependencies:**

   ```bash
   sudo dnf install -y git python3 systemd python3-inotify
   ```

2. **Download Fail2ban Source:**

   ```bash
   git clone https://github.com/fail2ban/fail2ban.git
   ```

3. **Install Fail2ban:**

   ```bash
   cd fail2ban
   sudo python3 setup.py install
   ```

4. **Configure Fail2ban:**

   Copy the example configuration file and edit as needed:

   ```bash
   sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
   sudo nano /etc/fail2ban/jail.local
   ```

   Make sure you have this settings:
   ```bash
   sudo nano /usr/lib/systemd/system/fail2ban.service

   then add this statement
   ExecStart=/usr/local/bin/fail2ban-server -xf start
   ```

   Start and enable Fail2ban:

   ```bash
   sudo systemctl start fail2ban
   sudo systemctl enable fail2ban
   sudo fail2ban-client start
   sudo fail2ban-server start
   ```

5. **Verify Fail2ban Status:**

   ```bash
   sudo fail2ban-client status
   ```

   Check logs for Fail2ban activity:

   ```bash
   sudo tail -f /var/log/fail2ban.log
   sudo journalctl -u fail2ban
   ```


---
