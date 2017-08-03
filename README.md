# Florida Health Care News Theme and Plugins

This site contains the wordpress theme and plugins for Florida Health Care News. 

# Overview

All development should be done locally. Setting up your local machine to run a wordpress site locally and importing the database can be a pain. To simplify this task, we can compose docker containers, allowing us to setup the environment instantly, no matter what platform you are running on. 

To setup a wordpress site using docker containers, you will need to install docker and pull down two docker images:

```
docker pull mariadb
docker pull wordpress
```

The first image, `mariadb`, is a simple docker contains who's sole purpose is to run a mariadb database. During container initialization, it will execute any sql scripts residing under `/docker-entrypoint-initdb.d`. This means we can create a volume mapping from a folder containing the FHCN database on the host machine to /docker-entrypoint-initdb.d on the container. When the container loads, it will run the database script automatically, loading the FHCN database with ease. The volume mapping will map the `mysql` folder on the host to `/docker-entrypoint-initdb.d`. Because of this, all database scripts should be placed in the `mysql` folder.

The second image, `wordpress`, is a simple docker container who's sole purpose is to run wordpress. Since we can compose containers, the wordpress container can be linked to the database container. This is a good practice to begin with because the database is running on a separate server, following the 12-factor development model. Furthermore, by using a wordpress container, our git repository no longer needs to store wordpress-specific files. The only thing our repository should be concerned with is storing the wordpress theme and plugin files, creating a separation of concerns. This simplifies our repository and decouples wordpress from the database. All that needs to be done when launching this container is to create a volume map from the `wp-content` directory from our repository to `/var/www/html` on the container. Once we've mapped wp-content, it will have the wordpress theme and plugins. 

To make setting up this environment as simple as possible, a `setup.sh` script will be used to:
  - Pull down the database
  - Sync the uploads folder under wp-content
  - Launch the mariadb container
  - Launch the wordpress container

# Development Setup

After installing docker and pulling down both `mariadb` and `wordpress`, you will need to add the following environment variables to your `bash_profile` or `zshrc`, depending on whether or not you are using bash or zsh.  

1. `IFMD_HOME` - should point to the local repo (ex. /Users/<username>/Sites/ifoundmydoctor)
2. `IFMD_DB_PASSWORD` - Set to the db password

Now that your environment variables are set, you are ready to run the `setup.sh` script. When running setup, you will have two options:

1. Sync the database from the server to your local
2. Sync the files from wp-content/uploads on the server to your local

If you are just setting up for the first time, you will want to execute both of these operations. Afterwards, it is only necessary to run each operation if you need to view new entries in the database that you don't have locally or if there are new images on the server in the upload folder. To execute both operations, add each option as an argument to the script:

```
./setup.sh --sync-db --sync-uploads
```

After running the script, you will notice two docker containers have been created:
```
$ ./setup.sh
981b9aff62d616d103d2d2dddf14efed31de9823933ab3772b2f1525cb2f8d46
8988d942430539dfbd989844509fa0c3c4be8d2424e356e32b535978f676730b
```

Running `docker ps` will show the following:
```
$ docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                    NAMES
8988d9424305        wordpress           "docker-entrypoint..."   6 hours ago         Up 6 hours          0.0.0.0:8080->80/tcp     ifmd-wordpress
981b9aff62d6        mariadb             "docker-entrypoint..."   6 hours ago         Up 6 hours          0.0.0.0:3307->3306/tcp   ifmd-db
```

Notice each container has a name. You can launch a bash prompt for either container by running:
```
docker exec -it ifmd-wordpress /bin/bash
docker exec -it ifmd-db /bin/bash
```

You will need to wait 15 to 20 seconds after running the setup script to allow the database to populate and for wordpress to initialize apache. When you are ready, navigate to `http://127.0.0.1:8080/`. The site should now load in your browser using the database that was pulled down. 
