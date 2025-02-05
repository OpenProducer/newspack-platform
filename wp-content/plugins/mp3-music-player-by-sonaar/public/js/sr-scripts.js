(function ($) {
  "use strict";

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/sonaar-filters.default", function ($scope) {
      sr_filterInit();
    });

    elementorFrontend.hooks.addAction("frontend/element_ready/music-player.default", function ($scope) {
      if( $scope.parents('.elementor-widget-jet-listing-grid').length || $('.elementor-editor-active').length){
        return;
      }
      var elementorWidgetID = $scope[0].dataset.id;
      if (typeof setIronAudioplayers == "function") {
        setIronAudioplayers('.elementor-widget-music-player[data-id="' + elementorWidgetID + '"]');
      }
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/jet-listing-grid.default', function($scope){
      if( $scope.find('.iron-audioplayer').length && typeof setIronAudioplayers == "function"){
        var elementorWidgetID = $scope[0].dataset.id;
        setIronAudioplayers('.elementor-widget-jet-listing-grid[data-id="' + elementorWidgetID + '"]');
      }
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/woocommerce-products.default", function ($scope) {
      var elementorWidgetID = $scope[0].dataset.id;
      if (typeof setIronAudioplayers == "function") {
        setIronAudioplayers('.elementor-widget-woocommerce-products[data-id="' + elementorWidgetID + '"]');
      }
    });
  });
})(jQuery);
