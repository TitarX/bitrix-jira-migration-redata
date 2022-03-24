# bitrix-jira-migration-redata

Run from CLI:
> /usr/bin/php -d short_open_tag=1 /path/to/site/jira_migration_redata/index.php

Run from CLI for Vagrant and specific PHP version:
> /usr/bin/php7.3 -d short_open_tag=1 /home/vagrant/code/site/jira_migration_redata/index.php

Run background long process from PHP code:
> exec(/usr/bin/php7.3 -d short_open_tag=1 /home/vagrant/code/site/jira_migration_redata/index.php);

Stop background process:
> pkill -f /home/vagrant/code/site/jira_migration_redata/index.php
