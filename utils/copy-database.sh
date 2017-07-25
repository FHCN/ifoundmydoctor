#!/bin/sh

ssh root@ifoundmydoctor.com 'cd /var/www/vhosts/ifoundmydoctor.com/; mysqldump --opt -u ifmdcom -p ifmdcom'
