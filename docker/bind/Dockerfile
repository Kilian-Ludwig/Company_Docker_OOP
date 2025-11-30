FROM ubuntu/bind9

COPY web.bbq.fwd /var/lib/bind/web.bbq.fwd
COPY named.conf.internal-zone /etc/bind/
COPY named.conf.options /etc/bind
RUN echo 'include "/etc/bind/named.conf.internal-zone";'  >> /etc/bind/named.conf
