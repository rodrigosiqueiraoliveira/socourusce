<?php
class Esmart_DefaultSetup_Model_Role extends Mage_Core_Model_Abstract
{

	const ROLE_TYPE_GROUP = 'G';
	const ROLE_TYPE_USER = 'U';

	protected $_helper = null;

	protected function _construct()
	{
		$this->_helper = Mage::helper('core');
	}

	/**
	 * Set up the default roles for a new store
	 *
	 */
	public function setupRoles()
	{
		$roles = Mage::app()->getConfig()->getNode('default/esmart_setup/roles')->asArray();

		foreach($roles as $role) {
			if(isset($role['name'])) {
				$resources = array();
				if(isset($role['resources']['allow']['all'])) {
					$resources[] = 'all';
				} elseif (isset($role['resources']['deny'])) {
					$resources = Mage::getModel('admin/roles')->getResourcesList2D();

					/* Unset All Resources Option */
					unset($resources[0]);

					foreach($role['resources']['deny'] as $deny) {
						foreach($resources as $resource) {
							$pattern = str_replace(array('/'), array('\/'), str_replace('/*', '.*', $deny));
							
							if(preg_match('/^'.$pattern.'$/', $resource)) {
								if(($key = array_search($resource, $resources)) !== false) {
									unset($resources[$key]);
								}
							}
						}
					}

					$resources = array_values($resources);
				}

				$roleObj = $this->_createNewRole(self::ROLE_TYPE_GROUP, $role['name'], $resources);
				if($roleObj && isset($role['users']) && (count($role['users']) > 0)) {
					foreach($role['users'] as $user) {
						$user = $this->_createNewUser($user['firstname'], $user['lastname'], $user['email'], $user['username'], 'esmart', $roleObj->getRoleId());
					}
				}

				unset($resources);
			}
		}
	}


	/**
	 * Create new Administrative Role
	 * 
	 * @return Mage_Admin_Model_Roles
	 * 
	 * @param (string) $roleType
	 * @param (string) $roleName
	 * @param (array) $resources
	 * @param (int) $parentId
	 * @param (int) $userId
	 *
	 */
	protected function _createNewRole($roleType = self::ROLE_TYPE_GROUP, $roleName = null, $resources = null, $parentId = null, $userId = null)
	{
		if(is_null($roleName)) {
			return false;
		}

		$role = Mage::getModel('admin/roles');
		$role->load($roleName, 'role_name');

		if(!$role->getRoleId()) {
			$role->setName($roleName)
	             ->setRoleType($roleType);

			if($roleType == self::ROLE_TYPE_USER) {
				$role->setParentId($parentId)
					 ->setTreeLevel('2')
					 ->setUserId($userId);
			}

	        $role->save();
		}

		if($roleType == self::ROLE_TYPE_GROUP) {
			Mage::getModel('admin/rules')
	                ->setRoleId($role->getId())
	                ->setResources($resources)
	                ->saveRel();
		}

		return $role;
	}


	/**
	 * Create new Administrative Users
	 * 
	 * @return Mage_Admin_Model_User
	 * 
	 * @param (string) $firstname
	 * @param (string) $lastname
	 * @param (string) $email
	 * @param (string) $userName
	 * @param (string) $password
	 * @param (int) $roleId
	 *
	 */
	protected function _createNewUser($firstname = null, $lastname = null, $email = null, $userName = null, $password = null, $roleId = null)
	{
		if(is_null($firstname) || is_null($lastname) || is_null($email) || is_null($userName) || is_null($password)) {
			return false;
		}

		$user = Mage::getModel('admin/user')->load($email, 'email');
		if(!$user->getUserId()) {
	        $user->setFirstname($firstname)
	        	 ->setLastname($lastname)
	        	 ->setEmail($email)
	        	 ->setUsername($userName)
	        	 ->setPassword($password)
	        	 ->setIsActive(1);
		    $user->save();
		}

    	 try{
		    if(!is_null($roleId) && is_numeric($roleId)) {
		    	$this->_createNewRole(self::ROLE_TYPE_USER, $firstname.' '.$lastname, false, $roleId, $user->getId());
		    }
    	 } catch(Exception $e) {
    	 	return false;
    	 }

        return $user;
	}

}