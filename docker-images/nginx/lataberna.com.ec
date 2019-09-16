#default SERVER 

server {
      listen 80;
      listen [::]:80;
      server_name lataberna.com.ec www.lataberna.com.ec;
      return 301 https://$server_name$request_uri;
}

server {

          ### HTTP2 Part
          #listen 443 ssl http2 default_server;
          listen 443 ssl http2;
          listen [::]:443 ssl http2;
          
          rewrite_log on;
          #server_name tabernatest.tk www.tabernatest.tk;
          server_name lataberna.com.ec www.lataberna.com.ec;
          #ssl_certificate /etc/letsencrypt/live/tabernatest.tk/fullchain.pem;
          #ssl_certificate_key /etc/letsencrypt/live/tabernatest.tk/privkey.pem;
          ssl_certificate /etc/nginx/ssl/lataberna.com.ec.chained.crt;
          ssl_certificate_key /etc/nginx/ssl/lataberna.com.ec.key;
          
          # Improve HTTPS performance with session resumption
            ssl_session_cache shared:SSL:10m;
            ssl_session_timeout 10m;

          # Enable server-side protection against BEAST attacks
            ssl_protocols       SSLv3 TLSv1 TLSv1.1 TLSv1.2;

          ssl_ciphers         HIGH:!aNULL:!MD5;


          root /html;

          index index.php index.html;
         
       
          location = /favicon.ico {
            log_not_found off;
            access_log off;
          }

          location = /robots.txt {
            auth_basic off;
            allow all;
            log_not_found off;
            access_log off;
          }

          add_header Access-Control-Allow-Origin *;
          add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS";
          add_header Access-Control-Allow-Headers "DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range";
          add_header Access-Control-Expose-Headers "Content-Length,Content-Range";
          add_header Access-Control-Max-Age 1728000;

          ## Adding Caching

          # cache.appcache, your document html and data
          location ~* \.(?:manifest|appcache|xml|json)$ {
             expires -1;
          }

          # Media: css, html, images & icons
          location ~* \.(?:css|html?|js|jpg|jpeg|gif|png|ico)$ {
            expires 12M;
            access_log off;
            add_header Cache-Control "public";
          }



          # Deny all attempts to access hidden files such as .htaccess, .htpasswd, .DS_Store (Mac).
          location ~ /\. {
            deny all;
            access_log off;
            log_not_found off;
          }
      

    rewrite ^/[a-zA-Z][a-zA-Z]/(index\.php.*)$ /$1 last; #Remove language code when index.php is called directly
     
      # Global rewrite not depending on languages
    rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
    rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
       
     
     rewrite ^/ambato/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
       
      rewrite ^/manta/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
     
     rewrite ^/loja/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
     rewrite ^/cuenca/gran-colombia/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;


    rewrite ^/cuenca/zona-rosa/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
     rewrite ^/cuenca/remigio-crespo/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
     
     rewrite ^/cuenca/estadio/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;


       rewrite ^/cuenca/tienda-nueva/(.*) /$1;
         
         rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;

     


    rewrite ^/cuenca/winery/(.*) /$1;
         
         rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;



    rewrite ^/quito/eloy-alfaro/(.*) /$1;
         
         rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;



    rewrite ^/quito/el-condado/(.*) /$1;
         
         rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;



    rewrite ^/quito/winery-eloy-alfaro/(.*) /$1;
         
         rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;

     

    rewrite ^/santo-domingo/(.*) /$1;
         
         rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;



    rewrite ^/quito/boliche/(.*) /$1;
         
         rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;




    rewrite ^/manta/plazaventura/(.*) /$1;
         
         rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;



    rewrite ^/playas/(.*) /$1;
         
         rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;




     
     rewrite ^/guayaquil/urdesa/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
     
     rewrite ^/guayaquil/ceibos/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
       rewrite ^/guayaquil/samborondon/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
       rewrite ^/guayaquil/kennedy-norte/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
       rewrite ^/machala/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
       rewrite ^/manta/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
       rewrite ^/quito/el-bosque/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
       rewrite ^/quito/cumbaya/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;


    rewrite ^/quito/brasil/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;

     
       rewrite ^/quito/la-taberna-orellana/(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;

        rewrite ^/cuenca/showroom(.*) /$1;
     
     rewrite ^/api/?(.*)$ /webservice/dispatcher.php?url=$1 last;
       rewrite "^/c/([0-9]+)(\-[_a-zA-Z0-9-]*)/(.*)\.jpg$" /img/c/$1$2.jpg last;
    rewrite "^/c/([_a-zA-Z-]+)/(.*)\.jpg$" /img/c/$1.jpg last;
    rewrite "^/([a-z0-9]+)\-([a-z0-9]+)(\-[_a-zA-Z0-9-]*)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2$3.jpg last;
    rewrite "^/([0-9]+)\-([0-9]+)/(\P{M}\p{M}*)*\.jpg$" /img/p/$1-$2.jpg last;
    rewrite "^/([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$1$2.jpg last;
    rewrite "^/([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$1$2$3.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$1$2$3$4.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$1$2$3$4$5.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg last;
    rewrite "^/([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-[_a-zA-Z0-9-]*)?/(\P{M}\p{M}*)*\.jpg$" /img/p/$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg last;
    rewrite "^/([0-9]+)\-(\P{M}\p{M}*)+\.html(.*)$" /index.php?controller=product&id_product=$1$3 last;
    rewrite "^/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=category&id_category=$1$3 last;
    rewrite "^/([a-zA-Z0-9-]*)/([0-9]+)\-([a-zA-Z0-9-]*)\.html(.*)$" /index.php?controller=product&id_product=$2$4 last;
    rewrite "^/([0-9]+)__([a-zA-Z0-9-]*)(.*)$" /index.php?controller=supplier&id_supplier=$1$3 last;
    rewrite "^/([0-9]+)_([a-zA-Z0-9-]*)(.*)$" /index.php?controller=manufacturer&id_manufacturer=$1$3 last;
    rewrite "^/content/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms=$1$3 last;
    rewrite "^/content/category/([0-9]+)\-([a-zA-Z0-9-]*)(.*)$" /index.php?controller=cms&id_cms_category=$1$3 last;
    rewrite "^/module/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?fc=module&module=$1&controller=$2 last;
    rewrite "^/stock/([_a-zA-Z0-9-]*)/([_a-zA-Z0-9-]*)$" /index.php?controller=$1$2 last;
     
     
     try_files $uri $uri/ /index.php?$args;


    # Symfony controllers Specific for 1.7
    location ~ /(international|_profiler|module|product|combination|specific-price)/(.*)$ {
        try_files $uri $uri/ /index.php?q=$uri&$args /backoffice/index.php$is_args$args;
    }


    location /backoffice/ {  # Change this for your admin url
        if (!-e $request_filename) {
            rewrite ^/.*$ /backoffice/index.php last;
        }
    }

    location / {

        if (!-e $request_filename) {
            rewrite ^/.*$ /index.php last;
        }
    }
    
       error_page 404 /index.php?controller=404;
     
       location ~* \.(gif)$ {
          expires 2592000s;
       }
       location ~* \.(jpeg|jpg)$ {
          expires 2592000s;
       }
       location ~* \.(png)$ {
          expires 2592000s;
       }
       location ~* \.(css)$ {
          expires 604800s;
       }
       location ~* \.(js|jsonp)$ {
          expires 604800s;
       }
       location ~* \.(js)$ {
          expires 604800s;
       }
       location ~* \.(ico)$ {
          expires 31536000s;
       }

       location ~ .php$ {
          fastcgi_split_path_info ^(.+.php)(/.*)$;
          try_files $uri =404;
          fastcgi_keep_conn on;
          include /etc/nginx/fastcgi_params;
          fastcgi_pass php-fpm:9000;   ### Adapt if directories are renamed
          fastcgi_index index.php;
          fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
          fastcgi_read_timeout 2000m;
          fastcgi_send_timeout 2000m;
          fastcgi_buffer_size 256k;
          fastcgi_buffers 256 256k;
          fastcgi_max_temp_file_size 0;
          fastcgi_busy_buffers_size 256k;
          fastcgi_temp_file_write_size 256k;
      }

}