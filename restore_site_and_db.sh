#!/bin/bash

# Set variables for the paths to the zip file and S3 bucket 
ZIP_FILE="/tmp/sito_test_backup.zip"
S3_BUCKET="s3://pegaso-s3-ial-pw/sito_test_backup.zip"

# Download zip file from S3 bucket
echo "Downloading ZIP file from S3..."
aws s3 cp $S3_BUCKET $ZIP_FILE
if [ $? -ne 0 ]; then
    echo "Failed to download ZIP file from S3, aborting script."
    exit 1
fi

# Verify if the zip file exists
if [ ! -f $ZIP_FILE ]; then
    echo "ZIP file not found after download, aborting script."
    exit 1
fi

# Unzip the ZIP file
echo "Unzipping the downloaded file..."
sudo unzip -o $ZIP_FILE -d /tmp
if [ $? -ne 0 ]; then
    echo "Failed to unzip file, aborting script."
    exit 1
fi

# Copy the files to the correct directory overriding the existing files 
echo "Copying website files to /var/www/html/sito_test (overwriting existing files)..."
sudo cp -rf /tmp/var/www/html/sito_test/* /var/www/html/sito_test/
if [ $? -ne 0 ]; then
    echo "Failed to copy website files, aborting script."
    exit 1
fi

# Upload the database dump into MariaDB
echo "Restoring the MariaDB database..."
sudo mysql -u root test < /tmp/tmp/db_backup.sql
if [ $? -ne 0 ]; then
    echo "Database restoration failed, aborting script."
    exit 1
fi

# Clean the temporary files
echo "Cleaning up temporary files..."
sudo rm -rf /tmp/var/www/html/sito_test /tmp/db_backup.sql $ZIP_FILE
if [ $? -ne 0 ]; then
    echo "Failed to clean up temporary files."
    exit 1
fi

echo "Website and database restoration completed successfully."
