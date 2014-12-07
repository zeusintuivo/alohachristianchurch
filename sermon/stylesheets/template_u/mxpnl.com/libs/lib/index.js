(function (e) {
    var t = {swipeTolerance: 40}, n = function (t, n) {
        this.target = e(t), this.touch = n, this.startX = this.currentX = n.screenX, this.startY = this.currentY = n.screenY, this.eventType = null
    };
    n.options = {}, n.latestTap = null, n.prototype.move = function (e) {
        this.currentX = e.screenX, this.currentY = e.screenY
    }, n.prototype.process = function () {
        var e = this.currentX - this.startX, t = this.currentY - this.startY;
        e == 0 && t == 0 ? this.checkForDoubleTap() : Math.abs(t) > n.options.swipeTolerance && Math.abs(t) > Math.abs(e) ? (this.eventType = t > 0 ? "swipedown" : "swipeup", this.target.trigger("swipe", [this])) : Math.abs(e) > n.options.swipeTolerance && (this.eventType = e > 0 ? "swiperight" : "swipeleft", this.target.trigger("swipe", [this])), this.eventType && this.target.trigger(this.eventType, [this]), this.target.trigger("touch", [this])
    }, n.prototype.checkForDoubleTap = function () {
        n.latestTap && new Date - n.latestTap < 400 && (this.eventType = "doubletap"), this.eventType || (this.eventType = "tap"), n.latestTap = new Date
    };
    var r = function (r, i) {
        n.options = e.extend(t, i), r.bind("touchstart", this.touchStart), r.bind("touchmove", this.touchMove), r.bind("touchcancel", this.touchCancel), r.bind("touchend", this.touchEnd)
    };
    r.prototype.touchStart = function (e) {
        var t = this;
        r.eachTouch(e, function (e) {
            r.touches[e.identifier] = new n(t, e)
        })
    }, r.prototype.touchMove = function (e) {
        r.eachTouch(e, function (e) {
            var t = r.touches[e.identifier];
            t && t.move(e)
        })
    }, r.prototype.touchCancel = function (e) {
        r.eachTouch(e, function (e) {
            r.purge(e, !0)
        })
    }, r.prototype.touchEnd = function (e) {
        r.eachTouch(e, function (e) {
            r.purge(e)
        })
    }, r.touches = {}, r.purge = function (e, t) {
        if (!t) {
            var n = r.touches[e.identifier];
            n && n.process()
        }
        delete r.touches[e.identifier]
    }, r.eachTouch = function (e, t) {
        var e = e.originalEvent, n = e.changedTouches.length;
        for (var r = 0; r < n; r++)t(e.changedTouches[r])
    }, e.fn.addSwipeEvents = function (e, t) {
        return!t && jQuery.isFunction(e) && (t = e, e = null), new r(this, e), t && this.bind("touch", t), this
    }
})(jQuery), define("doubletap", ["jquery"], function () {
}), function () {
    function e() {
        try {
            return i in n && n[i]
        } catch (e) {
            return!1
        }
    }

    var t = {}, n = window, r = n.document, i = "localStorage", s = "__storejs__", o;
    t.disabled = !1, t.set = function (e, t) {
    }, t.get = function (e) {
    }, t.remove = function (e) {
    }, t.clear = function () {
    }, t.transact = function (e, n, r) {
        var i = t.get(e);
        r == null && (r = n, n = null), typeof i == "undefined" && (i = n || {}), r(i), t.set(e, i)
    }, t.getAll = function () {
    }, t.serialize = function (e) {
        return JSON.stringify(e)
    }, t.deserialize = function (e) {
        if (typeof e != "string")return undefined;
        try {
            return JSON.parse(e)
        } catch (t) {
            return e || undefined
        }
    };
    if (e())o = n[i], t.set = function (e, n) {
        return n === undefined ? t.remove(e) : (o.setItem(e, t.serialize(n)), n)
    }, t.get = function (e) {
        return t.deserialize(o.getItem(e))
    }, t.remove = function (e) {
        o.removeItem(e)
    }, t.clear = function () {
        o.clear()
    }, t.getAll = function () {
        var e = {};
        for (var n = 0; n < o.length; ++n) {
            var r = o.key(n);
            e[r] = t.get(r)
        }
        return e
    }; else if (r.documentElement.addBehavior) {
        var u, a;
        try {
            a = new ActiveXObject("htmlfile"), a.open(), a.write('<script>document.w=window</script><iframe src="/favicon.ico"></frame>'), a.close(), u = a.w.frames[0].document, o = u.createElement("div")
        } catch (f) {
            o = r.createElement("div"), u = r.body
        }
        function l(e) {
            return function () {
                var n = Array.prototype.slice.call(arguments, 0);
                n.unshift(o), u.appendChild(o), o.addBehavior("#default#userData"), o.load(i);
                var r = e.apply(t, n);
                return u.removeChild(o), r
            }
        }

        var c = new RegExp("[!\"#$%&'()*+,/\\\\:;<=>?@[\\]^`{|}~]", "g");

        function h(e) {
            return e.replace(c, "___")
        }

        t.set = l(function (e, n, r) {
            return n = h(n), r === undefined ? t.remove(n) : (e.setAttribute(n, t.serialize(r)), e.save(i), r)
        }), t.get = l(function (e, n) {
            return n = h(n), t.deserialize(e.getAttribute(n))
        }), t.remove = l(function (e, t) {
            t = h(t), e.removeAttribute(t), e.save(i)
        }), t.clear = l(function (e) {
            var t = e.XMLDocument.documentElement.attributes;
            e.load(i);
            for (var n = 0, r; r = t[n]; n++)e.removeAttribute(r.name);
            e.save(i)
        }), t.getAll = l(function (e) {
            var n = e.XMLDocument.documentElement.attributes, r = {};
            for (var i = 0, s; s = n[i]; ++i) {
                var o = h(s.name);
                r[s.name] = t.deserialize(e.getAttribute(o))
            }
            return r
        })
    }
    try {
        t.set(s, s), t.get(s) != s && (t.disabled = !0), t.remove(s)
    } catch (f) {
        t.disabled = !0
    }
    t.enabled = !t.disabled, typeof module != "undefined" && typeof module != "function" ? module.exports = t : typeof define == "function" && define.amd ? define("store", t) : this.store = t
}(), define("lib/helpers/store-with-expire", ["store"], function (e) {
    "use strict";
    return{enabled: e.enabled, set: function (t, n, r) {
        e.set(t, {val: n, exp: r, time: (new Date).getTime()})
    }, get: function (t) {
        var n = e.get(t);
        return!n || !n.exp ? null : (new Date).getTime() - n.time > n.exp ? null : n.val
    }}
}), define("lib/helpers/form-city-search", ["jquery", "underscore", "lib/helpers/store-with-expire"], function (e, t, n) {
    "use strict";
    function s(t, n) {
        this.$ele = e(t), this.options = e.extend({}, i, n), this.init()
    }

    var r = "citySearch", i = {ajaxData: {url: "/cities", dataType: "JSON", data: {limit: 999}}};
    s.prototype.init = function () {
        if (!this.$ele.is("input"))return!1;
        n.get("citiesJSON") ? this.citiesList = n.get("citiesJSON") : e.ajax(e.extend(this.options.ajaxData, {context: this})).done(function (e) {
            n.enabled && n.set("citiesJSON", e, 3e5), this.citiesList = e
        }), window.citiesList = this.citiesList, this.link = null, this.$listContainer = this.$ele.parents(".js-city-search").find(".js-city-list"), this.$form = this.$ele.parents("form"), this.hideList(), this.$ele.on({keyup: e.proxy(this.search, this), blur: e.proxy(this.hideList, this), focus: e.proxy(this.search, this)}), this.$listContainer.on("mousedown", "a", this.clickCity), this.$form.on("submit", e.proxy(this.submitSearch, this)), this.$form.find(".js-search-button").on("click", e.proxy(this.submitSearch, this))
    }, s.prototype.search = function (e) {
        function u(e) {
            return e.replace(/[,-\/#!$%\^&\*;:{}=\-_`~()]/g, "").replace(/\s{2,}/g, " ").toLowerCase()
        }

        var n, r = this.$ele.val().toLowerCase(), i = this.$listContainer.find(".selected"), s = this.$listContainer.offset().top, o = this.$listContainer.offset().top + this.$listContainer.height();
        if (e.type === "keyup" && e.keyCode === 40) {
            var a = i.next(".js-search-item"), f = this.$listContainer.scrollTop(), l;
            a.length > 0 && (this.$ele.val(a.children("a").text()), i.removeClass("selected"), a.addClass("selected"), this.link = a.find("a").attr("href"), a.offset().top >= o && (l = a.offset().top - o + a.height() + f, this.$listContainer.scrollTop(l)))
        } else if (e.type === "keyup" && e.keyCode === 38) {
            var c = i.prev(".js-search-item"), f = this.$listContainer.scrollTop(), l;
            c.length > 0 && (this.$ele.val(c.children("a").text()), i.removeClass("selected"), c.addClass("selected"), this.link = c.find("a").attr("href"), c.offset().top < s && (l = f - (s - c.offset().top), this.$listContainer.scrollTop(l)))
        } else r.length > 0 ? (n = t.filter(this.citiesList, function (e) {
            return u(e.display_name).indexOf(u(r)) > -1
        }), this.renderCities(n)) : this.hideList()
    }, s.prototype.renderCities = function (n) {
        var r = this, i = [], s;
        this.$listContainer.empty(), n.length > 0 ? t.each(n, function (t, n) {
            var o = "/cities/" + t.slug;
            n == 0 ? (r.link = o, s = "selected") : s = "", i.push(e(document.createElement("div")).addClass("city-search-item js-search-item " + s).html(e(document.createElement("a")).attr("href", o).text(t.display_name)))
        }) : (t.each([
            {text: "No results for", wrapperClass: "search-info"},
            {text: this.$ele.val(), wrapperClass: "search-term"}
        ], function (t) {
            i.push(e(document.createElement("div")).addClass("city-search-item").append(e(document.createElement("span")).addClass(t.wrapperClass).text(t.text)))
        }), this.link = null), this.$listContainer.append(i), this.showList()
    }, s.prototype.clickCity = function (t) {
        t.preventDefault(), e(t.target).click()
    }, s.prototype.hideList = function () {
        this.$listContainer.hide()
    }, s.prototype.showList = function () {
        this.$listContainer.show()
    }, s.prototype.submitSearch = function (e) {
        e.preventDefault(), this.link && (window.location = this.link)
    }, e.fn[r] = function (t) {
        return this.each(function () {
            e.data(this, "plugin_" + r) || e.data(this, "plugin_" + r, new s(this, t))
        })
    }
}), define("lib/index", ["jquery", "doubletap", "lib/helpers/form-city-search"], function (e) {
    "use strict";
    var t = function () {
        var t, n, r, i, s, o;
        return t = function () {
            function f(n, i) {
                s = !0, n >= r.length ? n = 0 : n < 0 && (n = r.length - 1);
                var u = r.filter(".active"), a = r.eq(n), f = e(window).width() + 10;
                t.find(".active").removeClass("active");
                if (i) {
                    var l = "-" + f + "px", c = f + "px";
                    t.find("a").eq(n).addClass("active")
                } else {
                    var l = f + "px", c = "-" + f + "px";
                    t.find("a").eq(n).addClass("active")
                }
                a.css({left: l}), u.animate({left: c}, function () {
                    e(this).removeClass("active")
                }), a.addClass("active").animate({left: "0px"}, function () {
                    o = n, s = !1
                })
            }

            var t = e(".js-home-controller"), n = e(".js-home-hero"), r = n.find(".js-home-slide"), i = !0, s = !1, o = 0, u = e(".js-menu");
            u.on("click", function () {
                i = !1
            }), t.on("click", function (t) {
                t.preventDefault();
                if (s)return!1;
                var n = e(t.target).is("span") ? e(t.target).closest("a") : e(t.target);
                if (!n || n.index() === o)return!1;
                i = !1, f(n.index())
            }), n.addSwipeEvents().bind("swipe", function (e, t) {
                if (s)return!1;
                s = !0, i = !1, t.eventType === "swipeleft" ? f(o + 1) : t.eventType === "swiperight" ? f(o - 1, !0) : s = !1
            });
            var a = setInterval(function () {
                if (i === !1)return clearInterval(a), !1;
                f(r.filter(".active").index())
            }, 8e3)
        }, o = function () {
            function r(i) {
                i || (i = e(window).height()), i - 10 > n.find("img:visible").height() && (i = n.filter(".active").find("img:visible").height()), i === 0 ? setTimeout(function () {
                    r()
                }, 500) : (t.height(i - 10), n.height(i - 10))
            }

            var t = e(".js-home-hero"), n = t.find(".js-home-slide");
            e(window).resize(function () {
                r(e(this).height())
            }), r(e(window).height())
        }, n = function () {
            function r() {
                e("html, body").animate({scrollTop: e(".js-modal-gallery").offset().top - 5}, 500)
            }

            function i(e) {
                n.find(".active").removeClass("active"), e = e.length === 0 ? n.find(".modal-slide:first") : e, e.addClass("active")
            }

            var t = e(".js-story"), n = e(".js-modal-gallery");
            t.on("click", function (s) {
                s.preventDefault();
                var o = e(s.target).closest("li").attr("data-modal-name"), u = e(s.target).closest("li").index();
                i(n.find("[data-modal-slide=" + o + "]")), t.closest(".js-request-and-ride").addClass("gallery-open"), e(".js-global-container").hasClass("web") || r()
            }), e(".js-close-modal").on("click", function (e) {
                e.preventDefault(), n.find(".active").removeClass("active"), t.closest(".js-request-and-ride").removeClass("gallery-open")
            }), e(".modal-container .btn").on("click", function (t) {
                t.preventDefault();
                var r = e(t.target).hasClass("next") ? 1 : 0, s = n.find(".active").index(), o;
                r === 1 ? (o = n.find(".active").removeClass("active").next("li"), o.length === 0 && (o = n.find(".modal-slide").first())) : (o = n.find(".active").removeClass("active").prev("li"), o.length === 0 && (o = n.find(".modal-slide").last())), i(o)
            })
        }, s = function () {
            var t = e(".js-slider-bar"), n = e(".js-slider"), r = e(".js-content .js-vehicle-slide"), i = !1, s = 350, o = e(".js-vehicle-chooser .js-vehicles a").length - 1;
            t.on("mousedown touchstart",function (t) {
                i = !0;
                var r = e(this).offset(), o = t.pageX - r.left;
                return o < s + n.width() / 2 && n.css("left", o - n.width() / 2 + "px"), o <= 0 && n.css("left", -(n.width() / 2)), !1
            }).on("mousemove touchmove",function (t) {
                if (i) {
                    var r = e(this).offset(), o;
                    return t.pageX ? o = t.pageX - r.left : o = t.originalEvent.touches[0].pageX - r.left, o > s ? n.css("left", s + "px") : n.css("left", o - n.width() / 2 + "px"), !1
                }
            }).on("mouseup mouseleave touchend", function (t) {
                if (i) {
                    var u = t.pageX || t.originalEvent.changedTouches[0].pageX, a = parseInt(u - e(this).offset().left), f = s / o, l = Math.abs(a / f), c = l > 4 ? 4 : l, h = Math.round(c) * f, p = h > s ? s : h, d = e(".js-vehicles a").eq(Math.round(c)), v = d.attr("data-slide-type"), m = d.attr("data-icon-name");
                    n.css("left", p + "px").find(".car").removeClass().addClass("car " + m), r.removeClass("active").filter("[data-vehicle-slide=" + v + "]").addClass("active"), i = !1
                }
            })
        }, i = function () {
            var t = e(".js-mobile-nav");
            t.on("click", function (n) {
                n.preventDefault();
                var r = e(n.target);
                r.hasClass("js-vehicle-toggle") && r.closest("div").hasClass("active") ? t.find("> div").removeClass("active") : r.hasClass("js-vehicle-toggle") && (t.find("> div").removeClass("active"), e("html, body").animate({scrollTop: e(this).offset().top}, 500), r.closest("div").addClass("active"))
            })
        }, r = function () {
            e(document).on("keyup", function (t) {
                t.preventDefault();
                if (e(".js-request-and-ride").hasClass("gallery-open")) {
                    var n = e(".js-modal-gallery");
                    t.which === 27 ? n.find(".active").removeClass("active").closest(".js-request-and-ride").removeClass("gallery-open") : t.which === 39 ? n.find(".btn.next").click() : t.which === 37 && n.find(".btn.last").click()
                }
            })
        }, {init: function () {
            t(), o(), n(), s(), i(), r(), e(".js-city-search input").citySearch()
        }}
    }();
    return t.init()
});