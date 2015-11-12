<?php

namespace Application\Util;

class Acl implements RoleList
{
    private $role;
    private $rights = array();

    /**
     * @param \Application\Entity\User $user
     */
    public function initialize($user)
    {
        $acl = include __DIR__ . '../../../../config/module.acl.roles.php';

        $role = !is_null($user) ? $user->getRole() : self::ROLE_INVITE;
        $this->setRole($role);

        $rights = $acl['roles'][$role];

        $this->setRights($rights);
    }

    /**
     * @param string $controller
     * @param string $action
     * @return bool
     */
    public function isAllowed($controller, $action)
    {
        foreach ($this->getRights() as $key => $ressource) {
            if ($controller == $ressource) {
                return true;
            }
            if ($controller == $key && is_array($ressource)) {
                if (in_array($action, $ressource)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @param array $rights
     */
    public function setRights($rights)
    {
        $this->rights = $rights;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }
}
