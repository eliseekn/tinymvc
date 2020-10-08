// modules are defined as an array
// [ module function, map of requires ]
//
// map of requires is short require name -> numeric require
//
// anything defined in a previous bundle is accessed via the
// orig method which is the require for previous bundles
parcelRequire = (function (modules, cache, entry, globalName) {
  // Save the require from previous bundle to this closure if any
  var previousRequire = typeof parcelRequire === 'function' && parcelRequire;
  var nodeRequire = typeof require === 'function' && require;

  function newRequire(name, jumped) {
    if (!cache[name]) {
      if (!modules[name]) {
        // if we cannot find the module within our internal map or
        // cache jump to the current global require ie. the last bundle
        // that was added to the page.
        var currentRequire = typeof parcelRequire === 'function' && parcelRequire;
        if (!jumped && currentRequire) {
          return currentRequire(name, true);
        }

        // If there are other bundles on this page the require from the
        // previous one is saved to 'previousRequire'. Repeat this as
        // many times as there are bundles until the module is found or
        // we exhaust the require chain.
        if (previousRequire) {
          return previousRequire(name, true);
        }

        // Try the node require function if it exists.
        if (nodeRequire && typeof name === 'string') {
          return nodeRequire(name);
        }

        var err = new Error('Cannot find module \'' + name + '\'');
        err.code = 'MODULE_NOT_FOUND';
        throw err;
      }

      localRequire.resolve = resolve;
      localRequire.cache = {};

      var module = cache[name] = new newRequire.Module(name);

      modules[name][0].call(module.exports, localRequire, module, module.exports, this);
    }

    return cache[name].exports;

    function localRequire(x){
      return newRequire(localRequire.resolve(x));
    }

    function resolve(x){
      return modules[name][1][x] || x;
    }
  }

  function Module(moduleName) {
    this.id = moduleName;
    this.bundle = newRequire;
    this.exports = {};
  }

  newRequire.isParcelRequire = true;
  newRequire.Module = Module;
  newRequire.modules = modules;
  newRequire.cache = cache;
  newRequire.parent = previousRequire;
  newRequire.register = function (id, exports) {
    modules[id] = [function (require, module) {
      module.exports = exports;
    }, {}];
  };

  var error;
  for (var i = 0; i < entry.length; i++) {
    try {
      newRequire(entry[i]);
    } catch (e) {
      // Save first error but execute all entries
      if (!error) {
        error = e;
      }
    }
  }

  if (entry.length) {
    // Expose entry point to Node, AMD or browser globals
    // Based on https://github.com/ForbesLindesay/umd/blob/master/template.js
    var mainExports = newRequire(entry[entry.length - 1]);

    // CommonJS
    if (typeof exports === "object" && typeof module !== "undefined") {
      module.exports = mainExports;

    // RequireJS
    } else if (typeof define === "function" && define.amd) {
     define(function () {
       return mainExports;
     });

    // <script>
    } else if (globalName) {
      this[globalName] = mainExports;
    }
  }

  // Override the current require with this new one
  parcelRequire = newRequire;

  if (error) {
    // throw error from earlier, _after updating parcelRequire_
    throw error;
  }

  return newRequire;
})({"components/modal/upload-modal.js":[function(require,module,exports) {
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function _construct(Parent, args, Class) { if (_isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var UploadModal = /*#__PURE__*/function (_HTMLElement) {
  _inherits(UploadModal, _HTMLElement);

  var _super = _createSuper(UploadModal);

  function UploadModal() {
    var _this;

    _classCallCheck(this, UploadModal);

    _this = _super.call(this);
    _this.showDialog = _this.showDialog.bind(_assertThisInitialized(_this));

    _this.addEventListener('click', _this.showDialog);

    return _this;
  }

  _createClass(UploadModal, [{
    key: "connectedCallback",
    value: function connectedCallback() {
      this.innerHTML = '<button class="btn btn-primary ml-2">Import</button>';
    }
  }, {
    key: "showDialog",
    value: function showDialog() {
      var element = document.createElement('div');
      element.id = 'upload-modal';
      element.setAttribute('tabindex', '-1');
      element.setAttribute('role', 'dialog');
      element.classList.add('modal', 'fade');
      element.innerHTML = "\n            <div class=\"modal-dialog modal-dialog-centered\">\n                <div class=\"modal-content\">\n                    <div class=\"modal-header\">\n                        <h5 class=\"modal-title\">Import file</h5>\n                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n                            <span aria-hidden=\"true\">&times;</span>\n                        </button>\n                    </div>\n\n                    <form method=\"post\" action=\"".concat(this.getAttribute('action'), "\" enctype=\"multipart/form-data\">\n                        <div class=\"modal-body\">\n                            <input type=\"file\" name=\"file\" id=\"file\" class=\"form-control-file\" required>\n                        </div>\n\n                        <div class=\"modal-footer\">\n                            <button type=\"submit\" class=\"btn btn-primary\">Import</button>\n                            <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Cancel</button>\n                        </div>\n                    </form>\n                </div>\n            </div>\n        ");
      document.body.appendChild(element);
      $('#upload-modal').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
      });
    }
  }]);

  return UploadModal;
}( /*#__PURE__*/_wrapNativeSuper(HTMLElement));

var _default = UploadModal;
exports.default = _default;
},{}],"components/modal/export-modal.js":[function(require,module,exports) {
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function _construct(Parent, args, Class) { if (_isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var ExportModal = /*#__PURE__*/function (_HTMLElement) {
  _inherits(ExportModal, _HTMLElement);

  var _super = _createSuper(ExportModal);

  function ExportModal() {
    var _this;

    _classCallCheck(this, ExportModal);

    _this = _super.call(this);
    _this.showDialog = _this.showDialog.bind(_assertThisInitialized(_this));

    _this.addEventListener('click', _this.showDialog);

    return _this;
  }

  _createClass(ExportModal, [{
    key: "connectedCallback",
    value: function connectedCallback() {
      this.innerHTML = '<button class="btn btn-primary mx-2">Export</button>';
    }
  }, {
    key: "showDialog",
    value: function showDialog() {
      var element = document.createElement('div');
      element.id = 'export-modal';
      element.setAttribute('tabindex', '-1');
      element.setAttribute('role', 'dialog');
      element.classList.add('modal', 'fade');
      element.innerHTML = "\n            <div class=\"modal-dialog modal-dialog-centered\">\n                <div class=\"modal-content\">\n                    <div class=\"modal-header\">\n                        <h5 class=\"modal-title\">Export to file</h5>\n                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n                            <span aria-hidden=\"true\">&times;</span>\n                        </button>\n                    </div>\n\n                    <form method=\"post\" action=\"".concat(this.getAttribute('action'), "\">\n                        <div class=\"modal-body\">\n                            <fieldset class=\"form-group\">\n                                <div class=\"row\">\n                                    <legend class=\"col-form-label col-sm-4 pt-0\">File type</legend>\n\n                                    <div class=\"col-sm-8\">\n                                        <div class=\"custom-control custom-radio custom-control-inline\">\n                                            <input class=\"custom-control-input\" type=\"radio\" name=\"file_type\" id=\"csv\" value=\"csv\" checked>\n                                            <label class=\"custom-control-label\" for=\"csv\">CSV</label>\n                                        </div>\n                                        <div class=\"custom-control custom-radio custom-control-inline\">\n                                            <input class=\"custom-control-input\" type=\"radio\" name=\"file_type\" id=\"pdf\" value=\"pdf\">\n                                            <label class=\"custom-control-label\" for=\"pdf\">PDF</label>\n                                        </div>\n                                    </div>\n                                </div>\n                            </fieldset>\n\n                            <div class=\"form-group row\">\n                                <p class=\"col-sm-4 col-form-label mb-0\">Period <small>(optional)</small></p>\n\n                                <div class=\"col-sm-8\">\n                                    <div class=\"input-group mb-3\">\n                                        <div class=\"input-group-prepend\">\n                                            <div class=\"input-group-text\">\n                                                <i class=\"fa fa-calendar-alt\"></i>\n                                            </div>\n                                        </div>\n\n                                        <input type=\"date\" class=\"form-control\" name=\"date_start\" id=\"date_start\">\n                                    </div>\n\n                                    <div class=\"input-group\">\n                                        <div class=\"input-group-prepend\">\n                                            <div class=\"input-group-text\">\n                                                <i class=\"fa fa-calendar-alt\"></i>\n                                            </div>\n                                        </div>\n\n                                        <input type=\"date\" class=\"form-control\" name=\"date_end\" id=\"date_end\">\n                                    </div>\n                                </div>\n                            </div>\n                        </div>\n\n                        <div class=\"modal-footer\">\n                            <button type=\"submit\" class=\"btn btn-primary\">Export</button>\n                            <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Cancel</button>\n                        </div>\n                    </form>\n                </div>\n            </div>\n        ");
      document.body.appendChild(element);
      $('#export-modal').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
      });
    }
  }]);

  return ExportModal;
}( /*#__PURE__*/_wrapNativeSuper(HTMLElement));

var _default = ExportModal;
exports.default = _default;
},{}],"components/alert/alert-popup.js":[function(require,module,exports) {
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function _construct(Parent, args, Class) { if (_isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var AlertPopup = /*#__PURE__*/function (_HTMLElement) {
  _inherits(AlertPopup, _HTMLElement);

  var _super = _createSuper(AlertPopup);

  function AlertPopup() {
    var _this;

    _classCallCheck(this, AlertPopup);

    _this = _super.call(this);
    _this.modalIcon = _this.modalIcon.bind(_assertThisInitialized(_this));
    return _this;
  }

  _createClass(AlertPopup, [{
    key: "modalIcon",
    value: function modalIcon() {
      switch (this.getAttribute('type')) {
        case 'primary':
          return '<i class="fa fa-info-circle text-primary fa-4x"></i>';

        case 'danger':
          return '<i class="fa fa-exclamation-circle text-danger fa-4x"></i>';

        case 'warning':
          return '<i class="fa fa-exclamation-triangle text-warning fa-4x"></i>';

        default:
          return '<i class="fa fa-check-circle text-success fa-4x"></i>';
      }
    }
  }, {
    key: "connectedCallback",
    value: function connectedCallback() {
      var element = document.createElement('div');
      element.id = 'alert-popup';
      element.setAttribute('tabindex', '-1');
      element.setAttribute('role', 'dialog');
      element.classList.add('modal', 'fade');
      element.innerHTML = "\n            <div class=\"modal-dialog modal-dialog-centered\">\n                <div class=\"modal-content\">\n                    <div class=\"modal-body text-center\">\n                        ".concat(this.modalIcon(), "\n                        <p class=\"modal-title my-3\">").concat(this.getAttribute('message'), "</p>\n                        <button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\">OK</button>\n                    </div>\n                </div>\n            </div>\n        ");
      document.body.appendChild(element);
      $('#alert-popup').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
      });
    }
  }]);

  return AlertPopup;
}( /*#__PURE__*/_wrapNativeSuper(HTMLElement));

var _default = AlertPopup;
exports.default = _default;
},{}],"components/alert/alert-toast.js":[function(require,module,exports) {
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function _construct(Parent, args, Class) { if (_isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var AlertToast = /*#__PURE__*/function (_HTMLElement) {
  _inherits(AlertToast, _HTMLElement);

  var _super = _createSuper(AlertToast);

  function AlertToast() {
    var _this;

    _classCallCheck(this, AlertToast);

    _this = _super.call(this);
    _this.toastIcon = _this.toastIcon.bind(_assertThisInitialized(_this));
    return _this;
  }

  _createClass(AlertToast, [{
    key: "toastIcon",
    value: function toastIcon() {
      switch (this.getAttribute('type')) {
        case 'primary':
          return '<i class="fa fa-info-circle text-primary fa-lg"></i>';

        case 'danger':
          return '<i class="fa fa-exclamation-circle text-danger fa-lg"></i>';

        case 'warning':
          return '<i class="fa fa-exclamation-triangle text-warning fa-lg"></i>';

        default:
          return '<i class="fa fa-check-circle text-success fa-lg"></i>';
      }
    }
  }, {
    key: "connectedCallback",
    value: function connectedCallback() {
      var element = document.createElement('div');
      element.id = 'alert-toast';
      element.classList.add('fade');
      element.innerHTML = "\n            <div class=\"modal-dialog position-absolute shadow-sm rounded\" style=\"top: -1em; right: .8em\">\n                <div class=\"modal-content border-".concat(this.getAttribute('type'), "\">\n                    <div class=\"modal-body d-flex justify-content-around align-items-center\">\n                        ").concat(this.toastIcon(), "\n\n                        <p class=\"modal-title mx-3 mb-0\">").concat(this.getAttribute('message'), "</p>\n\n                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n                            <span aria-hidden=\"true\">&times;</span>\n                        </button>\n                    </div>\n                </div>\n            </div>\n        ");
      document.body.appendChild(element);
      $('#alert-toast').modal({
        backdrop: false,
        keyboard: false,
        show: true
      });
      $('#alert-toast').on('shown.bs.modal', function (e) {
        window.setTimeout(function () {
          $('#alert-toast').modal('hide');
        }, 2500);
      });
    }
  }]);

  return AlertToast;
}( /*#__PURE__*/_wrapNativeSuper(HTMLElement));

var _default = AlertToast;
exports.default = _default;
},{}],"components/mixed/confirm-delete.js":[function(require,module,exports) {
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function _construct(Parent, args, Class) { if (_isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var ConfirmDelete = /*#__PURE__*/function (_HTMLElement) {
  _inherits(ConfirmDelete, _HTMLElement);

  var _super = _createSuper(ConfirmDelete);

  function ConfirmDelete() {
    var _this;

    _classCallCheck(this, ConfirmDelete);

    _this = _super.call(this);
    _this.showDialog = _this.showDialog.bind(_assertThisInitialized(_this));

    _this.addEventListener('click', _this.showDialog);

    return _this;
  }

  _createClass(ConfirmDelete, [{
    key: "connectedCallback",
    value: function connectedCallback() {
      if (this.getAttribute('type') === 'icon') {
        this.innerHTML = "<a class=\"btn text-danger\" title=\"Delete item\">".concat(this.getAttribute('content'), "</a>");
      } else {
        this.innerHTML = "<a class=\"btn btn-danger\" href=\"#\">".concat(this.getAttribute('content'), "</a>");
      }
    }
  }, {
    key: "showDialog",
    value: function showDialog() {
      var _this2 = this;

      var innerHTML = this.childNodes[0].innerHTML;
      this.childNodes[0].innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

      if (window.confirm('Are you sure you want to delete this item?')) {
        fetch(this.getAttribute('action'), {
          method: 'delete'
        }).then(function () {
          return window.location = _this2.getAttribute('redirect');
        });
      }

      this.childNodes[0].innerHTML = innerHTML;
    }
  }]);

  return ConfirmDelete;
}( /*#__PURE__*/_wrapNativeSuper(HTMLElement));

var _default = ConfirmDelete;
exports.default = _default;
},{}],"components/mixed/text-editor.js":[function(require,module,exports) {
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function _construct(Parent, args, Class) { if (_isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var TextEditor = /*#__PURE__*/function (_HTMLElement) {
  _inherits(TextEditor, _HTMLElement);

  var _super = _createSuper(TextEditor);

  function TextEditor() {
    var _this;

    _classCallCheck(this, TextEditor);

    _this = _super.call(this);
    document.querySelector(_this.getAttribute('form')).addEventListener('submit', function (e) {
      e.preventDefault();
      var formData = new FormData(document.querySelector(_this.getAttribute('form')));
      formData.append('editor', _this.editor.root.innerHTML);
      fetch(document.querySelector(_this.getAttribute('form')).dataset.url, {
        method: 'post',
        body: formData
      }).then(function (response) {
        return response.json();
      }).then(function (data) {
        return window.location.href = data.redirect;
      });
    });
    return _this;
  }

  _createClass(TextEditor, [{
    key: "connectedCallback",
    value: function connectedCallback() {
      this.innerHTML = "<div id=\"editor\">".concat(this.getAttribute('content'), "</div>"); //initialize editor

      this.editor = new Quill('#editor', {
        modules: {
          toolbar: [['bold', 'italic', 'underline'], [{
            'align': []
          }], [{
            'list': 'ordered'
          }, {
            'list': 'bullet'
          }], [{
            'script': 'sub'
          }, {
            'script': 'super'
          }], [{
            'color': []
          }, {
            'background': []
          }], ['clean']]
        },
        theme: 'snow'
      }); //customize editor

      document.querySelector('.ql-editor').setAttribute('style', 'font-size: 1rem');
      document.querySelector('.ql-toolbar').classList.add('rounded-top');
      document.querySelector('#editor').classList.add('rounded-bottom');
    }
  }]);

  return TextEditor;
}( /*#__PURE__*/_wrapNativeSuper(HTMLElement));

var _default = TextEditor;
exports.default = _default;
},{}],"components/loading-button.js":[function(require,module,exports) {
document.addEventListener('DOMContentLoaded', function () {
  if (document.querySelectorAll('.loading')) {
    document.querySelectorAll('.loading').forEach(function (element) {
      element.addEventListener('click', function (event) {
        event.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        event.target.setAttribute('disabled', 'disabled');
      });
    });
  }
});
},{}],"components/password-toggler.js":[function(require,module,exports) {
document.addEventListener('DOMContentLoaded', function () {
  if (document.querySelector('#password-toggler')) {
    document.querySelector('#password-toggler').addEventListener('click', function (event) {
      if (document.querySelector('#password').type === 'password') {
        document.querySelector('#password').type = 'text';
        event.target.innerHTML = '<i class="fa fa-eye"></i>';
      } else {
        document.querySelector('#password').type = 'password';
        event.target.innerHTML = '<i class="fa fa-eye-slash"></i>';
      }
    });
  }
});
},{}],"components/admin.js":[function(require,module,exports) {
var _this = this;

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

document.addEventListener('DOMContentLoaded', function () {
  //toggle sidebar
  if (document.querySelector('#sidebar-toggler')) {
    document.querySelector('#sidebar-toggler').addEventListener('click', function (event) {
      document.querySelector('#wrapper').classList.toggle('toggled');
    });
  } //select all table items


  if (document.querySelector('#select-all')) {
    document.querySelector('#select-all').addEventListener('change', function (event) {
      if (event.target.checked) {
        document.querySelectorAll('.table input[type=checkbox]').forEach(function (element) {
          element.checked = true;
        });
      } else {
        document.querySelectorAll('.table input[type=checkbox]').forEach(function (element) {
          element.checked = false;
        });
      }
    });
  } //delete selected table items


  if (document.querySelector('#bulk-delete')) {
    document.querySelector('#bulk-delete').addEventListener('click', function (event) {
      var innerHTML = event.target.innerHTML;
      event.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

      if (window.confirm('Are you sure want to delete all selected items?')) {
        items = [];
        document.querySelectorAll('.table input[type=checkbox]').forEach(function (element) {
          if (element.id !== 'select-all' && element.checked) {
            items.push(element.dataset.id);
          }
        });

        if (items.length > 0) {
          fetch(event.target.dataset.url, {
            method: 'post',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              items: items
            })
          }).then(function () {
            return window.location.reload();
          });
        }
      }

      event.target.innerHTML = innerHTML;
    });
  } //filter tables
  //https://stackoverflow.com/questions/51187477/how-to-filter-a-html-table-using-simple-javascript


  if (document.querySelector('#filter')) {
    document.querySelector('#filter').addEventListener('keyup', function (event) {
      var filterText = event.target.value.toUpperCase();
      document.querySelectorAll('.table tbody tr:not(.header)').forEach(function (tr) {
        var match = _toConsumableArray(tr.children).some(function (td) {
          return td.textContent.toUpperCase().includes(filterText);
        });

        tr.style.display = match ? '' : 'none';
      });
    });
  } //sort tables
  //https://stackoverflow.com/questions/14267781/sorting-html-table-with-javascript


  if (document.querySelector('.table')) {
    var getCellValue = function getCellValue(tr, idx) {
      return tr.children[idx].innerText || tr.children[idx].textContent;
    };

    var comparer = function comparer(idx, asc) {
      return function (a, b) {
        return function (v1, v2) {
          return v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
        }(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));
      };
    };

    document.querySelectorAll('th').forEach(function (th) {
      return th.addEventListener('click', function () {
        var table = th.closest('table');
        var tbody = table.querySelector('tbody');
        Array.from(tbody.querySelectorAll('tr')).sort(comparer(Array.from(th.parentNode.children).indexOf(th), _this.asc = !_this.asc)).forEach(function (tr) {
          return tbody.appendChild(tr);
        });
      });
    });
  }
});
},{}],"index.js":[function(require,module,exports) {
"use strict";

var _uploadModal = _interopRequireDefault(require("./components/modal/upload-modal"));

var _exportModal = _interopRequireDefault(require("./components/modal/export-modal"));

var _alertPopup = _interopRequireDefault(require("./components/alert/alert-popup"));

var _alertToast = _interopRequireDefault(require("./components/alert/alert-toast"));

var _confirmDelete = _interopRequireDefault(require("./components/mixed/confirm-delete"));

var _textEditor = _interopRequireDefault(require("./components/mixed/text-editor"));

require("./components/loading-button");

require("./components/password-toggler");

require("./components/admin");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

window.customElements.define('upload-modal', _uploadModal.default);
window.customElements.define('export-modal', _exportModal.default);
window.customElements.define('alert-popup', _alertPopup.default);
window.customElements.define('alert-toast', _alertToast.default);
window.customElements.define('confirm-delete', _confirmDelete.default);
window.customElements.define('text-editor', _textEditor.default);
},{"./components/modal/upload-modal":"components/modal/upload-modal.js","./components/modal/export-modal":"components/modal/export-modal.js","./components/alert/alert-popup":"components/alert/alert-popup.js","./components/alert/alert-toast":"components/alert/alert-toast.js","./components/mixed/confirm-delete":"components/mixed/confirm-delete.js","./components/mixed/text-editor":"components/mixed/text-editor.js","./components/loading-button":"components/loading-button.js","./components/password-toggler":"components/password-toggler.js","./components/admin":"components/admin.js"}],"../../node_modules/parcel-bundler/src/builtins/hmr-runtime.js":[function(require,module,exports) {
var global = arguments[3];
var OVERLAY_ID = '__parcel__error__overlay__';
var OldModule = module.bundle.Module;

function Module(moduleName) {
  OldModule.call(this, moduleName);
  this.hot = {
    data: module.bundle.hotData,
    _acceptCallbacks: [],
    _disposeCallbacks: [],
    accept: function (fn) {
      this._acceptCallbacks.push(fn || function () {});
    },
    dispose: function (fn) {
      this._disposeCallbacks.push(fn);
    }
  };
  module.bundle.hotData = null;
}

module.bundle.Module = Module;
var checkedAssets, assetsToAccept;
var parent = module.bundle.parent;

if ((!parent || !parent.isParcelRequire) && typeof WebSocket !== 'undefined') {
  var hostname = "" || location.hostname;
  var protocol = location.protocol === 'https:' ? 'wss' : 'ws';
  var ws = new WebSocket(protocol + '://' + hostname + ':' + "43243" + '/');

  ws.onmessage = function (event) {
    checkedAssets = {};
    assetsToAccept = [];
    var data = JSON.parse(event.data);

    if (data.type === 'update') {
      var handled = false;
      data.assets.forEach(function (asset) {
        if (!asset.isNew) {
          var didAccept = hmrAcceptCheck(global.parcelRequire, asset.id);

          if (didAccept) {
            handled = true;
          }
        }
      }); // Enable HMR for CSS by default.

      handled = handled || data.assets.every(function (asset) {
        return asset.type === 'css' && asset.generated.js;
      });

      if (handled) {
        console.clear();
        data.assets.forEach(function (asset) {
          hmrApply(global.parcelRequire, asset);
        });
        assetsToAccept.forEach(function (v) {
          hmrAcceptRun(v[0], v[1]);
        });
      } else if (location.reload) {
        // `location` global exists in a web worker context but lacks `.reload()` function.
        location.reload();
      }
    }

    if (data.type === 'reload') {
      ws.close();

      ws.onclose = function () {
        location.reload();
      };
    }

    if (data.type === 'error-resolved') {
      console.log('[parcel] ✨ Error resolved');
      removeErrorOverlay();
    }

    if (data.type === 'error') {
      console.error('[parcel] 🚨  ' + data.error.message + '\n' + data.error.stack);
      removeErrorOverlay();
      var overlay = createErrorOverlay(data);
      document.body.appendChild(overlay);
    }
  };
}

function removeErrorOverlay() {
  var overlay = document.getElementById(OVERLAY_ID);

  if (overlay) {
    overlay.remove();
  }
}

function createErrorOverlay(data) {
  var overlay = document.createElement('div');
  overlay.id = OVERLAY_ID; // html encode message and stack trace

  var message = document.createElement('div');
  var stackTrace = document.createElement('pre');
  message.innerText = data.error.message;
  stackTrace.innerText = data.error.stack;
  overlay.innerHTML = '<div style="background: black; font-size: 16px; color: white; position: fixed; height: 100%; width: 100%; top: 0px; left: 0px; padding: 30px; opacity: 0.85; font-family: Menlo, Consolas, monospace; z-index: 9999;">' + '<span style="background: red; padding: 2px 4px; border-radius: 2px;">ERROR</span>' + '<span style="top: 2px; margin-left: 5px; position: relative;">🚨</span>' + '<div style="font-size: 18px; font-weight: bold; margin-top: 20px;">' + message.innerHTML + '</div>' + '<pre>' + stackTrace.innerHTML + '</pre>' + '</div>';
  return overlay;
}

function getParents(bundle, id) {
  var modules = bundle.modules;

  if (!modules) {
    return [];
  }

  var parents = [];
  var k, d, dep;

  for (k in modules) {
    for (d in modules[k][1]) {
      dep = modules[k][1][d];

      if (dep === id || Array.isArray(dep) && dep[dep.length - 1] === id) {
        parents.push(k);
      }
    }
  }

  if (bundle.parent) {
    parents = parents.concat(getParents(bundle.parent, id));
  }

  return parents;
}

function hmrApply(bundle, asset) {
  var modules = bundle.modules;

  if (!modules) {
    return;
  }

  if (modules[asset.id] || !bundle.parent) {
    var fn = new Function('require', 'module', 'exports', asset.generated.js);
    asset.isNew = !modules[asset.id];
    modules[asset.id] = [fn, asset.deps];
  } else if (bundle.parent) {
    hmrApply(bundle.parent, asset);
  }
}

function hmrAcceptCheck(bundle, id) {
  var modules = bundle.modules;

  if (!modules) {
    return;
  }

  if (!modules[id] && bundle.parent) {
    return hmrAcceptCheck(bundle.parent, id);
  }

  if (checkedAssets[id]) {
    return;
  }

  checkedAssets[id] = true;
  var cached = bundle.cache[id];
  assetsToAccept.push([bundle, id]);

  if (cached && cached.hot && cached.hot._acceptCallbacks.length) {
    return true;
  }

  return getParents(global.parcelRequire, id).some(function (id) {
    return hmrAcceptCheck(global.parcelRequire, id);
  });
}

function hmrAcceptRun(bundle, id) {
  var cached = bundle.cache[id];
  bundle.hotData = {};

  if (cached) {
    cached.hot.data = bundle.hotData;
  }

  if (cached && cached.hot && cached.hot._disposeCallbacks.length) {
    cached.hot._disposeCallbacks.forEach(function (cb) {
      cb(bundle.hotData);
    });
  }

  delete bundle.cache[id];
  bundle(id);
  cached = bundle.cache[id];

  if (cached && cached.hot && cached.hot._acceptCallbacks.length) {
    cached.hot._acceptCallbacks.forEach(function (cb) {
      cb();
    });

    return true;
  }
}
},{}]},{},["../../node_modules/parcel-bundler/src/builtins/hmr-runtime.js","index.js"], null)
//# sourceMappingURL=/index.js.map