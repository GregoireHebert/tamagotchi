# Tamagotchi
==========

Tamagotchi is a tamagotchi like project
The tamagotchi has need such as hunger, sleepiness and sometimes wants to play.
Each has an influence on it's health. If you do not take care of feeding him, put it to bed or play with it,
the tamagotchi will eventually die faster.

This project exists as a demonstration for the neat bundle.

## Play

This project comes with a docker file for the database.
and a dev compose file with adminer to navigate through the db while debugging.

```shell
$ docker-compose up -d
```

or

```shell
$ docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d
```

Clone this project and run:

```shell
$ composer install
$ php bin/console doctrine:schema:create
$ php bin/console assetic:dump --env=prod
```

to create a bunch of population and let the neat-bundle evaluate each genome, run

```shell
$ php bin/console gheb:neat:generate
```
    
to get the best genome and play it's network upon your inputs, run:

```shell
$ php bin/console gheb:neat:evaluate
```

you can run a webserver and the websocket by using the start script

```bash
$ ./start
```

you can now lauch your navigator

```bash
$ chromium-browser --start-fullscreen --start-maximized --no-default-browser-check --incognito http://127.0.0.1:8000 &>/dev/null &
```

to run the thing in the background you'll need supervisor

```shell
$ apt install supervisor
```

```ini
# /etc/supervisor/conf.d/tamagotchi.conf
[program:tamagotchi]
user=gregoire
command=/home/gregoire/TRAVAUX/PERSO/tamagotchi/start

# uncomment these lines to launch this at start
#autostart=true
#autorestart=true

stdout_logfile=/home/gregoire/TRAVAUX/PERSO/tamagotchi/var/logs/supervisor_stdout.log
stderr_logfile=/home/gregoire/TRAVAUX/PERSO/tamagotchi/var/logs/supervisor_stderr.log
```

```shell
$ supervisorctl reread
$ supervisorctl update
$ supervisorctl
tamagotchi                       STOPPED   Jan 14 02:33 PM
supervisor> 

#Now, we can start, stop and restart the tamagotchi:
 
supervisor> stop tamagotchi
tamagotchi: stopped
supervisor> start tamagotchi
tamagotchi: started
supervisor> restart tamagotchi
tamagotchi: stopped
tamagotchi: started

# You may also view the most recent entries from stdout and stderr logs using tail command:

supervisor> tail tamagotchi
[OK] Web server listening on http://127.0.0.1:8000

supervisor> tail tamagotchi stderr
[2018-01-14 15:34:43] websocket.INFO: Launching Ratchet on 127.0.0.1:1337 PID: 6305  
[2018-01-14 15:35:16] websocket.WARNING: User firewall is not configured, we have set ws_firewall by default  
[2018-01-14 15:35:16] websocket.INFO: anon-9760925065a5b6aa49679d118520908 connected {"connection_id":482,"session_id":"9760925065a5b6aa49679d118520908","storage_id":482} 
[2018-01-14 15:35:16] websocket.INFO: anon-9760925065a5b6aa49679d118520908 subscribe to output/application  
[2018-01-14 15:35:18] websocket.INFO: anon-9760925065a5b6aa49679d118520908 disconnected {"connection_id":482,"session_id":"9760925065a5b6aa49679d118520908","storage_id":482,"username":"anon-9760925065a5b6aa49679d118520908"} 

# Checking program status after making changes is easy as well:

supervisor> status
tamagotchi                       RUNNING   pid 26039, uptime 0:00:24
```

`error: <class 'socket.error'>, [Errno 13] Permission denied: file: /usr/lib/python2.7/socket.py line: 228`

if you got this message when lauching `supervisorctl` command, 
update `/etc/supervisor/supervisor.conf` and change the `unix_http_server` chmod to `766`


## todo
* display it's condition and it's suggested animation between each tick
* define the rythm to call a new evaluation.