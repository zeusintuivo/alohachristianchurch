_.mixin({
    inherit: function(subclass, superclass) {
        /*  Set up classical inheritance for two 
         *  classes.  See classes inheriting from 
         *  JSWidget for examples.
         */
        subclass.prototype = new superclass();
        subclass.prototype.constructor = subclass;
        subclass.superclass = superclass.prototype;
        return subclass;
    },
    reject: function(obj, iterator, context) {
        /* Allow reject to be used with objects, 
         * rather than just with arrays */
        if (_.isNumber(obj.length)) {
            // default functionality for arrays
            var results = [];
            if (obj == null) return results;
            _.each(obj, function(value, index, list) {
                if (!iterator.call(context, value, index, list)) results[results.length] = value;
            });
            return results;
        } else {
            // new functionality for objects
            var results = {};
            if (obj == null) return results;
            _.each(obj, function(value, key, o) {
                if(!iterator.call(context, value, key, o)) results[key] = value;
            });
            return results;
        }
    },
    group: function(list, iterator, context) {
        return _.reduce(list, function(buckets, item) {
            var key = iterator.call(context, item);
            if (key in buckets) { buckets[key].push(item); }
            else { buckets[key] = [item]; }
            return buckets;
        }, {}, context);
    },
    /* Bundle takes an array and reduces it into an array of similar
     * elements.  Similar to group, but it maintains order.  If iterator
     * is null, it defaults to using value equality (===) to decide
     * similarity.
     *
     * Iterator should be of the form function(a,b) { return a === b; }
     *
     * Example:
     *      var a = [1,2,2,3,5,1,1,2,2,2,5,3,3];
     *      var b = _.bundle(a);
     *      // b => [[1],[2,2],[3],[5],[1,1],[2,2,2],[5],[3,3]]
     */
    bundle: function(list, iterator, context) {
        iterator = iterator || function(a,b) { return a === b; };
        return _.reduce(list, function(result, item) {
            var current_bundle = result[result.length-1];
            var sample = current_bundle[0];
            if (sample !== undefined && sample !== null) {
                if (iterator.call(context, sample, item)) {
                    current_bundle.push(item);
                } else {
                    result.push([item]);
                }
            } else { current_bundle.push(item); }
            return result;
        }, [[]]);
    },

    /* Reduce an array to only have unique values in it.  This version
     * of uniq takes a callback to decide equality
     */
    uniq2: function(list, iterator, context) {
        var found = false;
        return _.reduce(list, function(memo, el, i) {
            if (i ==0 || !_.any(memo, function(value) {
                if (iterator.call(context, value, el)) return true;
            })) { memo[memo.length] = el; }
            return memo;
        }, []);
    },
    /* Return a random element of an array.  Not super underscore-idiomatic right now
     */
    choice: function(list) {
        var i = Math.floor(Math.random() * list.length);
        return list[i];
    },
    /*  Ensure that the namespace exists
     *
     *  Module should be a function that returns an object which will be
     *  merged into the namespace defined by the first paramater: ns.
     *
     *  The function takes an optional parameter which is the namespace
     *  which it is being merged into.  This can be useful if you want
     *  to reference future children or siblings.  Be careful since they
     *  most likely wont be defined until all namespace callbacks have been
     *  executed.
     */
    namespace: function(ns, module) {
        module = module || function() { return {}; };
        var s = ns.split('.'),
            outer = window;
        _.each(s, function(level) {
            if (outer[level] === undefined) {
                outer[level] = {};
            }
            outer = outer[level];
        });
        _.extend(outer, module(outer));
    }
});

