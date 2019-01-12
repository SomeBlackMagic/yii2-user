#!/bin/bash

composer -vvv install

# This wi
# ll exec the CMD from your Dockerfile, i.e. "npm start"
exec "$@"
