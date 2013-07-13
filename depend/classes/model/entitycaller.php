<?php

namespace Depend;
class Model_Entitycaller extends \Orm\Model
{
	protected static $_belongs_to = array('entity');

	protected static $_properties = array(
		'id',
		'entity_id',
		'name',
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

	public static function get_names_string( $entitycaller )
	{
		$res = '';
		foreach( $entitycaller as $val ) {
			$res .= $val->name."\r\n";
		}
		return $res;
	}

	public static function delete_by_entity( $entity_id )
	{
		$entitycaller = \Depend\Model_Entitycaller::find('all',array(
			'where' => array(
				array( 'entity_id', '=', $entity_id )
			),
		));
		foreach( $entitycaller as $val )
		{
			$val->delete();
		}
	}

	public static function regist_caller( $entity_id, $str )
	{
		// 前回の値を削除する
		\Depend\Model_Entitycaller::delete_by_entity( $entity_id );

		$entitycallers = explode("\r\n",$str);

		// 追加する
		foreach( $entitycallers as $en )
		{
			if ( $en != "" )
			{
				$e = \Depend\Model_Entitycaller::forge(array(
					'entity_id' => $entity_id,
					'name' => $en,
				));
				$e->save();
			}
		}
	}
}
