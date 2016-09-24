<?php
class ClockCommands {
    public function date() {
        return date("D M y H:i:s", time());
    }
}