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
  MediaUploadProvider: () => (/* reexport */ provider),
  UploadError: () => (/* reexport */ UploadError),
  store: () => (/* reexport */ store)
});

// NAMESPACE OBJECT: ./packages/upload-media/build-module/store/selectors.js
var selectors_namespaceObject = {};
__webpack_require__.r(selectors_namespaceObject);
__webpack_require__.d(selectors_namespaceObject, {
  getItems: () => (getItems),
  getSettings: () => (getSettings),
  isUploading: () => (isUploading),
  isUploadingById: () => (isUploadingById),
  isUploadingByUrl: () => (isUploadingByUrl)
});

// NAMESPACE OBJECT: ./packages/upload-media/build-module/store/private-selectors.js
var private_selectors_namespaceObject = {};
__webpack_require__.r(private_selectors_namespaceObject);
__webpack_require__.d(private_selectors_namespaceObject, {
  getAllItems: () => (getAllItems),
  getBlobUrls: () => (getBlobUrls),
  getItem: () => (getItem),
  getPausedUploadForPost: () => (getPausedUploadForPost),
  isBatchUploaded: () => (isBatchUploaded),
  isPaused: () => (isPaused),
  isUploadingToPost: () => (isUploadingToPost)
});

// NAMESPACE OBJECT: ./packages/upload-media/build-module/store/actions.js
var actions_namespaceObject = {};
__webpack_require__.r(actions_namespaceObject);
__webpack_require__.d(actions_namespaceObject, {
  addItems: () => (addItems),
  cancelItem: () => (cancelItem)
});

// NAMESPACE OBJECT: ./packages/upload-media/build-module/store/private-actions.js
var private_actions_namespaceObject = {};
__webpack_require__.r(private_actions_namespaceObject);
__webpack_require__.d(private_actions_namespaceObject, {
  addItem: () => (addItem),
  finishOperation: () => (finishOperation),
  pauseQueue: () => (pauseQueue),
  prepareItem: () => (prepareItem),
  processItem: () => (processItem),
  removeItem: () => (removeItem),
  resumeQueue: () => (resumeQueue),
  revokeBlobUrls: () => (revokeBlobUrls),
  updateSettings: () => (updateSettings),
  uploadItem: () => (uploadItem)
});

;// external ["wp","data"]
const external_wp_data_namespaceObject = window["wp"]["data"];
;// ./packages/upload-media/build-module/store/types.js
let Type = /*#__PURE__*/function (Type) {
  Type["Unknown"] = "REDUX_UNKNOWN";
  Type["Add"] = "ADD_ITEM";
  Type["Prepare"] = "PREPARE_ITEM";
  Type["Cancel"] = "CANCEL_ITEM";
  Type["Remove"] = "REMOVE_ITEM";
  Type["PauseItem"] = "PAUSE_ITEM";
  Type["ResumeItem"] = "RESUME_ITEM";
  Type["PauseQueue"] = "PAUSE_QUEUE";
  Type["ResumeQueue"] = "RESUME_QUEUE";
  Type["OperationStart"] = "OPERATION_START";
  Type["OperationFinish"] = "OPERATION_FINISH";
  Type["AddOperations"] = "ADD_OPERATIONS";
  Type["CacheBlobUrl"] = "CACHE_BLOB_URL";
  Type["RevokeBlobUrls"] = "REVOKE_BLOB_URLS";
  Type["UpdateSettings"] = "UPDATE_SETTINGS";
  return Type;
}({});

// Must match the Attachment type from the media-utils package.

let ItemStatus = /*#__PURE__*/function (ItemStatus) {
  ItemStatus["Processing"] = "PROCESSING";
  ItemStatus["Paused"] = "PAUSED";
  return ItemStatus;
}({});
let OperationType = /*#__PURE__*/function (OperationType) {
  OperationType["Prepare"] = "PREPARE";
  OperationType["Upload"] = "UPLOAD";
  return OperationType;
}({});

;// ./packages/upload-media/build-module/store/reducer.js
/* wp:polyfill */
/**
 * Internal dependencies
 */

const noop = () => {};
const DEFAULT_STATE = {
  queue: [],
  queueStatus: 'active',
  blobUrls: {},
  settings: {
    mediaUpload: noop
  }
};
function reducer(state = DEFAULT_STATE, action = {
  type: Type.Unknown
}) {
  switch (action.type) {
    case Type.PauseQueue:
      {
        return {
          ...state,
          queueStatus: 'paused'
        };
      }
    case Type.ResumeQueue:
      {
        return {
          ...state,
          queueStatus: 'active'
        };
      }
    case Type.Add:
      return {
        ...state,
        queue: [...state.queue, action.item]
      };
    case Type.Cancel:
      return {
        ...state,
        queue: state.queue.map(item => item.id === action.id ? {
          ...item,
          error: action.error
        } : item)
      };
    case Type.Remove:
      return {
        ...state,
        queue: state.queue.filter(item => item.id !== action.id)
      };
    case Type.OperationStart:
      {
        return {
          ...state,
          queue: state.queue.map(item => item.id === action.id ? {
            ...item,
            currentOperation: action.operation
          } : item)
        };
      }
    case Type.AddOperations:
      return {
        ...state,
        queue: state.queue.map(item => {
          if (item.id !== action.id) {
            return item;
          }
          return {
            ...item,
            operations: [...(item.operations || []), ...action.operations]
          };
        })
      };
    case Type.OperationFinish:
      return {
        ...state,
        queue: state.queue.map(item => {
          if (item.id !== action.id) {
            return item;
          }
          const operations = item.operations ? item.operations.slice(1) : [];

          // Prevent an empty object if there's no attachment data.
          const attachment = item.attachment || action.item.attachment ? {
            ...item.attachment,
            ...action.item.attachment
          } : undefined;
          return {
            ...item,
            currentOperation: undefined,
            operations,
            ...action.item,
            attachment,
            additionalData: {
              ...item.additionalData,
              ...action.item.additionalData
            }
          };
        })
      };
    case Type.CacheBlobUrl:
      {
        const blobUrls = state.blobUrls[action.id] || [];
        return {
          ...state,
          blobUrls: {
            ...state.blobUrls,
            [action.id]: [...blobUrls, action.blobUrl]
          }
        };
      }
    case Type.RevokeBlobUrls:
      {
        const newBlobUrls = {
          ...state.blobUrls
        };
        delete newBlobUrls[action.id];
        return {
          ...state,
          blobUrls: newBlobUrls
        };
      }
    case Type.UpdateSettings:
      {
        return {
          ...state,
          settings: {
            ...state.settings,
            ...action.settings
          }
        };
      }
  }
  return state;
}
/* harmony default export */ const store_reducer = (reducer);

;// ./packages/upload-media/build-module/store/selectors.js
/* wp:polyfill */
/**
 * Internal dependencies
 */

/**
 * Returns all items currently being uploaded.
 *
 * @param state Upload state.
 *
 * @return Queue items.
 */
function getItems(state) {
  return state.queue;
}

/**
 * Determines whether any upload is currently in progress.
 *
 * @param state Upload state.
 *
 * @return Whether any upload is currently in progress.
 */
function isUploading(state) {
  return state.queue.length >= 1;
}

/**
 * Determines whether an upload is currently in progress given an attachment URL.
 *
 * @param state Upload state.
 * @param url   Attachment URL.
 *
 * @return Whether upload is currently in progress for the given attachment.
 */
function isUploadingByUrl(state, url) {
  return state.queue.some(item => item.attachment?.url === url || item.sourceUrl === url);
}

/**
 * Determines whether an upload is currently in progress given an attachment ID.
 *
 * @param state        Upload state.
 * @param attachmentId Attachment ID.
 *
 * @return Whether upload is currently in progress for the given attachment.
 */
function isUploadingById(state, attachmentId) {
  return state.queue.some(item => item.attachment?.id === attachmentId || item.sourceAttachmentId === attachmentId);
}

/**
 * Returns the media upload settings.
 *
 * @param state Upload state.
 *
 * @return Settings
 */
function getSettings(state) {
  return state.settings;
}

;// ./packages/upload-media/build-module/store/private-selectors.js
/* wp:polyfill */
/**
 * Internal dependencies
 */


/**
 * Returns all items currently being uploaded.
 *
 * @param state Upload state.
 *
 * @return Queue items.
 */
function getAllItems(state) {
  return state.queue;
}

/**
 * Returns a specific item given its unique ID.
 *
 * @param state Upload state.
 * @param id    Item ID.
 *
 * @return Queue item.
 */
function getItem(state, id) {
  return state.queue.find(item => item.id === id);
}

/**
 * Determines whether a batch has been successfully uploaded, given its unique ID.
 *
 * @param state   Upload state.
 * @param batchId Batch ID.
 *
 * @return Whether a batch has been uploaded.
 */
function isBatchUploaded(state, batchId) {
  const batchItems = state.queue.filter(item => batchId === item.batchId);
  return batchItems.length === 0;
}

/**
 * Determines whether an upload is currently in progress given a post or attachment ID.
 *
 * @param state              Upload state.
 * @param postOrAttachmentId Post ID or attachment ID.
 *
 * @return Whether upload is currently in progress for the given post or attachment.
 */
function isUploadingToPost(state, postOrAttachmentId) {
  return state.queue.some(item => item.currentOperation === OperationType.Upload && item.additionalData.post === postOrAttachmentId);
}

/**
 * Returns the next paused upload for a given post or attachment ID.
 *
 * @param state              Upload state.
 * @param postOrAttachmentId Post ID or attachment ID.
 *
 * @return Paused item.
 */
function getPausedUploadForPost(state, postOrAttachmentId) {
  return state.queue.find(item => item.status === ItemStatus.Paused && item.additionalData.post === postOrAttachmentId);
}

/**
 * Determines whether uploading is currently paused.
 *
 * @param state Upload state.
 *
 * @return Whether uploading is currently paused.
 */
function isPaused(state) {
  return state.queueStatus === 'paused';
}

/**
 * Returns all cached blob URLs for a given item ID.
 *
 * @param state Upload state.
 * @param id    Item ID
 *
 * @return List of blob URLs.
 */
function getBlobUrls(state, id) {
  return state.blobUrls[id] || [];
}

;// ./node_modules/uuid/dist/esm-browser/native.js
const randomUUID = typeof crypto !== 'undefined' && crypto.randomUUID && crypto.randomUUID.bind(crypto);
/* harmony default export */ const esm_browser_native = ({
  randomUUID
});
;// ./node_modules/uuid/dist/esm-browser/rng.js
// Unique ID creation requires a high quality random # generator. In the browser we therefore
// require the crypto API and do not support built-in fallback to lower quality random number
// generators (like Math.random()).
let getRandomValues;
const rnds8 = new Uint8Array(16);
function rng() {
  // lazy load so that environments that need to polyfill have a chance to do so
  if (!getRandomValues) {
    // getRandomValues needs to be invoked in a context where "this" is a Crypto implementation.
    getRandomValues = typeof crypto !== 'undefined' && crypto.getRandomValues && crypto.getRandomValues.bind(crypto);

    if (!getRandomValues) {
      throw new Error('crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported');
    }
  }

  return getRandomValues(rnds8);
}
;// ./node_modules/uuid/dist/esm-browser/stringify.js

/**
 * Convert array of 16 byte values to UUID string format of the form:
 * XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
 */

const byteToHex = [];

for (let i = 0; i < 256; ++i) {
  byteToHex.push((i + 0x100).toString(16).slice(1));
}

function unsafeStringify(arr, offset = 0) {
  // Note: Be careful editing this code!  It's been tuned for performance
  // and works in ways you may not expect. See https://github.com/uuidjs/uuid/pull/434
  return byteToHex[arr[offset + 0]] + byteToHex[arr[offset + 1]] + byteToHex[arr[offset + 2]] + byteToHex[arr[offset + 3]] + '-' + byteToHex[arr[offset + 4]] + byteToHex[arr[offset + 5]] + '-' + byteToHex[arr[offset + 6]] + byteToHex[arr[offset + 7]] + '-' + byteToHex[arr[offset + 8]] + byteToHex[arr[offset + 9]] + '-' + byteToHex[arr[offset + 10]] + byteToHex[arr[offset + 11]] + byteToHex[arr[offset + 12]] + byteToHex[arr[offset + 13]] + byteToHex[arr[offset + 14]] + byteToHex[arr[offset + 15]];
}

function stringify(arr, offset = 0) {
  const uuid = unsafeStringify(arr, offset); // Consistency check for valid UUID.  If this throws, it's likely due to one
  // of the following:
  // - One or more input array values don't map to a hex octet (leading to
  // "undefined" in the uuid)
  // - Invalid input values for the RFC `version` or `variant` fields

  if (!validate(uuid)) {
    throw TypeError('Stringified UUID is invalid');
  }

  return uuid;
}

/* harmony default export */ const esm_browser_stringify = ((/* unused pure expression or super */ null && (stringify)));
;// ./node_modules/uuid/dist/esm-browser/v4.js




function v4(options, buf, offset) {
  if (esm_browser_native.randomUUID && !buf && !options) {
    return esm_browser_native.randomUUID();
  }

  options = options || {};
  const rnds = options.random || (options.rng || rng)(); // Per 4.4, set bits for version and `clock_seq_hi_and_reserved`

  rnds[6] = rnds[6] & 0x0f | 0x40;
  rnds[8] = rnds[8] & 0x3f | 0x80; // Copy bytes to buffer, if provided

  if (buf) {
    offset = offset || 0;

    for (let i = 0; i < 16; ++i) {
      buf[offset + i] = rnds[i];
    }

    return buf;
  }

  return unsafeStringify(rnds);
}

/* harmony default export */ const esm_browser_v4 = (v4);
;// external ["wp","i18n"]
const external_wp_i18n_namespaceObject = window["wp"]["i18n"];
;// ./packages/upload-media/build-module/upload-error.js
/**
 * MediaError class.
 *
 * Small wrapper around the `Error` class
 * to hold an error code and a reference to a file object.
 */
class UploadError extends Error {
  constructor({
    code,
    message,
    file,
    cause
  }) {
    super(message, {
      cause
    });
    Object.setPrototypeOf(this, new.target.prototype);
    this.code = code;
    this.file = file;
  }
}

;// ./packages/upload-media/build-module/validate-mime-type.js
/* wp:polyfill */
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


/**
 * Verifies if the caller (e.g. a block) supports this mime type.
 *
 * @param file         File object.
 * @param allowedTypes List of allowed mime types.
 */
function validateMimeType(file, allowedTypes) {
  if (!allowedTypes) {
    return;
  }

  // Allowed type specified by consumer.
  const isAllowedType = allowedTypes.some(allowedType => {
    // If a complete mimetype is specified verify if it matches exactly the mime type of the file.
    if (allowedType.includes('/')) {
      return allowedType === file.type;
    }
    // Otherwise a general mime type is used, and we should verify if the file mimetype starts with it.
    return file.type.startsWith(`${allowedType}/`);
  });
  if (file.type && !isAllowedType) {
    throw new UploadError({
      code: 'MIME_TYPE_NOT_SUPPORTED',
      message: (0,external_wp_i18n_namespaceObject.sprintf)(
      // translators: %s: file name.
      (0,external_wp_i18n_namespaceObject.__)('%s: Sorry, this file type is not supported here.'), file.name),
      file
    });
  }
}

;// ./packages/upload-media/build-module/get-mime-types-array.js
/* wp:polyfill */
/**
 * Browsers may use unexpected mime types, and they differ from browser to browser.
 * This function computes a flexible array of mime types from the mime type structured provided by the server.
 * Converts { jpg|jpeg|jpe: "image/jpeg" } into [ "image/jpeg", "image/jpg", "image/jpeg", "image/jpe" ]
 *
 * @param {?Object} wpMimeTypesObject Mime type object received from the server.
 *                                    Extensions are keys separated by '|' and values are mime types associated with an extension.
 *
 * @return An array of mime types or null
 */
function getMimeTypesArray(wpMimeTypesObject) {
  if (!wpMimeTypesObject) {
    return null;
  }
  return Object.entries(wpMimeTypesObject).flatMap(([extensionsString, mime]) => {
    const [type] = mime.split('/');
    const extensions = extensionsString.split('|');
    return [mime, ...extensions.map(extension => `${type}/${extension}`)];
  });
}

;// ./packages/upload-media/build-module/validate-mime-type-for-user.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



/**
 * Verifies if the user is allowed to upload this mime type.
 *
 * @param file               File object.
 * @param wpAllowedMimeTypes List of allowed mime types and file extensions.
 */
function validateMimeTypeForUser(file, wpAllowedMimeTypes) {
  // Allowed types for the current WP_User.
  const allowedMimeTypesForUser = getMimeTypesArray(wpAllowedMimeTypes);
  if (!allowedMimeTypesForUser) {
    return;
  }
  const isAllowedMimeTypeForUser = allowedMimeTypesForUser.includes(file.type);
  if (file.type && !isAllowedMimeTypeForUser) {
    throw new UploadError({
      code: 'MIME_TYPE_NOT_ALLOWED_FOR_USER',
      message: (0,external_wp_i18n_namespaceObject.sprintf)(
      // translators: %s: file name.
      (0,external_wp_i18n_namespaceObject.__)('%s: Sorry, you are not allowed to upload this file type.'), file.name),
      file
    });
  }
}

;// ./packages/upload-media/build-module/validate-file-size.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


/**
 * Verifies whether the file is within the file upload size limits for the site.
 *
 * @param file              File object.
 * @param maxUploadFileSize Maximum upload size in bytes allowed for the site.
 */
function validateFileSize(file, maxUploadFileSize) {
  // Don't allow empty files to be uploaded.
  if (file.size <= 0) {
    throw new UploadError({
      code: 'EMPTY_FILE',
      message: (0,external_wp_i18n_namespaceObject.sprintf)(
      // translators: %s: file name.
      (0,external_wp_i18n_namespaceObject.__)('%s: This file is empty.'), file.name),
      file
    });
  }
  if (maxUploadFileSize && file.size > maxUploadFileSize) {
    throw new UploadError({
      code: 'SIZE_ABOVE_LIMIT',
      message: (0,external_wp_i18n_namespaceObject.sprintf)(
      // translators: %s: file name.
      (0,external_wp_i18n_namespaceObject.__)('%s: This file exceeds the maximum upload size for this site.'), file.name),
      file
    });
  }
}

;// ./packages/upload-media/build-module/store/actions.js
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
 * Adds a new item to the upload queue.
 *
 * @param $0
 * @param $0.files            Files
 * @param [$0.onChange]       Function called each time a file or a temporary representation of the file is available.
 * @param [$0.onSuccess]      Function called after the file is uploaded.
 * @param [$0.onBatchSuccess] Function called after a batch of files is uploaded.
 * @param [$0.onError]        Function called when an error happens.
 * @param [$0.additionalData] Additional data to include in the request.
 * @param [$0.allowedTypes]   Array with the types of media that can be uploaded, if unset all types are allowed.
 */
function addItems({
  files,
  onChange,
  onSuccess,
  onError,
  onBatchSuccess,
  additionalData,
  allowedTypes
}) {
  return async ({
    select,
    dispatch
  }) => {
    const batchId = esm_browser_v4();
    for (const file of files) {
      /*
       Check if the caller (e.g. a block) supports this mime type.
       Special case for file types such as HEIC which will be converted before upload anyway.
       Another check will be done before upload.
      */
      try {
        validateMimeType(file, allowedTypes);
        validateMimeTypeForUser(file, select.getSettings().allowedMimeTypes);
      } catch (error) {
        onError?.(error);
        continue;
      }
      try {
        validateFileSize(file, select.getSettings().maxUploadFileSize);
      } catch (error) {
        onError?.(error);
        continue;
      }
      dispatch.addItem({
        file,
        batchId,
        onChange,
        onSuccess,
        onBatchSuccess,
        onError,
        additionalData
      });
    }
  };
}

/**
 * Cancels an item in the queue based on an error.
 *
 * @param id     Item ID.
 * @param error  Error instance.
 * @param silent Whether to cancel the item silently,
 *               without invoking its `onError` callback.
 */
function cancelItem(id, error, silent = false) {
  return async ({
    select,
    dispatch
  }) => {
    const item = select.getItem(id);
    if (!item) {
      /*
       * Do nothing if item has already been removed.
       * This can happen if an upload is cancelled manually
       * while transcoding with vips is still in progress.
       * Then, cancelItem() is once invoked manually and once
       * by the error handler in optimizeImageItem().
       */
      return;
    }
    item.abortController?.abort();
    if (!silent) {
      const {
        onError
      } = item;
      onError?.(error !== null && error !== void 0 ? error : new Error('Upload cancelled'));
      if (!onError && error) {
        // TODO: Find better way to surface errors with sideloads etc.
        // eslint-disable-next-line no-console -- Deliberately log errors here.
        console.error('Upload cancelled', error);
      }
    }
    dispatch({
      type: Type.Cancel,
      id,
      error
    });
    dispatch.removeItem(id);
    dispatch.revokeBlobUrls(id);

    // All items of this batch were cancelled or finished.
    if (item.batchId && select.isBatchUploaded(item.batchId)) {
      item.onBatchSuccess?.();
    }
  };
}

;// external ["wp","blob"]
const external_wp_blob_namespaceObject = window["wp"]["blob"];
;// external ["wp","url"]
const external_wp_url_namespaceObject = window["wp"]["url"];
;// ./packages/upload-media/build-module/utils.js
/**
 * WordPress dependencies
 */



/**
 * Converts a Blob to a File with a default name like "image.png".
 *
 * If it is already a File object, it is returned unchanged.
 *
 * @param fileOrBlob Blob object.
 * @return File object.
 */
function convertBlobToFile(fileOrBlob) {
  if (fileOrBlob instanceof File) {
    return fileOrBlob;
  }

  // Extension is only an approximation.
  // The server will override it if incorrect.
  const ext = fileOrBlob.type.split('/')[1];
  const mediaType = 'application/pdf' === fileOrBlob.type ? 'document' : fileOrBlob.type.split('/')[0];
  return new File([fileOrBlob], `${mediaType}.${ext}`, {
    type: fileOrBlob.type
  });
}

/**
 * Renames a given file and returns a new file.
 *
 * Copies over the last modified time.
 *
 * @param file File object.
 * @param name File name.
 * @return Renamed file object.
 */
function renameFile(file, name) {
  return new File([file], name, {
    type: file.type,
    lastModified: file.lastModified
  });
}

/**
 * Clones a given file object.
 *
 * @param file File object.
 * @return New file object.
 */
function cloneFile(file) {
  return renameFile(file, file.name);
}

/**
 * Returns the file extension from a given file name or URL.
 *
 * @param file File URL.
 * @return File extension or null if it does not have one.
 */
function getFileExtension(file) {
  return file.includes('.') ? file.split('.').pop() || null : null;
}

/**
 * Returns file basename without extension.
 *
 * For example, turns "my-awesome-file.jpeg" into "my-awesome-file".
 *
 * @param name File name.
 * @return File basename.
 */
function getFileBasename(name) {
  return name.includes('.') ? name.split('.').slice(0, -1).join('.') : name;
}

/**
 * Returns the file name including extension from a URL.
 *
 * @param url File URL.
 * @return File name.
 */
function getFileNameFromUrl(url) {
  return getFilename(url) || _x('unnamed', 'file name');
}

;// ./packages/upload-media/build-module/stub-file.js
class StubFile extends File {
  constructor(fileName = 'stub-file') {
    super([], fileName);
  }
}

;// ./packages/upload-media/build-module/store/private-actions.js
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
 * Adds a new item to the upload queue.
 *
 * @param $0
 * @param $0.file                 File
 * @param [$0.batchId]            Batch ID.
 * @param [$0.onChange]           Function called each time a file or a temporary representation of the file is available.
 * @param [$0.onSuccess]          Function called after the file is uploaded.
 * @param [$0.onBatchSuccess]     Function called after a batch of files is uploaded.
 * @param [$0.onError]            Function called when an error happens.
 * @param [$0.additionalData]     Additional data to include in the request.
 * @param [$0.sourceUrl]          Source URL. Used when importing a file from a URL or optimizing an existing file.
 * @param [$0.sourceAttachmentId] Source attachment ID. Used when optimizing an existing file for example.
 * @param [$0.abortController]    Abort controller for upload cancellation.
 * @param [$0.operations]         List of operations to perform. Defaults to automatically determined list, based on the file.
 */
function addItem({
  file: fileOrBlob,
  batchId,
  onChange,
  onSuccess,
  onBatchSuccess,
  onError,
  additionalData = {},
  sourceUrl,
  sourceAttachmentId,
  abortController,
  operations
}) {
  return async ({
    dispatch
  }) => {
    const itemId = esm_browser_v4();

    // Hardening in case a Blob is passed instead of a File.
    // See https://github.com/WordPress/gutenberg/pull/65693 for an example.
    const file = convertBlobToFile(fileOrBlob);
    let blobUrl;

    // StubFile could be coming from addItemFromUrl().
    if (!(file instanceof StubFile)) {
      blobUrl = (0,external_wp_blob_namespaceObject.createBlobURL)(file);
      dispatch({
        type: Type.CacheBlobUrl,
        id: itemId,
        blobUrl
      });
    }
    dispatch({
      type: Type.Add,
      item: {
        id: itemId,
        batchId,
        status: ItemStatus.Processing,
        sourceFile: cloneFile(file),
        file,
        attachment: {
          url: blobUrl
        },
        additionalData: {
          convert_format: false,
          ...additionalData
        },
        onChange,
        onSuccess,
        onBatchSuccess,
        onError,
        sourceUrl,
        sourceAttachmentId,
        abortController: abortController || new AbortController(),
        operations: Array.isArray(operations) ? operations : [OperationType.Prepare]
      }
    });
    dispatch.processItem(itemId);
  };
}

/**
 * Processes a single item in the queue.
 *
 * Runs the next operation in line and invokes any callbacks.
 *
 * @param id Item ID.
 */
function processItem(id) {
  return async ({
    select,
    dispatch
  }) => {
    if (select.isPaused()) {
      return;
    }
    const item = select.getItem(id);
    const {
      attachment,
      onChange,
      onSuccess,
      onBatchSuccess,
      batchId
    } = item;
    const operation = Array.isArray(item.operations?.[0]) ? item.operations[0][0] : item.operations?.[0];
    if (attachment) {
      onChange?.([attachment]);
    }

    /*
     If there are no more operations, the item can be removed from the queue,
     but only if there are no thumbnails still being side-loaded,
     or if itself is a side-loaded item.
    */

    if (!operation) {
      if (attachment) {
        onSuccess?.([attachment]);
      }

      // dispatch.removeItem( id );
      dispatch.revokeBlobUrls(id);
      if (batchId && select.isBatchUploaded(batchId)) {
        onBatchSuccess?.();
      }

      /*
       At this point we are dealing with a parent whose children haven't fully uploaded yet.
       Do nothing and let the removal happen once the last side-loaded item finishes.
       */

      return;
    }
    if (!operation) {
      // This shouldn't really happen.
      return;
    }
    dispatch({
      type: Type.OperationStart,
      id,
      operation
    });
    switch (operation) {
      case OperationType.Prepare:
        dispatch.prepareItem(item.id);
        break;
      case OperationType.Upload:
        dispatch.uploadItem(id);
        break;
    }
  };
}

/**
 * Returns an action object that pauses all processing in the queue.
 *
 * Useful for testing purposes.
 *
 * @return Action object.
 */
function pauseQueue() {
  return {
    type: Type.PauseQueue
  };
}

/**
 * Resumes all processing in the queue.
 *
 * Dispatches an action object for resuming the queue itself,
 * and triggers processing for each remaining item in the queue individually.
 */
function resumeQueue() {
  return async ({
    select,
    dispatch
  }) => {
    dispatch({
      type: Type.ResumeQueue
    });
    for (const item of select.getAllItems()) {
      dispatch.processItem(item.id);
    }
  };
}

/**
 * Removes a specific item from the queue.
 *
 * @param id Item ID.
 */
function removeItem(id) {
  return async ({
    select,
    dispatch
  }) => {
    const item = select.getItem(id);
    if (!item) {
      return;
    }
    dispatch({
      type: Type.Remove,
      id
    });
  };
}

/**
 * Finishes an operation for a given item ID and immediately triggers processing the next one.
 *
 * @param id      Item ID.
 * @param updates Updated item data.
 */
function finishOperation(id, updates) {
  return async ({
    dispatch
  }) => {
    dispatch({
      type: Type.OperationFinish,
      id,
      item: updates
    });
    dispatch.processItem(id);
  };
}

/**
 * Prepares an item for initial processing.
 *
 * Determines the list of operations to perform for a given image,
 * depending on its media type.
 *
 * For example, HEIF images first need to be converted, resized,
 * compressed, and then uploaded.
 *
 * Or videos need to be compressed, and then need poster generation
 * before upload.
 *
 * @param id Item ID.
 */
function prepareItem(id) {
  return async ({
    dispatch
  }) => {
    const operations = [OperationType.Upload];
    dispatch({
      type: Type.AddOperations,
      id,
      operations
    });
    dispatch.finishOperation(id, {});
  };
}

/**
 * Uploads an item to the server.
 *
 * @param id Item ID.
 */
function uploadItem(id) {
  return async ({
    select,
    dispatch
  }) => {
    const item = select.getItem(id);
    select.getSettings().mediaUpload({
      filesList: [item.file],
      additionalData: item.additionalData,
      signal: item.abortController?.signal,
      onFileChange: ([attachment]) => {
        if (!(0,external_wp_blob_namespaceObject.isBlobURL)(attachment.url)) {
          dispatch.finishOperation(id, {
            attachment
          });
        }
      },
      onSuccess: ([attachment]) => {
        dispatch.finishOperation(id, {
          attachment
        });
      },
      onError: error => {
        dispatch.cancelItem(id, error);
      }
    });
  };
}

/**
 * Revokes all blob URLs for a given item, freeing up memory.
 *
 * @param id Item ID.
 */
function revokeBlobUrls(id) {
  return async ({
    select,
    dispatch
  }) => {
    const blobUrls = select.getBlobUrls(id);
    for (const blobUrl of blobUrls) {
      (0,external_wp_blob_namespaceObject.revokeBlobURL)(blobUrl);
    }
    dispatch({
      type: Type.RevokeBlobUrls,
      id
    });
  };
}

/**
 * Returns an action object that pauses all processing in the queue.
 *
 * Useful for testing purposes.
 *
 * @param settings
 * @return Action object.
 */
function updateSettings(settings) {
  return {
    type: Type.UpdateSettings,
    settings
  };
}

;// external ["wp","privateApis"]
const external_wp_privateApis_namespaceObject = window["wp"]["privateApis"];
;// ./packages/upload-media/build-module/lock-unlock.js
/**
 * WordPress dependencies
 */

const {
  lock,
  unlock
} = (0,external_wp_privateApis_namespaceObject.__dangerousOptInToUnstableAPIsOnlyForCoreModules)('I acknowledge private features are not for use in themes or plugins and doing so will break in the next version of WordPress.', '@wordpress/upload-media');

;// ./packages/upload-media/build-module/store/constants.js
const STORE_NAME = 'core/upload-media';

;// ./packages/upload-media/build-module/store/index.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */








/**
 * Media upload data store configuration.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#registerStore
 */
const storeConfig = {
  reducer: store_reducer,
  selectors: selectors_namespaceObject,
  actions: actions_namespaceObject
};

/**
 * Store definition for the media upload namespace.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 */
const store = (0,external_wp_data_namespaceObject.createReduxStore)(STORE_NAME, {
  reducer: store_reducer,
  selectors: selectors_namespaceObject,
  actions: actions_namespaceObject
});
(0,external_wp_data_namespaceObject.register)(store);
// @ts-ignore
unlock(store).registerPrivateActions(private_actions_namespaceObject);
// @ts-ignore
unlock(store).registerPrivateSelectors(private_selectors_namespaceObject);

;// external ["wp","element"]
const external_wp_element_namespaceObject = window["wp"]["element"];
;// external ["wp","compose"]
const external_wp_compose_namespaceObject = window["wp"]["compose"];
;// external "ReactJSXRuntime"
const external_ReactJSXRuntime_namespaceObject = window["ReactJSXRuntime"];
;// ./packages/upload-media/build-module/components/provider/with-registry-provider.js
/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */



function getSubRegistry(subRegistries, registry, useSubRegistry) {
  if (!useSubRegistry) {
    return registry;
  }
  let subRegistry = subRegistries.get(registry);
  if (!subRegistry) {
    subRegistry = (0,external_wp_data_namespaceObject.createRegistry)({}, registry);
    subRegistry.registerStore(STORE_NAME, storeConfig);
    subRegistries.set(registry, subRegistry);
  }
  return subRegistry;
}
const withRegistryProvider = (0,external_wp_compose_namespaceObject.createHigherOrderComponent)(WrappedComponent => ({
  useSubRegistry = true,
  ...props
}) => {
  const registry = (0,external_wp_data_namespaceObject.useRegistry)();
  const [subRegistries] = (0,external_wp_element_namespaceObject.useState)(() => new WeakMap());
  const subRegistry = getSubRegistry(subRegistries, registry, useSubRegistry);
  if (subRegistry === registry) {
    return /*#__PURE__*/(0,external_ReactJSXRuntime_namespaceObject.jsx)(WrappedComponent, {
      registry: registry,
      ...props
    });
  }
  return /*#__PURE__*/(0,external_ReactJSXRuntime_namespaceObject.jsx)(external_wp_data_namespaceObject.RegistryProvider, {
    value: subRegistry,
    children: /*#__PURE__*/(0,external_ReactJSXRuntime_namespaceObject.jsx)(WrappedComponent, {
      registry: subRegistry,
      ...props
    })
  });
}, 'withRegistryProvider');
/* harmony default export */ const with_registry_provider = (withRegistryProvider);

;// ./packages/upload-media/build-module/components/provider/index.js
/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */




const MediaUploadProvider = with_registry_provider(props => {
  const {
    children,
    settings
  } = props;
  const {
    updateSettings
  } = unlock((0,external_wp_data_namespaceObject.useDispatch)(store));
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    updateSettings(settings);
  }, [settings, updateSettings]);
  return /*#__PURE__*/(0,external_ReactJSXRuntime_namespaceObject.jsx)(external_ReactJSXRuntime_namespaceObject.Fragment, {
    children: children
  });
});
/* harmony default export */ const provider = (MediaUploadProvider);

;// ./packages/upload-media/build-module/index.js
/**
 * Internal dependencies
 */





(window.wp = window.wp || {}).uploadMedia = __webpack_exports__;
/******/ })()
;