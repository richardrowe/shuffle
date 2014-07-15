angular.module('shuffle.services', ['ngResource'])

.factory('Deck', function($resource) {
	return $resource('http://localhost:8000/api/v1/decks/:id')
})

.factory('Card', function($resource) {
	return $resource('http://localhost:8000/api/v1/decks/:deck_id/cards/:id')
});