<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infomantra\Utility;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Acl extends ZendAcl implements ServiceLocatorAwareInterface{

    const DEFAULT_ROLE = 'guest';

    protected $serviceLocator;
    protected $roles;
    protected $resources;
    protected $rolePermission;
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function init() {
        
        $this->roles = $this->_getAllRoles();
        $this->resources = $this->_getAllResources();
        $this->rolePermission = $this->_getRolePermissions();

        $this->_addRoles()
                ->_addResources()
                ->_addRolePermissions();
    }

    public function isAccessAllowed($role, $resource, $permission) {
        if (!$this->hasResource($resource)) {
            return false;
        }
        if ($this->isAllowed($role, $resource, $permission)) {
            return true;
        }
        return false;
    }

    private function _addRoles() {
        
        if (!empty($this->roles)) {
            foreach ($this->roles as $role) {
                $roleName = $role['role'];
                if (!$this->hasRole($roleName)) {
                    $this->addRole(new Role($roleName));
                }
            }
        }
        return $this;
    }

    private function _addResources() {
        if (!empty($this->resources)) {
            foreach ($this->resources as $resource) {
                if (!$this->hasResource($resource['resource'])) {
                    $this->addResource(new Resource($resource['resource']));
                }
            }
        }
        return $this;
    }

    private function _addRolePermissions() {

        if (!empty($this->rolePermission)) {
            foreach ($this->rolePermission as $rolePermissions) {
                $this->allow($rolePermissions['role'], $rolePermissions['resource'], $rolePermissions['action']);
            }
        }
        return $this;
    }

    private function _getAllRoles() {
        $roleTable = $this->getServiceLocator()->get("RoleTable");
        return $roleTable->getRecords();
    }

    private function _getAllResources() {
        $resourceTable = $this->getServiceLocator()->get("ResourceTable");
        return $resourceTable->getRecords();
    }

    private function _getRolePermissions() {
        $rolePermissionTable = $this->getServiceLocator()->get("RolePermissionTable");
        return $rolePermissionTable->getRolePermissions();
    }
}
