/**
 * Adblock Detector
 * @author iMoneza
 * @author Aaron Saray
 */

/**
 * Main Module Exporter / Handler
 */
(function(root, factory) {
    if (typeof define === "function" && define.amd) { //amd
        define([root.document], factory);
    }
    else if (typeof module === "object" && module.exports) { //common js
        module.exports = factory(root.document);
    }
    else {
        root.ABD = factory(root.document);
    }
}(this, function(document) {
    /******************************************************************************************************************/
    /** Main Plugin Code **/
    /******************************************************************************************************************/

    /** default settings **/
    var defaultSettings = {
        'onBeforeStack': function() {},
        'onAfterStack': function() {},
        'onDetected': function() {},
        'onNotDetected': function() {},
        'onDone': function(adblockDetected) {},
        'strategyOptions': {}
    };

    /** current instance of detect's settings **/
    var currentSettings = {};

    /** holds the queue of strategies to check **/
    var strategiesStack = [];

    /** adblock detected? **/
    var adblockDetected = false;

    /**
     * Removes a strategy from the stack, if empty, notifies the undetected handler
     * @param strategyArray []
     */
    function removeStrategyFromStack(strategyArray) {
        var strategy = strategyArray[0]; // somehow this passes as a single value'd array.
        var index = strategiesStack.indexOf(strategy);
        if (index > -1) {
            strategiesStack.splice(index, 1);
            if (!strategiesStack.length && !adblockDetected) {
                currentSettings.onNotDetected();
                currentSettings.onDone(adblockDetected);
            }
        }
    }

    /**
     * Executes the notify detection
     */
    function notifyDetectedWithStrategy() {
        if (!adblockDetected) {
            adblockDetected = true;
            strategiesStack = [];
            currentSettings.onDetected();
            currentSettings.onDone(adblockDetected);
        }
    }

    /******************************************************************************************************************/

    /**
     * Attempts to load an external JS file
     */
    var strategyExternalJS = function(options) {
        /**
         * @var where is the stored files
         * @type {*|string}
         */
        var rootPath = options['strategyExternalJS']['rootPath'] || '';

        /**
         * @var string the path from the current document to the showads js
         */
        var filePath = rootPath + "/showads.js";

        /**
         * If loaded, means this did not find an adblocker
         */
        var onLoad = function() {
            removeStrategyFromStack(this);
        }.bind(this);

        /**
         * If error, means there is most likely an ad blocker here
         */
        function onError() {
            notifyDetectedWithStrategy();
        }

        var script  = document.createElement('script'),
            head = document.head || document.getElementsByTagName('head')[0];
        script.onload = onLoad;
        script.onerror = onError;
        script.onreadystatechange = function() {
            if (this.readyState == 4) {
                if (this.status == 404) {
                    onError();
                }
                else {
                    onLoad();
                }
            }
        };

        script.src = filePath;
        head.insertBefore(script, head.firstChild);
    };


    /******************************************************************************************************************/

    /**
     * Utility for extending an object
     * @param base {object}
     * @param incoming {object}
     * @returns {object}
     */
    function extend(base, incoming) {
        for (var key in incoming) {
            if (incoming.hasOwnProperty(key)) {
                base[key] = incoming[key];
            }
        }
        return base;
    }

    /******************************************************************************************************************/

    /**
     * Expose the detect method
     */
    return {
        detect: function(options) {
            currentSettings = extend(defaultSettings, options);

            /** load up strategies here (kept separate like this for readability) **/
            var strategies = [
                strategyExternalJS
            ];

            currentSettings.onBeforeStack();
            for (var i=0; i < strategies.length; i++) {
                strategiesStack.push(strategies[i]);
                strategies[i](currentSettings.strategyOptions);
            }
            currentSettings.onAfterStack();
        }
    };
}));