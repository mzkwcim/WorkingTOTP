<?php

class FileController extends Controller {
    public function serve($filename) {
        $filePath = __DIR__ . '/../../public/images/' . $filename;

        if (file_exists($filePath)) {
            $mimeType = mime_content_type($filePath);
            header('Content-Type: ' . $mimeType);
            readfile($filePath);
            exit;
        } else {
            http_response_code(404);
            echo "File not found.";
        }
    }
}
