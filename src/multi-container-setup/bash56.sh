#!/bin/sh

CWD="/shared/httpd"
docker-compose exec --user devilbox php56 env TERM=xterm /bin/sh -c "cd ${CWD}; exec bash -l"
