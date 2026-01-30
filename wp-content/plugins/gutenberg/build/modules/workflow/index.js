var __create = Object.create;
var __defProp = Object.defineProperty;
var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
var __getOwnPropNames = Object.getOwnPropertyNames;
var __getProtoOf = Object.getPrototypeOf;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __commonJS = (cb, mod) => function __require() {
  return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
};
var __copyProps = (to, from, except, desc) => {
  if (from && typeof from === "object" || typeof from === "function") {
    for (let key of __getOwnPropNames(from))
      if (!__hasOwnProp.call(to, key) && key !== except)
        __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
  }
  return to;
};
var __toESM = (mod, isNodeMode, target) => (target = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(
  // If the importer is in node compatibility mode or this is not an ESM
  // file that has been converted to a CommonJS file using a Babel-
  // compatible transform (i.e. "__esModule" has not been set), then set
  // "default" to the CommonJS "module.exports" for node compatibility.
  isNodeMode || !mod || !mod.__esModule ? __defProp(target, "default", { value: mod, enumerable: true }) : target,
  mod
));

// package-external:@wordpress/element
var require_element = __commonJS({
  "package-external:@wordpress/element"(exports, module) {
    module.exports = window.wp.element;
  }
});

// vendor-external:react
var require_react = __commonJS({
  "vendor-external:react"(exports, module) {
    module.exports = window.React;
  }
});

// vendor-external:react/jsx-runtime
var require_jsx_runtime = __commonJS({
  "vendor-external:react/jsx-runtime"(exports, module) {
    module.exports = window.ReactJSXRuntime;
  }
});

// vendor-external:react-dom
var require_react_dom = __commonJS({
  "vendor-external:react-dom"(exports, module) {
    module.exports = window.ReactDOM;
  }
});

// package-external:@wordpress/data
var require_data = __commonJS({
  "package-external:@wordpress/data"(exports, module) {
    module.exports = window.wp.data;
  }
});

// package-external:@wordpress/i18n
var require_i18n = __commonJS({
  "package-external:@wordpress/i18n"(exports, module) {
    module.exports = window.wp.i18n;
  }
});

// package-external:@wordpress/components
var require_components = __commonJS({
  "package-external:@wordpress/components"(exports, module) {
    module.exports = window.wp.components;
  }
});

// package-external:@wordpress/keyboard-shortcuts
var require_keyboard_shortcuts = __commonJS({
  "package-external:@wordpress/keyboard-shortcuts"(exports, module) {
    module.exports = window.wp.keyboardShortcuts;
  }
});

// package-external:@wordpress/primitives
var require_primitives = __commonJS({
  "package-external:@wordpress/primitives"(exports, module) {
    module.exports = window.wp.primitives;
  }
});

// package-external:@wordpress/private-apis
var require_private_apis = __commonJS({
  "package-external:@wordpress/private-apis"(exports, module) {
    module.exports = window.wp.privateApis;
  }
});

// packages/workflow/build-module/index.mjs
var import_element3 = __toESM(require_element(), 1);

// node_modules/cmdk/dist/chunk-NZJY6EH4.mjs
var U = 1;
var Y = 0.9;
var H = 0.8;
var J = 0.17;
var p = 0.1;
var u = 0.999;
var $ = 0.9999;
var k = 0.99;
var m = /[\\\/_+.#"@\[\(\{&]/;
var B = /[\\\/_+.#"@\[\(\{&]/g;
var K = /[\s-]/;
var X = /[\s-]/g;
function G(_, C, h, P2, A, f, O) {
  if (f === C.length) return A === _.length ? U : k;
  var T2 = `${A},${f}`;
  if (O[T2] !== void 0) return O[T2];
  for (var L2 = P2.charAt(f), c = h.indexOf(L2, A), S = 0, E, N2, R, M; c >= 0; ) E = G(_, C, h, P2, c + 1, f + 1, O), E > S && (c === A ? E *= U : m.test(_.charAt(c - 1)) ? (E *= H, R = _.slice(A, c - 1).match(B), R && A > 0 && (E *= Math.pow(u, R.length))) : K.test(_.charAt(c - 1)) ? (E *= Y, M = _.slice(A, c - 1).match(X), M && A > 0 && (E *= Math.pow(u, M.length))) : (E *= J, A > 0 && (E *= Math.pow(u, c - A))), _.charAt(c) !== C.charAt(f) && (E *= $)), (E < p && h.charAt(c - 1) === P2.charAt(f + 1) || P2.charAt(f + 1) === P2.charAt(f) && h.charAt(c - 1) !== P2.charAt(f)) && (N2 = G(_, C, h, P2, c + 1, f + 2, O), N2 * p > E && (E = N2 * p)), E > S && (S = E), c = h.indexOf(L2, c + 1);
  return O[T2] = S, S;
}
function D(_) {
  return _.toLowerCase().replace(X, " ");
}
function W(_, C, h) {
  return _ = h && h.length > 0 ? `${_ + " " + h.join(" ")}` : _, G(_, C, D(_), D(C), 0, 0, {});
}

// node_modules/@radix-ui/react-dialog/dist/index.mjs
var React37 = __toESM(require_react(), 1);

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/primitive/dist/index.mjs
var canUseDOM = !!(typeof window !== "undefined" && window.document && window.document.createElement);
function composeEventHandlers(originalEventHandler, ourEventHandler, { checkForDefaultPrevented = true } = {}) {
  return function handleEvent(event) {
    originalEventHandler?.(event);
    if (checkForDefaultPrevented === false || !event.defaultPrevented) {
      return ourEventHandler?.(event);
    }
  };
}

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-compose-refs/dist/index.mjs
var React = __toESM(require_react(), 1);
function setRef(ref, value) {
  if (typeof ref === "function") {
    return ref(value);
  } else if (ref !== null && ref !== void 0) {
    ref.current = value;
  }
}
function composeRefs(...refs) {
  return (node) => {
    let hasCleanup = false;
    const cleanups = refs.map((ref) => {
      const cleanup = setRef(ref, node);
      if (!hasCleanup && typeof cleanup == "function") {
        hasCleanup = true;
      }
      return cleanup;
    });
    if (hasCleanup) {
      return () => {
        for (let i = 0; i < cleanups.length; i++) {
          const cleanup = cleanups[i];
          if (typeof cleanup == "function") {
            cleanup();
          } else {
            setRef(refs[i], null);
          }
        }
      };
    }
  };
}
function useComposedRefs(...refs) {
  return React.useCallback(composeRefs(...refs), refs);
}

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-context/dist/index.mjs
var React2 = __toESM(require_react(), 1);
var import_jsx_runtime = __toESM(require_jsx_runtime(), 1);
function createContext2(rootComponentName, defaultContext) {
  const Context = React2.createContext(defaultContext);
  const Provider = (props) => {
    const { children, ...context } = props;
    const value = React2.useMemo(() => context, Object.values(context));
    return /* @__PURE__ */ (0, import_jsx_runtime.jsx)(Context.Provider, { value, children });
  };
  Provider.displayName = rootComponentName + "Provider";
  function useContext22(consumerName) {
    const context = React2.useContext(Context);
    if (context) return context;
    if (defaultContext !== void 0) return defaultContext;
    throw new Error(`\`${consumerName}\` must be used within \`${rootComponentName}\``);
  }
  return [Provider, useContext22];
}
function createContextScope(scopeName, createContextScopeDeps = []) {
  let defaultContexts = [];
  function createContext32(rootComponentName, defaultContext) {
    const BaseContext = React2.createContext(defaultContext);
    const index = defaultContexts.length;
    defaultContexts = [...defaultContexts, defaultContext];
    const Provider = (props) => {
      const { scope, children, ...context } = props;
      const Context = scope?.[scopeName]?.[index] || BaseContext;
      const value = React2.useMemo(() => context, Object.values(context));
      return /* @__PURE__ */ (0, import_jsx_runtime.jsx)(Context.Provider, { value, children });
    };
    Provider.displayName = rootComponentName + "Provider";
    function useContext22(consumerName, scope) {
      const Context = scope?.[scopeName]?.[index] || BaseContext;
      const context = React2.useContext(Context);
      if (context) return context;
      if (defaultContext !== void 0) return defaultContext;
      throw new Error(`\`${consumerName}\` must be used within \`${rootComponentName}\``);
    }
    return [Provider, useContext22];
  }
  const createScope = () => {
    const scopeContexts = defaultContexts.map((defaultContext) => {
      return React2.createContext(defaultContext);
    });
    return function useScope(scope) {
      const contexts = scope?.[scopeName] || scopeContexts;
      return React2.useMemo(
        () => ({ [`__scope${scopeName}`]: { ...scope, [scopeName]: contexts } }),
        [scope, contexts]
      );
    };
  };
  createScope.scopeName = scopeName;
  return [createContext32, composeContextScopes(createScope, ...createContextScopeDeps)];
}
function composeContextScopes(...scopes) {
  const baseScope = scopes[0];
  if (scopes.length === 1) return baseScope;
  const createScope = () => {
    const scopeHooks = scopes.map((createScope2) => ({
      useScope: createScope2(),
      scopeName: createScope2.scopeName
    }));
    return function useComposedScopes(overrideScopes) {
      const nextScopes = scopeHooks.reduce((nextScopes2, { useScope, scopeName }) => {
        const scopeProps = useScope(overrideScopes);
        const currentScope = scopeProps[`__scope${scopeName}`];
        return { ...nextScopes2, ...currentScope };
      }, {});
      return React2.useMemo(() => ({ [`__scope${baseScope.scopeName}`]: nextScopes }), [nextScopes]);
    };
  };
  createScope.scopeName = baseScope.scopeName;
  return createScope;
}

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-id/dist/index.mjs
var React4 = __toESM(require_react(), 1);

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-use-layout-effect/dist/index.mjs
var React3 = __toESM(require_react(), 1);
var useLayoutEffect2 = globalThis?.document ? React3.useLayoutEffect : () => {
};

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-id/dist/index.mjs
var useReactId = React4[" useId ".trim().toString()] || (() => void 0);
var count = 0;
function useId(deterministicId) {
  const [id, setId] = React4.useState(useReactId());
  useLayoutEffect2(() => {
    if (!deterministicId) setId((reactId) => reactId ?? String(count++));
  }, [deterministicId]);
  return deterministicId || (id ? `radix-${id}` : "");
}

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-use-controllable-state/dist/index.mjs
var React5 = __toESM(require_react(), 1);
var React22 = __toESM(require_react(), 1);
var useInsertionEffect = React5[" useInsertionEffect ".trim().toString()] || useLayoutEffect2;
function useControllableState({
  prop,
  defaultProp,
  onChange = () => {
  },
  caller
}) {
  const [uncontrolledProp, setUncontrolledProp, onChangeRef] = useUncontrolledState({
    defaultProp,
    onChange
  });
  const isControlled = prop !== void 0;
  const value = isControlled ? prop : uncontrolledProp;
  if (true) {
    const isControlledRef = React5.useRef(prop !== void 0);
    React5.useEffect(() => {
      const wasControlled = isControlledRef.current;
      if (wasControlled !== isControlled) {
        const from = wasControlled ? "controlled" : "uncontrolled";
        const to = isControlled ? "controlled" : "uncontrolled";
        console.warn(
          `${caller} is changing from ${from} to ${to}. Components should not switch from controlled to uncontrolled (or vice versa). Decide between using a controlled or uncontrolled value for the lifetime of the component.`
        );
      }
      isControlledRef.current = isControlled;
    }, [isControlled, caller]);
  }
  const setValue = React5.useCallback(
    (nextValue) => {
      if (isControlled) {
        const value2 = isFunction(nextValue) ? nextValue(prop) : nextValue;
        if (value2 !== prop) {
          onChangeRef.current?.(value2);
        }
      } else {
        setUncontrolledProp(nextValue);
      }
    },
    [isControlled, prop, setUncontrolledProp, onChangeRef]
  );
  return [value, setValue];
}
function useUncontrolledState({
  defaultProp,
  onChange
}) {
  const [value, setValue] = React5.useState(defaultProp);
  const prevValueRef = React5.useRef(value);
  const onChangeRef = React5.useRef(onChange);
  useInsertionEffect(() => {
    onChangeRef.current = onChange;
  }, [onChange]);
  React5.useEffect(() => {
    if (prevValueRef.current !== value) {
      onChangeRef.current?.(value);
      prevValueRef.current = value;
    }
  }, [value, prevValueRef]);
  return [value, setValue, onChangeRef];
}
function isFunction(value) {
  return typeof value === "function";
}

// node_modules/@radix-ui/react-dismissable-layer/dist/index.mjs
var React11 = __toESM(require_react(), 1);

// node_modules/@radix-ui/react-dismissable-layer/node_modules/@radix-ui/primitive/dist/index.mjs
var canUseDOM2 = !!(typeof window !== "undefined" && window.document && window.document.createElement);
function composeEventHandlers2(originalEventHandler, ourEventHandler, { checkForDefaultPrevented = true } = {}) {
  return function handleEvent(event) {
    originalEventHandler?.(event);
    if (checkForDefaultPrevented === false || !event.defaultPrevented) {
      return ourEventHandler?.(event);
    }
  };
}

// node_modules/@radix-ui/react-dismissable-layer/node_modules/@radix-ui/react-primitive/dist/index.mjs
var React8 = __toESM(require_react(), 1);
var ReactDOM = __toESM(require_react_dom(), 1);

// node_modules/@radix-ui/react-dismissable-layer/node_modules/@radix-ui/react-slot/dist/index.mjs
var React7 = __toESM(require_react(), 1);

// node_modules/@radix-ui/react-dismissable-layer/node_modules/@radix-ui/react-compose-refs/dist/index.mjs
var React6 = __toESM(require_react(), 1);
function setRef2(ref, value) {
  if (typeof ref === "function") {
    return ref(value);
  } else if (ref !== null && ref !== void 0) {
    ref.current = value;
  }
}
function composeRefs2(...refs) {
  return (node) => {
    let hasCleanup = false;
    const cleanups = refs.map((ref) => {
      const cleanup = setRef2(ref, node);
      if (!hasCleanup && typeof cleanup == "function") {
        hasCleanup = true;
      }
      return cleanup;
    });
    if (hasCleanup) {
      return () => {
        for (let i = 0; i < cleanups.length; i++) {
          const cleanup = cleanups[i];
          if (typeof cleanup == "function") {
            cleanup();
          } else {
            setRef2(refs[i], null);
          }
        }
      };
    }
  };
}
function useComposedRefs2(...refs) {
  return React6.useCallback(composeRefs2(...refs), refs);
}

// node_modules/@radix-ui/react-dismissable-layer/node_modules/@radix-ui/react-slot/dist/index.mjs
var import_jsx_runtime2 = __toESM(require_jsx_runtime(), 1);
// @__NO_SIDE_EFFECTS__
function createSlot(ownerName) {
  const SlotClone = /* @__PURE__ */ createSlotClone(ownerName);
  const Slot2 = React7.forwardRef((props, forwardedRef) => {
    const { children, ...slotProps } = props;
    const childrenArray = React7.Children.toArray(children);
    const slottable = childrenArray.find(isSlottable);
    if (slottable) {
      const newElement = slottable.props.children;
      const newChildren = childrenArray.map((child) => {
        if (child === slottable) {
          if (React7.Children.count(newElement) > 1) return React7.Children.only(null);
          return React7.isValidElement(newElement) ? newElement.props.children : null;
        } else {
          return child;
        }
      });
      return /* @__PURE__ */ (0, import_jsx_runtime2.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children: React7.isValidElement(newElement) ? React7.cloneElement(newElement, void 0, newChildren) : null });
    }
    return /* @__PURE__ */ (0, import_jsx_runtime2.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children });
  });
  Slot2.displayName = `${ownerName}.Slot`;
  return Slot2;
}
// @__NO_SIDE_EFFECTS__
function createSlotClone(ownerName) {
  const SlotClone = React7.forwardRef((props, forwardedRef) => {
    const { children, ...slotProps } = props;
    if (React7.isValidElement(children)) {
      const childrenRef = getElementRef(children);
      const props2 = mergeProps(slotProps, children.props);
      if (children.type !== React7.Fragment) {
        props2.ref = forwardedRef ? composeRefs2(forwardedRef, childrenRef) : childrenRef;
      }
      return React7.cloneElement(children, props2);
    }
    return React7.Children.count(children) > 1 ? React7.Children.only(null) : null;
  });
  SlotClone.displayName = `${ownerName}.SlotClone`;
  return SlotClone;
}
var SLOTTABLE_IDENTIFIER = /* @__PURE__ */ Symbol("radix.slottable");
function isSlottable(child) {
  return React7.isValidElement(child) && typeof child.type === "function" && "__radixId" in child.type && child.type.__radixId === SLOTTABLE_IDENTIFIER;
}
function mergeProps(slotProps, childProps) {
  const overrideProps = { ...childProps };
  for (const propName in childProps) {
    const slotPropValue = slotProps[propName];
    const childPropValue = childProps[propName];
    const isHandler = /^on[A-Z]/.test(propName);
    if (isHandler) {
      if (slotPropValue && childPropValue) {
        overrideProps[propName] = (...args) => {
          const result = childPropValue(...args);
          slotPropValue(...args);
          return result;
        };
      } else if (slotPropValue) {
        overrideProps[propName] = slotPropValue;
      }
    } else if (propName === "style") {
      overrideProps[propName] = { ...slotPropValue, ...childPropValue };
    } else if (propName === "className") {
      overrideProps[propName] = [slotPropValue, childPropValue].filter(Boolean).join(" ");
    }
  }
  return { ...slotProps, ...overrideProps };
}
function getElementRef(element) {
  let getter = Object.getOwnPropertyDescriptor(element.props, "ref")?.get;
  let mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.ref;
  }
  getter = Object.getOwnPropertyDescriptor(element, "ref")?.get;
  mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.props.ref;
  }
  return element.props.ref || element.ref;
}

// node_modules/@radix-ui/react-dismissable-layer/node_modules/@radix-ui/react-primitive/dist/index.mjs
var import_jsx_runtime3 = __toESM(require_jsx_runtime(), 1);
var NODES = [
  "a",
  "button",
  "div",
  "form",
  "h2",
  "h3",
  "img",
  "input",
  "label",
  "li",
  "nav",
  "ol",
  "p",
  "select",
  "span",
  "svg",
  "ul"
];
var Primitive = NODES.reduce((primitive, node) => {
  const Slot2 = createSlot(`Primitive.${node}`);
  const Node2 = React8.forwardRef((props, forwardedRef) => {
    const { asChild, ...primitiveProps } = props;
    const Comp = asChild ? Slot2 : node;
    if (typeof window !== "undefined") {
      window[/* @__PURE__ */ Symbol.for("radix-ui")] = true;
    }
    return /* @__PURE__ */ (0, import_jsx_runtime3.jsx)(Comp, { ...primitiveProps, ref: forwardedRef });
  });
  Node2.displayName = `Primitive.${node}`;
  return { ...primitive, [node]: Node2 };
}, {});
function dispatchDiscreteCustomEvent(target, event) {
  if (target) ReactDOM.flushSync(() => target.dispatchEvent(event));
}

// node_modules/@radix-ui/react-dismissable-layer/node_modules/@radix-ui/react-use-callback-ref/dist/index.mjs
var React9 = __toESM(require_react(), 1);
function useCallbackRef(callback) {
  const callbackRef = React9.useRef(callback);
  React9.useEffect(() => {
    callbackRef.current = callback;
  });
  return React9.useMemo(() => (...args) => callbackRef.current?.(...args), []);
}

// node_modules/@radix-ui/react-dismissable-layer/node_modules/@radix-ui/react-use-escape-keydown/dist/index.mjs
var React10 = __toESM(require_react(), 1);
function useEscapeKeydown(onEscapeKeyDownProp, ownerDocument = globalThis?.document) {
  const onEscapeKeyDown = useCallbackRef(onEscapeKeyDownProp);
  React10.useEffect(() => {
    const handleKeyDown = (event) => {
      if (event.key === "Escape") {
        onEscapeKeyDown(event);
      }
    };
    ownerDocument.addEventListener("keydown", handleKeyDown, { capture: true });
    return () => ownerDocument.removeEventListener("keydown", handleKeyDown, { capture: true });
  }, [onEscapeKeyDown, ownerDocument]);
}

// node_modules/@radix-ui/react-dismissable-layer/dist/index.mjs
var import_jsx_runtime4 = __toESM(require_jsx_runtime(), 1);
var DISMISSABLE_LAYER_NAME = "DismissableLayer";
var CONTEXT_UPDATE = "dismissableLayer.update";
var POINTER_DOWN_OUTSIDE = "dismissableLayer.pointerDownOutside";
var FOCUS_OUTSIDE = "dismissableLayer.focusOutside";
var originalBodyPointerEvents;
var DismissableLayerContext = React11.createContext({
  layers: /* @__PURE__ */ new Set(),
  layersWithOutsidePointerEventsDisabled: /* @__PURE__ */ new Set(),
  branches: /* @__PURE__ */ new Set()
});
var DismissableLayer = React11.forwardRef(
  (props, forwardedRef) => {
    const {
      disableOutsidePointerEvents = false,
      onEscapeKeyDown,
      onPointerDownOutside,
      onFocusOutside,
      onInteractOutside,
      onDismiss,
      ...layerProps
    } = props;
    const context = React11.useContext(DismissableLayerContext);
    const [node, setNode] = React11.useState(null);
    const ownerDocument = node?.ownerDocument ?? globalThis?.document;
    const [, force] = React11.useState({});
    const composedRefs = useComposedRefs2(forwardedRef, (node2) => setNode(node2));
    const layers = Array.from(context.layers);
    const [highestLayerWithOutsidePointerEventsDisabled] = [...context.layersWithOutsidePointerEventsDisabled].slice(-1);
    const highestLayerWithOutsidePointerEventsDisabledIndex = layers.indexOf(highestLayerWithOutsidePointerEventsDisabled);
    const index = node ? layers.indexOf(node) : -1;
    const isBodyPointerEventsDisabled = context.layersWithOutsidePointerEventsDisabled.size > 0;
    const isPointerEventsEnabled = index >= highestLayerWithOutsidePointerEventsDisabledIndex;
    const pointerDownOutside = usePointerDownOutside((event) => {
      const target = event.target;
      const isPointerDownOnBranch = [...context.branches].some((branch) => branch.contains(target));
      if (!isPointerEventsEnabled || isPointerDownOnBranch) return;
      onPointerDownOutside?.(event);
      onInteractOutside?.(event);
      if (!event.defaultPrevented) onDismiss?.();
    }, ownerDocument);
    const focusOutside = useFocusOutside((event) => {
      const target = event.target;
      const isFocusInBranch = [...context.branches].some((branch) => branch.contains(target));
      if (isFocusInBranch) return;
      onFocusOutside?.(event);
      onInteractOutside?.(event);
      if (!event.defaultPrevented) onDismiss?.();
    }, ownerDocument);
    useEscapeKeydown((event) => {
      const isHighestLayer = index === context.layers.size - 1;
      if (!isHighestLayer) return;
      onEscapeKeyDown?.(event);
      if (!event.defaultPrevented && onDismiss) {
        event.preventDefault();
        onDismiss();
      }
    }, ownerDocument);
    React11.useEffect(() => {
      if (!node) return;
      if (disableOutsidePointerEvents) {
        if (context.layersWithOutsidePointerEventsDisabled.size === 0) {
          originalBodyPointerEvents = ownerDocument.body.style.pointerEvents;
          ownerDocument.body.style.pointerEvents = "none";
        }
        context.layersWithOutsidePointerEventsDisabled.add(node);
      }
      context.layers.add(node);
      dispatchUpdate();
      return () => {
        if (disableOutsidePointerEvents && context.layersWithOutsidePointerEventsDisabled.size === 1) {
          ownerDocument.body.style.pointerEvents = originalBodyPointerEvents;
        }
      };
    }, [node, ownerDocument, disableOutsidePointerEvents, context]);
    React11.useEffect(() => {
      return () => {
        if (!node) return;
        context.layers.delete(node);
        context.layersWithOutsidePointerEventsDisabled.delete(node);
        dispatchUpdate();
      };
    }, [node, context]);
    React11.useEffect(() => {
      const handleUpdate = () => force({});
      document.addEventListener(CONTEXT_UPDATE, handleUpdate);
      return () => document.removeEventListener(CONTEXT_UPDATE, handleUpdate);
    }, []);
    return /* @__PURE__ */ (0, import_jsx_runtime4.jsx)(
      Primitive.div,
      {
        ...layerProps,
        ref: composedRefs,
        style: {
          pointerEvents: isBodyPointerEventsDisabled ? isPointerEventsEnabled ? "auto" : "none" : void 0,
          ...props.style
        },
        onFocusCapture: composeEventHandlers2(props.onFocusCapture, focusOutside.onFocusCapture),
        onBlurCapture: composeEventHandlers2(props.onBlurCapture, focusOutside.onBlurCapture),
        onPointerDownCapture: composeEventHandlers2(
          props.onPointerDownCapture,
          pointerDownOutside.onPointerDownCapture
        )
      }
    );
  }
);
DismissableLayer.displayName = DISMISSABLE_LAYER_NAME;
var BRANCH_NAME = "DismissableLayerBranch";
var DismissableLayerBranch = React11.forwardRef((props, forwardedRef) => {
  const context = React11.useContext(DismissableLayerContext);
  const ref = React11.useRef(null);
  const composedRefs = useComposedRefs2(forwardedRef, ref);
  React11.useEffect(() => {
    const node = ref.current;
    if (node) {
      context.branches.add(node);
      return () => {
        context.branches.delete(node);
      };
    }
  }, [context.branches]);
  return /* @__PURE__ */ (0, import_jsx_runtime4.jsx)(Primitive.div, { ...props, ref: composedRefs });
});
DismissableLayerBranch.displayName = BRANCH_NAME;
function usePointerDownOutside(onPointerDownOutside, ownerDocument = globalThis?.document) {
  const handlePointerDownOutside = useCallbackRef(onPointerDownOutside);
  const isPointerInsideReactTreeRef = React11.useRef(false);
  const handleClickRef = React11.useRef(() => {
  });
  React11.useEffect(() => {
    const handlePointerDown = (event) => {
      if (event.target && !isPointerInsideReactTreeRef.current) {
        let handleAndDispatchPointerDownOutsideEvent2 = function() {
          handleAndDispatchCustomEvent(
            POINTER_DOWN_OUTSIDE,
            handlePointerDownOutside,
            eventDetail,
            { discrete: true }
          );
        };
        var handleAndDispatchPointerDownOutsideEvent = handleAndDispatchPointerDownOutsideEvent2;
        const eventDetail = { originalEvent: event };
        if (event.pointerType === "touch") {
          ownerDocument.removeEventListener("click", handleClickRef.current);
          handleClickRef.current = handleAndDispatchPointerDownOutsideEvent2;
          ownerDocument.addEventListener("click", handleClickRef.current, { once: true });
        } else {
          handleAndDispatchPointerDownOutsideEvent2();
        }
      } else {
        ownerDocument.removeEventListener("click", handleClickRef.current);
      }
      isPointerInsideReactTreeRef.current = false;
    };
    const timerId = window.setTimeout(() => {
      ownerDocument.addEventListener("pointerdown", handlePointerDown);
    }, 0);
    return () => {
      window.clearTimeout(timerId);
      ownerDocument.removeEventListener("pointerdown", handlePointerDown);
      ownerDocument.removeEventListener("click", handleClickRef.current);
    };
  }, [ownerDocument, handlePointerDownOutside]);
  return {
    // ensures we check React component tree (not just DOM tree)
    onPointerDownCapture: () => isPointerInsideReactTreeRef.current = true
  };
}
function useFocusOutside(onFocusOutside, ownerDocument = globalThis?.document) {
  const handleFocusOutside = useCallbackRef(onFocusOutside);
  const isFocusInsideReactTreeRef = React11.useRef(false);
  React11.useEffect(() => {
    const handleFocus = (event) => {
      if (event.target && !isFocusInsideReactTreeRef.current) {
        const eventDetail = { originalEvent: event };
        handleAndDispatchCustomEvent(FOCUS_OUTSIDE, handleFocusOutside, eventDetail, {
          discrete: false
        });
      }
    };
    ownerDocument.addEventListener("focusin", handleFocus);
    return () => ownerDocument.removeEventListener("focusin", handleFocus);
  }, [ownerDocument, handleFocusOutside]);
  return {
    onFocusCapture: () => isFocusInsideReactTreeRef.current = true,
    onBlurCapture: () => isFocusInsideReactTreeRef.current = false
  };
}
function dispatchUpdate() {
  const event = new CustomEvent(CONTEXT_UPDATE);
  document.dispatchEvent(event);
}
function handleAndDispatchCustomEvent(name, handler, detail, { discrete }) {
  const target = detail.originalEvent.target;
  const event = new CustomEvent(name, { bubbles: false, cancelable: true, detail });
  if (handler) target.addEventListener(name, handler, { once: true });
  if (discrete) {
    dispatchDiscreteCustomEvent(target, event);
  } else {
    target.dispatchEvent(event);
  }
}

// node_modules/@radix-ui/react-focus-scope/dist/index.mjs
var React16 = __toESM(require_react(), 1);

// node_modules/@radix-ui/react-focus-scope/node_modules/@radix-ui/react-compose-refs/dist/index.mjs
var React12 = __toESM(require_react(), 1);
function setRef3(ref, value) {
  if (typeof ref === "function") {
    return ref(value);
  } else if (ref !== null && ref !== void 0) {
    ref.current = value;
  }
}
function composeRefs3(...refs) {
  return (node) => {
    let hasCleanup = false;
    const cleanups = refs.map((ref) => {
      const cleanup = setRef3(ref, node);
      if (!hasCleanup && typeof cleanup == "function") {
        hasCleanup = true;
      }
      return cleanup;
    });
    if (hasCleanup) {
      return () => {
        for (let i = 0; i < cleanups.length; i++) {
          const cleanup = cleanups[i];
          if (typeof cleanup == "function") {
            cleanup();
          } else {
            setRef3(refs[i], null);
          }
        }
      };
    }
  };
}
function useComposedRefs3(...refs) {
  return React12.useCallback(composeRefs3(...refs), refs);
}

// node_modules/@radix-ui/react-focus-scope/node_modules/@radix-ui/react-primitive/dist/index.mjs
var React14 = __toESM(require_react(), 1);
var ReactDOM2 = __toESM(require_react_dom(), 1);

// node_modules/@radix-ui/react-focus-scope/node_modules/@radix-ui/react-slot/dist/index.mjs
var React13 = __toESM(require_react(), 1);
var import_jsx_runtime5 = __toESM(require_jsx_runtime(), 1);
// @__NO_SIDE_EFFECTS__
function createSlot2(ownerName) {
  const SlotClone = /* @__PURE__ */ createSlotClone2(ownerName);
  const Slot2 = React13.forwardRef((props, forwardedRef) => {
    const { children, ...slotProps } = props;
    const childrenArray = React13.Children.toArray(children);
    const slottable = childrenArray.find(isSlottable2);
    if (slottable) {
      const newElement = slottable.props.children;
      const newChildren = childrenArray.map((child) => {
        if (child === slottable) {
          if (React13.Children.count(newElement) > 1) return React13.Children.only(null);
          return React13.isValidElement(newElement) ? newElement.props.children : null;
        } else {
          return child;
        }
      });
      return /* @__PURE__ */ (0, import_jsx_runtime5.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children: React13.isValidElement(newElement) ? React13.cloneElement(newElement, void 0, newChildren) : null });
    }
    return /* @__PURE__ */ (0, import_jsx_runtime5.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children });
  });
  Slot2.displayName = `${ownerName}.Slot`;
  return Slot2;
}
// @__NO_SIDE_EFFECTS__
function createSlotClone2(ownerName) {
  const SlotClone = React13.forwardRef((props, forwardedRef) => {
    const { children, ...slotProps } = props;
    if (React13.isValidElement(children)) {
      const childrenRef = getElementRef2(children);
      const props2 = mergeProps2(slotProps, children.props);
      if (children.type !== React13.Fragment) {
        props2.ref = forwardedRef ? composeRefs3(forwardedRef, childrenRef) : childrenRef;
      }
      return React13.cloneElement(children, props2);
    }
    return React13.Children.count(children) > 1 ? React13.Children.only(null) : null;
  });
  SlotClone.displayName = `${ownerName}.SlotClone`;
  return SlotClone;
}
var SLOTTABLE_IDENTIFIER2 = /* @__PURE__ */ Symbol("radix.slottable");
function isSlottable2(child) {
  return React13.isValidElement(child) && typeof child.type === "function" && "__radixId" in child.type && child.type.__radixId === SLOTTABLE_IDENTIFIER2;
}
function mergeProps2(slotProps, childProps) {
  const overrideProps = { ...childProps };
  for (const propName in childProps) {
    const slotPropValue = slotProps[propName];
    const childPropValue = childProps[propName];
    const isHandler = /^on[A-Z]/.test(propName);
    if (isHandler) {
      if (slotPropValue && childPropValue) {
        overrideProps[propName] = (...args) => {
          const result = childPropValue(...args);
          slotPropValue(...args);
          return result;
        };
      } else if (slotPropValue) {
        overrideProps[propName] = slotPropValue;
      }
    } else if (propName === "style") {
      overrideProps[propName] = { ...slotPropValue, ...childPropValue };
    } else if (propName === "className") {
      overrideProps[propName] = [slotPropValue, childPropValue].filter(Boolean).join(" ");
    }
  }
  return { ...slotProps, ...overrideProps };
}
function getElementRef2(element) {
  let getter = Object.getOwnPropertyDescriptor(element.props, "ref")?.get;
  let mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.ref;
  }
  getter = Object.getOwnPropertyDescriptor(element, "ref")?.get;
  mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.props.ref;
  }
  return element.props.ref || element.ref;
}

// node_modules/@radix-ui/react-focus-scope/node_modules/@radix-ui/react-primitive/dist/index.mjs
var import_jsx_runtime6 = __toESM(require_jsx_runtime(), 1);
var NODES2 = [
  "a",
  "button",
  "div",
  "form",
  "h2",
  "h3",
  "img",
  "input",
  "label",
  "li",
  "nav",
  "ol",
  "p",
  "select",
  "span",
  "svg",
  "ul"
];
var Primitive2 = NODES2.reduce((primitive, node) => {
  const Slot2 = createSlot2(`Primitive.${node}`);
  const Node2 = React14.forwardRef((props, forwardedRef) => {
    const { asChild, ...primitiveProps } = props;
    const Comp = asChild ? Slot2 : node;
    if (typeof window !== "undefined") {
      window[/* @__PURE__ */ Symbol.for("radix-ui")] = true;
    }
    return /* @__PURE__ */ (0, import_jsx_runtime6.jsx)(Comp, { ...primitiveProps, ref: forwardedRef });
  });
  Node2.displayName = `Primitive.${node}`;
  return { ...primitive, [node]: Node2 };
}, {});

// node_modules/@radix-ui/react-focus-scope/node_modules/@radix-ui/react-use-callback-ref/dist/index.mjs
var React15 = __toESM(require_react(), 1);
function useCallbackRef2(callback) {
  const callbackRef = React15.useRef(callback);
  React15.useEffect(() => {
    callbackRef.current = callback;
  });
  return React15.useMemo(() => (...args) => callbackRef.current?.(...args), []);
}

// node_modules/@radix-ui/react-focus-scope/dist/index.mjs
var import_jsx_runtime7 = __toESM(require_jsx_runtime(), 1);
var AUTOFOCUS_ON_MOUNT = "focusScope.autoFocusOnMount";
var AUTOFOCUS_ON_UNMOUNT = "focusScope.autoFocusOnUnmount";
var EVENT_OPTIONS = { bubbles: false, cancelable: true };
var FOCUS_SCOPE_NAME = "FocusScope";
var FocusScope = React16.forwardRef((props, forwardedRef) => {
  const {
    loop = false,
    trapped = false,
    onMountAutoFocus: onMountAutoFocusProp,
    onUnmountAutoFocus: onUnmountAutoFocusProp,
    ...scopeProps
  } = props;
  const [container, setContainer] = React16.useState(null);
  const onMountAutoFocus = useCallbackRef2(onMountAutoFocusProp);
  const onUnmountAutoFocus = useCallbackRef2(onUnmountAutoFocusProp);
  const lastFocusedElementRef = React16.useRef(null);
  const composedRefs = useComposedRefs3(forwardedRef, (node) => setContainer(node));
  const focusScope = React16.useRef({
    paused: false,
    pause() {
      this.paused = true;
    },
    resume() {
      this.paused = false;
    }
  }).current;
  React16.useEffect(() => {
    if (trapped) {
      let handleFocusIn2 = function(event) {
        if (focusScope.paused || !container) return;
        const target = event.target;
        if (container.contains(target)) {
          lastFocusedElementRef.current = target;
        } else {
          focus(lastFocusedElementRef.current, { select: true });
        }
      }, handleFocusOut2 = function(event) {
        if (focusScope.paused || !container) return;
        const relatedTarget = event.relatedTarget;
        if (relatedTarget === null) return;
        if (!container.contains(relatedTarget)) {
          focus(lastFocusedElementRef.current, { select: true });
        }
      }, handleMutations2 = function(mutations) {
        const focusedElement = document.activeElement;
        if (focusedElement !== document.body) return;
        for (const mutation of mutations) {
          if (mutation.removedNodes.length > 0) focus(container);
        }
      };
      var handleFocusIn = handleFocusIn2, handleFocusOut = handleFocusOut2, handleMutations = handleMutations2;
      document.addEventListener("focusin", handleFocusIn2);
      document.addEventListener("focusout", handleFocusOut2);
      const mutationObserver = new MutationObserver(handleMutations2);
      if (container) mutationObserver.observe(container, { childList: true, subtree: true });
      return () => {
        document.removeEventListener("focusin", handleFocusIn2);
        document.removeEventListener("focusout", handleFocusOut2);
        mutationObserver.disconnect();
      };
    }
  }, [trapped, container, focusScope.paused]);
  React16.useEffect(() => {
    if (container) {
      focusScopesStack.add(focusScope);
      const previouslyFocusedElement = document.activeElement;
      const hasFocusedCandidate = container.contains(previouslyFocusedElement);
      if (!hasFocusedCandidate) {
        const mountEvent = new CustomEvent(AUTOFOCUS_ON_MOUNT, EVENT_OPTIONS);
        container.addEventListener(AUTOFOCUS_ON_MOUNT, onMountAutoFocus);
        container.dispatchEvent(mountEvent);
        if (!mountEvent.defaultPrevented) {
          focusFirst(removeLinks(getTabbableCandidates(container)), { select: true });
          if (document.activeElement === previouslyFocusedElement) {
            focus(container);
          }
        }
      }
      return () => {
        container.removeEventListener(AUTOFOCUS_ON_MOUNT, onMountAutoFocus);
        setTimeout(() => {
          const unmountEvent = new CustomEvent(AUTOFOCUS_ON_UNMOUNT, EVENT_OPTIONS);
          container.addEventListener(AUTOFOCUS_ON_UNMOUNT, onUnmountAutoFocus);
          container.dispatchEvent(unmountEvent);
          if (!unmountEvent.defaultPrevented) {
            focus(previouslyFocusedElement ?? document.body, { select: true });
          }
          container.removeEventListener(AUTOFOCUS_ON_UNMOUNT, onUnmountAutoFocus);
          focusScopesStack.remove(focusScope);
        }, 0);
      };
    }
  }, [container, onMountAutoFocus, onUnmountAutoFocus, focusScope]);
  const handleKeyDown = React16.useCallback(
    (event) => {
      if (!loop && !trapped) return;
      if (focusScope.paused) return;
      const isTabKey = event.key === "Tab" && !event.altKey && !event.ctrlKey && !event.metaKey;
      const focusedElement = document.activeElement;
      if (isTabKey && focusedElement) {
        const container2 = event.currentTarget;
        const [first, last] = getTabbableEdges(container2);
        const hasTabbableElementsInside = first && last;
        if (!hasTabbableElementsInside) {
          if (focusedElement === container2) event.preventDefault();
        } else {
          if (!event.shiftKey && focusedElement === last) {
            event.preventDefault();
            if (loop) focus(first, { select: true });
          } else if (event.shiftKey && focusedElement === first) {
            event.preventDefault();
            if (loop) focus(last, { select: true });
          }
        }
      }
    },
    [loop, trapped, focusScope.paused]
  );
  return /* @__PURE__ */ (0, import_jsx_runtime7.jsx)(Primitive2.div, { tabIndex: -1, ...scopeProps, ref: composedRefs, onKeyDown: handleKeyDown });
});
FocusScope.displayName = FOCUS_SCOPE_NAME;
function focusFirst(candidates, { select = false } = {}) {
  const previouslyFocusedElement = document.activeElement;
  for (const candidate of candidates) {
    focus(candidate, { select });
    if (document.activeElement !== previouslyFocusedElement) return;
  }
}
function getTabbableEdges(container) {
  const candidates = getTabbableCandidates(container);
  const first = findVisible(candidates, container);
  const last = findVisible(candidates.reverse(), container);
  return [first, last];
}
function getTabbableCandidates(container) {
  const nodes = [];
  const walker = document.createTreeWalker(container, NodeFilter.SHOW_ELEMENT, {
    acceptNode: (node) => {
      const isHiddenInput = node.tagName === "INPUT" && node.type === "hidden";
      if (node.disabled || node.hidden || isHiddenInput) return NodeFilter.FILTER_SKIP;
      return node.tabIndex >= 0 ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP;
    }
  });
  while (walker.nextNode()) nodes.push(walker.currentNode);
  return nodes;
}
function findVisible(elements, container) {
  for (const element of elements) {
    if (!isHidden(element, { upTo: container })) return element;
  }
}
function isHidden(node, { upTo }) {
  if (getComputedStyle(node).visibility === "hidden") return true;
  while (node) {
    if (upTo !== void 0 && node === upTo) return false;
    if (getComputedStyle(node).display === "none") return true;
    node = node.parentElement;
  }
  return false;
}
function isSelectableInput(element) {
  return element instanceof HTMLInputElement && "select" in element;
}
function focus(element, { select = false } = {}) {
  if (element && element.focus) {
    const previouslyFocusedElement = document.activeElement;
    element.focus({ preventScroll: true });
    if (element !== previouslyFocusedElement && isSelectableInput(element) && select)
      element.select();
  }
}
var focusScopesStack = createFocusScopesStack();
function createFocusScopesStack() {
  let stack = [];
  return {
    add(focusScope) {
      const activeFocusScope = stack[0];
      if (focusScope !== activeFocusScope) {
        activeFocusScope?.pause();
      }
      stack = arrayRemove(stack, focusScope);
      stack.unshift(focusScope);
    },
    remove(focusScope) {
      stack = arrayRemove(stack, focusScope);
      stack[0]?.resume();
    }
  };
}
function arrayRemove(array, item) {
  const updatedArray = [...array];
  const index = updatedArray.indexOf(item);
  if (index !== -1) {
    updatedArray.splice(index, 1);
  }
  return updatedArray;
}
function removeLinks(items) {
  return items.filter((item) => item.tagName !== "A");
}

// node_modules/@radix-ui/react-portal/dist/index.mjs
var React21 = __toESM(require_react(), 1);
var import_react_dom = __toESM(require_react_dom(), 1);

// node_modules/@radix-ui/react-portal/node_modules/@radix-ui/react-primitive/dist/index.mjs
var React19 = __toESM(require_react(), 1);
var ReactDOM3 = __toESM(require_react_dom(), 1);

// node_modules/@radix-ui/react-portal/node_modules/@radix-ui/react-slot/dist/index.mjs
var React18 = __toESM(require_react(), 1);

// node_modules/@radix-ui/react-portal/node_modules/@radix-ui/react-compose-refs/dist/index.mjs
var React17 = __toESM(require_react(), 1);
function setRef4(ref, value) {
  if (typeof ref === "function") {
    return ref(value);
  } else if (ref !== null && ref !== void 0) {
    ref.current = value;
  }
}
function composeRefs4(...refs) {
  return (node) => {
    let hasCleanup = false;
    const cleanups = refs.map((ref) => {
      const cleanup = setRef4(ref, node);
      if (!hasCleanup && typeof cleanup == "function") {
        hasCleanup = true;
      }
      return cleanup;
    });
    if (hasCleanup) {
      return () => {
        for (let i = 0; i < cleanups.length; i++) {
          const cleanup = cleanups[i];
          if (typeof cleanup == "function") {
            cleanup();
          } else {
            setRef4(refs[i], null);
          }
        }
      };
    }
  };
}

// node_modules/@radix-ui/react-portal/node_modules/@radix-ui/react-slot/dist/index.mjs
var import_jsx_runtime8 = __toESM(require_jsx_runtime(), 1);
// @__NO_SIDE_EFFECTS__
function createSlot3(ownerName) {
  const SlotClone = /* @__PURE__ */ createSlotClone3(ownerName);
  const Slot2 = React18.forwardRef((props, forwardedRef) => {
    const { children, ...slotProps } = props;
    const childrenArray = React18.Children.toArray(children);
    const slottable = childrenArray.find(isSlottable3);
    if (slottable) {
      const newElement = slottable.props.children;
      const newChildren = childrenArray.map((child) => {
        if (child === slottable) {
          if (React18.Children.count(newElement) > 1) return React18.Children.only(null);
          return React18.isValidElement(newElement) ? newElement.props.children : null;
        } else {
          return child;
        }
      });
      return /* @__PURE__ */ (0, import_jsx_runtime8.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children: React18.isValidElement(newElement) ? React18.cloneElement(newElement, void 0, newChildren) : null });
    }
    return /* @__PURE__ */ (0, import_jsx_runtime8.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children });
  });
  Slot2.displayName = `${ownerName}.Slot`;
  return Slot2;
}
// @__NO_SIDE_EFFECTS__
function createSlotClone3(ownerName) {
  const SlotClone = React18.forwardRef((props, forwardedRef) => {
    const { children, ...slotProps } = props;
    if (React18.isValidElement(children)) {
      const childrenRef = getElementRef3(children);
      const props2 = mergeProps3(slotProps, children.props);
      if (children.type !== React18.Fragment) {
        props2.ref = forwardedRef ? composeRefs4(forwardedRef, childrenRef) : childrenRef;
      }
      return React18.cloneElement(children, props2);
    }
    return React18.Children.count(children) > 1 ? React18.Children.only(null) : null;
  });
  SlotClone.displayName = `${ownerName}.SlotClone`;
  return SlotClone;
}
var SLOTTABLE_IDENTIFIER3 = /* @__PURE__ */ Symbol("radix.slottable");
function isSlottable3(child) {
  return React18.isValidElement(child) && typeof child.type === "function" && "__radixId" in child.type && child.type.__radixId === SLOTTABLE_IDENTIFIER3;
}
function mergeProps3(slotProps, childProps) {
  const overrideProps = { ...childProps };
  for (const propName in childProps) {
    const slotPropValue = slotProps[propName];
    const childPropValue = childProps[propName];
    const isHandler = /^on[A-Z]/.test(propName);
    if (isHandler) {
      if (slotPropValue && childPropValue) {
        overrideProps[propName] = (...args) => {
          const result = childPropValue(...args);
          slotPropValue(...args);
          return result;
        };
      } else if (slotPropValue) {
        overrideProps[propName] = slotPropValue;
      }
    } else if (propName === "style") {
      overrideProps[propName] = { ...slotPropValue, ...childPropValue };
    } else if (propName === "className") {
      overrideProps[propName] = [slotPropValue, childPropValue].filter(Boolean).join(" ");
    }
  }
  return { ...slotProps, ...overrideProps };
}
function getElementRef3(element) {
  let getter = Object.getOwnPropertyDescriptor(element.props, "ref")?.get;
  let mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.ref;
  }
  getter = Object.getOwnPropertyDescriptor(element, "ref")?.get;
  mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.props.ref;
  }
  return element.props.ref || element.ref;
}

// node_modules/@radix-ui/react-portal/node_modules/@radix-ui/react-primitive/dist/index.mjs
var import_jsx_runtime9 = __toESM(require_jsx_runtime(), 1);
var NODES3 = [
  "a",
  "button",
  "div",
  "form",
  "h2",
  "h3",
  "img",
  "input",
  "label",
  "li",
  "nav",
  "ol",
  "p",
  "select",
  "span",
  "svg",
  "ul"
];
var Primitive3 = NODES3.reduce((primitive, node) => {
  const Slot2 = createSlot3(`Primitive.${node}`);
  const Node2 = React19.forwardRef((props, forwardedRef) => {
    const { asChild, ...primitiveProps } = props;
    const Comp = asChild ? Slot2 : node;
    if (typeof window !== "undefined") {
      window[/* @__PURE__ */ Symbol.for("radix-ui")] = true;
    }
    return /* @__PURE__ */ (0, import_jsx_runtime9.jsx)(Comp, { ...primitiveProps, ref: forwardedRef });
  });
  Node2.displayName = `Primitive.${node}`;
  return { ...primitive, [node]: Node2 };
}, {});

// node_modules/@radix-ui/react-portal/node_modules/@radix-ui/react-use-layout-effect/dist/index.mjs
var React20 = __toESM(require_react(), 1);
var useLayoutEffect22 = globalThis?.document ? React20.useLayoutEffect : () => {
};

// node_modules/@radix-ui/react-portal/dist/index.mjs
var import_jsx_runtime10 = __toESM(require_jsx_runtime(), 1);
var PORTAL_NAME = "Portal";
var Portal = React21.forwardRef((props, forwardedRef) => {
  const { container: containerProp, ...portalProps } = props;
  const [mounted, setMounted] = React21.useState(false);
  useLayoutEffect22(() => setMounted(true), []);
  const container = containerProp || mounted && globalThis?.document?.body;
  return container ? import_react_dom.default.createPortal(/* @__PURE__ */ (0, import_jsx_runtime10.jsx)(Primitive3.div, { ...portalProps, ref: forwardedRef }), container) : null;
});
Portal.displayName = PORTAL_NAME;

// node_modules/@radix-ui/react-presence/dist/index.mjs
var React25 = __toESM(require_react(), 1);

// node_modules/@radix-ui/react-presence/node_modules/@radix-ui/react-compose-refs/dist/index.mjs
var React23 = __toESM(require_react(), 1);
function setRef5(ref, value) {
  if (typeof ref === "function") {
    return ref(value);
  } else if (ref !== null && ref !== void 0) {
    ref.current = value;
  }
}
function composeRefs5(...refs) {
  return (node) => {
    let hasCleanup = false;
    const cleanups = refs.map((ref) => {
      const cleanup = setRef5(ref, node);
      if (!hasCleanup && typeof cleanup == "function") {
        hasCleanup = true;
      }
      return cleanup;
    });
    if (hasCleanup) {
      return () => {
        for (let i = 0; i < cleanups.length; i++) {
          const cleanup = cleanups[i];
          if (typeof cleanup == "function") {
            cleanup();
          } else {
            setRef5(refs[i], null);
          }
        }
      };
    }
  };
}
function useComposedRefs4(...refs) {
  return React23.useCallback(composeRefs5(...refs), refs);
}

// node_modules/@radix-ui/react-presence/node_modules/@radix-ui/react-use-layout-effect/dist/index.mjs
var React24 = __toESM(require_react(), 1);
var useLayoutEffect23 = globalThis?.document ? React24.useLayoutEffect : () => {
};

// node_modules/@radix-ui/react-presence/dist/index.mjs
var React26 = __toESM(require_react(), 1);
function useStateMachine(initialState, machine) {
  return React26.useReducer((state, event) => {
    const nextState = machine[state][event];
    return nextState ?? state;
  }, initialState);
}
var Presence = (props) => {
  const { present, children } = props;
  const presence = usePresence(present);
  const child = typeof children === "function" ? children({ present: presence.isPresent }) : React25.Children.only(children);
  const ref = useComposedRefs4(presence.ref, getElementRef4(child));
  const forceMount = typeof children === "function";
  return forceMount || presence.isPresent ? React25.cloneElement(child, { ref }) : null;
};
Presence.displayName = "Presence";
function usePresence(present) {
  const [node, setNode] = React25.useState();
  const stylesRef = React25.useRef(null);
  const prevPresentRef = React25.useRef(present);
  const prevAnimationNameRef = React25.useRef("none");
  const initialState = present ? "mounted" : "unmounted";
  const [state, send] = useStateMachine(initialState, {
    mounted: {
      UNMOUNT: "unmounted",
      ANIMATION_OUT: "unmountSuspended"
    },
    unmountSuspended: {
      MOUNT: "mounted",
      ANIMATION_END: "unmounted"
    },
    unmounted: {
      MOUNT: "mounted"
    }
  });
  React25.useEffect(() => {
    const currentAnimationName = getAnimationName(stylesRef.current);
    prevAnimationNameRef.current = state === "mounted" ? currentAnimationName : "none";
  }, [state]);
  useLayoutEffect23(() => {
    const styles = stylesRef.current;
    const wasPresent = prevPresentRef.current;
    const hasPresentChanged = wasPresent !== present;
    if (hasPresentChanged) {
      const prevAnimationName = prevAnimationNameRef.current;
      const currentAnimationName = getAnimationName(styles);
      if (present) {
        send("MOUNT");
      } else if (currentAnimationName === "none" || styles?.display === "none") {
        send("UNMOUNT");
      } else {
        const isAnimating = prevAnimationName !== currentAnimationName;
        if (wasPresent && isAnimating) {
          send("ANIMATION_OUT");
        } else {
          send("UNMOUNT");
        }
      }
      prevPresentRef.current = present;
    }
  }, [present, send]);
  useLayoutEffect23(() => {
    if (node) {
      let timeoutId;
      const ownerWindow = node.ownerDocument.defaultView ?? window;
      const handleAnimationEnd = (event) => {
        const currentAnimationName = getAnimationName(stylesRef.current);
        const isCurrentAnimation = currentAnimationName.includes(CSS.escape(event.animationName));
        if (event.target === node && isCurrentAnimation) {
          send("ANIMATION_END");
          if (!prevPresentRef.current) {
            const currentFillMode = node.style.animationFillMode;
            node.style.animationFillMode = "forwards";
            timeoutId = ownerWindow.setTimeout(() => {
              if (node.style.animationFillMode === "forwards") {
                node.style.animationFillMode = currentFillMode;
              }
            });
          }
        }
      };
      const handleAnimationStart = (event) => {
        if (event.target === node) {
          prevAnimationNameRef.current = getAnimationName(stylesRef.current);
        }
      };
      node.addEventListener("animationstart", handleAnimationStart);
      node.addEventListener("animationcancel", handleAnimationEnd);
      node.addEventListener("animationend", handleAnimationEnd);
      return () => {
        ownerWindow.clearTimeout(timeoutId);
        node.removeEventListener("animationstart", handleAnimationStart);
        node.removeEventListener("animationcancel", handleAnimationEnd);
        node.removeEventListener("animationend", handleAnimationEnd);
      };
    } else {
      send("ANIMATION_END");
    }
  }, [node, send]);
  return {
    isPresent: ["mounted", "unmountSuspended"].includes(state),
    ref: React25.useCallback((node2) => {
      stylesRef.current = node2 ? getComputedStyle(node2) : null;
      setNode(node2);
    }, [])
  };
}
function getAnimationName(styles) {
  return styles?.animationName || "none";
}
function getElementRef4(element) {
  let getter = Object.getOwnPropertyDescriptor(element.props, "ref")?.get;
  let mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.ref;
  }
  getter = Object.getOwnPropertyDescriptor(element, "ref")?.get;
  mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.props.ref;
  }
  return element.props.ref || element.ref;
}

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-primitive/dist/index.mjs
var React28 = __toESM(require_react(), 1);
var ReactDOM5 = __toESM(require_react_dom(), 1);

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-slot/dist/index.mjs
var React27 = __toESM(require_react(), 1);
var import_jsx_runtime11 = __toESM(require_jsx_runtime(), 1);
// @__NO_SIDE_EFFECTS__
function createSlot4(ownerName) {
  const SlotClone = /* @__PURE__ */ createSlotClone4(ownerName);
  const Slot2 = React27.forwardRef((props, forwardedRef) => {
    const { children, ...slotProps } = props;
    const childrenArray = React27.Children.toArray(children);
    const slottable = childrenArray.find(isSlottable4);
    if (slottable) {
      const newElement = slottable.props.children;
      const newChildren = childrenArray.map((child) => {
        if (child === slottable) {
          if (React27.Children.count(newElement) > 1) return React27.Children.only(null);
          return React27.isValidElement(newElement) ? newElement.props.children : null;
        } else {
          return child;
        }
      });
      return /* @__PURE__ */ (0, import_jsx_runtime11.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children: React27.isValidElement(newElement) ? React27.cloneElement(newElement, void 0, newChildren) : null });
    }
    return /* @__PURE__ */ (0, import_jsx_runtime11.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children });
  });
  Slot2.displayName = `${ownerName}.Slot`;
  return Slot2;
}
// @__NO_SIDE_EFFECTS__
function createSlotClone4(ownerName) {
  const SlotClone = React27.forwardRef((props, forwardedRef) => {
    const { children, ...slotProps } = props;
    if (React27.isValidElement(children)) {
      const childrenRef = getElementRef5(children);
      const props2 = mergeProps4(slotProps, children.props);
      if (children.type !== React27.Fragment) {
        props2.ref = forwardedRef ? composeRefs(forwardedRef, childrenRef) : childrenRef;
      }
      return React27.cloneElement(children, props2);
    }
    return React27.Children.count(children) > 1 ? React27.Children.only(null) : null;
  });
  SlotClone.displayName = `${ownerName}.SlotClone`;
  return SlotClone;
}
var SLOTTABLE_IDENTIFIER4 = /* @__PURE__ */ Symbol("radix.slottable");
function isSlottable4(child) {
  return React27.isValidElement(child) && typeof child.type === "function" && "__radixId" in child.type && child.type.__radixId === SLOTTABLE_IDENTIFIER4;
}
function mergeProps4(slotProps, childProps) {
  const overrideProps = { ...childProps };
  for (const propName in childProps) {
    const slotPropValue = slotProps[propName];
    const childPropValue = childProps[propName];
    const isHandler = /^on[A-Z]/.test(propName);
    if (isHandler) {
      if (slotPropValue && childPropValue) {
        overrideProps[propName] = (...args) => {
          const result = childPropValue(...args);
          slotPropValue(...args);
          return result;
        };
      } else if (slotPropValue) {
        overrideProps[propName] = slotPropValue;
      }
    } else if (propName === "style") {
      overrideProps[propName] = { ...slotPropValue, ...childPropValue };
    } else if (propName === "className") {
      overrideProps[propName] = [slotPropValue, childPropValue].filter(Boolean).join(" ");
    }
  }
  return { ...slotProps, ...overrideProps };
}
function getElementRef5(element) {
  let getter = Object.getOwnPropertyDescriptor(element.props, "ref")?.get;
  let mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.ref;
  }
  getter = Object.getOwnPropertyDescriptor(element, "ref")?.get;
  mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.props.ref;
  }
  return element.props.ref || element.ref;
}

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-primitive/dist/index.mjs
var import_jsx_runtime12 = __toESM(require_jsx_runtime(), 1);
var NODES4 = [
  "a",
  "button",
  "div",
  "form",
  "h2",
  "h3",
  "img",
  "input",
  "label",
  "li",
  "nav",
  "ol",
  "p",
  "select",
  "span",
  "svg",
  "ul"
];
var Primitive4 = NODES4.reduce((primitive, node) => {
  const Slot2 = createSlot4(`Primitive.${node}`);
  const Node2 = React28.forwardRef((props, forwardedRef) => {
    const { asChild, ...primitiveProps } = props;
    const Comp = asChild ? Slot2 : node;
    if (typeof window !== "undefined") {
      window[/* @__PURE__ */ Symbol.for("radix-ui")] = true;
    }
    return /* @__PURE__ */ (0, import_jsx_runtime12.jsx)(Comp, { ...primitiveProps, ref: forwardedRef });
  });
  Node2.displayName = `Primitive.${node}`;
  return { ...primitive, [node]: Node2 };
}, {});

// node_modules/@radix-ui/react-dialog/node_modules/@radix-ui/react-focus-guards/dist/index.mjs
var React29 = __toESM(require_react(), 1);
var count2 = 0;
function useFocusGuards() {
  React29.useEffect(() => {
    const edgeGuards = document.querySelectorAll("[data-radix-focus-guard]");
    document.body.insertAdjacentElement("afterbegin", edgeGuards[0] ?? createFocusGuard());
    document.body.insertAdjacentElement("beforeend", edgeGuards[1] ?? createFocusGuard());
    count2++;
    return () => {
      if (count2 === 1) {
        document.querySelectorAll("[data-radix-focus-guard]").forEach((node) => node.remove());
      }
      count2--;
    };
  }, []);
}
function createFocusGuard() {
  const element = document.createElement("span");
  element.setAttribute("data-radix-focus-guard", "");
  element.tabIndex = 0;
  element.style.outline = "none";
  element.style.opacity = "0";
  element.style.position = "fixed";
  element.style.pointerEvents = "none";
  return element;
}

// node_modules/tslib/tslib.es6.mjs
var __assign = function() {
  __assign = Object.assign || function __assign2(t2) {
    for (var s, i = 1, n = arguments.length; i < n; i++) {
      s = arguments[i];
      for (var p2 in s) if (Object.prototype.hasOwnProperty.call(s, p2)) t2[p2] = s[p2];
    }
    return t2;
  };
  return __assign.apply(this, arguments);
};
function __rest(s, e) {
  var t2 = {};
  for (var p2 in s) if (Object.prototype.hasOwnProperty.call(s, p2) && e.indexOf(p2) < 0)
    t2[p2] = s[p2];
  if (s != null && typeof Object.getOwnPropertySymbols === "function")
    for (var i = 0, p2 = Object.getOwnPropertySymbols(s); i < p2.length; i++) {
      if (e.indexOf(p2[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p2[i]))
        t2[p2[i]] = s[p2[i]];
    }
  return t2;
}
function __spreadArray(to, from, pack) {
  if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
    if (ar || !(i in from)) {
      if (!ar) ar = Array.prototype.slice.call(from, 0, i);
      ar[i] = from[i];
    }
  }
  return to.concat(ar || Array.prototype.slice.call(from));
}

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/Combination.js
var React36 = __toESM(require_react());

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/UI.js
var React32 = __toESM(require_react());

// node_modules/react-remove-scroll-bar/dist/es2015/constants.js
var zeroRightClassName = "right-scroll-bar-position";
var fullWidthClassName = "width-before-scroll-bar";
var noScrollbarsClassName = "with-scroll-bars-hidden";
var removedBarSizeVariable = "--removed-body-scroll-bar-size";

// node_modules/use-callback-ref/dist/es2015/assignRef.js
function assignRef(ref, value) {
  if (typeof ref === "function") {
    ref(value);
  } else if (ref) {
    ref.current = value;
  }
  return ref;
}

// node_modules/use-callback-ref/dist/es2015/useRef.js
var import_react = __toESM(require_react());
function useCallbackRef3(initialValue, callback) {
  var ref = (0, import_react.useState)(function() {
    return {
      // value
      value: initialValue,
      // last callback
      callback,
      // "memoized" public interface
      facade: {
        get current() {
          return ref.value;
        },
        set current(value) {
          var last = ref.value;
          if (last !== value) {
            ref.value = value;
            ref.callback(value, last);
          }
        }
      }
    };
  })[0];
  ref.callback = callback;
  return ref.facade;
}

// node_modules/use-callback-ref/dist/es2015/useMergeRef.js
var React30 = __toESM(require_react());
var useIsomorphicLayoutEffect = typeof window !== "undefined" ? React30.useLayoutEffect : React30.useEffect;
var currentValues = /* @__PURE__ */ new WeakMap();
function useMergeRefs(refs, defaultValue) {
  var callbackRef = useCallbackRef3(defaultValue || null, function(newValue) {
    return refs.forEach(function(ref) {
      return assignRef(ref, newValue);
    });
  });
  useIsomorphicLayoutEffect(function() {
    var oldValue = currentValues.get(callbackRef);
    if (oldValue) {
      var prevRefs_1 = new Set(oldValue);
      var nextRefs_1 = new Set(refs);
      var current_1 = callbackRef.current;
      prevRefs_1.forEach(function(ref) {
        if (!nextRefs_1.has(ref)) {
          assignRef(ref, null);
        }
      });
      nextRefs_1.forEach(function(ref) {
        if (!prevRefs_1.has(ref)) {
          assignRef(ref, current_1);
        }
      });
    }
    currentValues.set(callbackRef, refs);
  }, [refs]);
  return callbackRef;
}

// node_modules/use-sidecar/dist/es2015/medium.js
function ItoI(a) {
  return a;
}
function innerCreateMedium(defaults, middleware) {
  if (middleware === void 0) {
    middleware = ItoI;
  }
  var buffer = [];
  var assigned = false;
  var medium = {
    read: function() {
      if (assigned) {
        throw new Error("Sidecar: could not `read` from an `assigned` medium. `read` could be used only with `useMedium`.");
      }
      if (buffer.length) {
        return buffer[buffer.length - 1];
      }
      return defaults;
    },
    useMedium: function(data) {
      var item = middleware(data, assigned);
      buffer.push(item);
      return function() {
        buffer = buffer.filter(function(x) {
          return x !== item;
        });
      };
    },
    assignSyncMedium: function(cb) {
      assigned = true;
      while (buffer.length) {
        var cbs = buffer;
        buffer = [];
        cbs.forEach(cb);
      }
      buffer = {
        push: function(x) {
          return cb(x);
        },
        filter: function() {
          return buffer;
        }
      };
    },
    assignMedium: function(cb) {
      assigned = true;
      var pendingQueue = [];
      if (buffer.length) {
        var cbs = buffer;
        buffer = [];
        cbs.forEach(cb);
        pendingQueue = buffer;
      }
      var executeQueue = function() {
        var cbs2 = pendingQueue;
        pendingQueue = [];
        cbs2.forEach(cb);
      };
      var cycle = function() {
        return Promise.resolve().then(executeQueue);
      };
      cycle();
      buffer = {
        push: function(x) {
          pendingQueue.push(x);
          cycle();
        },
        filter: function(filter) {
          pendingQueue = pendingQueue.filter(filter);
          return buffer;
        }
      };
    }
  };
  return medium;
}
function createSidecarMedium(options) {
  if (options === void 0) {
    options = {};
  }
  var medium = innerCreateMedium(null);
  medium.options = __assign({ async: true, ssr: false }, options);
  return medium;
}

// node_modules/use-sidecar/dist/es2015/exports.js
var React31 = __toESM(require_react());
var SideCar = function(_a) {
  var sideCar = _a.sideCar, rest = __rest(_a, ["sideCar"]);
  if (!sideCar) {
    throw new Error("Sidecar: please provide `sideCar` property to import the right car");
  }
  var Target = sideCar.read();
  if (!Target) {
    throw new Error("Sidecar medium not found");
  }
  return React31.createElement(Target, __assign({}, rest));
};
SideCar.isSideCarExport = true;
function exportSidecar(medium, exported) {
  medium.useMedium(exported);
  return SideCar;
}

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/medium.js
var effectCar = createSidecarMedium();

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/UI.js
var nothing = function() {
  return;
};
var RemoveScroll = React32.forwardRef(function(props, parentRef) {
  var ref = React32.useRef(null);
  var _a = React32.useState({
    onScrollCapture: nothing,
    onWheelCapture: nothing,
    onTouchMoveCapture: nothing
  }), callbacks = _a[0], setCallbacks = _a[1];
  var forwardProps = props.forwardProps, children = props.children, className = props.className, removeScrollBar = props.removeScrollBar, enabled = props.enabled, shards = props.shards, sideCar = props.sideCar, noRelative = props.noRelative, noIsolation = props.noIsolation, inert = props.inert, allowPinchZoom = props.allowPinchZoom, _b = props.as, Container = _b === void 0 ? "div" : _b, gapMode = props.gapMode, rest = __rest(props, ["forwardProps", "children", "className", "removeScrollBar", "enabled", "shards", "sideCar", "noRelative", "noIsolation", "inert", "allowPinchZoom", "as", "gapMode"]);
  var SideCar2 = sideCar;
  var containerRef = useMergeRefs([ref, parentRef]);
  var containerProps = __assign(__assign({}, rest), callbacks);
  return React32.createElement(
    React32.Fragment,
    null,
    enabled && React32.createElement(SideCar2, { sideCar: effectCar, removeScrollBar, shards, noRelative, noIsolation, inert, setCallbacks, allowPinchZoom: !!allowPinchZoom, lockRef: ref, gapMode }),
    forwardProps ? React32.cloneElement(React32.Children.only(children), __assign(__assign({}, containerProps), { ref: containerRef })) : React32.createElement(Container, __assign({}, containerProps, { className, ref: containerRef }), children)
  );
});
RemoveScroll.defaultProps = {
  enabled: true,
  removeScrollBar: true,
  inert: false
};
RemoveScroll.classNames = {
  fullWidth: fullWidthClassName,
  zeroRight: zeroRightClassName
};

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/SideEffect.js
var React35 = __toESM(require_react());

// node_modules/react-remove-scroll-bar/dist/es2015/component.js
var React34 = __toESM(require_react());

// node_modules/react-style-singleton/dist/es2015/hook.js
var React33 = __toESM(require_react());

// node_modules/get-nonce/dist/es2015/index.js
var currentNonce;
var getNonce = function() {
  if (currentNonce) {
    return currentNonce;
  }
  if (typeof __webpack_nonce__ !== "undefined") {
    return __webpack_nonce__;
  }
  return void 0;
};

// node_modules/react-style-singleton/dist/es2015/singleton.js
function makeStyleTag() {
  if (!document)
    return null;
  var tag = document.createElement("style");
  tag.type = "text/css";
  var nonce = getNonce();
  if (nonce) {
    tag.setAttribute("nonce", nonce);
  }
  return tag;
}
function injectStyles(tag, css2) {
  if (tag.styleSheet) {
    tag.styleSheet.cssText = css2;
  } else {
    tag.appendChild(document.createTextNode(css2));
  }
}
function insertStyleTag(tag) {
  var head = document.head || document.getElementsByTagName("head")[0];
  head.appendChild(tag);
}
var stylesheetSingleton = function() {
  var counter = 0;
  var stylesheet = null;
  return {
    add: function(style) {
      if (counter == 0) {
        if (stylesheet = makeStyleTag()) {
          injectStyles(stylesheet, style);
          insertStyleTag(stylesheet);
        }
      }
      counter++;
    },
    remove: function() {
      counter--;
      if (!counter && stylesheet) {
        stylesheet.parentNode && stylesheet.parentNode.removeChild(stylesheet);
        stylesheet = null;
      }
    }
  };
};

// node_modules/react-style-singleton/dist/es2015/hook.js
var styleHookSingleton = function() {
  var sheet = stylesheetSingleton();
  return function(styles, isDynamic) {
    React33.useEffect(function() {
      sheet.add(styles);
      return function() {
        sheet.remove();
      };
    }, [styles && isDynamic]);
  };
};

// node_modules/react-style-singleton/dist/es2015/component.js
var styleSingleton = function() {
  var useStyle = styleHookSingleton();
  var Sheet = function(_a) {
    var styles = _a.styles, dynamic = _a.dynamic;
    useStyle(styles, dynamic);
    return null;
  };
  return Sheet;
};

// node_modules/react-remove-scroll-bar/dist/es2015/utils.js
var zeroGap = {
  left: 0,
  top: 0,
  right: 0,
  gap: 0
};
var parse = function(x) {
  return parseInt(x || "", 10) || 0;
};
var getOffset = function(gapMode) {
  var cs = window.getComputedStyle(document.body);
  var left = cs[gapMode === "padding" ? "paddingLeft" : "marginLeft"];
  var top = cs[gapMode === "padding" ? "paddingTop" : "marginTop"];
  var right = cs[gapMode === "padding" ? "paddingRight" : "marginRight"];
  return [parse(left), parse(top), parse(right)];
};
var getGapWidth = function(gapMode) {
  if (gapMode === void 0) {
    gapMode = "margin";
  }
  if (typeof window === "undefined") {
    return zeroGap;
  }
  var offsets = getOffset(gapMode);
  var documentWidth = document.documentElement.clientWidth;
  var windowWidth = window.innerWidth;
  return {
    left: offsets[0],
    top: offsets[1],
    right: offsets[2],
    gap: Math.max(0, windowWidth - documentWidth + offsets[2] - offsets[0])
  };
};

// node_modules/react-remove-scroll-bar/dist/es2015/component.js
var Style = styleSingleton();
var lockAttribute = "data-scroll-locked";
var getStyles = function(_a, allowRelative, gapMode, important) {
  var left = _a.left, top = _a.top, right = _a.right, gap = _a.gap;
  if (gapMode === void 0) {
    gapMode = "margin";
  }
  return "\n  .".concat(noScrollbarsClassName, " {\n   overflow: hidden ").concat(important, ";\n   padding-right: ").concat(gap, "px ").concat(important, ";\n  }\n  body[").concat(lockAttribute, "] {\n    overflow: hidden ").concat(important, ";\n    overscroll-behavior: contain;\n    ").concat([
    allowRelative && "position: relative ".concat(important, ";"),
    gapMode === "margin" && "\n    padding-left: ".concat(left, "px;\n    padding-top: ").concat(top, "px;\n    padding-right: ").concat(right, "px;\n    margin-left:0;\n    margin-top:0;\n    margin-right: ").concat(gap, "px ").concat(important, ";\n    "),
    gapMode === "padding" && "padding-right: ".concat(gap, "px ").concat(important, ";")
  ].filter(Boolean).join(""), "\n  }\n  \n  .").concat(zeroRightClassName, " {\n    right: ").concat(gap, "px ").concat(important, ";\n  }\n  \n  .").concat(fullWidthClassName, " {\n    margin-right: ").concat(gap, "px ").concat(important, ";\n  }\n  \n  .").concat(zeroRightClassName, " .").concat(zeroRightClassName, " {\n    right: 0 ").concat(important, ";\n  }\n  \n  .").concat(fullWidthClassName, " .").concat(fullWidthClassName, " {\n    margin-right: 0 ").concat(important, ";\n  }\n  \n  body[").concat(lockAttribute, "] {\n    ").concat(removedBarSizeVariable, ": ").concat(gap, "px;\n  }\n");
};
var getCurrentUseCounter = function() {
  var counter = parseInt(document.body.getAttribute(lockAttribute) || "0", 10);
  return isFinite(counter) ? counter : 0;
};
var useLockAttribute = function() {
  React34.useEffect(function() {
    document.body.setAttribute(lockAttribute, (getCurrentUseCounter() + 1).toString());
    return function() {
      var newCounter = getCurrentUseCounter() - 1;
      if (newCounter <= 0) {
        document.body.removeAttribute(lockAttribute);
      } else {
        document.body.setAttribute(lockAttribute, newCounter.toString());
      }
    };
  }, []);
};
var RemoveScrollBar = function(_a) {
  var noRelative = _a.noRelative, noImportant = _a.noImportant, _b = _a.gapMode, gapMode = _b === void 0 ? "margin" : _b;
  useLockAttribute();
  var gap = React34.useMemo(function() {
    return getGapWidth(gapMode);
  }, [gapMode]);
  return React34.createElement(Style, { styles: getStyles(gap, !noRelative, gapMode, !noImportant ? "!important" : "") });
};

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/aggresiveCapture.js
var passiveSupported = false;
if (typeof window !== "undefined") {
  try {
    options = Object.defineProperty({}, "passive", {
      get: function() {
        passiveSupported = true;
        return true;
      }
    });
    window.addEventListener("test", options, options);
    window.removeEventListener("test", options, options);
  } catch (err) {
    passiveSupported = false;
  }
}
var options;
var nonPassive = passiveSupported ? { passive: false } : false;

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/handleScroll.js
var alwaysContainsScroll = function(node) {
  return node.tagName === "TEXTAREA";
};
var elementCanBeScrolled = function(node, overflow) {
  if (!(node instanceof Element)) {
    return false;
  }
  var styles = window.getComputedStyle(node);
  return (
    // not-not-scrollable
    styles[overflow] !== "hidden" && // contains scroll inside self
    !(styles.overflowY === styles.overflowX && !alwaysContainsScroll(node) && styles[overflow] === "visible")
  );
};
var elementCouldBeVScrolled = function(node) {
  return elementCanBeScrolled(node, "overflowY");
};
var elementCouldBeHScrolled = function(node) {
  return elementCanBeScrolled(node, "overflowX");
};
var locationCouldBeScrolled = function(axis, node) {
  var ownerDocument = node.ownerDocument;
  var current = node;
  do {
    if (typeof ShadowRoot !== "undefined" && current instanceof ShadowRoot) {
      current = current.host;
    }
    var isScrollable = elementCouldBeScrolled(axis, current);
    if (isScrollable) {
      var _a = getScrollVariables(axis, current), scrollHeight = _a[1], clientHeight = _a[2];
      if (scrollHeight > clientHeight) {
        return true;
      }
    }
    current = current.parentNode;
  } while (current && current !== ownerDocument.body);
  return false;
};
var getVScrollVariables = function(_a) {
  var scrollTop = _a.scrollTop, scrollHeight = _a.scrollHeight, clientHeight = _a.clientHeight;
  return [
    scrollTop,
    scrollHeight,
    clientHeight
  ];
};
var getHScrollVariables = function(_a) {
  var scrollLeft = _a.scrollLeft, scrollWidth = _a.scrollWidth, clientWidth = _a.clientWidth;
  return [
    scrollLeft,
    scrollWidth,
    clientWidth
  ];
};
var elementCouldBeScrolled = function(axis, node) {
  return axis === "v" ? elementCouldBeVScrolled(node) : elementCouldBeHScrolled(node);
};
var getScrollVariables = function(axis, node) {
  return axis === "v" ? getVScrollVariables(node) : getHScrollVariables(node);
};
var getDirectionFactor = function(axis, direction) {
  return axis === "h" && direction === "rtl" ? -1 : 1;
};
var handleScroll = function(axis, endTarget, event, sourceDelta, noOverscroll) {
  var directionFactor = getDirectionFactor(axis, window.getComputedStyle(endTarget).direction);
  var delta = directionFactor * sourceDelta;
  var target = event.target;
  var targetInLock = endTarget.contains(target);
  var shouldCancelScroll = false;
  var isDeltaPositive = delta > 0;
  var availableScroll = 0;
  var availableScrollTop = 0;
  do {
    if (!target) {
      break;
    }
    var _a = getScrollVariables(axis, target), position = _a[0], scroll_1 = _a[1], capacity = _a[2];
    var elementScroll = scroll_1 - capacity - directionFactor * position;
    if (position || elementScroll) {
      if (elementCouldBeScrolled(axis, target)) {
        availableScroll += elementScroll;
        availableScrollTop += position;
      }
    }
    var parent_1 = target.parentNode;
    target = parent_1 && parent_1.nodeType === Node.DOCUMENT_FRAGMENT_NODE ? parent_1.host : parent_1;
  } while (
    // portaled content
    !targetInLock && target !== document.body || // self content
    targetInLock && (endTarget.contains(target) || endTarget === target)
  );
  if (isDeltaPositive && (noOverscroll && Math.abs(availableScroll) < 1 || !noOverscroll && delta > availableScroll)) {
    shouldCancelScroll = true;
  } else if (!isDeltaPositive && (noOverscroll && Math.abs(availableScrollTop) < 1 || !noOverscroll && -delta > availableScrollTop)) {
    shouldCancelScroll = true;
  }
  return shouldCancelScroll;
};

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/SideEffect.js
var getTouchXY = function(event) {
  return "changedTouches" in event ? [event.changedTouches[0].clientX, event.changedTouches[0].clientY] : [0, 0];
};
var getDeltaXY = function(event) {
  return [event.deltaX, event.deltaY];
};
var extractRef = function(ref) {
  return ref && "current" in ref ? ref.current : ref;
};
var deltaCompare = function(x, y) {
  return x[0] === y[0] && x[1] === y[1];
};
var generateStyle = function(id) {
  return "\n  .block-interactivity-".concat(id, " {pointer-events: none;}\n  .allow-interactivity-").concat(id, " {pointer-events: all;}\n");
};
var idCounter = 0;
var lockStack = [];
function RemoveScrollSideCar(props) {
  var shouldPreventQueue = React35.useRef([]);
  var touchStartRef = React35.useRef([0, 0]);
  var activeAxis = React35.useRef();
  var id = React35.useState(idCounter++)[0];
  var Style2 = React35.useState(styleSingleton)[0];
  var lastProps = React35.useRef(props);
  React35.useEffect(function() {
    lastProps.current = props;
  }, [props]);
  React35.useEffect(function() {
    if (props.inert) {
      document.body.classList.add("block-interactivity-".concat(id));
      var allow_1 = __spreadArray([props.lockRef.current], (props.shards || []).map(extractRef), true).filter(Boolean);
      allow_1.forEach(function(el) {
        return el.classList.add("allow-interactivity-".concat(id));
      });
      return function() {
        document.body.classList.remove("block-interactivity-".concat(id));
        allow_1.forEach(function(el) {
          return el.classList.remove("allow-interactivity-".concat(id));
        });
      };
    }
    return;
  }, [props.inert, props.lockRef.current, props.shards]);
  var shouldCancelEvent = React35.useCallback(function(event, parent) {
    if ("touches" in event && event.touches.length === 2 || event.type === "wheel" && event.ctrlKey) {
      return !lastProps.current.allowPinchZoom;
    }
    var touch = getTouchXY(event);
    var touchStart = touchStartRef.current;
    var deltaX = "deltaX" in event ? event.deltaX : touchStart[0] - touch[0];
    var deltaY = "deltaY" in event ? event.deltaY : touchStart[1] - touch[1];
    var currentAxis;
    var target = event.target;
    var moveDirection = Math.abs(deltaX) > Math.abs(deltaY) ? "h" : "v";
    if ("touches" in event && moveDirection === "h" && target.type === "range") {
      return false;
    }
    var canBeScrolledInMainDirection = locationCouldBeScrolled(moveDirection, target);
    if (!canBeScrolledInMainDirection) {
      return true;
    }
    if (canBeScrolledInMainDirection) {
      currentAxis = moveDirection;
    } else {
      currentAxis = moveDirection === "v" ? "h" : "v";
      canBeScrolledInMainDirection = locationCouldBeScrolled(moveDirection, target);
    }
    if (!canBeScrolledInMainDirection) {
      return false;
    }
    if (!activeAxis.current && "changedTouches" in event && (deltaX || deltaY)) {
      activeAxis.current = currentAxis;
    }
    if (!currentAxis) {
      return true;
    }
    var cancelingAxis = activeAxis.current || currentAxis;
    return handleScroll(cancelingAxis, parent, event, cancelingAxis === "h" ? deltaX : deltaY, true);
  }, []);
  var shouldPrevent = React35.useCallback(function(_event) {
    var event = _event;
    if (!lockStack.length || lockStack[lockStack.length - 1] !== Style2) {
      return;
    }
    var delta = "deltaY" in event ? getDeltaXY(event) : getTouchXY(event);
    var sourceEvent = shouldPreventQueue.current.filter(function(e) {
      return e.name === event.type && (e.target === event.target || event.target === e.shadowParent) && deltaCompare(e.delta, delta);
    })[0];
    if (sourceEvent && sourceEvent.should) {
      if (event.cancelable) {
        event.preventDefault();
      }
      return;
    }
    if (!sourceEvent) {
      var shardNodes = (lastProps.current.shards || []).map(extractRef).filter(Boolean).filter(function(node) {
        return node.contains(event.target);
      });
      var shouldStop = shardNodes.length > 0 ? shouldCancelEvent(event, shardNodes[0]) : !lastProps.current.noIsolation;
      if (shouldStop) {
        if (event.cancelable) {
          event.preventDefault();
        }
      }
    }
  }, []);
  var shouldCancel = React35.useCallback(function(name, delta, target, should) {
    var event = { name, delta, target, should, shadowParent: getOutermostShadowParent(target) };
    shouldPreventQueue.current.push(event);
    setTimeout(function() {
      shouldPreventQueue.current = shouldPreventQueue.current.filter(function(e) {
        return e !== event;
      });
    }, 1);
  }, []);
  var scrollTouchStart = React35.useCallback(function(event) {
    touchStartRef.current = getTouchXY(event);
    activeAxis.current = void 0;
  }, []);
  var scrollWheel = React35.useCallback(function(event) {
    shouldCancel(event.type, getDeltaXY(event), event.target, shouldCancelEvent(event, props.lockRef.current));
  }, []);
  var scrollTouchMove = React35.useCallback(function(event) {
    shouldCancel(event.type, getTouchXY(event), event.target, shouldCancelEvent(event, props.lockRef.current));
  }, []);
  React35.useEffect(function() {
    lockStack.push(Style2);
    props.setCallbacks({
      onScrollCapture: scrollWheel,
      onWheelCapture: scrollWheel,
      onTouchMoveCapture: scrollTouchMove
    });
    document.addEventListener("wheel", shouldPrevent, nonPassive);
    document.addEventListener("touchmove", shouldPrevent, nonPassive);
    document.addEventListener("touchstart", scrollTouchStart, nonPassive);
    return function() {
      lockStack = lockStack.filter(function(inst) {
        return inst !== Style2;
      });
      document.removeEventListener("wheel", shouldPrevent, nonPassive);
      document.removeEventListener("touchmove", shouldPrevent, nonPassive);
      document.removeEventListener("touchstart", scrollTouchStart, nonPassive);
    };
  }, []);
  var removeScrollBar = props.removeScrollBar, inert = props.inert;
  return React35.createElement(
    React35.Fragment,
    null,
    inert ? React35.createElement(Style2, { styles: generateStyle(id) }) : null,
    removeScrollBar ? React35.createElement(RemoveScrollBar, { noRelative: props.noRelative, gapMode: props.gapMode }) : null
  );
}
function getOutermostShadowParent(node) {
  var shadowParent = null;
  while (node !== null) {
    if (node instanceof ShadowRoot) {
      shadowParent = node.host;
      node = node.host;
    }
    node = node.parentNode;
  }
  return shadowParent;
}

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/sidecar.js
var sidecar_default = exportSidecar(effectCar, RemoveScrollSideCar);

// node_modules/@radix-ui/react-dialog/node_modules/react-remove-scroll/dist/es2015/Combination.js
var ReactRemoveScroll = React36.forwardRef(function(props, ref) {
  return React36.createElement(RemoveScroll, __assign({}, props, { ref, sideCar: sidecar_default }));
});
ReactRemoveScroll.classNames = RemoveScroll.classNames;
var Combination_default = ReactRemoveScroll;

// node_modules/aria-hidden/dist/es2015/index.js
var getDefaultParent = function(originalTarget) {
  if (typeof document === "undefined") {
    return null;
  }
  var sampleTarget = Array.isArray(originalTarget) ? originalTarget[0] : originalTarget;
  return sampleTarget.ownerDocument.body;
};
var counterMap = /* @__PURE__ */ new WeakMap();
var uncontrolledNodes = /* @__PURE__ */ new WeakMap();
var markerMap = {};
var lockCount = 0;
var unwrapHost = function(node) {
  return node && (node.host || unwrapHost(node.parentNode));
};
var correctTargets = function(parent, targets) {
  return targets.map(function(target) {
    if (parent.contains(target)) {
      return target;
    }
    var correctedTarget = unwrapHost(target);
    if (correctedTarget && parent.contains(correctedTarget)) {
      return correctedTarget;
    }
    console.error("aria-hidden", target, "in not contained inside", parent, ". Doing nothing");
    return null;
  }).filter(function(x) {
    return Boolean(x);
  });
};
var applyAttributeToOthers = function(originalTarget, parentNode, markerName, controlAttribute) {
  var targets = correctTargets(parentNode, Array.isArray(originalTarget) ? originalTarget : [originalTarget]);
  if (!markerMap[markerName]) {
    markerMap[markerName] = /* @__PURE__ */ new WeakMap();
  }
  var markerCounter = markerMap[markerName];
  var hiddenNodes = [];
  var elementsToKeep = /* @__PURE__ */ new Set();
  var elementsToStop = new Set(targets);
  var keep = function(el) {
    if (!el || elementsToKeep.has(el)) {
      return;
    }
    elementsToKeep.add(el);
    keep(el.parentNode);
  };
  targets.forEach(keep);
  var deep = function(parent) {
    if (!parent || elementsToStop.has(parent)) {
      return;
    }
    Array.prototype.forEach.call(parent.children, function(node) {
      if (elementsToKeep.has(node)) {
        deep(node);
      } else {
        try {
          var attr = node.getAttribute(controlAttribute);
          var alreadyHidden = attr !== null && attr !== "false";
          var counterValue = (counterMap.get(node) || 0) + 1;
          var markerValue = (markerCounter.get(node) || 0) + 1;
          counterMap.set(node, counterValue);
          markerCounter.set(node, markerValue);
          hiddenNodes.push(node);
          if (counterValue === 1 && alreadyHidden) {
            uncontrolledNodes.set(node, true);
          }
          if (markerValue === 1) {
            node.setAttribute(markerName, "true");
          }
          if (!alreadyHidden) {
            node.setAttribute(controlAttribute, "true");
          }
        } catch (e) {
          console.error("aria-hidden: cannot operate on ", node, e);
        }
      }
    });
  };
  deep(parentNode);
  elementsToKeep.clear();
  lockCount++;
  return function() {
    hiddenNodes.forEach(function(node) {
      var counterValue = counterMap.get(node) - 1;
      var markerValue = markerCounter.get(node) - 1;
      counterMap.set(node, counterValue);
      markerCounter.set(node, markerValue);
      if (!counterValue) {
        if (!uncontrolledNodes.has(node)) {
          node.removeAttribute(controlAttribute);
        }
        uncontrolledNodes.delete(node);
      }
      if (!markerValue) {
        node.removeAttribute(markerName);
      }
    });
    lockCount--;
    if (!lockCount) {
      counterMap = /* @__PURE__ */ new WeakMap();
      counterMap = /* @__PURE__ */ new WeakMap();
      uncontrolledNodes = /* @__PURE__ */ new WeakMap();
      markerMap = {};
    }
  };
};
var hideOthers = function(originalTarget, parentNode, markerName) {
  if (markerName === void 0) {
    markerName = "data-aria-hidden";
  }
  var targets = Array.from(Array.isArray(originalTarget) ? originalTarget : [originalTarget]);
  var activeParentNode = parentNode || getDefaultParent(originalTarget);
  if (!activeParentNode) {
    return function() {
      return null;
    };
  }
  targets.push.apply(targets, Array.from(activeParentNode.querySelectorAll("[aria-live], script")));
  return applyAttributeToOthers(targets, activeParentNode, markerName, "aria-hidden");
};

// node_modules/@radix-ui/react-dialog/dist/index.mjs
var import_jsx_runtime13 = __toESM(require_jsx_runtime(), 1);
var DIALOG_NAME = "Dialog";
var [createDialogContext, createDialogScope] = createContextScope(DIALOG_NAME);
var [DialogProvider, useDialogContext] = createDialogContext(DIALOG_NAME);
var Dialog = (props) => {
  const {
    __scopeDialog,
    children,
    open: openProp,
    defaultOpen,
    onOpenChange,
    modal = true
  } = props;
  const triggerRef = React37.useRef(null);
  const contentRef = React37.useRef(null);
  const [open, setOpen] = useControllableState({
    prop: openProp,
    defaultProp: defaultOpen ?? false,
    onChange: onOpenChange,
    caller: DIALOG_NAME
  });
  return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(
    DialogProvider,
    {
      scope: __scopeDialog,
      triggerRef,
      contentRef,
      contentId: useId(),
      titleId: useId(),
      descriptionId: useId(),
      open,
      onOpenChange: setOpen,
      onOpenToggle: React37.useCallback(() => setOpen((prevOpen) => !prevOpen), [setOpen]),
      modal,
      children
    }
  );
};
Dialog.displayName = DIALOG_NAME;
var TRIGGER_NAME = "DialogTrigger";
var DialogTrigger = React37.forwardRef(
  (props, forwardedRef) => {
    const { __scopeDialog, ...triggerProps } = props;
    const context = useDialogContext(TRIGGER_NAME, __scopeDialog);
    const composedTriggerRef = useComposedRefs(forwardedRef, context.triggerRef);
    return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(
      Primitive4.button,
      {
        type: "button",
        "aria-haspopup": "dialog",
        "aria-expanded": context.open,
        "aria-controls": context.contentId,
        "data-state": getState(context.open),
        ...triggerProps,
        ref: composedTriggerRef,
        onClick: composeEventHandlers(props.onClick, context.onOpenToggle)
      }
    );
  }
);
DialogTrigger.displayName = TRIGGER_NAME;
var PORTAL_NAME2 = "DialogPortal";
var [PortalProvider, usePortalContext] = createDialogContext(PORTAL_NAME2, {
  forceMount: void 0
});
var DialogPortal = (props) => {
  const { __scopeDialog, forceMount, children, container } = props;
  const context = useDialogContext(PORTAL_NAME2, __scopeDialog);
  return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(PortalProvider, { scope: __scopeDialog, forceMount, children: React37.Children.map(children, (child) => /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(Presence, { present: forceMount || context.open, children: /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(Portal, { asChild: true, container, children: child }) })) });
};
DialogPortal.displayName = PORTAL_NAME2;
var OVERLAY_NAME = "DialogOverlay";
var DialogOverlay = React37.forwardRef(
  (props, forwardedRef) => {
    const portalContext = usePortalContext(OVERLAY_NAME, props.__scopeDialog);
    const { forceMount = portalContext.forceMount, ...overlayProps } = props;
    const context = useDialogContext(OVERLAY_NAME, props.__scopeDialog);
    return context.modal ? /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(Presence, { present: forceMount || context.open, children: /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(DialogOverlayImpl, { ...overlayProps, ref: forwardedRef }) }) : null;
  }
);
DialogOverlay.displayName = OVERLAY_NAME;
var Slot = createSlot4("DialogOverlay.RemoveScroll");
var DialogOverlayImpl = React37.forwardRef(
  (props, forwardedRef) => {
    const { __scopeDialog, ...overlayProps } = props;
    const context = useDialogContext(OVERLAY_NAME, __scopeDialog);
    return (
      // Make sure `Content` is scrollable even when it doesn't live inside `RemoveScroll`
      // ie. when `Overlay` and `Content` are siblings
      /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(Combination_default, { as: Slot, allowPinchZoom: true, shards: [context.contentRef], children: /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(
        Primitive4.div,
        {
          "data-state": getState(context.open),
          ...overlayProps,
          ref: forwardedRef,
          style: { pointerEvents: "auto", ...overlayProps.style }
        }
      ) })
    );
  }
);
var CONTENT_NAME = "DialogContent";
var DialogContent = React37.forwardRef(
  (props, forwardedRef) => {
    const portalContext = usePortalContext(CONTENT_NAME, props.__scopeDialog);
    const { forceMount = portalContext.forceMount, ...contentProps } = props;
    const context = useDialogContext(CONTENT_NAME, props.__scopeDialog);
    return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(Presence, { present: forceMount || context.open, children: context.modal ? /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(DialogContentModal, { ...contentProps, ref: forwardedRef }) : /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(DialogContentNonModal, { ...contentProps, ref: forwardedRef }) });
  }
);
DialogContent.displayName = CONTENT_NAME;
var DialogContentModal = React37.forwardRef(
  (props, forwardedRef) => {
    const context = useDialogContext(CONTENT_NAME, props.__scopeDialog);
    const contentRef = React37.useRef(null);
    const composedRefs = useComposedRefs(forwardedRef, context.contentRef, contentRef);
    React37.useEffect(() => {
      const content = contentRef.current;
      if (content) return hideOthers(content);
    }, []);
    return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(
      DialogContentImpl,
      {
        ...props,
        ref: composedRefs,
        trapFocus: context.open,
        disableOutsidePointerEvents: true,
        onCloseAutoFocus: composeEventHandlers(props.onCloseAutoFocus, (event) => {
          event.preventDefault();
          context.triggerRef.current?.focus();
        }),
        onPointerDownOutside: composeEventHandlers(props.onPointerDownOutside, (event) => {
          const originalEvent = event.detail.originalEvent;
          const ctrlLeftClick = originalEvent.button === 0 && originalEvent.ctrlKey === true;
          const isRightClick = originalEvent.button === 2 || ctrlLeftClick;
          if (isRightClick) event.preventDefault();
        }),
        onFocusOutside: composeEventHandlers(
          props.onFocusOutside,
          (event) => event.preventDefault()
        )
      }
    );
  }
);
var DialogContentNonModal = React37.forwardRef(
  (props, forwardedRef) => {
    const context = useDialogContext(CONTENT_NAME, props.__scopeDialog);
    const hasInteractedOutsideRef = React37.useRef(false);
    const hasPointerDownOutsideRef = React37.useRef(false);
    return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(
      DialogContentImpl,
      {
        ...props,
        ref: forwardedRef,
        trapFocus: false,
        disableOutsidePointerEvents: false,
        onCloseAutoFocus: (event) => {
          props.onCloseAutoFocus?.(event);
          if (!event.defaultPrevented) {
            if (!hasInteractedOutsideRef.current) context.triggerRef.current?.focus();
            event.preventDefault();
          }
          hasInteractedOutsideRef.current = false;
          hasPointerDownOutsideRef.current = false;
        },
        onInteractOutside: (event) => {
          props.onInteractOutside?.(event);
          if (!event.defaultPrevented) {
            hasInteractedOutsideRef.current = true;
            if (event.detail.originalEvent.type === "pointerdown") {
              hasPointerDownOutsideRef.current = true;
            }
          }
          const target = event.target;
          const targetIsTrigger = context.triggerRef.current?.contains(target);
          if (targetIsTrigger) event.preventDefault();
          if (event.detail.originalEvent.type === "focusin" && hasPointerDownOutsideRef.current) {
            event.preventDefault();
          }
        }
      }
    );
  }
);
var DialogContentImpl = React37.forwardRef(
  (props, forwardedRef) => {
    const { __scopeDialog, trapFocus, onOpenAutoFocus, onCloseAutoFocus, ...contentProps } = props;
    const context = useDialogContext(CONTENT_NAME, __scopeDialog);
    const contentRef = React37.useRef(null);
    const composedRefs = useComposedRefs(forwardedRef, contentRef);
    useFocusGuards();
    return /* @__PURE__ */ (0, import_jsx_runtime13.jsxs)(import_jsx_runtime13.Fragment, { children: [
      /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(
        FocusScope,
        {
          asChild: true,
          loop: true,
          trapped: trapFocus,
          onMountAutoFocus: onOpenAutoFocus,
          onUnmountAutoFocus: onCloseAutoFocus,
          children: /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(
            DismissableLayer,
            {
              role: "dialog",
              id: context.contentId,
              "aria-describedby": context.descriptionId,
              "aria-labelledby": context.titleId,
              "data-state": getState(context.open),
              ...contentProps,
              ref: composedRefs,
              onDismiss: () => context.onOpenChange(false)
            }
          )
        }
      ),
      /* @__PURE__ */ (0, import_jsx_runtime13.jsxs)(import_jsx_runtime13.Fragment, { children: [
        /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(TitleWarning, { titleId: context.titleId }),
        /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(DescriptionWarning, { contentRef, descriptionId: context.descriptionId })
      ] })
    ] });
  }
);
var TITLE_NAME = "DialogTitle";
var DialogTitle = React37.forwardRef(
  (props, forwardedRef) => {
    const { __scopeDialog, ...titleProps } = props;
    const context = useDialogContext(TITLE_NAME, __scopeDialog);
    return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(Primitive4.h2, { id: context.titleId, ...titleProps, ref: forwardedRef });
  }
);
DialogTitle.displayName = TITLE_NAME;
var DESCRIPTION_NAME = "DialogDescription";
var DialogDescription = React37.forwardRef(
  (props, forwardedRef) => {
    const { __scopeDialog, ...descriptionProps } = props;
    const context = useDialogContext(DESCRIPTION_NAME, __scopeDialog);
    return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(Primitive4.p, { id: context.descriptionId, ...descriptionProps, ref: forwardedRef });
  }
);
DialogDescription.displayName = DESCRIPTION_NAME;
var CLOSE_NAME = "DialogClose";
var DialogClose = React37.forwardRef(
  (props, forwardedRef) => {
    const { __scopeDialog, ...closeProps } = props;
    const context = useDialogContext(CLOSE_NAME, __scopeDialog);
    return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(
      Primitive4.button,
      {
        type: "button",
        ...closeProps,
        ref: forwardedRef,
        onClick: composeEventHandlers(props.onClick, () => context.onOpenChange(false))
      }
    );
  }
);
DialogClose.displayName = CLOSE_NAME;
function getState(open) {
  return open ? "open" : "closed";
}
var TITLE_WARNING_NAME = "DialogTitleWarning";
var [WarningProvider, useWarningContext] = createContext2(TITLE_WARNING_NAME, {
  contentName: CONTENT_NAME,
  titleName: TITLE_NAME,
  docsSlug: "dialog"
});
var TitleWarning = ({ titleId }) => {
  const titleWarningContext = useWarningContext(TITLE_WARNING_NAME);
  const MESSAGE = `\`${titleWarningContext.contentName}\` requires a \`${titleWarningContext.titleName}\` for the component to be accessible for screen reader users.

If you want to hide the \`${titleWarningContext.titleName}\`, you can wrap it with our VisuallyHidden component.

For more information, see https://radix-ui.com/primitives/docs/components/${titleWarningContext.docsSlug}`;
  React37.useEffect(() => {
    if (titleId) {
      const hasTitle = document.getElementById(titleId);
      if (!hasTitle) console.error(MESSAGE);
    }
  }, [MESSAGE, titleId]);
  return null;
};
var DESCRIPTION_WARNING_NAME = "DialogDescriptionWarning";
var DescriptionWarning = ({ contentRef, descriptionId }) => {
  const descriptionWarningContext = useWarningContext(DESCRIPTION_WARNING_NAME);
  const MESSAGE = `Warning: Missing \`Description\` or \`aria-describedby={undefined}\` for {${descriptionWarningContext.contentName}}.`;
  React37.useEffect(() => {
    const describedById = contentRef.current?.getAttribute("aria-describedby");
    if (descriptionId && describedById) {
      const hasDescription = document.getElementById(descriptionId);
      if (!hasDescription) console.warn(MESSAGE);
    }
  }, [MESSAGE, contentRef, descriptionId]);
  return null;
};
var Root = Dialog;
var Portal2 = DialogPortal;
var Overlay = DialogOverlay;
var Content = DialogContent;

// node_modules/cmdk/dist/index.mjs
var t = __toESM(require_react(), 1);

// node_modules/cmdk/node_modules/@radix-ui/react-primitive/dist/index.mjs
var React40 = __toESM(require_react(), 1);
var ReactDOM6 = __toESM(require_react_dom(), 1);

// node_modules/cmdk/node_modules/@radix-ui/react-slot/dist/index.mjs
var React39 = __toESM(require_react(), 1);

// node_modules/cmdk/node_modules/@radix-ui/react-compose-refs/dist/index.mjs
var React38 = __toESM(require_react(), 1);
function setRef6(ref, value) {
  if (typeof ref === "function") {
    return ref(value);
  } else if (ref !== null && ref !== void 0) {
    ref.current = value;
  }
}
function composeRefs6(...refs) {
  return (node) => {
    let hasCleanup = false;
    const cleanups = refs.map((ref) => {
      const cleanup = setRef6(ref, node);
      if (!hasCleanup && typeof cleanup == "function") {
        hasCleanup = true;
      }
      return cleanup;
    });
    if (hasCleanup) {
      return () => {
        for (let i = 0; i < cleanups.length; i++) {
          const cleanup = cleanups[i];
          if (typeof cleanup == "function") {
            cleanup();
          } else {
            setRef6(refs[i], null);
          }
        }
      };
    }
  };
}

// node_modules/cmdk/node_modules/@radix-ui/react-slot/dist/index.mjs
var import_jsx_runtime14 = __toESM(require_jsx_runtime(), 1);
var REACT_LAZY_TYPE = /* @__PURE__ */ Symbol.for("react.lazy");
var use = React39[" use ".trim().toString()];
function isPromiseLike(value) {
  return typeof value === "object" && value !== null && "then" in value;
}
function isLazyComponent(element) {
  return element != null && typeof element === "object" && "$$typeof" in element && element.$$typeof === REACT_LAZY_TYPE && "_payload" in element && isPromiseLike(element._payload);
}
// @__NO_SIDE_EFFECTS__
function createSlot5(ownerName) {
  const SlotClone = /* @__PURE__ */ createSlotClone5(ownerName);
  const Slot2 = React39.forwardRef((props, forwardedRef) => {
    let { children, ...slotProps } = props;
    if (isLazyComponent(children) && typeof use === "function") {
      children = use(children._payload);
    }
    const childrenArray = React39.Children.toArray(children);
    const slottable = childrenArray.find(isSlottable5);
    if (slottable) {
      const newElement = slottable.props.children;
      const newChildren = childrenArray.map((child) => {
        if (child === slottable) {
          if (React39.Children.count(newElement) > 1) return React39.Children.only(null);
          return React39.isValidElement(newElement) ? newElement.props.children : null;
        } else {
          return child;
        }
      });
      return /* @__PURE__ */ (0, import_jsx_runtime14.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children: React39.isValidElement(newElement) ? React39.cloneElement(newElement, void 0, newChildren) : null });
    }
    return /* @__PURE__ */ (0, import_jsx_runtime14.jsx)(SlotClone, { ...slotProps, ref: forwardedRef, children });
  });
  Slot2.displayName = `${ownerName}.Slot`;
  return Slot2;
}
// @__NO_SIDE_EFFECTS__
function createSlotClone5(ownerName) {
  const SlotClone = React39.forwardRef((props, forwardedRef) => {
    let { children, ...slotProps } = props;
    if (isLazyComponent(children) && typeof use === "function") {
      children = use(children._payload);
    }
    if (React39.isValidElement(children)) {
      const childrenRef = getElementRef6(children);
      const props2 = mergeProps5(slotProps, children.props);
      if (children.type !== React39.Fragment) {
        props2.ref = forwardedRef ? composeRefs6(forwardedRef, childrenRef) : childrenRef;
      }
      return React39.cloneElement(children, props2);
    }
    return React39.Children.count(children) > 1 ? React39.Children.only(null) : null;
  });
  SlotClone.displayName = `${ownerName}.SlotClone`;
  return SlotClone;
}
var SLOTTABLE_IDENTIFIER5 = /* @__PURE__ */ Symbol("radix.slottable");
function isSlottable5(child) {
  return React39.isValidElement(child) && typeof child.type === "function" && "__radixId" in child.type && child.type.__radixId === SLOTTABLE_IDENTIFIER5;
}
function mergeProps5(slotProps, childProps) {
  const overrideProps = { ...childProps };
  for (const propName in childProps) {
    const slotPropValue = slotProps[propName];
    const childPropValue = childProps[propName];
    const isHandler = /^on[A-Z]/.test(propName);
    if (isHandler) {
      if (slotPropValue && childPropValue) {
        overrideProps[propName] = (...args) => {
          const result = childPropValue(...args);
          slotPropValue(...args);
          return result;
        };
      } else if (slotPropValue) {
        overrideProps[propName] = slotPropValue;
      }
    } else if (propName === "style") {
      overrideProps[propName] = { ...slotPropValue, ...childPropValue };
    } else if (propName === "className") {
      overrideProps[propName] = [slotPropValue, childPropValue].filter(Boolean).join(" ");
    }
  }
  return { ...slotProps, ...overrideProps };
}
function getElementRef6(element) {
  let getter = Object.getOwnPropertyDescriptor(element.props, "ref")?.get;
  let mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.ref;
  }
  getter = Object.getOwnPropertyDescriptor(element, "ref")?.get;
  mayWarn = getter && "isReactWarning" in getter && getter.isReactWarning;
  if (mayWarn) {
    return element.props.ref;
  }
  return element.props.ref || element.ref;
}

// node_modules/cmdk/node_modules/@radix-ui/react-primitive/dist/index.mjs
var import_jsx_runtime15 = __toESM(require_jsx_runtime(), 1);
var NODES5 = [
  "a",
  "button",
  "div",
  "form",
  "h2",
  "h3",
  "img",
  "input",
  "label",
  "li",
  "nav",
  "ol",
  "p",
  "select",
  "span",
  "svg",
  "ul"
];
var Primitive5 = NODES5.reduce((primitive, node) => {
  const Slot2 = createSlot5(`Primitive.${node}`);
  const Node2 = React40.forwardRef((props, forwardedRef) => {
    const { asChild, ...primitiveProps } = props;
    const Comp = asChild ? Slot2 : node;
    if (typeof window !== "undefined") {
      window[/* @__PURE__ */ Symbol.for("radix-ui")] = true;
    }
    return /* @__PURE__ */ (0, import_jsx_runtime15.jsx)(Comp, { ...primitiveProps, ref: forwardedRef });
  });
  Node2.displayName = `Primitive.${node}`;
  return { ...primitive, [node]: Node2 };
}, {});

// node_modules/cmdk/node_modules/@radix-ui/react-id/dist/index.mjs
var React42 = __toESM(require_react(), 1);

// node_modules/cmdk/node_modules/@radix-ui/react-use-layout-effect/dist/index.mjs
var React41 = __toESM(require_react(), 1);
var useLayoutEffect24 = globalThis?.document ? React41.useLayoutEffect : () => {
};

// node_modules/cmdk/node_modules/@radix-ui/react-id/dist/index.mjs
var useReactId2 = React42[" useId ".trim().toString()] || (() => void 0);
var count3 = 0;
function useId2(deterministicId) {
  const [id, setId] = React42.useState(useReactId2());
  useLayoutEffect24(() => {
    if (!deterministicId) setId((reactId) => reactId ?? String(count3++));
  }, [deterministicId]);
  return deterministicId || (id ? `radix-${id}` : "");
}

// node_modules/cmdk/dist/index.mjs
var N = '[cmdk-group=""]';
var Y2 = '[cmdk-group-items=""]';
var be = '[cmdk-group-heading=""]';
var le = '[cmdk-item=""]';
var ce = `${le}:not([aria-disabled="true"])`;
var Z = "cmdk-item-select";
var T = "data-value";
var Re = (r, o, n) => W(r, o, n);
var ue = t.createContext(void 0);
var K2 = () => t.useContext(ue);
var de = t.createContext(void 0);
var ee = () => t.useContext(de);
var fe = t.createContext(void 0);
var me = t.forwardRef((r, o) => {
  let n = L(() => {
    var e, a;
    return { search: "", value: (a = (e = r.value) != null ? e : r.defaultValue) != null ? a : "", selectedItemId: void 0, filtered: { count: 0, items: /* @__PURE__ */ new Map(), groups: /* @__PURE__ */ new Set() } };
  }), u2 = L(() => /* @__PURE__ */ new Set()), c = L(() => /* @__PURE__ */ new Map()), d = L(() => /* @__PURE__ */ new Map()), f = L(() => /* @__PURE__ */ new Set()), p2 = pe(r), { label: b, children: m2, value: R, onValueChange: x, filter: C, shouldFilter: S, loop: A, disablePointerSelection: ge = false, vimBindings: j = true, ...O } = r, $2 = useId2(), q = useId2(), _ = useId2(), I = t.useRef(null), v = ke();
  k2(() => {
    if (R !== void 0) {
      let e = R.trim();
      n.current.value = e, E.emit();
    }
  }, [R]), k2(() => {
    v(6, ne);
  }, []);
  let E = t.useMemo(() => ({ subscribe: (e) => (f.current.add(e), () => f.current.delete(e)), snapshot: () => n.current, setState: (e, a, s) => {
    var i, l, g, y;
    if (!Object.is(n.current[e], a)) {
      if (n.current[e] = a, e === "search") J2(), z(), v(1, W2);
      else if (e === "value") {
        if (document.activeElement.hasAttribute("cmdk-input") || document.activeElement.hasAttribute("cmdk-root")) {
          let h = document.getElementById(_);
          h ? h.focus() : (i = document.getElementById($2)) == null || i.focus();
        }
        if (v(7, () => {
          var h;
          n.current.selectedItemId = (h = M()) == null ? void 0 : h.id, E.emit();
        }), s || v(5, ne), ((l = p2.current) == null ? void 0 : l.value) !== void 0) {
          let h = a != null ? a : "";
          (y = (g = p2.current).onValueChange) == null || y.call(g, h);
          return;
        }
      }
      E.emit();
    }
  }, emit: () => {
    f.current.forEach((e) => e());
  } }), []), U2 = t.useMemo(() => ({ value: (e, a, s) => {
    var i;
    a !== ((i = d.current.get(e)) == null ? void 0 : i.value) && (d.current.set(e, { value: a, keywords: s }), n.current.filtered.items.set(e, te(a, s)), v(2, () => {
      z(), E.emit();
    }));
  }, item: (e, a) => (u2.current.add(e), a && (c.current.has(a) ? c.current.get(a).add(e) : c.current.set(a, /* @__PURE__ */ new Set([e]))), v(3, () => {
    J2(), z(), n.current.value || W2(), E.emit();
  }), () => {
    d.current.delete(e), u2.current.delete(e), n.current.filtered.items.delete(e);
    let s = M();
    v(4, () => {
      J2(), (s == null ? void 0 : s.getAttribute("id")) === e && W2(), E.emit();
    });
  }), group: (e) => (c.current.has(e) || c.current.set(e, /* @__PURE__ */ new Set()), () => {
    d.current.delete(e), c.current.delete(e);
  }), filter: () => p2.current.shouldFilter, label: b || r["aria-label"], getDisablePointerSelection: () => p2.current.disablePointerSelection, listId: $2, inputId: _, labelId: q, listInnerRef: I }), []);
  function te(e, a) {
    var i, l;
    let s = (l = (i = p2.current) == null ? void 0 : i.filter) != null ? l : Re;
    return e ? s(e, n.current.search, a) : 0;
  }
  function z() {
    if (!n.current.search || p2.current.shouldFilter === false) return;
    let e = n.current.filtered.items, a = [];
    n.current.filtered.groups.forEach((i) => {
      let l = c.current.get(i), g = 0;
      l.forEach((y) => {
        let h = e.get(y);
        g = Math.max(h, g);
      }), a.push([i, g]);
    });
    let s = I.current;
    V().sort((i, l) => {
      var h, F;
      let g = i.getAttribute("id"), y = l.getAttribute("id");
      return ((h = e.get(y)) != null ? h : 0) - ((F = e.get(g)) != null ? F : 0);
    }).forEach((i) => {
      let l = i.closest(Y2);
      l ? l.appendChild(i.parentElement === l ? i : i.closest(`${Y2} > *`)) : s.appendChild(i.parentElement === s ? i : i.closest(`${Y2} > *`));
    }), a.sort((i, l) => l[1] - i[1]).forEach((i) => {
      var g;
      let l = (g = I.current) == null ? void 0 : g.querySelector(`${N}[${T}="${encodeURIComponent(i[0])}"]`);
      l == null || l.parentElement.appendChild(l);
    });
  }
  function W2() {
    let e = V().find((s) => s.getAttribute("aria-disabled") !== "true"), a = e == null ? void 0 : e.getAttribute(T);
    E.setState("value", a || void 0);
  }
  function J2() {
    var a, s, i, l;
    if (!n.current.search || p2.current.shouldFilter === false) {
      n.current.filtered.count = u2.current.size;
      return;
    }
    n.current.filtered.groups = /* @__PURE__ */ new Set();
    let e = 0;
    for (let g of u2.current) {
      let y = (s = (a = d.current.get(g)) == null ? void 0 : a.value) != null ? s : "", h = (l = (i = d.current.get(g)) == null ? void 0 : i.keywords) != null ? l : [], F = te(y, h);
      n.current.filtered.items.set(g, F), F > 0 && e++;
    }
    for (let [g, y] of c.current) for (let h of y) if (n.current.filtered.items.get(h) > 0) {
      n.current.filtered.groups.add(g);
      break;
    }
    n.current.filtered.count = e;
  }
  function ne() {
    var a, s, i;
    let e = M();
    e && (((a = e.parentElement) == null ? void 0 : a.firstChild) === e && ((i = (s = e.closest(N)) == null ? void 0 : s.querySelector(be)) == null || i.scrollIntoView({ block: "nearest" })), e.scrollIntoView({ block: "nearest" }));
  }
  function M() {
    var e;
    return (e = I.current) == null ? void 0 : e.querySelector(`${le}[aria-selected="true"]`);
  }
  function V() {
    var e;
    return Array.from(((e = I.current) == null ? void 0 : e.querySelectorAll(ce)) || []);
  }
  function X2(e) {
    let s = V()[e];
    s && E.setState("value", s.getAttribute(T));
  }
  function Q(e) {
    var g;
    let a = M(), s = V(), i = s.findIndex((y) => y === a), l = s[i + e];
    (g = p2.current) != null && g.loop && (l = i + e < 0 ? s[s.length - 1] : i + e === s.length ? s[0] : s[i + e]), l && E.setState("value", l.getAttribute(T));
  }
  function re(e) {
    let a = M(), s = a == null ? void 0 : a.closest(N), i;
    for (; s && !i; ) s = e > 0 ? we(s, N) : De(s, N), i = s == null ? void 0 : s.querySelector(ce);
    i ? E.setState("value", i.getAttribute(T)) : Q(e);
  }
  let oe = () => X2(V().length - 1), ie = (e) => {
    e.preventDefault(), e.metaKey ? oe() : e.altKey ? re(1) : Q(1);
  }, se = (e) => {
    e.preventDefault(), e.metaKey ? X2(0) : e.altKey ? re(-1) : Q(-1);
  };
  return t.createElement(Primitive5.div, { ref: o, tabIndex: -1, ...O, "cmdk-root": "", onKeyDown: (e) => {
    var s;
    (s = O.onKeyDown) == null || s.call(O, e);
    let a = e.nativeEvent.isComposing || e.keyCode === 229;
    if (!(e.defaultPrevented || a)) switch (e.key) {
      case "n":
      case "j": {
        j && e.ctrlKey && ie(e);
        break;
      }
      case "ArrowDown": {
        ie(e);
        break;
      }
      case "p":
      case "k": {
        j && e.ctrlKey && se(e);
        break;
      }
      case "ArrowUp": {
        se(e);
        break;
      }
      case "Home": {
        e.preventDefault(), X2(0);
        break;
      }
      case "End": {
        e.preventDefault(), oe();
        break;
      }
      case "Enter": {
        e.preventDefault();
        let i = M();
        if (i) {
          let l = new Event(Z);
          i.dispatchEvent(l);
        }
      }
    }
  } }, t.createElement("label", { "cmdk-label": "", htmlFor: U2.inputId, id: U2.labelId, style: Te }, b), B2(r, (e) => t.createElement(de.Provider, { value: E }, t.createElement(ue.Provider, { value: U2 }, e))));
});
var he = t.forwardRef((r, o) => {
  var _, I;
  let n = useId2(), u2 = t.useRef(null), c = t.useContext(fe), d = K2(), f = pe(r), p2 = (I = (_ = f.current) == null ? void 0 : _.forceMount) != null ? I : c == null ? void 0 : c.forceMount;
  k2(() => {
    if (!p2) return d.item(n, c == null ? void 0 : c.id);
  }, [p2]);
  let b = ve(n, u2, [r.value, r.children, u2], r.keywords), m2 = ee(), R = P((v) => v.value && v.value === b.current), x = P((v) => p2 || d.filter() === false ? true : v.search ? v.filtered.items.get(n) > 0 : true);
  t.useEffect(() => {
    let v = u2.current;
    if (!(!v || r.disabled)) return v.addEventListener(Z, C), () => v.removeEventListener(Z, C);
  }, [x, r.onSelect, r.disabled]);
  function C() {
    var v, E;
    S(), (E = (v = f.current).onSelect) == null || E.call(v, b.current);
  }
  function S() {
    m2.setState("value", b.current, true);
  }
  if (!x) return null;
  let { disabled: A, value: ge, onSelect: j, forceMount: O, keywords: $2, ...q } = r;
  return t.createElement(Primitive5.div, { ref: composeRefs6(u2, o), ...q, id: n, "cmdk-item": "", role: "option", "aria-disabled": !!A, "aria-selected": !!R, "data-disabled": !!A, "data-selected": !!R, onPointerMove: A || d.getDisablePointerSelection() ? void 0 : S, onClick: A ? void 0 : C }, r.children);
});
var Ee = t.forwardRef((r, o) => {
  let { heading: n, children: u2, forceMount: c, ...d } = r, f = useId2(), p2 = t.useRef(null), b = t.useRef(null), m2 = useId2(), R = K2(), x = P((S) => c || R.filter() === false ? true : S.search ? S.filtered.groups.has(f) : true);
  k2(() => R.group(f), []), ve(f, p2, [r.value, r.heading, b]);
  let C = t.useMemo(() => ({ id: f, forceMount: c }), [c]);
  return t.createElement(Primitive5.div, { ref: composeRefs6(p2, o), ...d, "cmdk-group": "", role: "presentation", hidden: x ? void 0 : true }, n && t.createElement("div", { ref: b, "cmdk-group-heading": "", "aria-hidden": true, id: m2 }, n), B2(r, (S) => t.createElement("div", { "cmdk-group-items": "", role: "group", "aria-labelledby": n ? m2 : void 0 }, t.createElement(fe.Provider, { value: C }, S))));
});
var ye = t.forwardRef((r, o) => {
  let { alwaysRender: n, ...u2 } = r, c = t.useRef(null), d = P((f) => !f.search);
  return !n && !d ? null : t.createElement(Primitive5.div, { ref: composeRefs6(c, o), ...u2, "cmdk-separator": "", role: "separator" });
});
var Se = t.forwardRef((r, o) => {
  let { onValueChange: n, ...u2 } = r, c = r.value != null, d = ee(), f = P((m2) => m2.search), p2 = P((m2) => m2.selectedItemId), b = K2();
  return t.useEffect(() => {
    r.value != null && d.setState("search", r.value);
  }, [r.value]), t.createElement(Primitive5.input, { ref: o, ...u2, "cmdk-input": "", autoComplete: "off", autoCorrect: "off", spellCheck: false, "aria-autocomplete": "list", role: "combobox", "aria-expanded": true, "aria-controls": b.listId, "aria-labelledby": b.labelId, "aria-activedescendant": p2, id: b.inputId, type: "text", value: c ? r.value : f, onChange: (m2) => {
    c || d.setState("search", m2.target.value), n == null || n(m2.target.value);
  } });
});
var Ce = t.forwardRef((r, o) => {
  let { children: n, label: u2 = "Suggestions", ...c } = r, d = t.useRef(null), f = t.useRef(null), p2 = P((m2) => m2.selectedItemId), b = K2();
  return t.useEffect(() => {
    if (f.current && d.current) {
      let m2 = f.current, R = d.current, x, C = new ResizeObserver(() => {
        x = requestAnimationFrame(() => {
          let S = m2.offsetHeight;
          R.style.setProperty("--cmdk-list-height", S.toFixed(1) + "px");
        });
      });
      return C.observe(m2), () => {
        cancelAnimationFrame(x), C.unobserve(m2);
      };
    }
  }, []), t.createElement(Primitive5.div, { ref: composeRefs6(d, o), ...c, "cmdk-list": "", role: "listbox", tabIndex: -1, "aria-activedescendant": p2, "aria-label": u2, id: b.listId }, B2(r, (m2) => t.createElement("div", { ref: composeRefs6(f, b.listInnerRef), "cmdk-list-sizer": "" }, m2)));
});
var xe = t.forwardRef((r, o) => {
  let { open: n, onOpenChange: u2, overlayClassName: c, contentClassName: d, container: f, ...p2 } = r;
  return t.createElement(Root, { open: n, onOpenChange: u2 }, t.createElement(Portal2, { container: f }, t.createElement(Overlay, { "cmdk-overlay": "", className: c }), t.createElement(Content, { "aria-label": r.label, "cmdk-dialog": "", className: d }, t.createElement(me, { ref: o, ...p2 }))));
});
var Ie = t.forwardRef((r, o) => P((u2) => u2.filtered.count === 0) ? t.createElement(Primitive5.div, { ref: o, ...r, "cmdk-empty": "", role: "presentation" }) : null);
var Pe = t.forwardRef((r, o) => {
  let { progress: n, children: u2, label: c = "Loading...", ...d } = r;
  return t.createElement(Primitive5.div, { ref: o, ...d, "cmdk-loading": "", role: "progressbar", "aria-valuenow": n, "aria-valuemin": 0, "aria-valuemax": 100, "aria-label": c }, B2(r, (f) => t.createElement("div", { "aria-hidden": true }, f)));
});
var _e = Object.assign(me, { List: Ce, Item: he, Input: Se, Group: Ee, Separator: ye, Dialog: xe, Empty: Ie, Loading: Pe });
function we(r, o) {
  let n = r.nextElementSibling;
  for (; n; ) {
    if (n.matches(o)) return n;
    n = n.nextElementSibling;
  }
}
function De(r, o) {
  let n = r.previousElementSibling;
  for (; n; ) {
    if (n.matches(o)) return n;
    n = n.previousElementSibling;
  }
}
function pe(r) {
  let o = t.useRef(r);
  return k2(() => {
    o.current = r;
  }), o;
}
var k2 = typeof window == "undefined" ? t.useEffect : t.useLayoutEffect;
function L(r) {
  let o = t.useRef();
  return o.current === void 0 && (o.current = r()), o;
}
function P(r) {
  let o = ee(), n = () => r(o.snapshot());
  return t.useSyncExternalStore(o.subscribe, n, n);
}
function ve(r, o, n, u2 = []) {
  let c = t.useRef(), d = K2();
  return k2(() => {
    var b;
    let f = (() => {
      var m2;
      for (let R of n) {
        if (typeof R == "string") return R.trim();
        if (typeof R == "object" && "current" in R) return R.current ? (m2 = R.current.textContent) == null ? void 0 : m2.trim() : c.current;
      }
    })(), p2 = u2.map((m2) => m2.trim());
    d.value(r, f, p2), (b = o.current) == null || b.setAttribute(T, f), c.current = f;
  }), c;
}
var ke = () => {
  let [r, o] = t.useState(), n = L(() => /* @__PURE__ */ new Map());
  return k2(() => {
    n.current.forEach((u2) => u2()), n.current = /* @__PURE__ */ new Map();
  }, [r]), (u2, c) => {
    n.current.set(u2, c), o({});
  };
};
function Me(r) {
  let o = r.type;
  return typeof o == "function" ? o(r.props) : "render" in o ? o.render(r.props) : r;
}
function B2({ asChild: r, children: o }, n) {
  return r && t.isValidElement(o) ? t.cloneElement(Me(o), { ref: o.ref }, n(o.props.children)) : n(o);
}
var Te = { position: "absolute", width: "1px", height: "1px", padding: "0", margin: "-1px", overflow: "hidden", clip: "rect(0, 0, 0, 0)", whiteSpace: "nowrap", borderWidth: "0" };

// packages/workflow/build-module/components/workflow-menu.mjs
var import_data = __toESM(require_data(), 1);
var import_element2 = __toESM(require_element(), 1);
var import_i18n = __toESM(require_i18n(), 1);
var import_components = __toESM(require_components(), 1);
var import_keyboard_shortcuts = __toESM(require_keyboard_shortcuts(), 1);

// packages/icons/build-module/icon/index.mjs
var import_element = __toESM(require_element(), 1);
var icon_default = (0, import_element.forwardRef)(
  ({ icon, size = 24, ...props }, ref) => {
    return (0, import_element.cloneElement)(icon, {
      width: size,
      height: size,
      ...props,
      ref
    });
  }
);

// packages/icons/build-module/library/search.mjs
var import_primitives = __toESM(require_primitives(), 1);
var import_jsx_runtime16 = __toESM(require_jsx_runtime(), 1);
var search_default = /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(import_primitives.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(import_primitives.Path, { d: "M13 5c-3.3 0-6 2.7-6 6 0 1.4.5 2.7 1.3 3.7l-3.8 3.8 1.1 1.1 3.8-3.8c1 .8 2.3 1.3 3.7 1.3 3.3 0 6-2.7 6-6S16.3 5 13 5zm0 10.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5z" }) });

// packages/workflow/build-module/components/workflow-menu.mjs
import { executeAbility, store as abilitiesStore } from "@wordpress/abilities";

// packages/workflow/build-module/lock-unlock.mjs
var import_private_apis = __toESM(require_private_apis(), 1);
var { lock, unlock } = (0, import_private_apis.__dangerousOptInToUnstableAPIsOnlyForCoreModules)(
  "I acknowledge private features are not for use in themes or plugins and doing so will break in the next version of WordPress.",
  "@wordpress/workflows"
);

// packages/workflow/build-module/components/workflow-menu.mjs
var import_jsx_runtime17 = __toESM(require_jsx_runtime(), 1);
var css = `/**
 * Typography
 */
/**
 * SCSS Variables.
 *
 * Please use variables from this sheet to ensure consistency across the UI.
 * Don't add to this sheet unless you're pretty sure the value will be reused in many places.
 * For example, don't add rules to this sheet that affect block visuals. It's purely for UI.
 */
/**
 * Colors
 */
/**
 * Fonts & basic variables.
 */
/**
 * Typography
 */
/**
 * Grid System.
 * https://make.wordpress.org/design/2019/10/31/proposal-a-consistent-spacing-system-for-wordpress/
 */
/**
 * Radius scale.
 */
/**
 * Elevation scale.
 */
/**
 * Dimensions.
 */
/**
 * Mobile specific styles
 */
/**
 * Editor styles.
 */
/**
 * Block & Editor UI.
 */
/**
 * Block paddings.
 */
/**
 * React Native specific.
 * These variables do not appear to be used anywhere else.
 */
/**
 * Breakpoints & Media Queries
 */
/**
*  Converts a hex value into the rgb equivalent.
*
* @param {string} hex - the hexadecimal value to convert
* @return {string} comma separated rgb values
*/
/**
 * Long content fade mixin
 *
 * Creates a fading overlay to signify that the content is longer
 * than the space allows.
 */
/**
 * Breakpoint mixins
 */
/**
 * Focus styles.
 */
/**
 * Applies editor left position to the selector passed as argument
 */
/**
 * Styles that are reused verbatim in a few places
 */
/**
 * Allows users to opt-out of animations via OS-level preferences.
 */
/**
 * Reset default styles for JavaScript UI based pages.
 * This is a WP-admin agnostic reset
 */
/**
 * Reset the WP Admin page styles for Gutenberg-like pages.
 */
/**
 * Creates a checkerboard pattern background to indicate transparency.
 * @param {String} $size - The size of the squares in the checkerboard pattern. Default is 12px.
 */
:root {
  --wp-block-synced-color: #7a00df;
  --wp-block-synced-color--rgb: 122, 0, 223;
  --wp-bound-block-color: var(--wp-block-synced-color);
  --wp-editor-canvas-background: #ddd;
  --wp-admin-theme-color: #007cba;
  --wp-admin-theme-color--rgb: 0, 124, 186;
  --wp-admin-theme-color-darker-10: rgb(0, 107, 160.5);
  --wp-admin-theme-color-darker-10--rgb: 0, 107, 160.5;
  --wp-admin-theme-color-darker-20: #005a87;
  --wp-admin-theme-color-darker-20--rgb: 0, 90, 135;
  --wp-admin-border-width-focus: 2px;
}
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  :root {
    --wp-admin-border-width-focus: 1.5px;
  }
}

.workflows-workflow-menu {
  border-radius: 4px;
  width: calc(100% - 32px);
  margin: auto;
  max-width: 400px;
  position: relative;
  top: calc(5% + 64px);
}
@media (min-width: 600px) {
  .workflows-workflow-menu {
    top: calc(10% + 64px);
  }
}
.workflows-workflow-menu .components-modal__content {
  margin: 0;
  padding: 0;
}

.workflows-workflow-menu__overlay {
  display: block;
  align-items: start;
}

.workflows-workflow-menu__header {
  padding: 0 16px;
}

.workflows-workflow-menu__header-search-icon:dir(ltr) {
  transform: scaleX(-1);
}

.workflows-workflow-menu__container {
  will-change: transform;
}
.workflows-workflow-menu__container:focus {
  outline: none;
}
.workflows-workflow-menu__container [cmdk-input] {
  border: none;
  width: 100%;
  padding: 16px 4px;
  outline: none;
  color: #1e1e1e;
  margin: 0;
  font-size: 15px;
  line-height: 28px;
  border-radius: 0;
}
.workflows-workflow-menu__container [cmdk-input]::placeholder {
  color: #757575;
}
.workflows-workflow-menu__container [cmdk-input]:focus {
  box-shadow: none;
  outline: none;
}
.workflows-workflow-menu__container [cmdk-item] {
  border-radius: 2px;
  cursor: pointer;
  display: flex;
  align-items: center;
  color: #1e1e1e;
  font-size: 13px;
}
.workflows-workflow-menu__container [cmdk-item][aria-selected=true], .workflows-workflow-menu__container [cmdk-item]:active {
  background: var(--wp-admin-theme-color);
  color: #fff;
}
.workflows-workflow-menu__container [cmdk-item][aria-disabled=true] {
  color: #949494;
  cursor: not-allowed;
}
.workflows-workflow-menu__container [cmdk-item] > div {
  min-height: 40px;
  padding: 4px;
  padding-left: 16px;
}
.workflows-workflow-menu__container [cmdk-root] > [cmdk-list] {
  max-height: 368px;
  overflow: auto;
}
.workflows-workflow-menu__container [cmdk-root] > [cmdk-list] [cmdk-list-sizer] > [cmdk-group]:last-child [cmdk-group-items]:not(:empty) {
  padding-bottom: 8px;
}
.workflows-workflow-menu__container [cmdk-root] > [cmdk-list] [cmdk-list-sizer] > [cmdk-group] > [cmdk-group-items]:not(:empty) {
  padding: 0 8px;
}
.workflows-workflow-menu__container [cmdk-empty] {
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: pre-wrap;
  color: #1e1e1e;
  padding: 8px 0 32px;
}
.workflows-workflow-menu__container [cmdk-loading] {
  padding: 16px;
}
.workflows-workflow-menu__container [cmdk-list-sizer] {
  position: relative;
}

.workflows-workflow-menu__item span {
  display: inline-block;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.workflows-workflow-menu__item mark {
  color: inherit;
  background: unset;
  font-weight: 600;
}

.workflows-workflow-menu__output {
  padding: 16px;
}

.workflows-workflow-menu__output-header {
  margin-bottom: 16px;
  border-bottom: 1px solid #ddd;
  padding-bottom: 8px;
}
.workflows-workflow-menu__output-header h3 {
  margin: 0 0 4px;
  font-size: 16px;
  font-weight: 600;
  color: #1e1e1e;
}

.workflows-workflow-menu__output-hint {
  margin: 0;
  font-size: 12px;
  color: #757575;
}

.workflows-workflow-menu__output-content {
  max-height: 400px;
  overflow: auto;
}
.workflows-workflow-menu__output-content pre {
  margin: 0;
  padding: 12px;
  background: #f0f0f0;
  border-radius: 2px;
  font-size: 12px;
  line-height: 1.5;
  white-space: pre-wrap;
  word-break: break-word;
  color: #1e1e1e;
}

.workflows-workflow-menu__output-error {
  padding: 12px;
  background: #e0e0e0;
  border: 1px solid rgb(158.3684210526, 18.6315789474, 18.6315789474);
  border-radius: 2px;
  color: #cc1818;
}
.workflows-workflow-menu__output-error p {
  margin: 0;
  font-size: 13px;
}

.workflows-workflow-menu__executing {
  padding: 24px 16px;
  color: #757575;
  font-size: 14px;
}
/*# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VSb290IjoiL2hvbWUvcnVubmVyL3dvcmsvZ3V0ZW5iZXJnL2d1dGVuYmVyZy9wYWNrYWdlcy93b3JrZmxvdy9zcmMvY29tcG9uZW50cyIsInNvdXJjZXMiOlsiLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL0B3b3JkcHJlc3MvYmFzZS1zdHlsZXMvX21peGlucy5zY3NzIiwiLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL0B3b3JkcHJlc3MvYmFzZS1zdHlsZXMvX3ZhcmlhYmxlcy5zY3NzIiwiLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL0B3b3JkcHJlc3MvYmFzZS1zdHlsZXMvX2NvbG9ycy5zY3NzIiwiLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL0B3b3JkcHJlc3MvYmFzZS1zdHlsZXMvX2JyZWFrcG9pbnRzLnNjc3MiLCIuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvQHdvcmRwcmVzcy9iYXNlLXN0eWxlcy9fZnVuY3Rpb25zLnNjc3MiLCIuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvQHdvcmRwcmVzcy9iYXNlLXN0eWxlcy9fbG9uZy1jb250ZW50LWZhZGUuc2NzcyIsIi4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9Ad29yZHByZXNzL2Jhc2Utc3R5bGVzL19kZWZhdWx0LWN1c3RvbS1wcm9wZXJ0aWVzLnNjc3MiLCJ3b3JrZmxvdy1tZW51LnNjc3MiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FDQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUNBQTtBQUFBO0FBQUE7QURVQTtBQUFBO0FBQUE7QUFPQTtBQUFBO0FBQUE7QUE2QkE7QUFBQTtBQUFBO0FBQUE7QUFpQkE7QUFBQTtBQUFBO0FBV0E7QUFBQTtBQUFBO0FBZ0JBO0FBQUE7QUFBQTtBQXlCQTtBQUFBO0FBQUE7QUFLQTtBQUFBO0FBQUE7QUFlQTtBQUFBO0FBQUE7QUFtQkE7QUFBQTtBQUFBO0FBU0E7QUFBQTtBQUFBO0FBQUE7QUVuS0E7QUFBQTtBQUFBO0FDQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FDQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FMNEVBO0FBQUE7QUFBQTtBQTBEQTtBQUFBO0FBQUE7QUFnREE7QUFBQTtBQUFBO0FBcUNBO0FBQUE7QUFBQTtBQW9CQTtBQUFBO0FBQUE7QUEyS0E7QUFBQTtBQUFBO0FBQUE7QUFnREE7QUFBQTtBQUFBO0FBcU5BO0FBQUE7QUFBQTtBQUFBO0FNeHBCQTtFQUNDO0VBQ0E7RUFHQTtFQUNBO0VOeWVBO0VBQ0E7RUFFQTtFQUNBO0VBQ0E7RUFDQTtFQUlBOztBQUNBO0VNMWZEO0lOMmZFOzs7O0FPM2ZGO0VBQ0MsZU40Q2M7RU0zQ2Q7RUFDQTtFQUNBO0VBQ0E7RUFDQTs7QVB3R0E7RU85R0Q7SUFTRTs7O0FBR0Q7RUFDQztFQUNBOzs7QUFJRjtFQUNDO0VBQ0E7OztBQUdEO0VBQ0M7OztBQUlBO0VBQ0M7OztBQUlGO0VBRUM7O0FBRUE7RUFDQzs7QUFHRDtFQUNDO0VBQ0E7RUFDQTtFQUNBO0VBQ0EsT0wvQ1M7RUtnRFQ7RUFDQTtFQUNBO0VBQ0E7O0FBRUE7RUFDQyxPTHBEUTs7QUt1RFQ7RUFDQztFQUNBOztBQUlGO0VBQ0MsZU5GYTtFTUdiO0VBQ0E7RUFDQTtFQUNBLE9McEVTO0VLcUVULFdObkRpQjs7QU1xRGpCO0VBRUM7RUFDQSxPTGxFSzs7QUtxRU47RUFDQyxPTDNFUTtFSzRFUjs7QUFHRDtFQUNDLFlOTzZCO0VNTjdCLFNOdENZO0VNdUNaLGNOcENZOztBTXdDZDtFQUNDLFlOaUJtQjtFTWhCbkI7O0FBR0E7RUFHQyxnQk5sRFk7O0FNcURiO0VBQ0M7O0FBSUY7RUFDQztFQUNBO0VBQ0E7RUFDQTtFQUNBLE9MOUdTO0VLK0dUOztBQUdEO0VBQ0MsU05sRWE7O0FNcUVkO0VBQ0M7OztBQUlGO0VBRUM7RUFDQTtFQUNBO0VBQ0E7OztBQUdEO0VBQ0M7RUFDQTtFQUNBOzs7QUFHRDtFQUNDLFNOekZjOzs7QU00RmY7RUFDQyxlTjdGYztFTThGZDtFQUNBLGdCTmpHYzs7QU1tR2Q7RUFDQztFQUNBO0VBQ0E7RUFDQSxPTHRKUzs7O0FLMEpYO0VBQ0M7RUFDQTtFQUNBLE9MM0pVOzs7QUs4Slg7RUFDQztFQUNBOztBQUVBO0VBQ0M7RUFDQSxTTnRIYTtFTXVIYixZTGhLUztFS2lLVCxlTjFHYTtFTTJHYjtFQUNBO0VBQ0E7RUFDQTtFQUNBLE9MN0tTOzs7QUtpTFg7RUFDQyxTTmxJYztFTW1JZCxZTDdLVTtFSzhLVjtFQUNBLGVOdkhjO0VNd0hkLE9McktXOztBS3VLWDtFQUNDO0VBQ0E7OztBQUlGO0VBQ0M7RUFDQSxPTDlMVTtFSytMViIsInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogVHlwb2dyYXBoeVxuICovXG5cbkB1c2UgXCJzYXNzOmNvbG9yXCI7XG5AdXNlIFwic2FzczptYXRoXCI7XG5AdXNlIFwiLi92YXJpYWJsZXNcIjtcbkB1c2UgXCIuL2NvbG9yc1wiO1xuQHVzZSBcIi4vYnJlYWtwb2ludHNcIjtcbkB1c2UgXCIuL2Z1bmN0aW9uc1wiO1xuQHVzZSBcIi4vbG9uZy1jb250ZW50LWZhZGVcIjtcblxuQG1peGluIF90ZXh0LWhlYWRpbmcoKSB7XG5cdGZvbnQtZmFtaWx5OiB2YXJpYWJsZXMuJGZvbnQtZmFtaWx5LWhlYWRpbmdzO1xuXHRmb250LXdlaWdodDogdmFyaWFibGVzLiRmb250LXdlaWdodC1tZWRpdW07XG59XG5cbkBtaXhpbiBfdGV4dC1ib2R5KCkge1xuXHRmb250LWZhbWlseTogdmFyaWFibGVzLiRmb250LWZhbWlseS1ib2R5O1xuXHRmb250LXdlaWdodDogdmFyaWFibGVzLiRmb250LXdlaWdodC1yZWd1bGFyO1xufVxuXG5AbWl4aW4gaGVhZGluZy1zbWFsbCgpIHtcblx0QGluY2x1ZGUgX3RleHQtaGVhZGluZygpO1xuXHRmb250LXNpemU6IHZhcmlhYmxlcy4kZm9udC1zaXplLXgtc21hbGw7XG5cdGxpbmUtaGVpZ2h0OiB2YXJpYWJsZXMuJGZvbnQtbGluZS1oZWlnaHQteC1zbWFsbDtcbn1cblxuQG1peGluIGhlYWRpbmctbWVkaXVtKCkge1xuXHRAaW5jbHVkZSBfdGV4dC1oZWFkaW5nKCk7XG5cdGZvbnQtc2l6ZTogdmFyaWFibGVzLiRmb250LXNpemUtbWVkaXVtO1xuXHRsaW5lLWhlaWdodDogdmFyaWFibGVzLiRmb250LWxpbmUtaGVpZ2h0LXNtYWxsO1xufVxuXG5AbWl4aW4gaGVhZGluZy1sYXJnZSgpIHtcblx0QGluY2x1ZGUgX3RleHQtaGVhZGluZygpO1xuXHRmb250LXNpemU6IHZhcmlhYmxlcy4kZm9udC1zaXplLWxhcmdlO1xuXHRsaW5lLWhlaWdodDogdmFyaWFibGVzLiRmb250LWxpbmUtaGVpZ2h0LXNtYWxsO1xufVxuXG5AbWl4aW4gaGVhZGluZy14LWxhcmdlKCkge1xuXHRAaW5jbHVkZSBfdGV4dC1oZWFkaW5nKCk7XG5cdGZvbnQtc2l6ZTogdmFyaWFibGVzLiRmb250LXNpemUteC1sYXJnZTtcblx0bGluZS1oZWlnaHQ6IHZhcmlhYmxlcy4kZm9udC1saW5lLWhlaWdodC1tZWRpdW07XG59XG5cbkBtaXhpbiBoZWFkaW5nLTJ4LWxhcmdlKCkge1xuXHRAaW5jbHVkZSBfdGV4dC1oZWFkaW5nKCk7XG5cdGZvbnQtc2l6ZTogdmFyaWFibGVzLiRmb250LXNpemUtMngtbGFyZ2U7XG5cdGxpbmUtaGVpZ2h0OiB2YXJpYWJsZXMuJGZvbnQtbGluZS1oZWlnaHQtMngtbGFyZ2U7XG59XG5cbkBtaXhpbiBib2R5LXNtYWxsKCkge1xuXHRAaW5jbHVkZSBfdGV4dC1ib2R5KCk7XG5cdGZvbnQtc2l6ZTogdmFyaWFibGVzLiRmb250LXNpemUtc21hbGw7XG5cdGxpbmUtaGVpZ2h0OiB2YXJpYWJsZXMuJGZvbnQtbGluZS1oZWlnaHQteC1zbWFsbDtcbn1cblxuQG1peGluIGJvZHktbWVkaXVtKCkge1xuXHRAaW5jbHVkZSBfdGV4dC1ib2R5KCk7XG5cdGZvbnQtc2l6ZTogdmFyaWFibGVzLiRmb250LXNpemUtbWVkaXVtO1xuXHRsaW5lLWhlaWdodDogdmFyaWFibGVzLiRmb250LWxpbmUtaGVpZ2h0LXNtYWxsO1xufVxuXG5AbWl4aW4gYm9keS1sYXJnZSgpIHtcblx0QGluY2x1ZGUgX3RleHQtYm9keSgpO1xuXHRmb250LXNpemU6IHZhcmlhYmxlcy4kZm9udC1zaXplLWxhcmdlO1xuXHRsaW5lLWhlaWdodDogdmFyaWFibGVzLiRmb250LWxpbmUtaGVpZ2h0LW1lZGl1bTtcbn1cblxuQG1peGluIGJvZHkteC1sYXJnZSgpIHtcblx0QGluY2x1ZGUgX3RleHQtYm9keSgpO1xuXHRmb250LXNpemU6IHZhcmlhYmxlcy4kZm9udC1zaXplLXgtbGFyZ2U7XG5cdGxpbmUtaGVpZ2h0OiB2YXJpYWJsZXMuJGZvbnQtbGluZS1oZWlnaHQteC1sYXJnZTtcbn1cblxuLyoqXG4gKiBCcmVha3BvaW50IG1peGluc1xuICovXG5cbkBtaXhpbiBicmVhay14aHVnZSgpIHtcblx0QG1lZGlhIChtaW4td2lkdGg6ICN7IChicmVha3BvaW50cy4kYnJlYWsteGh1Z2UpIH0pIHtcblx0XHRAY29udGVudDtcblx0fVxufVxuXG5AbWl4aW4gYnJlYWstaHVnZSgpIHtcblx0QG1lZGlhIChtaW4td2lkdGg6ICN7IChicmVha3BvaW50cy4kYnJlYWstaHVnZSkgfSkge1xuXHRcdEBjb250ZW50O1xuXHR9XG59XG5cbkBtaXhpbiBicmVhay13aWRlKCkge1xuXHRAbWVkaWEgKG1pbi13aWR0aDogI3sgKGJyZWFrcG9pbnRzLiRicmVhay13aWRlKSB9KSB7XG5cdFx0QGNvbnRlbnQ7XG5cdH1cbn1cblxuQG1peGluIGJyZWFrLXhsYXJnZSgpIHtcblx0QG1lZGlhIChtaW4td2lkdGg6ICN7IChicmVha3BvaW50cy4kYnJlYWsteGxhcmdlKSB9KSB7XG5cdFx0QGNvbnRlbnQ7XG5cdH1cbn1cblxuQG1peGluIGJyZWFrLWxhcmdlKCkge1xuXHRAbWVkaWEgKG1pbi13aWR0aDogI3sgKGJyZWFrcG9pbnRzLiRicmVhay1sYXJnZSkgfSkge1xuXHRcdEBjb250ZW50O1xuXHR9XG59XG5cbkBtaXhpbiBicmVhay1tZWRpdW0oKSB7XG5cdEBtZWRpYSAobWluLXdpZHRoOiAjeyAoYnJlYWtwb2ludHMuJGJyZWFrLW1lZGl1bSkgfSkge1xuXHRcdEBjb250ZW50O1xuXHR9XG59XG5cbkBtaXhpbiBicmVhay1zbWFsbCgpIHtcblx0QG1lZGlhIChtaW4td2lkdGg6ICN7IChicmVha3BvaW50cy4kYnJlYWstc21hbGwpIH0pIHtcblx0XHRAY29udGVudDtcblx0fVxufVxuXG5AbWl4aW4gYnJlYWstbW9iaWxlKCkge1xuXHRAbWVkaWEgKG1pbi13aWR0aDogI3sgKGJyZWFrcG9pbnRzLiRicmVhay1tb2JpbGUpIH0pIHtcblx0XHRAY29udGVudDtcblx0fVxufVxuXG5AbWl4aW4gYnJlYWstem9vbWVkLWluKCkge1xuXHRAbWVkaWEgKG1pbi13aWR0aDogI3sgKGJyZWFrcG9pbnRzLiRicmVhay16b29tZWQtaW4pIH0pIHtcblx0XHRAY29udGVudDtcblx0fVxufVxuXG4vKipcbiAqIEZvY3VzIHN0eWxlcy5cbiAqL1xuXG5AbWl4aW4gYmxvY2stdG9vbGJhci1idXR0b24tc3R5bGVfX2ZvY3VzKCkge1xuXHRib3gtc2hhZG93OiBpbnNldCAwIDAgMCB2YXJpYWJsZXMuJGJvcmRlci13aWR0aCBjb2xvcnMuJHdoaXRlLCAwIDAgMCB2YXIoLS13cC1hZG1pbi1ib3JkZXItd2lkdGgtZm9jdXMpIHZhcigtLXdwLWFkbWluLXRoZW1lLWNvbG9yKTtcblxuXHQvLyBXaW5kb3dzIEhpZ2ggQ29udHJhc3QgbW9kZSB3aWxsIHNob3cgdGhpcyBvdXRsaW5lLCBidXQgbm90IHRoZSBib3gtc2hhZG93LlxuXHRvdXRsaW5lOiAycHggc29saWQgdHJhbnNwYXJlbnQ7XG59XG5cbi8vIFRhYnMsIElucHV0cywgU3F1YXJlIGJ1dHRvbnMuXG5AbWl4aW4gaW5wdXQtc3R5bGVfX25ldXRyYWwoKSB7XG5cdGJveC1zaGFkb3c6IDAgMCAwIHRyYW5zcGFyZW50O1xuXHRib3JkZXItcmFkaXVzOiB2YXJpYWJsZXMuJHJhZGl1cy1zbWFsbDtcblx0Ym9yZGVyOiB2YXJpYWJsZXMuJGJvcmRlci13aWR0aCBzb2xpZCBjb2xvcnMuJGdyYXktNjAwO1xuXG5cdEBtZWRpYSBub3QgKHByZWZlcnMtcmVkdWNlZC1tb3Rpb24pIHtcblx0XHR0cmFuc2l0aW9uOiBib3gtc2hhZG93IDAuMXMgbGluZWFyO1xuXHR9XG59XG5cblxuQG1peGluIGlucHV0LXN0eWxlX19mb2N1cygkYWNjZW50LWNvbG9yOiB2YXIoLS13cC1hZG1pbi10aGVtZS1jb2xvcikpIHtcblx0Ym9yZGVyLWNvbG9yOiAkYWNjZW50LWNvbG9yO1xuXHQvLyBFeHBhbmQgdGhlIGRlZmF1bHQgYm9yZGVyIGZvY3VzIHN0eWxlIGJ5IC41cHggdG8gYmUgYSB0b3RhbCBvZiAxLjVweC5cblx0Ym94LXNoYWRvdzogMCAwIDAgMC41cHggJGFjY2VudC1jb2xvcjtcblx0Ly8gV2luZG93cyBIaWdoIENvbnRyYXN0IG1vZGUgd2lsbCBzaG93IHRoaXMgb3V0bGluZSwgYnV0IG5vdCB0aGUgYm94LXNoYWRvdy5cblx0b3V0bGluZTogMnB4IHNvbGlkIHRyYW5zcGFyZW50O1xufVxuXG5AbWl4aW4gYnV0dG9uLXN0eWxlX19mb2N1cygpIHtcblx0Ym94LXNoYWRvdzogMCAwIDAgdmFyKC0td3AtYWRtaW4tYm9yZGVyLXdpZHRoLWZvY3VzKSB2YXIoLS13cC1hZG1pbi10aGVtZS1jb2xvcik7XG5cblx0Ly8gV2luZG93cyBIaWdoIENvbnRyYXN0IG1vZGUgd2lsbCBzaG93IHRoaXMgb3V0bGluZSwgYnV0IG5vdCB0aGUgYm94LXNoYWRvdy5cblx0b3V0bGluZTogMnB4IHNvbGlkIHRyYW5zcGFyZW50O1xufVxuXG5cbkBtaXhpbiBidXR0b24tc3R5bGUtb3V0c2V0X19mb2N1cygkZm9jdXMtY29sb3IpIHtcblx0Ym94LXNoYWRvdzogMCAwIDAgdmFyKC0td3AtYWRtaW4tYm9yZGVyLXdpZHRoLWZvY3VzKSBjb2xvcnMuJHdoaXRlLCAwIDAgMCBjYWxjKDIgKiB2YXIoLS13cC1hZG1pbi1ib3JkZXItd2lkdGgtZm9jdXMpKSAkZm9jdXMtY29sb3I7XG5cblx0Ly8gV2luZG93cyBIaWdoIENvbnRyYXN0IG1vZGUgd2lsbCBzaG93IHRoaXMgb3V0bGluZSwgYnV0IG5vdCB0aGUgYm94LXNoYWRvdy5cblx0b3V0bGluZTogMnB4IHNvbGlkIHRyYW5zcGFyZW50O1xuXHRvdXRsaW5lLW9mZnNldDogMnB4O1xufVxuXG5cbi8qKlxuICogQXBwbGllcyBlZGl0b3IgbGVmdCBwb3NpdGlvbiB0byB0aGUgc2VsZWN0b3IgcGFzc2VkIGFzIGFyZ3VtZW50XG4gKi9cblxuQG1peGluIGVkaXRvci1sZWZ0KCRzZWxlY3Rvcikge1xuXHQjeyRzZWxlY3Rvcn0geyAvKiBTZXQgbGVmdCBwb3NpdGlvbiB3aGVuIGF1dG8tZm9sZCBpcyBub3Qgb24gdGhlIGJvZHkgZWxlbWVudC4gKi9cblx0XHRsZWZ0OiAwO1xuXG5cdFx0QG1lZGlhIChtaW4td2lkdGg6ICN7IChicmVha3BvaW50cy4kYnJlYWstbWVkaXVtICsgMSkgfSkge1xuXHRcdFx0bGVmdDogdmFyaWFibGVzLiRhZG1pbi1zaWRlYmFyLXdpZHRoO1xuXHRcdH1cblx0fVxuXG5cdC5hdXRvLWZvbGQgI3skc2VsZWN0b3J9IHsgLyogQXV0byBmb2xkIGlzIHdoZW4gb24gc21hbGxlciBicmVha3BvaW50cywgbmF2IG1lbnUgYXV0byBjb2xsYXBzZXMuICovXG5cdFx0QG1lZGlhIChtaW4td2lkdGg6ICN7IChicmVha3BvaW50cy4kYnJlYWstbWVkaXVtICsgMSkgfSkge1xuXHRcdFx0bGVmdDogdmFyaWFibGVzLiRhZG1pbi1zaWRlYmFyLXdpZHRoLWNvbGxhcHNlZDtcblx0XHR9XG5cblx0XHRAbWVkaWEgKG1pbi13aWR0aDogI3sgKGJyZWFrcG9pbnRzLiRicmVhay1sYXJnZSArIDEpIH0pIHtcblx0XHRcdGxlZnQ6IHZhcmlhYmxlcy4kYWRtaW4tc2lkZWJhci13aWR0aDtcblx0XHR9XG5cdH1cblxuXHQvKiBTaWRlYmFyIG1hbnVhbGx5IGNvbGxhcHNlZC4gKi9cblx0LmZvbGRlZCAjeyRzZWxlY3Rvcn0ge1xuXHRcdGxlZnQ6IDA7XG5cblx0XHRAbWVkaWEgKG1pbi13aWR0aDogI3sgKGJyZWFrcG9pbnRzLiRicmVhay1tZWRpdW0gKyAxKSB9KSB7XG5cdFx0XHRsZWZ0OiB2YXJpYWJsZXMuJGFkbWluLXNpZGViYXItd2lkdGgtY29sbGFwc2VkO1xuXHRcdH1cblx0fVxuXG5cdGJvZHkuaXMtZnVsbHNjcmVlbi1tb2RlICN7JHNlbGVjdG9yfSB7XG5cdFx0bGVmdDogMCAhaW1wb3J0YW50O1xuXHR9XG59XG5cbi8qKlxuICogU3R5bGVzIHRoYXQgYXJlIHJldXNlZCB2ZXJiYXRpbSBpbiBhIGZldyBwbGFjZXNcbiAqL1xuXG4vLyBUaGVzZSBhcmUgYWRkaXRpb25hbCBzdHlsZXMgZm9yIGFsbCBjYXB0aW9ucywgd2hlbiB0aGUgdGhlbWUgb3B0cyBpbiB0byBibG9jayBzdHlsZXMuXG5AbWl4aW4gY2FwdGlvbi1zdHlsZSgpIHtcblx0bWFyZ2luLXRvcDogMC41ZW07XG5cdG1hcmdpbi1ib3R0b206IDFlbTtcbn1cblxuQG1peGluIGNhcHRpb24tc3R5bGUtdGhlbWUoKSB7XG5cdGNvbG9yOiAjNTU1O1xuXHRmb250LXNpemU6IHZhcmlhYmxlcy4kZGVmYXVsdC1mb250LXNpemU7XG5cdHRleHQtYWxpZ246IGNlbnRlcjtcblxuXHQuaXMtZGFyay10aGVtZSAmIHtcblx0XHRjb2xvcjogY29sb3JzLiRsaWdodC1ncmF5LXBsYWNlaG9sZGVyO1xuXHR9XG59XG5cbi8qKlxuICogQWxsb3dzIHVzZXJzIHRvIG9wdC1vdXQgb2YgYW5pbWF0aW9ucyB2aWEgT1MtbGV2ZWwgcHJlZmVyZW5jZXMuXG4gKi9cblxuQG1peGluIHJlZHVjZS1tb3Rpb24oJHByb3BlcnR5OiBcIlwiKSB7XG5cblx0QGlmICRwcm9wZXJ0eSA9PSBcInRyYW5zaXRpb25cIiB7XG5cdFx0QG1lZGlhIChwcmVmZXJzLXJlZHVjZWQtbW90aW9uOiByZWR1Y2UpIHtcblx0XHRcdHRyYW5zaXRpb24tZHVyYXRpb246IDBzO1xuXHRcdFx0dHJhbnNpdGlvbi1kZWxheTogMHM7XG5cdFx0fVxuXHR9IEBlbHNlIGlmICRwcm9wZXJ0eSA9PSBcImFuaW1hdGlvblwiIHtcblx0XHRAbWVkaWEgKHByZWZlcnMtcmVkdWNlZC1tb3Rpb246IHJlZHVjZSkge1xuXHRcdFx0YW5pbWF0aW9uLWR1cmF0aW9uOiAxbXM7XG5cdFx0XHRhbmltYXRpb24tZGVsYXk6IDBzO1xuXHRcdH1cblx0fSBAZWxzZSB7XG5cdFx0QG1lZGlhIChwcmVmZXJzLXJlZHVjZWQtbW90aW9uOiByZWR1Y2UpIHtcblx0XHRcdHRyYW5zaXRpb24tZHVyYXRpb246IDBzO1xuXHRcdFx0dHJhbnNpdGlvbi1kZWxheTogMHM7XG5cdFx0XHRhbmltYXRpb24tZHVyYXRpb246IDFtcztcblx0XHRcdGFuaW1hdGlvbi1kZWxheTogMHM7XG5cdFx0fVxuXHR9XG59XG5cbkBtaXhpbiBpbnB1dC1jb250cm9sKCRhY2NlbnQtY29sb3I6IHZhcigtLXdwLWFkbWluLXRoZW1lLWNvbG9yKSkge1xuXHRmb250LWZhbWlseTogdmFyaWFibGVzLiRkZWZhdWx0LWZvbnQ7XG5cdHBhZGRpbmc6IDZweCA4cHg7XG5cdC8qIEZvbnRzIHNtYWxsZXIgdGhhbiAxNnB4IGNhdXNlcyBtb2JpbGUgc2FmYXJpIHRvIHpvb20uICovXG5cdGZvbnQtc2l6ZTogdmFyaWFibGVzLiRtb2JpbGUtdGV4dC1taW4tZm9udC1zaXplO1xuXHQvKiBPdmVycmlkZSBjb3JlIGxpbmUtaGVpZ2h0LiBUbyBiZSByZXZpZXdlZC4gKi9cblx0bGluZS1oZWlnaHQ6IG5vcm1hbDtcblx0QGluY2x1ZGUgaW5wdXQtc3R5bGVfX25ldXRyYWwoKTtcblxuXHRAaW5jbHVkZSBicmVhay1zbWFsbCB7XG5cdFx0Zm9udC1zaXplOiB2YXJpYWJsZXMuJGRlZmF1bHQtZm9udC1zaXplO1xuXHRcdC8qIE92ZXJyaWRlIGNvcmUgbGluZS1oZWlnaHQuIFRvIGJlIHJldmlld2VkLiAqL1xuXHRcdGxpbmUtaGVpZ2h0OiBub3JtYWw7XG5cdH1cblxuXHQmOmZvY3VzIHtcblx0XHRAaW5jbHVkZSBpbnB1dC1zdHlsZV9fZm9jdXMoJGFjY2VudC1jb2xvcik7XG5cdH1cblxuXHQvLyBVc2Ugb3BhY2l0eSB0byB3b3JrIGluIHZhcmlvdXMgZWRpdG9yIHN0eWxlcy5cblx0Jjo6cGxhY2Vob2xkZXIge1xuXHRcdGNvbG9yOiBjb2xvcnMuJGRhcmstZ3JheS1wbGFjZWhvbGRlcjtcblx0fVxufVxuXG5AbWl4aW4gY2hlY2tib3gtY29udHJvbCB7XG5cdGJvcmRlcjogdmFyaWFibGVzLiRib3JkZXItd2lkdGggc29saWQgY29sb3JzLiRncmF5LTkwMDtcblx0bWFyZ2luLXJpZ2h0OiB2YXJpYWJsZXMuJGdyaWQtdW5pdC0xNTtcblx0dHJhbnNpdGlvbjogbm9uZTtcblx0Ym9yZGVyLXJhZGl1czogdmFyaWFibGVzLiRyYWRpdXMtc21hbGw7XG5cdEBpbmNsdWRlIGlucHV0LWNvbnRyb2w7XG5cblx0Jjpmb2N1cyB7XG5cdFx0Ym94LXNoYWRvdzogMCAwIDAgKHZhcmlhYmxlcy4kYm9yZGVyLXdpZHRoICogMikgY29sb3JzLiR3aGl0ZSwgMCAwIDAgKHZhcmlhYmxlcy4kYm9yZGVyLXdpZHRoICogMiArIHZhcmlhYmxlcy4kYm9yZGVyLXdpZHRoLWZvY3VzLWZhbGxiYWNrKSB2YXIoLS13cC1hZG1pbi10aGVtZS1jb2xvcik7XG5cblx0XHQvLyBPbmx5IHZpc2libGUgaW4gV2luZG93cyBIaWdoIENvbnRyYXN0IG1vZGUuXG5cdFx0b3V0bGluZTogMnB4IHNvbGlkIHRyYW5zcGFyZW50O1xuXHR9XG5cblx0JjpjaGVja2VkIHtcblx0XHRiYWNrZ3JvdW5kOiB2YXIoLS13cC1hZG1pbi10aGVtZS1jb2xvcik7XG5cdFx0Ym9yZGVyLWNvbG9yOiB2YXIoLS13cC1hZG1pbi10aGVtZS1jb2xvcik7XG5cdH1cblxuXHQmOmNoZWNrZWQ6OmJlZm9yZSxcblx0JlthcmlhLWNoZWNrZWQ9XCJtaXhlZFwiXTo6YmVmb3JlIHtcblx0XHRtYXJnaW46IC0zcHggLTVweDtcblx0XHRjb2xvcjogY29sb3JzLiR3aGl0ZTtcblxuXHRcdEBpbmNsdWRlIGJyZWFrLW1lZGl1bSgpIHtcblx0XHRcdG1hcmdpbjogLTRweCAwIDAgLTVweDtcblx0XHR9XG5cdH1cblxuXHQmW2FyaWEtY2hlY2tlZD1cIm1peGVkXCJdIHtcblx0XHRiYWNrZ3JvdW5kOiB2YXIoLS13cC1hZG1pbi10aGVtZS1jb2xvcik7XG5cdFx0Ym9yZGVyLWNvbG9yOiB2YXIoLS13cC1hZG1pbi10aGVtZS1jb2xvcik7XG5cblx0XHQmOjpiZWZvcmUge1xuXHRcdFx0Ly8gSW5oZXJpdGVkIGZyb20gYGZvcm1zLmNzc2AuXG5cdFx0XHQvLyBTZWU6IGh0dHBzOi8vZ2l0aHViLmNvbS9Xb3JkUHJlc3Mvd29yZHByZXNzLWRldmVsb3AvdHJlZS81LjEuMS9zcmMvd3AtYWRtaW4vY3NzL2Zvcm1zLmNzcyNMMTIyLUwxMzJcblx0XHRcdGNvbnRlbnQ6IFwiXFxmNDYwXCI7XG5cdFx0XHRmbG9hdDogbGVmdDtcblx0XHRcdGRpc3BsYXk6IGlubGluZS1ibG9jaztcblx0XHRcdHZlcnRpY2FsLWFsaWduOiBtaWRkbGU7XG5cdFx0XHR3aWR0aDogMTZweDtcblx0XHRcdC8qIHN0eWxlbGludC1kaXNhYmxlLW5leHQtbGluZSBmb250LWZhbWlseS1uby1taXNzaW5nLWdlbmVyaWMtZmFtaWx5LWtleXdvcmQgLS0gZGFzaGljb25zIGRvbid0IG5lZWQgYSBnZW5lcmljIGZhbWlseSBrZXl3b3JkLiAqL1xuXHRcdFx0Zm9udDogbm9ybWFsIDMwcHgvMSBkYXNoaWNvbnM7XG5cdFx0XHRzcGVhazogbm9uZTtcblx0XHRcdC13ZWJraXQtZm9udC1zbW9vdGhpbmc6IGFudGlhbGlhc2VkO1xuXHRcdFx0LW1vei1vc3gtZm9udC1zbW9vdGhpbmc6IGdyYXlzY2FsZTtcblxuXHRcdFx0QGluY2x1ZGUgYnJlYWstbWVkaXVtKCkge1xuXHRcdFx0XHRmbG9hdDogbm9uZTtcblx0XHRcdFx0Zm9udC1zaXplOiAyMXB4O1xuXHRcdFx0fVxuXHRcdH1cblx0fVxuXG5cdCZbYXJpYS1kaXNhYmxlZD1cInRydWVcIl0sXG5cdCY6ZGlzYWJsZWQge1xuXHRcdGJhY2tncm91bmQ6IGNvbG9ycy4kZ3JheS0xMDA7XG5cdFx0Ym9yZGVyLWNvbG9yOiBjb2xvcnMuJGdyYXktMzAwO1xuXHRcdGN1cnNvcjogZGVmYXVsdDtcblxuXHRcdC8vIE92ZXJyaWRlIHN0eWxlIGluaGVyaXRlZCBmcm9tIHdwLWFkbWluLiBSZXF1aXJlZCB0byBhdm9pZCBkZWdyYWRlZCBhcHBlYXJhbmNlIG9uIGRpZmZlcmVudCBiYWNrZ3JvdW5kcy5cblx0XHRvcGFjaXR5OiAxO1xuXHR9XG59XG5cbkBtaXhpbiByYWRpby1jb250cm9sIHtcblx0Ym9yZGVyOiB2YXJpYWJsZXMuJGJvcmRlci13aWR0aCBzb2xpZCBjb2xvcnMuJGdyYXktOTAwO1xuXHRtYXJnaW4tcmlnaHQ6IHZhcmlhYmxlcy4kZ3JpZC11bml0LTE1O1xuXHR0cmFuc2l0aW9uOiBub25lO1xuXHRib3JkZXItcmFkaXVzOiB2YXJpYWJsZXMuJHJhZGl1cy1yb3VuZDtcblx0d2lkdGg6IHZhcmlhYmxlcy4kcmFkaW8taW5wdXQtc2l6ZS1zbTtcblx0aGVpZ2h0OiB2YXJpYWJsZXMuJHJhZGlvLWlucHV0LXNpemUtc207XG5cdG1pbi13aWR0aDogdmFyaWFibGVzLiRyYWRpby1pbnB1dC1zaXplLXNtO1xuXHRtYXgtd2lkdGg6IHZhcmlhYmxlcy4kcmFkaW8taW5wdXQtc2l6ZS1zbTtcblx0cG9zaXRpb246IHJlbGF0aXZlO1xuXG5cdEBtZWRpYSBub3QgKHByZWZlcnMtcmVkdWNlZC1tb3Rpb24pIHtcblx0XHR0cmFuc2l0aW9uOiBib3gtc2hhZG93IDAuMXMgbGluZWFyO1xuXHR9XG5cblx0QGluY2x1ZGUgYnJlYWstc21hbGwoKSB7XG5cdFx0aGVpZ2h0OiB2YXJpYWJsZXMuJHJhZGlvLWlucHV0LXNpemU7XG5cdFx0d2lkdGg6IHZhcmlhYmxlcy4kcmFkaW8taW5wdXQtc2l6ZTtcblx0XHRtaW4td2lkdGg6IHZhcmlhYmxlcy4kcmFkaW8taW5wdXQtc2l6ZTtcblx0XHRtYXgtd2lkdGg6IHZhcmlhYmxlcy4kcmFkaW8taW5wdXQtc2l6ZTtcblx0fVxuXG5cdCY6Y2hlY2tlZDo6YmVmb3JlIHtcblx0XHRib3gtc2l6aW5nOiBpbmhlcml0O1xuXHRcdHdpZHRoOiBtYXRoLmRpdih2YXJpYWJsZXMuJHJhZGlvLWlucHV0LXNpemUtc20sIDIpO1xuXHRcdGhlaWdodDogbWF0aC5kaXYodmFyaWFibGVzLiRyYWRpby1pbnB1dC1zaXplLXNtLCAyKTtcblx0XHRwb3NpdGlvbjogYWJzb2x1dGU7XG5cdFx0dG9wOiA1MCU7XG5cdFx0bGVmdDogNTAlO1xuXHRcdHRyYW5zZm9ybTogdHJhbnNsYXRlKC01MCUsIC01MCUpO1xuXHRcdG1hcmdpbjogMDtcblx0XHRiYWNrZ3JvdW5kLWNvbG9yOiBjb2xvcnMuJHdoaXRlO1xuXG5cdFx0Ly8gVGhpcyBib3JkZXIgc2VydmVzIGFzIGEgYmFja2dyb3VuZCBjb2xvciBpbiBXaW5kb3dzIEhpZ2ggQ29udHJhc3QgbW9kZS5cblx0XHRib3JkZXI6IDRweCBzb2xpZCBjb2xvcnMuJHdoaXRlO1xuXG5cdFx0QGluY2x1ZGUgYnJlYWstc21hbGwoKSB7XG5cdFx0XHR3aWR0aDogbWF0aC5kaXYodmFyaWFibGVzLiRyYWRpby1pbnB1dC1zaXplLCAyKTtcblx0XHRcdGhlaWdodDogbWF0aC5kaXYodmFyaWFibGVzLiRyYWRpby1pbnB1dC1zaXplLCAyKTtcblx0XHR9XG5cdH1cblxuXHQmOmZvY3VzIHtcblx0XHRib3gtc2hhZG93OiAwIDAgMCAodmFyaWFibGVzLiRib3JkZXItd2lkdGggKiAyKSBjb2xvcnMuJHdoaXRlLCAwIDAgMCAodmFyaWFibGVzLiRib3JkZXItd2lkdGggKiAyICsgdmFyaWFibGVzLiRib3JkZXItd2lkdGgtZm9jdXMtZmFsbGJhY2spIHZhcigtLXdwLWFkbWluLXRoZW1lLWNvbG9yKTtcblxuXHRcdC8vIE9ubHkgdmlzaWJsZSBpbiBXaW5kb3dzIEhpZ2ggQ29udHJhc3QgbW9kZS5cblx0XHRvdXRsaW5lOiAycHggc29saWQgdHJhbnNwYXJlbnQ7XG5cdH1cblxuXHQmOmNoZWNrZWQge1xuXHRcdGJhY2tncm91bmQ6IHZhcigtLXdwLWFkbWluLXRoZW1lLWNvbG9yKTtcblx0XHRib3JkZXI6IG5vbmU7XG5cdH1cbn1cblxuLyoqXG4gKiBSZXNldCBkZWZhdWx0IHN0eWxlcyBmb3IgSmF2YVNjcmlwdCBVSSBiYXNlZCBwYWdlcy5cbiAqIFRoaXMgaXMgYSBXUC1hZG1pbiBhZ25vc3RpYyByZXNldFxuICovXG5cbkBtaXhpbiByZXNldCB7XG5cdGJveC1zaXppbmc6IGJvcmRlci1ib3g7XG5cblx0Kixcblx0Kjo6YmVmb3JlLFxuXHQqOjphZnRlciB7XG5cdFx0Ym94LXNpemluZzogaW5oZXJpdDtcblx0fVxufVxuXG5AbWl4aW4gbGluay1yZXNldCB7XG5cdCY6Zm9jdXMge1xuXHRcdGNvbG9yOiB2YXIoLS13cC1hZG1pbi10aGVtZS1jb2xvci0tcmdiKTtcblx0XHRib3gtc2hhZG93OiAwIDAgMCB2YXIoLS13cC1hZG1pbi1ib3JkZXItd2lkdGgtZm9jdXMpIHZhcigtLXdwLWFkbWluLXRoZW1lLWNvbG9yLCAjMDA3Y2JhKTtcblx0XHRib3JkZXItcmFkaXVzOiB2YXJpYWJsZXMuJHJhZGl1cy1zbWFsbDtcblx0fVxufVxuXG4vLyBUaGUgZWRpdG9yIGlucHV0IHJlc2V0IHdpdGggaW5jcmVhc2VkIHNwZWNpZmljaXR5IHRvIGF2b2lkIHRoZW1lIHN0eWxlcyBibGVlZGluZyBpbi5cbkBtaXhpbiBlZGl0b3ItaW5wdXQtcmVzZXQoKSB7XG5cdGZvbnQtZmFtaWx5OiB2YXJpYWJsZXMuJGVkaXRvci1odG1sLWZvbnQgIWltcG9ydGFudDtcblx0Y29sb3I6IGNvbG9ycy4kZ3JheS05MDAgIWltcG9ydGFudDtcblx0YmFja2dyb3VuZDogY29sb3JzLiR3aGl0ZSAhaW1wb3J0YW50O1xuXHRwYWRkaW5nOiB2YXJpYWJsZXMuJGdyaWQtdW5pdC0xNSAhaW1wb3J0YW50O1xuXHRib3JkZXI6IHZhcmlhYmxlcy4kYm9yZGVyLXdpZHRoIHNvbGlkIGNvbG9ycy4kZ3JheS05MDAgIWltcG9ydGFudDtcblx0Ym94LXNoYWRvdzogbm9uZSAhaW1wb3J0YW50O1xuXHRib3JkZXItcmFkaXVzOiB2YXJpYWJsZXMuJHJhZGl1cy1zbWFsbCAhaW1wb3J0YW50O1xuXG5cdC8vIEZvbnRzIHNtYWxsZXIgdGhhbiAxNnB4IGNhdXNlcyBtb2JpbGUgc2FmYXJpIHRvIHpvb20uXG5cdGZvbnQtc2l6ZTogdmFyaWFibGVzLiRtb2JpbGUtdGV4dC1taW4tZm9udC1zaXplICFpbXBvcnRhbnQ7XG5cdEBpbmNsdWRlIGJyZWFrLXNtYWxsIHtcblx0XHRmb250LXNpemU6IHZhcmlhYmxlcy4kZGVmYXVsdC1mb250LXNpemUgIWltcG9ydGFudDtcblx0fVxuXG5cdCY6Zm9jdXMge1xuXHRcdGJvcmRlci1jb2xvcjogdmFyKC0td3AtYWRtaW4tdGhlbWUtY29sb3IpICFpbXBvcnRhbnQ7XG5cdFx0Ym94LXNoYWRvdzogMCAwIDAgKHZhcmlhYmxlcy4kYm9yZGVyLXdpZHRoLWZvY3VzLWZhbGxiYWNrIC0gdmFyaWFibGVzLiRib3JkZXItd2lkdGgpIHZhcigtLXdwLWFkbWluLXRoZW1lLWNvbG9yKSAhaW1wb3J0YW50O1xuXG5cdFx0Ly8gV2luZG93cyBIaWdoIENvbnRyYXN0IG1vZGUgd2lsbCBzaG93IHRoaXMgb3V0bGluZSwgYnV0IG5vdCB0aGUgYm94LXNoYWRvdy5cblx0XHRvdXRsaW5lOiAycHggc29saWQgdHJhbnNwYXJlbnQgIWltcG9ydGFudDtcblx0fVxufVxuXG4vKipcbiAqIFJlc2V0IHRoZSBXUCBBZG1pbiBwYWdlIHN0eWxlcyBmb3IgR3V0ZW5iZXJnLWxpa2UgcGFnZXMuXG4gKi9cblxuQG1peGluIHdwLWFkbWluLXJlc2V0KCAkY29udGVudC1jb250YWluZXIgKSB7XG5cdGJhY2tncm91bmQ6IGNvbG9ycy4kd2hpdGU7XG5cblx0I3dwY29udGVudCB7XG5cdFx0cGFkZGluZy1sZWZ0OiAwO1xuXHR9XG5cblx0I3dwYm9keS1jb250ZW50IHtcblx0XHRwYWRkaW5nLWJvdHRvbTogMDtcblx0fVxuXG5cdC8qIFdlIGhpZGUgbGVnYWN5IG5vdGljZXMgaW4gR3V0ZW5iZXJnIEJhc2VkIFBhZ2VzLCBiZWNhdXNlIHRoZXkgd2VyZSBub3QgZGVzaWduZWQgaW4gYSB3YXkgdGhhdCBzY2FsZWQgd2VsbC5cblx0ICAgUGx1Z2lucyBjYW4gdXNlIEd1dGVuYmVyZyBub3RpY2VzIGlmIHRoZXkgbmVlZCB0byBwYXNzIG9uIGluZm9ybWF0aW9uIHRvIHRoZSB1c2VyIHdoZW4gdGhleSBhcmUgZWRpdGluZy4gKi9cblx0I3dwYm9keS1jb250ZW50ID4gZGl2Om5vdCgjeyAkY29udGVudC1jb250YWluZXIgfSk6bm90KCNzY3JlZW4tbWV0YSkge1xuXHRcdGRpc3BsYXk6IG5vbmU7XG5cdH1cblxuXHQjd3Bmb290ZXIge1xuXHRcdGRpc3BsYXk6IG5vbmU7XG5cdH1cblxuXHQuYTExeS1zcGVhay1yZWdpb24ge1xuXHRcdGxlZnQ6IC0xcHg7XG5cdFx0dG9wOiAtMXB4O1xuXHR9XG5cblx0dWwjYWRtaW5tZW51IGEud3AtaGFzLWN1cnJlbnQtc3VibWVudTo6YWZ0ZXIsXG5cdHVsI2FkbWlubWVudSA+IGxpLmN1cnJlbnQgPiBhLmN1cnJlbnQ6OmFmdGVyIHtcblx0XHRib3JkZXItcmlnaHQtY29sb3I6IGNvbG9ycy4kd2hpdGU7XG5cdH1cblxuXHQubWVkaWEtZnJhbWUgc2VsZWN0LmF0dGFjaG1lbnQtZmlsdGVyczpsYXN0LW9mLXR5cGUge1xuXHRcdHdpZHRoOiBhdXRvO1xuXHRcdG1heC13aWR0aDogMTAwJTtcblx0fVxufVxuXG5AbWl4aW4gYWRtaW4tc2NoZW1lKCRjb2xvci1wcmltYXJ5KSB7XG5cdC8vIERlZmluZSBSR0IgZXF1aXZhbGVudHMgZm9yIHVzZSBpbiByZ2JhIGZ1bmN0aW9uLlxuXHQvLyBIZXhhZGVjaW1hbCBjc3MgdmFycyBkbyBub3Qgd29yayBpbiB0aGUgcmdiYSBmdW5jdGlvbi5cblx0LS13cC1hZG1pbi10aGVtZS1jb2xvcjogI3skY29sb3ItcHJpbWFyeX07XG5cdC0td3AtYWRtaW4tdGhlbWUtY29sb3ItLXJnYjogI3tmdW5jdGlvbnMuaGV4LXRvLXJnYigkY29sb3ItcHJpbWFyeSl9O1xuXHQvLyBEYXJrZXIgc2hhZGVzLlxuXHQtLXdwLWFkbWluLXRoZW1lLWNvbG9yLWRhcmtlci0xMDogI3tjb2xvci5hZGp1c3QoJGNvbG9yLXByaW1hcnksICRsaWdodG5lc3M6IC01JSl9O1xuXHQtLXdwLWFkbWluLXRoZW1lLWNvbG9yLWRhcmtlci0xMC0tcmdiOiAje2Z1bmN0aW9ucy5oZXgtdG8tcmdiKGNvbG9yLmFkanVzdCgkY29sb3ItcHJpbWFyeSwgJGxpZ2h0bmVzczogLTUlKSl9O1xuXHQtLXdwLWFkbWluLXRoZW1lLWNvbG9yLWRhcmtlci0yMDogI3tjb2xvci5hZGp1c3QoJGNvbG9yLXByaW1hcnksICRsaWdodG5lc3M6IC0xMCUpfTtcblx0LS13cC1hZG1pbi10aGVtZS1jb2xvci1kYXJrZXItMjAtLXJnYjogI3tmdW5jdGlvbnMuaGV4LXRvLXJnYihjb2xvci5hZGp1c3QoJGNvbG9yLXByaW1hcnksICRsaWdodG5lc3M6IC0xMCUpKX07XG5cblx0Ly8gRm9jdXMgc3R5bGUgd2lkdGguXG5cdC8vIEF2b2lkIHJvdW5kaW5nIGlzc3VlcyBieSBzaG93aW5nIGEgd2hvbGUgMnB4IGZvciAxeCBzY3JlZW5zLCBhbmQgMS41cHggb24gaGlnaCByZXNvbHV0aW9uIHNjcmVlbnMuXG5cdC0td3AtYWRtaW4tYm9yZGVyLXdpZHRoLWZvY3VzOiAycHg7XG5cdEBtZWRpYSAoIC13ZWJraXQtbWluLWRldmljZS1waXhlbC1yYXRpbzogMiksIChtaW4tcmVzb2x1dGlvbjogMTkyZHBpKSB7XG5cdFx0LS13cC1hZG1pbi1ib3JkZXItd2lkdGgtZm9jdXM6IDEuNXB4O1xuXHR9XG59XG5cbkBtaXhpbiB3b3JkcHJlc3MtYWRtaW4tc2NoZW1lcygpIHtcblx0Ym9keS5hZG1pbi1jb2xvci1saWdodCB7XG5cdFx0QGluY2x1ZGUgYWRtaW4tc2NoZW1lKCMwMDg1YmEpO1xuXHR9XG5cblx0Ym9keS5hZG1pbi1jb2xvci1tb2Rlcm4ge1xuXHRcdEBpbmNsdWRlIGFkbWluLXNjaGVtZSgjMzg1OGU5KTtcblx0fVxuXG5cdGJvZHkuYWRtaW4tY29sb3ItYmx1ZSB7XG5cdFx0QGluY2x1ZGUgYWRtaW4tc2NoZW1lKCMwOTY0ODQpO1xuXHR9XG5cblx0Ym9keS5hZG1pbi1jb2xvci1jb2ZmZWUge1xuXHRcdEBpbmNsdWRlIGFkbWluLXNjaGVtZSgjNDY0MDNjKTtcblx0fVxuXG5cdGJvZHkuYWRtaW4tY29sb3ItZWN0b3BsYXNtIHtcblx0XHRAaW5jbHVkZSBhZG1pbi1zY2hlbWUoIzUyM2Y2ZCk7XG5cdH1cblxuXHRib2R5LmFkbWluLWNvbG9yLW1pZG5pZ2h0IHtcblx0XHRAaW5jbHVkZSBhZG1pbi1zY2hlbWUoI2UxNGQ0Myk7XG5cdH1cblxuXHRib2R5LmFkbWluLWNvbG9yLW9jZWFuIHtcblx0XHRAaW5jbHVkZSBhZG1pbi1zY2hlbWUoIzYyN2M4Myk7XG5cdH1cblxuXHRib2R5LmFkbWluLWNvbG9yLXN1bnJpc2Uge1xuXHRcdEBpbmNsdWRlIGFkbWluLXNjaGVtZSgjZGQ4MjNiKTtcblx0fVxufVxuXG4vLyBEZXByZWNhdGVkIGZyb20gVUksIGtlcHQgZm9yIGJhY2stY29tcGF0LlxuQG1peGluIGJhY2tncm91bmQtY29sb3JzLWRlcHJlY2F0ZWQoKSB7XG5cdC5oYXMtdmVyeS1saWdodC1ncmF5LWJhY2tncm91bmQtY29sb3Ige1xuXHRcdGJhY2tncm91bmQtY29sb3I6ICNlZWU7XG5cdH1cblxuXHQuaGFzLXZlcnktZGFyay1ncmF5LWJhY2tncm91bmQtY29sb3Ige1xuXHRcdGJhY2tncm91bmQtY29sb3I6ICMzMTMxMzE7XG5cdH1cbn1cblxuLy8gRGVwcmVjYXRlZCBmcm9tIFVJLCBrZXB0IGZvciBiYWNrLWNvbXBhdC5cbkBtaXhpbiBmb3JlZ3JvdW5kLWNvbG9ycy1kZXByZWNhdGVkKCkge1xuXHQuaGFzLXZlcnktbGlnaHQtZ3JheS1jb2xvciB7XG5cdFx0Y29sb3I6ICNlZWU7XG5cdH1cblxuXHQuaGFzLXZlcnktZGFyay1ncmF5LWNvbG9yIHtcblx0XHRjb2xvcjogIzMxMzEzMTtcblx0fVxufVxuXG4vLyBEZXByZWNhdGVkIGZyb20gVUksIGtlcHQgZm9yIGJhY2stY29tcGF0LlxuQG1peGluIGdyYWRpZW50LWNvbG9ycy1kZXByZWNhdGVkKCkge1xuXHQvLyBPdXIgY2xhc3NlcyB1c2VzIHRoZSBzYW1lIHZhbHVlcyB3ZSBzZXQgZm9yIGdyYWRpZW50IHZhbHVlIGF0dHJpYnV0ZXMuXG5cblx0Lyogc3R5bGVsaW50LWRpc2FibGUgQHN0eWxpc3RpYy9mdW5jdGlvbi1jb21tYS1zcGFjZS1hZnRlciAtLSBXZSBjYW4gbm90IHVzZSBzcGFjaW5nIGJlY2F1c2Ugb2YgV1AgbXVsdGkgc2l0ZSBrc2VzIHJ1bGUuICovXG5cdC5oYXMtdml2aWQtZ3JlZW4tY3lhbi10by12aXZpZC1jeWFuLWJsdWUtZ3JhZGllbnQtYmFja2dyb3VuZCB7XG5cdFx0YmFja2dyb3VuZDogbGluZWFyLWdyYWRpZW50KDEzNWRlZyxyZ2JhKDAsMjA4LDEzMiwxKSAwJSxyZ2JhKDYsMTQ3LDIyNywxKSAxMDAlKTtcblx0fVxuXG5cdC5oYXMtcHVycGxlLWNydXNoLWdyYWRpZW50LWJhY2tncm91bmQge1xuXHRcdGJhY2tncm91bmQ6IGxpbmVhci1ncmFkaWVudCgxMzVkZWcscmdiKDUyLDIyNiwyMjgpIDAlLHJnYig3MSwzMywyNTEpIDUwJSxyZ2IoMTcxLDI5LDI1NCkgMTAwJSk7XG5cdH1cblxuXHQuaGFzLWhhenktZGF3bi1ncmFkaWVudC1iYWNrZ3JvdW5kIHtcblx0XHRiYWNrZ3JvdW5kOiBsaW5lYXItZ3JhZGllbnQoMTM1ZGVnLHJnYigyNTAsMTcyLDE2OCkgMCUscmdiKDIxOCwyMDgsMjM2KSAxMDAlKTtcblx0fVxuXG5cdC5oYXMtc3ViZHVlZC1vbGl2ZS1ncmFkaWVudC1iYWNrZ3JvdW5kIHtcblx0XHRiYWNrZ3JvdW5kOiBsaW5lYXItZ3JhZGllbnQoMTM1ZGVnLHJnYigyNTAsMjUwLDIyNSkgMCUscmdiKDEwMywxNjYsMTEzKSAxMDAlKTtcblx0fVxuXG5cdC5oYXMtYXRvbWljLWNyZWFtLWdyYWRpZW50LWJhY2tncm91bmQge1xuXHRcdGJhY2tncm91bmQ6IGxpbmVhci1ncmFkaWVudCgxMzVkZWcscmdiKDI1MywyMTUsMTU0KSAwJSxyZ2IoMCw3NCw4OSkgMTAwJSk7XG5cdH1cblxuXHQuaGFzLW5pZ2h0c2hhZGUtZ3JhZGllbnQtYmFja2dyb3VuZCB7XG5cdFx0YmFja2dyb3VuZDogbGluZWFyLWdyYWRpZW50KDEzNWRlZyxyZ2IoNTEsOSwxMDQpIDAlLHJnYig0OSwyMDUsMjA3KSAxMDAlKTtcblx0fVxuXG5cdC5oYXMtbWlkbmlnaHQtZ3JhZGllbnQtYmFja2dyb3VuZCB7XG5cdFx0YmFja2dyb3VuZDogbGluZWFyLWdyYWRpZW50KDEzNWRlZyxyZ2IoMiwzLDEyOSkgMCUscmdiKDQwLDExNiwyNTIpIDEwMCUpO1xuXHR9XG5cdC8qIHN0eWxlbGludC1lbmFibGUgQHN0eWxpc3RpYy9mdW5jdGlvbi1jb21tYS1zcGFjZS1hZnRlciAqL1xufVxuXG5AbWl4aW4gY3VzdG9tLXNjcm9sbGJhcnMtb24taG92ZXIoJGhhbmRsZS1jb2xvciwgJGhhbmRsZS1jb2xvci1ob3Zlcikge1xuXG5cdC8vIFdlYktpdFxuXHQmOjotd2Via2l0LXNjcm9sbGJhciB7XG5cdFx0d2lkdGg6IDEycHg7XG5cdFx0aGVpZ2h0OiAxMnB4O1xuXHR9XG5cdCY6Oi13ZWJraXQtc2Nyb2xsYmFyLXRyYWNrIHtcblx0XHRiYWNrZ3JvdW5kLWNvbG9yOiB0cmFuc3BhcmVudDtcblx0fVxuXHQmOjotd2Via2l0LXNjcm9sbGJhci10aHVtYiB7XG5cdFx0YmFja2dyb3VuZC1jb2xvcjogJGhhbmRsZS1jb2xvcjtcblx0XHRib3JkZXItcmFkaXVzOiA4cHg7XG5cdFx0Ym9yZGVyOiAzcHggc29saWQgdHJhbnNwYXJlbnQ7XG5cdFx0YmFja2dyb3VuZC1jbGlwOiBwYWRkaW5nLWJveDtcblx0fVxuXHQmOmhvdmVyOjotd2Via2l0LXNjcm9sbGJhci10aHVtYiwgLy8gVGhpcyBuZWVkcyBzcGVjaWZpY2l0eS5cblx0Jjpmb2N1czo6LXdlYmtpdC1zY3JvbGxiYXItdGh1bWIsXG5cdCY6Zm9jdXMtd2l0aGluOjotd2Via2l0LXNjcm9sbGJhci10aHVtYiB7XG5cdFx0YmFja2dyb3VuZC1jb2xvcjogJGhhbmRsZS1jb2xvci1ob3Zlcjtcblx0fVxuXG5cdC8vIEZpcmVmb3ggMTA5KyBhbmQgQ2hyb21lIDExMStcblx0c2Nyb2xsYmFyLXdpZHRoOiB0aGluO1xuXHRzY3JvbGxiYXItZ3V0dGVyOiBzdGFibGUgYm90aC1lZGdlcztcblx0c2Nyb2xsYmFyLWNvbG9yOiAkaGFuZGxlLWNvbG9yIHRyYW5zcGFyZW50OyAvLyBTeW50YXgsIFwiZGFya1wiLCBcImxpZ2h0XCIsIG9yIFwiI2hhbmRsZS1jb2xvciAjdHJhY2stY29sb3JcIlxuXG5cdCY6aG92ZXIsXG5cdCY6Zm9jdXMsXG5cdCY6Zm9jdXMtd2l0aGluIHtcblx0XHRzY3JvbGxiYXItY29sb3I6ICRoYW5kbGUtY29sb3ItaG92ZXIgdHJhbnNwYXJlbnQ7XG5cdH1cblxuXHQvLyBOZWVkZWQgdG8gZml4IGEgU2FmYXJpIHJlbmRlcmluZyBpc3N1ZS5cblx0d2lsbC1jaGFuZ2U6IHRyYW5zZm9ybTtcblxuXHQvLyBBbHdheXMgc2hvdyBzY3JvbGxiYXIgb24gTW9iaWxlIGRldmljZXMuXG5cdEBtZWRpYSAoaG92ZXI6IG5vbmUpIHtcblx0XHQmIHtcblx0XHRcdHNjcm9sbGJhci1jb2xvcjogJGhhbmRsZS1jb2xvci1ob3ZlciB0cmFuc3BhcmVudDtcblx0XHR9XG5cdH1cbn1cblxuQG1peGluIHNlbGVjdGVkLWJsb2NrLW91dGxpbmUoJHdpZHRoUmF0aW86IDEpIHtcblx0b3V0bGluZS1jb2xvcjogdmFyKC0td3AtYWRtaW4tdGhlbWUtY29sb3IpO1xuXHRvdXRsaW5lLXN0eWxlOiBzb2xpZDtcblx0b3V0bGluZS13aWR0aDogY2FsYygjeyR3aWR0aFJhdGlvfSAqICh2YXIoLS13cC1hZG1pbi1ib3JkZXItd2lkdGgtZm9jdXMpIC8gdmFyKC0td3AtYmxvY2stZWRpdG9yLWlmcmFtZS16b29tLW91dC1zY2FsZSwgMSkpKTtcblx0b3V0bGluZS1vZmZzZXQ6IGNhbGMoI3skd2lkdGhSYXRpb30gKiAoKC0xICogdmFyKC0td3AtYWRtaW4tYm9yZGVyLXdpZHRoLWZvY3VzKSApIC8gdmFyKC0td3AtYmxvY2stZWRpdG9yLWlmcmFtZS16b29tLW91dC1zY2FsZSwgMSkpKTtcbn1cblxuQG1peGluIHNlbGVjdGVkLWJsb2NrLWZvY3VzKCR3aWR0aFJhdGlvOiAxKSB7XG5cdGNvbnRlbnQ6IFwiXCI7XG5cdHBvc2l0aW9uOiBhYnNvbHV0ZTtcblx0cG9pbnRlci1ldmVudHM6IG5vbmU7XG5cdHRvcDogMDtcblx0cmlnaHQ6IDA7XG5cdGJvdHRvbTogMDtcblx0bGVmdDogMDtcblx0QGluY2x1ZGUgc2VsZWN0ZWQtYmxvY2stb3V0bGluZSgkd2lkdGhSYXRpbyk7XG59XG5cbi8qKlxuICogQ3JlYXRlcyBhIGNoZWNrZXJib2FyZCBwYXR0ZXJuIGJhY2tncm91bmQgdG8gaW5kaWNhdGUgdHJhbnNwYXJlbmN5LlxuICogQHBhcmFtIHtTdHJpbmd9ICRzaXplIC0gVGhlIHNpemUgb2YgdGhlIHNxdWFyZXMgaW4gdGhlIGNoZWNrZXJib2FyZCBwYXR0ZXJuLiBEZWZhdWx0IGlzIDEycHguXG4gKi9cbkBtaXhpbiBjaGVja2VyYm9hcmQtYmFja2dyb3VuZCgkc2l6ZTogMTJweCkge1xuXHQvLyBUaGUgYmFja2dyb3VuZCBpbWFnZSBjcmVhdGVzIGEgY2hlY2tlcmJvYXJkIHBhdHRlcm4uIElnbm9yZSBydGxjc3MgdG9cblx0Ly8gbWFrZSBpdCB3b3JrIGJvdGggaW4gTFRSIGFuZCBSVEwuXG5cdC8vIFNlZSBodHRwczovL2dpdGh1Yi5jb20vV29yZFByZXNzL2d1dGVuYmVyZy9wdWxsLzQyNTEwXG5cdC8qcnRsOmJlZ2luOmlnbm9yZSovXG5cdGJhY2tncm91bmQtaW1hZ2U6XG5cdFx0cmVwZWF0aW5nLWxpbmVhci1ncmFkaWVudCg0NWRlZywgY29sb3JzLiRncmF5LTIwMCAyNSUsIHRyYW5zcGFyZW50IDI1JSwgdHJhbnNwYXJlbnQgNzUlLCBjb2xvcnMuJGdyYXktMjAwIDc1JSwgY29sb3JzLiRncmF5LTIwMCksXG5cdFx0cmVwZWF0aW5nLWxpbmVhci1ncmFkaWVudCg0NWRlZywgY29sb3JzLiRncmF5LTIwMCAyNSUsIHRyYW5zcGFyZW50IDI1JSwgdHJhbnNwYXJlbnQgNzUlLCBjb2xvcnMuJGdyYXktMjAwIDc1JSwgY29sb3JzLiRncmF5LTIwMCk7XG5cdGJhY2tncm91bmQtcG9zaXRpb246IDAgMCwgJHNpemUgJHNpemU7XG5cdC8qcnRsOmVuZDppZ25vcmUqL1xuXHRiYWNrZ3JvdW5kLXNpemU6IGNhbGMoMiAqICRzaXplKSBjYWxjKDIgKiAkc2l6ZSk7XG59XG4iLCIvKipcbiAqIFNDU1MgVmFyaWFibGVzLlxuICpcbiAqIFBsZWFzZSB1c2UgdmFyaWFibGVzIGZyb20gdGhpcyBzaGVldCB0byBlbnN1cmUgY29uc2lzdGVuY3kgYWNyb3NzIHRoZSBVSS5cbiAqIERvbid0IGFkZCB0byB0aGlzIHNoZWV0IHVubGVzcyB5b3UncmUgcHJldHR5IHN1cmUgdGhlIHZhbHVlIHdpbGwgYmUgcmV1c2VkIGluIG1hbnkgcGxhY2VzLlxuICogRm9yIGV4YW1wbGUsIGRvbid0IGFkZCBydWxlcyB0byB0aGlzIHNoZWV0IHRoYXQgYWZmZWN0IGJsb2NrIHZpc3VhbHMuIEl0J3MgcHVyZWx5IGZvciBVSS5cbiAqL1xuXG5AdXNlIFwiLi9jb2xvcnNcIjtcblxuLyoqXG4gKiBGb250cyAmIGJhc2ljIHZhcmlhYmxlcy5cbiAqL1xuXG4kZGVmYXVsdC1mb250OiAtYXBwbGUtc3lzdGVtLCBCbGlua01hY1N5c3RlbUZvbnQsXCJTZWdvZSBVSVwiLCBSb2JvdG8sIE94eWdlbi1TYW5zLCBVYnVudHUsIENhbnRhcmVsbCxcIkhlbHZldGljYSBOZXVlXCIsIHNhbnMtc2VyaWY7IC8vIFRvZG86IGRlcHJlY2F0ZSBpbiBmYXZvciBvZiAkZmFtaWx5IHZhcmlhYmxlc1xuJGRlZmF1bHQtbGluZS1oZWlnaHQ6IDEuNDsgLy8gVG9kbzogZGVwcmVjYXRlIGluIGZhdm9yIG9mICRsaW5lLWhlaWdodCB0b2tlbnNcblxuLyoqXG4gKiBUeXBvZ3JhcGh5XG4gKi9cblxuLy8gU2l6ZXNcbiRmb250LXNpemUteC1zbWFsbDogMTFweDtcbiRmb250LXNpemUtc21hbGw6IDEycHg7XG4kZm9udC1zaXplLW1lZGl1bTogMTNweDtcbiRmb250LXNpemUtbGFyZ2U6IDE1cHg7XG4kZm9udC1zaXplLXgtbGFyZ2U6IDIwcHg7XG4kZm9udC1zaXplLTJ4LWxhcmdlOiAzMnB4O1xuXG4vLyBMaW5lIGhlaWdodHNcbiRmb250LWxpbmUtaGVpZ2h0LXgtc21hbGw6IDE2cHg7XG4kZm9udC1saW5lLWhlaWdodC1zbWFsbDogMjBweDtcbiRmb250LWxpbmUtaGVpZ2h0LW1lZGl1bTogMjRweDtcbiRmb250LWxpbmUtaGVpZ2h0LWxhcmdlOiAyOHB4O1xuJGZvbnQtbGluZS1oZWlnaHQteC1sYXJnZTogMzJweDtcbiRmb250LWxpbmUtaGVpZ2h0LTJ4LWxhcmdlOiA0MHB4O1xuXG4vLyBXZWlnaHRzXG4kZm9udC13ZWlnaHQtcmVndWxhcjogNDAwO1xuJGZvbnQtd2VpZ2h0LW1lZGl1bTogNDk5OyAvLyBlbnN1cmVzIGZhbGxiYWNrIHRvIDQwMCAoaW5zdGVhZCBvZiA2MDApXG5cbi8vIEZhbWlsaWVzXG4kZm9udC1mYW1pbHktaGVhZGluZ3M6IC1hcHBsZS1zeXN0ZW0sIFwic3lzdGVtLXVpXCIsIFwiU2Vnb2UgVUlcIiwgUm9ib3RvLCBPeHlnZW4tU2FucywgVWJ1bnR1LCBDYW50YXJlbGwsIFwiSGVsdmV0aWNhIE5ldWVcIiwgc2Fucy1zZXJpZjtcbiRmb250LWZhbWlseS1ib2R5OiAtYXBwbGUtc3lzdGVtLCBcInN5c3RlbS11aVwiLCBcIlNlZ29lIFVJXCIsIFJvYm90bywgT3h5Z2VuLVNhbnMsIFVidW50dSwgQ2FudGFyZWxsLCBcIkhlbHZldGljYSBOZXVlXCIsIHNhbnMtc2VyaWY7XG4kZm9udC1mYW1pbHktbW9ubzogTWVubG8sIENvbnNvbGFzLCBtb25hY28sIG1vbm9zcGFjZTtcblxuLyoqXG4gKiBHcmlkIFN5c3RlbS5cbiAqIGh0dHBzOi8vbWFrZS53b3JkcHJlc3Mub3JnL2Rlc2lnbi8yMDE5LzEwLzMxL3Byb3Bvc2FsLWEtY29uc2lzdGVudC1zcGFjaW5nLXN5c3RlbS1mb3Itd29yZHByZXNzL1xuICovXG5cbiRncmlkLXVuaXQ6IDhweDtcbiRncmlkLXVuaXQtMDU6IDAuNSAqICRncmlkLXVuaXQ7XHQvLyA0cHhcbiRncmlkLXVuaXQtMTA6IDEgKiAkZ3JpZC11bml0O1x0XHQvLyA4cHhcbiRncmlkLXVuaXQtMTU6IDEuNSAqICRncmlkLXVuaXQ7XHQvLyAxMnB4XG4kZ3JpZC11bml0LTIwOiAyICogJGdyaWQtdW5pdDtcdFx0Ly8gMTZweFxuJGdyaWQtdW5pdC0zMDogMyAqICRncmlkLXVuaXQ7XHRcdC8vIDI0cHhcbiRncmlkLXVuaXQtNDA6IDQgKiAkZ3JpZC11bml0O1x0XHQvLyAzMnB4XG4kZ3JpZC11bml0LTUwOiA1ICogJGdyaWQtdW5pdDtcdFx0Ly8gNDBweFxuJGdyaWQtdW5pdC02MDogNiAqICRncmlkLXVuaXQ7XHRcdC8vIDQ4cHhcbiRncmlkLXVuaXQtNzA6IDcgKiAkZ3JpZC11bml0O1x0XHQvLyA1NnB4XG4kZ3JpZC11bml0LTgwOiA4ICogJGdyaWQtdW5pdDtcdFx0Ly8gNjRweFxuXG4vKipcbiAqIFJhZGl1cyBzY2FsZS5cbiAqL1xuXG4kcmFkaXVzLXgtc21hbGw6IDFweDsgICAvLyBBcHBsaWVkIHRvIGVsZW1lbnRzIGxpa2UgYnV0dG9ucyBuZXN0ZWQgd2l0aGluIHByaW1pdGl2ZXMgbGlrZSBpbnB1dHMuXG4kcmFkaXVzLXNtYWxsOiAycHg7ICAgICAvLyBBcHBsaWVkIHRvIG1vc3QgcHJpbWl0aXZlcy5cbiRyYWRpdXMtbWVkaXVtOiA0cHg7ICAgIC8vIEFwcGxpZWQgdG8gY29udGFpbmVycyB3aXRoIHNtYWxsZXIgcGFkZGluZy5cbiRyYWRpdXMtbGFyZ2U6IDhweDsgICAgIC8vIEFwcGxpZWQgdG8gY29udGFpbmVycyB3aXRoIGxhcmdlciBwYWRkaW5nLlxuJHJhZGl1cy1mdWxsOiA5OTk5cHg7ICAgLy8gRm9yIHBpbGxzLlxuJHJhZGl1cy1yb3VuZDogNTAlOyAgICAgLy8gRm9yIGNpcmNsZXMgYW5kIG92YWxzLlxuXG4vKipcbiAqIEVsZXZhdGlvbiBzY2FsZS5cbiAqL1xuXG4vLyBGb3Igc2VjdGlvbnMgYW5kIGNvbnRhaW5lcnMgdGhhdCBncm91cCByZWxhdGVkIGNvbnRlbnQgYW5kIGNvbnRyb2xzLCB3aGljaCBtYXkgb3ZlcmxhcCBvdGhlciBjb250ZW50LiBFeGFtcGxlOiBQcmV2aWV3IEZyYW1lLlxuJGVsZXZhdGlvbi14LXNtYWxsOiAwIDFweCAxcHggcmdiYShjb2xvcnMuJGJsYWNrLCAwLjAzKSwgMCAxcHggMnB4IHJnYmEoY29sb3JzLiRibGFjaywgMC4wMiksIDAgM3B4IDNweCByZ2JhKGNvbG9ycy4kYmxhY2ssIDAuMDIpLCAwIDRweCA0cHggcmdiYShjb2xvcnMuJGJsYWNrLCAwLjAxKTtcblxuLy8gRm9yIGNvbXBvbmVudHMgdGhhdCBwcm92aWRlIGNvbnRleHR1YWwgZmVlZGJhY2sgd2l0aG91dCBiZWluZyBpbnRydXNpdmUuIEdlbmVyYWxseSBub24taW50ZXJydXB0aXZlLiBFeGFtcGxlOiBUb29sdGlwcywgU25hY2tiYXIuXG4kZWxldmF0aW9uLXNtYWxsOiAwIDFweCAycHggcmdiYShjb2xvcnMuJGJsYWNrLCAwLjA1KSwgMCAycHggM3B4IHJnYmEoY29sb3JzLiRibGFjaywgMC4wNCksIDAgNnB4IDZweCByZ2JhKGNvbG9ycy4kYmxhY2ssIDAuMDMpLCAwIDhweCA4cHggcmdiYShjb2xvcnMuJGJsYWNrLCAwLjAyKTtcblxuLy8gRm9yIGNvbXBvbmVudHMgdGhhdCBvZmZlciBhZGRpdGlvbmFsIGFjdGlvbnMuIEV4YW1wbGU6IE1lbnVzLCBDb21tYW5kIFBhbGV0dGVcbiRlbGV2YXRpb24tbWVkaXVtOiAwIDJweCAzcHggcmdiYShjb2xvcnMuJGJsYWNrLCAwLjA1KSwgMCA0cHggNXB4IHJnYmEoY29sb3JzLiRibGFjaywgMC4wNCksIDAgMTJweCAxMnB4IHJnYmEoY29sb3JzLiRibGFjaywgMC4wMyksIDAgMTZweCAxNnB4IHJnYmEoY29sb3JzLiRibGFjaywgMC4wMik7XG5cbi8vIEZvciBjb21wb25lbnRzIHRoYXQgY29uZmlybSBkZWNpc2lvbnMgb3IgaGFuZGxlIG5lY2Vzc2FyeSBpbnRlcnJ1cHRpb25zLiBFeGFtcGxlOiBNb2RhbHMuXG4kZWxldmF0aW9uLWxhcmdlOiAwIDVweCAxNXB4IHJnYmEoY29sb3JzLiRibGFjaywgMC4wOCksIDAgMTVweCAyN3B4IHJnYmEoY29sb3JzLiRibGFjaywgMC4wNyksIDAgMzBweCAzNnB4IHJnYmEoY29sb3JzLiRibGFjaywgMC4wNCksIDAgNTBweCA0M3B4IHJnYmEoY29sb3JzLiRibGFjaywgMC4wMik7XG5cbi8qKlxuICogRGltZW5zaW9ucy5cbiAqL1xuXG4kaWNvbi1zaXplOiAyNHB4O1xuJGJ1dHRvbi1zaXplOiAzNnB4O1xuJGJ1dHRvbi1zaXplLW5leHQtZGVmYXVsdC00MHB4OiA0MHB4OyAvLyB0cmFuc2l0aW9uYXJ5IHZhcmlhYmxlIGZvciBuZXh0IGRlZmF1bHQgYnV0dG9uIHNpemVcbiRidXR0b24tc2l6ZS1zbWFsbDogMjRweDtcbiRidXR0b24tc2l6ZS1jb21wYWN0OiAzMnB4O1xuJGhlYWRlci1oZWlnaHQ6IDY0cHg7XG4kcGFuZWwtaGVhZGVyLWhlaWdodDogJGdyaWQtdW5pdC02MDtcbiRuYXYtc2lkZWJhci13aWR0aDogMzAwcHg7XG4kYWRtaW4tYmFyLWhlaWdodDogMzJweDtcbiRhZG1pbi1iYXItaGVpZ2h0LWJpZzogNDZweDtcbiRhZG1pbi1zaWRlYmFyLXdpZHRoOiAxNjBweDtcbiRhZG1pbi1zaWRlYmFyLXdpZHRoLWJpZzogMTkwcHg7XG4kYWRtaW4tc2lkZWJhci13aWR0aC1jb2xsYXBzZWQ6IDM2cHg7XG4kbW9kYWwtbWluLXdpZHRoOiAzNTBweDtcbiRtb2RhbC13aWR0aC1zbWFsbDogMzg0cHg7XG4kbW9kYWwtd2lkdGgtbWVkaXVtOiA1MTJweDtcbiRtb2RhbC13aWR0aC1sYXJnZTogODQwcHg7XG4kc3Bpbm5lci1zaXplOiAxNnB4O1xuJGNhbnZhcy1wYWRkaW5nOiAkZ3JpZC11bml0LTIwO1xuJHBhbGV0dGUtbWF4LWhlaWdodDogMzY4cHg7XG5cbi8qKlxuICogTW9iaWxlIHNwZWNpZmljIHN0eWxlc1xuICovXG4kbW9iaWxlLXRleHQtbWluLWZvbnQtc2l6ZTogMTZweDsgLy8gQW55IGZvbnQgc2l6ZSBiZWxvdyAxNnB4IHdpbGwgY2F1c2UgTW9iaWxlIFNhZmFyaSB0byBcInpvb20gaW5cIi5cblxuLyoqXG4gKiBFZGl0b3Igc3R5bGVzLlxuICovXG5cbiRzaWRlYmFyLXdpZHRoOiAyODBweDtcbiRjb250ZW50LXdpZHRoOiA4NDBweDtcbiR3aWRlLWNvbnRlbnQtd2lkdGg6IDExMDBweDtcbiR3aWRnZXQtYXJlYS13aWR0aDogNzAwcHg7XG4kc2Vjb25kYXJ5LXNpZGViYXItd2lkdGg6IDM1MHB4O1xuJGVkaXRvci1mb250LXNpemU6IDE2cHg7XG4kZGVmYXVsdC1ibG9jay1tYXJnaW46IDI4cHg7IC8vIFRoaXMgdmFsdWUgcHJvdmlkZXMgYSBjb25zaXN0ZW50LCBjb250aWd1b3VzIHNwYWNpbmcgYmV0d2VlbiBibG9ja3MuXG4kdGV4dC1lZGl0b3ItZm9udC1zaXplOiAxNXB4O1xuJGVkaXRvci1saW5lLWhlaWdodDogMS44O1xuJGVkaXRvci1odG1sLWZvbnQ6ICRmb250LWZhbWlseS1tb25vO1xuXG4vKipcbiAqIEJsb2NrICYgRWRpdG9yIFVJLlxuICovXG5cbiRibG9jay10b29sYmFyLWhlaWdodDogJGdyaWQtdW5pdC02MDtcbiRib3JkZXItd2lkdGg6IDFweDtcbiRib3JkZXItd2lkdGgtZm9jdXMtZmFsbGJhY2s6IDJweDsgLy8gVGhpcyBleGlzdHMgYXMgYSBmYWxsYmFjaywgYW5kIGlzIGlkZWFsbHkgb3ZlcnJpZGRlbiBieSB2YXIoLS13cC1hZG1pbi1ib3JkZXItd2lkdGgtZm9jdXMpIHVubGVzcyBpbiBzb21lIFNBU1MgbWF0aCBjYXNlcy5cbiRib3JkZXItd2lkdGgtdGFiOiAxLjVweDtcbiRoZWxwdGV4dC1mb250LXNpemU6IDEycHg7XG4kcmFkaW8taW5wdXQtc2l6ZTogMTZweDtcbiRyYWRpby1pbnB1dC1zaXplLXNtOiAyNHB4OyAvLyBXaWR0aCAmIGhlaWdodCBmb3Igc21hbGwgdmlld3BvcnRzLlxuXG4vLyBEZXByZWNhdGVkLCBwbGVhc2UgYXZvaWQgdXNpbmcgdGhlc2UuXG4kYmxvY2stcGFkZGluZzogMTRweDsgLy8gVXNlZCB0byBkZWZpbmUgc3BhY2UgYmV0d2VlbiBibG9jayBmb290cHJpbnQgYW5kIHN1cnJvdW5kaW5nIGJvcmRlcnMuXG4kcmFkaXVzLWJsb2NrLXVpOiAkcmFkaXVzLXNtYWxsO1xuJHNoYWRvdy1wb3BvdmVyOiAkZWxldmF0aW9uLXgtc21hbGw7XG4kc2hhZG93LW1vZGFsOiAkZWxldmF0aW9uLWxhcmdlO1xuJGRlZmF1bHQtZm9udC1zaXplOiAkZm9udC1zaXplLW1lZGl1bTtcblxuLyoqXG4gKiBCbG9jayBwYWRkaW5ncy5cbiAqL1xuXG4vLyBQYWRkaW5nIGZvciBibG9ja3Mgd2l0aCBhIGJhY2tncm91bmQgY29sb3IgKGUuZy4gcGFyYWdyYXBoIG9yIGdyb3VwKS5cbiRibG9jay1iZy1wYWRkaW5nLS12OiAxLjI1ZW07XG4kYmxvY2stYmctcGFkZGluZy0taDogMi4zNzVlbTtcblxuXG4vKipcbiAqIFJlYWN0IE5hdGl2ZSBzcGVjaWZpYy5cbiAqIFRoZXNlIHZhcmlhYmxlcyBkbyBub3QgYXBwZWFyIHRvIGJlIHVzZWQgYW55d2hlcmUgZWxzZS5cbiAqL1xuXG4vLyBEaW1lbnNpb25zLlxuJG1vYmlsZS1oZWFkZXItdG9vbGJhci1oZWlnaHQ6IDQ0cHg7XG4kbW9iaWxlLWhlYWRlci10b29sYmFyLWV4cGFuZGVkLWhlaWdodDogNTJweDtcbiRtb2JpbGUtZmxvYXRpbmctdG9vbGJhci1oZWlnaHQ6IDQ0cHg7XG4kbW9iaWxlLWZsb2F0aW5nLXRvb2xiYXItbWFyZ2luOiA4cHg7XG4kbW9iaWxlLWNvbG9yLXN3YXRjaDogNDhweDtcblxuLy8gQmxvY2sgVUkuXG4kbW9iaWxlLWJsb2NrLXRvb2xiYXItaGVpZ2h0OiA0NHB4O1xuJGRpbW1lZC1vcGFjaXR5OiAxO1xuJGJsb2NrLWVkZ2UtdG8tY29udGVudDogMTZweDtcbiRzb2xpZC1ib3JkZXItc3BhY2U6IDEycHg7XG4kZGFzaGVkLWJvcmRlci1zcGFjZTogNnB4O1xuJGJsb2NrLXNlbGVjdGVkLW1hcmdpbjogM3B4O1xuJGJsb2NrLXNlbGVjdGVkLWJvcmRlci13aWR0aDogMXB4O1xuJGJsb2NrLXNlbGVjdGVkLXBhZGRpbmc6IDA7XG4kYmxvY2stc2VsZWN0ZWQtY2hpbGQtbWFyZ2luOiA1cHg7XG4kYmxvY2stc2VsZWN0ZWQtdG8tY29udGVudDogJGJsb2NrLWVkZ2UtdG8tY29udGVudCAtICRibG9jay1zZWxlY3RlZC1tYXJnaW4gLSAkYmxvY2stc2VsZWN0ZWQtYm9yZGVyLXdpZHRoO1xuIiwiLyoqXG4gKiBDb2xvcnNcbiAqL1xuXG4vLyBXb3JkUHJlc3MgZ3JheXMuXG4kYmxhY2s6ICMwMDA7XHRcdFx0Ly8gVXNlIG9ubHkgd2hlbiB5b3UgdHJ1bHkgbmVlZCBwdXJlIGJsYWNrLiBGb3IgVUksIHVzZSAkZ3JheS05MDAuXG4kZ3JheS05MDA6ICMxZTFlMWU7XG4kZ3JheS04MDA6ICMyZjJmMmY7XG4kZ3JheS03MDA6ICM3NTc1NzU7XHRcdC8vIE1lZXRzIDQuNjoxICg0LjU6MSBpcyBtaW5pbXVtKSB0ZXh0IGNvbnRyYXN0IGFnYWluc3Qgd2hpdGUuXG4kZ3JheS02MDA6ICM5NDk0OTQ7XHRcdC8vIE1lZXRzIDM6MSBVSSBvciBsYXJnZSB0ZXh0IGNvbnRyYXN0IGFnYWluc3Qgd2hpdGUuXG4kZ3JheS00MDA6ICNjY2M7XG4kZ3JheS0zMDA6ICNkZGQ7XHRcdC8vIFVzZWQgZm9yIG1vc3QgYm9yZGVycy5cbiRncmF5LTIwMDogI2UwZTBlMDtcdFx0Ly8gVXNlZCBzcGFyaW5nbHkgZm9yIGxpZ2h0IGJvcmRlcnMuXG4kZ3JheS0xMDA6ICNmMGYwZjA7XHRcdC8vIFVzZWQgZm9yIGxpZ2h0IGdyYXkgYmFja2dyb3VuZHMuXG4kd2hpdGU6ICNmZmY7XG5cbi8vIE9wYWNpdGllcyAmIGFkZGl0aW9uYWwgY29sb3JzLlxuJGRhcmstZ3JheS1wbGFjZWhvbGRlcjogcmdiYSgkZ3JheS05MDAsIDAuNjIpO1xuJG1lZGl1bS1ncmF5LXBsYWNlaG9sZGVyOiByZ2JhKCRncmF5LTkwMCwgMC41NSk7XG4kbGlnaHQtZ3JheS1wbGFjZWhvbGRlcjogcmdiYSgkd2hpdGUsIDAuNjUpO1xuXG4vLyBBbGVydCBjb2xvcnMuXG4kYWxlcnQteWVsbG93OiAjZjBiODQ5O1xuJGFsZXJ0LXJlZDogI2NjMTgxODtcbiRhbGVydC1ncmVlbjogIzRhYjg2NjtcblxuLy8gRGVwcmVjYXRlZCwgcGxlYXNlIGF2b2lkIHVzaW5nIHRoZXNlLlxuJGRhcmstdGhlbWUtZm9jdXM6ICR3aGl0ZTtcdC8vIEZvY3VzIGNvbG9yIHdoZW4gdGhlIHRoZW1lIGlzIGRhcmsuXG4iLCIvKipcbiAqIEJyZWFrcG9pbnRzICYgTWVkaWEgUXVlcmllc1xuICovXG5cbi8vIE1vc3QgdXNlZCBicmVha3BvaW50c1xuJGJyZWFrLXhodWdlOiAxOTIwcHg7XG4kYnJlYWstaHVnZTogMTQ0MHB4O1xuJGJyZWFrLXdpZGU6IDEyODBweDtcbiRicmVhay14bGFyZ2U6IDEwODBweDtcbiRicmVhay1sYXJnZTogOTYwcHg7XHQvLyBhZG1pbiBzaWRlYmFyIGF1dG8gZm9sZHNcbiRicmVhay1tZWRpdW06IDc4MnB4O1x0Ly8gYWRtaW5iYXIgZ29lcyBiaWdcbiRicmVhay1zbWFsbDogNjAwcHg7XG4kYnJlYWstbW9iaWxlOiA0ODBweDtcbiRicmVhay16b29tZWQtaW46IDI4MHB4O1xuXG4vLyBBbGwgbWVkaWEgcXVlcmllcyBjdXJyZW50bHkgaW4gV29yZFByZXNzOlxuLy9cbi8vIG1pbi13aWR0aDogMjAwMHB4XG4vLyBtaW4td2lkdGg6IDE2ODBweFxuLy8gbWluLXdpZHRoOiAxMjUwcHhcbi8vIG1heC13aWR0aDogMTEyMHB4ICpcbi8vIG1heC13aWR0aDogMTAwMHB4XG4vLyBtaW4td2lkdGg6IDc2OXB4IGFuZCBtYXgtd2lkdGg6IDEwMDBweFxuLy8gbWF4LXdpZHRoOiA5NjBweCAqXG4vLyBtYXgtd2lkdGg6IDkwMHB4XG4vLyBtYXgtd2lkdGg6IDg1MHB4XG4vLyBtaW4td2lkdGg6IDgwMHB4IGFuZCBtYXgtd2lkdGg6IDE0OTlweFxuLy8gbWF4LXdpZHRoOiA4MDBweFxuLy8gbWF4LXdpZHRoOiA3OTlweFxuLy8gbWF4LXdpZHRoOiA3ODJweCAqXG4vLyBtYXgtd2lkdGg6IDc2OHB4XG4vLyBtYXgtd2lkdGg6IDY0MHB4ICpcbi8vIG1heC13aWR0aDogNjAwcHggKlxuLy8gbWF4LXdpZHRoOiA1MjBweFxuLy8gbWF4LXdpZHRoOiA1MDBweFxuLy8gbWF4LXdpZHRoOiA0ODBweCAqXG4vLyBtYXgtd2lkdGg6IDQwMHB4ICpcbi8vIG1heC13aWR0aDogMzgwcHhcbi8vIG1heC13aWR0aDogMzIwcHggKlxuLy9cbi8vIFRob3NlIG1hcmtlZCAqIHNlZW0gdG8gYmUgbW9yZSBjb21tb25seSB1c2VkIHRoYW4gdGhlIG90aGVycy5cbi8vIExldCdzIHRyeSBhbmQgdXNlIGFzIGZldyBvZiB0aGVzZSBhcyBwb3NzaWJsZSwgYW5kIGJlIG1pbmRmdWwgYWJvdXQgYWRkaW5nIG5ldyBvbmVzLCBzbyB3ZSBkb24ndCBtYWtlIHRoZSBzaXR1YXRpb24gd29yc2VcbiIsIi8qKlxuKiAgQ29udmVydHMgYSBoZXggdmFsdWUgaW50byB0aGUgcmdiIGVxdWl2YWxlbnQuXG4qXG4qIEBwYXJhbSB7c3RyaW5nfSBoZXggLSB0aGUgaGV4YWRlY2ltYWwgdmFsdWUgdG8gY29udmVydFxuKiBAcmV0dXJuIHtzdHJpbmd9IGNvbW1hIHNlcGFyYXRlZCByZ2IgdmFsdWVzXG4qL1xuXG5AdXNlIFwic2Fzczpjb2xvclwiO1xuQHVzZSBcInNhc3M6bWV0YVwiO1xuXG5AZnVuY3Rpb24gaGV4LXRvLXJnYigkaGV4KSB7XG5cdC8qXG5cdCAqIFRPRE86IGBjb2xvci57cmVkfGdyZWVufGJsdWV9YCB3aWxsIHRyaWdnZXIgYSBkZXByZWNhdGlvbiB3YXJuaW5nIGluIERhcnQgU2Fzcyxcblx0ICogYnV0IHRoZSBTYXNzIHVzZWQgYnkgdGhlIEd1dGVuYmVyZyBwcm9qZWN0IGRvZXNuJ3Qgc3VwcG9ydCBgY29sb3IuY2hhbm5lbCgpYCB5ZXQsXG5cdCAqIHNvIHdlIGNhbid0IG1pZ3JhdGUgdG8gaXQgYXQgdGhpcyB0aW1lLlxuXHQgKiBJbiB0aGUgZnV0dXJlLCBhZnRlciB0aGUgR3V0ZW5iZXJnIHByb2plY3QgaGFzIGJlZW4gZnVsbHkgbWlncmF0ZWQgdG8gRGFydCBTYXNzLFxuXHQgKiBSZW1vdmUgdGhpcyBjb25kaXRpb25hbCBzdGF0ZW1lbnQgYW5kIHVzZSBvbmx5IGBjb2xvci5jaGFubmVsKClgLlxuXHQgKi9cblx0QGlmIG1ldGEuZnVuY3Rpb24tZXhpc3RzKFwiY2hhbm5lbFwiLCBcImNvbG9yXCIpIHtcblx0XHRAcmV0dXJuIGNvbG9yLmNoYW5uZWwoJGhleCwgXCJyZWRcIiksIGNvbG9yLmNoYW5uZWwoJGhleCwgXCJncmVlblwiKSwgY29sb3IuY2hhbm5lbCgkaGV4LCBcImJsdWVcIik7XG5cdH0gQGVsc2Uge1xuXHRcdEByZXR1cm4gY29sb3IucmVkKCRoZXgpLCBjb2xvci5ncmVlbigkaGV4KSwgY29sb3IuYmx1ZSgkaGV4KTtcblx0fVxufVxuIiwiLyoqXG4gKiBMb25nIGNvbnRlbnQgZmFkZSBtaXhpblxuICpcbiAqIENyZWF0ZXMgYSBmYWRpbmcgb3ZlcmxheSB0byBzaWduaWZ5IHRoYXQgdGhlIGNvbnRlbnQgaXMgbG9uZ2VyXG4gKiB0aGFuIHRoZSBzcGFjZSBhbGxvd3MuXG4gKi9cblxuQG1peGluIGxvbmctY29udGVudC1mYWRlKCRkaXJlY3Rpb246IHJpZ2h0LCAkc2l6ZTogMjAlLCAkY29sb3I6ICNmZmYsICRlZGdlOiAwLCAkei1pbmRleDogZmFsc2UpIHtcblx0Y29udGVudDogXCJcIjtcblx0ZGlzcGxheTogYmxvY2s7XG5cdHBvc2l0aW9uOiBhYnNvbHV0ZTtcblx0LXdlYmtpdC10b3VjaC1jYWxsb3V0OiBub25lO1xuXHR1c2VyLXNlbGVjdDogbm9uZTtcblx0cG9pbnRlci1ldmVudHM6IG5vbmU7XG5cblx0QGlmICR6LWluZGV4IHtcblx0XHR6LWluZGV4OiAkei1pbmRleDtcblx0fVxuXG5cdEBpZiAkZGlyZWN0aW9uID09IFwiYm90dG9tXCIge1xuXHRcdGJhY2tncm91bmQ6IGxpbmVhci1ncmFkaWVudCh0byB0b3AsIHRyYW5zcGFyZW50LCAkY29sb3IgOTAlKTtcblx0XHRsZWZ0OiAkZWRnZTtcblx0XHRyaWdodDogJGVkZ2U7XG5cdFx0dG9wOiAkZWRnZTtcblx0XHRib3R0b206IGNhbGMoMTAwJSAtICRzaXplKTtcblx0XHR3aWR0aDogYXV0bztcblx0fVxuXG5cdEBpZiAkZGlyZWN0aW9uID09IFwidG9wXCIge1xuXHRcdGJhY2tncm91bmQ6IGxpbmVhci1ncmFkaWVudCh0byBib3R0b20sIHRyYW5zcGFyZW50LCAkY29sb3IgOTAlKTtcblx0XHR0b3A6IGNhbGMoMTAwJSAtICRzaXplKTtcblx0XHRsZWZ0OiAkZWRnZTtcblx0XHRyaWdodDogJGVkZ2U7XG5cdFx0Ym90dG9tOiAkZWRnZTtcblx0XHR3aWR0aDogYXV0bztcblx0fVxuXG5cdEBpZiAkZGlyZWN0aW9uID09IFwibGVmdFwiIHtcblx0XHRiYWNrZ3JvdW5kOiBsaW5lYXItZ3JhZGllbnQodG8gbGVmdCwgdHJhbnNwYXJlbnQsICRjb2xvciA5MCUpO1xuXHRcdHRvcDogJGVkZ2U7XG5cdFx0bGVmdDogJGVkZ2U7XG5cdFx0Ym90dG9tOiAkZWRnZTtcblx0XHRyaWdodDogYXV0bztcblx0XHR3aWR0aDogJHNpemU7XG5cdFx0aGVpZ2h0OiBhdXRvO1xuXHR9XG5cblx0QGlmICRkaXJlY3Rpb24gPT0gXCJyaWdodFwiIHtcblx0XHRiYWNrZ3JvdW5kOiBsaW5lYXItZ3JhZGllbnQodG8gcmlnaHQsIHRyYW5zcGFyZW50LCAkY29sb3IgOTAlKTtcblx0XHR0b3A6ICRlZGdlO1xuXHRcdGJvdHRvbTogJGVkZ2U7XG5cdFx0cmlnaHQ6ICRlZGdlO1xuXHRcdGxlZnQ6IGF1dG87XG5cdFx0d2lkdGg6ICRzaXplO1xuXHRcdGhlaWdodDogYXV0bztcblx0fVxufVxuIiwiQHVzZSBcIi4vbWl4aW5zXCI7XG5AdXNlIFwiLi9mdW5jdGlvbnNcIjtcbkB1c2UgXCIuL2NvbG9yc1wiO1xuXG4vLyBJdCBpcyBpbXBvcnRhbnQgdG8gaW5jbHVkZSB0aGVzZSBzdHlsZXMgaW4gYWxsIGJ1aWx0IHN0eWxlc2hlZXRzLlxuLy8gVGhpcyBhbGxvd3MgdG8gQ1NTIHZhcmlhYmxlcyBwb3N0IENTUyBwbHVnaW4gdG8gZ2VuZXJhdGUgZmFsbGJhY2tzLlxuLy8gSXQgYWxzbyBwcm92aWRlcyBkZWZhdWx0IENTUyB2YXJpYWJsZXMgZm9yIG5wbSBwYWNrYWdlIGNvbnN1bWVycy5cbjpyb290IHtcblx0LS13cC1ibG9jay1zeW5jZWQtY29sb3I6ICM3YTAwZGY7XG5cdC0td3AtYmxvY2stc3luY2VkLWNvbG9yLS1yZ2I6ICN7ZnVuY3Rpb25zLmhleC10by1yZ2IoIzdhMDBkZil9O1xuXHQvLyBUaGlzIENTUyB2YXJpYWJsZSBpcyBub3QgdXNlZCBpbiBHdXRlbmJlcmcgcHJvamVjdCxcblx0Ly8gYnV0IGlzIG1haW50YWluZWQgZm9yIGJhY2t3YXJkcyBjb21wYXRpYmlsaXR5LlxuXHQtLXdwLWJvdW5kLWJsb2NrLWNvbG9yOiB2YXIoLS13cC1ibG9jay1zeW5jZWQtY29sb3IpO1xuXHQtLXdwLWVkaXRvci1jYW52YXMtYmFja2dyb3VuZDogI3tjb2xvcnMuJGdyYXktMzAwfTtcblx0QGluY2x1ZGUgbWl4aW5zLmFkbWluLXNjaGVtZSgjMDA3Y2JhKTtcbn1cbiIsIkB1c2UgXCJzYXNzOmNvbG9yXCI7XG5AdXNlIFwiQHdvcmRwcmVzcy9iYXNlLXN0eWxlcy9taXhpbnNcIiBhcyAqO1xuQHVzZSBcIkB3b3JkcHJlc3MvYmFzZS1zdHlsZXMvdmFyaWFibGVzXCIgYXMgKjtcbkB1c2UgXCJAd29yZHByZXNzL2Jhc2Utc3R5bGVzL2NvbG9yc1wiIGFzICo7XG5AdXNlIFwiQHdvcmRwcmVzcy9iYXNlLXN0eWxlcy9kZWZhdWx0LWN1c3RvbS1wcm9wZXJ0aWVzXCIgYXMgKjtcblxuLy8gSGVyZSB3ZSBleHRlbmQgdGhlIG1vZGFsIHN0eWxlcyB0byBiZSB0aWdodGVyLCBhbmQgdG8gdGhlIGNlbnRlci4gQmVjYXVzZSB0aGUgcGFsZXR0ZSB1c2VzIHRoZSBtb2RhbCBhcyBhIGNvbnRhaW5lci5cbi53b3JrZmxvd3Mtd29ya2Zsb3ctbWVudSB7XG5cdGJvcmRlci1yYWRpdXM6ICRncmlkLXVuaXQtMDU7XG5cdHdpZHRoOiBjYWxjKDEwMCUgLSAjeyRncmlkLXVuaXQtNDB9KTtcblx0bWFyZ2luOiBhdXRvO1xuXHRtYXgtd2lkdGg6IDQwMHB4O1xuXHRwb3NpdGlvbjogcmVsYXRpdmU7XG5cdHRvcDogY2FsYyg1JSArICN7JGhlYWRlci1oZWlnaHR9KTtcblxuXHRAaW5jbHVkZSBicmVhay1zbWFsbCgpIHtcblx0XHR0b3A6IGNhbGMoMTAlICsgI3skaGVhZGVyLWhlaWdodH0pO1xuXHR9XG5cblx0LmNvbXBvbmVudHMtbW9kYWxfX2NvbnRlbnQge1xuXHRcdG1hcmdpbjogMDtcblx0XHRwYWRkaW5nOiAwO1xuXHR9XG59XG5cbi53b3JrZmxvd3Mtd29ya2Zsb3ctbWVudV9fb3ZlcmxheSB7XG5cdGRpc3BsYXk6IGJsb2NrO1xuXHRhbGlnbi1pdGVtczogc3RhcnQ7XG59XG5cbi53b3JrZmxvd3Mtd29ya2Zsb3ctbWVudV9faGVhZGVyIHtcblx0cGFkZGluZzogMCAkZ3JpZC11bml0LTIwO1xufVxuXG4ud29ya2Zsb3dzLXdvcmtmbG93LW1lbnVfX2hlYWRlci1zZWFyY2gtaWNvbiB7XG5cdCY6ZGlyKGx0cikge1xuXHRcdHRyYW5zZm9ybTogc2NhbGVYKC0xKTtcblx0fVxufVxuXG4ud29ya2Zsb3dzLXdvcmtmbG93LW1lbnVfX2NvbnRhaW5lciB7XG5cdC8vIHRoZSBzdHlsZSBoZXJlIGlzIGEgaGFjayB0byBmb3JjZSBzYWZhcmkgdG8gcmVwYWludCB0byBhdm9pZCBhIHN0eWxlIGdsaXRjaFxuXHR3aWxsLWNoYW5nZTogdHJhbnNmb3JtO1xuXG5cdCY6Zm9jdXMge1xuXHRcdG91dGxpbmU6IG5vbmU7XG5cdH1cblxuXHRbY21kay1pbnB1dF0ge1xuXHRcdGJvcmRlcjogbm9uZTtcblx0XHR3aWR0aDogMTAwJTtcblx0XHRwYWRkaW5nOiAkZ3JpZC11bml0LTIwICRncmlkLXVuaXQtMDU7XG5cdFx0b3V0bGluZTogbm9uZTtcblx0XHRjb2xvcjogJGdyYXktOTAwO1xuXHRcdG1hcmdpbjogMDtcblx0XHRmb250LXNpemU6IDE1cHg7XG5cdFx0bGluZS1oZWlnaHQ6IDI4cHg7XG5cdFx0Ym9yZGVyLXJhZGl1czogMDtcblxuXHRcdCY6OnBsYWNlaG9sZGVyIHtcblx0XHRcdGNvbG9yOiAkZ3JheS03MDA7XG5cdFx0fVxuXG5cdFx0Jjpmb2N1cyB7XG5cdFx0XHRib3gtc2hhZG93OiBub25lO1xuXHRcdFx0b3V0bGluZTogbm9uZTtcblx0XHR9XG5cdH1cblxuXHRbY21kay1pdGVtXSB7XG5cdFx0Ym9yZGVyLXJhZGl1czogJHJhZGl1cy1zbWFsbDtcblx0XHRjdXJzb3I6IHBvaW50ZXI7XG5cdFx0ZGlzcGxheTogZmxleDtcblx0XHRhbGlnbi1pdGVtczogY2VudGVyO1xuXHRcdGNvbG9yOiAkZ3JheS05MDA7XG5cdFx0Zm9udC1zaXplOiAkZGVmYXVsdC1mb250LXNpemU7XG5cblx0XHQmW2FyaWEtc2VsZWN0ZWQ9XCJ0cnVlXCJdLFxuXHRcdCY6YWN0aXZlIHtcblx0XHRcdGJhY2tncm91bmQ6IHZhcigtLXdwLWFkbWluLXRoZW1lLWNvbG9yKTtcblx0XHRcdGNvbG9yOiAkd2hpdGU7XG5cdFx0fVxuXG5cdFx0JlthcmlhLWRpc2FibGVkPVwidHJ1ZVwiXSB7XG5cdFx0XHRjb2xvcjogJGdyYXktNjAwO1xuXHRcdFx0Y3Vyc29yOiBub3QtYWxsb3dlZDtcblx0XHR9XG5cblx0XHQ+IGRpdiB7XG5cdFx0XHRtaW4taGVpZ2h0OiAkYnV0dG9uLXNpemUtbmV4dC1kZWZhdWx0LTQwcHg7XG5cdFx0XHRwYWRkaW5nOiAkZ3JpZC11bml0LTA1O1xuXHRcdFx0cGFkZGluZy1sZWZ0OiAkZ3JpZC11bml0LTIwO1xuXHRcdH1cblx0fVxuXG5cdFtjbWRrLXJvb3RdID4gW2NtZGstbGlzdF0ge1xuXHRcdG1heC1oZWlnaHQ6ICRwYWxldHRlLW1heC1oZWlnaHQ7IC8vIFNwZWNpZmljIHRvIG5vdCBoYXZlIHdvcmtmbG93cyBvdmVyZmxvdyBvZGRseS5cblx0XHRvdmVyZmxvdzogYXV0bztcblxuXHRcdC8vIEVuc3VyZXMgdGhlcmUgaXMgYWx3YXlzIHBhZGRpbmcgYm90dG9tIG9uIHRoZSBsYXN0IGdyb3VwLCB3aGVuIHRoZXJlIGFyZSB3b3JrZmxvd3MuXG5cdFx0JlxuXHRcdFtjbWRrLWxpc3Qtc2l6ZXJdID4gW2NtZGstZ3JvdXBdOmxhc3QtY2hpbGRcblx0XHRbY21kay1ncm91cC1pdGVtc106bm90KDplbXB0eSkge1xuXHRcdFx0cGFkZGluZy1ib3R0b206ICRncmlkLXVuaXQtMTA7XG5cdFx0fVxuXG5cdFx0JiBbY21kay1saXN0LXNpemVyXSA+IFtjbWRrLWdyb3VwXSA+IFtjbWRrLWdyb3VwLWl0ZW1zXTpub3QoOmVtcHR5KSB7XG5cdFx0XHRwYWRkaW5nOiAwICRncmlkLXVuaXQtMTA7XG5cdFx0fVxuXHR9XG5cblx0W2NtZGstZW1wdHldIHtcblx0XHRkaXNwbGF5OiBmbGV4O1xuXHRcdGFsaWduLWl0ZW1zOiBjZW50ZXI7XG5cdFx0anVzdGlmeS1jb250ZW50OiBjZW50ZXI7XG5cdFx0d2hpdGUtc3BhY2U6IHByZS13cmFwO1xuXHRcdGNvbG9yOiAkZ3JheS05MDA7XG5cdFx0cGFkZGluZzogJGdyaWQtdW5pdC0xMCAwICRncmlkLXVuaXQtNDA7XG5cdH1cblxuXHRbY21kay1sb2FkaW5nXSB7XG5cdFx0cGFkZGluZzogJGdyaWQtdW5pdC0yMDtcblx0fVxuXG5cdFtjbWRrLWxpc3Qtc2l6ZXJdIHtcblx0XHRwb3NpdGlvbjogcmVsYXRpdmU7XG5cdH1cbn1cblxuLndvcmtmbG93cy13b3JrZmxvdy1tZW51X19pdGVtIHNwYW4ge1xuXHQvLyBFbnN1cmUgd29ya2Zsb3dzIGRvIG5vdCBydW4gb2ZmIHRoZSBlZGdlIChncmVhdCBmb3IgcG9zdCB0aXRsZXMpLlxuXHRkaXNwbGF5OiBpbmxpbmUtYmxvY2s7XG5cdG92ZXJmbG93OiBoaWRkZW47XG5cdHRleHQtb3ZlcmZsb3c6IGVsbGlwc2lzO1xuXHR3aGl0ZS1zcGFjZTogbm93cmFwO1xufVxuXG4ud29ya2Zsb3dzLXdvcmtmbG93LW1lbnVfX2l0ZW0gbWFyayB7XG5cdGNvbG9yOiBpbmhlcml0O1xuXHRiYWNrZ3JvdW5kOiB1bnNldDtcblx0Zm9udC13ZWlnaHQ6IDYwMDtcbn1cblxuLndvcmtmbG93cy13b3JrZmxvdy1tZW51X19vdXRwdXQge1xuXHRwYWRkaW5nOiAkZ3JpZC11bml0LTIwO1xufVxuXG4ud29ya2Zsb3dzLXdvcmtmbG93LW1lbnVfX291dHB1dC1oZWFkZXIge1xuXHRtYXJnaW4tYm90dG9tOiAkZ3JpZC11bml0LTIwO1xuXHRib3JkZXItYm90dG9tOiAxcHggc29saWQgJGdyYXktMzAwO1xuXHRwYWRkaW5nLWJvdHRvbTogJGdyaWQtdW5pdC0xMDtcblxuXHRoMyB7XG5cdFx0bWFyZ2luOiAwIDAgJGdyaWQtdW5pdC0wNTtcblx0XHRmb250LXNpemU6IDE2cHg7XG5cdFx0Zm9udC13ZWlnaHQ6IDYwMDtcblx0XHRjb2xvcjogJGdyYXktOTAwO1xuXHR9XG59XG5cbi53b3JrZmxvd3Mtd29ya2Zsb3ctbWVudV9fb3V0cHV0LWhpbnQge1xuXHRtYXJnaW46IDA7XG5cdGZvbnQtc2l6ZTogMTJweDtcblx0Y29sb3I6ICRncmF5LTcwMDtcbn1cblxuLndvcmtmbG93cy13b3JrZmxvdy1tZW51X19vdXRwdXQtY29udGVudCB7XG5cdG1heC1oZWlnaHQ6IDQwMHB4O1xuXHRvdmVyZmxvdzogYXV0bztcblxuXHRwcmUge1xuXHRcdG1hcmdpbjogMDtcblx0XHRwYWRkaW5nOiAkZ3JpZC11bml0LTE1O1xuXHRcdGJhY2tncm91bmQ6ICRncmF5LTEwMDtcblx0XHRib3JkZXItcmFkaXVzOiAkcmFkaXVzLXNtYWxsO1xuXHRcdGZvbnQtc2l6ZTogMTJweDtcblx0XHRsaW5lLWhlaWdodDogMS41O1xuXHRcdHdoaXRlLXNwYWNlOiBwcmUtd3JhcDtcblx0XHR3b3JkLWJyZWFrOiBicmVhay13b3JkO1xuXHRcdGNvbG9yOiAkZ3JheS05MDA7XG5cdH1cbn1cblxuLndvcmtmbG93cy13b3JrZmxvdy1tZW51X19vdXRwdXQtZXJyb3Ige1xuXHRwYWRkaW5nOiAkZ3JpZC11bml0LTE1O1xuXHRiYWNrZ3JvdW5kOiAkZ3JheS0yMDA7XG5cdGJvcmRlcjogMXB4IHNvbGlkICN7Y29sb3IuYWRqdXN0KCAkYWxlcnQtcmVkLCAkbGlnaHRuZXNzOiAtMTAlICl9O1xuXHRib3JkZXItcmFkaXVzOiAkcmFkaXVzLXNtYWxsO1xuXHRjb2xvcjogJGFsZXJ0LXJlZDtcblxuXHRwIHtcblx0XHRtYXJnaW46IDA7XG5cdFx0Zm9udC1zaXplOiAxM3B4O1xuXHR9XG59XG5cbi53b3JrZmxvd3Mtd29ya2Zsb3ctbWVudV9fZXhlY3V0aW5nIHtcblx0cGFkZGluZzogJGdyaWQtdW5pdC0zMCAkZ3JpZC11bml0LTIwO1xuXHRjb2xvcjogJGdyYXktNzAwO1xuXHRmb250LXNpemU6IDE0cHg7XG59XG4iXX0= */`;
document.head.appendChild(document.createElement("style")).appendChild(document.createTextNode(css));
var { withIgnoreIMEEvents } = unlock(import_components.privateApis);
var EMPTY_ARRAY = [];
var inputLabel = (0, import_i18n.__)("Run abilities and workflows");
function WorkflowInput({ isOpen, search, setSearch, abilities }) {
  const workflowMenuInput = (0, import_element2.useRef)();
  const _value = P((state) => state.value);
  const selectedItemId = (0, import_element2.useMemo)(() => {
    const ability = abilities.find((a) => a.label === _value);
    return ability?.name;
  }, [_value, abilities]);
  (0, import_element2.useEffect)(() => {
    if (isOpen) {
      workflowMenuInput.current.focus();
    }
  }, [isOpen]);
  return /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(
    _e.Input,
    {
      ref: workflowMenuInput,
      value: search,
      onValueChange: setSearch,
      placeholder: inputLabel,
      "aria-activedescendant": selectedItemId
    }
  );
}
function WorkflowMenu() {
  const { registerShortcut } = (0, import_data.useDispatch)(import_keyboard_shortcuts.store);
  const [search, setSearch] = (0, import_element2.useState)("");
  const [isOpen, setIsOpen] = (0, import_element2.useState)(false);
  const [abilityOutput, setAbilityOutput] = (0, import_element2.useState)(null);
  const [isExecuting, setIsExecuting] = (0, import_element2.useState)(false);
  const containerRef = (0, import_element2.useRef)();
  const abilities = (0, import_data.useSelect)((select) => {
    const allAbilities = select(abilitiesStore).getAbilities();
    return allAbilities || EMPTY_ARRAY;
  }, []);
  const filteredAbilities = (0, import_element2.useMemo)(() => {
    if (!search) {
      return abilities;
    }
    const searchLower = search.toLowerCase();
    return abilities.filter(
      (ability) => ability.label?.toLowerCase().includes(searchLower) || ability.name?.toLowerCase().includes(searchLower)
    );
  }, [abilities, search]);
  (0, import_element2.useEffect)(() => {
    if (abilityOutput && containerRef.current) {
      containerRef.current.focus();
    }
  }, [abilityOutput]);
  (0, import_element2.useEffect)(() => {
    registerShortcut({
      name: "core/workflows",
      category: "global",
      description: (0, import_i18n.__)("Open the workflow palette."),
      keyCombination: {
        modifier: "primary",
        character: "j"
      }
    });
  }, [registerShortcut]);
  (0, import_keyboard_shortcuts.useShortcut)(
    "core/workflows",
    /** @type {import('react').KeyboardEventHandler} */
    withIgnoreIMEEvents((event) => {
      if (event.defaultPrevented) {
        return;
      }
      event.preventDefault();
      setIsOpen(!isOpen);
    }),
    {
      bindGlobal: true
    }
  );
  const closeAndReset = () => {
    setSearch("");
    setIsOpen(false);
    setAbilityOutput(null);
    setIsExecuting(false);
  };
  const goBack = () => {
    setAbilityOutput(null);
    setIsExecuting(false);
    setSearch("");
  };
  const handleExecuteAbility = async (ability) => {
    setIsExecuting(true);
    try {
      const result = await executeAbility(ability.name);
      setAbilityOutput({
        name: ability.name,
        label: ability?.label || ability.name,
        description: ability?.description || "",
        success: true,
        data: result
      });
    } catch (error) {
      setAbilityOutput({
        name: ability.name,
        label: ability?.label || ability.name,
        description: ability?.description || "",
        success: false,
        error: error.message || String(error)
      });
    } finally {
      setIsExecuting(false);
    }
  };
  const onContainerKeyDown = (event) => {
    if (abilityOutput && (event.key === "Escape" || event.key === "Backspace" || event.key === "Delete")) {
      event.preventDefault();
      event.stopPropagation();
      goBack();
    }
  };
  if (!isOpen) {
    return null;
  }
  return /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(
    import_components.Modal,
    {
      className: "workflows-workflow-menu",
      overlayClassName: "workflows-workflow-menu__overlay",
      onRequestClose: abilityOutput ? goBack : closeAndReset,
      __experimentalHideHeader: true,
      contentLabel: (0, import_i18n.__)("Workflow palette"),
      children: /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(
        "div",
        {
          className: "workflows-workflow-menu__container",
          onKeyDown: withIgnoreIMEEvents(onContainerKeyDown),
          ref: containerRef,
          tabIndex: -1,
          role: "presentation",
          children: abilityOutput ? /* @__PURE__ */ (0, import_jsx_runtime17.jsxs)("div", { className: "workflows-workflow-menu__output", children: [
            /* @__PURE__ */ (0, import_jsx_runtime17.jsxs)("div", { className: "workflows-workflow-menu__output-header", children: [
              /* @__PURE__ */ (0, import_jsx_runtime17.jsx)("h3", { children: abilityOutput.label }),
              abilityOutput.description && /* @__PURE__ */ (0, import_jsx_runtime17.jsx)("p", { className: "workflows-workflow-menu__output-hint", children: abilityOutput.description })
            ] }),
            /* @__PURE__ */ (0, import_jsx_runtime17.jsx)("div", { className: "workflows-workflow-menu__output-content", children: abilityOutput.success ? /* @__PURE__ */ (0, import_jsx_runtime17.jsx)("pre", { children: JSON.stringify(
              abilityOutput.data,
              null,
              2
            ) }) : /* @__PURE__ */ (0, import_jsx_runtime17.jsx)("div", { className: "workflows-workflow-menu__output-error", children: /* @__PURE__ */ (0, import_jsx_runtime17.jsx)("p", { children: abilityOutput.error }) }) })
          ] }) : /* @__PURE__ */ (0, import_jsx_runtime17.jsxs)(_e, { label: inputLabel, shouldFilter: false, children: [
            /* @__PURE__ */ (0, import_jsx_runtime17.jsxs)(import_components.__experimentalHStack, { className: "workflows-workflow-menu__header", children: [
              /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(
                icon_default,
                {
                  className: "workflows-workflow-menu__header-search-icon",
                  icon: search_default
                }
              ),
              /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(
                WorkflowInput,
                {
                  search,
                  setSearch,
                  isOpen,
                  abilities
                }
              )
            ] }),
            /* @__PURE__ */ (0, import_jsx_runtime17.jsxs)(_e.List, { label: (0, import_i18n.__)("Workflow suggestions"), children: [
              isExecuting && /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(
                import_components.__experimentalHStack,
                {
                  className: "workflows-workflow-menu__executing",
                  align: "center",
                  children: (0, import_i18n.__)("Executing ability\u2026")
                }
              ),
              !isExecuting && search && filteredAbilities.length === 0 && /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(_e.Empty, { children: (0, import_i18n.__)("No results found.") }),
              !isExecuting && filteredAbilities.length > 0 && /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(_e.Group, { children: filteredAbilities.map((ability) => /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(
                _e.Item,
                {
                  value: ability.label,
                  className: "workflows-workflow-menu__item",
                  onSelect: () => handleExecuteAbility(ability),
                  id: ability.name,
                  children: /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(import_components.__experimentalHStack, { alignment: "left", children: /* @__PURE__ */ (0, import_jsx_runtime17.jsx)("span", { children: /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(
                    import_components.TextHighlight,
                    {
                      text: ability.label,
                      highlight: search
                    }
                  ) }) })
                },
                ability.name
              )) })
            ] })
          ] })
        }
      )
    }
  );
}

// packages/workflow/build-module/index.mjs
var root = document.createElement("div");
document.body.appendChild(root);
(0, import_element3.createRoot)(root).render((0, import_element3.createElement)(WorkflowMenu));
//# sourceMappingURL=index.js.map
