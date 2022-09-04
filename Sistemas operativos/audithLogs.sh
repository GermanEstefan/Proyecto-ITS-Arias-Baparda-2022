#!bin/bash

"$(last)" > /var/log/customlogs/last.log
"$(lastlog | grep -v "Nunca ha accedido")" > /var/log/customlogs/last_log.log
