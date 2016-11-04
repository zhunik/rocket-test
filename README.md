# NOTAM locator

Finds NOTAM information for provided ICAO code & displays it on Google Map.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

Bassically all you need is composer.

```
php composer-setup.php --install-dir=bin --filename=composer
```

### Installing

To get app running on your dev machine you should do next.

Clone git repo.

```
git clone git@github.com:zhunik/rocket-test.git
```
 
Go to `rocket-test` directory & run composer

Copy `.env.example` file to `.env` and provide your config data in `.env` file.

```
cp .env.example .env
```
Run `docker-compose` to up development server. Or you can config your own. [Here](http://silex.sensiolabs.org/doc/master/web_servers.html) you can find alot of examples.

```
docker-compose up -d
```

Open `localhost` in your browser and now you shuld be rolling. 

## Running the tests

Going in the next version.

## Built With

* [Silex](http://silex.sensiolabs.org/) - The PHP micro-framework based on the Symfony Components.
* [RocketRoute API](http://www.rocketroute.com/developers/) - SOAP API to get NOTAM information for ICAO codes.
* [Google Maps JS API](https://developers.google.com/maps/documentation/javascript/) - GMaps JS APi to disply NOTAM info.

## Authors

* **Yurii Zhunkivskyi** - *Initial work* 

## License

This project is licensed under the MIT License

