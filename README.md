# Tamagotchi
==========

A colleague of mine once came with a fish at work.
At first I thought, wwaow, I need one too ! And then I thought of every single day I won't be able to feed him :(
This is when I decided as a side project to build a Tamagotchi Like project, and use an AI to decide what's to do next.
 
The tamagotchi has need such as hunger, sleepiness and sometimes wants to play.
Each has an influence on it's health. If you do not take care of feeding him, put it to bed or play with it,
the tamagotchi will eventually die faster.

Since my skills in animations/drawing are bellow zero, I used some free spritesheets. 

I could have used tensorflow or phpml but I wanted to use this project to learn how it works.
And this project is the result with the idea of reuse it later for other projects.

There is many things I would change, or not do the same way I did, but this is an experimental project.
Maybe I'll code a better one later :)

The Front is designed to be displayed on a 800x480 raspberry screen but should works on any screen that respect the ratio.

## Play

Clone the project or install it with composer:

```shell
$ composer create-project gheb/tamagotchi
```

This project comes with a docker file for the database.
and a dev compose file with adminer to navigate through the db while debugging.

```shell
$ docker-compose up -d
```

or

```shell
$ docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d
```

then 

```shell
$ docker/console doctrine:schema:create
$ docker/start
```

If you do not use docker, clone this project.
You'll need yarn, composer, mysql, php7.1.
And then execute the commands:


```shell
$ composer install
$ php bin/console doctrine:schema:create
$ cd web && yarn install && yarn run build
```

Then you need to train the AI. 
I encourage you to read how NEAT (Neuro Evolution of Augmented Topology) Génetic Algorithm works.
To create a bunch of population and let the neat-bundle evaluate each genome to learn, run:

```shell
$ docker/console gheb:neat:generate
$ php bin/console gheb:neat:generate
```

It's an infinite loop that'll try it's best to evolve a neural network and smash the score !
  
In order to get the best genome and play it's network upon the inputs, run:

```shell
$ docker/console gheb:neat:evaluate
$ php bin/console gheb:neat:evaluate
```

**If you don't use docker**, you can run a webserver and the websocket by using the start script.

```bash
$ php bin/console server:start localhost -p 8000
$ php bin/console gos:web:ser --env=prod
```

you can now launch your navigator and open the http://localhost:8000

```bash
$ chromium-browser --start-fullscreen --start-maximized --no-default-browser-check --incognito http://localhost:8000 &>/dev/null &
```

to run the all thing in the background you'll need supervisor

```shell
$ sudo apt install supervisor
```

Create a configuration file for the program

```ini
# /etc/supervisor/conf.d/tamagotchi.conf
[program:tamagotchi]

# change this with your username
user=gregoire

# change according to your path
command=/home/gregoire/TRAVAUX/PERSO/tamagotchi/start 

# uncomment these lines to launch this at start
#autostart=true
#autorestart=true

# change according to your path
stdout_logfile=/home/gregoire/TRAVAUX/PERSO/tamagotchi/var/logs/supervisor_stdout.log
stderr_logfile=/home/gregoire/TRAVAUX/PERSO/tamagotchi/var/logs/supervisor_stderr.log
```

Then you must tell supervisor to get and load this new config.

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

## TROUBLESHOUTING

`error: <class 'socket.error'>, [Errno 13] Permission denied: file: /usr/lib/python2.7/socket.py line: 228`

if you got this message when lauching `supervisorctl` command, 
update `/etc/supervisor/supervisor.conf` and change the `unix_http_server` chmod to `766`


## Todo
* Define the rythm to call a new evaluation and provide a way to use crontab or something else.
* for demonstration purposes, display a graph of the network.