#!/bin/bash

composer -vvv install

php /app/tests/_app/yii.php migrate --migrationPath=@SomeBlackMagic/Yii2User/src/Migrations --interactive=0
# This wi
# ll exec the CMD from your Dockerfile, i.e. "npm start"
exec "$@"
