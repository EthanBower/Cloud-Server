In order to get the cloud to work 100% as intended, there are a few things that need to be in order:

1) Use a linux distro to host server. This has been tested on Raspian OS, so best results will be on that

2a) Go into your php.ini file and set:
	-session.cookie_httponly = 1
	-session.use_only_cookies = 1
	-upload_max_filesize = 100M (if you dont like 100M upload, change it to whatever. Make sure to go to uploads.php and change the max upload to the values set here, too)
	-post_max_size = 100M
	-file_uploads = On

2b) IF you have a https host, set:
	-session.cookie_secure = 1
    IF NOT, set:
	-session.cookie_secure = 0

3) If you want https:
	-Enter this into terminal. It will create a key and certificate: 
		openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout [ENTER FILE NAME].key -out [ENTER FILE NAME].crt
	-For Debian flavors with Apache2, go to /etc/sites-available/[YOUR WEBSITE CONF FILE]
	-In the virtual host, add:
 		SSLEngine on
		SSLCertificateFile [CRT_DIRECTORY]
		SSLCertificateKeyFile [KEY_DIRECTORY]
	-You may need to enable SSL mod. Go to terminal and enter:
		sudo a2enmod ssl
