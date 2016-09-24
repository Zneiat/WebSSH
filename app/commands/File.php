<?php
class FileCommands {
    public function cat($Path) {
        if(!is_file($Path)){
            throw new Exception("Cannot Access $Path: No Such File Or Directory");
        }
        $Path = __BasePath__ . ($Path[0] != '/' ? "/" : "") . $Path;
        $Content = file_get_contents($Path);
        return nl2br(htmlspecialchars($Content));
    }
}