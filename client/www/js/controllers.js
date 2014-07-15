angular.module('shuffle.controllers', ['shuffle.services'])

.controller('LearnIndexCtrl', function($scope, $ionicSwipeCardDelegate, $ionicNavBarDelegate, $rootScope, $stateParams, Card) {
  
  $rootScope.accepted = 0;
  $rootScope.rejected = 0;

  var cards = Card.query({ deck_id: $stateParams.deckId });

  $scope.cards = cards;

  $scope.cardSwiped = function(index) {
    $scope.addCard();
  };

  $scope.cardDestroyed = function(index) {
    if (this.swipeCard.positive === true) {
      $scope.$root.accepted++;
    } else {
      $scope.$root.rejected++;
    }
    $scope.cards.splice(index, 1);
  };

  $scope.addCard = function() {
    var newCard = cards[Math.floor(Math.random() * cards.length)];
    newCard.id = Math.random();
    $scope.cards.push(angular.extend({}, newCard));
  }
})

.controller('CardCtrl', function($scope, $ionicSwipeCardDelegate, $rootScope) {
  $scope.accept = function () {
    var card = $ionicSwipeCardDelegate.getSwipebleCard($scope);
    $rootScope.accepted++;
    card.swipe(true);
  }
  $scope.reject = function() {
    var card = $ionicSwipeCardDelegate.getSwipebleCard($scope);
    $rootScope.rejected++;
    card.swipe();
  };
})

.controller('DeckIndexCtrl', function($scope, Deck) {
  var decks = Deck.query();

  $scope.decks = decks;
});