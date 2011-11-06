<?php
/**
 * Initialise the plugin.
 */
function drop_unvalidated_init() {
	$period = 'hourly';
	register_plugin_hook('cron', $period, 'drop_unvalidated_cron');
}

/**
 * Cron job
 */
function drop_unvalidated_cron($hook, $entity_type, $returnvalue, $params) {
	$time_created = strtotime("-1 months");

	elgg_set_ignore_access(true);
	access_show_hidden_entities(true);
	// only delete old ones.
        $wheres = uservalidationbyemail_get_unvalidated_users_sql_where();
	$wheres[] = "e.time_created < $time_created";

	$options = array(
		'type' => 'user',
		'wheres' => $wheres,
		'limit' => 50
	);
	$entities = elgg_get_entities($options);
	foreach ($entities as $entity) {
		$entity->delete();
	}
	elgg_set_ignore_access(false);
}

// Initialise plugin
register_elgg_event_handler('init', 'system', 'drop_unvalidated_init');
