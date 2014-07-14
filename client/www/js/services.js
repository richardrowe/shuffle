angular.module('shuffle.services', ['ngResource'])

.factory('Deck', function($resource) {
	return $resource('http://localhost:8000/api/v1/decks/:id')
});