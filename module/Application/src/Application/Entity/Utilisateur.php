<?php

namespace Application\Entity;

use Application\Util\RoleList;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Utilisateur
 * @package Application\Entity
 *
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\UtilisateurRepository");
 * @ORM\Table(
 *      name="user",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="email_unique",columns={"email"})}
 * )
 */
class Utilisateur implements RoleList
{
    const TOKEN_PATTERN = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="prenom", type="string", length=50)
     * @var String
     */
    protected $prenom;
    /**
     * @ORM\Column(name="nom", type="string", length=50)
     * @var String
     */
    protected $nom;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     * @var String
     */
    protected $email;

    /**
     * @ORM\Column(name="password", type="string", length=64)
     * @var String
     * info : SHA2-256 Encryption
     */
    protected $encryptedPassword;

    /**
     * @var String
     */
    protected $password;

    /**
     * @var String
     */
    protected $passwordConfirmation;

    /**
     * @ORM\Column(name="token", type="string", length=32, nullable=true)
     * @var String
     */
    protected $token;

    /**
     * @ORM\Column(name="role", type="integer")
     * @var int
     */
    protected $role;

    /**
     * @ORM\OneToMany(targetEntity="Ligue", mappedBy="createur", fetch="EXTRA_LAZY")
     * @var ArrayCollection
     */
    protected $liguesCrees;

    /**
     * @ORM\ManyToMany(targetEntity="Ligue", mappedBy="joueurs")
     * @var ArrayCollection
     **/
    protected $ligues;

    /**
     * @ORM\Column(name="disabled", type="boolean", options={"default" = 0})
     * @var bool
     */
    protected $disabled;

    /**
     * @return String
     */
    public static function getStaticTokenPattern()
    {
        return self::TOKEN_PATTERN;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Utilisateur $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return String
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param String $prenom
     * @return Utilisateur $this
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return String
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param String $nom
     * @return Utilisateur $this
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param String $email
     * @return Utilisateur $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return String
     */
    public function getEncryptedPassword()
    {
        return $this->encryptedPassword;
    }

    /**
     * @param String $encryptedPassword
     * @return Utilisateur $this
     */
    public function setEncryptedPassword($encryptedPassword)
    {
        $this->encryptedPassword = $encryptedPassword;
        return $this;
    }

    /**
     * @return String
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param String $password
     * @return Utilisateur $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return String
     */
    public function getPasswordConfirmation()
    {
        return $this->passwordConfirmation;
    }

    /**
     * @param String $passwordConfirmation
     * @return Utilisateur $this
     */
    public function setPasswordConfirmation($passwordConfirmation)
    {
        $this->passwordConfirmation = $passwordConfirmation;
        return $this;
    }

    /**
     * @return String
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param String $token
     * @return Utilisateur $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param int $role
     * @return Utilisateur $this
     */
    public function setRole($role)
    {
        if (in_array($role, array_keys(self::getStaticRoleList()))) {
            $this->role = $role;
        }
        return $this;
    }

    /**
     * @return array
     */
    public static function getStaticRoleList()
    {
        return array(
            self::ROLE_UTILISATEUR => 'Utilisateur',
            self::ROLE_ADMINISTRATEUR => 'Administrateur',
        );
    }

    /**
     * @return ArrayCollection
     */
    public function getLigues()
    {
        return $this->ligues;
    }

    /**
     * @param ArrayCollection $ligues
     * @return Utilisateur $this
     */
    public function setLigues($ligues)
    {
        $this->ligues = $ligues;
        return $this;
    }

    /**
     * @param Ligue $ligue
     * @return Utilisateur $this
     */
    public function addLigue($ligue)
    {
        if (!$this->ligues->contains($ligue)) {
            $this->ligues->add($ligue);
        }
        return $this;
    }

    /**
     * @param array $ligues
     * @return Utilisateur $this
     */
    public function addLigues($ligues)
    {
        foreach ($ligues as $ligue) {
            $this->addLigue($ligue);
        }
        return $this;
    }

    /**
     * @param Ligue $ligue
     * @return Utilisateur $this;
     */
    public function removeLigue($ligue)
    {
        $this->ligues->remove($ligue);
        return $this;
    }

    /**
     * @param array $connaisances
     * @return Utilisateur $this
     */
    public function removeLigues($connaisances)
    {
        foreach ($connaisances as $connaisance) {
            $this->removeLigue($connaisance);
        }
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLiguesCrees()
    {
        return $this->liguesCrees;
    }

    /**
     * @param ArrayCollection $liguesCrees
     * @return Utilisateur $this
     */
    public function setLiguesCrees($liguesCrees)
    {
        $this->liguesCrees = $liguesCrees;
        return $this;
    }

    /**
     * @param Ligue $ligueCree
     * @return Utilisateur $this
     */
    public function addLigueCree($ligueCree)
    {
        if (!$this->liguesCrees->contains($ligueCree)) {
            $this->liguesCrees->add($ligueCree);
        }
        return $this;
    }

    /**
     * @param array $liguesCrees
     * @return Utilisateur $this
     */
    public function addLiguesCrees($liguesCrees)
    {
        foreach ($liguesCrees as $ligueCree) {
            $this->addLigueCree($ligueCree);
        }
        return $this;
    }

    /**
     * @param Ligue $ligueCree
     * @return Utilisateur $this;
     */
    public function removeLigueCree($ligueCree)
    {
        $this->liguesCrees->remove($ligueCree);
        return $this;
    }

    /**
     * @param array $ligueCrees
     * @return Utilisateur $this
     */
    public function removeLiguesCrees($ligueCrees)
    {
        foreach ($ligueCrees as $ligueCree) {
            $this->removeLigueCree($ligueCree);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * Get disabled
     *
     * @return boolean
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return Utilisateur $this
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
        return $this;
    }
}
