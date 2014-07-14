angular.module('shuffle.controllers', ['shuffle.services'])

.controller('CardIndexCtrl', function($scope, $ionicSwipeCardDelegate, $rootScope) {
  $rootScope.accepted = 0;
  $rootScope.rejected = 0;
  var cardTypes = [
    { front: 'a', back: 'ア' },
    { front: 'i', back: 'イ' },
    { front: 'u', back: 'ウ' },
    { front: 'e', back: 'エ' },
    { front: 'o', back: 'オ' }
  ];

  $scope.cards = Array.prototype.slice.call(cardTypes, 0, 0);

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
    var newCard = cardTypes[Math.floor(Math.random() * cardTypes.length)];
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