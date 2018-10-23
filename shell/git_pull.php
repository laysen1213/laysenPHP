<?php
date_default_timezone_set('Asia/Shanghai');
shell_exec("sh /mygit/laysen/server/shell/git_pull.sh");
// shell_exec("cd /mygit/laysen/ && git pull");
echo date('Y-m-d H:i:s',time());
