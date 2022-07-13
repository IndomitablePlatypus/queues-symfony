# CARDZ

## Preface

This repo is a lightweight version of the "Bonus Cards API" [app](https://github.com/codderzcom/cardz). Please refer to
the [Cardz](https://github.com/codderzcom/cardz) documentation to get acquainted with the domain.

Oftentimes, the value of the new project is not yet apparent, and the management wishes to test some working prototype
without fully committing to the project, thus creating the need for some kind of quick pilot project, MVP or the likes
of it. In this instance, the idea is to create a simple MVP app with an extensive use of Symfony features. We'll try
to keep the same API routes and responses as in the main app to make them compatible and interchangeable from the
frontend perspective.

In some aspects it diverges from the simplification concept, but only inasmuch as it respects general Symfony
guidelines and accepted practices. For example, we use a RabbitMQ docker container instead of using a fake provider.
Given the availability of containers, queues, RabbitMQ and such, we use async messaging to handle asynchronous data
transfer when applicable.

Authorization is simplified almost to the level non-existence. Standard Symfony package is used to authenticate users
without any following authorization by roles. All further authorization checks are performed via the simple way of
traversing the models' relation tree.

This version casts aside a lot of design patterns used in the main one. This, of course, does not mean that it's
unstructured or not scalable. Though, admittedly, it's not exactly a great example of valid Hexagonal architecture or
such. Still, it's quite possible to develop this MVP into a fully functional production application.

The main difference between the two approaches (this and the [main app](https://github.com/codderzcom/cardz)) is the
starting point of development and presumed managerial requirements.

## Installation instructions

- clone the [repo](https://github.com/codderzcom/queues-symfony) with `git clone`;
- run `composer install`;
- copy `.env.example` to `.env`;
- provide your app secret;
- run `docker-compose build` to build the containers;
- run `docker-compose up`;
- run the migrations for your DB with `php bin/console doctrine:migrations:migrate`;
- the demo application is now running on the `https://localhost/`. Take a look at the API docs in Nelmio style
  at the [/api/v1/doc](https://localhost/api/v1/doc), or in RapidJS style at the [root](https://localhost/)

Optionally, you can run `php bin/phpunit` to take a look at the small assortment of included tests.
For this to work you will need to either add `app_testing` database in the postgres container or to modify
your config to work with some other database.
For the tests to run you'll need to run the migrations on the test
database `php bin/console doctrine:migrations:migrate --env=test` and then to
populate it with the fixtures: `php bin/console doctrine:fixtures:load --env=test`
All these commands need to be executed in the CLI of the php container (`docker exec -it queues-symfony_php_1 sh`)

### Code structure

App code lies within the `src` directory. Tests and data fixtures can be found in `tests` directory.

The actual domain of this app is rather small, all things considered, and discarding most aspects of the proper
anticipating design process by specifically posing it as oversimplified MVP allows us to contain business invariants in
two distinct places: in the models themselves and via the way of traversing the model relation tree during requests -
just as with the authorisation.   
