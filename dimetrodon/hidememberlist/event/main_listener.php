<?php
/**
 *
 * Var Dump. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, [Dimetrodon]
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dimetrodon\hidememberlist\event;

/**
 * @ignore
 */
use phpbb\auth\auth;
use phpbb\language\language;
use phpbb\template\twig\twig;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Var Dump Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public function __construct(
		private auth $auth,
		private language $language,
		private twig $twig,
		private user $user,
		
	)
	{
	}
	
	public static function getSubscribedEvents(): array
	{
		return [
			'core.user_setup' => 'user_setup',
		];
	}



	/**
	 * Loads on user setup.
	 * Redirects users to the index page if they do not have user administrative permissions yet try to access the memberlist.
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function user_setup($event): void
	{
		// Are we in the full memberlist?
		if ($this->user->page['page'] === 'memberlist.php' )
		{
			// Does this user lack administrative user permissions? 
			if (!$this->auth->acl_gets('a_user', 'a_useradd', 'a_userdel'))
			{
				// Redirect to index.php
				redirect(append_sid("{$phpbb_root_path}index.php"));
			}
		}
		
		// Are we trying to search a user?
		if ($this->user->page['page'] === 'memberlist.php?mode=searchuser' )
		{
			// Does this user lack administrative user permissions? 
			if (!$this->auth->acl_gets('a_user', 'a_useradd', 'a_userdel'))
			{
				// Redirect to index.php
				redirect(append_sid("{$phpbb_root_path}index.php"));
			}
		}
		
		
		// This will not prevent viewing direct links to groups but this will prevent navigating to groups from a member profile.
		if ($this->user->page['page'] === 'memberlist.php?mode=group' )
		{
			// Does this user lack administrative privileges? 
			if (!$this->auth->acl_gets('a_user', 'a_useradd', 'a_userdel'))
			{
				// Redirect to index.php
				redirect(append_sid("{$phpbb_root_path}index.php"));
			}
				
			
		}
		
	}
}
