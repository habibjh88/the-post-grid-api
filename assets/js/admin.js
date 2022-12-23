/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!******************************!*\
  !*** ./src/scripts/admin.js ***!
  \******************************/


(function ($) {
  $(document).ready(function () {
    //category image upload
    var meta_image_frame;
    $('#upload_image_btn').click(function (e) {
      e.preventDefault();
      if (meta_image_frame) {
        meta_image_frame.open();
        return;
      }

      // Sets up the media library frame
      meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
        title: 'Upload Category Image',
        button: {
          text: 'Upload Image'
        },
        library: {
          type: 'image'
        }
      });
      meta_image_frame.on('select', function () {
        var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
        $('.category-image').html("<div class='category-image-wrap'><img src='".concat(media_attachment.url, "' width='200' /><input type=\"hidden\" name=\"rt_category_image\" value='").concat(media_attachment.id, "' class=\"category-image-id\"/><button>x</button></div>"));
      });
      meta_image_frame.open();
    });
    $(document).on("click", ".category-image-wrap button", function () {
      $(this).parent().remove();
    });
  });

  // jquery passing
})(jQuery);
/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiL2Fzc2V0cy9qcy9hZG1pbi5qcyIsIm1hcHBpbmdzIjoiOzs7Ozs7QUFBYTs7QUFFYixDQUFDLFVBQVVBLENBQUMsRUFBRTtFQUdWQSxDQUFDLENBQUNDLFFBQVEsQ0FBQyxDQUFDQyxLQUFLLENBQUMsWUFBWTtJQUUxQjtJQUNBLElBQUlDLGdCQUFnQjtJQUNwQkgsQ0FBQyxDQUFDLG1CQUFtQixDQUFDLENBQUNJLEtBQUssQ0FBQyxVQUFVQyxDQUFDLEVBQUU7TUFDdENBLENBQUMsQ0FBQ0MsY0FBYyxFQUFFO01BQ2xCLElBQUlILGdCQUFnQixFQUFFO1FBQ2xCQSxnQkFBZ0IsQ0FBQ0ksSUFBSSxFQUFFO1FBQ3ZCO01BQ0o7O01BRUE7TUFDQUosZ0JBQWdCLEdBQUdLLEVBQUUsQ0FBQ0MsS0FBSyxDQUFDQyxNQUFNLENBQUNQLGdCQUFnQixHQUFHSyxFQUFFLENBQUNDLEtBQUssQ0FBQztRQUMzREUsS0FBSyxFQUFFLHVCQUF1QjtRQUM5QkMsTUFBTSxFQUFFO1VBQUNDLElBQUksRUFBRTtRQUFjLENBQUM7UUFDOUJDLE9BQU8sRUFBRTtVQUFDQyxJQUFJLEVBQUU7UUFBTztNQUMzQixDQUFDLENBQUM7TUFFRlosZ0JBQWdCLENBQUNhLEVBQUUsQ0FBQyxRQUFRLEVBQUUsWUFBWTtRQUN0QyxJQUFJQyxnQkFBZ0IsR0FBR2QsZ0JBQWdCLENBQUNlLEtBQUssRUFBRSxDQUFDQyxHQUFHLENBQUMsV0FBVyxDQUFDLENBQUNDLEtBQUssRUFBRSxDQUFDQyxNQUFNLEVBQUU7UUFDakZyQixDQUFDLENBQUMsaUJBQWlCLENBQUMsQ0FBQ3NCLElBQUksc0RBQStDTCxnQkFBZ0IsQ0FBQ00sR0FBRyxzRkFBd0VOLGdCQUFnQixDQUFDTyxFQUFFLDZEQUF3RDtNQUNuUCxDQUFDLENBQUM7TUFFRnJCLGdCQUFnQixDQUFDSSxJQUFJLEVBQUU7SUFDM0IsQ0FBQyxDQUFDO0lBRUZQLENBQUMsQ0FBQ0MsUUFBUSxDQUFDLENBQUNlLEVBQUUsQ0FBQyxPQUFPLEVBQUUsNkJBQTZCLEVBQUUsWUFBWTtNQUMvRGhCLENBQUMsQ0FBQyxJQUFJLENBQUMsQ0FBQ3lCLE1BQU0sRUFBRSxDQUFDQyxNQUFNLEVBQUU7SUFDN0IsQ0FBQyxDQUFDO0VBQ04sQ0FBQyxDQUFDOztFQUdOO0FBQ0EsQ0FBQyxFQUFFQyxNQUFNLENBQUMsQyIsInNvdXJjZXMiOlsiLi9zcmMvc2NyaXB0cy9hZG1pbi5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyJcInVzZSBzdHJpY3RcIjtcclxuXHJcbihmdW5jdGlvbiAoJCkge1xyXG5cclxuXHJcbiAgICAkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgIC8vY2F0ZWdvcnkgaW1hZ2UgdXBsb2FkXHJcbiAgICAgICAgbGV0IG1ldGFfaW1hZ2VfZnJhbWU7XHJcbiAgICAgICAgJCgnI3VwbG9hZF9pbWFnZV9idG4nKS5jbGljayhmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgIGlmIChtZXRhX2ltYWdlX2ZyYW1lKSB7XHJcbiAgICAgICAgICAgICAgICBtZXRhX2ltYWdlX2ZyYW1lLm9wZW4oKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8gU2V0cyB1cCB0aGUgbWVkaWEgbGlicmFyeSBmcmFtZVxyXG4gICAgICAgICAgICBtZXRhX2ltYWdlX2ZyYW1lID0gd3AubWVkaWEuZnJhbWVzLm1ldGFfaW1hZ2VfZnJhbWUgPSB3cC5tZWRpYSh7XHJcbiAgICAgICAgICAgICAgICB0aXRsZTogJ1VwbG9hZCBDYXRlZ29yeSBJbWFnZScsXHJcbiAgICAgICAgICAgICAgICBidXR0b246IHt0ZXh0OiAnVXBsb2FkIEltYWdlJ30sXHJcbiAgICAgICAgICAgICAgICBsaWJyYXJ5OiB7dHlwZTogJ2ltYWdlJ31cclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICBtZXRhX2ltYWdlX2ZyYW1lLm9uKCdzZWxlY3QnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgbWVkaWFfYXR0YWNobWVudCA9IG1ldGFfaW1hZ2VfZnJhbWUuc3RhdGUoKS5nZXQoJ3NlbGVjdGlvbicpLmZpcnN0KCkudG9KU09OKCk7XHJcbiAgICAgICAgICAgICAgICAkKCcuY2F0ZWdvcnktaW1hZ2UnKS5odG1sKGA8ZGl2IGNsYXNzPSdjYXRlZ29yeS1pbWFnZS13cmFwJz48aW1nIHNyYz0nJHttZWRpYV9hdHRhY2htZW50LnVybH0nIHdpZHRoPScyMDAnIC8+PGlucHV0IHR5cGU9XCJoaWRkZW5cIiBuYW1lPVwicnRfY2F0ZWdvcnlfaW1hZ2VcIiB2YWx1ZT0nJHttZWRpYV9hdHRhY2htZW50LmlkfScgY2xhc3M9XCJjYXRlZ29yeS1pbWFnZS1pZFwiLz48YnV0dG9uPng8L2J1dHRvbj48L2Rpdj5gKTtcclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICBtZXRhX2ltYWdlX2ZyYW1lLm9wZW4oKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgJChkb2N1bWVudCkub24oXCJjbGlja1wiLCBcIi5jYXRlZ29yeS1pbWFnZS13cmFwIGJ1dHRvblwiLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICQodGhpcykucGFyZW50KCkucmVtb3ZlKCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9KVxyXG5cclxuXHJcbi8vIGpxdWVyeSBwYXNzaW5nXHJcbn0pKGpRdWVyeSk7Il0sIm5hbWVzIjpbIiQiLCJkb2N1bWVudCIsInJlYWR5IiwibWV0YV9pbWFnZV9mcmFtZSIsImNsaWNrIiwiZSIsInByZXZlbnREZWZhdWx0Iiwib3BlbiIsIndwIiwibWVkaWEiLCJmcmFtZXMiLCJ0aXRsZSIsImJ1dHRvbiIsInRleHQiLCJsaWJyYXJ5IiwidHlwZSIsIm9uIiwibWVkaWFfYXR0YWNobWVudCIsInN0YXRlIiwiZ2V0IiwiZmlyc3QiLCJ0b0pTT04iLCJodG1sIiwidXJsIiwiaWQiLCJwYXJlbnQiLCJyZW1vdmUiLCJqUXVlcnkiXSwic291cmNlUm9vdCI6IiJ9