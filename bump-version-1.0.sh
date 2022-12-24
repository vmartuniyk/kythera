#!/bin/sh

VERSION=$1
DATE=`date +%d-%m-%Y` 

sed -i "s/#VERSION/#$DATE\n\t'kfn_version' => '$VERSION',\n\t&/" ./app/config/app.php

git diff ./app/config/app.php
git tag $VERSION

echo Bumped to $VERSION
