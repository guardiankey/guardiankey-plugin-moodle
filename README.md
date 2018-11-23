# GuardianKey

GuardianKey is a service to protect systems in real-time against authentication attacks. Through an advanced Machine Learning approach, it can detect and block malicious accesses to the system, notify the legitimate user and the system administrator about such access attempts.

Beyond the security, the GuardianKey solution provides a good user experience, because the user is not required to provide extra information or to execute tasks during the login.

GuardianKey’s approach provides a risk assessment in real-time. The events and risks can be explored in the GuardianKey’s administration panel. 

**More information at https://guardiankey.io/**

# Plugin Installation

1. Unpack the plugin and move it to the directory moodle/auth/
2. Access http://yourmoodle/admin and proceed with the plugin installation
3. Access Administration-\>Plugins-\>Authentication-\>Manage Authentication
4. Enable the GuardianKey plugin clicking in the "closed eye"
5. Wait the crontab task to run and register an account in GuardianKey's webserver *OR* 
   run it manually, e.g., /usr/bin/php /var/www/moodle/admin/cli/cron.php 
6. GuardianKey should send an e-mail to the e-mail address of the Moodle admin user (id=2).
7. Check the settings of the GuardianKey plugin to see if the fields are filled. If so, it is OK.

**Notes:**
- By default, the plugin is in testing mode. In this case, your users will not receive notification messages. 
- Also, by default, the plugin is not in the active mode, which means that Moodle will never block an access if GuardianKey recommends to. 

# Using GuardianKey

Access https://panel.guardiankey.io and login using the credentials sent to your e-mail address during the registration. You can recover the pass if you forgot it.

There is a documentation for the panel available at https://guardiankey.io/panel-documentation/

If you have troubles, join the community to get help, at https://groups.google.com/forum/#!forum/guardiankey


