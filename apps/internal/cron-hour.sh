#!/bin/sh

echo Running one minute cron job
curl http://www.263shop.co.zw/cron_job_263shop_restricted/run_cron_hourly --insecure --max-time 9999999 
echo Done running minute cron. 
