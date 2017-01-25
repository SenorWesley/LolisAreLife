<?php
/**
 * Created by PhpStorm.
 * User: Wesley Peeters
 * Date: 22-1-2017
 * Time: 22:22
 */

namespace LolisAreLife\Core;


class Error {
    /**
     * @var $currentError
     */
    private $currentError;

    /**
     * @var $info
     */
    private $info;


    /**
     * @param $currentError
     * @param $extra
     */
    private function __construct($currentError, $extra = "") {

        $errors = [
            "no.dsn" => "There is one or more database credential missing. Please check your .CONFIG file, under the database section.",
            "unknown.method" => "The requested method ($extra) does not exist.",
            "unknown.route.protocol" => "The routing protocol $extra does not exist.",
            "no.log.folder" => "The /logs folder does not exist. Pls create, senpai.",
            "log.file.cant.be.made" => "Kami-sama, the following logfile could not be made: $extra",
            "3weeb5me" => "Anata no jiko o korosu, onii-chan"
        ];

        $this->currentError = $errors[$currentError];
    }

    /**
     * @return $this
     */
    public static function throwError($currentError, $extra = "") {
        return new self($currentError, $extra);
    }

    /**
     *
     */
    public function fatal() {
        $body = str_replace(
            '&n',
            PHP_EOL,
            '<!DOCTYPE html>&n<head>&n<style>body{background:#1C1C1C;font-family:Consolas;}.mid{padding:10px;margin:100px auto; width: 800px;background:#ffffff;border:#ccc 1px solid}h1{margin:0;color:darkred}</style>&n<title>Tawagoto! KAMI SAMA!!!!</title>&n</head>&n<body>&n<div class="mid">&n' . $this->info . '</div>&n</body>&n</html>');

        die($body);
    }
}