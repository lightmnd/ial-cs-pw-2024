#!/bin/bash

# DB Dump
sudo mysqldump -u root test > /tmp/db_backup.sql
if [ $? -ne 0 ]; then
    echo "mysqldump failed, aborting script."
    exit 1
fi

# Website zip archive
sudo zip -r /tmp/sito_test_backup.zip /var/www/html/sito_test /tmp/db_backup.sql
if [ $? -ne 0 ]; then
    echo "ZIP creation failed, aborting script."
    exit 1
fi

# Check if the zip file was created
if [ ! -f /tmp/sito_test_backup.zip ]; then
    echo "ZIP file was not created, aborting upload."
    exit 1
fi

# Upload to S3 Bucket
aws s3 cp /tmp/sito_test_backup.zip s3://pegaso-s3-ial-pw/
if [ $? -ne 0 ]; then
    echo "AWS S3 upload failed."
    exit 1
fi

# Clean the temporary backup resources
sudo rm /tmp/db_backup.sql /tmp/sito_test_backup.zip
