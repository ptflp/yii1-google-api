#!/bin/bash
echo 'fix www-data permission'
chown -R www-data:www-data ./protected
chown -R www-data:www-data ./web