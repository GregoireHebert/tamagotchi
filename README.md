# Fish
==========

Fish is a tamagotchi like project
The fish has need such as hunger, sleepiness and sometimes wants to play.
Each has an influence on it's health. If you do not take care of feeding him, put it to bed or play with it,
the fish will eventually die faster.

This project exists as a demonstration for the neat bundle.

## Play

Clone this project and run:

```shell
$ composer install
$ php bin/console doctrine:schema:create
```

to create a bunch of population and let the neat-bundle evaluate each genome, run

```shell
$ composer install
$ php bin/console gheb:neat:generate
```

to get the best genome and play it's network upon your inputs, run:

```shell
$ composer install
$ php bin/console gheb:neat:evaluate
```
