function Logger(isVerbose) {
    this.init = Date.now();
    this.isVersbose = isVerbose;
}

/**
 * Write log
 *
 * @param {string} msg - log message
 * @param {string} [level=debug] - 'debug', 'error'
 */
Logger.prototype.log = function(msg, level) {
    !level && (level = 'debug');
    var runningTime = Date.now() - this.init;
    var line = '[' + level + '] ' + runningTime + ' '  + new Date().toJSON() + ' "' + msg + '"';
    if (this.isVersbose) {
        console.log(line);
    }
    /**
     * TODO: add logging to file
     */
};

exports.default = Logger;