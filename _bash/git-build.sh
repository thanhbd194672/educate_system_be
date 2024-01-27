#!/bin/bash

tag=latest;
distro=alpine;

while getopts "t:" flag
do
  # shellcheck disable=SC2220
  case "${flag}" in
    t) tag="${OPTARG}";;
  esac
done

docker buildx build -t registry.gitlab.com/thanhbd194672/itss2-api-rent-home:"$tag" -f Dockerfile-$distro --build-arg B_TAG="$tag" .
docker push registry.gitlab.com/thanhbd194672/itss2-api-rent-home:"$tag"

file_log="_bash/build.log"
if [ -f "$file_log" ]; then
    line_count=$(grep -c -v '^ *$' "$file_log")

    if [ "$line_count" -gt 100 ]; then
        mv "$file_log" "_bash/logs/build-$(date +'%Y%m%d').log"
        touch "$file_log"
        echo "Backup log"
    fi
fi

printf '%s\n%s\n' "[$(date '+%d/%m/%Y %H:%M:%S')] BUILD: $tag - AUTHOR: $(git config user.name) <$(git config user.email)>" "$(cat $file_log)" > "$file_log"
