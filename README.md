# Drupal

Drupal themes and modules used by ICOS websites.

We deploy this repository to production using [this Ansible role](https://github.com/ICOS-Carbon-Portal/infrastructure/tree/master/devops/roles/icos.drupal). Our Drupal websites run inside two Docker containers each, one Drupal and one MariaDB.

## Get started

This repository can be cloned on top of any Drupal installation but it is easier to get started by extracting a Borg backup from our production server. You will need access rights to fsicos to do this. You can get a list of the latest backups for all the websites by running:

```
borg list --last 9 fsicos.lunarc.lu.se:/disk/data/bbserver/repos/fsicos2.lunarc.lu.se/drupal/default/
```

and then extract one of them:

```
borg extract fsicos.lunarc.lu.se:/disk/data/bbserver/repos/fsicos2.lunarc.lu.se/drupal/default/::cp-2021-04-01T03:11:29
```

Our main website is https://www.icos-cp.eu/ and its archive name will look like `cp-{date}`. All our modules are enabled and used on this website however the archive includes many files and is >3GB compressed. Other backups are much smaller.

Once extracted, go to the docker folder and run:

```
docker compose up -d
```

Once the container is running, you must run the `composer update` command inside the Drupal container. Additionally, you must run `chown -R www-data:www-data /var/www/html/sites/default/files` inside the Drupal container in order to enable file uploading.

Note that by using a production backup, **all of the production configuration settings are present**, including SMTP settings for sending emails (e.g., scheduled emails from webforms). Ensure you disable this setting if you are creating a development environment (Admin -> Configuration -> System -> SMTP Authentication Support).