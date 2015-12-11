1) Copy startersuite-goalbit-legacy to /var/www/startersuite-goalbit-legacy

2) Create ``/etc/apache2/sites-enabled/apache2-startersuite.conf``
```
<VirtualHost goalbit-tracker.com:80>
        ServerAdmin webmaster@localhost

        DocumentRoot /var/www
        <Directory />
                Options FollowSymLinks
                AllowOverride None
        </Directory>
        <Directory /var/www/>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride all
                Order allow,deny
                allow from all
        </Directory>

        ScriptAlias /cgi-bin/ /usr/lib/cgi-bin/
        <Directory "/usr/lib/cgi-bin">
                AllowOverride None
                Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
                Order allow,deny
                Allow from all
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log

        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn

        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

3)  Create ``.htaccess`` file in ``/var/www/startersuite-goalbit-legacy``
```
<IfModule mod_rewrite.c>
    RewriteBase /goalbit-startersuite-legacy
    RewriteEngine On	
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```
4) Enable mod_rewrite 
```
sudo a2enmod rewrite
```

5) install php ``apt-get install php5-mysql php5``

6) Enable php5 short tags

In /etc/php5/apache2/php.ini set 
```
short_open_tag = On
```

7) Install mysql server
```
apt-get install mysql-server
```

8) update ``/etc/hosts`` file with
```
127.0.0.1       localhost goalbit-tracker.com
```

9) Login to goalbit-tracker.com and follow the instructions.





