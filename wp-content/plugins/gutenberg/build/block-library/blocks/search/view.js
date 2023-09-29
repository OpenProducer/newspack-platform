/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: external ["wp","interactivity"]
const external_wp_interactivity_namespaceObject = window["wp"]["interactivity"];
;// CONCATENATED MODULE: ./packages/block-library/build-module/search/view.js
/**
 * WordPress dependencies
 */

(0,external_wp_interactivity_namespaceObject.store)({
  selectors: {
    core: {
      search: {
        ariaLabel: ({
          context
        }) => {
          const {
            ariaLabelCollapsed,
            ariaLabelExpanded
          } = context.core.search;
          return context.core.search.isSearchInputVisible ? ariaLabelExpanded : ariaLabelCollapsed;
        },
        ariaControls: ({
          context
        }) => {
          return context.core.search.isSearchInputVisible ? null : context.core.search.inputId;
        },
        type: ({
          context
        }) => {
          return context.core.search.isSearchInputVisible ? 'submit' : 'button';
        },
        tabindex: ({
          context
        }) => {
          return context.core.search.isSearchInputVisible ? '0' : '-1';
        }
      }
    }
  },
  actions: {
    core: {
      search: {
        openSearchInput: ({
          context,
          event,
          ref
        }) => {
          if (!context.core.search.isSearchInputVisible) {
            event.preventDefault();
            context.core.search.isSearchInputVisible = true;
            ref.parentElement.querySelector('input').focus();
          }
        },
        closeSearchInput: ({
          context
        }) => {
          context.core.search.isSearchInputVisible = false;
        },
        handleSearchKeydown: store => {
          const {
            actions,
            event,
            ref
          } = store;
          // If Escape close the menu.
          if (event?.key === 'Escape') {
            actions.core.search.closeSearchInput(store);
            ref.querySelector('button').focus();
          }
        },
        handleSearchFocusout: store => {
          const {
            actions,
            event,
            ref
          } = store;
          // If focus is outside search form, and in the document, close menu
          // event.target === The element losing focus
          // event.relatedTarget === The element receiving focus (if any)
          // When focusout is outside the document,
          // `window.document.activeElement` doesn't change.
          if (!ref.contains(event.relatedTarget) && event.target !== window.document.activeElement) {
            actions.core.search.closeSearchInput(store);
          }
        }
      }
    }
  }
});

/******/ })()
;