<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Storage
 *
 * @property CI_Upload $upload
 * @property S3 $s3
 *
 * @author Norby Baruani <norbybaru@gmail.com/>
 * @link https://github.com/norbybaru/codeigneter-aws-s3
 * @version 1.0.0
 * @since 1.0.0
 */
class Storage
{
    /** @var UploadFile */
    protected $file;

    /** @var string */
    protected $storage;

    /** @var array */
    private $config;

    /** @var CI_Controller  */
    private $CI;

    /**
     * Storage constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library(array('upload','image_lib','s3'));
        
        $this->storage = config_item('app.storage');
        $this->config();

        log_message('debug', 'Storage library class loaded');
    }

    /**
     * @return string
     */
    public function getDisk()
    {
        return $this->storage;
    }

    /**
     * Initial configuration
     */
    private function config()
    {
        $config = &get_config();

        $this->config = array(
            'upload_path' => $config['app.upload_path'],
            'allowed_types' => $config['app.allowed_types'],
            'max_size' => $config['app.max_size']
        );
    }

    /**
     * Override default config
     *
     * @param array $config
     */
    public function initConfig(array $config)
    {
        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }
    }

    /**
     * @param string $field
     * @param null $name
     * @return bool|UploadFile
     * @throws Exception
     */
    public function put($field = 'file', $name = null)
    {
        $this->file = new UploadFile($_FILES[$field]);

        if (!empty($name)) {
            $this->file->customName = $name;
        }

        if ($this->storage == 's3') {
            $this->CI->s3->upload($this->file);
        }

        if ($this->storage == 'local') {
            if (isset($this->config['unique_name']) && $this->config['unique_name'] == true) {
                $this->config['file_name'] = $this->generateUniqueName() . '.' . $this->file->extension;
            }

            $this->CI->upload->initialize($this->config);
            if (!$this->CI->upload->do_upload($field)) {
                return false;
            }

            $this->data($this->CI->upload->data());
        }

        return $this->file;
    }

    /**
     * @param $filePath
     * @param null $name
     * @return UploadFile
     * @throws Exception
     */
    public function putFile($field = 'file', $name = '', $path = array())
    {
        $this->file = new UploadFile($_FILES[$field]);
            
        if ($this->storage == 's3') {
            $new_path = $path['s3'].'/';
            if (!empty($name)) {
                $this->file->customName = $new_path . $name;
            }
            $this->CI->s3->upload($this->file);
        }
        if ($this->storage == 'local') {
            $new_path = $path['local'].'/';
            //Config Upload
            $this->config['file_name'] = $name;
            $this->config['upload_path'] = './' . $new_path;
            $this->CI->upload->initialize($this->config);
            //Upload File
            if($this->CI->upload->do_upload($field)) {
                $this->data($this->CI->upload->data(), $new_path);
            }else{
                $this->CI->session->set_flashdata('notif', notif('danger', 'Peringatan', strip_tags($this->CI->upload->display_errors())));
                return $this->file;
            }
        }
        return $this->file;
    }
    
    public function putImg($field = 'file', $name = '', $path = array(), $width = 0, $ratio = FALSE, $height = 0)
    {
        $this->file = new UploadFile($_FILES[$field]);
        
        if ($this->storage == 's3') {
            $new_path = $path['s3'].'/';
            if (!empty($name)) {
                $this->file->customName = $new_path . $name;
            }
            $this->CI->s3->upload($this->file);
        }
        if ($this->storage == 'local') {
            $new_path = $path['local'].'/';
            
            $config = &get_config();
            $file = $_FILES[$field]['tmp_name'];
            if(empty($file)){
                $this->CI->session->set_flashdata('notif', notif('danger', 'Peringatan', 'File tidak dapat ditemukan'));
                return $this->file;
            }
            list($get_width, $get_height) = getimagesize($file);
            if($get_width < $width){
                $width = $get_width;
            }
            //Config Upload
            $this->config['file_name'] = $name.'-'.$get_width.'-'.$get_height;
            $this->config['upload_path'] = './' . $new_path;
            $this->config['allowed_types'] = $config['app.allowed_img'];
            $this->config['max_size'] = $config['app.max_img'];
            $this->CI->upload->initialize($this->config);
            //Upload Image
            if($this->CI->upload->do_upload($field)) {
                $upload = $this->CI->upload->data('file_name');
                //Compress Config
                $resize['image_library'] = 'gd2';
                $resize['source_image'] = './' . $new_path . $upload;
                $resize['create_thumb'] = FALSE;
                $resize['maintain_ratio'] = ($ratio) ? TRUE : FALSE;
                $resize['quality'] = '100%';
                $resize['width'] = ($width == 0) ?  $config['app.resize'] : $width;
                $resize['height'] = ($height == 0) ? $width : $height;
                $resize['new_image']= './' . $new_path . $upload;
                //Compress Image
                $this->CI->image_lib->initialize($resize);
                if($this->CI->image_lib->resize()){
                    //Return Image
                    $this->data($this->CI->upload->data(), $new_path);
                }else{
                    (is_file($new_path . $upload)) ? unlink($new_path . $upload) : '';    
                    $this->CI->session->set_flashdata('notif', notif('danger', 'Peringatan Foto', strip_tags($this->CI->image_lib->display_errors())));
                    return $this->file;
                }
            }else{
                $this->CI->session->set_flashdata('notif', notif('danger', 'Peringatan', strip_tags($this->CI->upload->display_errors())));
                return $this->file;
            }
        }
        return $this->file;
    }

    /**
     * @return UploadFile
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * Finalized Data Array
     *
     * Returns an associative array containing all of the information
     * related to the upload, allowing the developer easy access in one array.
     *
     * @param array $fileData
     */
    private function data(array $fileData, $path = null)
    {
        $this->file->name = $fileData['file_name'];
        $this->file->customName = $path.$this->file->name;
        $this->file->mime = $fileData['file_type'];
        $this->file->path = $fileData['full_path'];
        $this->file->fullPath = $fileData['full_path'];
        $this->file->rawName = $fileData['raw_name'];
        $this->file->clientName = $fileData['client_name'];
        $this->file->extension = $fileData['file_ext'];
        $this->file->size = $fileData['file_size'];
        $this->file->isImage = $fileData['is_image'];
        $this->file->imageWidth = $fileData['image_width'];
        $this->file->imageHeight = $fileData['image_height'];
        $this->file->imageSizeString = $fileData['image_size_str'];
    }

    /**
     * @param $source
     * @param $destination
     * @return bool
     */
    private function copy($source, $destination)
    {
        $destination = $this->config['upload_path'] . $destination;

        $path = pathinfo($destination);

        if (isset($this->config['unique_name']) && $this->config['unique_name'] == true) {
            $uniqueName = $this->generateUniqueName() . '.' . $path['extension'];
            $destination = $path['dirname'] . '/' . $uniqueName;
        }

        if (!file_exists($path['dirname'])) {
            mkdir($path['dirname'], 0777, true);
        }

        if (!@copy($source, $destination)) {
            if (!@move_uploaded_file($source, $destination)) {
                return FALSE;
            }
        }

        $this->file->path = $destination;
        $this->file->fullPath = $destination;

        return true;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (empty($this->file)) {
            return array();
        }

        return array(
            'file_name'		=> $this->file->name,
            'file_type'		=> $this->file->mime,
            'file_path'		=> $this->file->path,
            'full_path'		=> $this->file->fullPath,
            'raw_name'		=> $this->file->rawName,
            'client_name'	=> $this->file->clientName,
            'file_ext'		=> $this->file->extension,
            'file_size'		=> $this->file->size,
            'is_image'		=> $this->file->isImage,
            'image_width'	=> $this->file->imageWidth,
            'image_height'	=> $this->file->imageHeight,
            'image_type'	=> $this->file->mime,
            'image_size_str'	=> $this->file->imageSizeString,
        );
    }
}

/**
 * Class UploadFile
 */
final class UploadFile {

    protected $CI;

    /** @var string */
    public $customName;
    /** @var string */
    public $clientName;
    /** @var string */
    public $size;
    /** @var string */
    public $name;
    /** @var string */
    public $rawName;
    /** @var string */
    public $mime;
    /** @var string */
    public $path;
    /** @var string */
    public $fullPath;
    /** @var string */
    public $extension;
    /** @var bool*/
    public $isImage;
    /** @var string */
    public $imageWidth;
    /** @var string */
    public $imageHeight;
    /** @var string */
    public $imageSizeString;

    /** @var array  */
    private $mimeExtension = array(
        'image/jpeg' => 'jpg',
        'image/jpg' => 'jpg'
    );

    /**
     * Input constructor.
     *
     * @param $request_file
     * @throws Exception
     */
    public function __construct($request_file)
    {
        $this->CI =& get_instance();

        $this->fileInfo($request_file);
    }

    /**
     * @param $file - can be a $_FILE upload or file path
     * @throws Exception
     */
    private function fileInfo($file)
    {
        $this->CI->load->helper('string');

        //File path
        if (@is_file($file) && @file_exists($file)) {
            $pathInfo = pathinfo($file);
            $this->extension = $this->getExtension($pathInfo['basename']);

            $file_name = $this->generateUniqueName() . '.' . $this->extension;
            
            $this->name = $file_name;
            $this->clientName = $pathInfo['basename'];
            $this->path = $file;
            $this->mime = $this->getFileMimeType($file);
            $this->size = $this->getFileSize($file);
            $this->isImage = $this->isImage();
        } else {
            //File upload
            $path = $file['tmp_name'];
            $this->extension = $this->getExtension($file['name']);

            $file_name = $this->generateUniqueName() . '.' . $this->extension;

            $this->name = $file_name;
            $this->clientName = $file['name'];
            $this->path = $path;
            $this->mime = $file['type'];
            $this->size = $file['size'];
            $this->isImage = $this->isImage();
        }
        $this->convertFileSizeToKilobytes();
        $this->setImageProperties($this->path);
    }
    
    /**
     * Generate file unique name
     *
     * @return string
     */
    private function generateUniqueName()
    {
        return random_string('alnum', 8) . uniqid();
    }
    
    /**
     * @param string $mimeType
     * @return mixed
     */
    private function getMimeFileExtension($mimeType)
    {
        return $this->mimeExtension[strtolower($mimeType)];
    }

    /**
     * @param string $filename
     * @return string
     */
    private function getFileMimeType($filename)
    {
        return @mime_content_type($filename);
    }

    /**
     * @param $filename
     * @return int
     */
    private function getFileSize($filename)
    {
        return filesize($filename);
    }

    /**
     * Extract the file extension
     *
     * @param	string	$filename
     * @return	string
     */
    private function getExtension($filename)
    {
        $ext = explode('.', $filename);

        if (count($ext) === 1) {
            return '';
        }

        $ext = strtolower(end($ext));

        return $ext;
    }

    /**
     * Convert file size to kilobytes
     */
    private function convertFileSizeToKilobytes()
    {
        if ($this->size > 0) {
            $this->size = round($this->size/1024, 2);
        }
    }

    /**
     * Validate the image
     *
     * @return	bool
     */
    private function isImage()
    {
        // IE will sometimes return odd mime-types during upload, so here we just standardize all
        // jpegs or pngs to the same file type.

        $png_mimes  = array('image/x-png');
        $jpeg_mimes = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg');

        if (in_array($this->mime, $png_mimes)) {
            $this->mime = 'image/png';
        } elseif (in_array($this->mime, $jpeg_mimes)) {
            $this->mime = 'image/jpeg';
        }

        $img_mimes = array('image/gif',	'image/jpeg', 'image/png');

        return in_array($this->mime, $img_mimes, TRUE);
    }

    /**
     * Set Image Properties
     *
     * Uses GD to determine the width/height/type of image
     *
     * @param	string	$path
     */
    private function setImageProperties($path = '')
    {
        if ($this->isImage() && function_exists('getimagesize')) {
            if (FALSE !== ($D = @getimagesize($path))) {
                $this->imageWidth	= $D[0];
                $this->imageHeight	= $D[1];
                $this->imageSizeString	= $D[3]; // string containing height and width
            }
        }
    }
}