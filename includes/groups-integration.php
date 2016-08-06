<?php

require_once( ABSPATH . 'wp-includes/pluggable.php' );

function gm_update_lists_by_capabilities($user_id, $capability_id) {
	exec( "echo \"UID = " . $user_id . "\" >> logfile.txt" );
	$groups_user = new Groups_User( $user_id );

	// Get Current Subscriptions.
	$mailing_list_subscriptions = gm_get_user_subscriptions( $user_id );

	// Get Current Mailing Lists and Loop.
	$mailing_lists = gm_get_mailing_lists();

	foreach ( $mailing_lists as $list ) {
		trigger_error( 'Checking ' . $list['name'] . '... ' );
		$subscribe = $groups_user->can( gm_get_capability_name_from_list( $list['name'] ) );
		$already_subscribed = in_array( $list['id'], $mailing_list_subscriptions );

		$gm_debug_string = gm_get_capability_name_from_list( $list['name'] ) . ' - subscribe:' . $subscribe . ' - already: '.$already_subscribed . "\n" ;
		exec( "echo \"" . $gm_debug_string . "\" >> logfile.txt" );

		if ( $subscribe and !$already_subscribed ) {
			$gm_debug_string = 'subscribe to ' . $list.['name'] . "!\n";
			exec( "echo \"" . $gm_debug_string . "\" >> logfile.txt" );
			$list_id = $list['id'];
			gm_subscribe_user_list( $list_id, $user_id );			
		} else if ( !$subscribe and $already_subscribed ) {
			$gm_debug_string = 'unsubscribe from ' . $list.['name'] . "!\n";
			exec( "echo \"" . $gm_debug_string . "\" >> logfile.txt" );
			$list_id = $list['id'];
			gm_unsubscribe_user_list( $list_id, $user_id );
		}
	}
}

function gm_get_capability_name_from_list($name) {
	$name = preg_replace ( '/[^a-z0-9 -]/i', '', $name );	// remove everything but ASCII numbers and letters or spaces and minuses
	$name = ucwords( $name, "");	// convert to CamelCase (e.g. Hi there => HiThere)
	return 'mailman_'.$name;
}

function gm_create_capability_for_new_list($list_id) {
	$list_array = gm_get_mailing_lists();
	$new_list = $list_array[$list_id];
	$list_name = $new_list['name'];
	$capability_name = gm_get_capability_name_from_list($list_name);
	
	$capability_id = Groups_Capability::create(array("capability" => $capability_name));
	$gm_debug_string = 'new list has the name ' . $list_name;
	$gm_debug_string = $gm_debug_string . ' => ' . $capability_name . ' (id: ' . $capability_id . ")!\n";
	exec( "echo \"" . $gm_debug_string . "\" >> logfile.txt" );
		
}


add_action( 'groups_created_user_capability', 'gm_update_lists_by_capabilities', 999 );
add_action( 'groups_updated_user_capability', 'gm_update_lists_by_capabilities', 999 );
add_action( 'groups_deleted_user_capability', 'gm_update_lists_by_capabilities', 999 );
add_action( 'groups_created_user_group', 'gm_update_lists_by_capabilities', 999 );
add_action( 'groups_updated_user_group', 'gm_update_lists_by_capabilities', 999 );
add_action( 'groups_deleted_user_group', 'gm_update_lists_by_capabilities', 999 );
add_action( 'gm_list_added', 'gm_create_capability_for_new_list' );


trigger_error( 'Hello' );
exec( "echo \"Hello\" >> logfile.txt" );
?>