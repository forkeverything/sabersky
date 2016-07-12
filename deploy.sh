
now="$(date +'%d/%m/%Y/%r')"
gulp --production
git add .
git commit -m "DEPLOY LIVE AT: $now"
git push origin master

curl https://forge.laravel.com/servers/86877/sites/196834/deploy/http?token=PBsF5Pl2JEHrT2C2aprwuPu4XwvBPzpyl7ExEbDV
