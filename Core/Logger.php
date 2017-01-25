<?php
/**
 * Created by PhpStorm.
 * User: Wesley Peeters
 * Date: 24-1-2017
 * Time: 22:31
 */

namespace LolisAreLife\Core;


class Logger {
    protected $files = [
        "debug" => 0,
        "info" => 1,
        "warning" => 2,
        "critical" => 3,
        "fatal" => 4,
    ];
    protected $minimalLog = 0;
    protected $dateTime = "d-m-Y - H:i";
    const CHMOD = 756;
    const FOLDER = "/logs";
    protected $openLogFiles = [];

    public function __construct($message) {
        if (!is_dir(dirname(Logger::FOLDER))) {
            if (!mkdir(dirname(Logger::FOLDER, Logger::CHMOD, true))) {
                Error::throwError("no.log.folder")->fatal();
            }
        }
    }

    public static function logMessage($message) {
        return new self($message);
    }

    private function closeLogFile($level) {
        if ($this->openLogFiles[$level] != NULL) {
            fclose(Logger::FOLDER . DIRECTORY_SEPARATOR . $level . ".loli");
            $this->openLogFiles[$level] = NULL;
        }
    }

    private function createLogFile($level) {
        $this->closeLogFile($level);

        if ($this->openLogFiles[$level] = fopen(Logger::FOLDER . DIRECTORY_SEPARATOR . $level . ".loli", "a+"))
            Error::throwError("log.file.cant.be.made", $level);
    }

    public function debug() {

    }

}