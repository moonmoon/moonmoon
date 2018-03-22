#!/bin/bash

set -eu

tmp=$(mktemp -d -t moonmmon)

echo "[*] Building into $tmp..."

cd "$tmp"
git clone https://github.com/moonmoon/moonmoon.git --depth=1 --recursive -j8
cd moonmoon
composer install --no-suggest --prefer-dist --no-dev
git describe --abbrev=0 --tags > VERSION
find . -name .DS_Store -exec rm {} \;
rm -rf .git .github .travis.yml .gitignore .gitmodules docs/.git/
mkdir cache
cd ..
zip -r "moonmoon-$(cat moonmoon/VERSION).zip" .

echo "[*] Grab the archive: ${tmp}/moonmoon-$(cat moonmoon/VERSION).zip"
