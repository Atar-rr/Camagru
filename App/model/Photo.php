<?php

namespace App\model;

use App\core\Model;
use App\library\PhotoValidator;

class Photo extends Model
{

    protected $file = null;

    protected $imageType;

    protected $imageInfo;

    #TODO при создании модели, создавать поле $this->user где будет вся информация о текущем юзере
    public function __construct()
    {
        parent::__construct();
    }

    public function uploadPhoto()
    {
        $errors = [];

        $validator = new PhotoValidator();
        if (isset($_FILES['photo'])) {
            $this->file = $_FILES['photo'];
            $errors = $validator->validateUploadFile();

            if (count($errors)) {
                return $errors;
            }

            $image = file_get_contents($this->getFileTmpName());

            $this->addImageInfo($this->getFileTmpName());
            $this->addImageType();
            $name = $this->generateRandomString(5) . "." . $this->getImageType()[1];
            $image = "data:image/" . $this->getImageType()[1] . ";base64," . base64_encode($image);

            $this->savePhoto($name, $image);
        } elseif (isset($_POST['photo']) && !empty($_POST['photo'])) {
            $image = $_POST['photo'];

            $this->addImageInfo($image);
            if ($this->getImageInfo()) {
                $name = $this->generateRandomString(5) . "." .  $this->getImageType()[1];
                $this->savePhoto($name, $image);
            } else {
                $errors = ['message' => 'Файл имеет неверный тип. Можно загружать только изображения'];

                return $errors;
            }


        } else {
            $errors = ['message' => 'Файл не был загружен'];

            return $errors;
        }

        return $errors;
    }

    #TODO как переименовать это дерьмо?
    private function addImageInfo($file)
    {
        $this->imageInfo = getimagesize($file);
    }

    private function addImageType()
    {
        $this->imageType = explode('/', $this->getImageInfo()['mime']);
    }

    private function savePhoto(string $name, string $image)
    {
        $userId = $this->me();
        $this->execute
        (
            "INSERT INTO photo (user_id, name, photo) VALUES (:user_id, :name, :photo)",
            ['user_id' => $userId, 'name' => $name, 'photo' => $image]
        );
    }

    public function getImageInfo()
    {
        return $this->imageInfo;
    }

    public function getImageType()
    {
        return $this->imageType;
    }

    public function getGallery(): array
    {
        #TODO  объеденить выдачу с лайками
        $sth = $this->execute
        (
            "SELECT * FROM photo ORDER BY create_date"
        );

        return $this->makeSafeForView($sth->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getPhoto($id): array
    {
        #TODO  объеденить выдачу с лайками и комментами
        $sth = $this->execute
        (
            "SELECT * FROM photo WHERE :id=id",
            ['id' => $id]
        );

        return $this->makeSafeForView($sth->fetch(\PDO::FETCH_ASSOC));
    }

    protected function generateRandomString(int $len = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLen = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $len; $i++) {
            $randomString .= $characters[rand(0, $charactersLen - 1)];
        }

        return $randomString;
    }

    protected function getFileCodeError()
    {
        return $this->file['error'];
    }

    protected function getFileTmpName()
    {
        return $this->file['tmp_name'];
    }
}
