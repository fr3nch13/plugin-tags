<?php 
class TagsSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $tagged = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'null' => false, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'tag_id' => array('type' => 'string', 'null' => false, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'language' => array('type' => 'string', 'null' => false, 'length' => 6, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'times_tagged' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'UNIQUE_TAGGING' => array('column' => array('model', 'foreign_key', 'tag_id', 'language'), 'unique' => 1),
			'INDEX_TAGGED' => array('column' => 'model', 'unique' => 0),
			'INDEX_LANGUAGE' => array('column' => 'language', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	public $tags = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'identifier' => array('type' => 'string', 'null' => true, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'keyname' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'weight' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

}
