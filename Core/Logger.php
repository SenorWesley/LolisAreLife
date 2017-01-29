<?php
/**
 *      _        _ _      _           _    _  __
 *     | |   ___| (_)___ /_\  _ _ ___| |  (_)/ _|___
 *     | |__/ _ \ | (_-</ _ \| '_/ -_) |__| |  _/ -_)
 *     |____\___/_|_/__/_/ \_\_| \___|____|_|_| \___|
 *
 */

namespace LolisAreLife\Core;

/**
 * This file handles all logging. Logging can be
 * done in the following 5 categories:
 * debug, info, warning, critical, fatal
 * Logs will be saved in the corresponding files.
 *
 * Usage example:
 *
 * [CODE]
 * Logger::logMessage("message to be logged")->debug();
 * Logger::logMessage("message to be logged")->info();
 * Logger::logMessage("message to be logged")->warning();
 * Logger::logMessage("message to be logged")->critical();
 * Logger::logMessage("message to be logged")->fatal();
 * [/CODE]
 *
 * Class Logger
 * @package LolisAreLife\Core
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @author Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */

class Logger {

    /**
     * The minimal log level
     *
     * @access protected
     * @var int $minimalLog
     */
    protected $minimalLog = 0;

    /**
     * the format of the date and time
     *
     * @access protected
     * @var string $dateTime
     */
    protected $dateTime = "d-m-Y - H:i";

    /**
     * The error to log
     *
     * @access private
     * @var string $error
     */
    private $error = "";

    /**
     * The log files to open
     *
     * @access protected
     * @var array $openLogFiles
     */
    protected $openLogFiles = [];

    /**
     * all available log modes
     *
     * @access protected
     * @var array $modes
     */
    protected $modes = [
        "debug",
        "info",
        "warning",
        "critical",
        "fatal",
    ];


    /**
     * Check if the log directory can be created and pass through the current error.
     *
     * @param string $message: the message to log
     */
    public function __construct($message) {
        if (!is_dir(dirname(Config::get("logger", "folder")))) {
            if (!mkdir(dirname(Config::get("logger", "folder"), Config::get("logger", "chmod"), true))) {
                Error::throwError("no.log.folder")->fatal();
            }
        }

        $this->error = $message;
    }

    /**
     * Creates a new instance of the Logger class.
     *
     * @param string $message: the message to log
     * @return Logger
     */
    public static function logMessage($message) {
        return new self($message);
    }

    /**
     * Closes the log file
     *
     * @param string $filename: The file name to close
     */
    private function closeLogFile($filename) {
        if ($this->openLogFiles[$filename] != NULL) {
            fclose(Config::get("logger", "folder") . DIRECTORY_SEPARATOR . $filename . ".loli");
            $this->openLogFiles[$filename] = NULL;
        }
    }

    /**
     * Creates a new log file
     *
     * @param string $filename: the name of the file to create
     */
    private function createLogFile($filename) {
        $this->closeLogFile($filename);

        if ($this->openLogFiles[$filename] = fopen(Config::get("logger", "folder") . DIRECTORY_SEPARATOR . $filename . ".loli", "a+"))
            Error::throwError("log.file.cant.be.made", $filename)->fatal();
    }

    /**
     * Writes the log file
     *
     * @param string $mode: the logging mode we want to use
     */
    public function __call($mode) {
        if (in_array($mode, $this->modes)) {
            $this->createLogFile($mode);
            flock($this->openLogFiles[$mode], LOCK_EX);
            fwrite($this->openLogFiles[$mode], $this->error);
            flock($this->openLogFiles[$mode], LOCK_UN);
            $this->error = "";
            $this->closeLogFile($mode);
        } else {
            Error::throwError("no.log.mode", $mode)->fatal();
        }
    }
}