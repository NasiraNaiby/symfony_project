<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploaderService{

    //i will pass the uploaded file object 

    //and it has to return the name of this file 

    public function __construct(private SluggerInterface $slugger){}
    public function uploadFile(
        UploadedFile $file,
        string $directoryFolder
    ){

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($directoryFolder, $newFilename);
        } catch (FileException $e) {
            // Handle exception if something happens during file upload
        }
        return $newFilename;
    }
}