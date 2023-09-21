/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
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
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "privateApis": () => (/* reexport */ privateApis)
});

// NAMESPACE OBJECT: ./packages/patterns/build-module/store/actions.js
var actions_namespaceObject = {};
__webpack_require__.r(actions_namespaceObject);
__webpack_require__.d(actions_namespaceObject, {
  "__experimentalConvertSyncedPatternToStatic": () => (__experimentalConvertSyncedPatternToStatic),
  "__experimentalCreatePattern": () => (__experimentalCreatePattern),
  "__experimentalSetEditingPattern": () => (__experimentalSetEditingPattern)
});

// NAMESPACE OBJECT: ./packages/patterns/build-module/store/selectors.js
var selectors_namespaceObject = {};
__webpack_require__.r(selectors_namespaceObject);
__webpack_require__.d(selectors_namespaceObject, {
  "__experimentalIsEditingPattern": () => (__experimentalIsEditingPattern)
});

;// CONCATENATED MODULE: external ["wp","data"]
const external_wp_data_namespaceObject = window["wp"]["data"];
;// CONCATENATED MODULE: ./packages/patterns/build-module/store/reducer.js
/**
 * WordPress dependencies
 */

function isEditingPattern(state = {}, action) {
  if (action?.type === 'SET_EDITING_PATTERN') {
    return {
      ...state,
      [action.clientId]: action.isEditing
    };
  }
  return state;
}
/* harmony default export */ const reducer = ((0,external_wp_data_namespaceObject.combineReducers)({
  isEditingPattern
}));

;// CONCATENATED MODULE: external ["wp","blocks"]
const external_wp_blocks_namespaceObject = window["wp"]["blocks"];
;// CONCATENATED MODULE: external ["wp","coreData"]
const external_wp_coreData_namespaceObject = window["wp"]["coreData"];
;// CONCATENATED MODULE: external ["wp","blockEditor"]
const external_wp_blockEditor_namespaceObject = window["wp"]["blockEditor"];
;// CONCATENATED MODULE: ./packages/patterns/build-module/store/actions.js
/**
 * WordPress dependencies
 */





/**
 * Returns a generator converting one or more static blocks into a pattern, or creating a new empty pattern.
 *
 * @param {string}             title     Pattern title.
 * @param {'full'|'unsynced'}  syncType  They way block is synced, 'full' or 'unsynced'.
 * @param {string[]|undefined} clientIds Optional client IDs of blocks to convert to pattern.
 */
const __experimentalCreatePattern = (title, syncType, clientIds) => async ({
  registry,
  dispatch
}) => {
  const meta = syncType === 'unsynced' ? {
    wp_pattern_sync_status: syncType
  } : undefined;
  const reusableBlock = {
    title,
    content: clientIds ? (0,external_wp_blocks_namespaceObject.serialize)(registry.select(external_wp_blockEditor_namespaceObject.store).getBlocksByClientId(clientIds)) : undefined,
    status: 'publish',
    meta
  };
  const updatedRecord = await registry.dispatch(external_wp_coreData_namespaceObject.store).saveEntityRecord('postType', 'wp_block', reusableBlock);
  if (syncType === 'unsynced' || !clientIds) {
    return updatedRecord;
  }
  const newBlock = (0,external_wp_blocks_namespaceObject.createBlock)('core/block', {
    ref: updatedRecord.id
  });
  registry.dispatch(external_wp_blockEditor_namespaceObject.store).replaceBlocks(clientIds, newBlock);
  dispatch.__experimentalSetEditingPattern(newBlock.clientId, true);
  return updatedRecord;
};

/**
 * Returns a generator converting a synced pattern block into a static block.
 *
 * @param {string} clientId The client ID of the block to attach.
 */
const __experimentalConvertSyncedPatternToStatic = clientId => ({
  registry
}) => {
  const oldBlock = registry.select(external_wp_blockEditor_namespaceObject.store).getBlock(clientId);
  const pattern = registry.select('core').getEditedEntityRecord('postType', 'wp_block', oldBlock.attributes.ref);
  const newBlocks = (0,external_wp_blocks_namespaceObject.parse)(typeof pattern.content === 'function' ? pattern.content(pattern) : pattern.content);
  registry.dispatch(external_wp_blockEditor_namespaceObject.store).replaceBlocks(oldBlock.clientId, newBlocks);
};

/**
 * Returns an action descriptor for SET_EDITING_PATTERN action.
 *
 * @param {string}  clientId  The clientID of the pattern to target.
 * @param {boolean} isEditing Whether the block should be in editing state.
 * @return {Object} Action descriptor.
 */
function __experimentalSetEditingPattern(clientId, isEditing) {
  return {
    type: 'SET_EDITING_PATTERN',
    clientId,
    isEditing
  };
}

;// CONCATENATED MODULE: ./packages/patterns/build-module/store/constants.js
/**
 * Module Constants
 */
const STORE_NAME = 'core/patterns';

;// CONCATENATED MODULE: ./packages/patterns/build-module/store/selectors.js
/**
 * Returns true if pattern is in the editing state.
 *
 * @param {Object} state    Global application state.
 * @param {number} clientId the clientID of the block.
 * @return {boolean} Whether the pattern is in the editing state.
 */
function __experimentalIsEditingPattern(state, clientId) {
  return state.isEditingPattern[clientId];
}

;// CONCATENATED MODULE: ./packages/patterns/build-module/store/index.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */





/**
 * Post editor data store configuration.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#registerStore
 *
 * @type {Object}
 */
const storeConfig = {
  reducer: reducer,
  selectors: selectors_namespaceObject,
  actions: actions_namespaceObject
};

/**
 * Store definition for the editor namespace.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 *
 * @type {Object}
 */
const store = (0,external_wp_data_namespaceObject.createReduxStore)(STORE_NAME, {
  ...storeConfig
});
(0,external_wp_data_namespaceObject.register)(store);

;// CONCATENATED MODULE: external ["wp","privateApis"]
const external_wp_privateApis_namespaceObject = window["wp"]["privateApis"];
;// CONCATENATED MODULE: ./packages/patterns/build-module/lock-unlock.js
/**
 * WordPress dependencies
 */

const {
  lock,
  unlock
} = (0,external_wp_privateApis_namespaceObject.__dangerousOptInToUnstableAPIsOnlyForCoreModules)('I know using unstable features means my plugin or theme will inevitably break on the next WordPress release.', '@wordpress/patterns');

;// CONCATENATED MODULE: external ["wp","element"]
const external_wp_element_namespaceObject = window["wp"]["element"];
;// CONCATENATED MODULE: external ["wp","components"]
const external_wp_components_namespaceObject = window["wp"]["components"];
;// CONCATENATED MODULE: external ["wp","i18n"]
const external_wp_i18n_namespaceObject = window["wp"]["i18n"];
;// CONCATENATED MODULE: external ["wp","notices"]
const external_wp_notices_namespaceObject = window["wp"]["notices"];
;// CONCATENATED MODULE: ./packages/patterns/build-module/components/create-pattern-modal.js

/**
 * WordPress dependencies
 */





const USER_PATTERN_CATEGORY = 'my-patterns';
const SYNC_TYPES = {
  full: undefined,
  unsynced: 'unsynced'
};

/**
 * Internal dependencies
 */

function CreatePatternModal({
  onSuccess,
  onError,
  clientIds,
  onClose,
  className = 'patterns-menu-items__convert-modal'
}) {
  const [syncType, setSyncType] = (0,external_wp_element_namespaceObject.useState)(SYNC_TYPES.full);
  const [title, setTitle] = (0,external_wp_element_namespaceObject.useState)('');
  const {
    __experimentalCreatePattern: createPattern
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  const {
    createErrorNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  const onCreate = (0,external_wp_element_namespaceObject.useCallback)(async function (patternTitle, sync) {
    try {
      const newPattern = await createPattern(patternTitle, sync, clientIds);
      onSuccess({
        pattern: newPattern,
        categoryId: USER_PATTERN_CATEGORY
      });
    } catch (error) {
      createErrorNotice(error.message, {
        type: 'snackbar',
        id: 'convert-to-pattern-error'
      });
      onError();
    }
  }, [createPattern, clientIds, onSuccess, createErrorNotice, onError]);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Modal, {
    title: (0,external_wp_i18n_namespaceObject.__)('Create pattern'),
    onRequestClose: () => {
      onClose();
      setTitle('');
    },
    overlayClassName: className
  }, (0,external_wp_element_namespaceObject.createElement)("form", {
    onSubmit: event => {
      event.preventDefault();
      onCreate(title, syncType);
      setTitle('');
    }
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: "5"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.TextControl, {
    __nextHasNoMarginBottom: true,
    label: (0,external_wp_i18n_namespaceObject.__)('Name'),
    value: title,
    onChange: setTitle,
    placeholder: (0,external_wp_i18n_namespaceObject.__)('My pattern')
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ToggleControl, {
    label: (0,external_wp_i18n_namespaceObject.__)('Synced'),
    help: (0,external_wp_i18n_namespaceObject.__)('Editing the pattern will update it anywhere it is used.'),
    checked: !syncType,
    onChange: () => {
      setSyncType(syncType === SYNC_TYPES.full ? SYNC_TYPES.unsynced : SYNC_TYPES.full);
    }
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, {
    justify: "right"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "tertiary",
    onClick: () => {
      onClose();
      setTitle('');
    }
  }, (0,external_wp_i18n_namespaceObject.__)('Cancel')), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "primary",
    type: "submit"
  }, (0,external_wp_i18n_namespaceObject.__)('Create'))))));
}

;// CONCATENATED MODULE: external ["wp","primitives"]
const external_wp_primitives_namespaceObject = window["wp"]["primitives"];
;// CONCATENATED MODULE: ./packages/icons/build-module/library/symbol.js

/**
 * WordPress dependencies
 */

const symbol = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M21.3 10.8l-5.6-5.6c-.7-.7-1.8-.7-2.5 0l-5.6 5.6c-.7.7-.7 1.8 0 2.5l5.6 5.6c.3.3.8.5 1.2.5s.9-.2 1.2-.5l5.6-5.6c.8-.7.8-1.9.1-2.5zm-1 1.4l-5.6 5.6c-.1.1-.3.1-.4 0l-5.6-5.6c-.1-.1-.1-.3 0-.4l5.6-5.6s.1-.1.2-.1.1 0 .2.1l5.6 5.6c.1.1.1.3 0 .4zm-16.6-.4L10 5.5l-1-1-6.3 6.3c-.7.7-.7 1.8 0 2.5L9 19.5l1.1-1.1-6.3-6.3c-.2 0-.2-.2-.1-.3z"
}));
/* harmony default export */ const library_symbol = (symbol);

;// CONCATENATED MODULE: ./packages/patterns/build-module/components/pattern-convert-button.js

/**
 * WordPress dependencies
 */









/**
 * Internal dependencies
 */


/**
 * Menu control to convert block(s) to a pattern block.
 *
 * @param {Object}   props              Component props.
 * @param {string[]} props.clientIds    Client ids of selected blocks.
 * @param {string}   props.rootClientId ID of the currently selected top-level block.
 * @return {import('@wordpress/element').WPComponent} The menu control or null.
 */
function PatternConvertButton({
  clientIds,
  rootClientId
}) {
  const {
    createSuccessNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  const [isModalOpen, setIsModalOpen] = (0,external_wp_element_namespaceObject.useState)(false);
  const canConvert = (0,external_wp_data_namespaceObject.useSelect)(select => {
    var _getBlocksByClientId;
    const {
      canUser
    } = select(external_wp_coreData_namespaceObject.store);
    const {
      getBlocksByClientId,
      canInsertBlockType,
      getBlockRootClientId
    } = select(external_wp_blockEditor_namespaceObject.store);
    const rootId = rootClientId || (clientIds.length > 0 ? getBlockRootClientId(clientIds[0]) : undefined);
    const blocks = (_getBlocksByClientId = getBlocksByClientId(clientIds)) !== null && _getBlocksByClientId !== void 0 ? _getBlocksByClientId : [];
    const isReusable = blocks.length === 1 && blocks[0] && (0,external_wp_blocks_namespaceObject.isReusableBlock)(blocks[0]) && !!select(external_wp_coreData_namespaceObject.store).getEntityRecord('postType', 'wp_block', blocks[0].attributes.ref);
    const _canConvert =
    // Hide when this is already a synced pattern.
    !isReusable &&
    // Hide when patterns are disabled.
    canInsertBlockType('core/block', rootId) && blocks.every(block =>
    // Guard against the case where a regular block has *just* been converted.
    !!block &&
    // Hide on invalid blocks.
    block.isValid &&
    // Hide when block doesn't support being made into a pattern.
    (0,external_wp_blocks_namespaceObject.hasBlockSupport)(block.name, 'reusable', true)) &&
    // Hide when current doesn't have permission to do that.
    !!canUser('create', 'blocks');
    return _canConvert;
  }, [clientIds, rootClientId]);
  if (!canConvert) {
    return null;
  }
  const handleSuccess = ({
    pattern
  }) => {
    createSuccessNotice(pattern.wp_pattern_sync_status === 'unsynced' ? (0,external_wp_i18n_namespaceObject.sprintf)(
    // translators: %s: the name the user has given to the pattern.
    (0,external_wp_i18n_namespaceObject.__)('Unsynced Pattern created: %s'), pattern.title.raw) : (0,external_wp_i18n_namespaceObject.sprintf)(
    // translators: %s: the name the user has given to the pattern.
    (0,external_wp_i18n_namespaceObject.__)('Synced Pattern created: %s'), pattern.title.raw), {
      type: 'snackbar',
      id: 'convert-to-pattern-success'
    });
    setIsModalOpen(false);
  };
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    icon: library_symbol,
    onClick: () => setIsModalOpen(true),
    "aria-expanded": isModalOpen,
    "aria-haspopup": "dialog"
  }, (0,external_wp_i18n_namespaceObject.__)('Create pattern')), isModalOpen && (0,external_wp_element_namespaceObject.createElement)(CreatePatternModal, {
    clientIds: clientIds,
    onSuccess: pattern => {
      handleSuccess(pattern);
    },
    onError: () => {
      setIsModalOpen(false);
    },
    onClose: () => {
      setIsModalOpen(false);
    }
  }));
}

;// CONCATENATED MODULE: external ["wp","url"]
const external_wp_url_namespaceObject = window["wp"]["url"];
;// CONCATENATED MODULE: ./packages/patterns/build-module/components/patterns-manage-button.js

/**
 * WordPress dependencies
 */








/**
 * Internal dependencies
 */

function PatternsManageButton({
  clientId
}) {
  const {
    canRemove,
    isVisible,
    innerBlockCount,
    managePatternsUrl
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getBlock,
      canRemoveBlock,
      getBlockCount,
      getSettings
    } = select(external_wp_blockEditor_namespaceObject.store);
    const {
      canUser
    } = select(external_wp_coreData_namespaceObject.store);
    const reusableBlock = getBlock(clientId);
    const isBlockTheme = getSettings().__unstableIsBlockBasedTheme;
    return {
      canRemove: canRemoveBlock(clientId),
      isVisible: !!reusableBlock && (0,external_wp_blocks_namespaceObject.isReusableBlock)(reusableBlock) && !!canUser('update', 'blocks', reusableBlock.attributes.ref),
      innerBlockCount: getBlockCount(clientId),
      // The site editor and templates both check whether the user
      // has edit_theme_options capabilities. We can leverage that here
      // and omit the manage patterns link if the user can't access it.
      managePatternsUrl: isBlockTheme && canUser('read', 'templates') ? (0,external_wp_url_namespaceObject.addQueryArgs)('site-editor.php', {
        path: '/patterns'
      }) : (0,external_wp_url_namespaceObject.addQueryArgs)('edit.php', {
        post_type: 'wp_block'
      })
    };
  }, [clientId]);
  const {
    __experimentalConvertSyncedPatternToStatic: convertBlockToStatic
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  if (!isVisible) {
    return null;
  }
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    href: managePatternsUrl
  }, (0,external_wp_i18n_namespaceObject.__)('Manage patterns')), canRemove && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    onClick: () => convertBlockToStatic(clientId)
  }, innerBlockCount > 1 ? (0,external_wp_i18n_namespaceObject.__)('Detach patterns') : (0,external_wp_i18n_namespaceObject.__)('Detach pattern')));
}
/* harmony default export */ const patterns_manage_button = (PatternsManageButton);

;// CONCATENATED MODULE: ./packages/patterns/build-module/components/index.js

/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


function PatternsMenuItems({
  rootClientId
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockSettingsMenuControls, null, ({
    selectedClientIds
  }) => (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(PatternConvertButton, {
    clientIds: selectedClientIds,
    rootClientId: rootClientId
  }), selectedClientIds.length === 1 && (0,external_wp_element_namespaceObject.createElement)(patterns_manage_button, {
    clientId: selectedClientIds[0]
  })));
}

;// CONCATENATED MODULE: ./packages/patterns/build-module/private-apis.js
/**
 * Internal dependencies
 */



const privateApis = {};
lock(privateApis, {
  CreatePatternModal: CreatePatternModal,
  PatternsMenuItems: PatternsMenuItems
});

;// CONCATENATED MODULE: ./packages/patterns/build-module/index.js
/**
 * Internal dependencies
 */



(window.wp = window.wp || {}).patterns = __webpack_exports__;
/******/ })()
;