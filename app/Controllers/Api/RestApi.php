<?php
/**
 * Action Hooks class.
 *
 * @package RT_TPG_API
 */
namespace RT\ThePostGridAPI\Controllers\Api;


class RestApi {
	/**
	 * Register rest route
	 */
	public function __construct() {
		new GetLayoutsV1();
	}
}