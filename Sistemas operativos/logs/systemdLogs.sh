#!/bin/bash
journalctl -o cat -u httpd > /var/log/customlogs/ApacheSystemd.log
journalctl -o cat -u mariadb > /var/log/customlogs/MariaDBSystemd.log
journalctl -o cat -u prometheus.service > /var/log/customlogs/prometheusSystemd.log
journalctl -o cat -u grafana-server > /var/log/customlogs/GrafanaSystemd.log
journalctl -o cat -u clamd@scan > /var/log/customlogs/clamdSystemd.log
