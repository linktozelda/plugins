<?php

class BOPSearchPlugin extends Omeka_Plugin_AbstractPlugin
{

protected $_hooks = array('public_items_search',
						  'items_browse_sql');

function hookPublicItemsSearch($args)
{
        $view = $args['view'];
        $db = get_db();
        $elementId = 182; //ITM Branch of Philo
        $elTextTable = $db->getTable('ElementText');
        $select = $elTextTable->getSelectForFindBy(array('element_id' => $elementId));
        $select->group('text');
        $elTexts = $elTextTable->fetchObjects($select);
        $elTextsArray = array();
        $elTextsArray[] = "Select Below";
        foreach($elTexts as $elText) {$elTextsArray[$elText->id] = $elText->text;};
        $html = "";
		$html .= "<div class='field'>";
		//build up more html 
		$html .= $view->formLabel('branch-search', __('Branch of Philosophy'));
		$html .= "<div class='inputs'>";
		$html .= $view->formSelect('branch', @$_REQUEST['branch'], array('id' => 'branch-search'), 
		 		 $elTextsArray);        
		$html .= "</div> </div>";
		echo $html;
}

public function hookItemsBrowseSql($args)
{
	$select = $args['select'];
    $params = $args['params'];
    $alias = 'branch_search';


$db = get_db();

$joinCondition = "{$alias}.record_id = items.id AND {$alias}.record_type = 'Item' AND {$alias}.element_id = $elementId";

$select->joinLeft(array($alias => $db->ElementText), $joinCondition, array());


$value = $db->quote($value);
$whereClause = " {$branch_search}.text = $value";

$select->where($whereClause);


}

}