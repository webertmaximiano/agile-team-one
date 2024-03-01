$(document).ready(function () {
  var masonryOptions = {
    columnWidth: '.masonry-sizer',
    itemSelector: '.masonry-item',
    percentPosition: true
  };
  var $masonryContainer = $('.masonry-container');
  var masonryBreakpoint = 767; // change this as you wish
  var masonryActive = false;
  var activateMasonry = function () {
    if (masonryActive === false) {
      $masonryContainer.masonry(masonryOptions);
      masonryActive = true;
      console.log('bootstrap masonry activated: width ' + $(document).width());
    }
  };
  var destroyMasonry = function () {
    if (masonryActive === true) {
      $masonryContainer.masonry('destroy');
      masonryActive = false;
      console.log('bootstrap masonry destroied: width ' + $(document).width());
    }
  };
  if ($(document).width() > masonryBreakpoint) {
    activateMasonry();
    $masonryContainer.resize(function () {
      console.log('bootstrap masonry container resized');
      if ($(document).width() < masonryBreakpoint) {
        destroyMasonry();
      } else {
        activateMasonry();
      }
    });
  }
  else {
    console.log('bootstrap masonry not activated: width ' + $(document).width());
  }
  $(window).resize(function () {
    if ($(document).width() > masonryBreakpoint) {
      activateMasonry();
      $masonryContainer.resize(function () {
        console.log('bootstrap masonry container resized');
        if ($(document).width() < masonryBreakpoint) {
          destroyMasonry();
        } else {
          activateMasonry();
        }
      });
    } else {
      destroyMasonry();
    }
  });
});