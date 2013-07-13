<?php

namespace Depend;
class Model_Dependcolumn extends \Orm\Model
{
//	protected static $_belongs_to = array('user');

	protected static $_properties = array(
		'id',
		'user_id',
		'level',
		'name',
		'contents',
		'comment',
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

	public static function level_updw( $type, $level )
	{
		if ( $type == 1 ) {
			$level1 = $level;
			$level2 = $level+1;
		} else {
			$level1 = $level-1;
			$level2 = $level;
		}
		$depend1 = \Depend\Model_Dependcolumn::find('all', array(
			'where' => array(
				 array( 'level', '=', $level1 )
			),
		));
		$depend2 = \Depend\Model_Dependcolumn::find('all', array(
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
