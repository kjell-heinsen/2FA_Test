<?php
use Vectorface\GoogleAuthenticator;
class maintwofactor{
    /**
     * @var GoogleAuthenticator
     */
    private GoogleAuthenticator $_GoogleAuth;
    /**
     * @var string|null
     */
    private ?string $_name = NULL;
    /**
     * @var string|null
     */
    private ?string $_secret = NULL;
    /**
     * @var string|null
     */
    private ?string $_path = NULL;
    /**
     * @var string|null
     */
    private ?string $_filename = NULL;

    public function __construct(){
        $this->_GoogleAuth = new GoogleAuthenticator();
    }

    /**
     * @param string $base64_string
     * @param string $output_file
     * @return string
     */
    private function base64_to_jpeg(string $base64_string, string $output_file):string {
        $ifp = fopen( $output_file, 'wb' );
        $data = explode( ',', $base64_string );
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp );
        return $output_file;
    }

    /**
     * @return string
     */
    public function _getPath():string {
        return $this->_path;
    }

    /**
     * @param string $path
     * @return void
     */
    public function _setPath(string $path):void {
        $this->_path = $path;
    }

    /**
     * @return string
     */
    public function _getFileName():string {
        return $this->_filename;
    }

    /**
     * @param string $path
     * @return void
     */
    public function _setFileName(string $path):void {
        $this->_filename = $path;
    }

    /**
     * @return string
     */
    public function _getName():string{
        return $this->_name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function _setName(string $name):void{
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function _getSecret():string{
        return $this->_secret;
    }

    /**
     * @param string $secret
     * @return void
     */
    public function _setSecret(string $secret):void{
        $this->_secret = $secret;
    }

    /**
     * @return void
     */
    public function CreateSecret():void{
        try{
            $this->_secret = $this->_GoogleAuth->createSecret();
        } catch (Exception $e){
            // Handle The Exception
        }
    }

    /**
     * @return void
     */
    public function CreateQRCode():void{
        if(!is_null($this->_secret) && !is_null($this->_name)){
            try {
                $this->_GoogleAuth->getQRCodeUrl($this->_name, $this->_secret);
            } catch (Exception $e){
                 // Handle the Exception
            }
        }
    }

    public function ToImage(string $qrURL):void{
        if(!is_null($this->_path) && !is_null($this->_filename)){
            $thefile = $this->_path.$this->_filename;
            self::base64_to_jpeg($qrURL, $thefile);
        } else {
          throw new Exception("Path or Filename not set");
        }

    }

    /**
     * @return string|null
     */
    public function GetCode():?string
    {
        $rtn = NULL;
        try {
            $rtn = $this->_GoogleAuth->getCode($this->_secret);
        } catch (Exception $e){
            // Handle the Exception
        }
        return $rtn;
    }

    /**
     * @param string $code
     * @return bool
     */
    public function Verify (string $code):bool{
       return $this->_GoogleAuth->verifyCode($this->_secret,$code,2);
   }




}