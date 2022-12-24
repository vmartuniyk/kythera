#!/bin/sh

echo Dumping live db....
mysqldump -u live_kythera --password=Tce15h#0 live_kythera > live_kythera.sql
echo Importing live db into dev...
mysql -u laravel_kythera --password=x^Bs206e laravel_kythera < live_kythera.sql
rm live_kythera.sql

