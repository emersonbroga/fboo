<?php

namespace fboo;

class User
{

	const URL_FQL = 'https://graph.facebook.com/fql';
	
    private $id,$token;
    private $fields;

    public function __construct($token, $id = null)
    {

        if($token == '')
            throw new \InvalidArgumentException('You must provide a Token');

        if(!ctype_alnum($token))
            throw new \InvalidArgumentException('You must provide a valid Token');

        if(!is_null($id) && !ctype_digit($id))
            throw new \InvalidArgumentException('You must provide a valid Id');

        $this->token = $token;
        $this->id = $id ? : 'me';
        $this->fields = array();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
    
   	public function __call( $name , $value)
   	{
   		$key = strtolower(substr($name,3));
   		$value = (is_array($value)) ? current($value) : $value;
   		
   		if(stripos($name, 'set') === 0){
   			$this->fields[$key] = $value;
   		}elseif (stripos($name,'get') === 0){
   	 		return $this->fields[$key];
   	 		
   		}else{
   	 		throw new \Exception('Invalid method: '. $name );
   	 	}
   	 	
   	 }
    
   	
	public function getInfo( )
	{
		
		if(func_num_args() ==  0 )
			$fields = 'uid,name';
		else 
			$fields = implode(',', func_get_args());
			
		$userId = ($this->getId() === 'me') ? 'me()' : $this->getId();	
			
		$args = array(
            'q' => sprintf('SELECT %s FROM user WHERE uid = %s',$fields,$userId),
		 	'access_token' => $this->getToken()
        );
        
	 	return (object) array(
            'method' => 'get',
            'url' => sprintf('%s?%s', self::URL_FQL, http_build_query($args))
        );
	}
    

}
