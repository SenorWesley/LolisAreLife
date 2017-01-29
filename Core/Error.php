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
 * Throwing exceptions goes via this class. Before an error can
 * be shown, it's error code and corresponding error message
 * have to registered in the $errors array, which can be
 * found in the class constructor.
 *
 * Usage example:
 *
 * [CODE]
 * Error::throwError("error.code")->fatal();
 * [/CODE]
 *
 * Class Error
 * @package LolisAreLife\Core
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @author Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */
class Error {
    /**
     * The current error
     *
     * @access private
     * @var string $currentError
     */
    private $currentError;

    /**
     * The info about the current error
     *
     * @access private
     * @var string $info
     */
    private $info;


    /**
     * Defines the error we want to throw. Also gives additional information
     * for that corresponding error.
     *
     * @access private
     * @param string $currentError: The error we want to throw
     * @param mixed $extra: additional information about the error
     */
    private function __construct($currentError, $extra = "") {

        $errors = [
            "no.dsn" => "There is one or more database credential missing. Please check your .CONFIG file, under the database section.",
            "unknown.method" => "The requested method ($extra) does not exist.",
            "unknown.route.protocol" => "The routing protocol $extra does not exist.",
            "no.log.folder" => "The /logs folder does not exist. Pls create, senpai.",
            "log.file.cant.be.made" => "Kami-sama, the following logfile could not be made: $extra",
            "no.log.mode" => "The requested log mode ($extra) does not exsist.",
            "wherein.no.array" => "The value associated with key $extra is supposed to be an array.",
            "3weeb5me" => "Anata no jiko o korosu, onii-chan"
        ];

        $this->currentError = $errors[$currentError];
    }

    /**
     * Passes through the current error and the additional info.
     *
     * @access public
     * @param string $currentError: The error we want to throw
     * @param mixed $extra: additional information about the error
     * @return $this
     */
    public static function define($currentError, $extra = "") {
        return new self($currentError, $extra);
    }

    /**
     * Throw the defined error
     *
     * @access public
     */
    public function throwError() {
        $body = str_replace(
            '&n',
            PHP_EOL,
            '<!DOCTYPE html>&n<head>&n<style>body{background:#1C1C1C;font-family:Consolas;}.mid{padding:10px;margin:100px auto; width: 800px;background:#ffffff;border:#ccc 1px solid}h1{margin:0;color:darkred}</style>&n<title>Tawagoto! KAMI SAMA!!!!</title>&n</head>&n<body>&n<div class="mid">&n' . $this->info . '</div>&n</body>&n</html>');

        die($body);
    }
}