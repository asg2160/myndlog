<?php

class CustomCSS extends Model {
	
	var $table = 'CustomCSS';
	
	var $fields = array('ID','UserID','summary_bg','article_tags_date','article_tags_font','tabs_bg','tabs_font','selected_tabs_font','selected_tabs_bg','article_font','article_bg','DateAdded');
	
	var $ID;
	var $userID;
	var $summary_bg;
	var $article_tags_date;
	var $article_tags_font;
	var $tabs_bg;
	var $tabs_font;
	var $selected_tabs_font;
	var $selected_tabs_bg;
	var $article_font;
	var $article_bg;
	var $dateAdded;
	
	function __construct($tagID) {
		if(!$tagID) return;
		
		$results = DB::selectByID("CustomCSS", $tagID);
		$result = $results[0];
		$this->ID = $tagID;
		$this->userID = $result['DateAdded'];
		$this->summary_bg = $result['summary_bg'];
		$this->article_tags_date = $result['article_tags_date'];
		$this->article_tags_font = $result['article_tags_font'];
		$this->tabs_bg = $result['tabs_bg'];
		$this->tabs_font = $result['tabs_font'];
		$this->selected_tabs_font = $result['selected_tabs_font'];
		$this->selected_tabs_bg = $result['selected_tabs_bg'];
		$this->article_font = $result['article_font'];
		$this->article_bg = $result['article_bg'];
		$this->dateAdded = $result['DateAdded'];
	}
	
	public static function getDefault() {
		return CustomCSS::getByUser(0);
	}
		
	public static function getByUser($userID) {
		if(is_null($userID)) return false;
		
		$results = DB::query("SELECT * FROM CustomCSS WHERE UserID = ".$userID);
		unset($results['0']['ID'], $results['0']['UserID'], $results['0']['DateAdded']);
		
		// '&& $userID' has been added to prevent infinite recursion just in case
		if(!count($results) && $userID) return CustomCSS::getDefault();
		
		return $results['0'];
	}
	
	public static function getIDByUser($userID) {
		if(is_null($userID)) return false;
		
		$results = DB::query('SELECT ID FROM CustomCSS WHERE UserID = '.$userID);
		$id = $results['0']['ID'];
		
		return $id;
	}
	
	public static function resetToDefault($userID) {
		if(is_null($userID)) return false;
		
		$id = CustomCSS::getIDByUser($userID);
		
		$values = CustomCSS::getDefault();
		DB::updateByID('CustomCSS',$values, $userID);
	}
} 

?>