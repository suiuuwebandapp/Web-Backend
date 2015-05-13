ls
vi README 
cmake .
ls
groupadd mysql
useradd -r -g mysql mysql
cd /usr/local/
ln -s /root/tools/mysql-5.5.37-linux2.6-x86_64  mysql
ls
cd mysql/
ls
cd ..
rm -rf mysql/
ls
cd mysql/
ls
cd ..
rm -rf mysql
ls
cd /
ls
cd tools 
ls
cd tools 
ls
mv /root/tools/ /home
cd /root
l
ls
cd /home/tools/
ls
tar -zxvf mysql-5.5.37-linux2.6-x86_64
rm -rf mysql-5.5.37-linux2.6-x86_64
ls
tar -zxvf mysql-5.5.37-linux2.6-x86_64
ls
tar -zxvf mysql-5.5.37-linux2.6-x86_64.tar.gz 
cd /usr/local/
ln -s /home/tools/mysql-5.5.37-linux2.6-x86_64  mysql
cd mysql/
chown -R mysql .
 chgrp -R mysql .
scripts/mysql_install_db --user=mysql
chown -R root .
 chown -R mysql data
cp support-files/my-medium.cnf /etc/my.cnf
cp support-files/mysql.server /etc/init.d/mysql.server
service mysqld start
cd /etc/init.d/
ls
vi mysql.server 
mv mysql.server mysql
service mysql start
service mysql stop
service mysql start
vi mysql 
service mysql start
mv mysql mysqld
vi mysqld 
service mysqld restart
service mysqld start
df -h
chmod -R 755 /usr/local/mysql/data
service mysqld start
vi mysqld 
vi /etc/my.cnf 
cd /usr/local/mysql/
ls
cd data/
ls
vi iZ62g5yhlbmZ.err 
cd ..
ls
scripts/mysql_install_db --user=mysql
./bin/mysqladmin -u root password 'suiuu123'
service mysqld start
service mysqld stop
chown -R mysql.mysql /usr/local/mysql/var
 chmod +x /etc/rc.d/init.d/mysqld
chkconfig --add mysqld
chkconfig --list mysqld
service mysqld start
mysql -u root
mysqld -u root
rpm -qa|grep vim
yum -y install vim-enhanced
yum -y install vi
ll /bin/vi
vi /etc/vimrcvi
yum -y install vim-enhanced
vim /etc/my.cnf
vim /etc/bashrc
vi /etc/my.cnf
vim /etc/bashrc
mv /bin/vi /bin/vi.bak
ln -s /usr/bin/vim /bin/vi
vi /etc/my.cnf
exit
mysql -u root
service mysqld status
whereis mysql
cd /usr/local/mysql/
ls
cd bin/
ls
mysql -u root
cd /usr/bin
ls
mysqld
ps -aux |grep mysql
cd /usr/local/sb
cd /usr/local/sbin
ls
cd ..
cd bin
ls
cd ..
ls
cd mysql/
ls
cd bin/
ls
mysql -u root
ln -s /usr/local/mysql/bin/mysql /usr/bin
mysql -u root
mysql -u root 
mysql -u root -p
exit
mysql -u root -p
mysqld -u root
mysql -u root
mysql -u root -p
cd /home
l
ls
cd tools/
ls
tar -zxvf mirror 
s
ls
cd php-5.5.19/
wget http://download.redis.io/releases/redis-2.8.19.tar.gz
ls
mv redis-2.8.19.tar.gz ./
cd ..
;s
ls
cd php-5.5.19/
ls
mv redis-2.8.19.tar.gz /home/tools/
ls
cd ..
ls
tar xzf redis-2.8.19.tar.gz
cd redis-2.8.19
make
make install
make test
cd src
make install
make test
cd ..
ls
make install
cp redis.conf /etc/
src/redis-server
src/redis-server&
chmod +x /etc/init.d/redis
service redis start
ps -aux |grep redis
kill -9 8150
service redis start
ps -aux| grep redis
reboot 
ps -aux| grep mysqld
ps -aux| grep redis
chkconfig --add redis
chkconfig add redis
history
chkconfig --add redis
chmod +x /etc/rc.d/init.d/redis  
chkconfig --add redis
cd /etc/init.d/
ls
chkconfig --add redis
vi mysqld
vi redis 
ls
cd /usr/local/
ls
cd /home/
ls
cd tools/
ls
cd /usr/local/
ls
cd ..
cd /home/tools/
ls
cd redis-2.8.19
ls
cd .
cd ..
rm -rf redis-2.8.19
ls
tar -zxvf redis-2.8.19.tar.gz 
ln -s redis-2.8.19 /usr/local/redis
cd /usr/local/redis 
ls
cd /usr/local/
ls
cd redis 
ll
ln -s /home/tools/redis-2.8.19 /usr/local/redis
rm /usr/local/redis 
ln -s /home/tools/redis-2.8.19 /usr/local/redis
cd /usr/local/redis/
ls
make && make install
ls
service redis start
ls
cd src/
ls
service redis start
chkconfig --add redis
reboot 
reboot
ps -aux |grep redis
cd /home/tools/
ls
wget ftp://ftp.gnome.org/pub/GNOME/sources/libxml2/2.6/libxml2-2.6.30.tar.gz
wget http://prdownloads.sourceforge.net/mcrypt/libmcrypt-2.5.8.tar.gz?use_mirror=peterhost
wget http://zlib.net/zlib-1.2.8.tar.gz
wget https://bitbucket.org/libgd/gd-libgd/downloads/libgd-2.1.0.tar.gz
wget ftp://ftp.gnu.org/gnu/autoconf/autoconf-2.69.tar.gz
wget http://download.savannah.gnu.org/releases/freetype/freetype-2.5.0.1.tar.gz
wget ftp://ftp.simplesystems.org/pub/libpng/png/src/libpng16/libpng-1.6.7.tar.gz
wget http://www.ijg.org/files/jpegsrc.v9.tar.gz
tar zxvf libxml2-2.6.30.tar.gz
cd libxml2-2.6.30
/configure --prefix=/usr/local/libxml2
./configure --prefix=/usr/local/libxml2
make && make install
exit
cd /usr/local/
ls
cd /home/tools/
ls
tar -zxvf libmcrypt
tar -zxvf libmcrypt-2.5.8.tar.gz\?use_mirror\=peterhost 
cd libmcrypt-2.5.8
ls
./configure --prefix=/usr/local/libmcrypt
make && make install
tar -zxvf lib
ls
cd ..
tar -zxvf zlib-1.2.8.tar.gz 
cd zlib-1.2.8
./configure --prefix=/usr/local/zlib
make && make install
cd ..
tar -zxvf libpng-1.5.20.tar.gz 
cd libpng-1.5.20
./configure --prefix=/usr/local/libpng
cd ..
cd zlib-1.2.8
make clean
./configure 
make && make install
cd ..
cd libpng-1.5.20
./configure --prefix=/usr/local/lib
make clean
./configure --prefix=/usr/local/libpng
make && make install
cd ..
ls
tar -zxvf freetype-2.5.0.1.tar.gz 
cd freetype-2.5.0.1
./configure --prefix=/usr/local/freetype
./configure --prefix=/usr/local/freetype --without-png
./configure --prefix=/usr/local/freetype
vi /etc/profile
cd ..
cd libpng-1.5.20
make clean
./configure 
make && make install
cd ..
cd freetype-2.5.0.1
./configure --prefix=/usr/local/freetype
make && make install
cd ..
ls
tar -zxvf jpegsrc.v9.tar.gz 
cd ..
cd tools/
ls
cd jpeg9
cd jpeg-9
ls
./configure --prefix=/usr/local/jpeg9 --enable-shared --enable-static
make && make install
cd ..
ls
tar -zxvf autoconf-2.69.tar.gz 
cd autoconf-2.69
./configure
make && make install
cd ..
tar -zxvf libgd-2.1.0.tar.gz
cd libgd-2.1.0
./configure --prefix=/usr/local/gd2/ --with-zlib=/usr/local/zlib/ --with-jpeg=/usr/local/jpeg9/ --with-png=/usr/local/libpng/ --with-freetype=/usr/local/freetype/
make && make install
cd ..
cd php-5.5.19/
./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --with-libxml-dir=/usr/local/libxml2/ --with-png-dir=/usr/local/libpng/ -with-jpeg-dir=/usr/local/jpeg6/   --with-freetype-dir=/usr/local/freetype/ --with-gd=/usr/local/gd2/  --with-zlib-dir=/usr/local/zlib/ --with-mcrypt=/usr/local/libmcrypt/ --with-mysql=/usr/local/mysql --with-mysqli --enable-soap --enable-mbstring=all --enable-sockets --enable-pdo --with-pdo-mysql  --with-openssl --with-redis=/usr/local/redis
yum install openssl
./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --with-libxml-dir=/usr/local/libxml2/ --with-png-dir=/usr/local/libpng/ -with-jpeg-dir=/usr/local/jpeg6/   --with-freetype-dir=/usr/local/freetype/ --with-gd=/usr/local/gd2/  --with-zlib-dir=/usr/local/zlib/ --with-mcrypt=/usr/local/libmcrypt/ --with-mysql=/usr/local/mysql --with-mysqli --enable-soap --enable-mbstring=all --enable-sockets --enable-pdo --with-pdo-mysql  --with-openssl --with-redis=/usr/local/redis
yum install openssl-dev
yum install libcurl3-openssl-dev
yum install -y gcc gcc-c++  make zlib zlib-devel pcre pcre-devel  libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel libxml2 libxml2-devel glibc glibc-devel glib2 glib2-devel bzip2 bzip2-devel ncurses ncurses-devel curl curl-devel e2fsprogs e2fsprogs-devel krb5 krb5-devel openssl openssl-devel openldap openldap-devel nss_ldap openldap-clients openldap-servers 
yum  install  openssl.x86_64 openssl-devel.x86_64 -y
./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --with-libxml-dir=/usr/local/libxml2/ --with-png-dir=/usr/local/libpng/ -with-jpeg-dir=/usr/local/jpeg6/   --with-freetype-dir=/usr/local/freetype/ --with-gd=/usr/local/gd2/  --with-zlib-dir=/usr/local/zlib/ --with-mcrypt=/usr/local/libmcrypt/ --with-mysql=/usr/local/mysql --with-mysqli --enable-soap --enable-mbstring=all --enable-sockets --enable-pdo --with-pdo-mysql  --with-openssl --with-redis=/usr/local/redis --enable-fpm
make && make install
make  && make install
./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --with-libxml-dir=/usr/local/libxml2/ --with-png-dir=/usr/local/libpng/ -with-jpeg-dir=/usr/local/jpeg6/   --with-freetype-dir=/usr/local/freetype/ --with-gd=/usr/local/gd2/  --with-zlib-dir=/usr/local/zlib/ --with-mcrypt=/usr/local/libmcrypt/ --with-mysql=/usr/local/mysql --with-mysqli --enable-soap --enable-mbstring=all --enable-sockets --enable-pdo --with-pdo-mysql  --with-openssl  --enable-fpm
make 
make clean
cd ..
ls
cd /usr/local/gd2/
ls
cd ..
cd /
cd /home/tools/
cd php-5.5.19/
./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --with-libxml-dir=/usr/local/libxml2/ --with-png-dir=/usr/local/libpng/ -with-jpeg-dir=/usr/local/jpeg6/   --with-freetype-dir=/usr/local/freetype   --with-zlib-dir=/usr/local/zlib/ --with-mcrypt=/usr/local/libmcrypt/ --with-mysql=/usr/local/mysql --with-mysqli --enable-soap --enable-mbstring=all --enable-sockets --enable-pdo --with-pdo-mysql  --with-openssl  --enable-fpm  && make && make install
make test
cd /home/tools/
ls
tar -zxvf nginx-1.7.8.tar.gz 
ls
cd nginx-1.7.8
ls
yum install pcre
./configure --prefix=/usr/local/nginx --with-http_stub_status_module
make && make install
chkconfig --add nginx
chkconfig --add php-fpm
service php-fpm start
chmod x /etc/init.d/php-fpm 
chmod o+x /etc/init.d/php-fpm 
chmod o+x /etc/init.d/nginx
service php-fpm start
service nginx start
cd /home
ls
mkdir www
cd www
mkdir phpweb
mkdir log
ln -s /home/www/log /var/log/phpweb
cd phpweb/
mkdir suiuu
service nginx restart
ls
cd suiuu/
ls
mv index.php index.html
cd /usr/local/php/etc
ls
cp php-fpm.conf.default php-fpm.conf
cd /home/tools/php-5.5.19/
ls
cd php.ini-development /usr/local/php/etc/php.ini
cp php.ini-development /usr/local/php/etc/php.ini
service php-fpm start
service nginx restart
chmod o+x /dev/shm/php-fpm.sock
chmod 755 /dev/shm/php-fpm.sock
cd /dev/shm/php-fpm.sock 
cd /dev/shm/
ls
chmod 777 /dev/shm/php-fpm.sock
ls
exit
cd /usr/local/nginx/
ls
cd conf/
ls
cd host/
ls
cd vi www.suiuu.com.conf 
vi www.suiuu.com.conf 
service ngixn restart
service nginx restart
exit
cd /home/e
cd /home/
ls
cd www/
s
ls
cd phpweb/
ls
cd suiuu/
ls
vi index.html 
cd /home/www/phpweb/
ls
cd suiuu/
ls
rm -rf *
ls
cd /home/www/phpweb/
ls
cd suiuu/
ll
chmod o+x 5c139669cf08c940e35d6107f7aceaba1428401996347.html 
ll
chmod 755 5c139669cf08c940e35d6107f7aceaba1428401996347.html 
cd /usr/local/nginx/
ls
cd c
cd conf/
ls
cd host/
ls
cp www.suiuu.com.conf mail.suiuu.com.conf
ll
vi mail.suiuu.com.conf 
service nginx restart
vi mail.suiuu.com.conf 
service nginx restart
service nginx stop
service nginx start
ping mail.suiuu.com
ping www.suiuu.com
ping suiuu.com
ping mail.suiuu.com
vi mail.suiuu.com.conf 
service nginx restart
cd /usr/local/nginx/conf/host/
ls
cp www.suiuu.com.conf image.suiuu.com.conf
vi image.suiuu.com.conf 
service nginx restart
vi image.suiuu.com.conf 
service nginx restart
cd /home/www/phpweb/image_suiuu/
ls
vi index.html 
ping image.suiuu.com 
service ngixn restart
service nginx restart
cd /usr/local/nginx/host
cd /usr/local/nginx/
ls
cd conf/host/
ls
ll
vi image.suiuu.com.conf 
vi www.suiuu.com.conf 
vi image.suiuu.com.conf 
cd /home/www/phpweb/
ls
cd image_suiuu/
ll
chmod +r 3a19a05d996443adc4abc349637f4f971428895181240.html 
ll
sudo nginx -s reload
exit
ls
cd /
ls
cd ~/
ls
wget https://github-windows.s3.amazonaws.com/GitHubSetup.exe
cd /usr/local/nginx/
ls
cd conf/
ls
cd host/
ls
cp image.suiuu.com.conf map.suiuu.com.conf
vi map.suiuu.com.conf 
service ngixn restart
service nginx restart
ls
cd www.suiuu.com.conf 
vi www.suiuu.com.conf 
service nginx restart
vi www.suiuu.com.conf 
service nginx restart
vi www.suiuu.com.conf 
service nginx restart
chmod 777 -R /home/www/phpweb/mapsed/
chmod 777 -R /home/www/phpweb/
ls
cd /home/www/
ls
cd phpweb/
ls
cd mapsed/
ls
ll
cd ..
ls
..
ll
chmod 755 -R /home/www/phpweb/
ls
ll
cd /usr/local/php/
ls
cd con
cd ..
cd nginx/
cd conf/
ls
cd host/
ls
vi www.suiuu.com.conf 
cd ..
ls
vi fastcgi.conf
ll
cd /home/w
cd /home/www
cd phpweb/
ll
cd mapsed/
ll
cd ..
cd suiuu/
ll
cd /usr/local/nginx/conf/
ls
cd host/
ls
vi map.suiuu.com.conf 
vi www.suiuu.com.conf 
service ngixn restart
service nginx restasrt
service nginx restart
cd /home/www/phpweb/
ls
cd mapsed/
ls
ll
vi index.html 
wget http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false;
ll
vi js\?libraries\=places 
cp js\?libraries\=places googlemap.js
vi index.html 
curl
wget ttp://maps.google.com/maps/api/geocode/xml?latlng=39.910093,116.403945&language=zh-CN&sensor=false
http://maps.google.com/maps/api/geocode/xml?latlng=39.910093,116.403945&language=zh-CN&sensor=false
wget http://maps.google.com/maps/api/geocode/xml?latlng=39.910093,116.403945&language=zh-CN&sensor=false
ls
ll
vi xml\?latlng\=39.910093\,116.403945 
yum install curl
curl -l
curl
curl --help
curl -G "http://ditu.google.cn/maps/geo?output=json&oe=utf-8&q=China&key=ABQIAAAAzr2EBOXUKnm_jVnk0OJI7xSosDVG8KKPE1-m51RBrvYughuyMxQ-i1QfUnH94QxWIa6N4U6MouMmBA&mapclient=jsapi&hl=zh-CN"
wget http://maps.google.com/maps/api/geocode/json?address=china&sensor=false
vi json\?address\=china 
history
curl -G "http://ditu.google.cn/maps/geo?output=json&oe=utf-8&q=china&key=jsaou&hl=zh-cn";
curl -G "http://ditu.google.cn/maps/geo?output=json&oe=utf-8&q=china&key=jsaou&hl=zh-
;
curl -G "http://maps.google.com/maps/api/geocode/json?address=china&sensor=false"
curl -G "http://maps.google.com/maps/api/geocode/json?address=巴黎&sensor=false"
curl -G "http://maps.google.com/maps/api/geocode/json?address=开封&sensor=false"
curl -G "http://maps.google.com/maps/api/geocode/json?address=不详&sensor=false"
curl -G "http://maps.google.com/maps/api/geocode/json?address？？？&sensor=false"
mysql -u -p
mysql -uroot -p
mysql -uroot
mysql -uroot -p SuiuuDB123
mysql -uroot -p
mysql -usuiuu
mysql -usuiuu -p
history
ps -aux | grep mysqld
mysql -uroot -pSuiuuDB888
mysql -uroot -p
cd /usr/local/php
cd /home/
ls
cd www
cd phpweb/
ls
mkdir suiuu_web
mv suiuu_web.zip suiuu_web/suiuu_web.zio
ls
cd suiuu_web/
ls
mv suiuu_web.zio suiuu_web.zip
yum install zip
zip -r suiuu_web.zip 
zip -r suiuu_web.zip suiuu_web
zip -r suiuu_web.zip . -i suiuu_web
mysql -usuiuu -p
ls
unzip -o suiuu_web.zip suiuu_web
unzip -o  suiuu_web suiuu_web.zip 
unzip -o -d  suiuu_web suiuu_web.zip 
ll
ls
cd ..
;s
ls
cd suiuu_web
ls
cd suiuu/
ls
cd backend/
ls
cd web/
ls
dc ..
cd ..
ls
cd ..
ls
cd ..
ls
cd /usr/local/nginx/
ls
cd conf/
ls
cd host/
ls
cp www.suiuu.com.conf yuanbo.suiuu.com
vi yuanbo.suiuu.com 
service nginx restart
cd /home/www/phpweb/
ls
cd suiuu_web
ls
cd suiuu/
s
ls
cd backend/
;s
ls
cd web/
ls
vi index.php 
cd ..
ls
cd controllers/
ls
vi IndexController.php 
ls
cd ,,
cd ..
ls
cd ..
ls
cd suiuu/
ls
vi index.php 
./init
init
ls
php init
ls
cd ..
cd /usr/local/
ls
cd nginx/
ls
cd logs/
ls
vi access.log 
ps -ef | grep mysql
kill '/usr/local/mysql/data/iZ62g5yhlbmZ.pid' 
cd /usr/local
ls
cd mysql/
ls
cd /data
cd bin
ls
ps -ef | grep mysql server
net stop mysql
service mysqld stop
service mysqld start --skip-grant-tables
/usr/local/mysql/mysql-safe --skip-grant-tables &
/usr/local/mysql/bin/mysql-safe --skip-grant-tables &
cd   /usr/local/mysql/bin
ls
mysqld_safe  --skip-grant-tables
cd mysqld_safe 
 mysqld_safe --skip-grant-tables
sudo  mysqld_safe --skip-grant-tables
mysqladmin -h
ls
mysqladmin -u root flush-privileges password "suiuu321"
cd ../
mysqladmin -u root flush-privileges password "suiuu321"
mysql
vi /etc/my.cnf 
service mysqld restart
mysql -uroot -p
mysql
service mysqld restart
mysql 
mysql -usuiuu
mysql -usuiuu -p
mysql -uroot -p
service mysqld stop
 service mysqld start --skip-grant-tables
mysql
vi /etc/my.cnf 
exit
ls
vi access.log 
cd /usr/local/nginx/
ls
cd c
cd conf/
ls
cd /var/log/
ls
cd phpweb/
ls
ll
cd /usr/local/nginx/
ls
cd c
cd conf/
ls
cd host/
ls
vo yuanbo.suiuu.com 
vi yuanbo.suiuu.com 
cd /home/www/phpweb/
ls
cd suiuu_web
ls
cd suiuu/
ls
cd backend/
ls
cd web/
ls
vi index
vi index.php 
service ngixn restart
service nginx restart
ls
cd ..
ls
cd ..
ls
cd ..
ls
cd ..
ls
cd /usr/local/nginx/
ls
cd conf/
ls
cd host/
ls
vi map.suiuu.com.conf 
ll
mv yuanbo.suiuu.com yuanbo.suiuu.com.conf
service nginx restart
ls
cd /home/www/
ls
cd phpweb/
ls
cd suiuu_web
ls
cd suiuu/
ls
cd backend/
ls
vi web
cd web/
ls
vi index.php 
ll
cd ..
ls
cd ..
ls
chmod 755 -R suiuu/
cd ..
chmod 755 -R suiuu_web
chmod 777 -R suiuu_web/suiuu/backend/
ps -aux|grep redis
ls
cd suiuu_web
ls
cd suiuu/
ls
cd backend/
ls
cd co
cd config/
ls
vi main.php 
vi main-local.php 
vi params.php 
cd ..
ls
cd ..
ls
cd common/
ls
cd config/
ls
vi main.php 
vi main-local.php 
cd ..
ls
cd ..
ls
cd backend/
ls
cd controllers/
ls
vi DestinationController.php 
cd ..
cd common/on
cd common
ls
cd components/
ls
vi GoogleMap.php 
yun install curl
yum install curl
cd /root
ls
cd ~?
cd ~/
ls
cd home
ls
cd /home
ls
cd tools/
ls
cd php-5.5.19/
cd ext/
ls
cd curl/
ls
phpize
/usr/local/php/bin/phpize 
./configure 
whereis curl
./configure --with-curl=DIR
./configure --with-php-config=/usr/local/php/
./configure --with-php-config=/usr/local/php/bin/phpconfig
./configure --with-php-config=/usr/local/php/bin/php-config
make 
cd /usr/local/php/
ls
cd lib/
ls
cd php/
ls
cd extensions/
ls
cd no-debug-non-zts-20121212/
ls
cd /home/tools/php-5.5.19/ext/curl/modules
ls
cp curl.so /usr/local/php/lib/php/extensions/no-debug-non-zts-20121212/curl.so
vi /usr/local/php/etc/php.ini 
service php-fpm restart
service nginx restart
ll
service php-fpm stop
service php-fpm start
cd /usr/local/nginx/logs/
ls
vi error.log 
cd /dev/
ls
cd shm/
ls
chmod 777 php-fpm.sock 
cd /home/www/phpweb/suiuu_test
ls
history
unzip -o -d  suiuu_test suiuu_test.zip
chmod 755 suiuu.zip 
unzip -o -d  suiuu_test suiuu_test.zip
ls
unzip -o -d  suiuu.zip suiuu_test.zip
unzip -o -d  suiuu_test suiuu.zip
ll
cd /usr/local/nginx/
ls
cd conf/
ls
cd host/
ls
cp yuanbo.suiuu.com.conf test.suiuu.com
vi test.suiuu.com 
cp test.suiuu.com sys.suiuu.com
vi sys.suiuu.com 
service ngixn restart
service nginx restart
ls
cd /home/www/phpweb/
ls
cd suiuu_test/
ls
cd suiuu/
ls
cd /var/log/
ls
cd ntp
cd /usr/local/nginx/logs/
ls
vi access.log 
vi error.log 
cd ..
cd conf/host/
ls
vi sys.suiuu.com 
service nginx restart
mv test.suiuu.com test.suiuu.com.conf
mv sys.suiuu.com sys.suiuu.com.conf
service nginx restart
ll
vi sys.suiuu.com.conf 
vi mail.suiuu.com.conf 
vi test.suiuu.com.conf 
service nginx restart
vi sys.suiuu.com.conf 
vi test.suiuu.com.conf 
service nginx restart
cd /usr/local/nginx/logs/nginx.pid 
cd /usr/local/nginx/logs/
ls
chmod 755 nginx.pid 
service nginx restart
chmod -R 755 /home/www/phpweb/suiuu_test/
cd /usr/local/nginx/
ls
cd logs/
ls
vi error.log 
sudo chmod -W /home/www/phpweb/suiuu_test/suiuu/frontend/web/assets
sudo chmod -O+W /home/www/phpweb/suiuu_test/suiuu/frontend/web/assets
sudo chmod -o+r /home/www/phpweb/suiuu_test/suiuu/frontend/web/assets
sudo chmod -R 777 /home/www/phpweb/suiuu_test/suiuu/frontend/web/assets
sudo chmod -R 777 /home/www/phpweb/suiuu_test/suiuu/backend/web/assets
ls
vi error.log 
cd ..
vi /etc/hosts
vi /etc/host.conf 
vi /ets/hosts
cd /etc/
ls
cd host
vi hosts
ls
cd hosts
vi hosts
ls
cd /usr/local/nginx/
ls
cd logs/
ls
vi error.log 
cd /usr/local/nginx/logs/
vi error.log 
rediscli
redis-cli
redis-cli
history
curl -G "http://ditu.google.cn/maps/geo?output=json&oe=utf-8&q=古罗马广场"
cul-G "http://maps.google.com/maps/api/geocode/json?address='古罗马广场'"
curl -G "http://ditu.google.cn/maps/geo?output=json&oe=utf-8&q=古罗马广场"
curl -G "http://maps.google.com/maps/api/geocode/json?address='古罗马广场'"
curl -G "http://maps.google.com/maps/api/geocode/json?address='The ancient Roma Square'"
curl -G "http://maps.google.com/maps/api/geocode/json?address='TheancientRomaSquare'"
curl -G "http://maps.google.com/maps/api/geocode/json?address='Roma'"
curl -G "http://maps.google.com/maps/api/geocode/json?address=Roman Forum"
curl -G "http://maps.google.com/maps/api/geocode/json?address=Roman%2Forum"
curl -G "http://maps.google.com/maps/api/geocode/json?address=古罗马广场"
curl -G "http://maps.google.com/maps/api/geocode/json?address=Roman%2Forum"
