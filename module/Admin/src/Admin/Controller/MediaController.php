<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Admin for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin\Controller;

use Zend\Session\Container;
use Admin\Plugin\UploadHandler;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class MediaController extends AbstractActionController
{

    public function indexAction()
    {
        $ckeditor = $this->params()->fromQuery("CKEditor", null);
        $target = $this->params()->fromQuery("target", '');
        $type = $this->params()->fromQuery("type", '');
        if (! empty($ckeditor) || ! empty($target)) {
            $this->layout('admin/empty');
        }
        return array(
            'target_element' => $target,
            'ckeditor' => $ckeditor,
            'type' => $type
        );
    }

    public function uploadAction()
    {
        error_reporting(E_ALL | E_STRICT);
        $uploadFolder = $this->params()->fromPost("path", "files")."/";
        $options = array(
            'script_url' => $this->url()->fromRoute('admin/default', 
                array(
                    'controller' => 'media',
                    'action' => 'delete',
                ),
                array(
                    'query' => array(
                        'path' => urlencode($uploadFolder)
                    )
                )
            ),
            'upload_dir' => _ST_PUBLIC_DIR.$uploadFolder,
            'upload_url' => _ST_ROOT_URL.$uploadFolder,
            'correct_image_extensions' => true
        );
        
        $uploadHandler = new \ST\UploadHandler($options);
        return $this->getResponse()->setContent('');
    }

    public function browserfolderAction()
    {
        // $node = $this->params()->fromPost("action","files");
        $node = $this->params()->fromQuery('id', '#');
        $node = str_replace('.', '/', $node);
        $folder = $this->scanfolder($node);
        
        $file = $this->scanfile($node);
        return new JsonModel($folder);
    }

    public function browserfileAction()
    {
        $result = array(
            'result' => false
        );
        $node = $this->params()->fromPost('id', '#');
        $node = str_replace('.', '/', $node);
        try {
            $file = $this->scanfile($node);
            $result['file'] = $file;
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
        }
        return new JsonModel($result);
    }

    public function scanfolder($node)
    {
        $tree_result = array();
        $root = array();
        if ($node == "#") {
            $root['id'] = "files";
            $root['text'] = "files";
            $root['children'] = true;
            $root['type'] = 'root';
            $tree_result[] = $root;
        } else {
            $path = str_replace('\\', '/', getcwd() . '/public/' . $node);
            $subdirs = glob($path . "/*", GLOB_ONLYDIR);
            if (count($subdirs > 0)) {
                foreach ($subdirs as $s_dir) {
                    $name_arr = explode("/", $s_dir);
                    $file = end($name_arr);
                    if ($file != '.' && $file != '..' && $file != '.svn' && $file != 'thumbnail' && $file != 'thumb') {
                        $dirlist = array();
                        $s_subdirs = glob($path . '/' . $file . "/*", GLOB_ONLYDIR);
                        if (count($s_subdirs) > 0) {
                            foreach ($s_subdirs as $name) {
                                $name_arr2 = explode("/", $name);
                                $file2 = end($name_arr2);
                                if ($file2 != 'thumbnail') {
                                    $dirlist['children'] = true;
                                }
                            }
                        }
                        $dirlist['text'] = $file;
                        $dirlist['id'] = str_replace('/', '.', $node) . '.' . $file;
                        $tree_result[] = $dirlist;
                    }
                }
            }
        }
        return $tree_result;
    }

    /**
     */
    public function scanfile($node)
    {
        $path = _ST_PUBLIC_DIR.$node."/*.*";
        $filelist = array();
        foreach (glob($path) as $file){
            $filelist[] = pathinfo($file, PATHINFO_BASENAME);
//             $filelist[] = str_replace(_ST_PUBLIC_DIR, '', $file);
        }
        return $filelist;
    }

    public function createfolderAction()
    {
        $result = array(
            'result' => false
        );
        $parent_folder = $this->params()->fromPost("id", "");
        $new_folder = $this->params()->fromPost("text", "");
        $id = $parent_folder . '.' . $new_folder;
        $new_folder = \ST\Text::rewriteName($new_folder);
        
        $parent_folder = str_replace(".", "/", $parent_folder);
        
        $path = getcwd() . '/public/';
        $new_folder_path = $path . $parent_folder . '/' . $new_folder;
        try {
            $i = 1;
            while (file_exists($new_folder_path)) {
                $new_folder_path .= "($i)";
                $i ++;
            }
            mkdir($new_folder_path, 0755, true);
            $result['result'] = true;
            $result['id'] = $id;
            $result['text'] = $new_folder;
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
        }
        return new JsonModel($result);
    }

    public function renamefolderAction()
    {
        header('Content-Type: text/json');
        $result = array(
            'result' => false
        );
        $folder = $this->params()->fromPost("id", "");
        $new_name = $this->params()->fromPost("text", "");
        $old_name = $this->params()->fromPost("old", "");
        $folder = str_replace(".", "/", $folder);
        $path = getcwd() . '/public/' . $folder;
        if (! is_dir($path)) {
            $result['message'] = 'folder was not found';
            return $this->getResponse()->setContent(json_encode($result));
        }
        $pos = strrpos($path, $old_name);
        if ($pos !== false) {
            $new_path = substr($path, 0, $pos) . $new_name;
            try {
                if (rename($path, $new_path)) {
                    $result['result'] = true;
                } else {
                    $result['message'] = 'Something wrong when rename';
                }
            } catch (\Exception $e) {
                $result['message'] = $e->getMessage();
            }
        } else {}
        return $this->getResponse()->setContent(json_encode($result));
    }

    public function deletefolderAction()
    {
        header('Content-Type: text/json');
        $result = array(
            'result' => false
        );
        $folder = $this->params()->fromPost("id", "");
        $folder = str_replace(".", "/", $folder);
        $path = getcwd() . '/public/' . $folder;
        if (! is_dir($path)) {
            $result['message'] = 'folder was not found';
            return $this->getResponse()->setContent(json_encode($result));
        } else {
            try {
                $this->rrmdir($path);
                $result['result'] = true;
            } catch (\Exception $e) {
                $result['message'] = $e->getMessage();
            }
        }
        return $this->getResponse()->setContent(json_encode($result));
    }

    public function deleteAction()
    {
        $uploadFolder = $this->params()->fromQuery("path", "files");
        $uploadFolder = urldecode($uploadFolder)."/";
        $options = array(
            'script_url' => $this->url()->fromRoute('admin/default', 
                array(
                    'controller' => 'media',
                    'action' => 'delete',
                ),
                array(
                    'query' => array(
                        'path' => urlencode($uploadFolder)
                    )
                )
            ),
            'upload_dir' => _ST_PUBLIC_DIR.$uploadFolder,
            'upload_url' => _ST_ROOT_URL.$uploadFolder,
            'correct_image_extensions' => true
        );
        
        $uploadHandler = new \ST\UploadHandler($options);
        exit('');
    }

    function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
