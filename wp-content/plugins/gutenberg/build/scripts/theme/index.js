"use strict";
var wp;
(wp ||= {}).theme = (() => {
  var __create = Object.create;
  var __defProp = Object.defineProperty;
  var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __getProtoOf = Object.getPrototypeOf;
  var __hasOwnProp = Object.prototype.hasOwnProperty;
  var __commonJS = (cb, mod) => function __require() {
    return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
  };
  var __export = (target, all) => {
    for (var name in all)
      __defProp(target, name, { get: all[name], enumerable: true });
  };
  var __copyProps = (to2, from, except, desc) => {
    if (from && typeof from === "object" || typeof from === "function") {
      for (let key of __getOwnPropNames(from))
        if (!__hasOwnProp.call(to2, key) && key !== except)
          __defProp(to2, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
    }
    return to2;
  };
  var __toESM = (mod, isNodeMode, target) => (target = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(
    // If the importer is in node compatibility mode or this is not an ESM
    // file that has been converted to a CommonJS file using a Babel-
    // compatible transform (i.e. "__esModule" has not been set), then set
    // "default" to the CommonJS "module.exports" for node compatibility.
    isNodeMode || !mod || !mod.__esModule ? __defProp(target, "default", { value: mod, enumerable: true }) : target,
    mod
  ));
  var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: true }), mod);

  // package-external:@wordpress/private-apis
  var require_private_apis = __commonJS({
    "package-external:@wordpress/private-apis"(exports, module) {
      module.exports = window.wp.privateApis;
    }
  });

  // vendor-external:react/jsx-runtime
  var require_jsx_runtime = __commonJS({
    "vendor-external:react/jsx-runtime"(exports, module) {
      module.exports = window.ReactJSXRuntime;
    }
  });

  // package-external:@wordpress/element
  var require_element = __commonJS({
    "package-external:@wordpress/element"(exports, module) {
      module.exports = window.wp.element;
    }
  });

  // packages/theme/build-module/index.js
  var index_exports = {};
  __export(index_exports, {
    privateApis: () => privateApis
  });

  // packages/theme/build-module/lock-unlock.js
  var import_private_apis = __toESM(require_private_apis());
  var { lock, unlock } = (0, import_private_apis.__dangerousOptInToUnstableAPIsOnlyForCoreModules)(
    "I acknowledge private features are not for use in themes or plugins and doing so will break in the next version of WordPress.",
    "@wordpress/theme"
  );

  // packages/theme/build-module/theme-provider.js
  var import_jsx_runtime = __toESM(require_jsx_runtime());
  var import_element3 = __toESM(require_element());

  // packages/theme/build-module/context.js
  var import_element = __toESM(require_element());
  var ThemeContext = (0, import_element.createContext)({
    resolvedSettings: {
      color: {}
    }
  });

  // node_modules/colorjs.io/dist/color.js
  function multiplyMatrices(A, B) {
    let m3 = A.length;
    if (!Array.isArray(A[0])) {
      A = [A];
    }
    if (!Array.isArray(B[0])) {
      B = B.map((x) => [x]);
    }
    let p2 = B[0].length;
    let B_cols = B[0].map((_, i) => B.map((x) => x[i]));
    let product = A.map((row) => B_cols.map((col) => {
      let ret = 0;
      if (!Array.isArray(row)) {
        for (let c4 of col) {
          ret += row * c4;
        }
        return ret;
      }
      for (let i = 0; i < row.length; i++) {
        ret += row[i] * (col[i] || 0);
      }
      return ret;
    }));
    if (m3 === 1) {
      product = product[0];
    }
    if (p2 === 1) {
      return product.map((x) => x[0]);
    }
    return product;
  }
  function isString(str) {
    return type(str) === "string";
  }
  function type(o) {
    let str = Object.prototype.toString.call(o);
    return (str.match(/^\[object\s+(.*?)\]$/)[1] || "").toLowerCase();
  }
  function serializeNumber(n2, { precision, unit }) {
    if (isNone(n2)) {
      return "none";
    }
    return toPrecision(n2, precision) + (unit ?? "");
  }
  function isNone(n2) {
    return Number.isNaN(n2) || n2 instanceof Number && n2?.none;
  }
  function skipNone(n2) {
    return isNone(n2) ? 0 : n2;
  }
  function toPrecision(n2, precision) {
    if (n2 === 0) {
      return 0;
    }
    let integer = ~~n2;
    let digits = 0;
    if (integer && precision) {
      digits = ~~Math.log10(Math.abs(integer)) + 1;
    }
    const multiplier = 10 ** (precision - digits);
    return Math.floor(n2 * multiplier + 0.5) / multiplier;
  }
  var angleFactor = {
    deg: 1,
    grad: 0.9,
    rad: 180 / Math.PI,
    turn: 360
  };
  function parseFunction(str) {
    if (!str) {
      return;
    }
    str = str.trim();
    const isFunctionRegex = /^([a-z]+)\((.+?)\)$/i;
    const isNumberRegex = /^-?[\d.]+$/;
    const unitValueRegex = /%|deg|g?rad|turn$/;
    const singleArgument = /\/?\s*(none|[-\w.]+(?:%|deg|g?rad|turn)?)/g;
    let parts = str.match(isFunctionRegex);
    if (parts) {
      let args = [];
      parts[2].replace(singleArgument, ($0, rawArg) => {
        let match = rawArg.match(unitValueRegex);
        let arg = rawArg;
        if (match) {
          let unit = match[0];
          let unitlessArg = arg.slice(0, -unit.length);
          if (unit === "%") {
            arg = new Number(unitlessArg / 100);
            arg.type = "<percentage>";
          } else {
            arg = new Number(unitlessArg * angleFactor[unit]);
            arg.type = "<angle>";
            arg.unit = unit;
          }
        } else if (isNumberRegex.test(arg)) {
          arg = new Number(arg);
          arg.type = "<number>";
        } else if (arg === "none") {
          arg = new Number(NaN);
          arg.none = true;
        }
        if ($0.startsWith("/")) {
          arg = arg instanceof Number ? arg : new Number(arg);
          arg.alpha = true;
        }
        if (typeof arg === "object" && arg instanceof Number) {
          arg.raw = rawArg;
        }
        args.push(arg);
      });
      return {
        name: parts[1].toLowerCase(),
        rawName: parts[1],
        rawArgs: parts[2],
        // An argument could be (as of css-color-4):
        // a number, percentage, degrees (hue), ident (in color())
        args
      };
    }
  }
  function last(arr) {
    return arr[arr.length - 1];
  }
  function interpolate(start, end, p2) {
    if (isNaN(start)) {
      return end;
    }
    if (isNaN(end)) {
      return start;
    }
    return start + (end - start) * p2;
  }
  function interpolateInv(start, end, value) {
    return (value - start) / (end - start);
  }
  function mapRange(from, to2, value) {
    return interpolate(to2[0], to2[1], interpolateInv(from[0], from[1], value));
  }
  function parseCoordGrammar(coordGrammars) {
    return coordGrammars.map((coordGrammar2) => {
      return coordGrammar2.split("|").map((type2) => {
        type2 = type2.trim();
        let range2 = type2.match(/^(<[a-z]+>)\[(-?[.\d]+),\s*(-?[.\d]+)\]?$/);
        if (range2) {
          let ret = new String(range2[1]);
          ret.range = [+range2[2], +range2[3]];
          return ret;
        }
        return type2;
      });
    });
  }
  function clamp(min, val, max2) {
    return Math.max(Math.min(max2, val), min);
  }
  function copySign(to2, from) {
    return Math.sign(to2) === Math.sign(from) ? to2 : -to2;
  }
  function spow(base, exp) {
    return copySign(Math.abs(base) ** exp, base);
  }
  function zdiv(n2, d2) {
    return d2 === 0 ? 0 : n2 / d2;
  }
  function bisectLeft(arr, value, lo = 0, hi = arr.length) {
    while (lo < hi) {
      const mid = lo + hi >> 1;
      if (arr[mid] < value) {
        lo = mid + 1;
      } else {
        hi = mid;
      }
    }
    return lo;
  }
  var util = /* @__PURE__ */ Object.freeze({
    __proto__: null,
    bisectLeft,
    clamp,
    copySign,
    interpolate,
    interpolateInv,
    isNone,
    isString,
    last,
    mapRange,
    multiplyMatrices,
    parseCoordGrammar,
    parseFunction,
    serializeNumber,
    skipNone,
    spow,
    toPrecision,
    type,
    zdiv
  });
  var Hooks = class {
    add(name, callback, first) {
      if (typeof arguments[0] != "string") {
        for (var name in arguments[0]) {
          this.add(name, arguments[0][name], arguments[1]);
        }
        return;
      }
      (Array.isArray(name) ? name : [name]).forEach(function(name2) {
        this[name2] = this[name2] || [];
        if (callback) {
          this[name2][first ? "unshift" : "push"](callback);
        }
      }, this);
    }
    run(name, env) {
      this[name] = this[name] || [];
      this[name].forEach(function(callback) {
        callback.call(env && env.context ? env.context : env, env);
      });
    }
  };
  var hooks = new Hooks();
  var defaults = {
    gamut_mapping: "css",
    precision: 5,
    deltaE: "76",
    // Default deltaE method
    verbose: globalThis?.process?.env?.NODE_ENV?.toLowerCase() !== "test",
    warn: function warn(msg) {
      if (this.verbose) {
        globalThis?.console?.warn?.(msg);
      }
    }
  };
  var WHITES = {
    // for compatibility, the four-digit chromaticity-derived ones everyone else uses
    D50: [0.3457 / 0.3585, 1, (1 - 0.3457 - 0.3585) / 0.3585],
    D65: [0.3127 / 0.329, 1, (1 - 0.3127 - 0.329) / 0.329]
  };
  function getWhite(name) {
    if (Array.isArray(name)) {
      return name;
    }
    return WHITES[name];
  }
  function adapt$2(W1, W2, XYZ, options = {}) {
    W1 = getWhite(W1);
    W2 = getWhite(W2);
    if (!W1 || !W2) {
      throw new TypeError(`Missing white point to convert ${!W1 ? "from" : ""}${!W1 && !W2 ? "/" : ""}${!W2 ? "to" : ""}`);
    }
    if (W1 === W2) {
      return XYZ;
    }
    let env = { W1, W2, XYZ, options };
    hooks.run("chromatic-adaptation-start", env);
    if (!env.M) {
      if (env.W1 === WHITES.D65 && env.W2 === WHITES.D50) {
        env.M = [
          [1.0479297925449969, 0.022946870601609652, -0.05019226628920524],
          [0.02962780877005599, 0.9904344267538799, -0.017073799063418826],
          [-0.009243040646204504, 0.015055191490298152, 0.7518742814281371]
        ];
      } else if (env.W1 === WHITES.D50 && env.W2 === WHITES.D65) {
        env.M = [
          [0.955473421488075, -0.02309845494876471, 0.06325924320057072],
          [-0.0283697093338637, 1.0099953980813041, 0.021041441191917323],
          [0.012314014864481998, -0.020507649298898964, 1.330365926242124]
        ];
      }
    }
    hooks.run("chromatic-adaptation-end", env);
    if (env.M) {
      return multiplyMatrices(env.M, env.XYZ);
    } else {
      throw new TypeError("Only Bradford CAT with white points D50 and D65 supported for now.");
    }
  }
  var noneTypes = /* @__PURE__ */ new Set(["<number>", "<percentage>", "<angle>"]);
  function coerceCoords(space, format, name, coords) {
    let types = Object.entries(space.coords).map(([id, coordMeta], i) => {
      let coordGrammar2 = format.coordGrammar[i];
      let arg = coords[i];
      let providedType = arg?.type;
      let type2;
      if (arg.none) {
        type2 = coordGrammar2.find((c4) => noneTypes.has(c4));
      } else {
        type2 = coordGrammar2.find((c4) => c4 == providedType);
      }
      if (!type2) {
        let coordName = coordMeta.name || id;
        throw new TypeError(`${providedType ?? arg.raw} not allowed for ${coordName} in ${name}()`);
      }
      let fromRange = type2.range;
      if (providedType === "<percentage>") {
        fromRange ||= [0, 1];
      }
      let toRange = coordMeta.range || coordMeta.refRange;
      if (fromRange && toRange) {
        coords[i] = mapRange(fromRange, toRange, coords[i]);
      }
      return type2;
    });
    return types;
  }
  function parse(str, { meta } = {}) {
    let env = { "str": String(str)?.trim() };
    hooks.run("parse-start", env);
    if (env.color) {
      return env.color;
    }
    env.parsed = parseFunction(env.str);
    if (env.parsed) {
      let name = env.parsed.name;
      if (name === "color") {
        let id = env.parsed.args.shift();
        let alternateId = id.startsWith("--") ? id.substring(2) : `--${id}`;
        let ids = [id, alternateId];
        let alpha = env.parsed.rawArgs.indexOf("/") > 0 ? env.parsed.args.pop() : 1;
        for (let space of ColorSpace.all) {
          let colorSpec = space.getFormat("color");
          if (colorSpec) {
            if (ids.includes(colorSpec.id) || colorSpec.ids?.filter((specId) => ids.includes(specId)).length) {
              const coords = Object.keys(space.coords).map((_, i) => env.parsed.args[i] || 0);
              let types;
              if (colorSpec.coordGrammar) {
                types = coerceCoords(space, colorSpec, "color", coords);
              }
              if (meta) {
                Object.assign(meta, { formatId: "color", types });
              }
              if (colorSpec.id.startsWith("--") && !id.startsWith("--")) {
                defaults.warn(`${space.name} is a non-standard space and not currently supported in the CSS spec. Use prefixed color(${colorSpec.id}) instead of color(${id}).`);
              }
              if (id.startsWith("--") && !colorSpec.id.startsWith("--")) {
                defaults.warn(`${space.name} is a standard space and supported in the CSS spec. Use color(${colorSpec.id}) instead of prefixed color(${id}).`);
              }
              return { spaceId: space.id, coords, alpha };
            }
          }
        }
        let didYouMean = "";
        let registryId = id in ColorSpace.registry ? id : alternateId;
        if (registryId in ColorSpace.registry) {
          let cssId = ColorSpace.registry[registryId].formats?.color?.id;
          if (cssId) {
            didYouMean = `Did you mean color(${cssId})?`;
          }
        }
        throw new TypeError(`Cannot parse color(${id}). ` + (didYouMean || "Missing a plugin?"));
      } else {
        for (let space of ColorSpace.all) {
          let format = space.getFormat(name);
          if (format && format.type === "function") {
            let alpha = 1;
            if (format.lastAlpha || last(env.parsed.args).alpha) {
              alpha = env.parsed.args.pop();
            }
            let coords = env.parsed.args;
            let types;
            if (format.coordGrammar) {
              types = coerceCoords(space, format, name, coords);
            }
            if (meta) {
              Object.assign(meta, { formatId: format.name, types });
            }
            return {
              spaceId: space.id,
              coords,
              alpha
            };
          }
        }
      }
    } else {
      for (let space of ColorSpace.all) {
        for (let formatId in space.formats) {
          let format = space.formats[formatId];
          if (format.type !== "custom") {
            continue;
          }
          if (format.test && !format.test(env.str)) {
            continue;
          }
          let color = format.parse(env.str);
          if (color) {
            color.alpha ??= 1;
            if (meta) {
              meta.formatId = formatId;
            }
            return color;
          }
        }
      }
    }
    throw new TypeError(`Could not parse ${str} as a color. Missing a plugin?`);
  }
  function getColor(color) {
    if (Array.isArray(color)) {
      return color.map(getColor);
    }
    if (!color) {
      throw new TypeError("Empty color reference");
    }
    if (isString(color)) {
      color = parse(color);
    }
    let space = color.space || color.spaceId;
    if (!(space instanceof ColorSpace)) {
      color.space = ColorSpace.get(space);
    }
    if (color.alpha === void 0) {
      color.alpha = 1;
    }
    return color;
  }
  var \u03B5$7 = 75e-6;
  var ColorSpace = class _ColorSpace {
    constructor(options) {
      this.id = options.id;
      this.name = options.name;
      this.base = options.base ? _ColorSpace.get(options.base) : null;
      this.aliases = options.aliases;
      if (this.base) {
        this.fromBase = options.fromBase;
        this.toBase = options.toBase;
      }
      let coords = options.coords ?? this.base.coords;
      for (let name in coords) {
        if (!("name" in coords[name])) {
          coords[name].name = name;
        }
      }
      this.coords = coords;
      let white2 = options.white ?? this.base.white ?? "D65";
      this.white = getWhite(white2);
      this.formats = options.formats ?? {};
      for (let name in this.formats) {
        let format = this.formats[name];
        format.type ||= "function";
        format.name ||= name;
      }
      if (!this.formats.color?.id) {
        this.formats.color = {
          ...this.formats.color ?? {},
          id: options.cssId || this.id
        };
      }
      if (options.gamutSpace) {
        this.gamutSpace = options.gamutSpace === "self" ? this : _ColorSpace.get(options.gamutSpace);
      } else {
        if (this.isPolar) {
          this.gamutSpace = this.base;
        } else {
          this.gamutSpace = this;
        }
      }
      if (this.gamutSpace.isUnbounded) {
        this.inGamut = (coords2, options2) => {
          return true;
        };
      }
      this.referred = options.referred;
      Object.defineProperty(this, "path", {
        value: getPath(this).reverse(),
        writable: false,
        enumerable: true,
        configurable: true
      });
      hooks.run("colorspace-init-end", this);
    }
    inGamut(coords, { epsilon = \u03B5$7 } = {}) {
      if (!this.equals(this.gamutSpace)) {
        coords = this.to(this.gamutSpace, coords);
        return this.gamutSpace.inGamut(coords, { epsilon });
      }
      let coordMeta = Object.values(this.coords);
      return coords.every((c4, i) => {
        let meta = coordMeta[i];
        if (meta.type !== "angle" && meta.range) {
          if (Number.isNaN(c4)) {
            return true;
          }
          let [min, max2] = meta.range;
          return (min === void 0 || c4 >= min - epsilon) && (max2 === void 0 || c4 <= max2 + epsilon);
        }
        return true;
      });
    }
    get isUnbounded() {
      return Object.values(this.coords).every((coord) => !("range" in coord));
    }
    get cssId() {
      return this.formats?.color?.id || this.id;
    }
    get isPolar() {
      for (let id in this.coords) {
        if (this.coords[id].type === "angle") {
          return true;
        }
      }
      return false;
    }
    getFormat(format) {
      if (typeof format === "object") {
        format = processFormat(format, this);
        return format;
      }
      let ret;
      if (format === "default") {
        ret = Object.values(this.formats)[0];
      } else {
        ret = this.formats[format];
      }
      if (ret) {
        ret = processFormat(ret, this);
        return ret;
      }
      return null;
    }
    /**
     * Check if this color space is the same as another color space reference.
     * Allows proxying color space objects and comparing color spaces with ids.
     * @param {string | ColorSpace} space ColorSpace object or id to compare to
     * @returns {boolean}
     */
    equals(space) {
      if (!space) {
        return false;
      }
      return this === space || this.id === space || this.id === space.id;
    }
    to(space, coords) {
      if (arguments.length === 1) {
        const color = getColor(space);
        [space, coords] = [color.space, color.coords];
      }
      space = _ColorSpace.get(space);
      if (this.equals(space)) {
        return coords;
      }
      coords = coords.map((c4) => Number.isNaN(c4) ? 0 : c4);
      let myPath = this.path;
      let otherPath = space.path;
      let connectionSpace, connectionSpaceIndex;
      for (let i = 0; i < myPath.length; i++) {
        if (myPath[i].equals(otherPath[i])) {
          connectionSpace = myPath[i];
          connectionSpaceIndex = i;
        } else {
          break;
        }
      }
      if (!connectionSpace) {
        throw new Error(`Cannot convert between color spaces ${this} and ${space}: no connection space was found`);
      }
      for (let i = myPath.length - 1; i > connectionSpaceIndex; i--) {
        coords = myPath[i].toBase(coords);
      }
      for (let i = connectionSpaceIndex + 1; i < otherPath.length; i++) {
        coords = otherPath[i].fromBase(coords);
      }
      return coords;
    }
    from(space, coords) {
      if (arguments.length === 1) {
        const color = getColor(space);
        [space, coords] = [color.space, color.coords];
      }
      space = _ColorSpace.get(space);
      return space.to(this, coords);
    }
    toString() {
      return `${this.name} (${this.id})`;
    }
    getMinCoords() {
      let ret = [];
      for (let id in this.coords) {
        let meta = this.coords[id];
        let range2 = meta.range || meta.refRange;
        ret.push(range2?.min ?? 0);
      }
      return ret;
    }
    static registry = {};
    // Returns array of unique color spaces
    static get all() {
      return [...new Set(Object.values(_ColorSpace.registry))];
    }
    static register(id, space) {
      if (arguments.length === 1) {
        space = arguments[0];
        id = space.id;
      }
      space = this.get(space);
      if (this.registry[id] && this.registry[id] !== space) {
        throw new Error(`Duplicate color space registration: '${id}'`);
      }
      this.registry[id] = space;
      if (arguments.length === 1 && space.aliases) {
        for (let alias of space.aliases) {
          this.register(alias, space);
        }
      }
      return space;
    }
    /**
     * Lookup ColorSpace object by name
     * @param {ColorSpace | string} name
     */
    static get(space, ...alternatives) {
      if (!space || space instanceof _ColorSpace) {
        return space;
      }
      let argType = type(space);
      if (argType === "string") {
        let ret = _ColorSpace.registry[space.toLowerCase()];
        if (!ret) {
          throw new TypeError(`No color space found with id = "${space}"`);
        }
        return ret;
      }
      if (alternatives.length) {
        return _ColorSpace.get(...alternatives);
      }
      throw new TypeError(`${space} is not a valid color space`);
    }
    /**
     * Get metadata about a coordinate of a color space
     *
     * @static
     * @param {Array | string} ref
     * @param {ColorSpace | string} [workingSpace]
     * @return {Object}
     */
    static resolveCoord(ref, workingSpace) {
      let coordType = type(ref);
      let space, coord;
      if (coordType === "string") {
        if (ref.includes(".")) {
          [space, coord] = ref.split(".");
        } else {
          [space, coord] = [, ref];
        }
      } else if (Array.isArray(ref)) {
        [space, coord] = ref;
      } else {
        space = ref.space;
        coord = ref.coordId;
      }
      space = _ColorSpace.get(space);
      if (!space) {
        space = workingSpace;
      }
      if (!space) {
        throw new TypeError(`Cannot resolve coordinate reference ${ref}: No color space specified and relative references are not allowed here`);
      }
      coordType = type(coord);
      if (coordType === "number" || coordType === "string" && coord >= 0) {
        let meta = Object.entries(space.coords)[coord];
        if (meta) {
          return { space, id: meta[0], index: coord, ...meta[1] };
        }
      }
      space = _ColorSpace.get(space);
      let normalizedCoord = coord.toLowerCase();
      let i = 0;
      for (let id in space.coords) {
        let meta = space.coords[id];
        if (id.toLowerCase() === normalizedCoord || meta.name?.toLowerCase() === normalizedCoord) {
          return { space, id, index: i, ...meta };
        }
        i++;
      }
      throw new TypeError(`No "${coord}" coordinate found in ${space.name}. Its coordinates are: ${Object.keys(space.coords).join(", ")}`);
    }
    static DEFAULT_FORMAT = {
      type: "functions",
      name: "color"
    };
  };
  function getPath(space) {
    let ret = [space];
    for (let s = space; s = s.base; ) {
      ret.push(s);
    }
    return ret;
  }
  function processFormat(format, { coords } = {}) {
    if (format.coords && !format.coordGrammar) {
      format.type ||= "function";
      format.name ||= "color";
      format.coordGrammar = parseCoordGrammar(format.coords);
      let coordFormats = Object.entries(coords).map(([id, coordMeta], i) => {
        let outputType = format.coordGrammar[i][0];
        let fromRange = coordMeta.range || coordMeta.refRange;
        let toRange = outputType.range, suffix = "";
        if (outputType == "<percentage>") {
          toRange = [0, 100];
          suffix = "%";
        } else if (outputType == "<angle>") {
          suffix = "deg";
        }
        return { fromRange, toRange, suffix };
      });
      format.serializeCoords = (coords2, precision) => {
        return coords2.map((c4, i) => {
          let { fromRange, toRange, suffix } = coordFormats[i];
          if (fromRange && toRange) {
            c4 = mapRange(fromRange, toRange, c4);
          }
          c4 = serializeNumber(c4, { precision, unit: suffix });
          return c4;
        });
      };
    }
    return format;
  }
  var xyz_d65 = new ColorSpace({
    id: "xyz-d65",
    name: "XYZ D65",
    coords: {
      x: { name: "X" },
      y: { name: "Y" },
      z: { name: "Z" }
    },
    white: "D65",
    formats: {
      color: {
        ids: ["xyz-d65", "xyz"]
      }
    },
    aliases: ["xyz"]
  });
  var RGBColorSpace = class extends ColorSpace {
    /**
     * Creates a new RGB ColorSpace.
     * If coords are not specified, they will use the default RGB coords.
     * Instead of `fromBase()` and `toBase()` functions,
     * you can specify to/from XYZ matrices and have `toBase()` and `fromBase()` automatically generated.
     * @param {*} options - Same options as {@link ColorSpace} plus:
     * @param {number[][]} options.toXYZ_M - Matrix to convert to XYZ
     * @param {number[][]} options.fromXYZ_M - Matrix to convert from XYZ
     */
    constructor(options) {
      if (!options.coords) {
        options.coords = {
          r: {
            range: [0, 1],
            name: "Red"
          },
          g: {
            range: [0, 1],
            name: "Green"
          },
          b: {
            range: [0, 1],
            name: "Blue"
          }
        };
      }
      if (!options.base) {
        options.base = xyz_d65;
      }
      if (options.toXYZ_M && options.fromXYZ_M) {
        options.toBase ??= (rgb) => {
          let xyz = multiplyMatrices(options.toXYZ_M, rgb);
          if (this.white !== this.base.white) {
            xyz = adapt$2(this.white, this.base.white, xyz);
          }
          return xyz;
        };
        options.fromBase ??= (xyz) => {
          xyz = adapt$2(this.base.white, this.white, xyz);
          return multiplyMatrices(options.fromXYZ_M, xyz);
        };
      }
      options.referred ??= "display";
      super(options);
    }
  };
  function getAll(color, space) {
    color = getColor(color);
    if (!space || color.space.equals(space)) {
      return color.coords.slice();
    }
    space = ColorSpace.get(space);
    return space.from(color);
  }
  function get(color, prop) {
    color = getColor(color);
    let { space, index } = ColorSpace.resolveCoord(prop, color.space);
    let coords = getAll(color, space);
    return coords[index];
  }
  function setAll(color, space, coords) {
    color = getColor(color);
    space = ColorSpace.get(space);
    color.coords = space.to(color.space, coords);
    return color;
  }
  setAll.returns = "color";
  function set(color, prop, value) {
    color = getColor(color);
    if (arguments.length === 2 && type(arguments[1]) === "object") {
      let object = arguments[1];
      for (let p2 in object) {
        set(color, p2, object[p2]);
      }
    } else {
      if (typeof value === "function") {
        value = value(get(color, prop));
      }
      let { space, index } = ColorSpace.resolveCoord(prop, color.space);
      let coords = getAll(color, space);
      coords[index] = value;
      setAll(color, space, coords);
    }
    return color;
  }
  set.returns = "color";
  var XYZ_D50 = new ColorSpace({
    id: "xyz-d50",
    name: "XYZ D50",
    white: "D50",
    base: xyz_d65,
    fromBase: (coords) => adapt$2(xyz_d65.white, "D50", coords),
    toBase: (coords) => adapt$2("D50", xyz_d65.white, coords)
  });
  var \u03B5$6 = 216 / 24389;
  var \u03B53$1 = 24 / 116;
  var \u03BA$4 = 24389 / 27;
  var white$4 = WHITES.D50;
  var lab = new ColorSpace({
    id: "lab",
    name: "Lab",
    coords: {
      l: {
        refRange: [0, 100],
        name: "Lightness"
      },
      a: {
        refRange: [-125, 125]
      },
      b: {
        refRange: [-125, 125]
      }
    },
    // Assuming XYZ is relative to D50, convert to CIE Lab
    // from CIE standard, which now defines these as a rational fraction
    white: white$4,
    base: XYZ_D50,
    // Convert D50-adapted XYX to Lab
    //  CIE 15.3:2004 section 8.2.1.1
    fromBase(XYZ) {
      let xyz = XYZ.map((value, i) => value / white$4[i]);
      let f = xyz.map((value) => value > \u03B5$6 ? Math.cbrt(value) : (\u03BA$4 * value + 16) / 116);
      return [
        116 * f[1] - 16,
        // L
        500 * (f[0] - f[1]),
        // a
        200 * (f[1] - f[2])
        // b
      ];
    },
    // Convert Lab to D50-adapted XYZ
    // Same result as CIE 15.3:2004 Appendix D although the derivation is different
    // http://www.brucelindbloom.com/index.html?Eqn_RGB_XYZ_Matrix.html
    toBase(Lab) {
      let f = [];
      f[1] = (Lab[0] + 16) / 116;
      f[0] = Lab[1] / 500 + f[1];
      f[2] = f[1] - Lab[2] / 200;
      let xyz = [
        f[0] > \u03B53$1 ? Math.pow(f[0], 3) : (116 * f[0] - 16) / \u03BA$4,
        Lab[0] > 8 ? Math.pow((Lab[0] + 16) / 116, 3) : Lab[0] / \u03BA$4,
        f[2] > \u03B53$1 ? Math.pow(f[2], 3) : (116 * f[2] - 16) / \u03BA$4
      ];
      return xyz.map((value, i) => value * white$4[i]);
    },
    formats: {
      "lab": {
        coords: ["<number> | <percentage>", "<number> | <percentage>[-1,1]", "<number> | <percentage>[-1,1]"]
      }
    }
  });
  function constrain(angle) {
    return (angle % 360 + 360) % 360;
  }
  function adjust(arc, angles) {
    if (arc === "raw") {
      return angles;
    }
    let [a1, a2] = angles.map(constrain);
    let angleDiff = a2 - a1;
    if (arc === "increasing") {
      if (angleDiff < 0) {
        a2 += 360;
      }
    } else if (arc === "decreasing") {
      if (angleDiff > 0) {
        a1 += 360;
      }
    } else if (arc === "longer") {
      if (-180 < angleDiff && angleDiff < 180) {
        if (angleDiff > 0) {
          a1 += 360;
        } else {
          a2 += 360;
        }
      }
    } else if (arc === "shorter") {
      if (angleDiff > 180) {
        a1 += 360;
      } else if (angleDiff < -180) {
        a2 += 360;
      }
    }
    return [a1, a2];
  }
  var lch = new ColorSpace({
    id: "lch",
    name: "LCH",
    coords: {
      l: {
        refRange: [0, 100],
        name: "Lightness"
      },
      c: {
        refRange: [0, 150],
        name: "Chroma"
      },
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      }
    },
    base: lab,
    fromBase(Lab) {
      let [L, a2, b2] = Lab;
      let hue;
      const \u03B52 = 0.02;
      if (Math.abs(a2) < \u03B52 && Math.abs(b2) < \u03B52) {
        hue = NaN;
      } else {
        hue = Math.atan2(b2, a2) * 180 / Math.PI;
      }
      return [
        L,
        // L is still L
        Math.sqrt(a2 ** 2 + b2 ** 2),
        // Chroma
        constrain(hue)
        // Hue, in degrees [0 to 360)
      ];
    },
    toBase(LCH) {
      let [Lightness, Chroma, Hue] = LCH;
      if (Chroma < 0) {
        Chroma = 0;
      }
      if (isNaN(Hue)) {
        Hue = 0;
      }
      return [
        Lightness,
        // L is still L
        Chroma * Math.cos(Hue * Math.PI / 180),
        // a
        Chroma * Math.sin(Hue * Math.PI / 180)
        // b
      ];
    },
    formats: {
      "lch": {
        coords: ["<number> | <percentage>", "<number> | <percentage>", "<number> | <angle>"]
      }
    }
  });
  var Gfactor = 25 ** 7;
  var \u03C0$1 = Math.PI;
  var r2d = 180 / \u03C0$1;
  var d2r$1 = \u03C0$1 / 180;
  function pow7(x) {
    const x2 = x * x;
    const x7 = x2 * x2 * x2 * x;
    return x7;
  }
  function deltaE2000(color, sample, { kL = 1, kC = 1, kH = 1 } = {}) {
    [color, sample] = getColor([color, sample]);
    let [L1, a1, b1] = lab.from(color);
    let C1 = lch.from(lab, [L1, a1, b1])[1];
    let [L2, a2, b2] = lab.from(sample);
    let C2 = lch.from(lab, [L2, a2, b2])[1];
    if (C1 < 0) {
      C1 = 0;
    }
    if (C2 < 0) {
      C2 = 0;
    }
    let Cbar = (C1 + C2) / 2;
    let C7 = pow7(Cbar);
    let G = 0.5 * (1 - Math.sqrt(C7 / (C7 + Gfactor)));
    let adash1 = (1 + G) * a1;
    let adash2 = (1 + G) * a2;
    let Cdash1 = Math.sqrt(adash1 ** 2 + b1 ** 2);
    let Cdash2 = Math.sqrt(adash2 ** 2 + b2 ** 2);
    let h1 = adash1 === 0 && b1 === 0 ? 0 : Math.atan2(b1, adash1);
    let h2 = adash2 === 0 && b2 === 0 ? 0 : Math.atan2(b2, adash2);
    if (h1 < 0) {
      h1 += 2 * \u03C0$1;
    }
    if (h2 < 0) {
      h2 += 2 * \u03C0$1;
    }
    h1 *= r2d;
    h2 *= r2d;
    let \u0394L = L2 - L1;
    let \u0394C = Cdash2 - Cdash1;
    let hdiff = h2 - h1;
    let hsum = h1 + h2;
    let habs = Math.abs(hdiff);
    let \u0394h;
    if (Cdash1 * Cdash2 === 0) {
      \u0394h = 0;
    } else if (habs <= 180) {
      \u0394h = hdiff;
    } else if (hdiff > 180) {
      \u0394h = hdiff - 360;
    } else if (hdiff < -180) {
      \u0394h = hdiff + 360;
    } else {
      defaults.warn("the unthinkable has happened");
    }
    let \u0394H = 2 * Math.sqrt(Cdash2 * Cdash1) * Math.sin(\u0394h * d2r$1 / 2);
    let Ldash = (L1 + L2) / 2;
    let Cdash = (Cdash1 + Cdash2) / 2;
    let Cdash7 = pow7(Cdash);
    let hdash;
    if (Cdash1 * Cdash2 === 0) {
      hdash = hsum;
    } else if (habs <= 180) {
      hdash = hsum / 2;
    } else if (hsum < 360) {
      hdash = (hsum + 360) / 2;
    } else {
      hdash = (hsum - 360) / 2;
    }
    let lsq = (Ldash - 50) ** 2;
    let SL = 1 + 0.015 * lsq / Math.sqrt(20 + lsq);
    let SC = 1 + 0.045 * Cdash;
    let T = 1;
    T -= 0.17 * Math.cos((hdash - 30) * d2r$1);
    T += 0.24 * Math.cos(2 * hdash * d2r$1);
    T += 0.32 * Math.cos((3 * hdash + 6) * d2r$1);
    T -= 0.2 * Math.cos((4 * hdash - 63) * d2r$1);
    let SH = 1 + 0.015 * Cdash * T;
    let \u0394\u03B8 = 30 * Math.exp(-1 * ((hdash - 275) / 25) ** 2);
    let RC = 2 * Math.sqrt(Cdash7 / (Cdash7 + Gfactor));
    let RT = -1 * Math.sin(2 * \u0394\u03B8 * d2r$1) * RC;
    let dE = (\u0394L / (kL * SL)) ** 2;
    dE += (\u0394C / (kC * SC)) ** 2;
    dE += (\u0394H / (kH * SH)) ** 2;
    dE += RT * (\u0394C / (kC * SC)) * (\u0394H / (kH * SH));
    return Math.sqrt(dE);
  }
  var XYZtoLMS_M$1 = [
    [0.819022437996703, 0.3619062600528904, -0.1288737815209879],
    [0.0329836539323885, 0.9292868615863434, 0.0361446663506424],
    [0.0481771893596242, 0.2642395317527308, 0.6335478284694309]
  ];
  var LMStoXYZ_M$1 = [
    [1.2268798758459243, -0.5578149944602171, 0.2813910456659647],
    [-0.0405757452148008, 1.112286803280317, -0.0717110580655164],
    [-0.0763729366746601, -0.4214933324022432, 1.5869240198367816]
  ];
  var LMStoLab_M = [
    [0.210454268309314, 0.7936177747023054, -0.0040720430116193],
    [1.9779985324311684, -2.42859224204858, 0.450593709617411],
    [0.0259040424655478, 0.7827717124575296, -0.8086757549230774]
  ];
  var LabtoLMS_M = [
    [1, 0.3963377773761749, 0.2158037573099136],
    [1, -0.1055613458156586, -0.0638541728258133],
    [1, -0.0894841775298119, -1.2914855480194092]
  ];
  var OKLab = new ColorSpace({
    id: "oklab",
    name: "Oklab",
    coords: {
      l: {
        refRange: [0, 1],
        name: "Lightness"
      },
      a: {
        refRange: [-0.4, 0.4]
      },
      b: {
        refRange: [-0.4, 0.4]
      }
    },
    // Note that XYZ is relative to D65
    white: "D65",
    base: xyz_d65,
    fromBase(XYZ) {
      let LMS = multiplyMatrices(XYZtoLMS_M$1, XYZ);
      let LMSg = LMS.map((val) => Math.cbrt(val));
      return multiplyMatrices(LMStoLab_M, LMSg);
    },
    toBase(OKLab2) {
      let LMSg = multiplyMatrices(LabtoLMS_M, OKLab2);
      let LMS = LMSg.map((val) => val ** 3);
      return multiplyMatrices(LMStoXYZ_M$1, LMS);
    },
    formats: {
      "oklab": {
        coords: ["<percentage> | <number>", "<number> | <percentage>[-1,1]", "<number> | <percentage>[-1,1]"]
      }
    }
  });
  function deltaEOK(color, sample) {
    [color, sample] = getColor([color, sample]);
    let [L1, a1, b1] = OKLab.from(color);
    let [L2, a2, b2] = OKLab.from(sample);
    let \u0394L = L1 - L2;
    let \u0394a = a1 - a2;
    let \u0394b = b1 - b2;
    return Math.sqrt(\u0394L ** 2 + \u0394a ** 2 + \u0394b ** 2);
  }
  var \u03B5$5 = 75e-6;
  function inGamut(color, space, { epsilon = \u03B5$5 } = {}) {
    color = getColor(color);
    if (!space) {
      space = color.space;
    }
    space = ColorSpace.get(space);
    let coords = color.coords;
    if (space !== color.space) {
      coords = space.from(color);
    }
    return space.inGamut(coords, { epsilon });
  }
  function clone(color) {
    return {
      space: color.space,
      coords: color.coords.slice(),
      alpha: color.alpha
    };
  }
  function distance(color1, color2, space = "lab") {
    space = ColorSpace.get(space);
    let coords1 = space.from(color1);
    let coords2 = space.from(color2);
    return Math.sqrt(coords1.reduce((acc, c12, i) => {
      let c22 = coords2[i];
      if (isNaN(c12) || isNaN(c22)) {
        return acc;
      }
      return acc + (c22 - c12) ** 2;
    }, 0));
  }
  function deltaE76(color, sample) {
    return distance(color, sample, "lab");
  }
  var \u03C0 = Math.PI;
  var d2r = \u03C0 / 180;
  function deltaECMC(color, sample, { l = 2, c: c4 = 1 } = {}) {
    [color, sample] = getColor([color, sample]);
    let [L1, a1, b1] = lab.from(color);
    let [, C1, H1] = lch.from(lab, [L1, a1, b1]);
    let [L2, a2, b2] = lab.from(sample);
    let C2 = lch.from(lab, [L2, a2, b2])[1];
    if (C1 < 0) {
      C1 = 0;
    }
    if (C2 < 0) {
      C2 = 0;
    }
    let \u0394L = L1 - L2;
    let \u0394C = C1 - C2;
    let \u0394a = a1 - a2;
    let \u0394b = b1 - b2;
    let H2 = \u0394a ** 2 + \u0394b ** 2 - \u0394C ** 2;
    let SL = 0.511;
    if (L1 >= 16) {
      SL = 0.040975 * L1 / (1 + 0.01765 * L1);
    }
    let SC = 0.0638 * C1 / (1 + 0.0131 * C1) + 0.638;
    let T;
    if (Number.isNaN(H1)) {
      H1 = 0;
    }
    if (H1 >= 164 && H1 <= 345) {
      T = 0.56 + Math.abs(0.2 * Math.cos((H1 + 168) * d2r));
    } else {
      T = 0.36 + Math.abs(0.4 * Math.cos((H1 + 35) * d2r));
    }
    let C4 = Math.pow(C1, 4);
    let F = Math.sqrt(C4 / (C4 + 1900));
    let SH = SC * (F * T + 1 - F);
    let dE = (\u0394L / (l * SL)) ** 2;
    dE += (\u0394C / (c4 * SC)) ** 2;
    dE += H2 / SH ** 2;
    return Math.sqrt(dE);
  }
  var Yw$1 = 203;
  var XYZ_Abs_D65 = new ColorSpace({
    // Absolute CIE XYZ, with a D65 whitepoint,
    // as used in most HDR colorspaces as a starting point.
    // SDR spaces are converted per BT.2048
    // so that diffuse, media white is 203 cd/m²
    id: "xyz-abs-d65",
    cssId: "--xyz-abs-d65",
    name: "Absolute XYZ D65",
    coords: {
      x: {
        refRange: [0, 9504.7],
        name: "Xa"
      },
      y: {
        refRange: [0, 1e4],
        name: "Ya"
      },
      z: {
        refRange: [0, 10888.3],
        name: "Za"
      }
    },
    base: xyz_d65,
    fromBase(XYZ) {
      return XYZ.map((v) => Math.max(v * Yw$1, 0));
    },
    toBase(AbsXYZ) {
      return AbsXYZ.map((v) => Math.max(v / Yw$1, 0));
    }
  });
  var b$1 = 1.15;
  var g = 0.66;
  var n$1 = 2610 / 2 ** 14;
  var ninv$1 = 2 ** 14 / 2610;
  var c1$2 = 3424 / 2 ** 12;
  var c2$2 = 2413 / 2 ** 7;
  var c3$2 = 2392 / 2 ** 7;
  var p = 1.7 * 2523 / 2 ** 5;
  var pinv = 2 ** 5 / (1.7 * 2523);
  var d = -0.56;
  var d0 = 16295499532821565e-27;
  var XYZtoCone_M = [
    [0.41478972, 0.579999, 0.014648],
    [-0.20151, 1.120649, 0.0531008],
    [-0.0166008, 0.2648, 0.6684799]
  ];
  var ConetoXYZ_M = [
    [1.9242264357876067, -1.0047923125953657, 0.037651404030618],
    [0.35031676209499907, 0.7264811939316552, -0.06538442294808501],
    [-0.09098281098284752, -0.3127282905230739, 1.5227665613052603]
  ];
  var ConetoIab_M = [
    [0.5, 0.5, 0],
    [3.524, -4.066708, 0.542708],
    [0.199076, 1.096799, -1.295875]
  ];
  var IabtoCone_M = [
    [1, 0.1386050432715393, 0.05804731615611886],
    [0.9999999999999999, -0.1386050432715393, -0.05804731615611886],
    [0.9999999999999998, -0.09601924202631895, -0.8118918960560388]
  ];
  var Jzazbz = new ColorSpace({
    id: "jzazbz",
    name: "Jzazbz",
    coords: {
      jz: {
        refRange: [0, 1],
        name: "Jz"
      },
      az: {
        refRange: [-0.5, 0.5]
      },
      bz: {
        refRange: [-0.5, 0.5]
      }
    },
    base: XYZ_Abs_D65,
    fromBase(XYZ) {
      let [Xa, Ya, Za] = XYZ;
      let Xm = b$1 * Xa - (b$1 - 1) * Za;
      let Ym = g * Ya - (g - 1) * Xa;
      let LMS = multiplyMatrices(XYZtoCone_M, [Xm, Ym, Za]);
      let PQLMS = LMS.map(function(val) {
        let num = c1$2 + c2$2 * (val / 1e4) ** n$1;
        let denom = 1 + c3$2 * (val / 1e4) ** n$1;
        return (num / denom) ** p;
      });
      let [Iz, az, bz] = multiplyMatrices(ConetoIab_M, PQLMS);
      let Jz = (1 + d) * Iz / (1 + d * Iz) - d0;
      return [Jz, az, bz];
    },
    toBase(Jzazbz2) {
      let [Jz, az, bz] = Jzazbz2;
      let Iz = (Jz + d0) / (1 + d - d * (Jz + d0));
      let PQLMS = multiplyMatrices(IabtoCone_M, [Iz, az, bz]);
      let LMS = PQLMS.map(function(val) {
        let num = c1$2 - val ** pinv;
        let denom = c3$2 * val ** pinv - c2$2;
        let x = 1e4 * (num / denom) ** ninv$1;
        return x;
      });
      let [Xm, Ym, Za] = multiplyMatrices(ConetoXYZ_M, LMS);
      let Xa = (Xm + (b$1 - 1) * Za) / b$1;
      let Ya = (Ym + (g - 1) * Xa) / g;
      return [Xa, Ya, Za];
    },
    formats: {
      // https://drafts.csswg.org/css-color-hdr/#Jzazbz
      "color": {
        coords: ["<number> | <percentage>", "<number> | <percentage>[-1,1]", "<number> | <percentage>[-1,1]"]
      }
    }
  });
  var jzczhz = new ColorSpace({
    id: "jzczhz",
    name: "JzCzHz",
    coords: {
      jz: {
        refRange: [0, 1],
        name: "Jz"
      },
      cz: {
        refRange: [0, 1],
        name: "Chroma"
      },
      hz: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      }
    },
    base: Jzazbz,
    fromBase(jzazbz) {
      let [Jz, az, bz] = jzazbz;
      let hue;
      const \u03B52 = 2e-4;
      if (Math.abs(az) < \u03B52 && Math.abs(bz) < \u03B52) {
        hue = NaN;
      } else {
        hue = Math.atan2(bz, az) * 180 / Math.PI;
      }
      return [
        Jz,
        // Jz is still Jz
        Math.sqrt(az ** 2 + bz ** 2),
        // Chroma
        constrain(hue)
        // Hue, in degrees [0 to 360)
      ];
    },
    toBase(jzczhz2) {
      return [
        jzczhz2[0],
        // Jz is still Jz
        jzczhz2[1] * Math.cos(jzczhz2[2] * Math.PI / 180),
        // az
        jzczhz2[1] * Math.sin(jzczhz2[2] * Math.PI / 180)
        // bz
      ];
    }
  });
  function deltaEJz(color, sample) {
    [color, sample] = getColor([color, sample]);
    let [Jz1, Cz1, Hz1] = jzczhz.from(color);
    let [Jz2, Cz2, Hz2] = jzczhz.from(sample);
    let \u0394J = Jz1 - Jz2;
    let \u0394C = Cz1 - Cz2;
    if (Number.isNaN(Hz1) && Number.isNaN(Hz2)) {
      Hz1 = 0;
      Hz2 = 0;
    } else if (Number.isNaN(Hz1)) {
      Hz1 = Hz2;
    } else if (Number.isNaN(Hz2)) {
      Hz2 = Hz1;
    }
    let \u0394h = Hz1 - Hz2;
    let \u0394H = 2 * Math.sqrt(Cz1 * Cz2) * Math.sin(\u0394h / 2 * (Math.PI / 180));
    return Math.sqrt(\u0394J ** 2 + \u0394C ** 2 + \u0394H ** 2);
  }
  var c1$1 = 3424 / 4096;
  var c2$1 = 2413 / 128;
  var c3$1 = 2392 / 128;
  var m1$1 = 2610 / 16384;
  var m2 = 2523 / 32;
  var im1 = 16384 / 2610;
  var im2 = 32 / 2523;
  var XYZtoLMS_M = [
    [0.3592832590121217, 0.6976051147779502, -0.035891593232029],
    [-0.1920808463704993, 1.100476797037432, 0.0753748658519118],
    [0.0070797844607479, 0.0748396662186362, 0.8433265453898765]
  ];
  var LMStoIPT_M = [
    [2048 / 4096, 2048 / 4096, 0],
    [6610 / 4096, -13613 / 4096, 7003 / 4096],
    [17933 / 4096, -17390 / 4096, -543 / 4096]
  ];
  var IPTtoLMS_M = [
    [0.9999999999999998, 0.0086090370379328, 0.111029625003026],
    [0.9999999999999998, -0.0086090370379328, -0.1110296250030259],
    [0.9999999999999998, 0.5600313357106791, -0.3206271749873188]
  ];
  var LMStoXYZ_M = [
    [2.0701522183894223, -1.3263473389671563, 0.2066510476294053],
    [0.3647385209748072, 0.6805660249472273, -0.0453045459220347],
    [-0.0497472075358123, -0.0492609666966131, 1.1880659249923042]
  ];
  var ictcp = new ColorSpace({
    id: "ictcp",
    name: "ICTCP",
    // From BT.2100-2 page 7:
    // During production, signal values are expected to exceed the
    // range E′ = [0.0 : 1.0]. This provides processing headroom and avoids
    // signal degradation during cascaded processing. Such values of E′,
    // below 0.0 or exceeding 1.0, should not be clipped during production
    // and exchange.
    // Values below 0.0 should not be clipped in reference displays (even
    // though they represent “negative” light) to allow the black level of
    // the signal (LB) to be properly set using test signals known as “PLUGE”
    coords: {
      i: {
        refRange: [0, 1],
        // Constant luminance,
        name: "I"
      },
      ct: {
        refRange: [-0.5, 0.5],
        // Full BT.2020 gamut in range [-0.5, 0.5]
        name: "CT"
      },
      cp: {
        refRange: [-0.5, 0.5],
        name: "CP"
      }
    },
    base: XYZ_Abs_D65,
    fromBase(XYZ) {
      let LMS = multiplyMatrices(XYZtoLMS_M, XYZ);
      return LMStoICtCp(LMS);
    },
    toBase(ICtCp) {
      let LMS = ICtCptoLMS(ICtCp);
      return multiplyMatrices(LMStoXYZ_M, LMS);
    }
  });
  function LMStoICtCp(LMS) {
    let PQLMS = LMS.map(function(val) {
      let num = c1$1 + c2$1 * (val / 1e4) ** m1$1;
      let denom = 1 + c3$1 * (val / 1e4) ** m1$1;
      return (num / denom) ** m2;
    });
    return multiplyMatrices(LMStoIPT_M, PQLMS);
  }
  function ICtCptoLMS(ICtCp) {
    let PQLMS = multiplyMatrices(IPTtoLMS_M, ICtCp);
    let LMS = PQLMS.map(function(val) {
      let num = Math.max(val ** im2 - c1$1, 0);
      let denom = c2$1 - c3$1 * val ** im2;
      return 1e4 * (num / denom) ** im1;
    });
    return LMS;
  }
  function deltaEITP(color, sample) {
    [color, sample] = getColor([color, sample]);
    let [I1, T1, P1] = ictcp.from(color);
    let [I2, T2, P2] = ictcp.from(sample);
    return 720 * Math.sqrt((I1 - I2) ** 2 + 0.25 * (T1 - T2) ** 2 + (P1 - P2) ** 2);
  }
  var white$3 = WHITES.D65;
  var adaptedCoef = 0.42;
  var adaptedCoefInv = 1 / adaptedCoef;
  var tau = 2 * Math.PI;
  var cat16 = [
    [0.401288, 0.650173, -0.051461],
    [-0.250268, 1.204414, 0.045854],
    [-2079e-6, 0.048952, 0.953127]
  ];
  var cat16Inv = [
    [1.8620678550872327, -1.0112546305316843, 0.14918677544445175],
    [0.38752654323613717, 0.6214474419314753, -0.008973985167612518],
    [-0.015841498849333856, -0.03412293802851557, 1.0499644368778496]
  ];
  var m1 = [
    [460, 451, 288],
    [460, -891, -261],
    [460, -220, -6300]
  ];
  var surroundMap = {
    dark: [0.8, 0.525, 0.8],
    dim: [0.9, 0.59, 0.9],
    average: [1, 0.69, 1]
  };
  var hueQuadMap = {
    // Red, Yellow, Green, Blue, Red
    h: [20.14, 90, 164.25, 237.53, 380.14],
    e: [0.8, 0.7, 1, 1.2, 0.8],
    H: [0, 100, 200, 300, 400]
  };
  var rad2deg = 180 / Math.PI;
  var deg2rad$1 = Math.PI / 180;
  function adapt$1(coords, fl) {
    const temp = coords.map((c4) => {
      const x = spow(fl * Math.abs(c4) * 0.01, adaptedCoef);
      return 400 * copySign(x, c4) / (x + 27.13);
    });
    return temp;
  }
  function unadapt(adapted, fl) {
    const constant = 100 / fl * 27.13 ** adaptedCoefInv;
    return adapted.map((c4) => {
      const cabs = Math.abs(c4);
      return copySign(constant * spow(cabs / (400 - cabs), adaptedCoefInv), c4);
    });
  }
  function hueQuadrature(h) {
    let hp = constrain(h);
    if (hp <= hueQuadMap.h[0]) {
      hp += 360;
    }
    const i = bisectLeft(hueQuadMap.h, hp) - 1;
    const [hi, hii] = hueQuadMap.h.slice(i, i + 2);
    const [ei, eii] = hueQuadMap.e.slice(i, i + 2);
    const Hi = hueQuadMap.H[i];
    const t = (hp - hi) / ei;
    return Hi + 100 * t / (t + (hii - hp) / eii);
  }
  function invHueQuadrature(H) {
    let Hp = (H % 400 + 400) % 400;
    const i = Math.floor(0.01 * Hp);
    Hp = Hp % 100;
    const [hi, hii] = hueQuadMap.h.slice(i, i + 2);
    const [ei, eii] = hueQuadMap.e.slice(i, i + 2);
    return constrain(
      (Hp * (eii * hi - ei * hii) - 100 * hi * eii) / (Hp * (eii - ei) - 100 * eii)
    );
  }
  function environment(refWhite, adaptingLuminance, backgroundLuminance, surround, discounting) {
    const env = {};
    env.discounting = discounting;
    env.refWhite = refWhite;
    env.surround = surround;
    const xyzW = refWhite.map((c4) => {
      return c4 * 100;
    });
    env.la = adaptingLuminance;
    env.yb = backgroundLuminance;
    const yw = xyzW[1];
    const rgbW = multiplyMatrices(cat16, xyzW);
    surround = surroundMap[env.surround];
    const f = surround[0];
    env.c = surround[1];
    env.nc = surround[2];
    const k = 1 / (5 * env.la + 1);
    const k4 = k ** 4;
    env.fl = k4 * env.la + 0.1 * (1 - k4) * (1 - k4) * Math.cbrt(5 * env.la);
    env.flRoot = env.fl ** 0.25;
    env.n = env.yb / yw;
    env.z = 1.48 + Math.sqrt(env.n);
    env.nbb = 0.725 * env.n ** -0.2;
    env.ncb = env.nbb;
    const d2 = discounting ? 1 : Math.max(
      Math.min(f * (1 - 1 / 3.6 * Math.exp((-env.la - 42) / 92)), 1),
      0
    );
    env.dRgb = rgbW.map((c4) => {
      return interpolate(1, yw / c4, d2);
    });
    env.dRgbInv = env.dRgb.map((c4) => {
      return 1 / c4;
    });
    const rgbCW = rgbW.map((c4, i) => {
      return c4 * env.dRgb[i];
    });
    const rgbAW = adapt$1(rgbCW, env.fl);
    env.aW = env.nbb * (2 * rgbAW[0] + rgbAW[1] + 0.05 * rgbAW[2]);
    return env;
  }
  var viewingConditions$1 = environment(
    white$3,
    64 / Math.PI * 0.2,
    20,
    "average",
    false
  );
  function fromCam16(cam162, env) {
    if (!(cam162.J !== void 0 ^ cam162.Q !== void 0)) {
      throw new Error("Conversion requires one and only one: 'J' or 'Q'");
    }
    if (!(cam162.C !== void 0 ^ cam162.M !== void 0 ^ cam162.s !== void 0)) {
      throw new Error("Conversion requires one and only one: 'C', 'M' or 's'");
    }
    if (!(cam162.h !== void 0 ^ cam162.H !== void 0)) {
      throw new Error("Conversion requires one and only one: 'h' or 'H'");
    }
    if (cam162.J === 0 || cam162.Q === 0) {
      return [0, 0, 0];
    }
    let hRad = 0;
    if (cam162.h !== void 0) {
      hRad = constrain(cam162.h) * deg2rad$1;
    } else {
      hRad = invHueQuadrature(cam162.H) * deg2rad$1;
    }
    const cosh = Math.cos(hRad);
    const sinh = Math.sin(hRad);
    let Jroot = 0;
    if (cam162.J !== void 0) {
      Jroot = spow(cam162.J, 1 / 2) * 0.1;
    } else if (cam162.Q !== void 0) {
      Jroot = 0.25 * env.c * cam162.Q / ((env.aW + 4) * env.flRoot);
    }
    let alpha = 0;
    if (cam162.C !== void 0) {
      alpha = cam162.C / Jroot;
    } else if (cam162.M !== void 0) {
      alpha = cam162.M / env.flRoot / Jroot;
    } else if (cam162.s !== void 0) {
      alpha = 4e-4 * cam162.s ** 2 * (env.aW + 4) / env.c;
    }
    const t = spow(
      alpha * Math.pow(1.64 - Math.pow(0.29, env.n), -0.73),
      10 / 9
    );
    const et = 0.25 * (Math.cos(hRad + 2) + 3.8);
    const A = env.aW * spow(Jroot, 2 / env.c / env.z);
    const p1 = 5e4 / 13 * env.nc * env.ncb * et;
    const p2 = A / env.nbb;
    const r = 23 * (p2 + 0.305) * zdiv(t, 23 * p1 + t * (11 * cosh + 108 * sinh));
    const a2 = r * cosh;
    const b2 = r * sinh;
    const rgb_c = unadapt(
      multiplyMatrices(m1, [p2, a2, b2]).map((c4) => {
        return c4 * 1 / 1403;
      }),
      env.fl
    );
    return multiplyMatrices(
      cat16Inv,
      rgb_c.map((c4, i) => {
        return c4 * env.dRgbInv[i];
      })
    ).map((c4) => {
      return c4 / 100;
    });
  }
  function toCam16(xyzd65, env) {
    const xyz100 = xyzd65.map((c4) => {
      return c4 * 100;
    });
    const rgbA = adapt$1(
      multiplyMatrices(cat16, xyz100).map((c4, i) => {
        return c4 * env.dRgb[i];
      }),
      env.fl
    );
    const a2 = rgbA[0] + (-12 * rgbA[1] + rgbA[2]) / 11;
    const b2 = (rgbA[0] + rgbA[1] - 2 * rgbA[2]) / 9;
    const hRad = (Math.atan2(b2, a2) % tau + tau) % tau;
    const et = 0.25 * (Math.cos(hRad + 2) + 3.8);
    const t = 5e4 / 13 * env.nc * env.ncb * zdiv(
      et * Math.sqrt(a2 ** 2 + b2 ** 2),
      rgbA[0] + rgbA[1] + 1.05 * rgbA[2] + 0.305
    );
    const alpha = spow(t, 0.9) * Math.pow(1.64 - Math.pow(0.29, env.n), 0.73);
    const A = env.nbb * (2 * rgbA[0] + rgbA[1] + 0.05 * rgbA[2]);
    const Jroot = spow(A / env.aW, 0.5 * env.c * env.z);
    const J = 100 * spow(Jroot, 2);
    const Q = 4 / env.c * Jroot * (env.aW + 4) * env.flRoot;
    const C = alpha * Jroot;
    const M = C * env.flRoot;
    const h = constrain(hRad * rad2deg);
    const H = hueQuadrature(h);
    const s = 50 * spow(env.c * alpha / (env.aW + 4), 1 / 2);
    return { J, C, h, s, Q, M, H };
  }
  var cam16 = new ColorSpace({
    id: "cam16-jmh",
    cssId: "--cam16-jmh",
    name: "CAM16-JMh",
    coords: {
      j: {
        refRange: [0, 100],
        name: "J"
      },
      m: {
        refRange: [0, 105],
        name: "Colorfulness"
      },
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      }
    },
    base: xyz_d65,
    fromBase(xyz) {
      const cam162 = toCam16(xyz, viewingConditions$1);
      return [cam162.J, cam162.M, cam162.h];
    },
    toBase(cam162) {
      return fromCam16(
        { J: cam162[0], M: cam162[1], h: cam162[2] },
        viewingConditions$1
      );
    }
  });
  var white$2 = WHITES.D65;
  var \u03B5$4 = 216 / 24389;
  var \u03BA$3 = 24389 / 27;
  function toLstar(y) {
    const fy = y > \u03B5$4 ? Math.cbrt(y) : (\u03BA$3 * y + 16) / 116;
    return 116 * fy - 16;
  }
  function fromLstar(lstar) {
    return lstar > 8 ? Math.pow((lstar + 16) / 116, 3) : lstar / \u03BA$3;
  }
  function fromHct(coords, env) {
    let [h, c4, t] = coords;
    let xyz = [];
    let j = 0;
    if (t === 0) {
      return [0, 0, 0];
    }
    let y = fromLstar(t);
    if (t > 0) {
      j = 0.00379058511492914 * t ** 2 + 0.608983189401032 * t + 0.9155088574762233;
    } else {
      j = 9514440756550361e-21 * t ** 2 + 0.08693057439788597 * t - 21.928975842194614;
    }
    const threshold = 2e-12;
    const max_attempts = 15;
    let attempt = 0;
    let last2 = Infinity;
    while (attempt <= max_attempts) {
      xyz = fromCam16({ J: j, C: c4, h }, env);
      const delta = Math.abs(xyz[1] - y);
      if (delta < last2) {
        if (delta <= threshold) {
          return xyz;
        }
        last2 = delta;
      }
      j = j - (xyz[1] - y) * j / (2 * xyz[1]);
      attempt += 1;
    }
    return fromCam16({ J: j, C: c4, h }, env);
  }
  function toHct(xyz, env) {
    const t = toLstar(xyz[1]);
    if (t === 0) {
      return [0, 0, 0];
    }
    const cam162 = toCam16(xyz, viewingConditions);
    return [constrain(cam162.h), cam162.C, t];
  }
  var viewingConditions = environment(
    white$2,
    200 / Math.PI * fromLstar(50),
    fromLstar(50) * 100,
    "average",
    false
  );
  var hct = new ColorSpace({
    id: "hct",
    name: "HCT",
    coords: {
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      },
      c: {
        refRange: [0, 145],
        name: "Colorfulness"
      },
      t: {
        refRange: [0, 100],
        name: "Tone"
      }
    },
    base: xyz_d65,
    fromBase(xyz) {
      return toHct(xyz);
    },
    toBase(hct2) {
      return fromHct(hct2, viewingConditions);
    },
    formats: {
      color: {
        id: "--hct",
        coords: ["<number> | <angle>", "<percentage> | <number>", "<percentage> | <number>"]
      }
    }
  });
  var deg2rad = Math.PI / 180;
  var ucsCoeff = [1, 7e-3, 0.0228];
  function convertUcsAb(coords) {
    if (coords[1] < 0) {
      coords = hct.fromBase(hct.toBase(coords));
    }
    const M = Math.log(Math.max(1 + ucsCoeff[2] * coords[1] * viewingConditions.flRoot, 1)) / ucsCoeff[2];
    const hrad = coords[0] * deg2rad;
    const a2 = M * Math.cos(hrad);
    const b2 = M * Math.sin(hrad);
    return [coords[2], a2, b2];
  }
  function deltaEHCT(color, sample) {
    [color, sample] = getColor([color, sample]);
    let [t1, a1, b1] = convertUcsAb(hct.from(color));
    let [t2, a2, b2] = convertUcsAb(hct.from(sample));
    return Math.sqrt((t1 - t2) ** 2 + (a1 - a2) ** 2 + (b1 - b2) ** 2);
  }
  var deltaEMethods = {
    deltaE76,
    deltaECMC,
    deltaE2000,
    deltaEJz,
    deltaEITP,
    deltaEOK,
    deltaEHCT
  };
  function calcEpsilon(jnd) {
    const order = !jnd ? 0 : Math.floor(Math.log10(Math.abs(jnd)));
    return Math.max(parseFloat(`1e${order - 2}`), 1e-6);
  }
  var GMAPPRESET = {
    "hct": {
      method: "hct.c",
      jnd: 2,
      deltaEMethod: "hct",
      blackWhiteClamp: {}
    },
    "hct-tonal": {
      method: "hct.c",
      jnd: 0,
      deltaEMethod: "hct",
      blackWhiteClamp: { channel: "hct.t", min: 0, max: 100 }
    }
  };
  function toGamut(color, {
    method = defaults.gamut_mapping,
    space = void 0,
    deltaEMethod = "",
    jnd = 2,
    blackWhiteClamp = {}
  } = {}) {
    color = getColor(color);
    if (isString(arguments[1])) {
      space = arguments[1];
    } else if (!space) {
      space = color.space;
    }
    space = ColorSpace.get(space);
    if (inGamut(color, space, { epsilon: 0 })) {
      return color;
    }
    let spaceColor;
    if (method === "css") {
      spaceColor = toGamutCSS(color, { space });
    } else {
      if (method !== "clip" && !inGamut(color, space)) {
        if (Object.prototype.hasOwnProperty.call(GMAPPRESET, method)) {
          ({ method, jnd, deltaEMethod, blackWhiteClamp } = GMAPPRESET[method]);
        }
        let de = deltaE2000;
        if (deltaEMethod !== "") {
          for (let m3 in deltaEMethods) {
            if ("deltae" + deltaEMethod.toLowerCase() === m3.toLowerCase()) {
              de = deltaEMethods[m3];
              break;
            }
          }
        }
        let clipped = toGamut(to(color, space), { method: "clip", space });
        if (de(color, clipped) > jnd) {
          if (Object.keys(blackWhiteClamp).length === 3) {
            let channelMeta = ColorSpace.resolveCoord(blackWhiteClamp.channel);
            let channel = get(to(color, channelMeta.space), channelMeta.id);
            if (isNone(channel)) {
              channel = 0;
            }
            if (channel >= blackWhiteClamp.max) {
              return to({ space: "xyz-d65", coords: WHITES["D65"] }, color.space);
            } else if (channel <= blackWhiteClamp.min) {
              return to({ space: "xyz-d65", coords: [0, 0, 0] }, color.space);
            }
          }
          let coordMeta = ColorSpace.resolveCoord(method);
          let mapSpace = coordMeta.space;
          let coordId = coordMeta.id;
          let mappedColor = to(color, mapSpace);
          mappedColor.coords.forEach((c4, i) => {
            if (isNone(c4)) {
              mappedColor.coords[i] = 0;
            }
          });
          let bounds = coordMeta.range || coordMeta.refRange;
          let min = bounds[0];
          let \u03B52 = calcEpsilon(jnd);
          let low = min;
          let high = get(mappedColor, coordId);
          while (high - low > \u03B52) {
            let clipped2 = clone(mappedColor);
            clipped2 = toGamut(clipped2, { space, method: "clip" });
            let deltaE2 = de(mappedColor, clipped2);
            if (deltaE2 - jnd < \u03B52) {
              low = get(mappedColor, coordId);
            } else {
              high = get(mappedColor, coordId);
            }
            set(mappedColor, coordId, (low + high) / 2);
          }
          spaceColor = to(mappedColor, space);
        } else {
          spaceColor = clipped;
        }
      } else {
        spaceColor = to(color, space);
      }
      if (method === "clip" || !inGamut(spaceColor, space, { epsilon: 0 })) {
        let bounds = Object.values(space.coords).map((c4) => c4.range || []);
        spaceColor.coords = spaceColor.coords.map((c4, i) => {
          let [min, max2] = bounds[i];
          if (min !== void 0) {
            c4 = Math.max(min, c4);
          }
          if (max2 !== void 0) {
            c4 = Math.min(c4, max2);
          }
          return c4;
        });
      }
    }
    if (space !== color.space) {
      spaceColor = to(spaceColor, color.space);
    }
    color.coords = spaceColor.coords;
    return color;
  }
  toGamut.returns = "color";
  var COLORS = {
    WHITE: { space: OKLab, coords: [1, 0, 0] },
    BLACK: { space: OKLab, coords: [0, 0, 0] }
  };
  function toGamutCSS(origin, { space } = {}) {
    const JND = 0.02;
    const \u03B52 = 1e-4;
    origin = getColor(origin);
    if (!space) {
      space = origin.space;
    }
    space = ColorSpace.get(space);
    const oklchSpace = ColorSpace.get("oklch");
    if (space.isUnbounded) {
      return to(origin, space);
    }
    const origin_OKLCH = to(origin, oklchSpace);
    let L = origin_OKLCH.coords[0];
    if (L >= 1) {
      const white2 = to(COLORS.WHITE, space);
      white2.alpha = origin.alpha;
      return to(white2, space);
    }
    if (L <= 0) {
      const black = to(COLORS.BLACK, space);
      black.alpha = origin.alpha;
      return to(black, space);
    }
    if (inGamut(origin_OKLCH, space, { epsilon: 0 })) {
      return to(origin_OKLCH, space);
    }
    function clip(_color) {
      const destColor = to(_color, space);
      const spaceCoords = Object.values(space.coords);
      destColor.coords = destColor.coords.map((coord, index) => {
        if ("range" in spaceCoords[index]) {
          const [min2, max3] = spaceCoords[index].range;
          return clamp(min2, coord, max3);
        }
        return coord;
      });
      return destColor;
    }
    let min = 0;
    let max2 = origin_OKLCH.coords[1];
    let min_inGamut = true;
    let current = clone(origin_OKLCH);
    let clipped = clip(current);
    let E = deltaEOK(clipped, current);
    if (E < JND) {
      return clipped;
    }
    while (max2 - min > \u03B52) {
      const chroma = (min + max2) / 2;
      current.coords[1] = chroma;
      if (min_inGamut && inGamut(current, space, { epsilon: 0 })) {
        min = chroma;
      } else {
        clipped = clip(current);
        E = deltaEOK(clipped, current);
        if (E < JND) {
          if (JND - E < \u03B52) {
            break;
          } else {
            min_inGamut = false;
            min = chroma;
          }
        } else {
          max2 = chroma;
        }
      }
    }
    return clipped;
  }
  function to(color, space, { inGamut: inGamut2 } = {}) {
    color = getColor(color);
    space = ColorSpace.get(space);
    let coords = space.from(color);
    let ret = { space, coords, alpha: color.alpha };
    if (inGamut2) {
      ret = toGamut(ret, inGamut2 === true ? void 0 : inGamut2);
    }
    return ret;
  }
  to.returns = "color";
  function serialize(color, {
    precision = defaults.precision,
    format = "default",
    inGamut: inGamut$1 = true,
    ...customOptions
  } = {}) {
    let ret;
    color = getColor(color);
    let formatId = format;
    format = color.space.getFormat(format) ?? color.space.getFormat("default") ?? ColorSpace.DEFAULT_FORMAT;
    let coords = color.coords.slice();
    inGamut$1 ||= format.toGamut;
    if (inGamut$1 && !inGamut(color)) {
      coords = toGamut(clone(color), inGamut$1 === true ? void 0 : inGamut$1).coords;
    }
    if (format.type === "custom") {
      customOptions.precision = precision;
      if (format.serialize) {
        ret = format.serialize(coords, color.alpha, customOptions);
      } else {
        throw new TypeError(`format ${formatId} can only be used to parse colors, not for serialization`);
      }
    } else {
      let name = format.name || "color";
      if (format.serializeCoords) {
        coords = format.serializeCoords(coords, precision);
      } else {
        if (precision !== null) {
          coords = coords.map((c4) => {
            return serializeNumber(c4, { precision });
          });
        }
      }
      let args = [...coords];
      if (name === "color") {
        let cssId = format.id || format.ids?.[0] || color.space.id;
        args.unshift(cssId);
      }
      let alpha = color.alpha;
      if (precision !== null) {
        alpha = serializeNumber(alpha, { precision });
      }
      let strAlpha = color.alpha >= 1 || format.noAlpha ? "" : `${format.commas ? "," : " /"} ${alpha}`;
      ret = `${name}(${args.join(format.commas ? ", " : " ")}${strAlpha})`;
    }
    return ret;
  }
  var toXYZ_M$5 = [
    [0.6369580483012914, 0.14461690358620832, 0.1688809751641721],
    [0.2627002120112671, 0.6779980715188708, 0.05930171646986196],
    [0, 0.028072693049087428, 1.060985057710791]
  ];
  var fromXYZ_M$5 = [
    [1.716651187971268, -0.355670783776392, -0.25336628137366],
    [-0.666684351832489, 1.616481236634939, 0.0157685458139111],
    [0.017639857445311, -0.042770613257809, 0.942103121235474]
  ];
  var REC2020Linear = new RGBColorSpace({
    id: "rec2020-linear",
    cssId: "--rec2020-linear",
    name: "Linear REC.2020",
    white: "D65",
    toXYZ_M: toXYZ_M$5,
    fromXYZ_M: fromXYZ_M$5
  });
  var \u03B1 = 1.09929682680944;
  var \u03B2 = 0.018053968510807;
  var REC2020 = new RGBColorSpace({
    id: "rec2020",
    name: "REC.2020",
    base: REC2020Linear,
    // Non-linear transfer function from Rec. ITU-R BT.2020-2 table 4
    toBase(RGB) {
      return RGB.map(function(val) {
        if (val < \u03B2 * 4.5) {
          return val / 4.5;
        }
        return Math.pow((val + \u03B1 - 1) / \u03B1, 1 / 0.45);
      });
    },
    fromBase(RGB) {
      return RGB.map(function(val) {
        if (val >= \u03B2) {
          return \u03B1 * Math.pow(val, 0.45) - (\u03B1 - 1);
        }
        return 4.5 * val;
      });
    }
  });
  var toXYZ_M$4 = [
    [0.4865709486482162, 0.26566769316909306, 0.1982172852343625],
    [0.2289745640697488, 0.6917385218365064, 0.079286914093745],
    [0, 0.04511338185890264, 1.043944368900976]
  ];
  var fromXYZ_M$4 = [
    [2.493496911941425, -0.9313836179191239, -0.40271078445071684],
    [-0.8294889695615747, 1.7626640603183463, 0.023624685841943577],
    [0.03584583024378447, -0.07617238926804182, 0.9568845240076872]
  ];
  var P3Linear = new RGBColorSpace({
    id: "p3-linear",
    cssId: "--display-p3-linear",
    name: "Linear P3",
    white: "D65",
    toXYZ_M: toXYZ_M$4,
    fromXYZ_M: fromXYZ_M$4
  });
  var toXYZ_M$3 = [
    [0.41239079926595934, 0.357584339383878, 0.1804807884018343],
    [0.21263900587151027, 0.715168678767756, 0.07219231536073371],
    [0.01933081871559182, 0.11919477979462598, 0.9505321522496607]
  ];
  var fromXYZ_M$3 = [
    [3.2409699419045226, -1.537383177570094, -0.4986107602930034],
    [-0.9692436362808796, 1.8759675015077202, 0.04155505740717559],
    [0.05563007969699366, -0.20397695888897652, 1.0569715142428786]
  ];
  var sRGBLinear = new RGBColorSpace({
    id: "srgb-linear",
    name: "Linear sRGB",
    white: "D65",
    toXYZ_M: toXYZ_M$3,
    fromXYZ_M: fromXYZ_M$3
  });
  var KEYWORDS = {
    "aliceblue": [240 / 255, 248 / 255, 1],
    "antiquewhite": [250 / 255, 235 / 255, 215 / 255],
    "aqua": [0, 1, 1],
    "aquamarine": [127 / 255, 1, 212 / 255],
    "azure": [240 / 255, 1, 1],
    "beige": [245 / 255, 245 / 255, 220 / 255],
    "bisque": [1, 228 / 255, 196 / 255],
    "black": [0, 0, 0],
    "blanchedalmond": [1, 235 / 255, 205 / 255],
    "blue": [0, 0, 1],
    "blueviolet": [138 / 255, 43 / 255, 226 / 255],
    "brown": [165 / 255, 42 / 255, 42 / 255],
    "burlywood": [222 / 255, 184 / 255, 135 / 255],
    "cadetblue": [95 / 255, 158 / 255, 160 / 255],
    "chartreuse": [127 / 255, 1, 0],
    "chocolate": [210 / 255, 105 / 255, 30 / 255],
    "coral": [1, 127 / 255, 80 / 255],
    "cornflowerblue": [100 / 255, 149 / 255, 237 / 255],
    "cornsilk": [1, 248 / 255, 220 / 255],
    "crimson": [220 / 255, 20 / 255, 60 / 255],
    "cyan": [0, 1, 1],
    "darkblue": [0, 0, 139 / 255],
    "darkcyan": [0, 139 / 255, 139 / 255],
    "darkgoldenrod": [184 / 255, 134 / 255, 11 / 255],
    "darkgray": [169 / 255, 169 / 255, 169 / 255],
    "darkgreen": [0, 100 / 255, 0],
    "darkgrey": [169 / 255, 169 / 255, 169 / 255],
    "darkkhaki": [189 / 255, 183 / 255, 107 / 255],
    "darkmagenta": [139 / 255, 0, 139 / 255],
    "darkolivegreen": [85 / 255, 107 / 255, 47 / 255],
    "darkorange": [1, 140 / 255, 0],
    "darkorchid": [153 / 255, 50 / 255, 204 / 255],
    "darkred": [139 / 255, 0, 0],
    "darksalmon": [233 / 255, 150 / 255, 122 / 255],
    "darkseagreen": [143 / 255, 188 / 255, 143 / 255],
    "darkslateblue": [72 / 255, 61 / 255, 139 / 255],
    "darkslategray": [47 / 255, 79 / 255, 79 / 255],
    "darkslategrey": [47 / 255, 79 / 255, 79 / 255],
    "darkturquoise": [0, 206 / 255, 209 / 255],
    "darkviolet": [148 / 255, 0, 211 / 255],
    "deeppink": [1, 20 / 255, 147 / 255],
    "deepskyblue": [0, 191 / 255, 1],
    "dimgray": [105 / 255, 105 / 255, 105 / 255],
    "dimgrey": [105 / 255, 105 / 255, 105 / 255],
    "dodgerblue": [30 / 255, 144 / 255, 1],
    "firebrick": [178 / 255, 34 / 255, 34 / 255],
    "floralwhite": [1, 250 / 255, 240 / 255],
    "forestgreen": [34 / 255, 139 / 255, 34 / 255],
    "fuchsia": [1, 0, 1],
    "gainsboro": [220 / 255, 220 / 255, 220 / 255],
    "ghostwhite": [248 / 255, 248 / 255, 1],
    "gold": [1, 215 / 255, 0],
    "goldenrod": [218 / 255, 165 / 255, 32 / 255],
    "gray": [128 / 255, 128 / 255, 128 / 255],
    "green": [0, 128 / 255, 0],
    "greenyellow": [173 / 255, 1, 47 / 255],
    "grey": [128 / 255, 128 / 255, 128 / 255],
    "honeydew": [240 / 255, 1, 240 / 255],
    "hotpink": [1, 105 / 255, 180 / 255],
    "indianred": [205 / 255, 92 / 255, 92 / 255],
    "indigo": [75 / 255, 0, 130 / 255],
    "ivory": [1, 1, 240 / 255],
    "khaki": [240 / 255, 230 / 255, 140 / 255],
    "lavender": [230 / 255, 230 / 255, 250 / 255],
    "lavenderblush": [1, 240 / 255, 245 / 255],
    "lawngreen": [124 / 255, 252 / 255, 0],
    "lemonchiffon": [1, 250 / 255, 205 / 255],
    "lightblue": [173 / 255, 216 / 255, 230 / 255],
    "lightcoral": [240 / 255, 128 / 255, 128 / 255],
    "lightcyan": [224 / 255, 1, 1],
    "lightgoldenrodyellow": [250 / 255, 250 / 255, 210 / 255],
    "lightgray": [211 / 255, 211 / 255, 211 / 255],
    "lightgreen": [144 / 255, 238 / 255, 144 / 255],
    "lightgrey": [211 / 255, 211 / 255, 211 / 255],
    "lightpink": [1, 182 / 255, 193 / 255],
    "lightsalmon": [1, 160 / 255, 122 / 255],
    "lightseagreen": [32 / 255, 178 / 255, 170 / 255],
    "lightskyblue": [135 / 255, 206 / 255, 250 / 255],
    "lightslategray": [119 / 255, 136 / 255, 153 / 255],
    "lightslategrey": [119 / 255, 136 / 255, 153 / 255],
    "lightsteelblue": [176 / 255, 196 / 255, 222 / 255],
    "lightyellow": [1, 1, 224 / 255],
    "lime": [0, 1, 0],
    "limegreen": [50 / 255, 205 / 255, 50 / 255],
    "linen": [250 / 255, 240 / 255, 230 / 255],
    "magenta": [1, 0, 1],
    "maroon": [128 / 255, 0, 0],
    "mediumaquamarine": [102 / 255, 205 / 255, 170 / 255],
    "mediumblue": [0, 0, 205 / 255],
    "mediumorchid": [186 / 255, 85 / 255, 211 / 255],
    "mediumpurple": [147 / 255, 112 / 255, 219 / 255],
    "mediumseagreen": [60 / 255, 179 / 255, 113 / 255],
    "mediumslateblue": [123 / 255, 104 / 255, 238 / 255],
    "mediumspringgreen": [0, 250 / 255, 154 / 255],
    "mediumturquoise": [72 / 255, 209 / 255, 204 / 255],
    "mediumvioletred": [199 / 255, 21 / 255, 133 / 255],
    "midnightblue": [25 / 255, 25 / 255, 112 / 255],
    "mintcream": [245 / 255, 1, 250 / 255],
    "mistyrose": [1, 228 / 255, 225 / 255],
    "moccasin": [1, 228 / 255, 181 / 255],
    "navajowhite": [1, 222 / 255, 173 / 255],
    "navy": [0, 0, 128 / 255],
    "oldlace": [253 / 255, 245 / 255, 230 / 255],
    "olive": [128 / 255, 128 / 255, 0],
    "olivedrab": [107 / 255, 142 / 255, 35 / 255],
    "orange": [1, 165 / 255, 0],
    "orangered": [1, 69 / 255, 0],
    "orchid": [218 / 255, 112 / 255, 214 / 255],
    "palegoldenrod": [238 / 255, 232 / 255, 170 / 255],
    "palegreen": [152 / 255, 251 / 255, 152 / 255],
    "paleturquoise": [175 / 255, 238 / 255, 238 / 255],
    "palevioletred": [219 / 255, 112 / 255, 147 / 255],
    "papayawhip": [1, 239 / 255, 213 / 255],
    "peachpuff": [1, 218 / 255, 185 / 255],
    "peru": [205 / 255, 133 / 255, 63 / 255],
    "pink": [1, 192 / 255, 203 / 255],
    "plum": [221 / 255, 160 / 255, 221 / 255],
    "powderblue": [176 / 255, 224 / 255, 230 / 255],
    "purple": [128 / 255, 0, 128 / 255],
    "rebeccapurple": [102 / 255, 51 / 255, 153 / 255],
    "red": [1, 0, 0],
    "rosybrown": [188 / 255, 143 / 255, 143 / 255],
    "royalblue": [65 / 255, 105 / 255, 225 / 255],
    "saddlebrown": [139 / 255, 69 / 255, 19 / 255],
    "salmon": [250 / 255, 128 / 255, 114 / 255],
    "sandybrown": [244 / 255, 164 / 255, 96 / 255],
    "seagreen": [46 / 255, 139 / 255, 87 / 255],
    "seashell": [1, 245 / 255, 238 / 255],
    "sienna": [160 / 255, 82 / 255, 45 / 255],
    "silver": [192 / 255, 192 / 255, 192 / 255],
    "skyblue": [135 / 255, 206 / 255, 235 / 255],
    "slateblue": [106 / 255, 90 / 255, 205 / 255],
    "slategray": [112 / 255, 128 / 255, 144 / 255],
    "slategrey": [112 / 255, 128 / 255, 144 / 255],
    "snow": [1, 250 / 255, 250 / 255],
    "springgreen": [0, 1, 127 / 255],
    "steelblue": [70 / 255, 130 / 255, 180 / 255],
    "tan": [210 / 255, 180 / 255, 140 / 255],
    "teal": [0, 128 / 255, 128 / 255],
    "thistle": [216 / 255, 191 / 255, 216 / 255],
    "tomato": [1, 99 / 255, 71 / 255],
    "turquoise": [64 / 255, 224 / 255, 208 / 255],
    "violet": [238 / 255, 130 / 255, 238 / 255],
    "wheat": [245 / 255, 222 / 255, 179 / 255],
    "white": [1, 1, 1],
    "whitesmoke": [245 / 255, 245 / 255, 245 / 255],
    "yellow": [1, 1, 0],
    "yellowgreen": [154 / 255, 205 / 255, 50 / 255]
  };
  var coordGrammar = Array(3).fill("<percentage> | <number>[0, 255]");
  var coordGrammarNumber = Array(3).fill("<number>[0, 255]");
  var sRGB = new RGBColorSpace({
    id: "srgb",
    name: "sRGB",
    base: sRGBLinear,
    fromBase: (rgb) => {
      return rgb.map((val) => {
        let sign = val < 0 ? -1 : 1;
        let abs = val * sign;
        if (abs > 31308e-7) {
          return sign * (1.055 * abs ** (1 / 2.4) - 0.055);
        }
        return 12.92 * val;
      });
    },
    toBase: (rgb) => {
      return rgb.map((val) => {
        let sign = val < 0 ? -1 : 1;
        let abs = val * sign;
        if (abs <= 0.04045) {
          return val / 12.92;
        }
        return sign * ((abs + 0.055) / 1.055) ** 2.4;
      });
    },
    formats: {
      "rgb": {
        coords: coordGrammar
      },
      "rgb_number": {
        name: "rgb",
        commas: true,
        coords: coordGrammarNumber,
        noAlpha: true
      },
      "color": {
        /* use defaults */
      },
      "rgba": {
        coords: coordGrammar,
        commas: true,
        lastAlpha: true
      },
      "rgba_number": {
        name: "rgba",
        commas: true,
        coords: coordGrammarNumber
      },
      "hex": {
        type: "custom",
        toGamut: true,
        test: (str) => /^#([a-f0-9]{3,4}){1,2}$/i.test(str),
        parse(str) {
          if (str.length <= 5) {
            str = str.replace(/[a-f0-9]/gi, "$&$&");
          }
          let rgba = [];
          str.replace(/[a-f0-9]{2}/gi, (component) => {
            rgba.push(parseInt(component, 16) / 255);
          });
          return {
            spaceId: "srgb",
            coords: rgba.slice(0, 3),
            alpha: rgba.slice(3)[0]
          };
        },
        serialize: (coords, alpha, {
          collapse = true
          // collapse to 3-4 digit hex when possible?
        } = {}) => {
          if (alpha < 1) {
            coords.push(alpha);
          }
          coords = coords.map((c4) => Math.round(c4 * 255));
          let collapsible = collapse && coords.every((c4) => c4 % 17 === 0);
          let hex = coords.map((c4) => {
            if (collapsible) {
              return (c4 / 17).toString(16);
            }
            return c4.toString(16).padStart(2, "0");
          }).join("");
          return "#" + hex;
        }
      },
      "keyword": {
        type: "custom",
        test: (str) => /^[a-z]+$/i.test(str),
        parse(str) {
          str = str.toLowerCase();
          let ret = { spaceId: "srgb", coords: null, alpha: 1 };
          if (str === "transparent") {
            ret.coords = KEYWORDS.black;
            ret.alpha = 0;
          } else {
            ret.coords = KEYWORDS[str];
          }
          if (ret.coords) {
            return ret;
          }
        }
      }
    }
  });
  var P3 = new RGBColorSpace({
    id: "p3",
    cssId: "display-p3",
    name: "P3",
    base: P3Linear,
    // Gamma encoding/decoding is the same as sRGB
    fromBase: sRGB.fromBase,
    toBase: sRGB.toBase
  });
  defaults.display_space = sRGB;
  var supportsNone;
  if (typeof CSS !== "undefined" && CSS.supports) {
    for (let space of [lab, REC2020, P3]) {
      let coords = space.getMinCoords();
      let color = { space, coords, alpha: 1 };
      let str = serialize(color);
      if (CSS.supports("color", str)) {
        defaults.display_space = space;
        break;
      }
    }
  }
  function display(color, { space = defaults.display_space, ...options } = {}) {
    let ret = serialize(color, options);
    if (typeof CSS === "undefined" || CSS.supports("color", ret) || !defaults.display_space) {
      ret = new String(ret);
      ret.color = color;
    } else {
      let fallbackColor = color;
      let hasNone = color.coords.some(isNone) || isNone(color.alpha);
      if (hasNone) {
        if (!(supportsNone ??= CSS.supports("color", "hsl(none 50% 50%)"))) {
          fallbackColor = clone(color);
          fallbackColor.coords = fallbackColor.coords.map(skipNone);
          fallbackColor.alpha = skipNone(fallbackColor.alpha);
          ret = serialize(fallbackColor, options);
          if (CSS.supports("color", ret)) {
            ret = new String(ret);
            ret.color = fallbackColor;
            return ret;
          }
        }
      }
      fallbackColor = to(fallbackColor, space);
      ret = new String(serialize(fallbackColor, options));
      ret.color = fallbackColor;
    }
    return ret;
  }
  function equals(color1, color2) {
    color1 = getColor(color1);
    color2 = getColor(color2);
    return color1.space === color2.space && color1.alpha === color2.alpha && color1.coords.every((c4, i) => c4 === color2.coords[i]);
  }
  function getLuminance(color) {
    return get(color, [xyz_d65, "y"]);
  }
  function setLuminance(color, value) {
    set(color, [xyz_d65, "y"], value);
  }
  function register$2(Color2) {
    Object.defineProperty(Color2.prototype, "luminance", {
      get() {
        return getLuminance(this);
      },
      set(value) {
        setLuminance(this, value);
      }
    });
  }
  var luminance = /* @__PURE__ */ Object.freeze({
    __proto__: null,
    getLuminance,
    register: register$2,
    setLuminance
  });
  function contrastWCAG21(color1, color2) {
    color1 = getColor(color1);
    color2 = getColor(color2);
    let Y1 = Math.max(getLuminance(color1), 0);
    let Y2 = Math.max(getLuminance(color2), 0);
    if (Y2 > Y1) {
      [Y1, Y2] = [Y2, Y1];
    }
    return (Y1 + 0.05) / (Y2 + 0.05);
  }
  var normBG = 0.56;
  var normTXT = 0.57;
  var revTXT = 0.62;
  var revBG = 0.65;
  var blkThrs = 0.022;
  var blkClmp = 1.414;
  var loClip = 0.1;
  var deltaYmin = 5e-4;
  var scaleBoW = 1.14;
  var loBoWoffset = 0.027;
  var scaleWoB = 1.14;
  function fclamp(Y) {
    if (Y >= blkThrs) {
      return Y;
    }
    return Y + (blkThrs - Y) ** blkClmp;
  }
  function linearize(val) {
    let sign = val < 0 ? -1 : 1;
    let abs = Math.abs(val);
    return sign * Math.pow(abs, 2.4);
  }
  function contrastAPCA(background, foreground) {
    foreground = getColor(foreground);
    background = getColor(background);
    let S;
    let C;
    let Sapc;
    let R, G, B;
    foreground = to(foreground, "srgb");
    [R, G, B] = foreground.coords;
    let lumTxt = linearize(R) * 0.2126729 + linearize(G) * 0.7151522 + linearize(B) * 0.072175;
    background = to(background, "srgb");
    [R, G, B] = background.coords;
    let lumBg = linearize(R) * 0.2126729 + linearize(G) * 0.7151522 + linearize(B) * 0.072175;
    let Ytxt = fclamp(lumTxt);
    let Ybg = fclamp(lumBg);
    let BoW = Ybg > Ytxt;
    if (Math.abs(Ybg - Ytxt) < deltaYmin) {
      C = 0;
    } else {
      if (BoW) {
        S = Ybg ** normBG - Ytxt ** normTXT;
        C = S * scaleBoW;
      } else {
        S = Ybg ** revBG - Ytxt ** revTXT;
        C = S * scaleWoB;
      }
    }
    if (Math.abs(C) < loClip) {
      Sapc = 0;
    } else if (C > 0) {
      Sapc = C - loBoWoffset;
    } else {
      Sapc = C + loBoWoffset;
    }
    return Sapc * 100;
  }
  function contrastMichelson(color1, color2) {
    color1 = getColor(color1);
    color2 = getColor(color2);
    let Y1 = Math.max(getLuminance(color1), 0);
    let Y2 = Math.max(getLuminance(color2), 0);
    if (Y2 > Y1) {
      [Y1, Y2] = [Y2, Y1];
    }
    let denom = Y1 + Y2;
    return denom === 0 ? 0 : (Y1 - Y2) / denom;
  }
  var max = 5e4;
  function contrastWeber(color1, color2) {
    color1 = getColor(color1);
    color2 = getColor(color2);
    let Y1 = Math.max(getLuminance(color1), 0);
    let Y2 = Math.max(getLuminance(color2), 0);
    if (Y2 > Y1) {
      [Y1, Y2] = [Y2, Y1];
    }
    return Y2 === 0 ? max : (Y1 - Y2) / Y2;
  }
  function contrastLstar(color1, color2) {
    color1 = getColor(color1);
    color2 = getColor(color2);
    let L1 = get(color1, [lab, "l"]);
    let L2 = get(color2, [lab, "l"]);
    return Math.abs(L1 - L2);
  }
  var \u03B5$3 = 216 / 24389;
  var \u03B53 = 24 / 116;
  var \u03BA$2 = 24389 / 27;
  var white$1 = WHITES.D65;
  var lab_d65 = new ColorSpace({
    id: "lab-d65",
    name: "Lab D65",
    coords: {
      l: {
        refRange: [0, 100],
        name: "Lightness"
      },
      a: {
        refRange: [-125, 125]
      },
      b: {
        refRange: [-125, 125]
      }
    },
    // Assuming XYZ is relative to D65, convert to CIE Lab
    // from CIE standard, which now defines these as a rational fraction
    white: white$1,
    base: xyz_d65,
    // Convert D65-adapted XYZ to Lab
    //  CIE 15.3:2004 section 8.2.1.1
    fromBase(XYZ) {
      let xyz = XYZ.map((value, i) => value / white$1[i]);
      let f = xyz.map((value) => value > \u03B5$3 ? Math.cbrt(value) : (\u03BA$2 * value + 16) / 116);
      return [
        116 * f[1] - 16,
        // L
        500 * (f[0] - f[1]),
        // a
        200 * (f[1] - f[2])
        // b
      ];
    },
    // Convert Lab to D65-adapted XYZ
    // Same result as CIE 15.3:2004 Appendix D although the derivation is different
    // http://www.brucelindbloom.com/index.html?Eqn_RGB_XYZ_Matrix.html
    toBase(Lab) {
      let f = [];
      f[1] = (Lab[0] + 16) / 116;
      f[0] = Lab[1] / 500 + f[1];
      f[2] = f[1] - Lab[2] / 200;
      let xyz = [
        f[0] > \u03B53 ? Math.pow(f[0], 3) : (116 * f[0] - 16) / \u03BA$2,
        Lab[0] > 8 ? Math.pow((Lab[0] + 16) / 116, 3) : Lab[0] / \u03BA$2,
        f[2] > \u03B53 ? Math.pow(f[2], 3) : (116 * f[2] - 16) / \u03BA$2
      ];
      return xyz.map((value, i) => value * white$1[i]);
    },
    formats: {
      "lab-d65": {
        coords: ["<number> | <percentage>", "<number> | <percentage>[-1,1]", "<number> | <percentage>[-1,1]"]
      }
    }
  });
  var phi = Math.pow(5, 0.5) * 0.5 + 0.5;
  function contrastDeltaPhi(color1, color2) {
    color1 = getColor(color1);
    color2 = getColor(color2);
    let Lstr1 = get(color1, [lab_d65, "l"]);
    let Lstr2 = get(color2, [lab_d65, "l"]);
    let deltaPhiStar = Math.abs(Math.pow(Lstr1, phi) - Math.pow(Lstr2, phi));
    let contrast2 = Math.pow(deltaPhiStar, 1 / phi) * Math.SQRT2 - 40;
    return contrast2 < 7.5 ? 0 : contrast2;
  }
  var contrastMethods = /* @__PURE__ */ Object.freeze({
    __proto__: null,
    contrastAPCA,
    contrastDeltaPhi,
    contrastLstar,
    contrastMichelson,
    contrastWCAG21,
    contrastWeber
  });
  function contrast(background, foreground, o = {}) {
    if (isString(o)) {
      o = { algorithm: o };
    }
    let { algorithm, ...rest } = o;
    if (!algorithm) {
      let algorithms = Object.keys(contrastMethods).map((a2) => a2.replace(/^contrast/, "")).join(", ");
      throw new TypeError(`contrast() function needs a contrast algorithm. Please specify one of: ${algorithms}`);
    }
    background = getColor(background);
    foreground = getColor(foreground);
    for (let a2 in contrastMethods) {
      if ("contrast" + algorithm.toLowerCase() === a2.toLowerCase()) {
        return contrastMethods[a2](background, foreground, rest);
      }
    }
    throw new TypeError(`Unknown contrast algorithm: ${algorithm}`);
  }
  function uv(color) {
    let [X, Y, Z] = getAll(color, xyz_d65);
    let denom = X + 15 * Y + 3 * Z;
    return [4 * X / denom, 9 * Y / denom];
  }
  function xy(color) {
    let [X, Y, Z] = getAll(color, xyz_d65);
    let sum = X + Y + Z;
    return [X / sum, Y / sum];
  }
  function register$1(Color2) {
    Object.defineProperty(Color2.prototype, "uv", {
      get() {
        return uv(this);
      }
    });
    Object.defineProperty(Color2.prototype, "xy", {
      get() {
        return xy(this);
      }
    });
  }
  var chromaticity = /* @__PURE__ */ Object.freeze({
    __proto__: null,
    register: register$1,
    uv,
    xy
  });
  function deltaE(c12, c22, o = {}) {
    if (isString(o)) {
      o = { method: o };
    }
    let { method = defaults.deltaE, ...rest } = o;
    for (let m3 in deltaEMethods) {
      if ("deltae" + method.toLowerCase() === m3.toLowerCase()) {
        return deltaEMethods[m3](c12, c22, rest);
      }
    }
    throw new TypeError(`Unknown deltaE method: ${method}`);
  }
  function lighten(color, amount = 0.25) {
    let space = ColorSpace.get("oklch", "lch");
    let lightness = [space, "l"];
    return set(color, lightness, (l) => l * (1 + amount));
  }
  function darken(color, amount = 0.25) {
    let space = ColorSpace.get("oklch", "lch");
    let lightness = [space, "l"];
    return set(color, lightness, (l) => l * (1 - amount));
  }
  var variations = /* @__PURE__ */ Object.freeze({
    __proto__: null,
    darken,
    lighten
  });
  function mix(c12, c22, p2 = 0.5, o = {}) {
    [c12, c22] = [getColor(c12), getColor(c22)];
    if (type(p2) === "object") {
      [p2, o] = [0.5, p2];
    }
    let r = range(c12, c22, o);
    return r(p2);
  }
  function steps(c12, c22, options = {}) {
    let colorRange;
    if (isRange(c12)) {
      [colorRange, options] = [c12, c22];
      [c12, c22] = colorRange.rangeArgs.colors;
    }
    let {
      maxDeltaE,
      deltaEMethod,
      steps: steps2 = 2,
      maxSteps = 1e3,
      ...rangeOptions
    } = options;
    if (!colorRange) {
      [c12, c22] = [getColor(c12), getColor(c22)];
      colorRange = range(c12, c22, rangeOptions);
    }
    let totalDelta = deltaE(c12, c22);
    let actualSteps = maxDeltaE > 0 ? Math.max(steps2, Math.ceil(totalDelta / maxDeltaE) + 1) : steps2;
    let ret = [];
    if (maxSteps !== void 0) {
      actualSteps = Math.min(actualSteps, maxSteps);
    }
    if (actualSteps === 1) {
      ret = [{ p: 0.5, color: colorRange(0.5) }];
    } else {
      let step = 1 / (actualSteps - 1);
      ret = Array.from({ length: actualSteps }, (_, i) => {
        let p2 = i * step;
        return { p: p2, color: colorRange(p2) };
      });
    }
    if (maxDeltaE > 0) {
      let maxDelta = ret.reduce((acc, cur, i) => {
        if (i === 0) {
          return 0;
        }
        let \u0394\u0395 = deltaE(cur.color, ret[i - 1].color, deltaEMethod);
        return Math.max(acc, \u0394\u0395);
      }, 0);
      while (maxDelta > maxDeltaE) {
        maxDelta = 0;
        for (let i = 1; i < ret.length && ret.length < maxSteps; i++) {
          let prev = ret[i - 1];
          let cur = ret[i];
          let p2 = (cur.p + prev.p) / 2;
          let color = colorRange(p2);
          maxDelta = Math.max(maxDelta, deltaE(color, prev.color), deltaE(color, cur.color));
          ret.splice(i, 0, { p: p2, color: colorRange(p2) });
          i++;
        }
      }
    }
    ret = ret.map((a2) => a2.color);
    return ret;
  }
  function range(color1, color2, options = {}) {
    if (isRange(color1)) {
      let [r, options2] = [color1, color2];
      return range(...r.rangeArgs.colors, { ...r.rangeArgs.options, ...options2 });
    }
    let { space, outputSpace, progression, premultiplied } = options;
    color1 = getColor(color1);
    color2 = getColor(color2);
    color1 = clone(color1);
    color2 = clone(color2);
    let rangeArgs = { colors: [color1, color2], options };
    if (space) {
      space = ColorSpace.get(space);
    } else {
      space = ColorSpace.registry[defaults.interpolationSpace] || color1.space;
    }
    outputSpace = outputSpace ? ColorSpace.get(outputSpace) : space;
    color1 = to(color1, space);
    color2 = to(color2, space);
    color1 = toGamut(color1);
    color2 = toGamut(color2);
    if (space.coords.h && space.coords.h.type === "angle") {
      let arc = options.hue = options.hue || "shorter";
      let hue = [space, "h"];
      let [\u03B81, \u03B82] = [get(color1, hue), get(color2, hue)];
      if (isNaN(\u03B81) && !isNaN(\u03B82)) {
        \u03B81 = \u03B82;
      } else if (isNaN(\u03B82) && !isNaN(\u03B81)) {
        \u03B82 = \u03B81;
      }
      [\u03B81, \u03B82] = adjust(arc, [\u03B81, \u03B82]);
      set(color1, hue, \u03B81);
      set(color2, hue, \u03B82);
    }
    if (premultiplied) {
      color1.coords = color1.coords.map((c4) => c4 * color1.alpha);
      color2.coords = color2.coords.map((c4) => c4 * color2.alpha);
    }
    return Object.assign((p2) => {
      p2 = progression ? progression(p2) : p2;
      let coords = color1.coords.map((start, i) => {
        let end = color2.coords[i];
        return interpolate(start, end, p2);
      });
      let alpha = interpolate(color1.alpha, color2.alpha, p2);
      let ret = { space, coords, alpha };
      if (premultiplied) {
        ret.coords = ret.coords.map((c4) => c4 / alpha);
      }
      if (outputSpace !== space) {
        ret = to(ret, outputSpace);
      }
      return ret;
    }, {
      rangeArgs
    });
  }
  function isRange(val) {
    return type(val) === "function" && !!val.rangeArgs;
  }
  defaults.interpolationSpace = "lab";
  function register(Color2) {
    Color2.defineFunction("mix", mix, { returns: "color" });
    Color2.defineFunction("range", range, { returns: "function<color>" });
    Color2.defineFunction("steps", steps, { returns: "array<color>" });
  }
  var interpolation = /* @__PURE__ */ Object.freeze({
    __proto__: null,
    isRange,
    mix,
    range,
    register,
    steps
  });
  var HSL = new ColorSpace({
    id: "hsl",
    name: "HSL",
    coords: {
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      },
      s: {
        range: [0, 100],
        name: "Saturation"
      },
      l: {
        range: [0, 100],
        name: "Lightness"
      }
    },
    base: sRGB,
    // Adapted from https://drafts.csswg.org/css-color-4/better-rgbToHsl.js
    fromBase: (rgb) => {
      let max2 = Math.max(...rgb);
      let min = Math.min(...rgb);
      let [r, g2, b2] = rgb;
      let [h, s, l] = [NaN, 0, (min + max2) / 2];
      let d2 = max2 - min;
      if (d2 !== 0) {
        s = l === 0 || l === 1 ? 0 : (max2 - l) / Math.min(l, 1 - l);
        switch (max2) {
          case r:
            h = (g2 - b2) / d2 + (g2 < b2 ? 6 : 0);
            break;
          case g2:
            h = (b2 - r) / d2 + 2;
            break;
          case b2:
            h = (r - g2) / d2 + 4;
        }
        h = h * 60;
      }
      if (s < 0) {
        h += 180;
        s = Math.abs(s);
      }
      if (h >= 360) {
        h -= 360;
      }
      return [h, s * 100, l * 100];
    },
    // Adapted from https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
    toBase: (hsl) => {
      let [h, s, l] = hsl;
      h = h % 360;
      if (h < 0) {
        h += 360;
      }
      s /= 100;
      l /= 100;
      function f(n2) {
        let k = (n2 + h / 30) % 12;
        let a2 = s * Math.min(l, 1 - l);
        return l - a2 * Math.max(-1, Math.min(k - 3, 9 - k, 1));
      }
      return [f(0), f(8), f(4)];
    },
    formats: {
      "hsl": {
        coords: ["<number> | <angle>", "<percentage>", "<percentage>"]
      },
      "hsla": {
        coords: ["<number> | <angle>", "<percentage>", "<percentage>"],
        commas: true,
        lastAlpha: true
      }
    }
  });
  var HSV = new ColorSpace({
    id: "hsv",
    name: "HSV",
    coords: {
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      },
      s: {
        range: [0, 100],
        name: "Saturation"
      },
      v: {
        range: [0, 100],
        name: "Value"
      }
    },
    base: HSL,
    // https://en.wikipedia.org/wiki/HSL_and_HSV#Interconversion
    fromBase(hsl) {
      let [h, s, l] = hsl;
      s /= 100;
      l /= 100;
      let v = l + s * Math.min(l, 1 - l);
      return [
        h,
        // h is the same
        v === 0 ? 0 : 200 * (1 - l / v),
        // s
        100 * v
      ];
    },
    // https://en.wikipedia.org/wiki/HSL_and_HSV#Interconversion
    toBase(hsv) {
      let [h, s, v] = hsv;
      s /= 100;
      v /= 100;
      let l = v * (1 - s / 2);
      return [
        h,
        // h is the same
        l === 0 || l === 1 ? 0 : (v - l) / Math.min(l, 1 - l) * 100,
        l * 100
      ];
    },
    formats: {
      color: {
        id: "--hsv",
        coords: ["<number> | <angle>", "<percentage> | <number>", "<percentage> | <number>"]
      }
    }
  });
  var hwb = new ColorSpace({
    id: "hwb",
    name: "HWB",
    coords: {
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      },
      w: {
        range: [0, 100],
        name: "Whiteness"
      },
      b: {
        range: [0, 100],
        name: "Blackness"
      }
    },
    base: HSV,
    fromBase(hsv) {
      let [h, s, v] = hsv;
      return [h, v * (100 - s) / 100, 100 - v];
    },
    toBase(hwb2) {
      let [h, w, b2] = hwb2;
      w /= 100;
      b2 /= 100;
      let sum = w + b2;
      if (sum >= 1) {
        let gray = w / sum;
        return [h, 0, gray * 100];
      }
      let v = 1 - b2;
      let s = v === 0 ? 0 : 1 - w / v;
      return [h, s * 100, v * 100];
    },
    formats: {
      "hwb": {
        coords: ["<number> | <angle>", "<percentage> | <number>", "<percentage> | <number>"]
      }
    }
  });
  var toXYZ_M$2 = [
    [0.5766690429101305, 0.1855582379065463, 0.1882286462349947],
    [0.29734497525053605, 0.6273635662554661, 0.07529145849399788],
    [0.02703136138641234, 0.07068885253582723, 0.9913375368376388]
  ];
  var fromXYZ_M$2 = [
    [2.0415879038107465, -0.5650069742788596, -0.34473135077832956],
    [-0.9692436362808795, 1.8759675015077202, 0.04155505740717557],
    [0.013444280632031142, -0.11836239223101838, 1.0151749943912054]
  ];
  var A98Linear = new RGBColorSpace({
    id: "a98rgb-linear",
    cssId: "--a98-rgb-linear",
    name: "Linear Adobe\xAE 98 RGB compatible",
    white: "D65",
    toXYZ_M: toXYZ_M$2,
    fromXYZ_M: fromXYZ_M$2
  });
  var a98rgb = new RGBColorSpace({
    id: "a98rgb",
    cssId: "a98-rgb",
    name: "Adobe\xAE 98 RGB compatible",
    base: A98Linear,
    toBase: (RGB) => RGB.map((val) => Math.pow(Math.abs(val), 563 / 256) * Math.sign(val)),
    fromBase: (RGB) => RGB.map((val) => Math.pow(Math.abs(val), 256 / 563) * Math.sign(val))
  });
  var toXYZ_M$1 = [
    [0.7977666449006423, 0.13518129740053308, 0.0313477341283922],
    [0.2880748288194013, 0.711835234241873, 8993693872564e-17],
    [0, 0, 0.8251046025104602]
  ];
  var fromXYZ_M$1 = [
    [1.3457868816471583, -0.25557208737979464, -0.05110186497554526],
    [-0.5446307051249019, 1.5082477428451468, 0.02052744743642139],
    [0, 0, 1.2119675456389452]
  ];
  var ProPhotoLinear = new RGBColorSpace({
    id: "prophoto-linear",
    cssId: "--prophoto-rgb-linear",
    name: "Linear ProPhoto",
    white: "D50",
    base: XYZ_D50,
    toXYZ_M: toXYZ_M$1,
    fromXYZ_M: fromXYZ_M$1
  });
  var Et = 1 / 512;
  var Et2 = 16 / 512;
  var prophoto = new RGBColorSpace({
    id: "prophoto",
    cssId: "prophoto-rgb",
    name: "ProPhoto",
    base: ProPhotoLinear,
    toBase(RGB) {
      return RGB.map((v) => v < Et2 ? v / 16 : v ** 1.8);
    },
    fromBase(RGB) {
      return RGB.map((v) => v >= Et ? v ** (1 / 1.8) : 16 * v);
    }
  });
  var oklch = new ColorSpace({
    id: "oklch",
    name: "Oklch",
    coords: {
      l: {
        refRange: [0, 1],
        name: "Lightness"
      },
      c: {
        refRange: [0, 0.4],
        name: "Chroma"
      },
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      }
    },
    white: "D65",
    base: OKLab,
    fromBase(oklab) {
      let [L, a2, b2] = oklab;
      let h;
      const \u03B52 = 2e-4;
      if (Math.abs(a2) < \u03B52 && Math.abs(b2) < \u03B52) {
        h = NaN;
      } else {
        h = Math.atan2(b2, a2) * 180 / Math.PI;
      }
      return [
        L,
        // OKLab L is still L
        Math.sqrt(a2 ** 2 + b2 ** 2),
        // Chroma
        constrain(h)
        // Hue, in degrees [0 to 360)
      ];
    },
    // Convert from polar form
    toBase(oklch2) {
      let [L, C, h] = oklch2;
      let a2, b2;
      if (isNaN(h)) {
        a2 = 0;
        b2 = 0;
      } else {
        a2 = C * Math.cos(h * Math.PI / 180);
        b2 = C * Math.sin(h * Math.PI / 180);
      }
      return [L, a2, b2];
    },
    formats: {
      "oklch": {
        coords: ["<percentage> | <number>", "<number> | <percentage>[0,1]", "<number> | <angle>"]
      }
    }
  });
  var white = WHITES.D65;
  var \u03B5$2 = 216 / 24389;
  var \u03BA$1 = 24389 / 27;
  var [U_PRIME_WHITE, V_PRIME_WHITE] = uv({ space: xyz_d65, coords: white });
  var Luv = new ColorSpace({
    id: "luv",
    name: "Luv",
    coords: {
      l: {
        refRange: [0, 100],
        name: "Lightness"
      },
      // Reference ranges from https://facelessuser.github.io/coloraide/colors/luv/
      u: {
        refRange: [-215, 215]
      },
      v: {
        refRange: [-215, 215]
      }
    },
    white,
    base: xyz_d65,
    // Convert D65-adapted XYZ to Luv
    // https://en.wikipedia.org/wiki/CIELUV#The_forward_transformation
    fromBase(XYZ) {
      let xyz = [skipNone(XYZ[0]), skipNone(XYZ[1]), skipNone(XYZ[2])];
      let y = xyz[1];
      let [up, vp] = uv({ space: xyz_d65, coords: xyz });
      if (!Number.isFinite(up) || !Number.isFinite(vp)) {
        return [0, 0, 0];
      }
      let L = y <= \u03B5$2 ? \u03BA$1 * y : 116 * Math.cbrt(y) - 16;
      return [
        L,
        13 * L * (up - U_PRIME_WHITE),
        13 * L * (vp - V_PRIME_WHITE)
      ];
    },
    // Convert Luv to D65-adapted XYZ
    // https://en.wikipedia.org/wiki/CIELUV#The_reverse_transformation
    toBase(Luv2) {
      let [L, u, v] = Luv2;
      if (L === 0 || isNone(L)) {
        return [0, 0, 0];
      }
      u = skipNone(u);
      v = skipNone(v);
      let up = u / (13 * L) + U_PRIME_WHITE;
      let vp = v / (13 * L) + V_PRIME_WHITE;
      let y = L <= 8 ? L / \u03BA$1 : Math.pow((L + 16) / 116, 3);
      return [
        y * (9 * up / (4 * vp)),
        y,
        y * ((12 - 3 * up - 20 * vp) / (4 * vp))
      ];
    },
    formats: {
      color: {
        id: "--luv",
        coords: ["<number> | <percentage>", "<number> | <percentage>[-1,1]", "<number> | <percentage>[-1,1]"]
      }
    }
  });
  var LCHuv = new ColorSpace({
    id: "lchuv",
    name: "LChuv",
    coords: {
      l: {
        refRange: [0, 100],
        name: "Lightness"
      },
      c: {
        refRange: [0, 220],
        name: "Chroma"
      },
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      }
    },
    base: Luv,
    fromBase(Luv2) {
      let [L, u, v] = Luv2;
      let hue;
      const \u03B52 = 0.02;
      if (Math.abs(u) < \u03B52 && Math.abs(v) < \u03B52) {
        hue = NaN;
      } else {
        hue = Math.atan2(v, u) * 180 / Math.PI;
      }
      return [
        L,
        // L is still L
        Math.sqrt(u ** 2 + v ** 2),
        // Chroma
        constrain(hue)
        // Hue, in degrees [0 to 360)
      ];
    },
    toBase(LCH) {
      let [Lightness, Chroma, Hue] = LCH;
      if (Chroma < 0) {
        Chroma = 0;
      }
      if (isNaN(Hue)) {
        Hue = 0;
      }
      return [
        Lightness,
        // L is still L
        Chroma * Math.cos(Hue * Math.PI / 180),
        // u
        Chroma * Math.sin(Hue * Math.PI / 180)
        // v
      ];
    },
    formats: {
      color: {
        id: "--lchuv",
        coords: ["<number> | <percentage>", "<number> | <percentage>", "<number> | <angle>"]
      }
    }
  });
  var \u03B5$1 = 216 / 24389;
  var \u03BA = 24389 / 27;
  var m_r0 = fromXYZ_M$3[0][0];
  var m_r1 = fromXYZ_M$3[0][1];
  var m_r2 = fromXYZ_M$3[0][2];
  var m_g0 = fromXYZ_M$3[1][0];
  var m_g1 = fromXYZ_M$3[1][1];
  var m_g2 = fromXYZ_M$3[1][2];
  var m_b0 = fromXYZ_M$3[2][0];
  var m_b1 = fromXYZ_M$3[2][1];
  var m_b2 = fromXYZ_M$3[2][2];
  function distanceFromOriginAngle(slope, intercept, angle) {
    const d2 = intercept / (Math.sin(angle) - slope * Math.cos(angle));
    return d2 < 0 ? Infinity : d2;
  }
  function calculateBoundingLines(l) {
    const sub1 = Math.pow(l + 16, 3) / 1560896;
    const sub2 = sub1 > \u03B5$1 ? sub1 : l / \u03BA;
    const s1r = sub2 * (284517 * m_r0 - 94839 * m_r2);
    const s2r = sub2 * (838422 * m_r2 + 769860 * m_r1 + 731718 * m_r0);
    const s3r = sub2 * (632260 * m_r2 - 126452 * m_r1);
    const s1g = sub2 * (284517 * m_g0 - 94839 * m_g2);
    const s2g = sub2 * (838422 * m_g2 + 769860 * m_g1 + 731718 * m_g0);
    const s3g = sub2 * (632260 * m_g2 - 126452 * m_g1);
    const s1b = sub2 * (284517 * m_b0 - 94839 * m_b2);
    const s2b = sub2 * (838422 * m_b2 + 769860 * m_b1 + 731718 * m_b0);
    const s3b = sub2 * (632260 * m_b2 - 126452 * m_b1);
    return {
      r0s: s1r / s3r,
      r0i: s2r * l / s3r,
      r1s: s1r / (s3r + 126452),
      r1i: (s2r - 769860) * l / (s3r + 126452),
      g0s: s1g / s3g,
      g0i: s2g * l / s3g,
      g1s: s1g / (s3g + 126452),
      g1i: (s2g - 769860) * l / (s3g + 126452),
      b0s: s1b / s3b,
      b0i: s2b * l / s3b,
      b1s: s1b / (s3b + 126452),
      b1i: (s2b - 769860) * l / (s3b + 126452)
    };
  }
  function calcMaxChromaHsluv(lines, h) {
    const hueRad = h / 360 * Math.PI * 2;
    const r0 = distanceFromOriginAngle(lines.r0s, lines.r0i, hueRad);
    const r1 = distanceFromOriginAngle(lines.r1s, lines.r1i, hueRad);
    const g0 = distanceFromOriginAngle(lines.g0s, lines.g0i, hueRad);
    const g1 = distanceFromOriginAngle(lines.g1s, lines.g1i, hueRad);
    const b0 = distanceFromOriginAngle(lines.b0s, lines.b0i, hueRad);
    const b1 = distanceFromOriginAngle(lines.b1s, lines.b1i, hueRad);
    return Math.min(r0, r1, g0, g1, b0, b1);
  }
  var hsluv = new ColorSpace({
    id: "hsluv",
    name: "HSLuv",
    coords: {
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      },
      s: {
        range: [0, 100],
        name: "Saturation"
      },
      l: {
        range: [0, 100],
        name: "Lightness"
      }
    },
    base: LCHuv,
    gamutSpace: sRGB,
    // Convert LCHuv to HSLuv
    fromBase(lch2) {
      let [l, c4, h] = [skipNone(lch2[0]), skipNone(lch2[1]), skipNone(lch2[2])];
      let s;
      if (l > 99.9999999) {
        s = 0;
        l = 100;
      } else if (l < 1e-8) {
        s = 0;
        l = 0;
      } else {
        let lines = calculateBoundingLines(l);
        let max2 = calcMaxChromaHsluv(lines, h);
        s = c4 / max2 * 100;
      }
      return [h, s, l];
    },
    // Convert HSLuv to LCHuv
    toBase(hsl) {
      let [h, s, l] = [skipNone(hsl[0]), skipNone(hsl[1]), skipNone(hsl[2])];
      let c4;
      if (l > 99.9999999) {
        l = 100;
        c4 = 0;
      } else if (l < 1e-8) {
        l = 0;
        c4 = 0;
      } else {
        let lines = calculateBoundingLines(l);
        let max2 = calcMaxChromaHsluv(lines, h);
        c4 = max2 / 100 * s;
      }
      return [l, c4, h];
    },
    formats: {
      color: {
        id: "--hsluv",
        coords: ["<number> | <angle>", "<percentage> | <number>", "<percentage> | <number>"]
      }
    }
  });
  fromXYZ_M$3[0][0];
  fromXYZ_M$3[0][1];
  fromXYZ_M$3[0][2];
  fromXYZ_M$3[1][0];
  fromXYZ_M$3[1][1];
  fromXYZ_M$3[1][2];
  fromXYZ_M$3[2][0];
  fromXYZ_M$3[2][1];
  fromXYZ_M$3[2][2];
  function distanceFromOrigin(slope, intercept) {
    return Math.abs(intercept) / Math.sqrt(Math.pow(slope, 2) + 1);
  }
  function calcMaxChromaHpluv(lines) {
    let r0 = distanceFromOrigin(lines.r0s, lines.r0i);
    let r1 = distanceFromOrigin(lines.r1s, lines.r1i);
    let g0 = distanceFromOrigin(lines.g0s, lines.g0i);
    let g1 = distanceFromOrigin(lines.g1s, lines.g1i);
    let b0 = distanceFromOrigin(lines.b0s, lines.b0i);
    let b1 = distanceFromOrigin(lines.b1s, lines.b1i);
    return Math.min(r0, r1, g0, g1, b0, b1);
  }
  var hpluv = new ColorSpace({
    id: "hpluv",
    name: "HPLuv",
    coords: {
      h: {
        refRange: [0, 360],
        type: "angle",
        name: "Hue"
      },
      s: {
        range: [0, 100],
        name: "Saturation"
      },
      l: {
        range: [0, 100],
        name: "Lightness"
      }
    },
    base: LCHuv,
    gamutSpace: "self",
    // Convert LCHuv to HPLuv
    fromBase(lch2) {
      let [l, c4, h] = [skipNone(lch2[0]), skipNone(lch2[1]), skipNone(lch2[2])];
      let s;
      if (l > 99.9999999) {
        s = 0;
        l = 100;
      } else if (l < 1e-8) {
        s = 0;
        l = 0;
      } else {
        let lines = calculateBoundingLines(l);
        let max2 = calcMaxChromaHpluv(lines);
        s = c4 / max2 * 100;
      }
      return [h, s, l];
    },
    // Convert HPLuv to LCHuv
    toBase(hsl) {
      let [h, s, l] = [skipNone(hsl[0]), skipNone(hsl[1]), skipNone(hsl[2])];
      let c4;
      if (l > 99.9999999) {
        l = 100;
        c4 = 0;
      } else if (l < 1e-8) {
        l = 0;
        c4 = 0;
      } else {
        let lines = calculateBoundingLines(l);
        let max2 = calcMaxChromaHpluv(lines);
        c4 = max2 / 100 * s;
      }
      return [l, c4, h];
    },
    formats: {
      color: {
        id: "--hpluv",
        coords: ["<number> | <angle>", "<percentage> | <number>", "<percentage> | <number>"]
      }
    }
  });
  var Yw = 203;
  var n = 2610 / 2 ** 14;
  var ninv = 2 ** 14 / 2610;
  var m = 2523 / 2 ** 5;
  var minv = 2 ** 5 / 2523;
  var c1 = 3424 / 2 ** 12;
  var c2 = 2413 / 2 ** 7;
  var c3 = 2392 / 2 ** 7;
  var rec2100Pq = new RGBColorSpace({
    id: "rec2100pq",
    cssId: "rec2100-pq",
    name: "REC.2100-PQ",
    base: REC2020Linear,
    toBase(RGB) {
      return RGB.map(function(val) {
        let x = (Math.max(val ** minv - c1, 0) / (c2 - c3 * val ** minv)) ** ninv;
        return x * 1e4 / Yw;
      });
    },
    fromBase(RGB) {
      return RGB.map(function(val) {
        let x = Math.max(val * Yw / 1e4, 0);
        let num = c1 + c2 * x ** n;
        let denom = 1 + c3 * x ** n;
        return (num / denom) ** m;
      });
    }
  });
  var a = 0.17883277;
  var b = 0.28466892;
  var c = 0.55991073;
  var scale = 3.7743;
  var rec2100Hlg = new RGBColorSpace({
    id: "rec2100hlg",
    cssId: "rec2100-hlg",
    name: "REC.2100-HLG",
    referred: "scene",
    base: REC2020Linear,
    toBase(RGB) {
      return RGB.map(function(val) {
        if (val <= 0.5) {
          return val ** 2 / 3 * scale;
        }
        return (Math.exp((val - c) / a) + b) / 12 * scale;
      });
    },
    fromBase(RGB) {
      return RGB.map(function(val) {
        val /= scale;
        if (val <= 1 / 12) {
          return Math.sqrt(3 * val);
        }
        return a * Math.log(12 * val - b) + c;
      });
    }
  });
  var CATs = {};
  hooks.add("chromatic-adaptation-start", (env) => {
    if (env.options.method) {
      env.M = adapt(env.W1, env.W2, env.options.method);
    }
  });
  hooks.add("chromatic-adaptation-end", (env) => {
    if (!env.M) {
      env.M = adapt(env.W1, env.W2, env.options.method);
    }
  });
  function defineCAT({ id, toCone_M, fromCone_M }) {
    CATs[id] = arguments[0];
  }
  function adapt(W1, W2, id = "Bradford") {
    let method = CATs[id];
    let [\u03C1s, \u03B3s, \u03B2s] = multiplyMatrices(method.toCone_M, W1);
    let [\u03C1d, \u03B3d, \u03B2d] = multiplyMatrices(method.toCone_M, W2);
    let scale2 = [
      [\u03C1d / \u03C1s, 0, 0],
      [0, \u03B3d / \u03B3s, 0],
      [0, 0, \u03B2d / \u03B2s]
    ];
    let scaled_cone_M = multiplyMatrices(scale2, method.toCone_M);
    let adapt_M = multiplyMatrices(method.fromCone_M, scaled_cone_M);
    return adapt_M;
  }
  defineCAT({
    id: "von Kries",
    toCone_M: [
      [0.40024, 0.7076, -0.08081],
      [-0.2263, 1.16532, 0.0457],
      [0, 0, 0.91822]
    ],
    fromCone_M: [
      [1.8599363874558397, -1.1293816185800916, 0.21989740959619328],
      [0.3611914362417676, 0.6388124632850422, -6370596838649899e-21],
      [0, 0, 1.0890636230968613]
    ]
  });
  defineCAT({
    id: "Bradford",
    // Convert an array of XYZ values in the range 0.0 - 1.0
    // to cone fundamentals
    toCone_M: [
      [0.8951, 0.2664, -0.1614],
      [-0.7502, 1.7135, 0.0367],
      [0.0389, -0.0685, 1.0296]
    ],
    // and back
    fromCone_M: [
      [0.9869929054667121, -0.14705425642099013, 0.15996265166373122],
      [0.4323052697233945, 0.5183602715367774, 0.049291228212855594],
      [-0.00852866457517732, 0.04004282165408486, 0.96848669578755]
    ]
  });
  defineCAT({
    id: "CAT02",
    // with complete chromatic adaptation to W2, so D = 1.0
    toCone_M: [
      [0.7328, 0.4296, -0.1624],
      [-0.7036, 1.6975, 61e-4],
      [3e-3, 0.0136, 0.9834]
    ],
    fromCone_M: [
      [1.0961238208355142, -0.27886900021828726, 0.18274517938277307],
      [0.4543690419753592, 0.4735331543074117, 0.07209780371722911],
      [-0.009627608738429355, -0.00569803121611342, 1.0153256399545427]
    ]
  });
  defineCAT({
    id: "CAT16",
    toCone_M: [
      [0.401288, 0.650173, -0.051461],
      [-0.250268, 1.204414, 0.045854],
      [-2079e-6, 0.048952, 0.953127]
    ],
    // the extra precision is needed to avoid roundtripping errors
    fromCone_M: [
      [1.862067855087233, -1.0112546305316845, 0.14918677544445172],
      [0.3875265432361372, 0.6214474419314753, -0.008973985167612521],
      [-0.01584149884933386, -0.03412293802851557, 1.0499644368778496]
    ]
  });
  Object.assign(WHITES, {
    // whitepoint values from ASTM E308-01 with 10nm spacing, 1931 2 degree observer
    // all normalized to Y (luminance) = 1.00000
    // Illuminant A is a tungsten electric light, giving a very warm, orange light.
    A: [1.0985, 1, 0.35585],
    // Illuminant C was an early approximation to daylight: illuminant A with a blue filter.
    C: [0.98074, 1, 1.18232],
    // The daylight series of illuminants simulate natural daylight.
    // The color temperature (in degrees Kelvin/100) ranges from
    // cool, overcast daylight (D50) to bright, direct sunlight (D65).
    D55: [0.95682, 1, 0.92149],
    D75: [0.94972, 1, 1.22638],
    // Equal-energy illuminant, used in two-stage CAT16
    E: [1, 1, 1],
    // The F series of illuminants represent fluorescent lights
    F2: [0.99186, 1, 0.67393],
    F7: [0.95041, 1, 1.08747],
    F11: [1.00962, 1, 0.6435]
  });
  WHITES.ACES = [0.32168 / 0.33767, 1, (1 - 0.32168 - 0.33767) / 0.33767];
  var toXYZ_M = [
    [0.6624541811085053, 0.13400420645643313, 0.1561876870049078],
    [0.27222871678091454, 0.6740817658111484, 0.05368951740793705],
    [-0.005574649490394108, 0.004060733528982826, 1.0103391003129971]
  ];
  var fromXYZ_M = [
    [1.6410233796943257, -0.32480329418479, -0.23642469523761225],
    [-0.6636628587229829, 1.6153315916573379, 0.016756347685530137],
    [0.011721894328375376, -0.008284441996237409, 0.9883948585390215]
  ];
  var ACEScg = new RGBColorSpace({
    id: "acescg",
    cssId: "--acescg",
    name: "ACEScg",
    // ACEScg – A scene-referred, linear-light encoding of ACES Data
    // https://docs.acescentral.com/specifications/acescg/
    // uses the AP1 primaries, see section 4.3.1 Color primaries
    coords: {
      r: {
        range: [0, 65504],
        name: "Red"
      },
      g: {
        range: [0, 65504],
        name: "Green"
      },
      b: {
        range: [0, 65504],
        name: "Blue"
      }
    },
    referred: "scene",
    white: WHITES.ACES,
    toXYZ_M,
    fromXYZ_M
  });
  var \u03B5 = 2 ** -16;
  var ACES_min_nonzero = -0.35828683;
  var ACES_cc_max = (Math.log2(65504) + 9.72) / 17.52;
  var acescc = new RGBColorSpace({
    id: "acescc",
    cssId: "--acescc",
    name: "ACEScc",
    // see S-2014-003 ACEScc – A Logarithmic Encoding of ACES Data
    // https://docs.acescentral.com/specifications/acescc/
    // uses the AP1 primaries, see section 4.3.1 Color primaries
    // Appendix A: "Very small ACES scene referred values below 7 1/4 stops
    // below 18% middle gray are encoded as negative ACEScc values.
    // These values should be preserved per the encoding in Section 4.4
    // so that all positive ACES values are maintained."
    coords: {
      r: {
        range: [ACES_min_nonzero, ACES_cc_max],
        name: "Red"
      },
      g: {
        range: [ACES_min_nonzero, ACES_cc_max],
        name: "Green"
      },
      b: {
        range: [ACES_min_nonzero, ACES_cc_max],
        name: "Blue"
      }
    },
    referred: "scene",
    base: ACEScg,
    // from section 4.4.2 Decoding Function
    toBase(RGB) {
      const low = (9.72 - 15) / 17.52;
      return RGB.map(function(val) {
        if (val <= low) {
          return (2 ** (val * 17.52 - 9.72) - \u03B5) * 2;
        } else if (val < ACES_cc_max) {
          return 2 ** (val * 17.52 - 9.72);
        } else {
          return 65504;
        }
      });
    },
    // Non-linear encoding function from S-2014-003, section 4.4.1 Encoding Function
    fromBase(RGB) {
      return RGB.map(function(val) {
        if (val <= 0) {
          return (Math.log2(\u03B5) + 9.72) / 17.52;
        } else if (val < \u03B5) {
          return (Math.log2(\u03B5 + val * 0.5) + 9.72) / 17.52;
        } else {
          return (Math.log2(val) + 9.72) / 17.52;
        }
      });
    }
    // encoded media white (rgb 1,1,1) => linear  [ 222.861, 222.861, 222.861 ]
    // encoded media black (rgb 0,0,0) => linear [ 0.0011857, 0.0011857, 0.0011857]
  });
  var spaces = /* @__PURE__ */ Object.freeze({
    __proto__: null,
    A98RGB: a98rgb,
    A98RGB_Linear: A98Linear,
    ACEScc: acescc,
    ACEScg,
    CAM16_JMh: cam16,
    HCT: hct,
    HPLuv: hpluv,
    HSL,
    HSLuv: hsluv,
    HSV,
    HWB: hwb,
    ICTCP: ictcp,
    JzCzHz: jzczhz,
    Jzazbz,
    LCH: lch,
    LCHuv,
    Lab: lab,
    Lab_D65: lab_d65,
    Luv,
    OKLCH: oklch,
    OKLab,
    P3,
    P3_Linear: P3Linear,
    ProPhoto: prophoto,
    ProPhoto_Linear: ProPhotoLinear,
    REC_2020: REC2020,
    REC_2020_Linear: REC2020Linear,
    REC_2100_HLG: rec2100Hlg,
    REC_2100_PQ: rec2100Pq,
    XYZ_ABS_D65: XYZ_Abs_D65,
    XYZ_D50,
    XYZ_D65: xyz_d65,
    sRGB,
    sRGB_Linear: sRGBLinear
  });
  var Color = class _Color {
    /**
     * Creates an instance of Color.
     * Signatures:
     * - `new Color(stringToParse)`
     * - `new Color(otherColor)`
     * - `new Color({space, coords, alpha})`
     * - `new Color(space, coords, alpha)`
     * - `new Color(spaceId, coords, alpha)`
     */
    constructor(...args) {
      let color;
      if (args.length === 1) {
        color = getColor(args[0]);
      }
      let space, coords, alpha;
      if (color) {
        space = color.space || color.spaceId;
        coords = color.coords;
        alpha = color.alpha;
      } else {
        [space, coords, alpha] = args;
      }
      Object.defineProperty(this, "space", {
        value: ColorSpace.get(space),
        writable: false,
        enumerable: true,
        configurable: true
        // see note in https://262.ecma-international.org/8.0/#sec-proxy-object-internal-methods-and-internal-slots-get-p-receiver
      });
      this.coords = coords ? coords.slice() : [0, 0, 0];
      this.alpha = alpha > 1 || alpha === void 0 ? 1 : alpha < 0 ? 0 : alpha;
      for (let i = 0; i < this.coords.length; i++) {
        if (this.coords[i] === "NaN") {
          this.coords[i] = NaN;
        }
      }
      for (let id in this.space.coords) {
        Object.defineProperty(this, id, {
          get: () => this.get(id),
          set: (value) => this.set(id, value)
        });
      }
    }
    get spaceId() {
      return this.space.id;
    }
    clone() {
      return new _Color(this.space, this.coords, this.alpha);
    }
    toJSON() {
      return {
        spaceId: this.spaceId,
        coords: this.coords,
        alpha: this.alpha
      };
    }
    display(...args) {
      let ret = display(this, ...args);
      ret.color = new _Color(ret.color);
      return ret;
    }
    /**
     * Get a color from the argument passed
     * Basically gets us the same result as new Color(color) but doesn't clone an existing color object
     */
    static get(color, ...args) {
      if (color instanceof _Color) {
        return color;
      }
      return new _Color(color, ...args);
    }
    static defineFunction(name, code, o = code) {
      let { instance = true, returns } = o;
      let func = function(...args) {
        let ret = code(...args);
        if (returns === "color") {
          ret = _Color.get(ret);
        } else if (returns === "function<color>") {
          let f = ret;
          ret = function(...args2) {
            let ret2 = f(...args2);
            return _Color.get(ret2);
          };
          Object.assign(ret, f);
        } else if (returns === "array<color>") {
          ret = ret.map((c4) => _Color.get(c4));
        }
        return ret;
      };
      if (!(name in _Color)) {
        _Color[name] = func;
      }
      if (instance) {
        _Color.prototype[name] = function(...args) {
          return func(this, ...args);
        };
      }
    }
    static defineFunctions(o) {
      for (let name in o) {
        _Color.defineFunction(name, o[name], o[name]);
      }
    }
    static extend(exports) {
      if (exports.register) {
        exports.register(_Color);
      } else {
        for (let name in exports) {
          _Color.defineFunction(name, exports[name]);
        }
      }
    }
  };
  Color.defineFunctions({
    get,
    getAll,
    set,
    setAll,
    to,
    equals,
    inGamut,
    toGamut,
    distance,
    toString: serialize
  });
  Object.assign(Color, {
    util,
    hooks,
    WHITES,
    Space: ColorSpace,
    spaces: ColorSpace.registry,
    parse,
    // Global defaults one may want to configure
    defaults
  });
  for (let key of Object.keys(spaces)) {
    ColorSpace.register(spaces[key]);
  }
  for (let id in ColorSpace.registry) {
    addSpaceAccessors(id, ColorSpace.registry[id]);
  }
  hooks.add("colorspace-init-end", (space) => {
    addSpaceAccessors(space.id, space);
    space.aliases?.forEach((alias) => {
      addSpaceAccessors(alias, space);
    });
  });
  function addSpaceAccessors(id, space) {
    let propId = id.replace(/-/g, "_");
    Object.defineProperty(Color.prototype, propId, {
      // Convert coords to coords in another colorspace and return them
      // Source colorspace: this.spaceId
      // Target colorspace: id
      get() {
        let ret = this.getAll(id);
        if (typeof Proxy === "undefined") {
          return ret;
        }
        return new Proxy(ret, {
          has: (obj, property) => {
            try {
              ColorSpace.resolveCoord([space, property]);
              return true;
            } catch (e) {
            }
            return Reflect.has(obj, property);
          },
          get: (obj, property, receiver) => {
            if (property && typeof property !== "symbol" && !(property in obj)) {
              let { index } = ColorSpace.resolveCoord([space, property]);
              if (index >= 0) {
                return obj[index];
              }
            }
            return Reflect.get(obj, property, receiver);
          },
          set: (obj, property, value, receiver) => {
            if (property && typeof property !== "symbol" && !(property in obj) || property >= 0) {
              let { index } = ColorSpace.resolveCoord([space, property]);
              if (index >= 0) {
                obj[index] = value;
                this.setAll(id, obj);
                return true;
              }
            }
            return Reflect.set(obj, property, value, receiver);
          }
        });
      },
      // Convert coords in another colorspace to internal coords and set them
      // Target colorspace: this.spaceId
      // Source colorspace: id
      set(coords) {
        this.setAll(id, coords);
      },
      configurable: true,
      enumerable: true
    });
  }
  Color.extend(deltaEMethods);
  Color.extend({ deltaE });
  Object.assign(Color, { deltaEMethods });
  Color.extend(variations);
  Color.extend({ contrast });
  Color.extend(chromaticity);
  Color.extend(luminance);
  Color.extend(interpolation);
  Color.extend(contrastMethods);

  // packages/theme/build-module/use-theme-provider-styles.js
  var import_element2 = __toESM(require_element());

  // packages/theme/build-module/prebuilt/ts/design-tokens.js
  var design_tokens_default = {
    "--wpds-border-radius-x-small": {
      ".": "1px"
    },
    "--wpds-border-radius-small": {
      ".": "2px"
    },
    "--wpds-border-radius-medium": {
      ".": "4px"
    },
    "--wpds-border-radius-large": {
      ".": "8px"
    },
    "--wpds-border-width-focus": {
      ".": "2px",
      "high-dpi": "1.5px"
    },
    "--wpds-color-bg-surface-neutral": {
      ".": "var(--wpds-color-private-bg-surface2)"
    },
    "--wpds-color-bg-surface-neutral-strong": {
      ".": "var(--wpds-color-private-bg-surface3)"
    },
    "--wpds-color-bg-surface-neutral-weak": {
      ".": "var(--wpds-color-private-bg-surface1)"
    },
    "--wpds-color-bg-surface-brand": {
      ".": "var(--wpds-color-private-primary-surface1)"
    },
    "--wpds-color-bg-surface-success": {
      ".": "var(--wpds-color-private-success-surface4)"
    },
    "--wpds-color-bg-surface-success-weak": {
      ".": "var(--wpds-color-private-success-surface2)"
    },
    "--wpds-color-bg-surface-info": {
      ".": "var(--wpds-color-private-info-surface4)"
    },
    "--wpds-color-bg-surface-info-weak": {
      ".": "var(--wpds-color-private-info-surface2)"
    },
    "--wpds-color-bg-surface-warning": {
      ".": "var(--wpds-color-private-warning-surface4)"
    },
    "--wpds-color-bg-surface-warning-weak": {
      ".": "var(--wpds-color-private-warning-surface2)"
    },
    "--wpds-color-bg-surface-error": {
      ".": "var(--wpds-color-private-error-surface4)"
    },
    "--wpds-color-bg-surface-error-weak": {
      ".": "var(--wpds-color-private-error-surface2)"
    },
    "--wpds-color-bg-interactive-neutral": {
      ".": "#00000000"
    },
    "--wpds-color-bg-interactive-neutral-active": {
      ".": "var(--wpds-color-private-bg-surface4)"
    },
    "--wpds-color-bg-interactive-neutral-disabled": {
      ".": "var(--wpds-color-private-bg-surface5)"
    },
    "--wpds-color-bg-interactive-neutral-strong": {
      ".": "var(--wpds-color-private-bg-bg-fill-inverted1)"
    },
    "--wpds-color-bg-interactive-neutral-strong-active": {
      ".": "var(--wpds-color-private-bg-bg-fill-inverted2)"
    },
    "--wpds-color-bg-interactive-neutral-strong-disabled": {
      ".": "var(--wpds-color-private-bg-surface6)"
    },
    "--wpds-color-bg-interactive-neutral-weak": {
      ".": "#00000000"
    },
    "--wpds-color-bg-interactive-neutral-weak-active": {
      ".": "var(--wpds-color-private-bg-surface4)"
    },
    "--wpds-color-bg-interactive-neutral-weak-disabled": {
      ".": "var(--wpds-color-private-bg-surface5)"
    },
    "--wpds-color-bg-interactive-brand": {
      ".": "#00000000"
    },
    "--wpds-color-bg-interactive-brand-active": {
      ".": "var(--wpds-color-private-primary-surface2)"
    },
    "--wpds-color-bg-interactive-brand-disabled": {
      ".": "var(--wpds-color-private-bg-surface5)"
    },
    "--wpds-color-bg-interactive-brand-strong": {
      ".": "var(--wpds-color-private-primary-bg-fill1)"
    },
    "--wpds-color-bg-interactive-brand-strong-active": {
      ".": "var(--wpds-color-private-primary-bg-fill2)"
    },
    "--wpds-color-bg-interactive-brand-strong-disabled": {
      ".": "var(--wpds-color-private-bg-surface6)"
    },
    "--wpds-color-bg-interactive-brand-weak": {
      ".": "#00000000"
    },
    "--wpds-color-bg-interactive-brand-weak-active": {
      ".": "var(--wpds-color-private-primary-surface4)"
    },
    "--wpds-color-bg-interactive-brand-weak-disabled": {
      ".": "var(--wpds-color-private-bg-surface5)"
    },
    "--wpds-color-bg-track-neutral-weak": {
      ".": "var(--wpds-color-private-bg-stroke1)"
    },
    "--wpds-color-bg-track-neutral": {
      ".": "var(--wpds-color-private-bg-stroke2)"
    },
    "--wpds-color-bg-thumb-neutral-weak": {
      ".": "var(--wpds-color-private-bg-stroke3)"
    },
    "--wpds-color-bg-thumb-neutral-weak-active": {
      ".": "var(--wpds-color-private-bg-stroke4)"
    },
    "--wpds-color-bg-thumb-brand": {
      ".": "var(--wpds-color-private-primary-stroke3)"
    },
    "--wpds-color-bg-thumb-brand-active": {
      ".": "var(--wpds-color-private-primary-stroke3)"
    },
    "--wpds-color-bg-thumb-brand-disabled": {
      ".": "var(--wpds-color-private-bg-stroke2)"
    },
    "--wpds-color-fg-content-neutral": {
      ".": "var(--wpds-color-private-bg-fg-surface4)"
    },
    "--wpds-color-fg-content-neutral-weak": {
      ".": "var(--wpds-color-private-bg-fg-surface3)"
    },
    "--wpds-color-fg-interactive-neutral": {
      ".": "var(--wpds-color-private-bg-fg-surface4)"
    },
    "--wpds-color-fg-interactive-neutral-active": {
      ".": "var(--wpds-color-private-bg-fg-surface4)"
    },
    "--wpds-color-fg-interactive-neutral-disabled": {
      ".": "var(--wpds-color-private-bg-fg-surface2)"
    },
    "--wpds-color-fg-interactive-neutral-strong": {
      ".": "var(--wpds-color-private-bg-fg-fill-inverted)"
    },
    "--wpds-color-fg-interactive-neutral-strong-active": {
      ".": "var(--wpds-color-private-bg-fg-fill-inverted)"
    },
    "--wpds-color-fg-interactive-neutral-strong-disabled": {
      ".": "var(--wpds-color-private-bg-fg-surface3)"
    },
    "--wpds-color-fg-interactive-neutral-weak": {
      ".": "var(--wpds-color-private-bg-fg-surface3)"
    },
    "--wpds-color-fg-interactive-neutral-weak-disabled": {
      ".": "var(--wpds-color-private-bg-fg-surface2)"
    },
    "--wpds-color-fg-interactive-brand": {
      ".": "var(--wpds-color-private-primary-fg-surface3)"
    },
    "--wpds-color-fg-interactive-brand-active": {
      ".": "var(--wpds-color-private-primary-fg-surface3)"
    },
    "--wpds-color-fg-interactive-brand-disabled": {
      ".": "var(--wpds-color-private-bg-fg-surface2)"
    },
    "--wpds-color-fg-interactive-brand-strong": {
      ".": "var(--wpds-color-private-primary-fg-fill)"
    },
    "--wpds-color-fg-interactive-brand-strong-active": {
      ".": "var(--wpds-color-private-primary-fg-fill)"
    },
    "--wpds-color-fg-interactive-brand-strong-disabled": {
      ".": "var(--wpds-color-private-bg-fg-surface3)"
    },
    "--wpds-color-stroke-surface-neutral": {
      ".": "var(--wpds-color-private-bg-stroke2)"
    },
    "--wpds-color-stroke-surface-neutral-weak": {
      ".": "var(--wpds-color-private-bg-stroke1)"
    },
    "--wpds-color-stroke-surface-neutral-strong": {
      ".": "var(--wpds-color-private-bg-stroke3)"
    },
    "--wpds-color-stroke-surface-brand": {
      ".": "var(--wpds-color-private-primary-stroke1)"
    },
    "--wpds-color-stroke-surface-brand-strong": {
      ".": "var(--wpds-color-private-primary-stroke3)"
    },
    "--wpds-color-stroke-surface-success": {
      ".": "var(--wpds-color-private-success-stroke1)"
    },
    "--wpds-color-stroke-surface-success-strong": {
      ".": "var(--wpds-color-private-success-stroke3)"
    },
    "--wpds-color-stroke-surface-info": {
      ".": "var(--wpds-color-private-info-stroke1)"
    },
    "--wpds-color-stroke-surface-info-strong": {
      ".": "var(--wpds-color-private-info-stroke3)"
    },
    "--wpds-color-stroke-surface-warning": {
      ".": "var(--wpds-color-private-warning-stroke1)"
    },
    "--wpds-color-stroke-surface-warning-strong": {
      ".": "var(--wpds-color-private-warning-stroke3)"
    },
    "--wpds-color-stroke-surface-error": {
      ".": "var(--wpds-color-private-error-stroke1)"
    },
    "--wpds-color-stroke-surface-error-strong": {
      ".": "var(--wpds-color-private-error-stroke3)"
    },
    "--wpds-color-stroke-interactive-neutral": {
      ".": "var(--wpds-color-private-bg-stroke3)"
    },
    "--wpds-color-stroke-interactive-neutral-active": {
      ".": "var(--wpds-color-private-bg-stroke4)"
    },
    "--wpds-color-stroke-interactive-neutral-disabled": {
      ".": "var(--wpds-color-private-bg-stroke2)"
    },
    "--wpds-color-stroke-interactive-neutral-strong": {
      ".": "var(--wpds-color-private-bg-stroke4)"
    },
    "--wpds-color-stroke-interactive-brand": {
      ".": "var(--wpds-color-private-primary-stroke3)"
    },
    "--wpds-color-stroke-interactive-brand-active": {
      ".": "var(--wpds-color-private-primary-stroke4)"
    },
    "--wpds-color-stroke-interactive-brand-disabled": {
      ".": "var(--wpds-color-private-bg-stroke2)"
    },
    "--wpds-color-stroke-interactive-error-strong": {
      ".": "var(--wpds-color-private-error-stroke3)"
    },
    "--wpds-color-stroke-focus-brand": {
      ".": "var(--wpds-color-private-primary-stroke3)"
    },
    "--wpds-elevation-x-small": {
      ".": "0 1px 1px 0 #00000008, 0 1px 2px 0 #00000005, 0 3px 3px 0 #00000005, 0 4px 4px 0 #00000003"
    },
    "--wpds-elevation-small": {
      ".": "0 1px 2px 0 #0000000d, 0 2px 3px 0 #0000000a, 0 6px 6px 0 #00000008, 0 8px 8px 0 #00000005"
    },
    "--wpds-elevation-medium": {
      ".": "0 2px 3px 0 #0000000d, 0 4px 5px 0 #0000000a, 0 12px 12px 0 #00000008, 0 16px 16px 0 #00000005"
    },
    "--wpds-elevation-large": {
      ".": "0 5px 15px 0 #00000014, 0 15px 27px 0 #00000012, 0 30px 36px 0 #0000000a, 0 50px 43px 0 #00000005"
    },
    "--wpds-spacing-05": {
      ".": "4px"
    },
    "--wpds-spacing-10": {
      ".": "8px"
    },
    "--wpds-spacing-15": {
      ".": "12px"
    },
    "--wpds-spacing-20": {
      ".": "16px"
    },
    "--wpds-spacing-30": {
      ".": "24px"
    },
    "--wpds-spacing-40": {
      ".": "32px"
    },
    "--wpds-spacing-50": {
      ".": "40px"
    },
    "--wpds-spacing-60": {
      ".": "48px"
    },
    "--wpds-spacing-70": {
      ".": "56px"
    },
    "--wpds-spacing-80": {
      ".": "64px"
    },
    "--wpds-font-family-heading": {
      ".": '-apple-system, system-ui, "Segoe UI", "Roboto", "Oxygen-Sans", "Ubuntu", "Cantarell", "Helvetica Neue", sans-serif'
    },
    "--wpds-font-family-body": {
      ".": '-apple-system, system-ui, "Segoe UI", "Roboto", "Oxygen-Sans", "Ubuntu", "Cantarell", "Helvetica Neue", sans-serif'
    },
    "--wpds-font-family-mono": {
      ".": '"Menlo", "Consolas", monaco, monospace'
    },
    "--wpds-font-size-x-small": {
      ".": "11px"
    },
    "--wpds-font-size-small": {
      ".": "12px"
    },
    "--wpds-font-size-medium": {
      ".": "13px"
    },
    "--wpds-font-size-large": {
      ".": "15px"
    },
    "--wpds-font-size-x-large": {
      ".": "20px"
    },
    "--wpds-font-size-2x-large": {
      ".": "32px"
    },
    "--wpds-font-line-height-x-small": {
      ".": "16px"
    },
    "--wpds-font-line-height-small": {
      ".": "20px"
    },
    "--wpds-font-line-height-medium": {
      ".": "24px"
    },
    "--wpds-font-line-height-large": {
      ".": "28px"
    },
    "--wpds-font-line-height-x-large": {
      ".": "32px"
    },
    "--wpds-font-line-height-2x-large": {
      ".": "40px"
    }
  };

  // packages/theme/build-module/color-ramps/lib/cache-utils.js
  var contrastCache = /* @__PURE__ */ new Map();
  var colorStringCache = /* @__PURE__ */ new Map();
  function getColorString(color) {
    let str = colorStringCache.get(color);
    if (str === void 0) {
      str = color.to("srgb").toString({ format: "hex", inGamut: true });
      colorStringCache.set(color, str);
    }
    return str;
  }
  function getCachedContrast(colorA, colorB) {
    const keyA = getColorString(colorA);
    const keyB = getColorString(colorB);
    const cacheKey = keyA < keyB ? `${keyA}|${keyB}` : `${keyB}|${keyA}`;
    let contrast2 = contrastCache.get(cacheKey);
    if (contrast2 === void 0) {
      contrast2 = colorA.contrastWCAG21(colorB);
      contrastCache.set(cacheKey, contrast2);
    }
    return contrast2;
  }

  // packages/theme/build-module/color-ramps/lib/constants.js
  var WHITE = new Color("#fff").to("oklch");
  var BLACK = new Color("#000").to("oklch");
  var UNIVERSAL_CONTRAST_TOPUP = 0.05;
  var WHITE_TEXT_CONTRAST_MARGIN = 3.1;
  var ACCENT_SCALE_BASE_LIGHTNESS_THRESHOLDS = {
    lighter: { min: 0.2, max: 0.4 },
    darker: { min: 0.75, max: 0.98 }
  };
  var LIGHTNESS_EPSILON = 1e-3;
  var MAX_BISECTION_ITERATIONS = 25;
  var DEFAULT_SEED_COLORS = {
    bg: "#f8f8f8",
    primary: "#3858e9",
    info: "#0090ff",
    success: "#4ab866",
    warning: "#f0b849",
    error: "#cc1818"
  };

  // packages/theme/build-module/color-ramps/lib/utils.js
  var clampToGamut = (c4) => c4.toGamut({ space: "p3", method: "css" }).to("oklch");
  function buildDependencyGraph(config) {
    const dependencies = /* @__PURE__ */ new Map();
    const dependents = /* @__PURE__ */ new Map();
    Object.keys(config).forEach((step) => {
      dependencies.set(step, []);
    });
    dependents.set("seed", []);
    Object.keys(config).forEach((step) => {
      dependents.set(step, []);
    });
    Object.entries(config).forEach(([stepName, stepConfig]) => {
      const step = stepName;
      const reference = stepConfig.contrast.reference;
      dependencies.get(step).push(reference);
      dependents.get(reference).push(step);
      if (stepConfig.sameAsIfPossible) {
        dependencies.get(step).push(stepConfig.sameAsIfPossible);
        dependents.get(stepConfig.sameAsIfPossible).push(step);
      }
    });
    return { dependencies, dependents };
  }
  function sortByDependency(config) {
    const { dependents } = buildDependencyGraph(config);
    const result = [];
    const visited = /* @__PURE__ */ new Set();
    const visiting = /* @__PURE__ */ new Set();
    function visit(node) {
      if (visiting.has(node)) {
        throw new Error(
          `Circular dependency detected involving step: ${String(
            node
          )}`
        );
      }
      if (visited.has(node)) {
        return;
      }
      visiting.add(node);
      const nodeDependents = dependents.get(node) || [];
      nodeDependents.forEach((dependent) => {
        visit(dependent);
      });
      visiting.delete(node);
      visited.add(node);
      if (node !== "seed") {
        result.unshift(node);
      }
    }
    visit("seed");
    return result;
  }
  function computeBetterFgColorDirection(seed, preferLighter) {
    const contrastAgainstBlack = getCachedContrast(seed, BLACK);
    const contrastAgainstWhite = getCachedContrast(seed, WHITE);
    return contrastAgainstBlack > contrastAgainstWhite + (preferLighter ? WHITE_TEXT_CONTRAST_MARGIN : 0) ? { better: "darker", worse: "lighter" } : { better: "lighter", worse: "darker" };
  }
  function adjustContrastTarget(target) {
    if (target === 1) {
      return 1;
    }
    return target + UNIVERSAL_CONTRAST_TOPUP;
  }
  function clampAccentScaleReferenceLightness(rawLightness, direction) {
    const thresholds = ACCENT_SCALE_BASE_LIGHTNESS_THRESHOLDS[direction];
    return Math.max(thresholds.min, Math.min(thresholds.max, rawLightness));
  }

  // packages/theme/build-module/color-ramps/lib/taper-chroma.js
  function taperChroma(seed, lTarget, options = {}) {
    const gamut = options.gamut ?? "p3";
    const alpha = options.alpha ?? 0.65;
    const carry = options.carry ?? 0.5;
    const cUpperBound = options.cUpperBound ?? 0.45;
    const radiusLight = options.radiusLight ?? 0.2;
    const radiusDark = options.radiusDark ?? 0.2;
    const kLight = options.kLight ?? 0.85;
    const kDark = options.kDark ?? 0.85;
    const achromaEpsilon = options.achromaEpsilon ?? 5e-3;
    const cSeed = Math.max(0, seed.oklch.c);
    let hSeed = Number(seed.oklch.h);
    const chromaIsTiny = cSeed < achromaEpsilon;
    const hueIsInvalid = !Number.isFinite(hSeed);
    if (chromaIsTiny || hueIsInvalid) {
      if (typeof options.hueFallback === "number") {
        hSeed = normalizeHue(options.hueFallback);
      } else {
        return new Color("oklch", [clamp01(lTarget), 0, 0]);
      }
    }
    const lSeed = clamp01(seed.oklch.l);
    const cmaxSeed = getCachedMaxChromaAtLH(lSeed, hSeed, gamut, cUpperBound);
    const cmaxTarget = getCachedMaxChromaAtLH(
      clamp01(lTarget),
      hSeed,
      gamut,
      cUpperBound
    );
    let seedRelative = 0;
    const denom = cmaxSeed > 0 ? cmaxSeed : 1e-6;
    seedRelative = clamp01(cSeed / denom);
    const cIntendedBase = alpha * cmaxTarget;
    const cWithCarry = cIntendedBase * Math.pow(seedRelative, clamp01(carry));
    const t = continuousTaper(lSeed, lTarget, {
      radiusLight,
      radiusDark,
      kLight,
      kDark
    });
    let cPlanned = cWithCarry * t;
    const lOut = clamp01(lTarget);
    const candidate = new Color("oklch", [lOut, cPlanned, hSeed]);
    if (!candidate.inGamut(gamut)) {
      const cap = Math.min(cPlanned, cUpperBound);
      cPlanned = getCachedMaxChromaAtLH(lOut, hSeed, gamut, cap);
    }
    cPlanned = Math.min(cPlanned, cSeed);
    return { l: lOut, c: cPlanned };
  }
  function clamp01(x) {
    if (x < 0) {
      return 0;
    }
    if (x > 1) {
      return 1;
    }
    return x;
  }
  function normalizeHue(h) {
    let hue = h % 360;
    if (hue < 0) {
      hue += 360;
    }
    return hue;
  }
  function raisedCosine(u) {
    const x = clamp01(u);
    return 0.5 - 0.5 * Math.cos(Math.PI * x);
  }
  function continuousTaper(seedL, targetL, opts) {
    const d2 = targetL - seedL;
    if (d2 >= 0) {
      const u2 = opts.radiusLight > 0 ? Math.abs(d2) / opts.radiusLight : 1;
      const w2 = raisedCosine(u2 > 1 ? 1 : u2);
      return 1 - (1 - opts.kLight) * w2;
    }
    const u = opts.radiusDark > 0 ? Math.abs(d2) / opts.radiusDark : 1;
    const w = raisedCosine(u > 1 ? 1 : u);
    return 1 - (1 - opts.kDark) * w;
  }
  var maxChromaCache = /* @__PURE__ */ new Map();
  function keyMax(l, h, gamut, cap) {
    const lq = quantize(l, 1e-3);
    const hq = quantize(normalizeHue(h), 0.1);
    const cq = quantize(cap, 1e-3);
    return `${gamut}|L:${lq}|H:${hq}|cap:${cq}`;
  }
  function quantize(x, step) {
    const k = Math.round(x / step);
    return k * step;
  }
  function getCachedMaxChromaAtLH(l, h, gamut, cap) {
    const key = keyMax(l, h, gamut, cap);
    const hit = maxChromaCache.get(key);
    if (typeof hit === "number") {
      return hit;
    }
    const computed = maxInGamutChromaAtLH(l, h, gamut, cap);
    maxChromaCache.set(key, computed);
    return computed;
  }
  function maxInGamutChromaAtLH(l, h, gamut, cap) {
    let lo = 0;
    let hi = cap;
    let ok = 0;
    const lFixed = clamp01(l);
    const hFixed = normalizeHue(h);
    for (let i = 0; i < 18; i++) {
      const mid = (lo + hi) / 2;
      const probe = new Color("oklch", [lFixed, mid, hFixed]);
      if (probe.inGamut(gamut)) {
        ok = mid;
        lo = mid;
      } else {
        hi = mid;
      }
    }
    return ok;
  }

  // packages/theme/build-module/color-ramps/lib/find-color-with-constraints.js
  function findColorMeetingRequirements(reference, seed, target, direction, {
    lightnessConstraint,
    taperChromaOptions,
    strict = true,
    debug = false
  } = {}) {
    if (target <= 1) {
      return { color: seed.clone(), reached: true, achieved: 1 };
    }
    if (lightnessConstraint) {
      let newL = lightnessConstraint.value;
      let newC = seed.oklch.c;
      if (taperChromaOptions) {
        ({ l: newL, c: newC } = taperChroma(
          seed,
          newL,
          taperChromaOptions
        ));
      }
      const colorWithExactL = clampToGamut(
        new Color("oklch", [newL, newC, seed.oklch.h])
      );
      const exactLContrast = getCachedContrast(reference, colorWithExactL);
      if (debug) {
        console.log(
          `Succeeded with ${lightnessConstraint.type} lightness`,
          lightnessConstraint.value,
          colorWithExactL.oklch.l
        );
      }
      if (lightnessConstraint.type === "force" || exactLContrast >= target) {
        return {
          color: colorWithExactL,
          reached: exactLContrast >= target,
          achieved: exactLContrast
        };
      }
    }
    const mostContrastingL = direction === "lighter" ? 1 : 0;
    const mostContrastingColor = direction === "lighter" ? WHITE : BLACK;
    const highestPossibleContrast = getCachedContrast(
      reference,
      mostContrastingColor
    );
    if (highestPossibleContrast < target) {
      if (strict) {
        throw new Error(
          `Contrast target ${target.toFixed(
            2
          )}:1 unreachable in ${direction} direction against ${mostContrastingColor.toString()}(boundary achieves ${highestPossibleContrast.toFixed(
            3
          )}:1).`
        );
      }
      if (debug) {
        console.log(
          "Did not succeeded because it reached the limit",
          mostContrastingL
        );
      }
      return {
        color: mostContrastingColor,
        reached: false,
        achieved: highestPossibleContrast
      };
    }
    let worseL = reference.oklch.l;
    let betterL = mostContrastingL;
    let bestContrastFound = highestPossibleContrast;
    let resultingColor = mostContrastingColor;
    for (let i = 0; i < MAX_BISECTION_ITERATIONS && Math.abs(betterL - worseL) > LIGHTNESS_EPSILON; i++) {
      let newL = (worseL + betterL) / 2;
      let newC = seed.oklch.c;
      if (taperChromaOptions) {
        ({ l: newL, c: newC } = taperChroma(
          seed,
          newL,
          taperChromaOptions
        ));
      }
      const newColor = clampToGamut(
        new Color("oklch", [newL, newC, seed.oklch.h])
      );
      const newContrast = getCachedContrast(reference, newColor);
      if (newContrast >= target) {
        betterL = newL;
        bestContrastFound = newContrast;
        resultingColor = newColor;
      } else {
        worseL = newL;
      }
    }
    return {
      color: resultingColor,
      reached: true,
      achieved: bestContrastFound
    };
  }

  // packages/theme/build-module/color-ramps/lib/index.js
  function calculateRamp({
    seed,
    sortedSteps,
    config,
    mainDir,
    oppDir,
    pinLightness,
    debug = false
  }) {
    const rampResults = {};
    let SATISFIED_ALL_CONTRAST_REQUIREMENTS = true;
    let UNSATISFIED_DIRECTION = "lighter";
    let MAX_WEIGHTED_DEFICIT = 0;
    const calculatedColors = /* @__PURE__ */ new Map();
    calculatedColors.set("seed", seed);
    for (const stepName of sortedSteps) {
      let computeDirection2 = function(color, followDirection) {
        if (followDirection === "main") {
          return mainDir;
        }
        if (followDirection === "opposite") {
          return oppDir;
        }
        if (followDirection === "best") {
          return computeBetterFgColorDirection(
            color,
            contrast2.preferLighter
          ).better;
        }
        return followDirection;
      };
      var computeDirection = computeDirection2;
      const {
        contrast: contrast2,
        lightness: stepLightnessConstraint,
        taperChromaOptions,
        sameAsIfPossible
      } = config[stepName];
      const referenceColor = calculatedColors.get(contrast2.reference);
      if (!referenceColor) {
        throw new Error(
          `Reference color for step ${stepName} not found: ${contrast2.reference}`
        );
      }
      if (sameAsIfPossible) {
        const candidateColor = calculatedColors.get(sameAsIfPossible);
        if (candidateColor) {
          const candidateContrast = getCachedContrast(
            referenceColor,
            candidateColor
          );
          const adjustedTarget2 = adjustContrastTarget(contrast2.target);
          if (candidateContrast >= adjustedTarget2) {
            calculatedColors.set(stepName, candidateColor);
            rampResults[stepName] = {
              color: getColorString(candidateColor),
              warning: false
            };
            continue;
          }
        }
      }
      const computedDir = computeDirection2(
        referenceColor,
        contrast2.followDirection
      );
      const adjustedTarget = adjustContrastTarget(contrast2.target);
      let lightnessConstraint;
      if (pinLightness?.stepName === stepName) {
        lightnessConstraint = {
          value: pinLightness.value,
          type: "force"
        };
      } else if (stepLightnessConstraint) {
        lightnessConstraint = {
          value: stepLightnessConstraint(computedDir),
          type: "onlyIfSucceeds"
        };
      }
      const searchResults = findColorMeetingRequirements(
        referenceColor,
        seed,
        adjustedTarget,
        computedDir,
        {
          strict: false,
          lightnessConstraint,
          taperChromaOptions,
          debug
        }
      );
      if (!searchResults.reached && !contrast2.ignoreWhenAdjustingSeed) {
        SATISFIED_ALL_CONTRAST_REQUIREMENTS = false;
        const deficitVsTarget = adjustedTarget - searchResults.achieved;
        const impactWeight = 1 / getCachedContrast(seed, referenceColor);
        const weightedDeficit = deficitVsTarget * impactWeight;
        if (weightedDeficit > MAX_WEIGHTED_DEFICIT) {
          MAX_WEIGHTED_DEFICIT = weightedDeficit;
          UNSATISFIED_DIRECTION = computedDir;
        }
      }
      calculatedColors.set(stepName, searchResults.color);
      rampResults[stepName] = {
        color: getColorString(searchResults.color),
        warning: !contrast2.ignoreWhenAdjustingSeed && !searchResults.reached
      };
    }
    return {
      rampResults,
      SATISFIED_ALL_CONTRAST_REQUIREMENTS,
      UNSATISFIED_DIRECTION
    };
  }
  function buildRamp(seedArg, config, {
    mainDirection,
    pinLightness,
    debug = false,
    rescaleToFitContrastTargets = true
  } = {}) {
    let seed;
    try {
      seed = clampToGamut(new Color(seedArg));
    } catch (error) {
      throw new Error(
        `Invalid seed color "${seedArg}": ${error instanceof Error ? error.message : "Unknown error"}`
      );
    }
    let mainDir = "lighter";
    let oppDir = "darker";
    if (mainDirection) {
      mainDir = mainDirection;
      oppDir = mainDirection === "darker" ? "lighter" : "darker";
    } else {
      const { better, worse } = computeBetterFgColorDirection(seed);
      mainDir = better;
      oppDir = worse;
    }
    const sortedSteps = sortByDependency(config);
    const {
      rampResults,
      SATISFIED_ALL_CONTRAST_REQUIREMENTS,
      UNSATISFIED_DIRECTION
    } = calculateRamp({
      seed,
      sortedSteps,
      config,
      mainDir,
      oppDir,
      pinLightness,
      debug
    });
    const toReturn = {
      ramp: rampResults,
      direction: mainDir
    };
    if (debug) {
      console.log(`First run`, {
        SATISFIED_ALL_CONTRAST_REQUIREMENTS,
        UNSATISFIED_DIRECTION,
        seed: seed.toString(),
        sortedSteps,
        config,
        mainDir,
        oppDir,
        pinLightness
      });
    }
    if (!SATISFIED_ALL_CONTRAST_REQUIREMENTS && rescaleToFitContrastTargets) {
      let worseSeedL = seed.oklch.l;
      let betterSeedL = UNSATISFIED_DIRECTION === "lighter" ? 0 : 1;
      for (let i = 0; i < MAX_BISECTION_ITERATIONS && Math.abs(betterSeedL - worseSeedL) > LIGHTNESS_EPSILON; i++) {
        const newSeed = clampToGamut(
          seed.clone().set({
            l: (worseSeedL + betterSeedL) / 2
          })
        );
        if (debug) {
          console.log(`Iteration ${i}`, {
            worseSeedL,
            newSeedL: (worseSeedL + betterSeedL) / 2,
            betterSeedL
          });
        }
        const iterationResults = calculateRamp({
          seed: newSeed,
          sortedSteps,
          config,
          mainDir,
          oppDir,
          pinLightness,
          debug
        });
        if (iterationResults.SATISFIED_ALL_CONTRAST_REQUIREMENTS) {
          betterSeedL = newSeed.oklch.l;
          toReturn.ramp = iterationResults.rampResults;
        } else if (UNSATISFIED_DIRECTION !== mainDir) {
          betterSeedL = newSeed.oklch.l;
        } else {
          worseSeedL = newSeed.oklch.l;
        }
        if (debug) {
          console.log(`Retry #${i}`, {
            SATISFIED_ALL_CONTRAST_REQUIREMENTS,
            UNSATISFIED_DIRECTION,
            seed: newSeed.toString(),
            sortedSteps,
            config,
            mainDir,
            oppDir,
            pinLightness
          });
        }
      }
    }
    if (mainDir === "darker") {
      const tmpSurface1 = toReturn.ramp.surface1;
      toReturn.ramp.surface1 = toReturn.ramp.surface3;
      toReturn.ramp.surface3 = tmpSurface1;
    }
    return toReturn;
  }

  // packages/theme/build-module/color-ramps/lib/ramp-configs.js
  var lightnessConstraintForegroundHighContrast = (direction) => direction === "lighter" ? 0.9551 : 0.235;
  var lightnessConstraintForegroundMediumContrast = (direction) => direction === "lighter" ? 0.77 : 0.56;
  var lightnessConstraintBgFill = (direction) => direction === "lighter" ? 0.67 : 0.45;
  var BG_SURFACE_TAPER_CHROMA = {
    alpha: 0.7
  };
  var FG_TAPER_CHROMA = {
    alpha: 0.6,
    kLight: 0.2,
    kDark: 0.2
  };
  var STROKE_TAPER_CHROMA = {
    alpha: 0.6,
    radiusDark: 0.01,
    radiusLight: 0.01,
    kLight: 0.8,
    kDark: 0.8
  };
  var ACCENT_SURFACE_TAPER_CHROMA = {
    alpha: 0.75,
    radiusDark: 0.01,
    radiusLight: 0.01
  };
  var fgSurface4Config = {
    contrast: {
      reference: "surface3",
      followDirection: "main",
      target: 7,
      preferLighter: true
    },
    lightness: lightnessConstraintForegroundHighContrast,
    taperChromaOptions: FG_TAPER_CHROMA
  };
  var BG_RAMP_CONFIG = {
    // Surface
    surface1: {
      contrast: {
        reference: "surface2",
        followDirection: "opposite",
        target: 1.02,
        ignoreWhenAdjustingSeed: true
      },
      taperChromaOptions: BG_SURFACE_TAPER_CHROMA
    },
    surface2: {
      contrast: {
        reference: "seed",
        followDirection: "main",
        target: 1
      }
    },
    surface3: {
      contrast: {
        reference: "surface2",
        followDirection: "main",
        target: 1.02
      },
      taperChromaOptions: BG_SURFACE_TAPER_CHROMA
    },
    surface4: {
      contrast: {
        reference: "surface2",
        followDirection: "main",
        target: 1.08
      },
      taperChromaOptions: BG_SURFACE_TAPER_CHROMA
    },
    surface5: {
      contrast: {
        reference: "surface2",
        followDirection: "main",
        target: 1.2
      },
      taperChromaOptions: BG_SURFACE_TAPER_CHROMA
    },
    surface6: {
      contrast: {
        reference: "surface2",
        followDirection: "main",
        target: 1.4
      },
      taperChromaOptions: BG_SURFACE_TAPER_CHROMA
    },
    // Bg fill
    bgFill1: {
      contrast: {
        reference: "surface2",
        followDirection: "main",
        target: 4
      },
      lightness: lightnessConstraintBgFill
    },
    bgFill2: {
      contrast: {
        reference: "bgFill1",
        followDirection: "main",
        target: 1.2
      }
    },
    bgFillInverted1: {
      contrast: {
        reference: "bgFillInverted2",
        followDirection: "opposite",
        target: 1.2
      }
    },
    bgFillInverted2: fgSurface4Config,
    bgFillDark: {
      contrast: {
        reference: "surface3",
        followDirection: "darker",
        // This is what causes the token to be always dark
        target: 7,
        ignoreWhenAdjustingSeed: true
      },
      lightness: lightnessConstraintForegroundHighContrast,
      taperChromaOptions: FG_TAPER_CHROMA
    },
    // Stroke
    stroke1: {
      contrast: {
        reference: "stroke3",
        followDirection: "opposite",
        target: 2.2
      },
      taperChromaOptions: STROKE_TAPER_CHROMA
    },
    stroke2: {
      contrast: {
        reference: "stroke3",
        followDirection: "opposite",
        target: 1.5
      },
      taperChromaOptions: STROKE_TAPER_CHROMA
    },
    stroke3: {
      contrast: {
        reference: "surface3",
        followDirection: "main",
        target: 3
      },
      taperChromaOptions: STROKE_TAPER_CHROMA
    },
    stroke4: {
      contrast: {
        reference: "stroke3",
        followDirection: "main",
        target: 1.5
      },
      taperChromaOptions: STROKE_TAPER_CHROMA
    },
    // fgSurface
    fgSurface1: {
      contrast: {
        reference: "surface3",
        followDirection: "main",
        target: 2,
        preferLighter: true
      },
      taperChromaOptions: FG_TAPER_CHROMA
    },
    fgSurface2: {
      contrast: {
        reference: "surface3",
        followDirection: "main",
        target: 3,
        preferLighter: true
      },
      taperChromaOptions: FG_TAPER_CHROMA
    },
    fgSurface3: {
      contrast: {
        reference: "surface3",
        followDirection: "main",
        target: 4.5,
        preferLighter: true
      },
      lightness: lightnessConstraintForegroundMediumContrast,
      taperChromaOptions: FG_TAPER_CHROMA
    },
    fgSurface4: fgSurface4Config,
    // fgFill
    fgFill: {
      contrast: {
        reference: "bgFill1",
        followDirection: "best",
        target: 4.5,
        preferLighter: true
      },
      lightness: lightnessConstraintForegroundHighContrast,
      taperChromaOptions: FG_TAPER_CHROMA
    },
    fgFillInverted: {
      contrast: {
        reference: "bgFillInverted1",
        followDirection: "best",
        target: 4.5,
        preferLighter: true
      },
      lightness: lightnessConstraintForegroundHighContrast,
      taperChromaOptions: FG_TAPER_CHROMA
    },
    fgFillDark: {
      contrast: {
        reference: "bgFillDark",
        followDirection: "best",
        target: 4.5,
        preferLighter: true
      },
      lightness: lightnessConstraintForegroundHighContrast,
      taperChromaOptions: FG_TAPER_CHROMA
    }
  };
  var ACCENT_RAMP_CONFIG = {
    ...BG_RAMP_CONFIG,
    surface1: {
      ...BG_RAMP_CONFIG.surface1,
      taperChromaOptions: ACCENT_SURFACE_TAPER_CHROMA
    },
    surface2: {
      contrast: {
        reference: "bgFill1",
        followDirection: "opposite",
        target: BG_RAMP_CONFIG.bgFill1.contrast.target,
        ignoreWhenAdjustingSeed: true
      },
      taperChromaOptions: ACCENT_SURFACE_TAPER_CHROMA
    },
    surface3: {
      ...BG_RAMP_CONFIG.surface3,
      taperChromaOptions: ACCENT_SURFACE_TAPER_CHROMA
    },
    surface4: {
      ...BG_RAMP_CONFIG.surface4,
      taperChromaOptions: ACCENT_SURFACE_TAPER_CHROMA
    },
    surface5: {
      ...BG_RAMP_CONFIG.surface5,
      taperChromaOptions: ACCENT_SURFACE_TAPER_CHROMA
    },
    surface6: {
      ...BG_RAMP_CONFIG.surface6,
      taperChromaOptions: ACCENT_SURFACE_TAPER_CHROMA
    },
    bgFill1: {
      contrast: {
        reference: "seed",
        followDirection: "main",
        target: 1
      }
    },
    stroke1: {
      ...BG_RAMP_CONFIG.stroke1
    },
    stroke2: {
      ...BG_RAMP_CONFIG.stroke2
    },
    stroke3: {
      ...BG_RAMP_CONFIG.stroke3,
      sameAsIfPossible: "fgSurface3",
      taperChromaOptions: void 0
    },
    stroke4: {
      ...BG_RAMP_CONFIG.stroke4,
      taperChromaOptions: void 0
    },
    // fgSurface: do not de-saturate
    fgSurface1: {
      ...BG_RAMP_CONFIG.fgSurface1,
      taperChromaOptions: void 0
    },
    fgSurface2: {
      ...BG_RAMP_CONFIG.fgSurface2,
      taperChromaOptions: void 0
    },
    fgSurface3: {
      ...BG_RAMP_CONFIG.fgSurface3,
      taperChromaOptions: void 0,
      sameAsIfPossible: "bgFill1"
    },
    fgSurface4: {
      ...BG_RAMP_CONFIG.fgSurface4,
      taperChromaOptions: void 0
    }
  };

  // packages/theme/build-module/color-ramps/index.js
  function buildBgRamp({
    seed,
    debug
  }) {
    if (typeof seed !== "string" || seed.trim() === "") {
      throw new Error("Seed color must be a non-empty string");
    }
    return buildRamp(seed, BG_RAMP_CONFIG, { debug });
  }
  var STEP_TO_PIN = "surface2";
  function getBgRampInfo(ramp) {
    return {
      mainDirection: ramp.direction,
      pinLightness: {
        stepName: STEP_TO_PIN,
        value: clampAccentScaleReferenceLightness(
          new Color(ramp.ramp[STEP_TO_PIN].color).oklch.l,
          ramp.direction
        )
      }
    };
  }
  function buildAccentRamp({
    seed,
    bgRamp,
    debug
  }) {
    if (typeof seed !== "string" || seed.trim() === "") {
      throw new Error("Seed color must be a non-empty string");
    }
    const bgRampInfo = bgRamp ? getBgRampInfo(bgRamp) : void 0;
    return buildRamp(seed, ACCENT_RAMP_CONFIG, {
      ...bgRampInfo,
      debug
    });
  }

  // packages/theme/build-module/use-theme-provider-styles.js
  var legacyWpComponentsOverridesCSS = [
    ["--wp-components-color-accent", "var(--wp-admin-theme-color)"],
    [
      "--wp-components-color-accent-darker-10",
      "var(--wp-admin-theme-color-darker-10)"
    ],
    [
      "--wp-components-color-accent-darker-20",
      "var(--wp-admin-theme-color-darker-20)"
    ],
    [
      "--wp-components-color-accent-inverted",
      "var(--wpds-color-fg-interactive-brand-strong)"
    ],
    [
      "--wp-components-color-background",
      "var(--wpds-color-bg-surface-neutral-strong)"
    ],
    [
      "--wp-components-color-foreground",
      "var(--wpds-color-fg-content-neutral)"
    ],
    [
      "--wp-components-color-foreground-inverted",
      "var(--wpds-color-bg-surface-neutral)"
    ],
    [
      "--wp-components-color-gray-100",
      "var(--wpds-color-bg-surface-neutral)"
    ],
    [
      "--wp-components-color-gray-200",
      "var(--wpds-color-stroke-surface-neutral)"
    ],
    [
      "--wp-components-color-gray-300",
      "var(--wpds-color-stroke-surface-neutral)"
    ],
    [
      "--wp-components-color-gray-400",
      "var(--wpds-color-stroke-interactive-neutral)"
    ],
    [
      "--wp-components-color-gray-600",
      "var(--wpds-color-stroke-interactive-neutral)"
    ],
    [
      "--wp-components-color-gray-700",
      "var(--wpds-color-fg-content-neutral-weak)"
    ],
    [
      "--wp-components-color-gray-800",
      "var(--wpds-color-fg-content-neutral)"
    ]
  ];
  function customRgbFormat(color) {
    const rgb = color.to("srgb");
    return [rgb.r, rgb.g, rgb.b].map((n2) => Math.round(n2 * 255)).join(", ");
  }
  function legacyWpAdminThemeOverridesCSS(accent) {
    const parsedAccent = new Color(accent).to("hsl");
    const hsl = parsedAccent.coords;
    const darker10 = new Color("hsl", [
      hsl[0],
      // h
      hsl[1],
      // s
      Math.max(0, Math.min(100, hsl[2] - 5))
      // l (reduced by 5%)
    ]).to("srgb");
    const darker20 = new Color("hsl", [
      hsl[0],
      // h
      hsl[1],
      // s
      Math.max(0, Math.min(100, hsl[2] - 10))
      // l (reduced by 10%)
    ]).to("srgb");
    return [
      [
        "--wp-admin-theme-color",
        parsedAccent.to("srgb").toString({ format: "hex" })
      ],
      ["--wp-admin-theme-color--rgb", customRgbFormat(parsedAccent)],
      [
        "--wp-admin-theme-color-darker-10",
        darker10.toString({ format: "hex" })
      ],
      [
        "--wp-admin-theme-color-darker-10--rgb",
        customRgbFormat(darker10)
      ],
      [
        "--wp-admin-theme-color-darker-20",
        darker20.toString({ format: "hex" })
      ],
      [
        "--wp-admin-theme-color-darker-20--rgb",
        customRgbFormat(darker20)
      ]
    ];
  }
  function semanticTokensCSS(filterFn = () => true) {
    return Object.entries(design_tokens_default).filter(filterFn).map(([variableName, modesAndValues]) => [
      variableName,
      modesAndValues["."]
    ]);
  }
  var toKebabCase = (str) => str.replace(
    /[A-Z]+(?![a-z])|[A-Z]/g,
    ($, ofs) => (ofs ? "-" : "") + $.toLowerCase()
  );
  function colorRampCSS(ramp, prefix) {
    return [...Object.entries(ramp.ramp)].map(
      ([tokenName, tokenValue]) => [
        `${prefix}${toKebabCase(tokenName)}`,
        tokenValue.color
      ]
    );
  }
  function generateStyles({
    primary,
    computedColorRamps
  }) {
    return Object.fromEntries(
      [
        // Primitive tokens
        Array.from(computedColorRamps).map(([rampName, computedColorRamp]) => [
          colorRampCSS(
            computedColorRamp,
            `--wpds-color-private-${rampName}-`
          )
        ]).flat(2),
        // Semantic color tokens (other semantic tokens for now are static)
        semanticTokensCSS(([key]) => /color/.test(key)),
        // Legacy overrides
        legacyWpAdminThemeOverridesCSS(primary),
        legacyWpComponentsOverridesCSS
      ].flat()
    );
  }
  function useThemeProviderStyles({
    color = {}
  } = {}) {
    const { resolvedSettings: inheritedSettings } = (0, import_element2.useContext)(ThemeContext);
    const primary = color.primary ?? inheritedSettings.color?.primary ?? DEFAULT_SEED_COLORS.primary;
    const bg = color.bg ?? inheritedSettings.color?.bg ?? DEFAULT_SEED_COLORS.bg;
    const resolvedSettings = (0, import_element2.useMemo)(
      () => ({
        color: {
          primary,
          bg
        }
      }),
      [primary, bg]
    );
    const themeProviderStyles = (0, import_element2.useMemo)(() => {
      const seeds = {
        ...DEFAULT_SEED_COLORS,
        bg,
        primary
      };
      const computedColorRamps = /* @__PURE__ */ new Map();
      const bgRamp = buildBgRamp({ seed: seeds.bg });
      Object.entries(seeds).forEach(([rampName, seed]) => {
        if (rampName === "bg") {
          computedColorRamps.set(rampName, bgRamp);
        } else {
          computedColorRamps.set(
            rampName,
            buildAccentRamp({
              seed,
              bgRamp
            })
          );
        }
      });
      return generateStyles({
        primary: seeds.primary,
        computedColorRamps
      });
    }, [primary, bg]);
    return {
      resolvedSettings,
      themeProviderStyles
    };
  }

  // packages/theme/build-module/style.module.css.js
  var style_module_css_default = { "root": "_root_th78q_1" };

  // packages/theme/build-module/theme-provider.js
  function cssObjectToText(values) {
    return Object.entries(values).map(([key, value]) => `${key}: ${value};`).join("");
  }
  function generateCSSSelector({
    instanceId,
    isRoot
  }) {
    const rootSel = `[data-wpds-root-provider="true"]`;
    const instanceIdSel = `[data-wpds-theme-provider-id="${instanceId}"]`;
    const selectors = [];
    if (isRoot) {
      selectors.push(
        `:root:has(.${style_module_css_default.root}${rootSel}${instanceIdSel})`
      );
    }
    selectors.push(`.${style_module_css_default.root}.${style_module_css_default.root}${instanceIdSel}`);
    return selectors.join(",");
  }
  var ThemeProvider = ({
    children,
    color = {},
    isRoot = false
  }) => {
    const instanceId = (0, import_element3.useId)();
    const { themeProviderStyles, resolvedSettings } = useThemeProviderStyles({
      color
    });
    const contextValue = (0, import_element3.useMemo)(
      () => ({
        resolvedSettings
      }),
      [resolvedSettings]
    );
    return /* @__PURE__ */ (0, import_jsx_runtime.jsxs)(import_jsx_runtime.Fragment, { children: [
      themeProviderStyles ? /* @__PURE__ */ (0, import_jsx_runtime.jsx)("style", { children: `${generateCSSSelector({
        instanceId,
        isRoot
      })} {${cssObjectToText(themeProviderStyles)}}` }) : null,
      /* @__PURE__ */ (0, import_jsx_runtime.jsx)(
        "div",
        {
          "data-wpds-theme-provider-id": instanceId,
          "data-wpds-root-provider": isRoot,
          className: style_module_css_default.root,
          children: /* @__PURE__ */ (0, import_jsx_runtime.jsx)(ThemeContext.Provider, { value: contextValue, children })
        }
      )
    ] });
  };

  // packages/theme/build-module/private-apis.js
  var privateApis = {};
  lock(privateApis, {
    ThemeProvider,
    useThemeProviderStyles
  });
  return __toCommonJS(index_exports);
})();
//# sourceMappingURL=index.js.map
