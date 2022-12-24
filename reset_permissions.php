#!/bin/sh

chown -R kythera-admin:psacln *
chmod -R 777 app/storage
chmod -R 777 public/download
chmod -R 777 public/photos
chmod -R 777 public/audio
chmod -R 777 public/tmp

