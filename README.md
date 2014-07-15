 Shuffle [![Build Status](https://travis-ci.org/richardrowe/shuffle.svg?branch=master)](https://travis-ci.org/richardrowe/shuffle)
========

This project is a flash card application that intelligently serves you information you want to remember.

Built on top of Ionic, this is a hybrid HTML5 application meant to be run via PhoneGap or Cordova, and not as a web application. 

## Current Features

* RESTful API to provide the client with decks and cards
* Work in progress, lots of bugs and missing features

## Soon

* A user bundle that supports Facebook
* Token authentication for our REST API, likely through WSSE
* Implement in-memory caching on the server for API requests
* API version should be set in the header 
* Ability to work with your own decks offline on the client
* Implement Leitner algorithm for more efficient learning 
* Ability to subscribe to another person's deck of cards (see Spotify's playlists)

## Screenshots

<img src="http://richardrowe.github.io/images/shuffle-deck-index.png" alt="Shuffle deck index" height="600" width="360">
<span/>
<img src="http://richardrowe.github.io/images/shuffle-learn-index.png" alt="Shuffle learn index" height="600" width="360">

## API Documentation

http://localhost/api/doc

Bug tracking
------------

Shuffle uses [GitHub issues](https://github.com/richardrowe/shuffle/issues).
If you have found bug, please create an issue.

## Credits

`liuggio` for his brilliant tutorial on building RESTful APIs with Symfony 2. Lots of good ideas and a great starting point.