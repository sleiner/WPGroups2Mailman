<?php

require_once( ABSPATH . 'wp-includes/pluggable.php' );

class GM_Groups {
	public static function update_subscription($user_id, $capability_id) {
		$groups_user = new Groups_User( $user_id );

		// Get Current Subscriptions.
		$mailing_list_subscriptions = gm_get_user_subscriptions( $user_id );

		// Get Current Mailing Lists and Loop.
		$mailing_lists = gm_get_mailing_lists();

		foreach ( $mailing_lists as $list ) {
			trigger_error( 'Checking ' . $list['name'] . '... ' );
			$subscribe = $groups_user->can( gm_get_capability_name_from_list( $list['name'] ) );
			$already_subscribed = in_array( $list['id'], $mailing_list_subscriptions );

			if ( $subscribe and !$already_subscribed ) {
				gm_subscribe_user_list( $list['id'], $user_id );			
			} else if ( !$subscribe and $already_subscribed ) {
				gm_unsubscribe_user_list( $list['id'], $user_id );
			}
		}
	}

	public static function get_capability_name_from_list( $name ) {
		$name = preg_replace ( '/[^a-z0-9 -]/i', '', $name );	// remove everything but ASCII numbers and letters or spaces and minuses
		$name = ucwords( $name, "");	// convert to CamelCase (e.g. Hi there => HiThere)
		return 'mailman_'.$name;
	}

	public static function create_capability_for_new_list( $list_id ) {
		// get added list
		$list_array = gm_get_mailing_lists();
		$new_list = $list_array[ $list_id ];
		$list_name = $new_list[ 'name' ];
		
		// create capability
		$capability_name = GM_Groups::get_capability_name_from_list( $list_name );	
		$capability_id = Groups_Capability::create( array( "capability" => $capability_name ) );
		
		// store connection to SQL table
		global $wpdb;
		$wpdb->insert(
			GM_Groups::get_table_name(),
			array(
				'list_id' => $list_id,
				'capability_id' => $capability_id
			)
		);
	}
	
	public static function get_capability_id_from_list($list_id) {
		global $wpdb;
		$table_name = GM_Groups::get_table_name();
		$sql_query = "
			SELECT capability_id
			FROM   $table_name
			WHERE  list_id =  '$list_id'
		";
		$capability_id = $wpdb->get_var($sql_query);
		
		return $capability_id;
	}
	
	public static function update_capability_names() {
		// Get Current Mailing Lists and Loop.
		$mailing_lists = gm_get_mailing_lists();

		foreach ( $mailing_lists as $list ) {
			// get capability
			$capability_id = GM_Groups::get_capability_id_from_list($list[ 'id' ]);
			if( is_null( $capability_id ) ) {
				GM_Groups::delete_from_db( $list[ 'id' ] );
				GM_Groups::create_capability_for_new_list( $list[ 'id' ] );
			} else {
				$capability = Groups_Capability::read( $capability_id );
				$old_name = $capability->capability;
				$new_name = GM_Groups::get_capability_name_from_list( $list[ 'name' ]);
				if ($old_name != $new_name) {
					Groups_Capability::update(array(
						'capability_id' => $capability_id,
						'capability' => $new_name
					));
				}
			}
		}
	}
	
	public static function delete_from_db ( $list_id ) {
		global $wpdb;
		$wpdb->delete( GM_Groups::get_table_name(), array( 'list_id' => $list_id) );
	}
	
	public static function check_for_deleted_lists() {
		// Get Current Mailing Lists and Loop.
		$mailing_lists = gm_get_mailing_lists();
		
		// get old lists from database
		global $wpdb;
		$table_name = GM_Groups::get_table_name();
		$db_entries = $wpdb->get_col( "SELECT list_id FROM $table_name" );

		foreach ( $db_entries as $list_id ) {
			if( !in_array( $list_id, $mailing_lists[ 'id' ] ) ) {
				// delete capability
				Groups_Capability::delete( GM_Groups::get_capability_id_from_list( $list_id ) );
				// delete DB entry
				GM_Groups::delete_from_db( $list_id );
			}
		}
	}
	
	public static function get_table_name() {
		global $wpdb;
		return $wpdb->prefix . "gm_groups";
	}
	
	public static function install() {
		// Database
		global $wpdb;
		$table_name = GM_Groups::get_table_name();
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
				list_id char(32) NOT NULL,
				capability_id int,
				UNIQUE KEY list_id (list_id)
				) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}



add_action( 'groups_created_user_capability', 'GM_Groups::update_subscription', 999 );
add_action( 'groups_updated_user_capability', 'GM_Groups::update_subscription', 999 );
add_action( 'groups_deleted_user_capability', 'GM_Groups::update_subscription', 999 );
add_action( 'groups_created_user_group', 'GM_Groups::update_subscription', 999 );
add_action( 'groups_updated_user_group', 'GM_Groups::update_subscription', 999 );
add_action( 'groups_deleted_user_group', 'GM_Groups::update_subscription', 999 );
add_action( 'gm_list_added', 'GM_Groups::create_capability_for_new_list' );
add_action( 'gm_lists_updated', 'GM_Groups::update_capability_names' );
add_action( 'gm_lists_deleted', 'GM_Groups::check_for_deleted_lists' );
?>