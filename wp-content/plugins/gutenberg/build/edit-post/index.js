/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 4403:
/***/ ((module, exports) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2018 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString === Object.prototype.toString) {
					for (var key in arg) {
						if (hasOwn.call(arg, key) && arg[key]) {
							classes.push(key);
						}
					}
				} else {
					classes.push(arg.toString());
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  PluginBlockSettingsMenuItem: () => (/* reexport */ PluginBlockSettingsMenuItem),
  PluginDocumentSettingPanel: () => (/* reexport */ PluginDocumentSettingPanel),
  PluginMoreMenuItem: () => (/* reexport */ PluginMoreMenuItem),
  PluginPostPublishPanel: () => (/* reexport */ PluginPostPublishPanel),
  PluginPostStatusInfo: () => (/* reexport */ PluginPostStatusInfo),
  PluginPrePublishPanel: () => (/* reexport */ PluginPrePublishPanel),
  PluginSidebar: () => (/* reexport */ PluginSidebar),
  PluginSidebarMoreMenuItem: () => (/* reexport */ PluginSidebarMoreMenuItem),
  __experimentalFullscreenModeClose: () => (/* reexport */ fullscreen_mode_close),
  __experimentalMainDashboardButton: () => (/* reexport */ main_dashboard_button),
  __experimentalPluginPostExcerpt: () => (/* reexport */ __experimentalPluginPostExcerpt),
  initializeEditor: () => (/* binding */ initializeEditor),
  reinitializeEditor: () => (/* binding */ reinitializeEditor),
  store: () => (/* reexport */ store)
});

// NAMESPACE OBJECT: ./packages/edit-post/build-module/store/actions.js
var actions_namespaceObject = {};
__webpack_require__.r(actions_namespaceObject);
__webpack_require__.d(actions_namespaceObject, {
  __experimentalSetPreviewDeviceType: () => (__experimentalSetPreviewDeviceType),
  __unstableCreateTemplate: () => (__unstableCreateTemplate),
  closeGeneralSidebar: () => (closeGeneralSidebar),
  closeModal: () => (closeModal),
  closePublishSidebar: () => (closePublishSidebar),
  hideBlockTypes: () => (hideBlockTypes),
  initializeMetaBoxes: () => (initializeMetaBoxes),
  metaBoxUpdatesFailure: () => (metaBoxUpdatesFailure),
  metaBoxUpdatesSuccess: () => (metaBoxUpdatesSuccess),
  openGeneralSidebar: () => (openGeneralSidebar),
  openModal: () => (openModal),
  openPublishSidebar: () => (openPublishSidebar),
  removeEditorPanel: () => (removeEditorPanel),
  requestMetaBoxUpdates: () => (requestMetaBoxUpdates),
  setAvailableMetaBoxesPerLocation: () => (setAvailableMetaBoxesPerLocation),
  setIsEditingTemplate: () => (setIsEditingTemplate),
  setIsInserterOpened: () => (setIsInserterOpened),
  setIsListViewOpened: () => (setIsListViewOpened),
  showBlockTypes: () => (showBlockTypes),
  switchEditorMode: () => (switchEditorMode),
  toggleDistractionFree: () => (toggleDistractionFree),
  toggleEditorPanelEnabled: () => (toggleEditorPanelEnabled),
  toggleEditorPanelOpened: () => (toggleEditorPanelOpened),
  toggleFeature: () => (toggleFeature),
  togglePinnedPluginItem: () => (togglePinnedPluginItem),
  togglePublishSidebar: () => (togglePublishSidebar),
  updatePreferredStyleVariations: () => (updatePreferredStyleVariations)
});

// NAMESPACE OBJECT: ./packages/edit-post/build-module/store/selectors.js
var selectors_namespaceObject = {};
__webpack_require__.r(selectors_namespaceObject);
__webpack_require__.d(selectors_namespaceObject, {
  __experimentalGetInsertionPoint: () => (__experimentalGetInsertionPoint),
  __experimentalGetPreviewDeviceType: () => (__experimentalGetPreviewDeviceType),
  areMetaBoxesInitialized: () => (areMetaBoxesInitialized),
  getActiveGeneralSidebarName: () => (getActiveGeneralSidebarName),
  getActiveMetaBoxLocations: () => (getActiveMetaBoxLocations),
  getAllMetaBoxes: () => (getAllMetaBoxes),
  getEditedPostTemplate: () => (getEditedPostTemplate),
  getEditorMode: () => (getEditorMode),
  getHiddenBlockTypes: () => (getHiddenBlockTypes),
  getMetaBoxesPerLocation: () => (getMetaBoxesPerLocation),
  getPreference: () => (getPreference),
  getPreferences: () => (getPreferences),
  hasMetaBoxes: () => (hasMetaBoxes),
  isEditingTemplate: () => (isEditingTemplate),
  isEditorPanelEnabled: () => (isEditorPanelEnabled),
  isEditorPanelOpened: () => (isEditorPanelOpened),
  isEditorPanelRemoved: () => (isEditorPanelRemoved),
  isEditorSidebarOpened: () => (isEditorSidebarOpened),
  isFeatureActive: () => (isFeatureActive),
  isInserterOpened: () => (isInserterOpened),
  isListViewOpened: () => (isListViewOpened),
  isMetaBoxLocationActive: () => (isMetaBoxLocationActive),
  isMetaBoxLocationVisible: () => (isMetaBoxLocationVisible),
  isModalActive: () => (isModalActive),
  isPluginItemPinned: () => (isPluginItemPinned),
  isPluginSidebarOpened: () => (isPluginSidebarOpened),
  isPublishSidebarOpened: () => (isPublishSidebarOpened),
  isSavingMetaBoxes: () => (selectors_isSavingMetaBoxes)
});

;// CONCATENATED MODULE: external "React"
const external_React_namespaceObject = window["React"];
;// CONCATENATED MODULE: external ["wp","blocks"]
const external_wp_blocks_namespaceObject = window["wp"]["blocks"];
;// CONCATENATED MODULE: external ["wp","blockLibrary"]
const external_wp_blockLibrary_namespaceObject = window["wp"]["blockLibrary"];
;// CONCATENATED MODULE: external ["wp","deprecated"]
const external_wp_deprecated_namespaceObject = window["wp"]["deprecated"];
var external_wp_deprecated_default = /*#__PURE__*/__webpack_require__.n(external_wp_deprecated_namespaceObject);
;// CONCATENATED MODULE: external ["wp","element"]
const external_wp_element_namespaceObject = window["wp"]["element"];
;// CONCATENATED MODULE: external ["wp","data"]
const external_wp_data_namespaceObject = window["wp"]["data"];
;// CONCATENATED MODULE: external ["wp","preferences"]
const external_wp_preferences_namespaceObject = window["wp"]["preferences"];
;// CONCATENATED MODULE: external ["wp","widgets"]
const external_wp_widgets_namespaceObject = window["wp"]["widgets"];
;// CONCATENATED MODULE: external ["wp","editor"]
const external_wp_editor_namespaceObject = window["wp"]["editor"];
;// CONCATENATED MODULE: external ["wp","hooks"]
const external_wp_hooks_namespaceObject = window["wp"]["hooks"];
;// CONCATENATED MODULE: external ["wp","mediaUtils"]
const external_wp_mediaUtils_namespaceObject = window["wp"]["mediaUtils"];
;// CONCATENATED MODULE: ./packages/edit-post/build-module/hooks/components/index.js
/**
 * WordPress dependencies
 */


const replaceMediaUpload = () => external_wp_mediaUtils_namespaceObject.MediaUpload;
(0,external_wp_hooks_namespaceObject.addFilter)('editor.MediaUpload', 'core/edit-post/replace-media-upload', replaceMediaUpload);

;// CONCATENATED MODULE: external ["wp","components"]
const external_wp_components_namespaceObject = window["wp"]["components"];
;// CONCATENATED MODULE: external ["wp","blockEditor"]
const external_wp_blockEditor_namespaceObject = window["wp"]["blockEditor"];
;// CONCATENATED MODULE: external ["wp","i18n"]
const external_wp_i18n_namespaceObject = window["wp"]["i18n"];
;// CONCATENATED MODULE: external ["wp","compose"]
const external_wp_compose_namespaceObject = window["wp"]["compose"];
;// CONCATENATED MODULE: ./packages/edit-post/build-module/hooks/validate-multiple-use/index.js

/**
 * WordPress dependencies
 */








/**
 * Recursively find very first block of an specific block type.
 *
 * @param {Object[]} blocks List of blocks.
 * @param {string}   name   Block name to search.
 *
 * @return {Object|undefined} Return block object or undefined.
 */
function findFirstOfSameType(blocks, name) {
  if (!Array.isArray(blocks) || !blocks.length) {
    return;
  }
  for (const block of blocks) {
    if (block.name === name) {
      return block;
    }

    // Search inside innerBlocks.
    const firstBlock = findFirstOfSameType(block.innerBlocks, name);
    if (firstBlock) {
      return firstBlock;
    }
  }
}
const enhance = (0,external_wp_compose_namespaceObject.compose)(
/**
 * For blocks whose block type doesn't support `multiple`, provides the
 * wrapped component with `originalBlockClientId` -- a reference to the
 * first block of the same type in the content -- if and only if that
 * "original" block is not the current one. Thus, an inexisting
 * `originalBlockClientId` prop signals that the block is valid.
 *
 * @param {Component} WrappedBlockEdit A filtered BlockEdit instance.
 *
 * @return {Component} Enhanced component with merged state data props.
 */
(0,external_wp_data_namespaceObject.withSelect)((select, block) => {
  const multiple = (0,external_wp_blocks_namespaceObject.hasBlockSupport)(block.name, 'multiple', true);

  // For block types with `multiple` support, there is no "original
  // block" to be found in the content, as the block itself is valid.
  if (multiple) {
    return {};
  }

  // Otherwise, only pass `originalBlockClientId` if it refers to a different
  // block from the current one.
  const blocks = select(external_wp_blockEditor_namespaceObject.store).getBlocks();
  const firstOfSameType = findFirstOfSameType(blocks, block.name);
  const isInvalid = firstOfSameType && firstOfSameType.clientId !== block.clientId;
  return {
    originalBlockClientId: isInvalid && firstOfSameType.clientId
  };
}), (0,external_wp_data_namespaceObject.withDispatch)((dispatch, {
  originalBlockClientId
}) => ({
  selectFirst: () => dispatch(external_wp_blockEditor_namespaceObject.store).selectBlock(originalBlockClientId)
})));
const withMultipleValidation = (0,external_wp_compose_namespaceObject.createHigherOrderComponent)(BlockEdit => {
  return enhance(({
    originalBlockClientId,
    selectFirst,
    ...props
  }) => {
    if (!originalBlockClientId) {
      return (0,external_React_namespaceObject.createElement)(BlockEdit, {
        ...props
      });
    }
    const blockType = (0,external_wp_blocks_namespaceObject.getBlockType)(props.name);
    const outboundType = getOutboundType(props.name);
    return [(0,external_React_namespaceObject.createElement)("div", {
      key: "invalid-preview",
      style: {
        minHeight: '60px'
      }
    }, (0,external_React_namespaceObject.createElement)(BlockEdit, {
      key: "block-edit",
      ...props
    })), (0,external_React_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.Warning, {
      key: "multiple-use-warning",
      actions: [(0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
        key: "find-original",
        variant: "secondary",
        onClick: selectFirst
      }, (0,external_wp_i18n_namespaceObject.__)('Find original')), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
        key: "remove",
        variant: "secondary",
        onClick: () => props.onReplace([])
      }, (0,external_wp_i18n_namespaceObject.__)('Remove')), outboundType && (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
        key: "transform",
        variant: "secondary",
        onClick: () => props.onReplace((0,external_wp_blocks_namespaceObject.createBlock)(outboundType.name, props.attributes))
      }, (0,external_wp_i18n_namespaceObject.__)('Transform into:'), " ", outboundType.title)]
    }, (0,external_React_namespaceObject.createElement)("strong", null, blockType?.title, ": "), (0,external_wp_i18n_namespaceObject.__)('This block can only be used once.'))];
  });
}, 'withMultipleValidation');

/**
 * Given a base block name, returns the default block type to which to offer
 * transforms.
 *
 * @param {string} blockName Base block name.
 *
 * @return {?Object} The chosen default block type.
 */
function getOutboundType(blockName) {
  // Grab the first outbound transform.
  const transform = (0,external_wp_blocks_namespaceObject.findTransform)((0,external_wp_blocks_namespaceObject.getBlockTransforms)('to', blockName), ({
    type,
    blocks
  }) => type === 'block' && blocks.length === 1 // What about when .length > 1?
  );
  if (!transform) {
    return null;
  }
  return (0,external_wp_blocks_namespaceObject.getBlockType)(transform.blocks[0]);
}
(0,external_wp_hooks_namespaceObject.addFilter)('editor.BlockEdit', 'core/edit-post/validate-multiple-use/with-multiple-validation', withMultipleValidation);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/hooks/index.js
/**
 * Internal dependencies
 */



;// CONCATENATED MODULE: external ["wp","coreData"]
const external_wp_coreData_namespaceObject = window["wp"]["coreData"];
;// CONCATENATED MODULE: external ["wp","commands"]
const external_wp_commands_namespaceObject = window["wp"]["commands"];
// EXTERNAL MODULE: ./node_modules/classnames/index.js
var classnames = __webpack_require__(4403);
var classnames_default = /*#__PURE__*/__webpack_require__.n(classnames);
;// CONCATENATED MODULE: external ["wp","plugins"]
const external_wp_plugins_namespaceObject = window["wp"]["plugins"];
;// CONCATENATED MODULE: external ["wp","keyboardShortcuts"]
const external_wp_keyboardShortcuts_namespaceObject = window["wp"]["keyboardShortcuts"];
;// CONCATENATED MODULE: external ["wp","notices"]
const external_wp_notices_namespaceObject = window["wp"]["notices"];
;// CONCATENATED MODULE: external ["wp","coreCommands"]
const external_wp_coreCommands_namespaceObject = window["wp"]["coreCommands"];
;// CONCATENATED MODULE: external ["wp","keycodes"]
const external_wp_keycodes_namespaceObject = window["wp"]["keycodes"];
;// CONCATENATED MODULE: ./packages/edit-post/build-module/store/reducer.js
/**
 * WordPress dependencies
 */


/**
 * Reducer keeping track of the meta boxes isSaving state.
 * A "true" value means the meta boxes saving request is in-flight.
 *
 *
 * @param {boolean} state  Previous state.
 * @param {Object}  action Action Object.
 *
 * @return {Object} Updated state.
 */
function isSavingMetaBoxes(state = false, action) {
  switch (action.type) {
    case 'REQUEST_META_BOX_UPDATES':
      return true;
    case 'META_BOX_UPDATES_SUCCESS':
    case 'META_BOX_UPDATES_FAILURE':
      return false;
    default:
      return state;
  }
}
function mergeMetaboxes(metaboxes = [], newMetaboxes) {
  const mergedMetaboxes = [...metaboxes];
  for (const metabox of newMetaboxes) {
    const existing = mergedMetaboxes.findIndex(box => box.id === metabox.id);
    if (existing !== -1) {
      mergedMetaboxes[existing] = metabox;
    } else {
      mergedMetaboxes.push(metabox);
    }
  }
  return mergedMetaboxes;
}

/**
 * Reducer keeping track of the meta boxes per location.
 *
 * @param {boolean} state  Previous state.
 * @param {Object}  action Action Object.
 *
 * @return {Object} Updated state.
 */
function metaBoxLocations(state = {}, action) {
  switch (action.type) {
    case 'SET_META_BOXES_PER_LOCATIONS':
      {
        const newState = {
          ...state
        };
        for (const [location, metaboxes] of Object.entries(action.metaBoxesPerLocation)) {
          newState[location] = mergeMetaboxes(newState[location], metaboxes);
        }
        return newState;
      }
  }
  return state;
}

/**
 * Reducer tracking whether meta boxes are initialized.
 *
 * @param {boolean} state
 * @param {Object}  action
 *
 * @return {boolean} Updated state.
 */
function metaBoxesInitialized(state = false, action) {
  switch (action.type) {
    case 'META_BOXES_INITIALIZED':
      return true;
  }
  return state;
}
const metaBoxes = (0,external_wp_data_namespaceObject.combineReducers)({
  isSaving: isSavingMetaBoxes,
  locations: metaBoxLocations,
  initialized: metaBoxesInitialized
});
/* harmony default export */ const reducer = ((0,external_wp_data_namespaceObject.combineReducers)({
  metaBoxes
}));

;// CONCATENATED MODULE: external ["wp","apiFetch"]
const external_wp_apiFetch_namespaceObject = window["wp"]["apiFetch"];
var external_wp_apiFetch_default = /*#__PURE__*/__webpack_require__.n(external_wp_apiFetch_namespaceObject);
;// CONCATENATED MODULE: ./packages/edit-post/build-module/utils/meta-boxes.js
/**
 * Function returning the current Meta Boxes DOM Node in the editor
 * whether the meta box area is opened or not.
 * If the MetaBox Area is visible returns it, and returns the original container instead.
 *
 * @param {string} location Meta Box location.
 *
 * @return {string} HTML content.
 */
const getMetaBoxContainer = location => {
  const area = document.querySelector(`.edit-post-meta-boxes-area.is-${location} .metabox-location-${location}`);
  if (area) {
    return area;
  }
  return document.querySelector('#metaboxes .metabox-location-' + location);
};

;// CONCATENATED MODULE: external ["wp","privateApis"]
const external_wp_privateApis_namespaceObject = window["wp"]["privateApis"];
;// CONCATENATED MODULE: ./packages/edit-post/build-module/lock-unlock.js
/**
 * WordPress dependencies
 */

const {
  lock,
  unlock
} = (0,external_wp_privateApis_namespaceObject.__dangerousOptInToUnstableAPIsOnlyForCoreModules)('I know using unstable features means my theme or plugin will inevitably break in the next version of WordPress.', '@wordpress/edit-post');

;// CONCATENATED MODULE: ./packages/edit-post/build-module/store/actions.js
/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */


const {
  interfaceStore
} = unlock(external_wp_editor_namespaceObject.privateApis);

/**
 * Returns an action object used in signalling that the user opened an editor sidebar.
 *
 * @param {?string} name Sidebar name to be opened.
 */
const openGeneralSidebar = name => ({
  registry
}) => {
  registry.dispatch(interfaceStore).enableComplementaryArea('core', name);
};

/**
 * Returns an action object signalling that the user closed the sidebar.
 */
const closeGeneralSidebar = () => ({
  registry
}) => registry.dispatch(interfaceStore).disableComplementaryArea('core');

/**
 * Returns an action object used in signalling that the user opened a modal.
 *
 * @deprecated since WP 6.3 use `core/interface` store's action with the same name instead.
 *
 *
 * @param {string} name A string that uniquely identifies the modal.
 *
 * @return {Object} Action object.
 */
const openModal = name => ({
  registry
}) => {
  external_wp_deprecated_default()("select( 'core/edit-post' ).openModal( name )", {
    since: '6.3',
    alternative: "select( 'core/interface').openModal( name )"
  });
  return registry.dispatch(interfaceStore).openModal(name);
};

/**
 * Returns an action object signalling that the user closed a modal.
 *
 * @deprecated since WP 6.3 use `core/interface` store's action with the same name instead.
 *
 * @return {Object} Action object.
 */
const closeModal = () => ({
  registry
}) => {
  external_wp_deprecated_default()("select( 'core/edit-post' ).closeModal()", {
    since: '6.3',
    alternative: "select( 'core/interface').closeModal()"
  });
  return registry.dispatch(interfaceStore).closeModal();
};

/**
 * Returns an action object used in signalling that the user opened the publish
 * sidebar.
 * @deprecated
 *
 * @return {Object} Action object
 */
const openPublishSidebar = () => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).openPublishSidebar", {
    since: '6.6',
    alternative: "dispatch( 'core/editor').openPublishSidebar"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).openPublishSidebar();
};

/**
 * Returns an action object used in signalling that the user closed the
 * publish sidebar.
 * @deprecated
 *
 * @return {Object} Action object.
 */
const closePublishSidebar = () => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).closePublishSidebar", {
    since: '6.6',
    alternative: "dispatch( 'core/editor').closePublishSidebar"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).closePublishSidebar();
};

/**
 * Returns an action object used in signalling that the user toggles the publish sidebar.
 * @deprecated
 *
 * @return {Object} Action object
 */
const togglePublishSidebar = () => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).togglePublishSidebar", {
    since: '6.6',
    alternative: "dispatch( 'core/editor').togglePublishSidebar"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).togglePublishSidebar();
};

/**
 * Returns an action object used to enable or disable a panel in the editor.
 *
 * @deprecated
 *
 * @param {string} panelName A string that identifies the panel to enable or disable.
 *
 * @return {Object} Action object.
 */
const toggleEditorPanelEnabled = panelName => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).toggleEditorPanelEnabled", {
    since: '6.5',
    alternative: "dispatch( 'core/editor').toggleEditorPanelEnabled"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).toggleEditorPanelEnabled(panelName);
};

/**
 * Opens a closed panel and closes an open panel.
 *
 * @deprecated
 *
 * @param {string} panelName A string that identifies the panel to open or close.
 */
const toggleEditorPanelOpened = panelName => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).toggleEditorPanelOpened", {
    since: '6.5',
    alternative: "dispatch( 'core/editor').toggleEditorPanelOpened"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).toggleEditorPanelOpened(panelName);
};

/**
 * Returns an action object used to remove a panel from the editor.
 *
 * @deprecated
 *
 * @param {string} panelName A string that identifies the panel to remove.
 *
 * @return {Object} Action object.
 */
const removeEditorPanel = panelName => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).removeEditorPanel", {
    since: '6.5',
    alternative: "dispatch( 'core/editor').removeEditorPanel"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).removeEditorPanel(panelName);
};

/**
 * Triggers an action used to toggle a feature flag.
 *
 * @param {string} feature Feature name.
 */
const toggleFeature = feature => ({
  registry
}) => registry.dispatch(external_wp_preferences_namespaceObject.store).toggle('core/edit-post', feature);

/**
 * Triggers an action used to switch editor mode.
 *
 * @deprecated
 *
 * @param {string} mode The editor mode.
 */
const switchEditorMode = mode => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).switchEditorMode", {
    since: '6.6',
    alternative: "dispatch( 'core/editor').switchEditorMode"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).switchEditorMode(mode);
};

/**
 * Triggers an action object used to toggle a plugin name flag.
 *
 * @param {string} pluginName Plugin name.
 */
const togglePinnedPluginItem = pluginName => ({
  registry
}) => {
  const isPinned = registry.select(interfaceStore).isItemPinned('core', pluginName);
  registry.dispatch(interfaceStore)[isPinned ? 'unpinItem' : 'pinItem']('core', pluginName);
};

/**
 * Returns an action object used in signaling that a style should be auto-applied when a block is created.
 *
 * @deprecated
 */
function updatePreferredStyleVariations() {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).updatePreferredStyleVariations", {
    since: '6.6',
    hint: 'Preferred Style Variations are not supported anymore.'
  });
  return {
    type: 'NOTHING'
  };
}

/**
 * Update the provided block types to be visible.
 *
 * @param {string[]} blockNames Names of block types to show.
 */
const showBlockTypes = blockNames => ({
  registry
}) => {
  unlock(registry.dispatch(external_wp_editor_namespaceObject.store)).showBlockTypes(blockNames);
};

/**
 * Update the provided block types to be hidden.
 *
 * @param {string[]} blockNames Names of block types to hide.
 */
const hideBlockTypes = blockNames => ({
  registry
}) => {
  unlock(registry.dispatch(external_wp_editor_namespaceObject.store)).hideBlockTypes(blockNames);
};

/**
 * Stores info about which Meta boxes are available in which location.
 *
 * @param {Object} metaBoxesPerLocation Meta boxes per location.
 */
function setAvailableMetaBoxesPerLocation(metaBoxesPerLocation) {
  return {
    type: 'SET_META_BOXES_PER_LOCATIONS',
    metaBoxesPerLocation
  };
}

/**
 * Update a metabox.
 */
const requestMetaBoxUpdates = () => async ({
  registry,
  select,
  dispatch
}) => {
  dispatch({
    type: 'REQUEST_META_BOX_UPDATES'
  });

  // Saves the wp_editor fields.
  if (window.tinyMCE) {
    window.tinyMCE.triggerSave();
  }

  // Additional data needed for backward compatibility.
  // If we do not provide this data, the post will be overridden with the default values.
  const post = registry.select(external_wp_editor_namespaceObject.store).getCurrentPost();
  const additionalData = [post.comment_status ? ['comment_status', post.comment_status] : false, post.ping_status ? ['ping_status', post.ping_status] : false, post.sticky ? ['sticky', post.sticky] : false, post.author ? ['post_author', post.author] : false].filter(Boolean);

  // We gather all the metaboxes locations data and the base form data.
  const baseFormData = new window.FormData(document.querySelector('.metabox-base-form'));
  const activeMetaBoxLocations = select.getActiveMetaBoxLocations();
  const formDataToMerge = [baseFormData, ...activeMetaBoxLocations.map(location => new window.FormData(getMetaBoxContainer(location)))];

  // Merge all form data objects into a single one.
  const formData = formDataToMerge.reduce((memo, currentFormData) => {
    for (const [key, value] of currentFormData) {
      memo.append(key, value);
    }
    return memo;
  }, new window.FormData());
  additionalData.forEach(([key, value]) => formData.append(key, value));
  try {
    // Save the metaboxes.
    await external_wp_apiFetch_default()({
      url: window._wpMetaBoxUrl,
      method: 'POST',
      body: formData,
      parse: false
    });
    dispatch.metaBoxUpdatesSuccess();
  } catch {
    dispatch.metaBoxUpdatesFailure();
  }
};

/**
 * Returns an action object used to signal a successful meta box update.
 *
 * @return {Object} Action object.
 */
function metaBoxUpdatesSuccess() {
  return {
    type: 'META_BOX_UPDATES_SUCCESS'
  };
}

/**
 * Returns an action object used to signal a failed meta box update.
 *
 * @return {Object} Action object.
 */
function metaBoxUpdatesFailure() {
  return {
    type: 'META_BOX_UPDATES_FAILURE'
  };
}

/**
 * Action that changes the width of the editing canvas.
 *
 * @deprecated
 *
 * @param {string} deviceType
 */
const __experimentalSetPreviewDeviceType = deviceType => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).__experimentalSetPreviewDeviceType", {
    since: '6.5',
    version: '6.7',
    hint: 'registry.dispatch( editorStore ).setDeviceType'
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).setDeviceType(deviceType);
};

/**
 * Returns an action object used to open/close the inserter.
 *
 * @deprecated
 *
 * @param {boolean|Object} value Whether the inserter should be opened (true) or closed (false).
 */
const setIsInserterOpened = value => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).setIsInserterOpened", {
    since: '6.5',
    alternative: "dispatch( 'core/editor').setIsInserterOpened"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).setIsInserterOpened(value);
};

/**
 * Returns an action object used to open/close the list view.
 *
 * @deprecated
 *
 * @param {boolean} isOpen A boolean representing whether the list view should be opened or closed.
 */
const setIsListViewOpened = isOpen => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).setIsListViewOpened", {
    since: '6.5',
    alternative: "dispatch( 'core/editor').setIsListViewOpened"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).setIsListViewOpened(isOpen);
};

/**
 * Returns an action object used to switch to template editing.
 *
 * @deprecated
 */
function setIsEditingTemplate() {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).setIsEditingTemplate", {
    since: '6.5',
    alternative: "dispatch( 'core/editor').setRenderingMode"
  });
  return {
    type: 'NOTHING'
  };
}

/**
 * Create a block based template.
 *
 * @deprecated
 */
function __unstableCreateTemplate() {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).__unstableCreateTemplate", {
    since: '6.5'
  });
  return {
    type: 'NOTHING'
  };
}
let actions_metaBoxesInitialized = false;

/**
 * Initializes WordPress `postboxes` script and the logic for saving meta boxes.
 */
const initializeMetaBoxes = () => ({
  registry,
  select,
  dispatch
}) => {
  const isEditorReady = registry.select(external_wp_editor_namespaceObject.store).__unstableIsEditorReady();
  if (!isEditorReady) {
    return;
  }
  // Only initialize once.
  if (actions_metaBoxesInitialized) {
    return;
  }
  const postType = registry.select(external_wp_editor_namespaceObject.store).getCurrentPostType();
  if (window.postboxes.page !== postType) {
    window.postboxes.add_postbox_toggles(postType);
  }
  actions_metaBoxesInitialized = true;

  // Save metaboxes on save completion, except for autosaves.
  (0,external_wp_hooks_namespaceObject.addFilter)('editor.__unstableSavePost', 'core/edit-post/save-metaboxes', (previous, options) => previous.then(() => {
    if (options.isAutosave) {
      return;
    }
    if (!select.hasMetaBoxes()) {
      return;
    }
    return dispatch.requestMetaBoxUpdates();
  }));
  dispatch({
    type: 'META_BOXES_INITIALIZED'
  });
};

/**
 * Action that toggles Distraction free mode.
 * Distraction free mode expects there are no sidebars, as due to the
 * z-index values set, you can't close sidebars.
 *
 * @deprecated
 */
const toggleDistractionFree = () => ({
  registry
}) => {
  external_wp_deprecated_default()("dispatch( 'core/edit-post' ).toggleDistractionFree", {
    since: '6.6',
    alternative: "dispatch( 'core/editor').toggleDistractionFree"
  });
  registry.dispatch(external_wp_editor_namespaceObject.store).toggleDistractionFree();
};

;// CONCATENATED MODULE: ./packages/edit-post/build-module/store/selectors.js
/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */

const {
  interfaceStore: selectors_interfaceStore
} = unlock(external_wp_editor_namespaceObject.privateApis);
const EMPTY_ARRAY = [];
const EMPTY_OBJECT = {};

/**
 * Returns the current editing mode.
 *
 * @param {Object} state Global application state.
 *
 * @return {string} Editing mode.
 */
const getEditorMode = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  var _select$get;
  return (_select$get = select(external_wp_preferences_namespaceObject.store).get('core', 'editorMode')) !== null && _select$get !== void 0 ? _select$get : 'visual';
});

/**
 * Returns true if the editor sidebar is opened.
 *
 * @param {Object} state Global application state
 *
 * @return {boolean} Whether the editor sidebar is opened.
 */
const isEditorSidebarOpened = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  const activeGeneralSidebar = select(selectors_interfaceStore).getActiveComplementaryArea('core');
  return ['edit-post/document', 'edit-post/block'].includes(activeGeneralSidebar);
});

/**
 * Returns true if the plugin sidebar is opened.
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} Whether the plugin sidebar is opened.
 */
const isPluginSidebarOpened = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  const activeGeneralSidebar = select(selectors_interfaceStore).getActiveComplementaryArea('core');
  return !!activeGeneralSidebar && !['edit-post/document', 'edit-post/block'].includes(activeGeneralSidebar);
});

/**
 * Returns the current active general sidebar name, or null if there is no
 * general sidebar active. The active general sidebar is a unique name to
 * identify either an editor or plugin sidebar.
 *
 * Examples:
 *
 *  - `edit-post/document`
 *  - `my-plugin/insert-image-sidebar`
 *
 * @param {Object} state Global application state.
 *
 * @return {?string} Active general sidebar name.
 */
const getActiveGeneralSidebarName = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  return select(selectors_interfaceStore).getActiveComplementaryArea('core');
});

/**
 * Converts panels from the new preferences store format to the old format
 * that the post editor previously used.
 *
 * The resultant converted data should look like this:
 * {
 *     panelName: {
 *         enabled: false,
 *         opened: true,
 *     },
 *     anotherPanelName: {
 *         opened: true
 *     },
 * }
 *
 * @param {string[] | undefined} inactivePanels An array of inactive panel names.
 * @param {string[] | undefined} openPanels     An array of open panel names.
 *
 * @return {Object} The converted panel data.
 */
function convertPanelsToOldFormat(inactivePanels, openPanels) {
  var _ref;
  // First reduce the inactive panels.
  const panelsWithEnabledState = inactivePanels?.reduce((accumulatedPanels, panelName) => ({
    ...accumulatedPanels,
    [panelName]: {
      enabled: false
    }
  }), {});

  // Then reduce the open panels, passing in the result of the previous
  // reduction as the initial value so that both open and inactive
  // panel state is combined.
  const panels = openPanels?.reduce((accumulatedPanels, panelName) => {
    const currentPanelState = accumulatedPanels?.[panelName];
    return {
      ...accumulatedPanels,
      [panelName]: {
        ...currentPanelState,
        opened: true
      }
    };
  }, panelsWithEnabledState !== null && panelsWithEnabledState !== void 0 ? panelsWithEnabledState : {});

  // The panels variable will only be set if openPanels wasn't `undefined`.
  // If it isn't set just return `panelsWithEnabledState`, and if that isn't
  // set return an empty object.
  return (_ref = panels !== null && panels !== void 0 ? panels : panelsWithEnabledState) !== null && _ref !== void 0 ? _ref : EMPTY_OBJECT;
}

/**
 * Returns the preferences (these preferences are persisted locally).
 *
 * @param {Object} state Global application state.
 *
 * @return {Object} Preferences Object.
 */
const getPreferences = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).getPreferences`, {
    since: '6.0',
    alternative: `select( 'core/preferences' ).get`
  });
  const corePreferences = ['editorMode', 'hiddenBlockTypes'].reduce((accumulatedPrefs, preferenceKey) => {
    const value = select(external_wp_preferences_namespaceObject.store).get('core', preferenceKey);
    return {
      ...accumulatedPrefs,
      [preferenceKey]: value
    };
  }, {});

  // Panels were a preference, but the data structure changed when the state
  // was migrated to the preferences store. They need to be converted from
  // the new preferences store format to old format to ensure no breaking
  // changes for plugins.
  const inactivePanels = select(external_wp_preferences_namespaceObject.store).get('core', 'inactivePanels');
  const openPanels = select(external_wp_preferences_namespaceObject.store).get('core', 'openPanels');
  const panels = convertPanelsToOldFormat(inactivePanels, openPanels);
  return {
    ...corePreferences,
    panels
  };
});

/**
 *
 * @param {Object} state         Global application state.
 * @param {string} preferenceKey Preference Key.
 * @param {*}      defaultValue  Default Value.
 *
 * @return {*} Preference Value.
 */
function getPreference(state, preferenceKey, defaultValue) {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).getPreference`, {
    since: '6.0',
    alternative: `select( 'core/preferences' ).get`
  });

  // Avoid using the `getPreferences` registry selector where possible.
  const preferences = getPreferences(state);
  const value = preferences[preferenceKey];
  return value === undefined ? defaultValue : value;
}

/**
 * Returns an array of blocks that are hidden.
 *
 * @return {Array} A list of the hidden block types
 */
const getHiddenBlockTypes = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  var _select$get2;
  return (_select$get2 = select(external_wp_preferences_namespaceObject.store).get('core', 'hiddenBlockTypes')) !== null && _select$get2 !== void 0 ? _select$get2 : EMPTY_ARRAY;
});

/**
 * Returns true if the publish sidebar is opened.
 *
 * @deprecated
 *
 * @param {Object} state Global application state
 *
 * @return {boolean} Whether the publish sidebar is open.
 */
const isPublishSidebarOpened = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).isPublishSidebarOpened`, {
    since: '6.6',
    alternative: `select( 'core/editor' ).isPublishSidebarOpened`
  });
  return select(external_wp_editor_namespaceObject.store).isPublishSidebarOpened();
});

/**
 * Returns true if the given panel was programmatically removed, or false otherwise.
 * All panels are not removed by default.
 *
 * @deprecated
 *
 * @param {Object} state     Global application state.
 * @param {string} panelName A string that identifies the panel.
 *
 * @return {boolean} Whether or not the panel is removed.
 */
const isEditorPanelRemoved = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => (state, panelName) => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).isEditorPanelRemoved`, {
    since: '6.5',
    alternative: `select( 'core/editor' ).isEditorPanelRemoved`
  });
  return select(external_wp_editor_namespaceObject.store).isEditorPanelRemoved(panelName);
});

/**
 * Returns true if the given panel is enabled, or false otherwise. Panels are
 * enabled by default.
 *
 * @deprecated
 *
 * @param {Object} state     Global application state.
 * @param {string} panelName A string that identifies the panel.
 *
 * @return {boolean} Whether or not the panel is enabled.
 */
const isEditorPanelEnabled = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => (state, panelName) => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).isEditorPanelEnabled`, {
    since: '6.5',
    alternative: `select( 'core/editor' ).isEditorPanelEnabled`
  });
  return select(external_wp_editor_namespaceObject.store).isEditorPanelEnabled(panelName);
});

/**
 * Returns true if the given panel is open, or false otherwise. Panels are
 * closed by default.
 *
 * @deprecated
 *
 * @param {Object} state     Global application state.
 * @param {string} panelName A string that identifies the panel.
 *
 * @return {boolean} Whether or not the panel is open.
 */
const isEditorPanelOpened = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => (state, panelName) => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).isEditorPanelOpened`, {
    since: '6.5',
    alternative: `select( 'core/editor' ).isEditorPanelOpened`
  });
  return select(external_wp_editor_namespaceObject.store).isEditorPanelOpened(panelName);
});

/**
 * Returns true if a modal is active, or false otherwise.
 *
 * @deprecated since WP 6.3 use `core/interface` store's selector with the same name instead.
 *
 * @param {Object} state     Global application state.
 * @param {string} modalName A string that uniquely identifies the modal.
 *
 * @return {boolean} Whether the modal is active.
 */
const isModalActive = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => (state, modalName) => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).isModalActive`, {
    since: '6.3',
    alternative: `select( 'core/interface' ).isModalActive`
  });
  return !!select(selectors_interfaceStore).isModalActive(modalName);
});

/**
 * Returns whether the given feature is enabled or not.
 *
 * @param {Object} state   Global application state.
 * @param {string} feature Feature slug.
 *
 * @return {boolean} Is active.
 */
const isFeatureActive = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => (state, feature) => {
  return !!select(external_wp_preferences_namespaceObject.store).get('core/edit-post', feature);
});

/**
 * Returns true if the plugin item is pinned to the header.
 * When the value is not set it defaults to true.
 *
 * @param {Object} state      Global application state.
 * @param {string} pluginName Plugin item name.
 *
 * @return {boolean} Whether the plugin item is pinned.
 */
const isPluginItemPinned = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => (state, pluginName) => {
  return select(selectors_interfaceStore).isItemPinned('core', pluginName);
});

/**
 * Returns an array of active meta box locations.
 *
 * @param {Object} state Post editor state.
 *
 * @return {string[]} Active meta box locations.
 */
const getActiveMetaBoxLocations = (0,external_wp_data_namespaceObject.createSelector)(state => {
  return Object.keys(state.metaBoxes.locations).filter(location => isMetaBoxLocationActive(state, location));
}, state => [state.metaBoxes.locations]);

/**
 * Returns true if a metabox location is active and visible
 *
 * @param {Object} state    Post editor state.
 * @param {string} location Meta box location to test.
 *
 * @return {boolean} Whether the meta box location is active and visible.
 */
const isMetaBoxLocationVisible = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => (state, location) => {
  return isMetaBoxLocationActive(state, location) && getMetaBoxesPerLocation(state, location)?.some(({
    id
  }) => {
    return select(external_wp_editor_namespaceObject.store).isEditorPanelEnabled(state, `meta-box-${id}`);
  });
});

/**
 * Returns true if there is an active meta box in the given location, or false
 * otherwise.
 *
 * @param {Object} state    Post editor state.
 * @param {string} location Meta box location to test.
 *
 * @return {boolean} Whether the meta box location is active.
 */
function isMetaBoxLocationActive(state, location) {
  const metaBoxes = getMetaBoxesPerLocation(state, location);
  return !!metaBoxes && metaBoxes.length !== 0;
}

/**
 * Returns the list of all the available meta boxes for a given location.
 *
 * @param {Object} state    Global application state.
 * @param {string} location Meta box location to test.
 *
 * @return {?Array} List of meta boxes.
 */
function getMetaBoxesPerLocation(state, location) {
  return state.metaBoxes.locations[location];
}

/**
 * Returns the list of all the available meta boxes.
 *
 * @param {Object} state Global application state.
 *
 * @return {Array} List of meta boxes.
 */
const getAllMetaBoxes = (0,external_wp_data_namespaceObject.createSelector)(state => {
  return Object.values(state.metaBoxes.locations).flat();
}, state => [state.metaBoxes.locations]);

/**
 * Returns true if the post is using Meta Boxes
 *
 * @param {Object} state Global application state
 *
 * @return {boolean} Whether there are metaboxes or not.
 */
function hasMetaBoxes(state) {
  return getActiveMetaBoxLocations(state).length > 0;
}

/**
 * Returns true if the Meta Boxes are being saved.
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} Whether the metaboxes are being saved.
 */
function selectors_isSavingMetaBoxes(state) {
  return state.metaBoxes.isSaving;
}

/**
 * Returns the current editing canvas device type.
 *
 * @deprecated
 *
 * @param {Object} state Global application state.
 *
 * @return {string} Device type.
 */
const __experimentalGetPreviewDeviceType = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  external_wp_deprecated_default()(`select( 'core/edit-site' ).__experimentalGetPreviewDeviceType`, {
    since: '6.5',
    version: '6.7',
    alternative: `select( 'core/editor' ).getDeviceType`
  });
  return select(external_wp_editor_namespaceObject.store).getDeviceType();
});

/**
 * Returns true if the inserter is opened.
 *
 * @deprecated
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} Whether the inserter is opened.
 */
const isInserterOpened = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).isInserterOpened`, {
    since: '6.5',
    alternative: `select( 'core/editor' ).isInserterOpened`
  });
  return select(external_wp_editor_namespaceObject.store).isInserterOpened();
});

/**
 * Get the insertion point for the inserter.
 *
 * @deprecated
 *
 * @param {Object} state Global application state.
 *
 * @return {Object} The root client ID, index to insert at and starting filter value.
 */
const __experimentalGetInsertionPoint = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).__experimentalGetInsertionPoint`, {
    since: '6.5',
    version: '6.7'
  });
  return unlock(select(external_wp_editor_namespaceObject.store)).getInsertionPoint();
});

/**
 * Returns true if the list view is opened.
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} Whether the list view is opened.
 */
const isListViewOpened = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).isListViewOpened`, {
    since: '6.5',
    alternative: `select( 'core/editor' ).isListViewOpened`
  });
  return select(external_wp_editor_namespaceObject.store).isListViewOpened();
});

/**
 * Returns true if the template editing mode is enabled.
 *
 * @deprecated
 */
const isEditingTemplate = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  external_wp_deprecated_default()(`select( 'core/edit-post' ).isEditingTemplate`, {
    since: '6.5',
    alternative: `select( 'core/editor' ).getRenderingMode`
  });
  return select(external_wp_editor_namespaceObject.store).getCurrentPostType() === 'wp_template';
});

/**
 * Returns true if meta boxes are initialized.
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} Whether meta boxes are initialized.
 */
function areMetaBoxesInitialized(state) {
  return state.metaBoxes.initialized;
}

/**
 * Retrieves the template of the currently edited post.
 *
 * @return {Object?} Post Template.
 */
const getEditedPostTemplate = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => {
  const {
    id: postId,
    type: postType,
    slug
  } = select(external_wp_editor_namespaceObject.store).getCurrentPost();
  const {
    getSite,
    getEditedEntityRecord,
    getEntityRecords
  } = select(external_wp_coreData_namespaceObject.store);
  const siteSettings = getSite();
  // First check if the current page is set as the posts page.
  const isPostsPage = +postId === siteSettings?.page_for_posts;
  if (isPostsPage) {
    const defaultTemplateId = select(external_wp_coreData_namespaceObject.store).getDefaultTemplateId({
      slug: 'home'
    });
    return getEditedEntityRecord('postType', 'wp_template', defaultTemplateId);
  }
  const currentTemplate = select(external_wp_editor_namespaceObject.store).getEditedPostAttribute('template');
  if (currentTemplate) {
    const templateWithSameSlug = getEntityRecords('postType', 'wp_template', {
      per_page: -1
    })?.find(template => template.slug === currentTemplate);
    if (!templateWithSameSlug) {
      return templateWithSameSlug;
    }
    return getEditedEntityRecord('postType', 'wp_template', templateWithSameSlug.id);
  }
  let slugToCheck;
  // In `draft` status we might not have a slug available, so we use the `single`
  // post type templates slug(ex page, single-post, single-product etc..).
  // Pages do not need the `single` prefix in the slug to be prioritized
  // through template hierarchy.
  if (slug) {
    slugToCheck = postType === 'page' ? `${postType}-${slug}` : `single-${postType}-${slug}`;
  } else {
    slugToCheck = postType === 'page' ? 'page' : `single-${postType}`;
  }
  const defaultTemplateId = select(external_wp_coreData_namespaceObject.store).getDefaultTemplateId({
    slug: slugToCheck
  });
  return select(external_wp_coreData_namespaceObject.store).getEditedEntityRecord('postType', 'wp_template', defaultTemplateId);
});

;// CONCATENATED MODULE: ./packages/edit-post/build-module/store/constants.js
/**
 * The identifier for the data store.
 *
 * @type {string}
 */
const STORE_NAME = 'core/edit-post';

/**
 * CSS selector string for the admin bar view post link anchor tag.
 *
 * @type {string}
 */
const VIEW_AS_LINK_SELECTOR = '#wp-admin-bar-view a';

/**
 * CSS selector string for the admin bar preview post link anchor tag.
 *
 * @type {string}
 */
const VIEW_AS_PREVIEW_LINK_SELECTOR = '#wp-admin-bar-preview a';

;// CONCATENATED MODULE: ./packages/edit-post/build-module/store/index.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */





/**
 * Store definition for the edit post namespace.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 *
 * @type {Object}
 */
const store = (0,external_wp_data_namespaceObject.createReduxStore)(STORE_NAME, {
  reducer: reducer,
  actions: actions_namespaceObject,
  selectors: selectors_namespaceObject
});
(0,external_wp_data_namespaceObject.register)(store);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/text-editor/index.js

/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */

function TextEditor() {
  const isRichEditingEnabled = (0,external_wp_data_namespaceObject.useSelect)(select => {
    return select(external_wp_editor_namespaceObject.store).getEditorSettings().richEditingEnabled;
  }, []);
  const {
    switchEditorMode
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_editor_namespaceObject.store);
  const {
    isWelcomeGuideVisible
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      isFeatureActive
    } = select(store);
    return {
      isWelcomeGuideVisible: isFeatureActive('welcomeGuide')
    };
  }, []);
  const titleRef = (0,external_wp_element_namespaceObject.useRef)();
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (isWelcomeGuideVisible) {
      return;
    }
    titleRef?.current?.focus();
  }, [isWelcomeGuideVisible]);
  return (0,external_React_namespaceObject.createElement)("div", {
    className: "edit-post-text-editor"
  }, isRichEditingEnabled && (0,external_React_namespaceObject.createElement)("div", {
    className: "edit-post-text-editor__toolbar"
  }, (0,external_React_namespaceObject.createElement)("h2", null, (0,external_wp_i18n_namespaceObject.__)('Editing code')), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "tertiary",
    onClick: () => switchEditorMode('visual'),
    shortcut: external_wp_keycodes_namespaceObject.displayShortcut.secondary('m')
  }, (0,external_wp_i18n_namespaceObject.__)('Exit code editor'))), (0,external_React_namespaceObject.createElement)("div", {
    className: "edit-post-text-editor__body"
  }, (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostTitleRaw, {
    ref: titleRef
  }), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostTextEditor, null)));
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/visual-editor/use-padding-appender.js
/**
 * WordPress dependencies
 */




function usePaddingAppender() {
  const registry = (0,external_wp_data_namespaceObject.useRegistry)();
  return (0,external_wp_compose_namespaceObject.useRefEffect)(node => {
    function onMouseDown(event) {
      if (event.target !== node) {
        return;
      }
      const {
        ownerDocument
      } = node;
      const {
        defaultView
      } = ownerDocument;
      const paddingBottom = defaultView.parseInt(defaultView.getComputedStyle(node).paddingBottom, 10);
      if (!paddingBottom) {
        return;
      }

      // only handle clicks under the last child
      const lastChild = node.lastElementChild;
      if (!lastChild) {
        return;
      }
      const lastChildRect = lastChild.getBoundingClientRect();
      if (event.clientY < lastChildRect.bottom) {
        return;
      }
      event.preventDefault();
      const blockOrder = registry.select(external_wp_blockEditor_namespaceObject.store).getBlockOrder('');
      const lastBlockClientId = blockOrder[blockOrder.length - 1];
      const lastBlock = registry.select(external_wp_blockEditor_namespaceObject.store).getBlock(lastBlockClientId);
      const {
        selectBlock,
        insertDefaultBlock
      } = registry.dispatch(external_wp_blockEditor_namespaceObject.store);
      if ((0,external_wp_blocks_namespaceObject.isUnmodifiedDefaultBlock)(lastBlock)) {
        selectBlock(lastBlockClientId);
      } else {
        insertDefaultBlock();
      }
    }
    node.addEventListener('mousedown', onMouseDown);
    return () => {
      node.removeEventListener('mousedown', onMouseDown);
    };
  }, [registry]);
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/visual-editor/index.js

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */



const {
  EditorCanvas
} = unlock(external_wp_editor_namespaceObject.privateApis);
const isGutenbergPlugin =  true ? true : 0;
function VisualEditor({
  styles
}) {
  const {
    isWelcomeGuideVisible,
    renderingMode,
    isBlockBasedTheme,
    hasV3BlocksOnly,
    isEditingTemplate
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      isFeatureActive
    } = select(store);
    const {
      getEditorSettings,
      getRenderingMode
    } = select(external_wp_editor_namespaceObject.store);
    const {
      getBlockTypes
    } = select(external_wp_blocks_namespaceObject.store);
    const editorSettings = getEditorSettings();
    return {
      isWelcomeGuideVisible: isFeatureActive('welcomeGuide'),
      renderingMode: getRenderingMode(),
      isBlockBasedTheme: editorSettings.__unstableIsBlockBasedTheme,
      hasV3BlocksOnly: getBlockTypes().every(type => {
        return type.apiVersion >= 3;
      }),
      isEditingTemplate: select(external_wp_editor_namespaceObject.store).getCurrentPostType() === 'wp_template'
    };
  }, []);
  const hasMetaBoxes = (0,external_wp_data_namespaceObject.useSelect)(select => select(store).hasMetaBoxes(), []);
  const paddingAppenderRef = usePaddingAppender();
  let paddingBottom;

  // Add a constant padding for the typewritter effect. When typing at the
  // bottom, there needs to be room to scroll up.
  if (!hasMetaBoxes && renderingMode === 'post-only') {
    paddingBottom = '40vh';
  }
  styles = (0,external_wp_element_namespaceObject.useMemo)(() => [...styles, {
    // We should move this in to future to the body.
    css: paddingBottom ? `body{padding-bottom:${paddingBottom}}` : ''
  }], [styles, paddingBottom]);
  const isToBeIframed = (hasV3BlocksOnly || isGutenbergPlugin && isBlockBasedTheme) && !hasMetaBoxes || isEditingTemplate;
  return (0,external_React_namespaceObject.createElement)("div", {
    className: classnames_default()('edit-post-visual-editor', {
      'has-inline-canvas': !isToBeIframed
    })
  }, (0,external_React_namespaceObject.createElement)(EditorCanvas, {
    disableIframe: !isToBeIframed,
    styles: styles
    // We should auto-focus the canvas (title) on load.
    // eslint-disable-next-line jsx-a11y/no-autofocus
    ,
    autoFocus: !isWelcomeGuideVisible,
    contentRef: paddingAppenderRef
  }));
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/keyboard-shortcuts/index.js
/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */

function KeyboardShortcuts() {
  const {
    toggleFeature
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  const {
    registerShortcut
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_keyboardShortcuts_namespaceObject.store);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    registerShortcut({
      name: 'core/edit-post/toggle-fullscreen',
      category: 'global',
      description: (0,external_wp_i18n_namespaceObject.__)('Toggle fullscreen mode.'),
      keyCombination: {
        modifier: 'secondary',
        character: 'f'
      }
    });
  }, []);
  (0,external_wp_keyboardShortcuts_namespaceObject.useShortcut)('core/edit-post/toggle-fullscreen', () => {
    toggleFeature('fullscreenMode');
  });
  return null;
}
/* harmony default export */ const keyboard_shortcuts = (KeyboardShortcuts);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/init-pattern-modal/index.js

/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */


const {
  ReusableBlocksRenameHint
} = unlock(external_wp_blockEditor_namespaceObject.privateApis);
function InitPatternModal() {
  const {
    editPost
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_editor_namespaceObject.store);
  const [isModalOpen, setIsModalOpen] = (0,external_wp_element_namespaceObject.useState)(false);
  const [syncType, setSyncType] = (0,external_wp_element_namespaceObject.useState)(undefined);
  const [title, setTitle] = (0,external_wp_element_namespaceObject.useState)('');
  const {
    postType,
    isNewPost
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEditedPostAttribute,
      isCleanNewPost
    } = select(external_wp_editor_namespaceObject.store);
    return {
      postType: getEditedPostAttribute('type'),
      isNewPost: isCleanNewPost()
    };
  }, []);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (isNewPost && postType === 'wp_block') {
      setIsModalOpen(true);
    }
    // We only want the modal to open when the page is first loaded.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);
  if (postType !== 'wp_block' || !isNewPost) {
    return null;
  }
  return (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, isModalOpen && (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Modal, {
    title: (0,external_wp_i18n_namespaceObject.__)('Create pattern'),
    onRequestClose: () => {
      setIsModalOpen(false);
    },
    overlayClassName: "reusable-blocks-menu-items__convert-modal"
  }, (0,external_React_namespaceObject.createElement)("form", {
    onSubmit: event => {
      event.preventDefault();
      setIsModalOpen(false);
      editPost({
        title,
        meta: {
          wp_pattern_sync_status: syncType
        }
      });
    }
  }, (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: "5"
  }, (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.TextControl, {
    label: (0,external_wp_i18n_namespaceObject.__)('Name'),
    value: title,
    onChange: setTitle,
    placeholder: (0,external_wp_i18n_namespaceObject.__)('My pattern'),
    className: "patterns-create-modal__name-input",
    __nextHasNoMarginBottom: true,
    __next40pxDefaultSize: true
  }), (0,external_React_namespaceObject.createElement)(ReusableBlocksRenameHint, null), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.ToggleControl, {
    label: (0,external_wp_i18n_namespaceObject._x)('Synced', 'Option that makes an individual pattern synchronized'),
    help: (0,external_wp_i18n_namespaceObject.__)('Sync this pattern across multiple locations.'),
    checked: !syncType,
    onChange: () => {
      setSyncType(!syncType ? 'unsynced' : undefined);
    }
  }), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, {
    justify: "right"
  }, (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "primary",
    type: "submit",
    disabled: !title,
    __experimentalIsFocusable: true
  }, (0,external_wp_i18n_namespaceObject.__)('Create')))))));
}

;// CONCATENATED MODULE: external ["wp","url"]
const external_wp_url_namespaceObject = window["wp"]["url"];
;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/browser-url/index.js
/**
 * WordPress dependencies
 */





/**
 * Returns the Post's Edit URL.
 *
 * @param {number} postId Post ID.
 *
 * @return {string} Post edit URL.
 */
function getPostEditURL(postId) {
  return (0,external_wp_url_namespaceObject.addQueryArgs)('post.php', {
    post: postId,
    action: 'edit'
  });
}

/**
 * Returns the Post's Trashed URL.
 *
 * @param {number} postId   Post ID.
 * @param {string} postType Post Type.
 *
 * @return {string} Post trashed URL.
 */
function getPostTrashedURL(postId, postType) {
  return (0,external_wp_url_namespaceObject.addQueryArgs)('edit.php', {
    trashed: 1,
    post_type: postType,
    ids: postId
  });
}
class BrowserURL extends external_wp_element_namespaceObject.Component {
  constructor() {
    super(...arguments);
    this.state = {
      historyId: null
    };
  }
  componentDidUpdate(prevProps) {
    const {
      postId,
      postStatus,
      postType,
      isSavingPost,
      hasHistory
    } = this.props;
    const {
      historyId
    } = this.state;

    // Posts are still dirty while saving so wait for saving to finish
    // to avoid the unsaved changes warning when trashing posts.
    if (postStatus === 'trash' && !isSavingPost) {
      this.setTrashURL(postId, postType);
      return;
    }
    if ((postId !== prevProps.postId || postId !== historyId) && postStatus !== 'auto-draft' && postId && !hasHistory) {
      this.setBrowserURL(postId);
    }
  }

  /**
   * Navigates the browser to the post trashed URL to show a notice about the trashed post.
   *
   * @param {number} postId   Post ID.
   * @param {string} postType Post Type.
   */
  setTrashURL(postId, postType) {
    window.location.href = getPostTrashedURL(postId, postType);
  }

  /**
   * Replaces the browser URL with a post editor link for the given post ID.
   *
   * Note it is important that, since this function may be called when the
   * editor first loads, the result generated `getPostEditURL` matches that
   * produced by the server. Otherwise, the URL will change unexpectedly.
   *
   * @param {number} postId Post ID for which to generate post editor URL.
   */
  setBrowserURL(postId) {
    window.history.replaceState({
      id: postId
    }, 'Post ' + postId, getPostEditURL(postId));
    this.setState(() => ({
      historyId: postId
    }));
  }
  render() {
    return null;
  }
}
/* harmony default export */ const browser_url = ((0,external_wp_data_namespaceObject.withSelect)(select => {
  const {
    getCurrentPost,
    isSavingPost
  } = select(external_wp_editor_namespaceObject.store);
  const post = getCurrentPost();
  let {
    id,
    status,
    type
  } = post;
  const isTemplate = ['wp_template', 'wp_template_part'].includes(type);
  if (isTemplate) {
    id = post.wp_id;
  }
  return {
    postId: id,
    postStatus: status,
    postType: type,
    isSavingPost: isSavingPost()
  };
})(BrowserURL));

;// CONCATENATED MODULE: external ["wp","primitives"]
const external_wp_primitives_namespaceObject = window["wp"]["primitives"];
;// CONCATENATED MODULE: ./packages/icons/build-module/library/wordpress.js

/**
 * WordPress dependencies
 */

const wordpress = (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "-2 -2 24 24"
}, (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M20 10c0-5.51-4.49-10-10-10C4.48 0 0 4.49 0 10c0 5.52 4.48 10 10 10 5.51 0 10-4.48 10-10zM7.78 15.37L4.37 6.22c.55-.02 1.17-.08 1.17-.08.5-.06.44-1.13-.06-1.11 0 0-1.45.11-2.37.11-.18 0-.37 0-.58-.01C4.12 2.69 6.87 1.11 10 1.11c2.33 0 4.45.87 6.05 2.34-.68-.11-1.65.39-1.65 1.58 0 .74.45 1.36.9 2.1.35.61.55 1.36.55 2.46 0 1.49-1.4 5-1.4 5l-3.03-8.37c.54-.02.82-.17.82-.17.5-.05.44-1.25-.06-1.22 0 0-1.44.12-2.38.12-.87 0-2.33-.12-2.33-.12-.5-.03-.56 1.2-.06 1.22l.92.08 1.26 3.41zM17.41 10c.24-.64.74-1.87.43-4.25.7 1.29 1.05 2.71 1.05 4.25 0 3.29-1.73 6.24-4.4 7.78.97-2.59 1.94-5.2 2.92-7.78zM6.1 18.09C3.12 16.65 1.11 13.53 1.11 10c0-1.3.23-2.48.72-3.59C3.25 10.3 4.67 14.2 6.1 18.09zm4.03-6.63l2.58 6.98c-.86.29-1.76.45-2.71.45-.79 0-1.57-.11-2.29-.33.81-2.38 1.62-4.74 2.42-7.1z"
}));
/* harmony default export */ const library_wordpress = (wordpress);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/header/fullscreen-mode-close/index.js

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */









/**
 * Internal dependencies
 */

function FullscreenModeClose({
  showTooltip,
  icon,
  href,
  initialPost
}) {
  var _postType$labels$view;
  const {
    isActive,
    isRequestingSiteIcon,
    postType,
    siteIconUrl
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getCurrentPostType
    } = select(external_wp_editor_namespaceObject.store);
    const {
      isFeatureActive
    } = select(store);
    const {
      getEntityRecord,
      getPostType,
      isResolving
    } = select(external_wp_coreData_namespaceObject.store);
    const siteData = getEntityRecord('root', '__unstableBase', undefined) || {};
    const _postType = initialPost?.type || getCurrentPostType();
    return {
      isActive: isFeatureActive('fullscreenMode'),
      isRequestingSiteIcon: isResolving('getEntityRecord', ['root', '__unstableBase', undefined]),
      postType: getPostType(_postType),
      siteIconUrl: siteData.site_icon_url
    };
  }, []);
  const disableMotion = (0,external_wp_compose_namespaceObject.useReducedMotion)();
  if (!isActive || !postType) {
    return null;
  }
  let buttonIcon = (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Icon, {
    size: "36px",
    icon: library_wordpress
  });
  const effect = {
    expand: {
      scale: 1.25,
      transition: {
        type: 'tween',
        duration: '0.3'
      }
    }
  };
  if (siteIconUrl) {
    buttonIcon = (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__unstableMotion.img, {
      variants: !disableMotion && effect,
      alt: (0,external_wp_i18n_namespaceObject.__)('Site Icon'),
      className: "edit-post-fullscreen-mode-close_site-icon",
      src: siteIconUrl
    });
  }
  if (isRequestingSiteIcon) {
    buttonIcon = null;
  }

  // Override default icon if custom icon is provided via props.
  if (icon) {
    buttonIcon = (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Icon, {
      size: "36px",
      icon: icon
    });
  }
  const classes = classnames_default()({
    'edit-post-fullscreen-mode-close': true,
    'has-icon': siteIconUrl
  });
  const buttonHref = href !== null && href !== void 0 ? href : (0,external_wp_url_namespaceObject.addQueryArgs)('edit.php', {
    post_type: postType.slug
  });
  const buttonLabel = (_postType$labels$view = postType?.labels?.view_items) !== null && _postType$labels$view !== void 0 ? _postType$labels$view : (0,external_wp_i18n_namespaceObject.__)('Back');
  return (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__unstableMotion.div, {
    whileHover: "expand"
  }, (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: classes,
    href: buttonHref,
    label: buttonLabel,
    showTooltip: showTooltip
  }, buttonIcon));
}
/* harmony default export */ const fullscreen_mode_close = (FullscreenModeClose);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/header/more-menu/manage-patterns-menu-item.js

/**
 * WordPress dependencies
 */





function ManagePatternsMenuItem() {
  const url = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      canUser
    } = select(external_wp_coreData_namespaceObject.store);
    const defaultUrl = (0,external_wp_url_namespaceObject.addQueryArgs)('edit.php', {
      post_type: 'wp_block'
    });
    const patternsUrl = (0,external_wp_url_namespaceObject.addQueryArgs)('site-editor.php', {
      path: '/patterns'
    });

    // The site editor and templates both check whether the user has
    // edit_theme_options capabilities. We can leverage that here and not
    // display the manage patterns link if the user can't access it.
    return canUser('read', 'templates') ? patternsUrl : defaultUrl;
  }, []);
  return (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    role: "menuitem",
    href: url
  }, (0,external_wp_i18n_namespaceObject.__)('Manage patterns'));
}
/* harmony default export */ const manage_patterns_menu_item = (ManagePatternsMenuItem);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/header/more-menu/welcome-guide-menu-item.js

/**
 * WordPress dependencies
 */




function WelcomeGuideMenuItem() {
  const isEditingTemplate = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_editor_namespaceObject.store).getCurrentPostType() === 'wp_template', []);
  return (0,external_React_namespaceObject.createElement)(external_wp_preferences_namespaceObject.PreferenceToggleMenuItem, {
    scope: "core/edit-post",
    name: isEditingTemplate ? 'welcomeGuideTemplate' : 'welcomeGuide',
    label: (0,external_wp_i18n_namespaceObject.__)('Welcome Guide')
  });
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/preferences-modal/enable-custom-fields.js

/**
 * WordPress dependencies
 */








/**
 * Internal dependencies
 */

const {
  PreferenceBaseOption
} = unlock(external_wp_preferences_namespaceObject.privateApis);
function submitCustomFieldsForm() {
  const customFieldsForm = document.getElementById('toggle-custom-fields-form');

  // Ensure the referrer values is up to update with any
  customFieldsForm.querySelector('[name="_wp_http_referer"]').setAttribute('value', (0,external_wp_url_namespaceObject.getPathAndQueryString)(window.location.href));
  customFieldsForm.submit();
}
function CustomFieldsConfirmation({
  willEnable
}) {
  const [isReloading, setIsReloading] = (0,external_wp_element_namespaceObject.useState)(false);
  return (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, (0,external_React_namespaceObject.createElement)("p", {
    className: "edit-post-preferences-modal__custom-fields-confirmation-message"
  }, (0,external_wp_i18n_namespaceObject.__)('A page reload is required for this change. Make sure your content is saved before reloading.')), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-post-preferences-modal__custom-fields-confirmation-button",
    variant: "secondary",
    isBusy: isReloading,
    disabled: isReloading,
    onClick: () => {
      setIsReloading(true);
      submitCustomFieldsForm();
    }
  }, willEnable ? (0,external_wp_i18n_namespaceObject.__)('Show & Reload Page') : (0,external_wp_i18n_namespaceObject.__)('Hide & Reload Page')));
}
function EnableCustomFieldsOption({
  label,
  areCustomFieldsEnabled
}) {
  const [isChecked, setIsChecked] = (0,external_wp_element_namespaceObject.useState)(areCustomFieldsEnabled);
  return (0,external_React_namespaceObject.createElement)(PreferenceBaseOption, {
    label: label,
    isChecked: isChecked,
    onChange: setIsChecked
  }, isChecked !== areCustomFieldsEnabled && (0,external_React_namespaceObject.createElement)(CustomFieldsConfirmation, {
    willEnable: isChecked
  }));
}
/* harmony default export */ const enable_custom_fields = ((0,external_wp_data_namespaceObject.withSelect)(select => ({
  areCustomFieldsEnabled: !!select(external_wp_editor_namespaceObject.store).getEditorSettings().enableCustomFields
}))(EnableCustomFieldsOption));

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/preferences-modal/enable-panel.js
/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */

const {
  PreferenceBaseOption: enable_panel_PreferenceBaseOption
} = unlock(external_wp_preferences_namespaceObject.privateApis);
/* harmony default export */ const enable_panel = ((0,external_wp_compose_namespaceObject.compose)((0,external_wp_data_namespaceObject.withSelect)((select, {
  panelName
}) => {
  const {
    isEditorPanelEnabled,
    isEditorPanelRemoved
  } = select(external_wp_editor_namespaceObject.store);
  return {
    isRemoved: isEditorPanelRemoved(panelName),
    isChecked: isEditorPanelEnabled(panelName)
  };
}), (0,external_wp_compose_namespaceObject.ifCondition)(({
  isRemoved
}) => !isRemoved), (0,external_wp_data_namespaceObject.withDispatch)((dispatch, {
  panelName
}) => ({
  onChange: () => dispatch(external_wp_editor_namespaceObject.store).toggleEditorPanelEnabled(panelName)
})))(enable_panel_PreferenceBaseOption));

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/preferences-modal/meta-boxes-section.js

/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */




const {
  PreferencesModalSection
} = unlock(external_wp_preferences_namespaceObject.privateApis);
function MetaBoxesSection({
  areCustomFieldsRegistered,
  metaBoxes,
  ...sectionProps
}) {
  // The 'Custom Fields' meta box is a special case that we handle separately.
  const thirdPartyMetaBoxes = metaBoxes.filter(({
    id
  }) => id !== 'postcustom');
  if (!areCustomFieldsRegistered && thirdPartyMetaBoxes.length === 0) {
    return null;
  }
  return (0,external_React_namespaceObject.createElement)(PreferencesModalSection, {
    ...sectionProps
  }, areCustomFieldsRegistered && (0,external_React_namespaceObject.createElement)(enable_custom_fields, {
    label: (0,external_wp_i18n_namespaceObject.__)('Custom fields')
  }), thirdPartyMetaBoxes.map(({
    id,
    title
  }) => (0,external_React_namespaceObject.createElement)(enable_panel, {
    key: id,
    label: title,
    panelName: `meta-box-${id}`
  })));
}
/* harmony default export */ const meta_boxes_section = ((0,external_wp_data_namespaceObject.withSelect)(select => {
  const {
    getEditorSettings
  } = select(external_wp_editor_namespaceObject.store);
  const {
    getAllMetaBoxes
  } = select(store);
  return {
    // This setting should not live in the block editor's store.
    areCustomFieldsRegistered: getEditorSettings().enableCustomFields !== undefined,
    metaBoxes: getAllMetaBoxes()
  };
})(MetaBoxesSection));

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/preferences-modal/index.js

/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */


const {
  PreferenceToggleControl
} = unlock(external_wp_preferences_namespaceObject.privateApis);
const {
  PreferencesModal
} = unlock(external_wp_editor_namespaceObject.privateApis);
function EditPostPreferencesModal() {
  const extraSections = {
    general: (0,external_React_namespaceObject.createElement)(meta_boxes_section, {
      title: (0,external_wp_i18n_namespaceObject.__)('Advanced')
    }),
    appearance: (0,external_React_namespaceObject.createElement)(PreferenceToggleControl, {
      scope: "core/edit-post",
      featureName: "themeStyles",
      help: (0,external_wp_i18n_namespaceObject.__)('Make the editor look like your theme.'),
      label: (0,external_wp_i18n_namespaceObject.__)('Use theme styles')
    })
  };
  return (0,external_React_namespaceObject.createElement)(PreferencesModal, {
    extraSections: extraSections
  });
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/header/more-menu/index.js

/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */




const {
  ToolsMoreMenuGroup,
  ViewMoreMenuGroup
} = unlock(external_wp_editor_namespaceObject.privateApis);
const MoreMenu = () => {
  const isLargeViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('large');
  return (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, isLargeViewport && (0,external_React_namespaceObject.createElement)(ViewMoreMenuGroup, null, (0,external_React_namespaceObject.createElement)(external_wp_preferences_namespaceObject.PreferenceToggleMenuItem, {
    scope: "core/edit-post",
    name: "fullscreenMode",
    label: (0,external_wp_i18n_namespaceObject.__)('Fullscreen mode'),
    info: (0,external_wp_i18n_namespaceObject.__)('Show and hide the admin user interface'),
    messageActivated: (0,external_wp_i18n_namespaceObject.__)('Fullscreen mode activated'),
    messageDeactivated: (0,external_wp_i18n_namespaceObject.__)('Fullscreen mode deactivated'),
    shortcut: external_wp_keycodes_namespaceObject.displayShortcut.secondary('f')
  })), (0,external_React_namespaceObject.createElement)(ToolsMoreMenuGroup, null, (0,external_React_namespaceObject.createElement)(manage_patterns_menu_item, null), (0,external_React_namespaceObject.createElement)(WelcomeGuideMenuItem, null)), (0,external_React_namespaceObject.createElement)(EditPostPreferencesModal, null));
};
/* harmony default export */ const more_menu = (MoreMenu);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/header/main-dashboard-button/index.js

/**
 * WordPress dependencies
 */

const slotName = '__experimentalMainDashboardButton';
const {
  Fill,
  Slot: MainDashboardButtonSlot
} = (0,external_wp_components_namespaceObject.createSlotFill)(slotName);
const MainDashboardButton = Fill;
const Slot = ({
  children
}) => {
  const fills = (0,external_wp_components_namespaceObject.__experimentalUseSlotFills)(slotName);
  const hasFills = Boolean(fills && fills.length);
  if (!hasFills) {
    return children;
  }
  return (0,external_React_namespaceObject.createElement)(MainDashboardButtonSlot, {
    bubblesVirtually: true
  });
};
MainDashboardButton.Slot = Slot;
/* harmony default export */ const main_dashboard_button = (MainDashboardButton);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/header/index.js

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */





const {
  CollapsableBlockToolbar,
  DocumentTools,
  PostViewLink,
  PreviewDropdown,
  PinnedItems,
  MoreMenu: header_MoreMenu,
  PostPublishButtonOrToggle
} = unlock(external_wp_editor_namespaceObject.privateApis);
const slideY = {
  hidden: {
    y: '-50px'
  },
  distractionFreeInactive: {
    y: 0
  },
  hover: {
    y: 0,
    transition: {
      type: 'tween',
      delay: 0.2
    }
  }
};
const slideX = {
  hidden: {
    x: '-100%'
  },
  distractionFreeInactive: {
    x: 0
  },
  hover: {
    x: 0,
    transition: {
      type: 'tween',
      delay: 0.2
    }
  }
};
function Header({
  setEntitiesSavedStatesCallback,
  initialPost
}) {
  const isWideViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('large');
  const isLargeViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('medium');
  const {
    isTextEditor,
    hasActiveMetaboxes,
    isPublishSidebarOpened,
    showIconLabels,
    hasHistory,
    hasFixedToolbar
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      get: getPreference
    } = select(external_wp_preferences_namespaceObject.store);
    const {
      getEditorMode
    } = select(external_wp_editor_namespaceObject.store);
    return {
      isTextEditor: getEditorMode() === 'text',
      hasActiveMetaboxes: select(store).hasMetaBoxes(),
      hasHistory: !!select(external_wp_editor_namespaceObject.store).getEditorSettings().onNavigateToPreviousEntityRecord,
      isPublishSidebarOpened: select(external_wp_editor_namespaceObject.store).isPublishSidebarOpened(),
      showIconLabels: getPreference('core', 'showIconLabels'),
      hasFixedToolbar: getPreference('core', 'fixedToolbar')
    };
  }, []);
  const hasTopToolbar = isLargeViewport && hasFixedToolbar;
  const [isBlockToolsCollapsed, setIsBlockToolsCollapsed] = (0,external_wp_element_namespaceObject.useState)(true);
  return (0,external_React_namespaceObject.createElement)("div", {
    className: "edit-post-header"
  }, (0,external_React_namespaceObject.createElement)(main_dashboard_button.Slot, null, (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__unstableMotion.div, {
    variants: slideX,
    transition: {
      type: 'tween',
      delay: 0.8
    }
  }, (0,external_React_namespaceObject.createElement)(fullscreen_mode_close, {
    showTooltip: true,
    initialPost: initialPost
  }))), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__unstableMotion.div, {
    variants: slideY,
    transition: {
      type: 'tween',
      delay: 0.8
    },
    className: "edit-post-header__toolbar"
  }, (0,external_React_namespaceObject.createElement)(DocumentTools, {
    disableBlockTools: isTextEditor
  }), hasTopToolbar && (0,external_React_namespaceObject.createElement)(CollapsableBlockToolbar, {
    isCollapsed: isBlockToolsCollapsed,
    onToggle: setIsBlockToolsCollapsed
  }), (0,external_React_namespaceObject.createElement)("div", {
    className: classnames_default()('edit-post-header__center', {
      'is-collapsed': hasHistory && !isBlockToolsCollapsed && hasTopToolbar
    })
  }, hasHistory && (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.DocumentBar, null))), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__unstableMotion.div, {
    variants: slideY,
    transition: {
      type: 'tween',
      delay: 0.8
    },
    className: "edit-post-header__settings"
  }, !isPublishSidebarOpened &&
  // This button isn't completely hidden by the publish sidebar.
  // We can't hide the whole toolbar when the publish sidebar is open because
  // we want to prevent mounting/unmounting the PostPublishButtonOrToggle DOM node.
  // We track that DOM node to return focus to the PostPublishButtonOrToggle
  // when the publish sidebar has been closed.
  (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostSavedState, {
    forceIsDirty: hasActiveMetaboxes
  }), (0,external_React_namespaceObject.createElement)(PreviewDropdown, {
    forceIsAutosaveable: hasActiveMetaboxes
  }), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostPreviewButton, {
    className: "edit-post-header__post-preview-button",
    forceIsAutosaveable: hasActiveMetaboxes
  }), (0,external_React_namespaceObject.createElement)(PostViewLink, null), (0,external_React_namespaceObject.createElement)(PostPublishButtonOrToggle, {
    forceIsDirty: hasActiveMetaboxes,
    setEntitiesSavedStatesCallback: setEntitiesSavedStatesCallback
  }), (isWideViewport || !showIconLabels) && (0,external_React_namespaceObject.createElement)(PinnedItems.Slot, {
    scope: "core"
  }), (0,external_React_namespaceObject.createElement)(header_MoreMenu, null), (0,external_React_namespaceObject.createElement)(more_menu, null)));
}
/* harmony default export */ const header = (Header);

;// CONCATENATED MODULE: ./packages/icons/build-module/library/drawer-left.js

/**
 * WordPress dependencies
 */

const drawerLeft = (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  width: "24",
  height: "24",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  fillRule: "evenodd",
  clipRule: "evenodd",
  d: "M18 4H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM8.5 18.5H6c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h2.5v13zm10-.5c0 .3-.2.5-.5.5h-8v-13h8c.3 0 .5.2.5.5v12z"
}));
/* harmony default export */ const drawer_left = (drawerLeft);

;// CONCATENATED MODULE: ./packages/icons/build-module/library/drawer-right.js

/**
 * WordPress dependencies
 */

const drawerRight = (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  width: "24",
  height: "24",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  fillRule: "evenodd",
  clipRule: "evenodd",
  d: "M18 4H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-4 14.5H6c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h8v13zm4.5-.5c0 .3-.2.5-.5.5h-2.5v-13H18c.3 0 .5.2.5.5v12z"
}));
/* harmony default export */ const drawer_right = (drawerRight);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/sidebar/settings-header/index.js

/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */


const {
  Tabs
} = unlock(external_wp_components_namespaceObject.privateApis);
const SettingsHeader = (_, ref) => {
  const {
    documentLabel
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getPostTypeLabel
    } = select(external_wp_editor_namespaceObject.store);
    return {
      // translators: Default label for the Document sidebar tab, not selected.
      documentLabel: getPostTypeLabel() || (0,external_wp_i18n_namespaceObject._x)('Document', 'noun')
    };
  }, []);
  return (0,external_React_namespaceObject.createElement)(Tabs.TabList, {
    ref: ref
  }, (0,external_React_namespaceObject.createElement)(Tabs.Tab, {
    tabId: sidebars.document
    // Used for focus management in the SettingsSidebar component.
    ,
    "data-tab-id": sidebars.document
  }, documentLabel), (0,external_React_namespaceObject.createElement)(Tabs.Tab, {
    tabId: sidebars.block
    // Used for focus management in the SettingsSidebar component.
    ,
    "data-tab-id": sidebars.block
  }, (0,external_wp_i18n_namespaceObject.__)('Block')));
};
/* harmony default export */ const settings_header = ((0,external_wp_element_namespaceObject.forwardRef)(SettingsHeader));

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/sidebar/post-trash/index.js

/**
 * WordPress dependencies
 */

function PostTrash() {
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostTrashCheck, null, (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostTrash, null));
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/sidebar/post-sticky/index.js

/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */

const {
  PostPanelRow
} = unlock(external_wp_editor_namespaceObject.privateApis);
function PostSticky() {
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostStickyCheck, null, (0,external_React_namespaceObject.createElement)(PostPanelRow, null, (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostSticky, null)));
}
/* harmony default export */ const post_sticky = (PostSticky);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/sidebar/post-slug/index.js

/**
 * WordPress dependencies
 */


function PostSlug() {
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostSlugCheck, null, (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelRow, {
    className: "edit-post-post-slug"
  }, (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostSlug, null)));
}
/* harmony default export */ const post_slug = (PostSlug);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/sidebar/post-format/index.js

/**
 * WordPress dependencies
 */


function PostFormat() {
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostFormatCheck, null, (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelRow, {
    className: "edit-post-post-format"
  }, (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostFormat, null)));
}
/* harmony default export */ const post_format = (PostFormat);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/sidebar/post-status/index.js

/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */





const {
  PostStatus: PostStatusPanel,
  PrivatePostExcerptPanel,
  PostContentInformation,
  PostLastEditedPanel
} = unlock(external_wp_editor_namespaceObject.privateApis);

/**
 * Module Constants
 */
const PANEL_NAME = 'post-status';
function PostStatus() {
  const {
    isOpened,
    isRemoved,
    showPostContentPanels
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    // We use isEditorPanelRemoved to hide the panel if it was programatically removed. We do
    // not use isEditorPanelEnabled since this panel should not be disabled through the UI.
    const {
      isEditorPanelRemoved,
      isEditorPanelOpened,
      getCurrentPostType
    } = select(external_wp_editor_namespaceObject.store);
    const postType = getCurrentPostType();
    return {
      isRemoved: isEditorPanelRemoved(PANEL_NAME),
      isOpened: isEditorPanelOpened(PANEL_NAME),
      // Post excerpt panel is rendered in different place depending on the post type.
      // So we cannot make this check inside the PostExcerpt component based on the current edited entity.
      showPostContentPanels: !['wp_template', 'wp_template_part', 'wp_block'].includes(postType)
    };
  }, []);
  const {
    toggleEditorPanelOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_editor_namespaceObject.store);
  if (isRemoved) {
    return null;
  }
  return (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelBody, {
    className: "edit-post-post-status",
    title: (0,external_wp_i18n_namespaceObject.__)('Summary'),
    opened: isOpened,
    onToggle: () => toggleEditorPanelOpened(PANEL_NAME)
  }, (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginPostStatusInfo.Slot, null, fills => (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, showPostContentPanels && (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: 3
    //  TODO: this needs to be consolidated with the panel in site editor, when we unify them.
    ,
    style: {
      marginBlockEnd: '24px'
    }
  }, (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostFeaturedImagePanel, {
    withPanelBody: false
  }), (0,external_React_namespaceObject.createElement)(PrivatePostExcerptPanel, null), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: 1
  }, (0,external_React_namespaceObject.createElement)(PostContentInformation, null), (0,external_React_namespaceObject.createElement)(PostLastEditedPanel, null))), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: 1,
    style: {
      marginBlockEnd: '12px'
    }
  }, (0,external_React_namespaceObject.createElement)(PostStatusPanel, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostSchedulePanel, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostTemplatePanel, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostURLPanel, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostSyncStatus, null)), (0,external_React_namespaceObject.createElement)(post_sticky, null), (0,external_React_namespaceObject.createElement)(post_format, null), (0,external_React_namespaceObject.createElement)(post_slug, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostAuthorPanel, null), fills, (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, {
    style: {
      marginTop: '16px'
    }
  }, (0,external_React_namespaceObject.createElement)(PostTrash, null)))));
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/meta-boxes/meta-boxes-area/index.js

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


/**
 * Render metabox area.
 *
 * @param {Object} props          Component props.
 * @param {string} props.location metabox location.
 * @return {Component} The component to be rendered.
 */
function MetaBoxesArea({
  location
}) {
  const container = (0,external_wp_element_namespaceObject.useRef)(null);
  const formRef = (0,external_wp_element_namespaceObject.useRef)(null);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    formRef.current = document.querySelector('.metabox-location-' + location);
    if (formRef.current) {
      container.current.appendChild(formRef.current);
    }
    return () => {
      if (formRef.current) {
        document.querySelector('#metaboxes').appendChild(formRef.current);
      }
    };
  }, [location]);
  const isSaving = (0,external_wp_data_namespaceObject.useSelect)(select => {
    return select(store).isSavingMetaBoxes();
  }, []);
  const classes = classnames_default()('edit-post-meta-boxes-area', `is-${location}`, {
    'is-loading': isSaving
  });
  return (0,external_React_namespaceObject.createElement)("div", {
    className: classes
  }, isSaving && (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Spinner, null), (0,external_React_namespaceObject.createElement)("div", {
    className: "edit-post-meta-boxes-area__container",
    ref: container
  }), (0,external_React_namespaceObject.createElement)("div", {
    className: "edit-post-meta-boxes-area__clear"
  }));
}
/* harmony default export */ const meta_boxes_area = (MetaBoxesArea);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/meta-boxes/meta-box-visibility.js
/**
 * WordPress dependencies
 */



class MetaBoxVisibility extends external_wp_element_namespaceObject.Component {
  componentDidMount() {
    this.updateDOM();
  }
  componentDidUpdate(prevProps) {
    if (this.props.isVisible !== prevProps.isVisible) {
      this.updateDOM();
    }
  }
  updateDOM() {
    const {
      id,
      isVisible
    } = this.props;
    const element = document.getElementById(id);
    if (!element) {
      return;
    }
    if (isVisible) {
      element.classList.remove('is-hidden');
    } else {
      element.classList.add('is-hidden');
    }
  }
  render() {
    return null;
  }
}
/* harmony default export */ const meta_box_visibility = ((0,external_wp_data_namespaceObject.withSelect)((select, {
  id
}) => ({
  isVisible: select(external_wp_editor_namespaceObject.store).isEditorPanelEnabled(`meta-box-${id}`)
}))(MetaBoxVisibility));

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/meta-boxes/index.js

/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */



function MetaBoxes({
  location
}) {
  const registry = (0,external_wp_data_namespaceObject.useRegistry)();
  const {
    metaBoxes,
    areMetaBoxesInitialized,
    isEditorReady
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      __unstableIsEditorReady
    } = select(external_wp_editor_namespaceObject.store);
    const {
      getMetaBoxesPerLocation,
      areMetaBoxesInitialized: _areMetaBoxesInitialized
    } = select(store);
    return {
      metaBoxes: getMetaBoxesPerLocation(location),
      areMetaBoxesInitialized: _areMetaBoxesInitialized(),
      isEditorReady: __unstableIsEditorReady()
    };
  }, [location]);
  const hasMetaBoxes = !!metaBoxes?.length;

  // When editor is ready, initialize postboxes (wp core script) and metabox
  // saving. This initializes all meta box locations, not just this specific
  // one.
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (isEditorReady && hasMetaBoxes && !areMetaBoxesInitialized) {
      registry.dispatch(store).initializeMetaBoxes();
    }
  }, [isEditorReady, hasMetaBoxes, areMetaBoxesInitialized]);
  if (!areMetaBoxesInitialized) {
    return null;
  }
  return (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, (metaBoxes !== null && metaBoxes !== void 0 ? metaBoxes : []).map(({
    id
  }) => (0,external_React_namespaceObject.createElement)(meta_box_visibility, {
    key: id,
    id: id
  })), (0,external_React_namespaceObject.createElement)(meta_boxes_area, {
    location: location
  }));
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/sidebar/settings-sidebar/index.js

/**
 * WordPress dependencies
 */










/**
 * Internal dependencies
 */






const {
  PostCardPanel,
  PostActions,
  interfaceStore: settings_sidebar_interfaceStore
} = unlock(external_wp_editor_namespaceObject.privateApis);
const {
  Tabs: settings_sidebar_Tabs
} = unlock(external_wp_components_namespaceObject.privateApis);
const {
  PatternOverridesPanel,
  useAutoSwitchEditorSidebars
} = unlock(external_wp_editor_namespaceObject.privateApis);
const SIDEBAR_ACTIVE_BY_DEFAULT = external_wp_element_namespaceObject.Platform.select({
  web: true,
  native: false
});
const sidebars = {
  document: 'edit-post/document',
  block: 'edit-post/block'
};
const SidebarContent = ({
  tabName,
  keyboardShortcut,
  isEditingTemplate
}) => {
  const tabListRef = (0,external_wp_element_namespaceObject.useRef)(null);
  // Because `PluginSidebar` renders a `ComplementaryArea`, we
  // need to forward the `Tabs` context so it can be passed through the
  // underlying slot/fill.
  const tabsContextValue = (0,external_wp_element_namespaceObject.useContext)(settings_sidebar_Tabs.Context);

  // This effect addresses a race condition caused by tabbing from the last
  // block in the editor into the settings sidebar. Without this effect, the
  // selected tab and browser focus can become separated in an unexpected way
  // (e.g the "block" tab is focused, but the "post" tab is selected).
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    const tabsElements = Array.from(tabListRef.current?.querySelectorAll('[role="tab"]') || []);
    const selectedTabElement = tabsElements.find(
    // We are purposefully using a custom `data-tab-id` attribute here
    // because we don't want rely on any assumptions about `Tabs`
    // component internals.
    element => element.getAttribute('data-tab-id') === tabName);
    const activeElement = selectedTabElement?.ownerDocument.activeElement;
    const tabsHasFocus = tabsElements.some(element => {
      return activeElement && activeElement.id === element.id;
    });
    if (tabsHasFocus && selectedTabElement && selectedTabElement.id !== activeElement?.id) {
      selectedTabElement?.focus();
    }
  }, [tabName]);
  const {
    createSuccessNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  const onActionPerformed = (0,external_wp_element_namespaceObject.useCallback)((actionId, items) => {
    switch (actionId) {
      case 'move-to-trash':
        {
          const postType = items[0].type;
          document.location.href = (0,external_wp_url_namespaceObject.addQueryArgs)('edit.php', {
            post_type: postType
          });
        }
        break;
      case 'duplicate-post':
        {
          const newItem = items[0];
          const title = typeof newItem.title === 'string' ? newItem.title : newItem.title?.rendered;
          createSuccessNotice((0,external_wp_i18n_namespaceObject.sprintf)(
          // translators: %s: Title of the created post e.g: "Post 1".
          (0,external_wp_i18n_namespaceObject.__)('"%s" successfully created.'), title), {
            type: 'snackbar',
            id: 'duplicate-post-action',
            actions: [{
              label: (0,external_wp_i18n_namespaceObject.__)('Edit'),
              onClick: () => {
                const postId = newItem.id;
                document.location.href = (0,external_wp_url_namespaceObject.addQueryArgs)('post.php', {
                  post: postId,
                  action: 'edit'
                });
              }
            }]
          });
        }
        break;
    }
  }, [createSuccessNotice]);
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginSidebar, {
    identifier: tabName,
    header: (0,external_React_namespaceObject.createElement)(settings_sidebar_Tabs.Context.Provider, {
      value: tabsContextValue
    }, (0,external_React_namespaceObject.createElement)(settings_header, {
      ref: tabListRef
    })),
    closeLabel: (0,external_wp_i18n_namespaceObject.__)('Close Settings')
    // This classname is added so we can apply a corrective negative
    // margin to the panel.
    // see https://github.com/WordPress/gutenberg/pull/55360#pullrequestreview-1737671049
    ,
    className: "edit-post-sidebar__panel",
    headerClassName: "edit-post-sidebar__panel-tabs"
    /* translators: button label text should, if possible, be under 16 characters. */,
    title: (0,external_wp_i18n_namespaceObject.__)('Settings'),
    toggleShortcut: keyboardShortcut,
    icon: (0,external_wp_i18n_namespaceObject.isRTL)() ? drawer_left : drawer_right,
    isActiveByDefault: SIDEBAR_ACTIVE_BY_DEFAULT
  }, (0,external_React_namespaceObject.createElement)(settings_sidebar_Tabs.Context.Provider, {
    value: tabsContextValue
  }, (0,external_React_namespaceObject.createElement)(settings_sidebar_Tabs.TabPanel, {
    tabId: sidebars.document,
    focusable: false
  }, (0,external_React_namespaceObject.createElement)(PostCardPanel, {
    actions: (0,external_React_namespaceObject.createElement)(PostActions, {
      onActionPerformed: onActionPerformed
    })
  }), !isEditingTemplate && (0,external_React_namespaceObject.createElement)(PostStatus, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginDocumentSettingPanel.Slot, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostLastRevisionPanel, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostTaxonomiesPanel, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostDiscussionPanel, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PageAttributesPanel, null), (0,external_React_namespaceObject.createElement)(PatternOverridesPanel, null), !isEditingTemplate && (0,external_React_namespaceObject.createElement)(MetaBoxes, {
    location: "side"
  })), (0,external_React_namespaceObject.createElement)(settings_sidebar_Tabs.TabPanel, {
    tabId: sidebars.block,
    focusable: false
  }, (0,external_React_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockInspector, null))));
};
const SettingsSidebar = () => {
  useAutoSwitchEditorSidebars();
  const {
    tabName,
    keyboardShortcut,
    isEditingTemplate
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const shortcut = select(external_wp_keyboardShortcuts_namespaceObject.store).getShortcutRepresentation('core/editor/toggle-sidebar');
    const sidebar = select(settings_sidebar_interfaceStore).getActiveComplementaryArea('core');
    const _isEditorSidebarOpened = [sidebars.block, sidebars.document].includes(sidebar);
    let _tabName = sidebar;
    if (!_isEditorSidebarOpened) {
      _tabName = !!select(external_wp_blockEditor_namespaceObject.store).getBlockSelectionStart() ? sidebars.block : sidebars.document;
    }
    return {
      tabName: _tabName,
      keyboardShortcut: shortcut,
      isEditingTemplate: select(external_wp_editor_namespaceObject.store).getCurrentPostType() === 'wp_template'
    };
  }, []);
  const {
    openGeneralSidebar
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  const onTabSelect = (0,external_wp_element_namespaceObject.useCallback)(newSelectedTabId => {
    if (!!newSelectedTabId) {
      openGeneralSidebar(newSelectedTabId);
    }
  }, [openGeneralSidebar]);
  return (0,external_React_namespaceObject.createElement)(settings_sidebar_Tabs, {
    selectedTabId: tabName,
    onSelect: onTabSelect,
    selectOnMove: false
  }, (0,external_React_namespaceObject.createElement)(SidebarContent, {
    tabName: tabName,
    keyboardShortcut: keyboardShortcut,
    isEditingTemplate: isEditingTemplate
  }));
};
/* harmony default export */ const settings_sidebar = (SettingsSidebar);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/welcome-guide/image.js

function WelcomeGuideImage({
  nonAnimatedSrc,
  animatedSrc
}) {
  return (0,external_React_namespaceObject.createElement)("picture", {
    className: "edit-post-welcome-guide__image"
  }, (0,external_React_namespaceObject.createElement)("source", {
    srcSet: nonAnimatedSrc,
    media: "(prefers-reduced-motion: reduce)"
  }), (0,external_React_namespaceObject.createElement)("img", {
    src: animatedSrc,
    width: "312",
    height: "240",
    alt: ""
  }));
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/welcome-guide/default.js

/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */


function WelcomeGuideDefault() {
  const {
    toggleFeature
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  return (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Guide, {
    className: "edit-post-welcome-guide",
    contentLabel: (0,external_wp_i18n_namespaceObject.__)('Welcome to the block editor'),
    finishButtonText: (0,external_wp_i18n_namespaceObject.__)('Get started'),
    onFinish: () => toggleFeature('welcomeGuide'),
    pages: [{
      image: (0,external_React_namespaceObject.createElement)(WelcomeGuideImage, {
        nonAnimatedSrc: "https://s.w.org/images/block-editor/welcome-canvas.svg",
        animatedSrc: "https://s.w.org/images/block-editor/welcome-canvas.gif"
      }),
      content: (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, (0,external_React_namespaceObject.createElement)("h1", {
        className: "edit-post-welcome-guide__heading"
      }, (0,external_wp_i18n_namespaceObject.__)('Welcome to the block editor')), (0,external_React_namespaceObject.createElement)("p", {
        className: "edit-post-welcome-guide__text"
      }, (0,external_wp_i18n_namespaceObject.__)('In the WordPress editor, each paragraph, image, or video is presented as a distinct “block” of content.')))
    }, {
      image: (0,external_React_namespaceObject.createElement)(WelcomeGuideImage, {
        nonAnimatedSrc: "https://s.w.org/images/block-editor/welcome-editor.svg",
        animatedSrc: "https://s.w.org/images/block-editor/welcome-editor.gif"
      }),
      content: (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, (0,external_React_namespaceObject.createElement)("h1", {
        className: "edit-post-welcome-guide__heading"
      }, (0,external_wp_i18n_namespaceObject.__)('Make each block your own')), (0,external_React_namespaceObject.createElement)("p", {
        className: "edit-post-welcome-guide__text"
      }, (0,external_wp_i18n_namespaceObject.__)('Each block comes with its own set of controls for changing things like color, width, and alignment. These will show and hide automatically when you have a block selected.')))
    }, {
      image: (0,external_React_namespaceObject.createElement)(WelcomeGuideImage, {
        nonAnimatedSrc: "https://s.w.org/images/block-editor/welcome-library.svg",
        animatedSrc: "https://s.w.org/images/block-editor/welcome-library.gif"
      }),
      content: (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, (0,external_React_namespaceObject.createElement)("h1", {
        className: "edit-post-welcome-guide__heading"
      }, (0,external_wp_i18n_namespaceObject.__)('Get to know the block library')), (0,external_React_namespaceObject.createElement)("p", {
        className: "edit-post-welcome-guide__text"
      }, (0,external_wp_element_namespaceObject.createInterpolateElement)((0,external_wp_i18n_namespaceObject.__)('All of the blocks available to you live in the block library. You’ll find it wherever you see the <InserterIconImage /> icon.'), {
        InserterIconImage: (0,external_React_namespaceObject.createElement)("img", {
          alt: (0,external_wp_i18n_namespaceObject.__)('inserter'),
          src: "data:image/svg+xml,%3Csvg width='18' height='18' viewBox='0 0 18 18' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='18' height='18' rx='2' fill='%231E1E1E'/%3E%3Cpath d='M9.22727 4V14M4 8.77273H14' stroke='white' stroke-width='1.5'/%3E%3C/svg%3E%0A"
        })
      })))
    }, {
      image: (0,external_React_namespaceObject.createElement)(WelcomeGuideImage, {
        nonAnimatedSrc: "https://s.w.org/images/block-editor/welcome-documentation.svg",
        animatedSrc: "https://s.w.org/images/block-editor/welcome-documentation.gif"
      }),
      content: (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, (0,external_React_namespaceObject.createElement)("h1", {
        className: "edit-post-welcome-guide__heading"
      }, (0,external_wp_i18n_namespaceObject.__)('Learn how to use the block editor')), (0,external_React_namespaceObject.createElement)("p", {
        className: "edit-post-welcome-guide__text"
      }, (0,external_wp_i18n_namespaceObject.__)('New to the block editor? Want to learn more about using it? '), (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.ExternalLink, {
        href: (0,external_wp_i18n_namespaceObject.__)('https://wordpress.org/documentation/article/wordpress-block-editor/')
      }, (0,external_wp_i18n_namespaceObject.__)("Here's a detailed guide."))))
    }]
  });
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/welcome-guide/template.js

/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function WelcomeGuideTemplate() {
  const {
    toggleFeature
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  return (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.Guide, {
    className: "edit-template-welcome-guide",
    contentLabel: (0,external_wp_i18n_namespaceObject.__)('Welcome to the template editor'),
    finishButtonText: (0,external_wp_i18n_namespaceObject.__)('Get started'),
    onFinish: () => toggleFeature('welcomeGuideTemplate'),
    pages: [{
      image: (0,external_React_namespaceObject.createElement)(WelcomeGuideImage, {
        nonAnimatedSrc: "https://s.w.org/images/block-editor/welcome-template-editor.svg",
        animatedSrc: "https://s.w.org/images/block-editor/welcome-template-editor.gif"
      }),
      content: (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, (0,external_React_namespaceObject.createElement)("h1", {
        className: "edit-post-welcome-guide__heading"
      }, (0,external_wp_i18n_namespaceObject.__)('Welcome to the template editor')), (0,external_React_namespaceObject.createElement)("p", {
        className: "edit-post-welcome-guide__text"
      }, (0,external_wp_i18n_namespaceObject.__)('Templates help define the layout of the site. You can customize all aspects of your posts and pages using blocks and patterns in this editor.')))
    }]
  });
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/welcome-guide/index.js

/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */



function WelcomeGuide() {
  const {
    isActive,
    isEditingTemplate
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      isFeatureActive
    } = select(store);
    const {
      getCurrentPostType
    } = select(external_wp_editor_namespaceObject.store);
    const _isEditingTemplate = getCurrentPostType() === 'wp_template';
    const feature = _isEditingTemplate ? 'welcomeGuideTemplate' : 'welcomeGuide';
    return {
      isActive: isFeatureActive(feature),
      isEditingTemplate: _isEditingTemplate
    };
  }, []);
  if (!isActive) {
    return null;
  }
  return isEditingTemplate ? (0,external_React_namespaceObject.createElement)(WelcomeGuideTemplate, null) : (0,external_React_namespaceObject.createElement)(WelcomeGuideDefault, null);
}

;// CONCATENATED MODULE: ./packages/icons/build-module/library/block-default.js

/**
 * WordPress dependencies
 */

const blockDefault = (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M19 8h-1V6h-5v2h-2V6H6v2H5c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2zm.5 10c0 .3-.2.5-.5.5H5c-.3 0-.5-.2-.5-.5v-8c0-.3.2-.5.5-.5h14c.3 0 .5.2.5.5v8z"
}));
/* harmony default export */ const block_default = (blockDefault);

;// CONCATENATED MODULE: ./packages/icons/build-module/library/fullscreen.js

/**
 * WordPress dependencies
 */

const fullscreen = (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M6 4a2 2 0 0 0-2 2v3h1.5V6a.5.5 0 0 1 .5-.5h3V4H6Zm3 14.5H6a.5.5 0 0 1-.5-.5v-3H4v3a2 2 0 0 0 2 2h3v-1.5Zm6 1.5v-1.5h3a.5.5 0 0 0 .5-.5v-3H20v3a2 2 0 0 1-2 2h-3Zm3-16a2 2 0 0 1 2 2v3h-1.5V6a.5.5 0 0 0-.5-.5h-3V4h3Z"
}));
/* harmony default export */ const library_fullscreen = (fullscreen);

;// CONCATENATED MODULE: ./packages/icons/build-module/library/format-list-bullets.js

/**
 * WordPress dependencies
 */

const formatListBullets = (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_React_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M11.1 15.8H20v-1.5h-8.9v1.5zm0-8.6v1.5H20V7.2h-8.9zM6 13c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-7c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"
}));
/* harmony default export */ const format_list_bullets = (formatListBullets);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/hooks/commands/use-common-commands.js
/**
 * WordPress dependencies
 */








/**
 * Internal dependencies
 */


const {
  interfaceStore: use_common_commands_interfaceStore
} = unlock(external_wp_editor_namespaceObject.privateApis);
function useCommonCommands() {
  const {
    openGeneralSidebar,
    closeGeneralSidebar
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  const {
    activeSidebar,
    isFullscreen,
    isPublishSidebarEnabled
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      get
    } = select(external_wp_preferences_namespaceObject.store);
    return {
      activeSidebar: select(use_common_commands_interfaceStore).getActiveComplementaryArea('core'),
      isPublishSidebarEnabled: select(external_wp_editor_namespaceObject.store).isPublishSidebarEnabled(),
      isFullscreen: get('core/edit-post', 'fullscreenMode')
    };
  }, []);
  const {
    toggle
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_preferences_namespaceObject.store);
  const {
    createInfoNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  (0,external_wp_commands_namespaceObject.useCommand)({
    name: 'core/open-settings-sidebar',
    label: (0,external_wp_i18n_namespaceObject.__)('Toggle settings sidebar'),
    icon: (0,external_wp_i18n_namespaceObject.isRTL)() ? drawer_left : drawer_right,
    callback: ({
      close
    }) => {
      close();
      if (activeSidebar === 'edit-post/document') {
        closeGeneralSidebar();
      } else {
        openGeneralSidebar('edit-post/document');
      }
    }
  });
  (0,external_wp_commands_namespaceObject.useCommand)({
    name: 'core/open-block-inspector',
    label: (0,external_wp_i18n_namespaceObject.__)('Toggle block inspector'),
    icon: block_default,
    callback: ({
      close
    }) => {
      close();
      if (activeSidebar === 'edit-post/block') {
        closeGeneralSidebar();
      } else {
        openGeneralSidebar('edit-post/block');
      }
    }
  });
  (0,external_wp_commands_namespaceObject.useCommand)({
    name: 'core/toggle-fullscreen-mode',
    label: isFullscreen ? (0,external_wp_i18n_namespaceObject.__)('Exit fullscreen') : (0,external_wp_i18n_namespaceObject.__)('Enter fullscreen'),
    icon: library_fullscreen,
    callback: ({
      close
    }) => {
      toggle('core/edit-post', 'fullscreenMode');
      close();
      createInfoNotice(isFullscreen ? (0,external_wp_i18n_namespaceObject.__)('Fullscreen off.') : (0,external_wp_i18n_namespaceObject.__)('Fullscreen on.'), {
        id: 'core/edit-post/toggle-fullscreen-mode/notice',
        type: 'snackbar',
        actions: [{
          label: (0,external_wp_i18n_namespaceObject.__)('Undo'),
          onClick: () => {
            toggle('core/edit-post', 'fullscreenMode');
          }
        }]
      });
    }
  });
  (0,external_wp_commands_namespaceObject.useCommand)({
    name: 'core/toggle-publish-sidebar',
    label: isPublishSidebarEnabled ? (0,external_wp_i18n_namespaceObject.__)('Disable pre-publish checks') : (0,external_wp_i18n_namespaceObject.__)('Enable pre-publish checks'),
    icon: format_list_bullets,
    callback: ({
      close
    }) => {
      close();
      toggle('core', 'isPublishSidebarEnabled');
      createInfoNotice(isPublishSidebarEnabled ? (0,external_wp_i18n_namespaceObject.__)('Pre-publish checks disabled.') : (0,external_wp_i18n_namespaceObject.__)('Pre-publish checks enabled.'), {
        id: 'core/editor/publish-sidebar/notice',
        type: 'snackbar'
      });
    }
  });
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/layout/index.js

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */















/**
 * Internal dependencies
 */












const {
  getLayoutStyles
} = unlock(external_wp_blockEditor_namespaceObject.privateApis);
const {
  useCommands
} = unlock(external_wp_coreCommands_namespaceObject.privateApis);
const {
  useCommandContext
} = unlock(external_wp_commands_namespaceObject.privateApis);
const {
  InserterSidebar,
  ListViewSidebar,
  ComplementaryArea,
  FullscreenMode,
  SavePublishPanels,
  InterfaceSkeleton,
  interfaceStore: layout_interfaceStore
} = unlock(external_wp_editor_namespaceObject.privateApis);
const {
  BlockKeyboardShortcuts
} = unlock(external_wp_blockLibrary_namespaceObject.privateApis);
const interfaceLabels = {
  /* translators: accessibility text for the editor top bar landmark region. */
  header: (0,external_wp_i18n_namespaceObject.__)('Editor top bar'),
  /* translators: accessibility text for the editor content landmark region. */
  body: (0,external_wp_i18n_namespaceObject.__)('Editor content'),
  /* translators: accessibility text for the editor settings landmark region. */
  sidebar: (0,external_wp_i18n_namespaceObject.__)('Editor settings'),
  /* translators: accessibility text for the editor publish landmark region. */
  actions: (0,external_wp_i18n_namespaceObject.__)('Editor publish'),
  /* translators: accessibility text for the editor footer landmark region. */
  footer: (0,external_wp_i18n_namespaceObject.__)('Editor footer')
};
function useEditorStyles() {
  const {
    hasThemeStyleSupport,
    editorSettings
  } = (0,external_wp_data_namespaceObject.useSelect)(select => ({
    hasThemeStyleSupport: select(store).isFeatureActive('themeStyles'),
    editorSettings: select(external_wp_editor_namespaceObject.store).getEditorSettings()
  }), []);

  // Compute the default styles.
  return (0,external_wp_element_namespaceObject.useMemo)(() => {
    var _editorSettings$style, _editorSettings$style2;
    const presetStyles = (_editorSettings$style = editorSettings.styles?.filter(style => style.__unstableType && style.__unstableType !== 'theme')) !== null && _editorSettings$style !== void 0 ? _editorSettings$style : [];
    const defaultEditorStyles = [...editorSettings.defaultEditorStyles, ...presetStyles];

    // Has theme styles if the theme supports them and if some styles were not preset styles (in which case they're theme styles).
    const hasThemeStyles = hasThemeStyleSupport && presetStyles.length !== ((_editorSettings$style2 = editorSettings.styles?.length) !== null && _editorSettings$style2 !== void 0 ? _editorSettings$style2 : 0);

    // If theme styles are not present or displayed, ensure that
    // base layout styles are still present in the editor.
    if (!editorSettings.disableLayoutStyles && !hasThemeStyles) {
      defaultEditorStyles.push({
        css: getLayoutStyles({
          style: {},
          selector: 'body',
          hasBlockGapSupport: false,
          hasFallbackGapSupport: true,
          fallbackGapValue: '0.5em'
        })
      });
    }
    return hasThemeStyles ? editorSettings.styles : defaultEditorStyles;
  }, [editorSettings.defaultEditorStyles, editorSettings.disableLayoutStyles, editorSettings.styles, hasThemeStyleSupport]);
}
function Layout({
  initialPost
}) {
  useCommands();
  useCommonCommands();
  const isMobileViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('medium', '<');
  const isHugeViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('huge', '>=');
  const isWideViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('large');
  const isLargeViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('medium');
  const {
    closeGeneralSidebar
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  const {
    createErrorNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  const {
    setIsInserterOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_editor_namespaceObject.store);
  const {
    mode,
    isFullscreenActive,
    isRichEditingEnabled,
    sidebarIsOpened,
    hasActiveMetaboxes,
    previousShortcut,
    nextShortcut,
    hasBlockSelected,
    isInserterOpened,
    isListViewOpened,
    showIconLabels,
    isDistractionFree,
    showBlockBreadcrumbs,
    showMetaBoxes,
    documentLabel,
    hasHistory,
    hasBlockBreadcrumbs
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      get
    } = select(external_wp_preferences_namespaceObject.store);
    const {
      getEditorSettings,
      getPostTypeLabel
    } = select(external_wp_editor_namespaceObject.store);
    const editorSettings = getEditorSettings();
    const postTypeLabel = getPostTypeLabel();
    return {
      showMetaBoxes: select(external_wp_editor_namespaceObject.store).getRenderingMode() === 'post-only',
      sidebarIsOpened: !!(select(layout_interfaceStore).getActiveComplementaryArea('core') || select(external_wp_editor_namespaceObject.store).isPublishSidebarOpened()),
      isFullscreenActive: select(store).isFeatureActive('fullscreenMode'),
      isInserterOpened: select(external_wp_editor_namespaceObject.store).isInserterOpened(),
      isListViewOpened: select(external_wp_editor_namespaceObject.store).isListViewOpened(),
      mode: select(external_wp_editor_namespaceObject.store).getEditorMode(),
      isRichEditingEnabled: editorSettings.richEditingEnabled,
      hasActiveMetaboxes: select(store).hasMetaBoxes(),
      previousShortcut: select(external_wp_keyboardShortcuts_namespaceObject.store).getAllShortcutKeyCombinations('core/editor/previous-region'),
      nextShortcut: select(external_wp_keyboardShortcuts_namespaceObject.store).getAllShortcutKeyCombinations('core/editor/next-region'),
      showIconLabels: get('core', 'showIconLabels'),
      isDistractionFree: get('core', 'distractionFree'),
      showBlockBreadcrumbs: get('core', 'showBlockBreadcrumbs'),
      // translators: Default label for the Document in the Block Breadcrumb.
      documentLabel: postTypeLabel || (0,external_wp_i18n_namespaceObject._x)('Document', 'noun'),
      hasBlockSelected: !!select(external_wp_blockEditor_namespaceObject.store).getBlockSelectionStart(),
      hasHistory: !!getEditorSettings().onNavigateToPreviousEntityRecord,
      hasBlockBreadcrumbs: get('core', 'showBlockBreadcrumbs')
    };
  }, []);

  // Set the right context for the command palette
  const commandContext = hasBlockSelected ? 'block-selection-edit' : 'post-editor-edit';
  useCommandContext(commandContext);
  const styles = useEditorStyles();

  // Inserter and Sidebars are mutually exclusive
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (sidebarIsOpened && !isHugeViewport) {
      setIsInserterOpened(false);
    }
  }, [isHugeViewport, setIsInserterOpened, sidebarIsOpened]);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (isInserterOpened && !isHugeViewport) {
      closeGeneralSidebar();
    }
  }, [closeGeneralSidebar, isInserterOpened, isHugeViewport]);

  // Local state for save panel.
  // Note 'truthy' callback implies an open panel.
  const [entitiesSavedStatesCallback, setEntitiesSavedStatesCallback] = (0,external_wp_element_namespaceObject.useState)(false);
  const closeEntitiesSavedStates = (0,external_wp_element_namespaceObject.useCallback)(arg => {
    if (typeof entitiesSavedStatesCallback === 'function') {
      entitiesSavedStatesCallback(arg);
    }
    setEntitiesSavedStatesCallback(false);
  }, [entitiesSavedStatesCallback]);

  // We need to add the show-icon-labels class to the body element so it is applied to modals.
  if (showIconLabels) {
    document.body.classList.add('show-icon-labels');
  } else {
    document.body.classList.remove('show-icon-labels');
  }
  const className = classnames_default()('edit-post-layout', 'is-mode-' + mode, {
    'is-sidebar-opened': sidebarIsOpened,
    'has-metaboxes': hasActiveMetaboxes,
    'is-distraction-free': isDistractionFree && isWideViewport,
    'is-entity-save-view-open': !!entitiesSavedStatesCallback,
    'has-block-breadcrumbs': hasBlockBreadcrumbs && !isDistractionFree && isWideViewport
  });
  const secondarySidebarLabel = isListViewOpened ? (0,external_wp_i18n_namespaceObject.__)('Document Overview') : (0,external_wp_i18n_namespaceObject.__)('Block Library');
  const secondarySidebar = () => {
    if (mode === 'visual' && isInserterOpened) {
      return (0,external_React_namespaceObject.createElement)(InserterSidebar, {
        closeGeneralSidebar: closeGeneralSidebar,
        isRightSidebarOpen: sidebarIsOpened
      });
    }
    if (mode === 'visual' && isListViewOpened) {
      return (0,external_React_namespaceObject.createElement)(ListViewSidebar, null);
    }
    return null;
  };
  function onPluginAreaError(name) {
    createErrorNotice((0,external_wp_i18n_namespaceObject.sprintf)( /* translators: %s: plugin name */
    (0,external_wp_i18n_namespaceObject.__)('The "%s" plugin has encountered an error and cannot be rendered.'), name));
  }
  return (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, (0,external_React_namespaceObject.createElement)(FullscreenMode, {
    isActive: isFullscreenActive
  }), (0,external_React_namespaceObject.createElement)(browser_url, {
    hasHistory: hasHistory
  }), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.UnsavedChangesWarning, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.AutosaveMonitor, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.LocalAutosaveMonitor, null), (0,external_React_namespaceObject.createElement)(keyboard_shortcuts, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.EditorKeyboardShortcutsRegister, null), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.EditorKeyboardShortcuts, null), (0,external_React_namespaceObject.createElement)(BlockKeyboardShortcuts, null), (0,external_React_namespaceObject.createElement)(InterfaceSkeleton, {
    isDistractionFree: isDistractionFree && isWideViewport,
    className: className,
    labels: {
      ...interfaceLabels,
      secondarySidebar: secondarySidebarLabel
    },
    header: (0,external_React_namespaceObject.createElement)(header, {
      setEntitiesSavedStatesCallback: setEntitiesSavedStatesCallback,
      initialPost: initialPost
    }),
    editorNotices: (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.EditorNotices, null),
    secondarySidebar: secondarySidebar(),
    sidebar: !isDistractionFree && (0,external_React_namespaceObject.createElement)(ComplementaryArea.Slot, {
      scope: "core"
    }),
    notices: (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.EditorSnackbars, null),
    content: (0,external_React_namespaceObject.createElement)(external_React_namespaceObject.Fragment, null, !isDistractionFree && (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.EditorNotices, null), (mode === 'text' || !isRichEditingEnabled) && (0,external_React_namespaceObject.createElement)(TextEditor, null), !isLargeViewport && (0,external_React_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockToolbar, {
      hideDragHandle: true
    }), isRichEditingEnabled && mode === 'visual' && (0,external_React_namespaceObject.createElement)(VisualEditor, {
      styles: styles
    }), !isDistractionFree && showMetaBoxes && (0,external_React_namespaceObject.createElement)("div", {
      className: "edit-post-layout__metaboxes"
    }, (0,external_React_namespaceObject.createElement)(MetaBoxes, {
      location: "normal"
    }), (0,external_React_namespaceObject.createElement)(MetaBoxes, {
      location: "advanced"
    })), isMobileViewport && sidebarIsOpened && (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.ScrollLock, null)),
    footer: !isDistractionFree && !isMobileViewport && showBlockBreadcrumbs && isRichEditingEnabled && mode === 'visual' && (0,external_React_namespaceObject.createElement)("div", {
      className: "edit-post-layout__footer"
    }, (0,external_React_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockBreadcrumb, {
      rootLabelText: documentLabel
    })),
    actions: (0,external_React_namespaceObject.createElement)(SavePublishPanels, {
      closeEntitiesSavedStates: closeEntitiesSavedStates,
      isEntitiesSavedStatesOpen: entitiesSavedStatesCallback,
      setEntitiesSavedStatesCallback: setEntitiesSavedStatesCallback,
      forceIsDirtyPublishPanel: hasActiveMetaboxes
    }),
    shortcuts: {
      previous: previousShortcut,
      next: nextShortcut
    }
  }), (0,external_React_namespaceObject.createElement)(WelcomeGuide, null), (0,external_React_namespaceObject.createElement)(InitPatternModal, null), (0,external_React_namespaceObject.createElement)(external_wp_plugins_namespaceObject.PluginArea, {
    onError: onPluginAreaError
  }), !isDistractionFree && (0,external_React_namespaceObject.createElement)(settings_sidebar, null));
}
/* harmony default export */ const layout = (Layout);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/editor-initialization/listener-hooks.js
/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


/**
 * This listener hook monitors any change in permalink and updates the view
 * post link in the admin bar.
 */
const useUpdatePostLinkListener = () => {
  const {
    newPermalink
  } = (0,external_wp_data_namespaceObject.useSelect)(select => ({
    newPermalink: select(external_wp_editor_namespaceObject.store).getCurrentPost().link
  }), []);
  const nodeToUpdate = (0,external_wp_element_namespaceObject.useRef)();
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    nodeToUpdate.current = document.querySelector(VIEW_AS_PREVIEW_LINK_SELECTOR) || document.querySelector(VIEW_AS_LINK_SELECTOR);
  }, []);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (!newPermalink || !nodeToUpdate.current) {
      return;
    }
    nodeToUpdate.current.setAttribute('href', newPermalink);
  }, [newPermalink]);
};

;// CONCATENATED MODULE: ./packages/edit-post/build-module/components/editor-initialization/index.js
/**
 * Internal dependencies
 */


/**
 * Data component used for initializing the editor and re-initializes
 * when postId changes or on unmount.
 *
 * @return {null} This is a data component so does not render any ui.
 */
function EditorInitialization() {
  useUpdatePostLinkListener();
  return null;
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/hooks/use-navigate-to-entity-record.js
/**
 * WordPress dependencies
 */




/**
 * A hook that records the 'entity' history in the post editor as a user
 * navigates between editing a post and editing the post template or patterns.
 *
 * Implemented as a stack, so a little similar to the browser history API.
 *
 * Used to control displaying UI elements like the back button.
 *
 * @param {number} initialPostId        The post id of the post when the editor loaded.
 * @param {string} initialPostType      The post type of the post when the editor loaded.
 * @param {string} defaultRenderingMode The rendering mode to switch to when navigating.
 *
 * @return {Object} An object containing the `currentPost` variable and
 *                 `onNavigateToEntityRecord` and `onNavigateToPreviousEntityRecord` functions.
 */
function useNavigateToEntityRecord(initialPostId, initialPostType, defaultRenderingMode) {
  const [postHistory, dispatch] = (0,external_wp_element_namespaceObject.useReducer)((historyState, {
    type,
    post,
    previousRenderingMode
  }) => {
    if (type === 'push') {
      return [...historyState, {
        post,
        previousRenderingMode
      }];
    }
    if (type === 'pop') {
      // Try to leave one item in the history.
      if (historyState.length > 1) {
        return historyState.slice(0, -1);
      }
    }
    return historyState;
  }, [{
    post: {
      postId: initialPostId,
      postType: initialPostType
    }
  }]);
  const {
    post,
    previousRenderingMode
  } = postHistory[postHistory.length - 1];
  const {
    getRenderingMode
  } = (0,external_wp_data_namespaceObject.useSelect)(external_wp_editor_namespaceObject.store);
  const {
    setRenderingMode
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_editor_namespaceObject.store);
  const onNavigateToEntityRecord = (0,external_wp_element_namespaceObject.useCallback)(params => {
    dispatch({
      type: 'push',
      post: {
        postId: params.postId,
        postType: params.postType
      },
      // Save the current rendering mode so we can restore it when navigating back.
      previousRenderingMode: getRenderingMode()
    });
    setRenderingMode(defaultRenderingMode);
  }, [getRenderingMode, setRenderingMode, defaultRenderingMode]);
  const onNavigateToPreviousEntityRecord = (0,external_wp_element_namespaceObject.useCallback)(() => {
    dispatch({
      type: 'pop'
    });
    if (previousRenderingMode) {
      setRenderingMode(previousRenderingMode);
    }
  }, [setRenderingMode, previousRenderingMode]);
  return {
    currentPost: post,
    onNavigateToEntityRecord,
    onNavigateToPreviousEntityRecord: postHistory.length > 1 ? onNavigateToPreviousEntityRecord : undefined
  };
}

;// CONCATENATED MODULE: ./packages/edit-post/build-module/editor.js

/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */





const {
  ExperimentalEditorProvider
} = unlock(external_wp_editor_namespaceObject.privateApis);
function Editor({
  postId: initialPostId,
  postType: initialPostType,
  settings,
  initialEdits,
  ...props
}) {
  const {
    currentPost,
    onNavigateToEntityRecord,
    onNavigateToPreviousEntityRecord
  } = useNavigateToEntityRecord(initialPostId, initialPostType, 'post-only');
  const {
    post,
    template
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    var _getPostType$viewable;
    const {
      getEditedPostTemplate
    } = select(store);
    const {
      getEntityRecord,
      getPostType,
      canUser
    } = select(external_wp_coreData_namespaceObject.store);
    const {
      getEditorSettings
    } = select(external_wp_editor_namespaceObject.store);
    const postObject = getEntityRecord('postType', currentPost.postType, currentPost.postId);
    const supportsTemplateMode = getEditorSettings().supportsTemplateMode;
    const isViewable = (_getPostType$viewable = getPostType(currentPost.postType)?.viewable) !== null && _getPostType$viewable !== void 0 ? _getPostType$viewable : false;
    const canViewTemplate = canUser('read', 'templates');
    return {
      template: supportsTemplateMode && isViewable && canViewTemplate && currentPost.postType !== 'wp_template' ? getEditedPostTemplate() : null,
      post: postObject
    };
  }, [currentPost.postType, currentPost.postId]);
  const editorSettings = (0,external_wp_element_namespaceObject.useMemo)(() => ({
    ...settings,
    onNavigateToEntityRecord,
    onNavigateToPreviousEntityRecord,
    defaultRenderingMode: 'post-only'
  }), [settings, onNavigateToEntityRecord, onNavigateToPreviousEntityRecord]);
  const initialPost = (0,external_wp_element_namespaceObject.useMemo)(() => {
    return {
      type: initialPostType,
      id: initialPostId
    };
  }, [initialPostType, initialPostId]);
  if (!post) {
    return null;
  }
  return (0,external_React_namespaceObject.createElement)(external_wp_components_namespaceObject.SlotFillProvider, null, (0,external_React_namespaceObject.createElement)(ExperimentalEditorProvider, {
    settings: editorSettings,
    post: post,
    initialEdits: initialEdits,
    useSubRegistry: false,
    __unstableTemplate: template,
    ...props
  }, (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.ErrorBoundary, null, (0,external_React_namespaceObject.createElement)(external_wp_commands_namespaceObject.CommandMenu, null), (0,external_React_namespaceObject.createElement)(EditorInitialization, null), (0,external_React_namespaceObject.createElement)(layout, {
    initialPost: initialPost
  })), (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PostLockedModal, null)));
}
/* harmony default export */ const editor = (Editor);

;// CONCATENATED MODULE: ./packages/edit-post/build-module/deprecated.js

/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */

const {
  PluginPostExcerpt
} = unlock(external_wp_editor_namespaceObject.privateApis);
const deprecateSlot = name => {
  external_wp_deprecated_default()(`wp.editPost.${name}`, {
    since: '6.6',
    alternative: `wp.editor.${name}`
  });
};

/* eslint-disable jsdoc/require-param */
/**
 * @see PluginBlockSettingsMenuItem in @wordpress/editor package.
 */
function PluginBlockSettingsMenuItem(props) {
  deprecateSlot('PluginBlockSettingsMenuItem');
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginBlockSettingsMenuItem, {
    ...props
  });
}

/**
 * @see PluginDocumentSettingPanel in @wordpress/editor package.
 */
function PluginDocumentSettingPanel(props) {
  deprecateSlot('PluginDocumentSettingPanel');
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginDocumentSettingPanel, {
    ...props
  });
}

/**
 * @see PluginMoreMenuItem in @wordpress/editor package.
 */
function PluginMoreMenuItem(props) {
  deprecateSlot('PluginMoreMenuItem');
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginMoreMenuItem, {
    ...props
  });
}

/**
 * @see PluginPrePublishPanel in @wordpress/editor package.
 */
function PluginPrePublishPanel(props) {
  deprecateSlot('PluginPrePublishPanel');
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginPrePublishPanel, {
    ...props
  });
}

/**
 * @see PluginPostPublishPanel in @wordpress/editor package.
 */
function PluginPostPublishPanel(props) {
  deprecateSlot('PluginPostPublishPanel');
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginPostPublishPanel, {
    ...props
  });
}

/**
 * @see PluginPostStatusInfo in @wordpress/editor package.
 */
function PluginPostStatusInfo(props) {
  deprecateSlot('PluginPostStatusInfo');
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginPostStatusInfo, {
    ...props
  });
}

/**
 * @see PluginSidebar in @wordpress/editor package.
 */
function PluginSidebar(props) {
  deprecateSlot('PluginSidebar');
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginSidebar, {
    ...props
  });
}

/**
 * @see PluginSidebarMoreMenuItem in @wordpress/editor package.
 */
function PluginSidebarMoreMenuItem(props) {
  deprecateSlot('PluginSidebarMoreMenuItem');
  return (0,external_React_namespaceObject.createElement)(external_wp_editor_namespaceObject.PluginSidebarMoreMenuItem, {
    ...props
  });
}

/**
 * @see PluginPostExcerpt in @wordpress/editor package.
 */
function __experimentalPluginPostExcerpt() {
  external_wp_deprecated_default()('wp.editPost.__experimentalPluginPostExcerpt', {
    since: '6.6',
    hint: 'Core and custom panels can be access programmatically using their panel name.',
    link: 'https://developer.wordpress.org/block-editor/reference-guides/slotfills/plugin-document-setting-panel/#accessing-a-panel-programmatically'
  });
  return PluginPostExcerpt;
}

/* eslint-enable jsdoc/require-param */

;// CONCATENATED MODULE: ./packages/edit-post/build-module/index.js

/**
 * WordPress dependencies
 */









/**
 * Internal dependencies
 */



/**
 * Initializes and returns an instance of Editor.
 *
 * @param {string}  id           Unique identifier for editor instance.
 * @param {string}  postType     Post type of the post to edit.
 * @param {Object}  postId       ID of the post to edit.
 * @param {?Object} settings     Editor settings object.
 * @param {Object}  initialEdits Programmatic edits to apply initially, to be
 *                               considered as non-user-initiated (bypass for
 *                               unsaved changes prompt).
 */
function initializeEditor(id, postType, postId, settings, initialEdits) {
  const isMediumOrBigger = window.matchMedia('(min-width: 782px)').matches;
  const target = document.getElementById(id);
  const root = (0,external_wp_element_namespaceObject.createRoot)(target);
  (0,external_wp_data_namespaceObject.dispatch)(external_wp_preferences_namespaceObject.store).setDefaults('core/edit-post', {
    fullscreenMode: true,
    themeStyles: true,
    welcomeGuide: true,
    welcomeGuideTemplate: true
  });
  (0,external_wp_data_namespaceObject.dispatch)(external_wp_preferences_namespaceObject.store).setDefaults('core', {
    allowRightClickOverrides: true,
    editorMode: 'visual',
    fixedToolbar: false,
    hiddenBlockTypes: [],
    inactivePanels: [],
    openPanels: ['post-status'],
    showBlockBreadcrumbs: true,
    showIconLabels: false,
    showListViewByDefault: false,
    isPublishSidebarEnabled: true
  });
  (0,external_wp_data_namespaceObject.dispatch)(external_wp_blocks_namespaceObject.store).reapplyBlockTypeFilters();

  // Check if the block list view should be open by default.
  // If `distractionFree` mode is enabled, the block list view should not be open.
  // This behavior is disabled for small viewports.
  if (isMediumOrBigger && (0,external_wp_data_namespaceObject.select)(external_wp_preferences_namespaceObject.store).get('core', 'showListViewByDefault') && !(0,external_wp_data_namespaceObject.select)(external_wp_preferences_namespaceObject.store).get('core', 'distractionFree')) {
    (0,external_wp_data_namespaceObject.dispatch)(external_wp_editor_namespaceObject.store).setIsListViewOpened(true);
  }
  (0,external_wp_blockLibrary_namespaceObject.registerCoreBlocks)();
  (0,external_wp_widgets_namespaceObject.registerLegacyWidgetBlock)({
    inserter: false
  });
  (0,external_wp_widgets_namespaceObject.registerWidgetGroupBlock)({
    inserter: false
  });
  if (true) {
    (0,external_wp_blockLibrary_namespaceObject.__experimentalRegisterExperimentalCoreBlocks)({
      enableFSEBlocks: settings.__unstableEnableFullSiteEditingBlocks
    });
  }

  // Show a console log warning if the browser is not in Standards rendering mode.
  const documentMode = document.compatMode === 'CSS1Compat' ? 'Standards' : 'Quirks';
  if (documentMode !== 'Standards') {
    // eslint-disable-next-line no-console
    console.warn("Your browser is using Quirks Mode. \nThis can cause rendering issues such as blocks overlaying meta boxes in the editor. Quirks Mode can be triggered by PHP errors or HTML code appearing before the opening <!DOCTYPE html>. Try checking the raw page source or your site's PHP error log and resolving errors there, removing any HTML before the doctype, or disabling plugins.");
  }

  // This is a temporary fix for a couple of issues specific to Webkit on iOS.
  // Without this hack the browser scrolls the mobile toolbar off-screen.
  // Once supported in Safari we can replace this in favor of preventScroll.
  // For details see issue #18632 and PR #18686
  // Specifically, we scroll `interface-interface-skeleton__body` to enable a fixed top toolbar.
  // But Mobile Safari forces the `html` element to scroll upwards, hiding the toolbar.

  const isIphone = window.navigator.userAgent.indexOf('iPhone') !== -1;
  if (isIphone) {
    window.addEventListener('scroll', event => {
      const editorScrollContainer = document.getElementsByClassName('interface-interface-skeleton__body')[0];
      if (event.target === document) {
        // Scroll element into view by scrolling the editor container by the same amount
        // that Mobile Safari tried to scroll the html element upwards.
        if (window.scrollY > 100) {
          editorScrollContainer.scrollTop = editorScrollContainer.scrollTop + window.scrollY;
        }
        // Undo unwanted scroll on html element, but only in the visual editor.
        if (document.getElementsByClassName('is-mode-visual')[0]) {
          window.scrollTo(0, 0);
        }
      }
    });
  }

  // Prevent the default browser action for files dropped outside of dropzones.
  window.addEventListener('dragover', e => e.preventDefault(), false);
  window.addEventListener('drop', e => e.preventDefault(), false);
  root.render((0,external_React_namespaceObject.createElement)(editor, {
    settings: settings,
    postId: postId,
    postType: postType,
    initialEdits: initialEdits
  }));
  return root;
}

/**
 * Used to reinitialize the editor after an error. Now it's a deprecated noop function.
 */
function reinitializeEditor() {
  external_wp_deprecated_default()('wp.editPost.reinitializeEditor', {
    since: '6.2',
    version: '6.3'
  });
}





})();

(window.wp = window.wp || {}).editPost = __webpack_exports__;
/******/ })()
;