<?php
class Theme extends Model {
	
	var $table = 'Theme';
	private static $defaultID = 6;
	
	var $fields = array('ID','header_background','username_color','selected_tab_background','article_tab','articles_desc','selected_articles_desc','articles_desc_color');
	
	var $ID;
	var $header_background;
	var $username_color;
	var $selected_tab_background;
	var $article_tab;
	var $articles_desc;
	var $articles_desc_color;
	
	function __construct($themeID) {
		if(!$themeID) return;
		
		$results = DB::selectByID("Theme", $themeID);
		$result = $results[0];
		$this->ID = $themeID;
		$this->header_background = $result['header_background'];
		$this->username_color = $result['username_color'];
		$this->selected_tab_background = $result['selected_tab_background'];
		$this->article_tab = $result['article_tab'];
		$this->articles_desc = $result['articles_desc'];
		$this->dateAdded = $result['DateAdded'];
	}
	
	public static function getAllNames() {
		$result = DB::Query('SELECT Name FROM Theme ORDER BY ID', true);
		return $result;
	}
	
	public static function getColorPairs($themeID) {
		$results = DB::Query('SELECT header_background, username_color, selected_tab_background, article_tab, articles_desc, articles_desc_color FROM Theme WHERE ID = '.$themeID." LIMIT 1");
		return $results[0];
	}
	
	public static function getDefaultID() {
		return Theme::$defaultID;
	}
} 

?>