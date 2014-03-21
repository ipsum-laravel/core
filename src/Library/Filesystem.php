<?php
namespace Ipsum\Core\Library;

class Filesystem extends \Illuminate\Filesystem\Filesystem
{

    /**
    * Recherche un ou plusieurs fichier correspondant à un masque et des extensions
    * @param string masque
    * @param array - extensions recherchées
    * @return  array - nom des fichiers trouvés
    */
    public function find($masque, $ext = null, $first = false)
    {
        $files_news = false;
        if (is_array($ext))
            $extension = '.{'.implode(',',$ext).'}';
        else $extension = '';
        $files = $this->glob($masque.$extension, GLOB_BRACE);
        if (empty($files))
            return false;
        return ($files and $first) ? $files[0] : $files;
    }

    /**
     * Delete all the files
     *
     * @param  string  $path
     * @return bool
     */
    public function deleteAll($masque, $ext = null)
    {
        return $this->delete($this->find($masque, $ext));
    }
}
