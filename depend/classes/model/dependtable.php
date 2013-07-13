<?php

namespace Depend;
class Model_Dependtable extends \Orm\Model
{
	protected static $_belongs_to = array('entity');

	protected static $_properties = array(
		'id',
		'entity_id',
		'depend_entity_id',
		'level',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);


	public static function is_exist( $depend_entity_id, $entity_id )
	{
		$dependtable = \Depend\Model_Dependtable::find('all', array(
			'where' => array(
				array( 'depend_entity_id', '=', $depend_entity_id ),
				array( 'entity_id', '=', $entity_id ),
			),
		));
		return $dependtable;
	}

	public static function level_updw( $type, $level )
	{
		if ( $type == 1 ) {
			$level1 = $level;
			$level2 = $level+1;
		} else {
			$level1 = $level-1;
			$level2 = $level;
		}
		$depend1 = \Depend\Model_Dependtable::find('all', array(
			'where' => array(
				 array( 'level', '=', $level1 )
			),
		));
		$depend2 = \Depend\Model_Dependtable::find('all', array(
			'where' => array(
				 array( 'level', '=', $level2 )
			),
		));
		foreach( $depend1 as $val )
		{
			$val->level++;
			$val->save();
		}
		foreach( $depend2 as $val )
		{
			$val->level--;
			$val->save();
		}
	}
}
