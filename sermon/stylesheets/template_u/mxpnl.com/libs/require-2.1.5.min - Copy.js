var requirejs, require, define;
(function (aa) {
    function I(e) {
        return"[object Function]" === L.call(e)
    }

    function J(e) {
        return"[object Array]" === L.call(e)
    }

    function y(e, t) {
        if (e) {
            var n;
            for (n = 0; n < e.length && (!e[n] || !t(e[n], n, e)); n += 1);
        }
    }

    function M(e, t) {
        if (e) {
            var n;
            for (n = e.length - 1; -1 < n && (!e[n] || !t(e[n], n, e)); n -= 1);
        }
    }

    function s(e, t) {
        return ga.call(e, t)
    }

    function m(e, t) {
        return s(e, t) && e[t]
    }

    function G(e, t) {
        for (var n in e)if (s(e, n) && t(e[n], n))break
    }

    function R(e, t, n, r) {
        return t && G(t, function (t, i) {
            if (n || !s(e, i))r && "string" != typeof t ? (e[i] || (e[i] = {}), R(e[i], t, n, r)) : e[i] = t
        }), e
    }

    function u(e, t) {
        return function () {
            return t.apply(e, arguments)
        }
    }

    function ba(e) {
        if (!e)return e;
        var t = aa;
        return y(e.split("."), function (e) {
            t = t[e]
        }), t
    }

    function B(e, t, n, r) {
        return t = Error(t + "\nhttp://requirejs.org/docs/errors.html#" + e), t.requireType = e, t.requireModules = r, n && (t.originalError = n), t
    }

    function ha(e) {
        function t(e, t, n) {
            var r, i, s, o, u, a, f, l = t && t.split("/");
            r = l;
            var c = k.map, h = c && c["*"];
            if (e && "." === e.charAt(0))if (t) {
                r = m(k.pkgs, t) ? l = [t] : l.slice(0, l.length - 1), t = e = r.concat(e.split("/"));
                for (r = 0; t[r]; r += 1)if (i = t[r], "." === i)t.splice(r, 1), r -= 1; else if (".." === i) {
                    if (1 === r && (".." === t[2] || ".." === t[0]))break;
                    0 < r && (t.splice(r - 1, 2), r -= 2)
                }
                r = m(k.pkgs, t = e[0]), e = e.join("/"), r && e === t + "/" + r.main && (e = t)
            } else 0 === e.indexOf("./") && (e = e.substring(2));
            if (n && c && (l || h)) {
                t = e.split("/");
                for (r = t.length; 0 < r; r -= 1) {
                    s = t.slice(0, r).join("/");
                    if (l)for (i = l.length; 0 < i; i -= 1)if (n = m(c, l.slice(0, i).join("/")))if (n = m(n, s)) {
                        o = n, u = r;
                        break
                    }
                    if (o)break;
                    !a && h && m(h, s) && (a = m(h, s), f = r)
                }
                !o && a && (o = a, u = f), o && (t.splice(0, u, o), e = t.join("/"))
            }
            return e
        }

        function n(e) {
            A && y(document.getElementsByTagName("script"), function (t) {
                if (t.getAttribute("data-requiremodule") === e && t.getAttribute("data-requirecontext") === x.contextName)return t.parentNode.removeChild(t), !0
            })
        }

        function r(e) {
            var t = m(k.paths, e);
            if (t && J(t) && 1 < t.length)return n(e), t.shift(), x.require.undef(e), x.require([e]), !0
        }

        function i(e) {
            var t, n = e ? e.indexOf("!") : -1;
            return-1 < n && (t = e.substring(0, n), e = e.substring(n + 1, e.length)), [t, e]
        }

        function o(e, n, r, s) {
            var o, u, a = null, f = n ? n.name : null, l = e, c = !0, h = "";
            return e || (c = !1, e = "_@r" + (F += 1)), e = i(e), a = e[0], e = e[1], a && (a = t(a, f, s), u = m(H, a)), e && (a ? h = u && u.normalize ? u.normalize(e, function (e) {
                return t(e, f, s)
            }) : t(e, f, s) : (h = t(e, f, s), e = i(h), a = e[0], h = e[1], r = !0, o = x.nameToUrl(h))), r = a && !u && !r ? "_unnormalized" + (q += 1) : "", {prefix: a, name: h, parentMap: n, unnormalized: !!r, url: o, originalName: l, isDefine: c, id: (a ? a + "!" + h : h) + r}
        }

        function a(e) {
            var t = e.id, n = m(L, t);
            return n || (n = L[t] = new x.Module(e)), n
        }

        function f(e, t, n) {
            var r = e.id, i = m(L, r);
            s(H, r) && (!i || i.defineEmitComplete) ? "defined" === t && n(H[r]) : a(e).on(t, n)
        }

        function c(e, t) {
            var n = e.requireModules, r = !1;
            t ? t(e) : (y(n, function (t) {
                if (t = m(L, t))t.error = e, t.events.error && (r = !0, t.emit("error", e))
            }), !r) && l.onError(e)
        }

        function h() {
            T.length && (ia.apply(D, [D.length - 1, 0].concat(T)), T = [])
        }

        function p(e) {
            delete L[e], delete M[e]
        }

        function d(e, t, n) {
            var r = e.map.id;
            e.error ? e.emit("error", e.error) : (t[r] = !0, y(e.depMaps, function (r, i) {
                var s = r.id, o = m(L, s);
                o && !e.depMatched[i] && !n[s] && (m(t, s) ? (e.defineDep(i, H[s]), e.check()) : d(o, t, n))
            }), n[r] = !0)
        }

        function v() {
            var e, t, i, s, o = (i = 1e3 * k.waitSeconds) && x.startTime + i < (new Date).getTime(), u = [], a = [], f = !1, l = !0;
            if (!E) {
                E = !0, G(M, function (i) {
                    e = i.map, t = e.id;
                    if (i.enabled && (e.isDefine || a.push(i), !i.error))if (!i.inited && o)r(t) ? f = s = !0 : (u.push(t), n(t)); else if (!i.inited && i.fetched && e.isDefine && (f = !0, !e.prefix))return l = !1
                });
                if (o && u.length)return i = B("timeout", "Load timeout for modules: " + u, null, u), i.contextName = x.contextName, c(i);
                l && y(a, function (e) {
                    d(e, {}, {})
                }), (!o || s) && f && (A || da) && !C && (C = setTimeout(function () {
                    C = 0, v()
                }, 50)), E = !1
            }
        }

        function g(e) {
            s(H, e[0]) || a(o(e[0], null, !0)).init(e[1], e[2])
        }

        function b(e) {
            var e = e.currentTarget || e.srcElement, t = x.onScriptLoad;
            return e.detachEvent && !Y ? e.detachEvent("onreadystatechange", t) : e.removeEventListener("load", t, !1), t = x.onScriptError, (!e.detachEvent || Y) && e.removeEventListener("error", t, !1), {node: e, id: e && e.getAttribute("data-requiremodule")}
        }

        function w() {
            var e;
            for (h(); D.length;) {
                e = D.shift();
                if (null === e[0])return c(B("mismatch", "Mismatched anonymous define() module: " + e[e.length - 1]));
                g(e)
            }
        }

        var E, S, x, N, C, k = {waitSeconds: 7, baseUrl: "./", paths: {}, pkgs: {}, shim: {}, config: {}}, L = {}, M = {}, _ = {}, D = [], H = {}, j = {}, F = 1, q = 1;
        return N = {require: function (e) {
            return e.require ? e.require : e.require = x.makeRequire(e.map)
        }, exports: function (e) {
            e.usingExports = !0;
            if (e.map.isDefine)return e.exports ? e.exports : e.exports = H[e.map.id] = {}
        }, module: function (e) {
            return e.module ? e.module : e.module = {id: e.map.id, uri: e.map.url, config: function () {
                return k.config && m(k.config, e.map.id) || {}
            }, exports: H[e.map.id]}
        }}, S = function (e) {
            this.events = m(_, e.id) || {}, this.map = e, this.shim = m(k.shim, e.id), this.depExports = [], this.depMaps = [], this.depMatched = [], this.pluginMaps = {}, this.depCount = 0
        }, S.prototype = {init: function (e, t, n, r) {
            r = r || {}, this.inited || (this.factory = t, n ? this.on("error", n) : this.events.error && (n = u(this, function (e) {
                this.emit("error", e)
            })), this.depMaps = e && e.slice(0), this.errback = n, this.inited = !0, this.ignore = r.ignore, r.enabled || this.enabled ? this.enable() : this.check())
        }, defineDep: function (e, t) {
            this.depMatched[e] || (this.depMatched[e] = !0, this.depCount -= 1, this.depExports[e] = t)
        }, fetch: function () {
            if (!this.fetched) {
                this.fetched = !0, x.startTime = (new Date).getTime();
                var e = this.map;
                if (!this.shim)return e.prefix ? this.callPlugin() : this.load();
                x.makeRequire(this.map, {enableBuildCallback: !0})(this.shim.deps || [], u(this, function () {
                    return e.prefix ? this.callPlugin() : this.load()
                }))
            }
        }, load: function () {
            var e = this.map.url;
            j[e] || (j[e] = !0, x.load(this.map.id, e))
        }, check: function () {
            if (this.enabled && !this.enabling) {
                var e, t, n = this.map.id;
                t = this.depExports;
                var r = this.exports, i = this.factory;
                if (this.inited) {
                    if (this.error)this.emit("error", this.error); else if (!this.defining) {
                        this.defining = !0;
                        if (1 > this.depCount && !this.defined) {
                            if (I(i)) {
                                if (this.events.error)try {
                                    r = x.execCb(n, i, t, r)
                                } catch (s) {
                                    e = s
                                } else r = x.execCb(n, i, t, r);
                                this.map.isDefine && ((t = this.module) && void 0 !== t.exports && t.exports !== this.exports ? r = t.exports : void 0 === r && this.usingExports && (r = this.exports));
                                if (e)return e.requireMap = this.map, e.requireModules = [this.map.id], e.requireType = "define", c(this.error = e)
                            } else r = i;
                            this.exports = r, this.map.isDefine && !this.ignore && (H[n] = r, l.onResourceLoad) && l.onResourceLoad(x, this.map, this.depMaps), p(n), this.defined = !0
                        }
                        this.defining = !1, this.defined && !this.defineEmitted && (this.defineEmitted = !0, this.emit("defined", this.exports), this.defineEmitComplete = !0)
                    }
                } else this.fetch()
            }
        }, callPlugin: function () {
            var e = this.map, n = e.id, r = o(e.prefix);
            this.depMaps.push(r), f(r, "defined", u(this, function (r) {
                var i, h;
                h = this.map.name;
                var d = this.map.parentMap ? this.map.parentMap.name : null, v = x.makeRequire(e.parentMap, {enableBuildCallback: !0});
                if (this.map.unnormalized) {
                    if (r.normalize && (h = r.normalize(h, function (e) {
                        return t(e, d, !0)
                    }) || ""), r = o(e.prefix + "!" + h, this.map.parentMap), f(r, "defined", u(this, function (e) {
                        this.init([], function () {
                            return e
                        }, null, {enabled: !0, ignore: !0})
                    })), h = m(L, r.id))this.depMaps.push(r), this.events.error && h.on("error", u(this, function (e) {
                        this.emit("error", e)
                    })), h.enable()
                } else i = u(this, function (e) {
                    this.init([], function () {
                        return e
                    }, null, {enabled: !0})
                }), i.error = u(this, function (e) {
                    this.inited = !0, this.error = e, e.requireModules = [n], G(L, function (e) {
                        0 === e.map.id.indexOf(n + "_unnormalized") && p(e.map.id)
                    }), c(e)
                }), i.fromText = u(this, function (t, r) {
                    var u = e.name, f = o(u), h = O;
                    r && (t = r), h && (O = !1), a(f), s(k.config, n) && (k.config[u] = k.config[n]);
                    try {
                        l.exec(t)
                    } catch (p) {
                        return c(B("fromtexteval", "fromText eval for " + n + " failed: " + p, p, [n]))
                    }
                    h && (O = !0), this.depMaps.push(f), x.completeLoad(u), v([u], i)
                }), r.load(e.name, v, i, k)
            })), x.enable(r, this), this.pluginMaps[r.id] = r
        }, enable: function () {
            M[this.map.id] = this, this.enabling = this.enabled = !0, y(this.depMaps, u(this, function (e, t) {
                var n, r;
                if ("string" == typeof e) {
                    e = o(e, this.map.isDefine ? this.map : this.map.parentMap, !1, !this.skipMap), this.depMaps[t] = e;
                    if (n = m(N, e.id)) {
                        this.depExports[t] = n(this);
                        return
                    }
                    this.depCount += 1, f(e, "defined", u(this, function (e) {
                        this.defineDep(t, e), this.check()
                    })), this.errback && f(e, "error", this.errback)
                }
                n = e.id, r = L[n], !s(N, n) && r && !r.enabled && x.enable(e, this)
            })), G(this.pluginMaps, u(this, function (e) {
                var t = m(L, e.id);
                t && !t.enabled && x.enable(e, this)
            })), this.enabling = !1, this.check()
        }, on: function (e, t) {
            var n = this.events[e];
            n || (n = this.events[e] = []), n.push(t)
        }, emit: function (e, t) {
            y(this.events[e], function (e) {
                e(t)
            }), "error" === e && delete this.events[e]
        }}, x = {config: k, contextName: e, registry: L, defined: H, urlFetched: j, defQueue: D, Module: S, makeModuleMap: o, nextTick: l.nextTick, onError: c, configure: function (e) {
            e.baseUrl && "/" !== e.baseUrl.charAt(e.baseUrl.length - 1) && (e.baseUrl += "/");
            var t = k.pkgs, n = k.shim, r = {paths: !0, config: !0, map: !0};
            G(e, function (e, t) {
                r[t] ? "map" === t ? (k.map || (k.map = {}), R(k[t], e, !0, !0)) : R(k[t], e, !0) : k[t] = e
            }), e.shim && (G(e.shim, function (e, t) {
                J(e) && (e = {deps: e}), (e.exports || e.init) && !e.exportsFn && (e.exportsFn = x.makeShimExports(e)), n[t] = e
            }), k.shim = n), e.packages && (y(e.packages, function (e) {
                e = "string" == typeof e ? {name: e} : e, t[e.name] = {name: e.name, location: e.location || e.name, main: (e.main || "main").replace(ja, "").replace(ea, "")}
            }), k.pkgs = t), G(L, function (e, t) {
                !e.inited && !e.map.unnormalized && (e.map = o(t))
            }), (e.deps || e.callback) && x.require(e.deps || [], e.callback)
        }, makeShimExports: function (e) {
            return function () {
                var t;
                return e.init && (t = e.init.apply(aa, arguments)), t || e.exports && ba(e.exports)
            }
        }, makeRequire: function (n, r) {
            function i(t, u, f) {
                var h, p;
                return r.enableBuildCallback && u && I(u) && (u.__requireJsBuild = !0), "string" == typeof t ? I(u) ? c(B("requireargs", "Invalid require call"), f) : n && s(N, t) ? N[t](L[n.id]) : l.get ? l.get(x, t, n, i) : (h = o(t, n, !1, !0), h = h.id, s(H, h) ? H[h] : c(B("notloaded", 'Module name "' + h + '" has not been loaded yet for context: ' + e + (n ? "" : ". Use require([])")))) : (w(), x.nextTick(function () {
                    w(), p = a(o(null, n)), p.skipMap = r.skipMap, p.init(t, u, f, {enabled: !0}), v()
                }), i)
            }

            return r = r || {}, R(i, {isBrowser: A, toUrl: function (e) {
                var r, i = e.lastIndexOf("."), s = e.split("/")[0];
                return-1 !== i && ("." !== s && ".." !== s || 1 < i) && (r = e.substring(i, e.length), e = e.substring(0, i)), x.nameToUrl(t(e, n && n.id, !0), r, !0)
            }, defined: function (e) {
                return s(H, o(e, n, !1, !0).id)
            }, specified: function (e) {
                return e = o(e, n, !1, !0).id, s(H, e) || s(L, e)
            }}), n || (i.undef = function (e) {
                h();
                var t = o(e, n, !0), r = m(L, e);
                delete H[e], delete j[t.url], delete _[e], r && (r.events.defined && (_[e] = r.events), p(e))
            }), i
        }, enable: function (e) {
            m(L, e.id) && a(e).enable()
        }, completeLoad: function (e) {
            var t, n, i = m(k.shim, e) || {}, o = i.exports;
            for (h(); D.length;) {
                n = D.shift();
                if (null === n[0]) {
                    n[0] = e;
                    if (t)break;
                    t = !0
                } else n[0] === e && (t = !0);
                g(n)
            }
            n = m(L, e);
            if (!t && !s(H, e) && n && !n.inited) {
                if (k.enforceDefine && (!o || !ba(o)))return r(e) ? void 0 : c(B("nodefine", "No define call for " + e, null, [e]));
                g([e, i.deps || [], i.exportsFn])
            }
            v()
        }, nameToUrl: function (e, t, n) {
            var r, i, s, o, u, a;
            if (l.jsExtRegExp.test(e))o = e + (t || ""); else {
                r = k.paths, i = k.pkgs, o = e.split("/");
                for (u = o.length; 0 < u; u -= 1) {
                    if (a = o.slice(0, u).join("/"), s = m(i, a), a = m(r, a)) {
                        J(a) && (a = a[0]), o.splice(0, u, a);
                        break
                    }
                    if (s) {
                        e = e === s.name ? s.location + "/" + s.main : s.location, o.splice(0, u, e);
                        break
                    }
                }
                o = o.join("/"), o += t || (/\?/.test(o) || n ? "" : ".js"), o = ("/" === o.charAt(0) || o.match(/^[\w\+\.\-]+:/) ? "" : k.baseUrl) + o
            }
            return k.urlArgs ? o + ((-1 === o.indexOf("?") ? "?" : "&") + k.urlArgs) : o
        }, load: function (e, t) {
            l.load(x, e, t)
        }, execCb: function (e, t, n, r) {
            return t.apply(r, n)
        }, onScriptLoad: function (e) {
            if ("load" === e.type || ka.test((e.currentTarget || e.srcElement).readyState))P = null, e = b(e), x.completeLoad(e.id)
        }, onScriptError: function (e) {
            var t = b(e);
            if (!r(t.id))return c(B("scripterror", "Script error", e, [t.id]))
        }}, x.require = x.makeRequire(), x
    }

    var l, w, x, D, t, E, P, K, Q, fa, la = /(\/\*([\s\S]*?)\*\/|([^:]|^)\/\/(.*)$)/mg, ma = /[^.]\s*require\s*\(\s*["']([^'"\s]+)["']\s*\)/g, ea = /\.js$/, ja = /^\.\//;
    w = Object.prototype;
    var L = w.toString, ga = w.hasOwnProperty, ia = Array.prototype.splice, A = "undefined" != typeof window && !!navigator && !!document, da = !A && "undefined" != typeof importScripts, ka = A && "PLAYSTATION 3" === navigator.platform ? /^complete$/ : /^(complete|loaded)$/, Y = "undefined" != typeof opera && "[object Opera]" === opera.toString(), F = {}, r = {}, T = [], O = !1;
    if ("undefined" == typeof define) {
        if ("undefined" != typeof requirejs) {
            if (I(requirejs))return;
            r = requirejs, requirejs = void 0
        }
        "undefined" != typeof require && !I(require) && (r = require, require = void 0), l = requirejs = function (e, t, n, r) {
            var i, s = "_";
            return!J(e) && "string" != typeof e && (i = e, J(t) ? (e = t, t = n, n = r) : e = []), i && i.context && (s = i.context), (r = m(F, s)) || (r = F[s] = l.s.newContext(s)), i && r.configure(i), r.require(e, t, n)
        }, l.config = function (e) {
            return l(e)
        }, l.nextTick = "undefined" != typeof setTimeout ? function (e) {
            setTimeout(e, 4)
        } : function (e) {
            e()
        }, require || (require = l), l.version = "2.1.5", l.jsExtRegExp = /^\/|:|\?|\.js$/, l.isBrowser = A, w = l.s = {contexts: F, newContext: ha}, l({}), y(["toUrl", "undef", "defined", "specified"], function (e) {
            l[e] = function () {
                var t = F._;
                return t.require[e].apply(t, arguments)
            }
        }), A && (x = w.head = document.getElementsByTagName("head")[0], D = document.getElementsByTagName("base")[0]) && (x = w.head = D.parentNode), l.onError = function (e) {
            throw e
        }, l.load = function (e, t, n) {
            var r = e && e.config || {}, i;
            if (A)return i = r.xhtml ? document.createElementNS("http://www.w3.org/1999/xhtml", "html:script") : document.createElement("script"), i.type = r.scriptType || "text/javascript", i.charset = "utf-8", i.async = !0, i.setAttribute("data-requirecontext", e.contextName), i.setAttribute("data-requiremodule", t), i.attachEvent && !(i.attachEvent.toString && 0 > i.attachEvent.toString().indexOf("[native code")) && !Y ? (O = !0, i.attachEvent("onreadystatechange", e.onScriptLoad)) : (i.addEventListener("load", e.onScriptLoad, !1), i.addEventListener("error", e.onScriptError, !1)), i.src = n, K = i, D ? x.insertBefore(i, D) : x.appendChild(i), K = null, i;
            if (da)try {
                importScripts(n), e.completeLoad(t)
            } catch (s) {
                e.onError(B("importscripts", "importScripts failed for " + t + " at " + n, s, [t]))
            }
        }, A && M(document.getElementsByTagName("script"), function (e) {
            x || (x = e.parentNode);
            if (t = e.getAttribute("data-main"))return r.baseUrl || (E = t.split("/"), Q = E.pop(), fa = E.length ? E.join("/") + "/" : "./", r.baseUrl = fa, t = Q), t = t.replace(ea, ""), r.deps = r.deps ? r.deps.concat(t) : [t], !0
        }), define = function (e, t, n) {
            var r, i;
            "string" != typeof e && (n = t, t = e, e = null), J(t) || (n = t, t = []), !t.length && I(n) && n.length && (n.toString().replace(la, "").replace(ma, function (e, n) {
                t.push(n)
            }), t = (1 === n.length ? ["require"] : ["require", "exports", "module"]).concat(t)), O && ((r = K) || (P && "interactive" === P.readyState || M(document.getElementsByTagName("script"), function (e) {
                if ("interactive" === e.readyState)return P = e
            }), r = P), r && (e || (e = r.getAttribute("data-requiremodule")), i = F[r.getAttribute("data-requirecontext")])), (i ? i.defQueue : T).push([e, t, n])
        }, define.amd = {jQuery: !0}, l.exec = function (b) {
            return eval(b)
        }, l(r)
    }
})(this);