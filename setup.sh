# Sync items in wp-content/uploads on the server down to our local. This will grab any changes to wp-content/uploads
# since the last sync.
while test $# -gt 0; do
    case "$1" in 
        -u|--sync-uploads)
            rsync -avP root@ifoundmydoctor.com:/var/www/vhosts/ifoundmydoctor.com/httpdocs/wp-content/uploads/ wp-content/uploads
            shift
            ;;
        -d|--sync-db)
            mkdir mysql
            ssh root@ifoundmydoctor.com "mkdir -p /tmp/db_backup && mysql -u ifmdcom -p$IFMD_DB_PASSWORD -D ifmdcom -N -e 'show tables like \"wp\_live2\_%\"' | xargs mysqldump ifmdcom -u ifmdcom -p$IFMD_DB_PASSWORD > /tmp/db_backup/db_backup_ifmdcom.sql"
            scp root@ifoundmydoctor.com:/tmp/db_backup/db_backup_ifmdcom.sql $IFMD_HOME/mysql
            ssh root@ifoundmydoctor.com "rm -rf /tmp/db_backup"
            echo "UPDATE ifmdcom.wp_live2_options SET option_value='http://127.0.0.1:8080' WHERE option_name='siteurl';" | tee -a $IFMD_HOME/mysql/db_backup_ifmdcom.sql
            echo "UPDATE ifmdcom.wp_live2_options SET option_value='http://127.0.0.1:8080' WHERE option_name='home';" | tee -a $IFMD_HOME/mysql/db_backup_ifmdcom.sql
            shift
            ;;
        *)
            break
            ;;
    esac
done

# Run the database image. Notice that this image creates a volume mapping from the mysql directory to
# /docker-entrypoint-initdb.d. By doing this, it will run any .sql files in the mysql folder. The sql
# file in the mysql folder is the database backup from the server. Thus, once the image finishes loading,
# it will have a restored copy of the database that was imported. 
docker run \
-it \
--name ifmd-db \
-e MYSQL_ROOT_PASSWORD=$IFMD_DB_PASSWORD \
-e MYSQL_DATABASE=ifmdcom \
-e MYSQL_USER=ifmdcom \
-e MYSQL_PASSWORD=$IFMD_DB_PASSWORD \
-v /sys/fs/cgroup:/sys/fs/cgroup \
-v $IFMD_HOME/mysql:/docker-entrypoint-initdb.d \
-p 3307:3306 \
-d \
mariadb

# Run the wordpress image. Note that we configure the database settings and link to our database container. 
# This image will communicate with the dataabase container to manage the wordpress database. 
docker run \
-it \
-d \
--name ifmd-wordpress \
--cap-add=ALL \
--link ifmd-db:mysql \
-e WORDPRESS_DB_USER=ifmdcom \
-e WORDPRESS_DB_PASSWORD=$IFMD_DB_PASSWORD \
-e WORDPRESS_DB_NAME=ifmdcom \
-e WORDPRESS_TABLE_PREFIX=wp_live2_ \
-v /sys/fs/cgroup:/sys/fs/cgroup \
-v $IFMD_HOME/wp-content:/var/www/html/wp-content \
-p 8080:80 \
-h "ifoundmydoctor.local" \
wordpress

