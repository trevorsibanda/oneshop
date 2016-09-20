#!/bin/sh

echo Compiling all javascript into one file
rm minified.js
echo "/**263Shop by Trevor Sibanda**/\n" >> minified.js
cat 'config.js' | python /opt/rjsmin-1.0.12/rjsmin.py >> minified.js 
echo "\n" >> minified.js
cat 'directives.js' | python /opt/rjsmin-1.0.12/rjsmin.py >> minified.js
cat 'routes.js' | python /opt/rjsmin-1.0.12/rjsmin.py >> minified.js
cat 'services.js' | python /opt/rjsmin-1.0.12/rjsmin.py >> minified.js
cat 'controllers.js' | python /opt/rjsmin-1.0.12/rjsmin.py >> minified.js
cat 'app.js' | python /opt/rjsmin-1.0.12/rjsmin.py >> minified.js

chown root *
chown www-data minified.js

echo Done
