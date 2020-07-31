<?php

class ZnHgFw_PagesDataSource extends ZnHgFw_BaseDataSource {

	public $dataSourceType = 'pages';


	/**
	 * Will set the data Source Content
	 */
	function setDataSource(){
		$result = array(
			'__zn_default__' => __('Use default', 'zn_framework')
		);

		$pages = get_pages();
		if(empty($pages)){
			return $result;
		}

		foreach($pages as $page){
			$result[$page->ID] = $page->post_title;
		}
		return $result;
	}

}

return new ZnHgFw_PagesDataSource();
