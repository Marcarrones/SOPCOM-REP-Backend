# [BACKUP SCRIPTS](https://nozzlegear.com/blog/backing-up-a-docker-sql-server-database-instance)
## Backup 
`
docker exec CONTAINER /usr/bin/mysqldump -u root --password=PASSWORD DATABASE > backup.sql
`


## Restore
`
cat backup.sql | docker exec -i CONTAINER /usr/bin/mysql -u root --password=PASSWORD DATABASE
`