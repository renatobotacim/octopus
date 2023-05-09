<?php

/**
 * Upload.class [ HELPER ]
 * Resposável por executar upload de imagens, arquivos e mídias no sistema!
 * @copyright (c) 2017, Renato S. Botacim - Infire Soluções
 */
class Upload {

    private $File;
    private $Name;
    private $Send;

    /**
     * IMAGE ULPLOAD
     */
    private $Width;
    private $Image;

    /**
     * RESULTSET
     */
    private $Result;
    private $Error;

    /**
     * DIRETÓRIOS
     */
    private $Folder;
    private static $BaseDir;

    function __construct($BaseDir = null) {
        self::$BaseDir = ((string) $BaseDir ? $BaseDir : '../uploads/');
        if (!file_exists(self::$BaseDir) && !is_dir(self::$BaseDir)):
            mkdir(self::$BaseDir, 0777, true);
        endif;
    }

    public function Image(array $Image, $Name = null, $Width = null, $Folder = null) {
        $this->File = $Image;
        $this->Name = ((string) $Name ? $Name : substr($Image['name'], 0, strripos($Image['name'], '.')));
        $this->Width = ((int) $Width ? $Width : 1024);
        $this->Folder = ((string) $Folder ? $Folder : 'images');

        $this->CheckFolder($this->Folder);
        $this->setFileName();
        $this->UploadImage();
    }

    public function File(array $File, $Name = null, $Folder = null, $MaxFileSize = null) {
        $this->File = $File;
        $this->Name = ((string) $Name ? $Name : substr($File['name'], 0, strripos($File['name'], '.')));
        $this->Folder = ((string) $Folder ? $Folder : 'files');
        $MaxFileSize = ((int) $MaxFileSize ? $MaxFileSize : 128);

//        $FileAccept = [
//            'application/msword',
//            'application/pdf'
//        ];

        if ($this->File['size'] > ($MaxFileSize * (1024 * 1024))):
            $this->Result = false;
            $this->Error = "Arquivo muito Grande, Tamanho máximo permitido de {$MaxFileSize}mb";
        else:
            $this->CheckFolder($this->Folder);
            $this->setFileName();
            $this->MoveFile();
        endif;
    }

    public function Media(array $Media, $Name = null, $Folder = null, $MaxFileSize = null) {
        $this->File = $Media;
        $this->Name = ((string) $Name ? $Name : substr($Media['name'], 0, strripos($Media['name'], '.')));
        $this->Folder = ((string) $Folder ? $Folder : 'medias');
        $MaxFileSize = ((int) $MaxFileSize ? $MaxFileSize : 128);

        $FileAccept = [
            'audio/mp3',
            'video/mp4'
        ];

        if ($this->File['size'] > ($MaxFileSize * (1024 * 1024))):
            $this->Result = false;
            $this->Error = "Arquivo muito Grande, Tamanho máximo permitido de {$MaxFileSize}mb";
        elseif (!in_array($this->File['type'], $FileAccept)):
            $this->Result = false;
            $this->Error = "Formato Não suportado! Envie audio MP3 ou vídeo MP4!";
        else:
//            $this->CheckFolder($this->Folder);
            $this->setFileName();
            $this->MoveFile();
        endif;
    }

    public function MoveFiles($Folder) {
        $this->CheckFolder($Folder);
        return $this->Send ;
    }

    function getResult() {
        return $this->Result;
    }

    function getError() {
        return $this->Error;
    }

    //PRIVATES
    private function CheckFolder($Folder) {
        $this->CreateFolder("{$Folder}");
        $this->Send = "{$Folder}/";
    }

//    private function CheckFolder($Folder) {
//        list($y, $m) = explode('/', date('Y/m'));
//        $this->CreateFolder("{$Folder}");
//        $this->CreateFolder("{$Folder}/{$y}");
//        $this->CreateFolder("{$Folder}/{$y}/{$m}/");
//        $this->Send = "{$Folder}/{$y}/{$m}/";
//    }

    private function CreateFolder($Folder) {
        if (!file_exists(self::$BaseDir . $Folder) && !is_dir(self::$BaseDir . $Folder)):
            mkdir(self::$BaseDir . $Folder, 0777, true);
        endif;
    }

    private function setFileName() {
        $FileName = Check::Name($this->Name) . strrchr($this->File['name'], '.');
        if (file_exists(self::$BaseDir . $this->Send . $FileName)):
            $FileName = Check::Name($this->Name) . '-' . time() . strrchr($this->File['name'], '.');
        endif;
        $this->Name = $FileName;
    }

    //realiza o upload de Imagens redimencionando a mesma!
    private function UploadImage() {
        switch ($this->File['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->Image = imagecreatefromjpeg($this->File['tmp_name']);
                break;
            case 'image/png':
            case 'image/PNG':
            case 'image/x-png':
            case 'image/ppm':
                $this->Image = imagecreatefrompng($this->File['tmp_name']);
                break;
            case 'image/bm':
            case 'image/bmp':
                $this->Image = imagecreatefromwbmp($this->File['tmp_name']);
                break;
            case 'image/gif':
                $this->Image = imagecreatefromgif($this->File['tmp_name']);
                break;
        endswitch;

        if (!$this->Image):
            $this->Result = false;
            $this->Error = "Tipo de arquivo inválido, envie imagens JPG, PNG, GIF ou BMP!";
        else:
            $x = imagesx($this->Image);
            $y = imagesy($this->Image);
            $imageX = ($this->Width < $x ? $this->Width : $x);
            $imageH = ($imageX * $y) / $x;

            $NewImage = imagecreatetruecolor($imageX, $imageH);
            imagealphablending($NewImage, false);
            imagesavealpha($NewImage, true);
            imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $imageX, $imageH, $x, $y);

            switch ($this->File['type']):
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewImage, self::$BaseDir . $this->Send . $this->Name);
                    break;
                case 'image/png':
                case 'image/x-png':
                case 'image/ppm':
                    imagepng($NewImage, self::$BaseDir . $this->Send . $this->Name);
                    break;
                case 'image/bm':
                case 'image/bmp':
                    imagewbmp($NewImage, self::$BaseDir . $this->Send . $this->Name);
                    break;
                case 'image/gif':
                    imagegif($NewImage, self::$BaseDir . $this->Send . $this->Name);
                    break;
            endswitch;
            if (!$this->Image):
                $this->Result = false;
                $this->Error = "Tipo de arquivo inválido, envie imagens JPG, PNG, GIF ou BMP!";
            else:
                $this->Result = $this->Send . $this->Name;
                $this->Error = null;
            endif;

            imagedestroy($this->Image);
            imagedestroy($NewImage);
        endif;
    }

    private function MoveFile() {
        if (move_uploaded_file($this->File['tmp_name'], self::$BaseDir . $this->Send . $this->Name)):
            $this->Result = $this->Send . $this->Name;
            $this->Error = null;
        else:
            $this->Result = false;
            $this->Error = "Erro ao Mover o arquivo. Favor tente mais tarde!";
        endif;
    }

}
