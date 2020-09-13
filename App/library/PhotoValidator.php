<?php

namespace App\library;

use App\model\Photo;

class PhotoValidator extends Photo
{
    protected $errors = [];

    public function validateUploadFile(): array
    {
        if (
            !$this->issetError()
            || !$this->checkMimeType()
        ) {
            return $this->errors;
        }

        return $this->errors;
    }

    private function checkMimeType(): bool
    {
        $fileInfo = finfo_open(FILEINFO_MIME);
        $mime = finfo_file($fileInfo, $this->getFileTmpName() ?? $this->file);

        if (strpos($mime, 'image') === false) {
            $this->errors['message'] = 'Файл имеет неверный тип. Можно загружать только изображения';

            return false;
        }

        return true;
    }

    private function issetError(): bool
    {
        $fileUploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP',
            UPLOAD_ERR_FORM_SIZE => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме',
            UPLOAD_ERR_PARTIAL => 'Загружаемый файл был получен только частично',
            UPLOAD_ERR_NO_FILE => 'Файл не был загружен',
            UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка',
            UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск',
            UPLOAD_ERR_EXTENSION => 'PHP-расширение остановило загрузку файла',
        ];
        if (array_key_exists($this->getFileCodeError(), $fileUploadErrors)) {
            $this->errors['message'] = $fileUploadErrors[$this->getFileCodeError()];

            return false;
        }

        return true;
    }

}