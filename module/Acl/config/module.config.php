<?php
namespace StAcl;
return array(
	// added for Acl   ###################################
	'controller_plugins' => array(
			'invokables' => array(
				'AclPlugin' => 'Acl\Controller\Plugin\AclPlugin',
			)
	),
		
	// end: added for Acl   ###################################	
);