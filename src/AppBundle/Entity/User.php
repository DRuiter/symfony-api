<?php

namespace AppBundle\Entity;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length="254", unique=true)
     * 254 characters because http://www.rfc-editor.org/errata_search.php?rfc=3696&eid=1690
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $gender;

    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    protected $salt;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     * 128 characters for storing a SHA-512 hash
     */
    protected $passwordHash;

    /**
     * @return string
     */
    public function setPassword(String $password){
        if(!isset($this->$salt)){
            $salt = $this->generateSalt();
        } else {
            $salt = $this->$salt;
        }

        $this->passwordHash = openssl_digest($password.$salt, 'sha512');

        return $this->$passwordHash;
    }

    /**
     * @return string
     */
    public function generateSalt(){
        function generateRandomString(Integer $length) {
            $characters         = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength   = strlen($characters);
            $randomString       = '';

            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            return $randomString;
        }

        $this->$salt = generateRandomString(16);

        return $this->$salt;
    }

    public function checkPassword(String $password){
        $salt = $this->$salt;

        return openssl_digest($password.$salt, 'sha512') === $this->$passwordHash;
    }

    /**
     * @return string
     */
    public function getUrlPath(){
        return str_replace(' ', '-', strtolower($this->$title));
    }

    /**
     * @return string
     */
    public function getEmail(){
        return $this->$email;
    }

    /**
     * @return string
     */
    public function setEmail(String $email){
        $this->$email = $email;

        return $this->$email;
    }

    /**
     * @return string
     */
    public function getFirstName(){
        return $this->$firstName;
    }

    /**
     * @return string
     */
    public function setFirstName(String $firstName){
        $this->$firstName = $firstName;

        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(){
        return $this->$lastName;
    }

    /**
     * @return string
     */
    public function setLastName(String $lastName){
        $this->$lastName = $lastName;

        return $lastName;
    }

    /**
     * @return string
     */
    public function getGender(){
        return $this->$gender;
    }

    /**
     * @return string
     */
    public function setGender(String $gender){
        $enum = array('m', 'f');

        if(!in_array($gender, $enum)){
            return false;
        }

        $this->$gender = $gender;

        return $this->$gender;
    }

    /**
     * @return string
     */
    protected function getSalt(){
        return $this->$salt;
    }

    /**
     * @return string
     */
    protected function getPasswordHash(){
        return $this->$passwordHash;
    }
}
