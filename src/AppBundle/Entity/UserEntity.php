<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @UniqueEntity("email")
 * @ExclusionPolicy("all")
 */
class UserEntity
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=254, unique=true)
     * 254 characters because http://www.rfc-editor.org/errata_search.php?rfc=3696&eid=1690
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      max = 254,
     *      minMessage = "Your email must be at least {{ limit }} characters long",
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true,
     *     checkHost = true
     * )
     * @Expose
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2
     * )
     * @Expose
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2
     * )
     * @Expose
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\Choice({"m", "f"})
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
     * @return UserEntity
     */
    public function setPassword($password){
        if(!isset($this->salt)){
            $salt = $this->generateSalt();
        } else {
            $salt = $this->salt;
        }

        $this->passwordHash = openssl_digest($password.$salt, 'sha512');

        return $this;
    }

    /**
     * @return string
     */
    public function generateSalt(){
        function generateRandomString($length) {
            $characters         = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength   = strlen($characters);
            $randomString       = '';

            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            return $randomString;
        }

        $this->salt = generateRandomString(16);

        return $this->salt;
    }

    public function checkPassword($password){
        $salt = $this->salt;

        return openssl_digest($password.$salt, 'sha512') === $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getUrlPath(){
        return str_replace(' ', '-', strtolower($this->title));
    }

    /**
     * @return string
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * @return UserEntity
     */
    public function setEmail($email){
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(){
        return $this->firstName;
    }

    /**
     * @return UserEntity
     */
    public function setFirstName($firstName){
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(){
        return $this->lastName;
    }

    /**
     * @return UserEntity
     */
    public function setLastName($lastName){
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender(){
        return $this->gender;
    }

    /**
     * @return UserEntity
     */
    public function setGender($gender){
        $enum = array('m', 'f');

        if(!in_array($gender, $enum)){
            return false;
        }

        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    protected function getSalt(){
        return $this->salt;
    }

    /**
     * @return string
     */
    protected function getPasswordHash(){
        return $this->passwordHash;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId(){
        return $this->id;
    }
}
