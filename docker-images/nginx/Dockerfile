FROM nginx:1.16.0

MAINTAINER Andreas Schlapbach <schlpbch@gmail.com>

# Make snakeoil certificates available
RUN apt-get update && apt-get install -qy ssl-cert

#Adding NGINX configuration
RUN mkdir -p /etc/nginx/sites-available/
RUN mkdir -p /etc/nginx/sites-enabled/


#COPY ssl/lataberna.com.ec.crt /etc/nginx/ssl/lataberna.com.ec.crt
#COPY ssl/lataberna.com.ec.key /etc/nginx/ssl/lataberna.com.ec.key

COPY nginx.conf /etc/nginx/nginx.conf
COPY lataberna.com.ec /etc/nginx/sites-available/lataberna.com.ec
#COPY default_local /etc/nginx/sites-available/default
#COPY timeout.conf /etc/nginx/conf.d/timeout.conf

#RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/
COPY hosts /etc/hosts


