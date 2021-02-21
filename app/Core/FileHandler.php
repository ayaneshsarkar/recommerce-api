<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 21/02/2021
     */

    namespace App\Core;

    /**
     * Class FileHandler
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Core
    */

    use App\Core\Application;
    use App\Helpers\Helper;
    use App\Exceptions\FileException;

    class FileHandler {

        /**
         * moveFile function
         *
         * @param array $file
         * @param string $fileDestination
         *
         * @return string
         */
        public static function moveFile(
            array $file, string $fileDestination = 'bookimages'
        ): string
        {
            $fileName = $file['name'];

            $fileNameArr = explode('.', $fileName);
            $actualFileExt = end($fileNameArr);
            $actualFileExt = strtolower($actualFileExt);

            $tmpDir = $file['tmp_name'];
            $fileError = $file['error'];

            if($fileError === 0) {
                $newName = Helper::randomString(12) . '.' . $actualFileExt;

                $finalDestination = "$fileDestination/$newName";
                move_uploaded_file($tmpDir, $finalDestination);

                return $fileDestination . '/' . $newName;
            } else {
                throw new FileException();
            }
        }

        public static function deleteFile(string $path)
        {
            if(\file_exists("$path")) {
                \unlink("$path");
            }
        }

    }