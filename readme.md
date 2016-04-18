# WordPress JobCast Plugin

***

### Installing dev environment for this plugin:

1. Setup WordPress on your local server
2. Connect to your local server via ftp then go into the following directories: '-> wp-content -> plugins'
3. Add all the files inside this git into another folder named 'jobcast-plugin'
 (make sure you **name it exactly jobcast-plugin** otherwise it will crash!)
4. Now if you head to localserver.com/wp-admin, log in with your admin account
you set up with WordPress then you'll be able to see the plugin in the left column.
5. Your dev environment is setup!


***


### Changing plugin description

+ Open file 'jobcast-plugin.php'
+ Change the comments at the top according to what you would like to see
+ WordPress uses the comments to to determine which file is the main plugin file so keep the format as is! :+1:

***


### Deactivating the plugin

Currently a user doesn't have the ability to deactivate there plugin if they wanted, however we can implement this easily if we wanted by doing the following:
+ Simply redirect the user to **deactivate_plugin.php**
+ deactivate_plugin.php handles the rest for you
+ this feature is being used in 'jobcast-main.php' for redirectErrorUrl if the apikeylogin API failed
